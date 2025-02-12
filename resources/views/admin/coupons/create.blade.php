@extends('adminlte::page')

@section('title', 'Créer un Coupon')

@section('content_header')
    <h1>Créer un Coupon</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Code du Coupon</label>
                    <input type="text" name="code" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Type de Réduction</label>
                    <select name="discount_type" class="form-control" required>
                        <option value="percentage">Pourcentage (%)</option>
                        <option value="fixed">Montant (€)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Valeur de la Réduction</label>
                    <input type="number" name="discount_value" class="form-control" step="0.01" required>
                </div>

                <div class="form-group">
                    <label>Montant Minimum de Commande</label>
                    <input type="number" name="min_order_amount" class="form-control" step="0.01">
                </div>

                <div class="form-group">
                    <label>Date d'Expiration</label>
                    <input type="date" name="expiration_date" class="form-control">
                </div>

                <div class="form-group">
                    <label>Statut</label>
                    <select name="is_active" class="form-control">
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Créer</button>
            </form>
        </div>
    </div>
@endsection
