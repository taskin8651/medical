<?php
 
// ============================================================
// FILE: app/Http/Controllers/Admin/OrderController.php
// ============================================================
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
 
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
        return redirect()->route('admin.orders.manualBilling');
    }

    public function manualBilling()
    {
        $this->ensureBillingVariantsExist();

        $variants = ProductVariant::with('product')
            ->where('is_active', true)
            ->orderBy('sku')
            ->get();

        return view('admin.orders.manual-billing', compact('variants'));
    }

    public function storeManualBilling(Request $request)
    {
        $data = $request->validate([
            'customer_name'         => 'required|string|max:255',
            'customer_phone'        => 'nullable|string|max:30',
            'customer_email'        => 'nullable|email|max:255',
            'customer_address'      => 'nullable|string|max:500',
            'customer_city'         => 'nullable|string|max:100',
            'customer_state'        => 'nullable|string|max:100',
            'customer_pincode'      => 'nullable|string|max:20',
            'buyer_gst_no'          => 'nullable|string|max:20',
            'buyer_drug_license'    => 'nullable|string|max:100',
            'payment_method'        => 'required|in:cash,upi,card,bank_transfer,credit',
            'amount_paid'           => 'nullable|numeric|min:0',
            'is_inter_state'        => 'boolean',
            'notes'                 => 'nullable|string|max:1000',
            'items'                 => 'required|array|min:1',
            'items.*.variant_id'    => 'required|exists:product_variants,id',
            'items.*.qty'           => 'required|integer|min:1',
            'items.*.unit_price'    => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();

        try {
            $user = $this->resolveWalkInUser($data);
            $isInterState = (bool) ($data['is_inter_state'] ?? false);
            $subtotal = $totalGst = $totalCgst = $totalSgst = $totalIgst = 0;
            $orderItems = [];

            foreach ($data['items'] as $row) {
                $variant = ProductVariant::with('product')->findOrFail($row['variant_id']);
                $qty = (int) $row['qty'];
                $unitPrice = (float) $row['unit_price'];
                $discountPercent = (float) ($row['discount_percent'] ?? 0);
                $gstRate = (float) $variant->effective_gst_rate;

                $gross = round($qty * $unitPrice, 2);
                $discountAmount = round($gross * $discountPercent / 100, 2);
                $taxable = round($gross - $discountAmount, 2);
                $gstAmount = round($taxable * $gstRate / 100, 2);
                $cgst = $sgst = $igst = 0;

                if ($isInterState) {
                    $igst = $gstAmount;
                } else {
                    $cgst = round($gstAmount / 2, 2);
                    $sgst = round($gstAmount - $cgst, 2);
                }

                $lineTotal = round($taxable + $gstAmount, 2);

                $subtotal += $taxable;
                $totalGst += $gstAmount;
                $totalCgst += $cgst;
                $totalSgst += $sgst;
                $totalIgst += $igst;

                $orderItems[] = [
                    'product_variant_id' => $variant->id,
                    'product_name'       => $variant->product->name,
                    'variant_name'       => $variant->name,
                    'sku'                => $variant->sku,
                    'hsn_code'           => $variant->product->hsn_code,
                    'batch_number'       => $variant->batch_number,
                    'expiry_date'        => $variant->expiry_date,
                    'qty'                => $qty,
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

            $subtotal = round($subtotal, 2);
            $totalGst = round($totalGst, 2);
            $grandTotal = round($subtotal + $totalGst, 2);
            $amountPaid = min((float) ($data['amount_paid'] ?? $grandTotal), $grandTotal);
            $paymentStatus = $amountPaid >= $grandTotal ? 'paid' : ($amountPaid > 0 ? 'partial' : 'pending');

            $address = [
                'name' => $data['customer_name'],
                'email' => $data['customer_email'] ?? null,
                'phone' => $data['customer_phone'] ?? null,
                'address_1' => $data['customer_address'] ?? null,
                'city' => $data['customer_city'] ?? null,
                'state' => $data['customer_state'] ?? null,
                'postcode' => $data['customer_pincode'] ?? null,
                'country' => 'India',
            ];

            $order = Order::create([
                'order_number'       => Order::generateOrderNumber(),
                'user_id'            => $user->id,
                'buyer_gst_no'       => $data['buyer_gst_no'] ?? null,
                'buyer_drug_license' => $data['buyer_drug_license'] ?? null,
                'billing_address'    => $address,
                'shipping_address'   => $address,
                'subtotal'           => $subtotal,
                'total_gst'          => $totalGst,
                'cgst'               => round($totalCgst, 2),
                'sgst'               => round($totalSgst, 2),
                'igst'               => round($totalIgst, 2),
                'discount_amount'    => array_sum(array_column($orderItems, 'discount_amount')),
                'shipping_charge'    => 0,
                'total'              => $grandTotal,
                'payment_method'     => $data['payment_method'],
                'payment_terms'      => $data['payment_method'] === 'credit' ? 'net_15' : 'immediate',
                'payment_status'     => $paymentStatus,
                'due_date'           => $data['payment_method'] === 'credit' ? now()->addDays(15)->toDateString() : now()->toDateString(),
                'amount_paid'        => $amountPaid,
                'invoice_number'     => Order::generateInvoiceNumber(),
                'invoice_date'       => now()->toDateString(),
                'status'             => 'delivered',
                'dispatch_mode'      => 'pickup',
                'notes'              => $data['notes'] ?? 'Manual counter bill',
            ]);

            $order->items()->insert(
                array_map(fn ($item) => array_merge($item, ['order_id' => $order->id]), $orderItems)
            );

            foreach ($orderItems as $item) {
                ProductVariant::where('id', $item['product_variant_id'])
                    ->decrement('stock', $item['qty']);
            }

            DB::commit();

            return redirect()->route('admin.orders.manualBill', $order)
                ->with('success', 'Manual bill created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Manual bill failed: ' . $e->getMessage());
        }
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
    $order->load(['user', 'items']);

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

    public function manualBill(Order $order)
    {
        if (!$order->invoice_number) {
            $order->update([
                'invoice_number' => Order::generateInvoiceNumber(),
                'invoice_date'   => now()->toDateString(),
            ]);
        }

        $order->load(['user', 'items.variant.product']);
        $settings = Setting::getSettings();

        return view('admin.orders.manual-bill', compact('order', 'settings'));
    }

    private function resolveWalkInUser(array $data): User
    {
        $email = $data['customer_email'] ?? null;

        if (!$email) {
            $email = 'walkin-' . now()->format('YmdHis') . '-' . Str::lower(Str::random(5)) . '@manual.local';
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->fill([
            'name' => $data['customer_name'],
            'phone' => $data['customer_phone'] ?? null,
            'business_name' => $data['customer_name'],
            'gst_no' => $data['buyer_gst_no'] ?? null,
            'drug_license_no' => $data['buyer_drug_license'] ?? null,
            'address' => $data['customer_address'] ?? null,
            'city' => $data['customer_city'] ?? null,
            'state' => $data['customer_state'] ?? null,
            'pincode' => $data['customer_pincode'] ?? null,
            'country' => 'India',
            'approval_status' => 'approved',
        ]);

        if (!$user->exists) {
            $user->password = Str::random(16);
            $user->email_verified_at = now()->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        }

        $user->save();

        return $user;
    }

    private function ensureBillingVariantsExist(): void
    {
        Product::whereDoesntHave('variants')->chunkById(100, function ($products) {
            foreach ($products as $product) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => trim(($product->strength ? $product->strength . ' ' : '') . ($product->pack_size ?: 'Default Pack')),
                    'sku' => $this->uniqueVariantSku($product->sku ?: Str::slug($product->name)),
                    'strength' => $product->strength,
                    'pack_size' => $product->pack_size,
                    'pack_type' => $product->pack_type,
                    'batch_number' => null,
                    'expiry_date' => null,
                    'mrp' => $product->mrp ?? $product->price,
                    'ptr' => $product->ptr,
                    'pts' => $product->pts,
                    'price' => $product->sale_price ?: $product->price,
                    'gst_rate' => $product->gst_rate,
                    'stock' => $product->stock ?? 0,
                    'low_stock_alert' => 10,
                    'is_active' => true,
                ]);
            }
        });
    }

    private function uniqueVariantSku(string $baseSku): string
    {
        $baseSku = strtoupper(Str::slug($baseSku ?: 'ITEM', '-'));
        $sku = $baseSku;
        $counter = 1;

        while (ProductVariant::where('sku', $sku)->exists()) {
            $sku = $baseSku . '-' . $counter;
            $counter++;
        }

        return $sku;
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

    // ----------------------------------------------------------------
    // EDIT
    // ----------------------------------------------------------------

    public function edit(Order $order)
    {
        $order->load(['user', 'items.variant.product']);
        return view('admin.orders.edit', compact('order'));
    }

    // ----------------------------------------------------------------
    // UPDATE
    // ----------------------------------------------------------------

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update($data);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }
}
