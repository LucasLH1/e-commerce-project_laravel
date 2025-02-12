@extends('adminlte::page')

@section('title', 'Détails de la Commande')

@section('content_header')
    <h1>Détails de la Commande #{{ $order->id }}</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Informations Client</h3>
                </div>
                <div class="card-body">
                    <p><strong>Nom :</strong> {{ $order->user->name }}</p>
                    <p><strong>Email :</strong> {{ $order->user->email }}</p>
                    <p><strong>Date de commande :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Adresse de Livraison</h3>
                </div>
                <div class="card-body">
                    <p><strong>Rue :</strong> {{ $order->address->street }}</p>
                    <p><strong>Ville :</strong> {{ $order->address->city }}</p>
                    <p><strong>Code Postal :</strong> {{ $order->address->postal_code }}</p>
                    <p><strong>Pays :</strong> {{ $order->address->country }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Produits Commandés</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($order->orderDetails as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price, 2) }}€</td>
                        <td>{{ number_format($detail->quantity * $detail->price, 2) }}€</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Résumé de la Commande</h3>
                </div>
                <div class="card-body">
                    <p><strong>Total :</strong> {{ number_format($order->total_price, 2) }}€</p>
                    <p><strong>Statut :</strong>
                        <span class="badge
                        @if($order->status == 'pending') badge-warning
                        @elseif($order->status == 'shipped') badge-primary
                        @elseif($order->status == 'delivered') badge-success
                        @else badge-danger @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Changer le Statut</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="status">Statut de la Commande :</label>
                            <select name="status" class="form-control">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Expédié</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livré</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
