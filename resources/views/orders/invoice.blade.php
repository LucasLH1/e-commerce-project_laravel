<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
<h1>Facture #{{ $order->id }}</h1>
<p>Date de commande : <strong>{{ $order->created_at->format('d/m/Y') }}</strong></p>
<p>Client : <strong>{{ $order->user->name }}</strong></p>

@if($order->address)
    <p>Adresse de livraison : <strong>{{ $order->address->street }}, {{ $order->address->city }}, {{ $order->address->postal_code }}</strong></p>
@else
    <p><strong>Adresse non renseignée</strong></p>
@endif

<table>
    <thead>
    <tr>
        <th>Produit</th>
        <th>Quantité</th>
        <th>Prix unitaire</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->orderDetails as $detail)
        <tr>
            <td>{{ $detail->product->name }}</td>
            <td>{{ $detail->quantity }}</td>
            <td>{{ number_format($detail->price, 2) }} €</td>
            <td>{{ number_format($detail->quantity * $detail->price, 2) }} €</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h2>Total : <strong>{{ number_format($order->total_price, 2) }} €</strong></h2>
</body>
</html>
