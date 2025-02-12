<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function history()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('orderDetails.product', 'paymentMethod')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.history', compact('orders'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('profile.index')->with('error', 'Vous ne pouvez pas annuler cette commande.');
        }

        if ($order->cancel()) {
            return redirect()->route('profile.index')->with('success', 'Commande annulée et stock restauré.');
        }

        return redirect()->route('profile.index')->with('error', 'Cette commande ne peut pas être annulée.');
    }



    public function downloadInvoice(Order $order)
    {
        // Vérifier si l'utilisateur est bien propriétaire de la commande
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('orders.history')->with('error', 'Accès non autorisé à cette facture.');
        }

        // Générer un PDF avec DomPDF
        $pdf = Pdf::loadView('orders.invoice', compact('order'));

        // Retourner le PDF en téléchargement
        return $pdf->download('Facture-Commande-' . $order->id . '.pdf');
    }



}
