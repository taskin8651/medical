<?php
 
// ============================================================
// FILE: app/Http/Controllers/Admin/OrderController.php
// ============================================================
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class OrderController extends Controller
{
    // ----------------------------------------------------------------
    // LIST
    // ----------------------------------------------------------------
 
    public function index(Request $request)
    {
        $query = Order::with('user')->withCount('items');
 
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('invoice_number', 'like', "%{$s}%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$s}%"));
            });
        }
 
        if ($request->filled('status'))         $query->where('status', $request->status);
        if ($request->filled('payment_status')) $query->where('payment_status', $request->payment_status);
        if ($request->filled('user_id'))        $query->where('user_id', $request->user_id);
 
        if ($request->filled('from')) $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to'))   $query->whereDate('created_at', '<=', $request->to);
 
        // Summary cards
        $summary = [
            'total'     => Order::sum('total'),
            'pending'   => Order::where('status', 'pending')->count(),
            'overdue'   => Order::overdue()->count(),
            'today'     => Order::whereDate('created_at', today())->sum('total'),
        ];
 
        $orders = $query->latest()->paginate(25)->withQueryString();
 
        return view('admin.orders.index', compact('orders', 'summary'));
    }
 
    // ----------------------------------------------------------------
    // CREATE — Admin builds order on behalf of a buyer
    // ----------------------------------------------------------------
 
    public function create()
    {
        $buyers = User::where('role', 'buyer')->where('is_active', true)->get();
        return view('admin.orders.create', compact('buyers'));
    }
 
    // ----------------------------------------------------------------
    // STORE
    // ----------------------------------------------------------------
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'             => 'required|exists:users,id',
            'buyer_gst_no'        => 'nullable|string|max:20',
            'buyer_drug_license'  => 'nullable|string|max:100',
            'billing_address'     => 'required|array',
            'billing_address.name'    => 'required|string',
            'billing_address.address' => 'required|string',
            'billing_address.city'    => 'required|string',
            'billing_address.state'   => 'required|string',
            'billing_address.pin'     => 'required|string|max:10',
            'billing_address.phone'   => 'required|string|max:15',
            'shipping_address'    => 'nullable|array',
            'payment_method'      => 'nullable|string|in:bank_transfer,cheque,cod,online,credit',
            'payment_terms'       => 'required|in:immediate,net_15,net_30,net_45',
            'dispatch_mode'       => 'nullable|string|in:courier,own_vehicle,pickup',
            'notes'               => 'nullable|string',
            'is_inter_state'      => 'boolean',
 
            'items'               => 'required|array|min:1',
            'items.*.variant_id'  => 'required|exists:product_variants,id',
            'items.*.qty'         => 'required|integer|min:1',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);
 
        DB::beginTransaction();
        try {
            // --- Build order items and compute totals ---
            $isInterState   = (bool) ($data['is_inter_state'] ?? false);
            $orderItemsData = [];
            $subtotal       = 0;
            $totalGst       = 0;
            $totalCgst      = 0;
            $totalSgst      = 0;
            $totalIgst      = 0;
 
            foreach ($data['items'] as $row) {
                $variant = ProductVariant::with('product')->findOrFail($row['variant_id']);
 
                $unitPrice       = $variant->getPriceForQty($row['qty']);
                $discountPercent = $row['discount_percent'] ?? 0;
                $gstRate         = $variant->effective_gst_rate;
 
                $gross          = $row['qty'] * $unitPrice;
                $discountAmount = round($gross * $discountPercent / 100, 2);
                $taxable        = round($gross - $discountAmount, 2);
                $gstAmount      = round($taxable * $gstRate / 100, 2);
                $cgst = $sgst = $igst = 0;
 
                if ($isInterState) {
                    $igst = $gstAmount;
                } else {
                    $cgst = round($gstAmount / 2, 2);
                    $sgst = round($gstAmount / 2, 2);
                }
 
                $lineTotal = round($taxable + $gstAmount, 2);
 
                $subtotal   += $taxable;
                $totalGst   += $gstAmount;
                $totalCgst  += $cgst;
                $totalSgst  += $sgst;
                $totalIgst  += $igst;
 
                $orderItemsData[] = [
                    'product_variant_id' => $variant->id,
                    'product_name'       => $variant->product->name,
                    'variant_name'       => $variant->name,
                    'sku'                => $variant->sku,
                    'hsn_code'           => $variant->product->hsn_code,
                    'batch_number'       => $variant->batch_number,
                    'expiry_date'        => $variant->expiry_date,
                    'qty'                => $row['qty'],
                    'mrp'                => $variant->mrp,
                    'unit_price'         => $unitPrice,
                    'discount_percent'   => $discountPercent,
                    'discount_amount'    => $discountAmount,
                    'taxable_amount'     => $taxable,
                    'gst_rate'           => $gstRate,
                    'gst_amount'         => $gstAmount,
                    'cgst'               => $cgst,
                    'sgst'               => $sgst,
                    'igst'               => $igst,
                    'total'              => $lineTotal,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            }
 
            $total = round($subtotal + $totalGst, 2);
 
            // Due date based on payment terms
            $dueDate = match ($data['payment_terms']) {
                'net_15' => now()->addDays(15)->toDateString(),
                'net_30' => now()->addDays(30)->toDateString(),
                'net_45' => now()->addDays(45)->toDateString(),
                default  => now()->toDateString(),
            };
 
            // Create order
            $order = Order::create([
                'order_number'       => Order::generateOrderNumber(),
                'user_id'            => $data['user_id'],
                'buyer_gst_no'       => $data['buyer_gst_no'] ?? null,
                'buyer_drug_license' => $data['buyer_drug_license'] ?? null,
                'billing_address'    => $data['billing_address'],
                'shipping_address'   => $data['shipping_address'] ?? null,
                'subtotal'           => $subtotal,
                'total_gst'          => $totalGst,
                'cgst'               => $totalCgst,
                'sgst'               => $totalSgst,
                'igst'               => $totalIgst,
                'discount_amount'    => 0,
                'shipping_charge'    => 0,
                'total'              => $total,
                'payment_method'     => $data['payment_method'] ?? null,
                'payment_terms'      => $data['payment_terms'],
                'payment_status'     => 'pending',
                'due_date'           => $dueDate,
                'amount_paid'        => 0,
                'status'             => 'confirmed',
                'dispatch_mode'      => $data['dispatch_mode'] ?? null,
                'notes'              => $data['notes'] ?? null,
            ]);
 
            // Bulk insert items
            $order->items()->insert(
                array_map(fn($item) => array_merge($item, ['order_id' => $order->id]), $orderItemsData)
            );
 
            // Deduct stock
            foreach ($orderItemsData as $item) {
                ProductVariant::where('id', $item['product_variant_id'])
                    ->decrement('stock', $item['qty']);
            }
 
            DB::commit();
 
            return redirect()->route('admin.orders.show', $order)
                ->with('success', "Order #{$order->order_number} created.");
 
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Order failed: ' . $e->getMessage());
        }
    }
 
    // ----------------------------------------------------------------
    // SHOW
    // ----------------------------------------------------------------
 
    public function show(Order $order)
    {
        $order->load(['user', 'items.variant.product']);
        return view('admin.orders.show', compact('order'));
    }
 
    // ----------------------------------------------------------------
    // UPDATE STATUS
    // ----------------------------------------------------------------
 
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'          => 'required|in:pending,confirmed,processing,dispatched,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:200',
            'internal_notes'  => 'nullable|string',
        ]);
 
        $updates = ['status' => $data['status']];
 
        if ($data['status'] === 'dispatched') {
            $updates['dispatched_at']   = now();
            $updates['tracking_number'] = $data['tracking_number'] ?? null;
        }
 
        if ($data['status'] === 'delivered') {
            $updates['delivered_at'] = now();
        }
 
        // On cancel: restore stock
        if ($data['status'] === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                ProductVariant::where('id', $item->product_variant_id)
                    ->increment('stock', $item->qty);
            }
            $updates['payment_status'] = 'refunded';
        }
 
        if (!empty($data['internal_notes'])) {
            $updates['internal_notes'] = $data['internal_notes'];
        }
 
        $order->update($updates);
 
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => $order->fresh()->status]);
        }
 
        return back()->with('success', 'Order status updated.');
    }
 
    // ----------------------------------------------------------------
    // RECORD PAYMENT (AJAX or POST)
    // ----------------------------------------------------------------
 
    public function recordPayment(Request $request, Order $order)
    {
        $data = $request->validate([
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:bank_transfer,cheque,cod,online,upi',
            'transaction_ref'=> 'nullable|string|max:200',
            'notes'          => 'nullable|string',
        ]);
 
        $newPaid = $order->amount_paid + $data['amount'];
 
        $paymentStatus = match (true) {
            $newPaid >= $order->total => 'paid',
            $newPaid > 0             => 'partial',
            default                  => 'pending',
        };
 
        $order->update([
            'amount_paid'    => $newPaid,
            'payment_status' => $paymentStatus,
            'payment_method' => $data['payment_method'],
        ]);
 
        // TODO: log to payment_transactions table if needed
 
        if ($request->expectsJson()) {
            return response()->json([
                'success'        => true,
                'amount_paid'    => $newPaid,
                'balance_due'    => $order->fresh()->balance_due,
                'payment_status' => $paymentStatus,
            ]);
        }
 
        return back()->with('success', "₹" . number_format($data['amount'], 2) . " payment recorded.");
    }
 
    // ----------------------------------------------------------------
    // GENERATE INVOICE
    // ----------------------------------------------------------------
 
    public function generateInvoice(Order $order)
    {
        if (!$order->invoice_number) {
            $order->update([
                'invoice_number' => Order::generateInvoiceNumber(),
                'invoice_date'   => now()->toDateString(),
            ]);
        }
 
        $order->load(['user', 'items.variant.product']);
 
        // Return a printable invoice view
        return view('admin.orders.invoice', compact('order'));
    }
 
    // ----------------------------------------------------------------
    // SUMMARY STATS (AJAX — for dashboard widgets)
    // ----------------------------------------------------------------
 
    public function stats(Request $request)
    {
        $period = $request->input('period', '30'); // days
 
        $from = now()->subDays((int) $period);
 
        $data = [
            'total_orders'   => Order::where('created_at', '>=', $from)->count(),
            'total_revenue'  => Order::where('created_at', '>=', $from)->sum('total'),
            'total_gst'      => Order::where('created_at', '>=', $from)->sum('total_gst'),
            'pending_count'  => Order::where('status', 'pending')->count(),
            'overdue_count'  => Order::overdue()->count(),
            'overdue_amount' => Order::overdue()
                ->selectRaw('SUM(total - amount_paid) as balance')
                ->value('balance') ?? 0,
            'top_buyers'     => Order::where('created_at', '>=', $from)
                ->select('user_id', DB::raw('SUM(total) as total_spent'), DB::raw('COUNT(*) as order_count'))
                ->with('user:id,name')
                ->groupBy('user_id')
                ->orderByDesc('total_spent')
                ->limit(5)
                ->get(),
        ];
 
        return response()->json($data);
    }
}