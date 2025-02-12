<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $orders = Order::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        $roles = Role::all(); // Liste de tous les rôles disponibles

        return view('admin.users.show', compact('user', 'orders', 'roles'));
    }

    public function updateRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->syncRoles($request->roles);

        return redirect()->back()->with('success', 'Rôles mis à jour avec succès.');
    }
}


