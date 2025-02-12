@extends('adminlte::page')

@section('title', 'Tableau de Bord')

@section('content_header')
    <h1>📊 Tableau de Bord</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalOrders }}</h3>
                    <p>Total des Commandes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="small-box-footer">
                    Plus de détails <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $productsCount }}</h3>
                    <p>Nombre de produits</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="{{ route('admin.products.index') }}" class="small-box-footer">
                    Plus de détails <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Utilisateurs inscrits</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                    Plus de détails <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $couponsUsedCount }}</h3>
                    <p>Coupons Utilisés</p>
                </div>
                <div class="icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <a href="{{ route('admin.coupons.index') }}" class="small-box-footer">
                    Plus de détails <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">📊 Nombre de Ventes par Mois</h3>
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title">📈 Chiffre d'Affaires par Mois</h3>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $couponsUsedCount }}</h3>
                    <p>Coupons Utilisés</p>
                </div>
                <div class="icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ number_format($totalDiscountGiven, 2) }} €</h3>
                    <p>Réduction Totale Appliquée</p>
                </div>
                <div class="icon">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">
            <h3 class="card-title">🏆 Top 5 des Coupons les Plus Utilisés</h3>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Code du Coupon</th>
                    <th>Nombre d'Utilisations</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($topCoupons as $coupon)
                    <tr>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ $coupon->usage_count }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: { labels: @json($months), datasets: [{ label: 'Ventes', data: @json($salesData), backgroundColor: 'blue' }] }
        });

        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: { labels: @json($months), datasets: [{ label: 'Chiffre d\'Affaires', data: @json($revenueData), borderColor: 'green', fill: false }] }
        });
    </script>
@endsection
