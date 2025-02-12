<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code|max:50',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expiration_date' => 'nullable|date|after:today',
            'is_active' => 'required|boolean',
        ]);

        Coupon::create([
            'code' => $request->code,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'expiration_date' => $request->expiration_date,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon ajouté avec succès.');
    }


    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:50|unique:coupons,code,' . $id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expiration_date' => 'nullable|date|after:today',
            'is_active' => 'required|boolean',
        ]);

        $coupon = Coupon::findOrFail($id);
        $coupon->update([
            'code' => $request->code,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'expiration_date' => $request->expiration_date,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon mis à jour.');
    }


    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon supprimé.');
    }
}
