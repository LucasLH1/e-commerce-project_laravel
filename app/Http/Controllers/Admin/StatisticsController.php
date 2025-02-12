<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Review;
use Spatie\Permission\Models\Role;
use App\Models\PaymentMethod;
use App\Models\Session;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $usersWithOrdersNum = User::has('orders')->count();
        $usersWithOrders = round($usersWithOrdersNum * 100 / $totalUsers, 2);
        $inactiveUsersNum = $totalUsers - $usersWithOrdersNum;
        $inactiveUsers = round($inactiveUsersNum * 100 / $totalUsers, 2);
        $userRegistrations = User::select(DB::raw("COUNT(id) as count, strftime('%m-%Y', created_at) as month"))
            ->groupBy('month')
            ->pluck('count', 'month');
        $rolesDistribution = Role::with('users')->get()->mapWithKeys(function ($role) {
            return [$role->name => $role->users->count()];
        });

        $totalProducts = Product::count();
        $outOfStockProducts = Product::where('stock', 0)->count();
        $topProducts = Product::select('products.name', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', 'delivered')
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
        $productsByCategory = Category::select('categories.name', DB::raw('COUNT(products.id) as total'))
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->groupBy('categories.id')
            ->pluck('total', 'name');

        $totalOrders = Order::count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $highestOrder = Order::where('status', 'delivered')->max('total_price');
        $paymentMethods = PaymentMethod::select('name', DB::raw('COUNT(orders.id) as total'))
            ->join('orders', 'payment_methods.id', '=', 'orders.payment_method_id')
            ->groupBy('payment_methods.id')
            ->pluck('total', 'name');

        $couponsUsed = Order::whereNotNull('coupon_id')->count();
        $totalDiscount = Order::whereNotNull('coupon_id')
            ->join('coupons', 'orders.coupon_id', '=', 'coupons.id')
            ->where('orders.status', 'delivered') // On ne prend en compte que les commandes livrées
            ->get()
            ->sum(function ($order) {
                if ($order->discount_type === 'fixed') {
                    return min($order->total_price, $order->discount_value); // Remise fixe
                } elseif ($order->discount_type === 'percentage') {
                    return ($order->discount_value / 100) * $order->total_price; // Remise en pourcentage
                }
                return 0;
            });
        $topCoupons = Coupon::select('code', DB::raw('COUNT(orders.id) as usage_count'))
            ->join('orders', 'coupons.id', '=', 'orders.coupon_id')
            ->groupBy('coupons.id')
            ->orderByDesc('usage_count')
            ->limit(3)
            ->pluck('usage_count', 'code');

        $totalReviews = Review::count();
        $averageRating = Review::avg('rating');
        $topRatedProduct = Product::select('products.name', DB::raw('AVG(reviews.rating) as avg_rating'))
            ->join('reviews', 'products.id', '=', 'reviews.product_id')
            ->groupBy('products.id')
            ->orderByDesc('avg_rating')
            ->first();
        $totalTestimonials = Testimonial::count();

        $sessionsByMonth = Session::select(DB::raw("COUNT(id) as count, strftime('%m-%Y', datetime(last_activity, 'unixepoch')) as month"))
            ->groupBy('month')
            ->pluck('count', 'month');

        $activeUsers = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '<=', now()->subHours(5)->timestamp)
            ->distinct()
            ->count('user_id');

        $usersRegisteredPerMonth = User::select(DB::raw("COUNT(id) as count, strftime('%m-%Y', created_at) as month"))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->pluck('count', 'month');

        $ordersPerMonth = Order::select(DB::raw("COUNT(id) as count, strftime('%m-%Y', created_at) as month"))
            ->where('status', 'delivered')
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->pluck('count', 'month');

        $productsSoldByCategory = Category::select('categories.name', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', 'delivered')
            ->groupBy('categories.id')
            ->pluck('total_sold', 'name');


        return view('admin.statistics.index', compact(
            'totalUsers', 'usersWithOrders', 'inactiveUsers', 'userRegistrations', 'rolesDistribution',
            'totalProducts', 'outOfStockProducts', 'topProducts', 'productsByCategory',
            'totalOrders', 'deliveredOrders', 'pendingOrders', 'highestOrder', 'paymentMethods',
            'couponsUsed', 'totalDiscount', 'topCoupons',
            'totalReviews', 'averageRating', 'topRatedProduct', 'totalTestimonials',
            'activeUsers', 'sessionsByMonth','productsSoldByCategory', 'ordersPerMonth', 'usersRegisteredPerMonth'
        ));
    }
}
