<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'delivered')->sum('total_price');
        $totalUsers = User::count();
        $productsCount = Product::count();

        $couponsUsedCount = Order::whereNotNull('coupon_id')->count();

        $totalDiscountGiven = 0;
        $ordersWithCoupons = Order::whereNotNull('coupon_id')->where('status', 'delivered')->get();

        foreach ($ordersWithCoupons as $order) {
            $coupon = Coupon::find($order->coupon_id);

            if ($coupon) {
                if ($coupon->discount_type === 'fixed') {
                    $totalDiscountGiven += $coupon->discount_value;
                } elseif ($coupon->discount_type === 'percentage') {
                    $initialPrice = $order->total_price / (1 - ($coupon->discount_value / 100));
                    $discountAmount = $initialPrice - $order->total_price;
                    $totalDiscountGiven += $discountAmount;
                }
            }
        }

        $topCoupons = Coupon::select('coupons.code')
            ->join('orders', 'coupons.id', '=', 'orders.coupon_id')
            ->selectRaw('COUNT(orders.id) as usage_count')
            ->groupBy('coupons.code')
            ->orderByDesc('usage_count')
            ->limit(5)
            ->get();

        $salesData = [];
        $revenueData = [];
        $months = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $months[] = Carbon::now()->subMonths($i)->format('M Y');

            $salesData[] = Order::where('status', 'delivered')
                ->whereYear('created_at', Carbon::now()->subMonths($i)->year)
                ->whereMonth('created_at', Carbon::now()->subMonths($i)->month)
                ->count();

            $revenueData[] = Order::where('status', 'delivered')
                ->whereYear('created_at', Carbon::now()->subMonths($i)->year)
                ->whereMonth('created_at', Carbon::now()->subMonths($i)->month)
                ->sum('total_price');
        }

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalUsers',
            'productsCount',
            'couponsUsedCount',
            'totalDiscountGiven',
            'topCoupons',
            'salesData',
            'revenueData',
            'months'
        ));
    }
}




