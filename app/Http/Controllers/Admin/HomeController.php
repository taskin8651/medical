<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController
{
    public function index()
    {
        $today = today();
        $monthStart = now()->startOfMonth();

        $orderBase = Order::query();
        $todayOrders = (clone $orderBase)->whereDate('created_at', $today);
        $monthOrders = (clone $orderBase)->where('created_at', '>=', $monthStart);
        $overdueOrders = Order::overdue();

        $summary = [
            'today_orders' => (clone $todayOrders)->count(),
            'today_billing' => (clone $todayOrders)->sum('total'),
            'today_paid' => (clone $todayOrders)->sum('amount_paid'),
            'month_orders' => (clone $monthOrders)->count(),
            'month_billing' => (clone $monthOrders)->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'payment_pending' => Order::where('payment_status', '!=', 'paid')->count(),
            'due_amount' => Order::where('payment_status', '!=', 'paid')->sum(DB::raw('total - amount_paid')),
            'overdue_count' => (clone $overdueOrders)->count(),
            'overdue_amount' => (clone $overdueOrders)->sum(DB::raw('total - amount_paid')),
            'active_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => Product::where('is_active', true)->where('stock', '<=', 10)->count(),
            'approved_buyers' => User::where('approval_status', 'approved')->count(),
        ];

        $recentOrders = Order::with('user')
            ->withCount('items')
            ->latest()
            ->take(8)
            ->get();

        $pendingPayments = Order::with('user')
            ->where('payment_status', '!=', 'paid')
            ->orderByRaw('(total - amount_paid) desc')
            ->take(6)
            ->get();

        $statusCounts = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $paymentCounts = Order::select('payment_status', DB::raw('COUNT(*) as total'))
            ->groupBy('payment_status')
            ->pluck('total', 'payment_status');

        return view('home', compact(
            'summary',
            'recentOrders',
            'pendingPayments',
            'statusCounts',
            'paymentCounts'
        ));
    }
}
