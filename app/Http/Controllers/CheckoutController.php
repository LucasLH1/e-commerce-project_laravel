<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = session()->get('cart_total', 0);
        $user = auth()->user();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        $activeAddress = $user->addresses()->where('is_active', true)->first();

        return view('checkout.index', compact('cart', 'total', 'activeAddress','paymentMethods'));
    }


    public function processPayment(Request $request)
    {
        //dd($request->all());

        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $user = auth()->user();
        $cart = session()->get('cart', []);
        $quickProductId = $request->input('quick_product_id');
        $paymentMethod = PaymentMethod::find($validated['payment_method_id']);
        $couponId = session()->has('applied_coupon') ? session('applied_coupon')['id'] : null;

        //dd($couponId);
        //dd($quickProductId);

        if (!$paymentMethod) {
            return redirect()->route('checkout.index')->with('error', 'Méthode de paiement invalide.');
        }

        if ($quickProductId) {
            $product = Product::find($quickProductId);

            if (!$product || !$product->isInStock(1)) {
                return redirect()->route('products.show', $quickProductId)
                    ->with('error', 'Ce produit est en rupture de stock.');
            }

            $total = $product->price;

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'payment_method_id' => $paymentMethod->id,
                'status' => 'pending',
                'address_id' => auth()->user()->addresses()->where('is_active', true)->first()?->id,
            ]);

            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);

            $product->decrement('stock', 1);

        } else {
            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
            }

            $total = session()->get('cart_total', 0);
            //dd($couponId);

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'payment_method_id' => $paymentMethod->id,
                'coupon_id' => $couponId,
                'status' => 'pending',
                'address_id' => auth()->user()->addresses()->where('is_active', true)->first()?->id,
            ]);

            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                if ($product && $product->isInStock($item['quantity'])) {
                    $product->decrement('stock', $item['quantity']);
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }
            }

            session()->forget('applied_coupon');
            session()->forget('cart');
        }

        return redirect()->route('checkout.success', ['order' => $order->id]);
    }



    public function quickBuy(Product $product)
    {
        $paymentMethods = PaymentMethod::all();

        $selectedProduct = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'image' => $product->images->first()->image_path ?? '/images/default.jpg'
        ];

        return view('checkout.index', [
            'quickProduct' => $selectedProduct,
            'paymentMethods' => $paymentMethods
        ]);
    }



    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }


}

