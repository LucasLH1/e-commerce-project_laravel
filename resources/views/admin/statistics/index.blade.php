@extends('adminlte::page')

@section('title', 'Statistiques')

@section('content_header')
    <h1>📊 Tableau de Bord des Statistiques</h1>
@endsection

@section('content')

    <div class="accordion" id="statsAccordion">

        <div class="card">
            <div class="card-header" id="headingUsers">
                <h2 class="mb-0">
                    <button class="btn btn-primary btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseUsers">
                        👤 Activité des Utilisateurs
                    </button>
                </h2>
            </div>
            <div id="collapseUsers" class="collapse show" data-parent="#statsAccordion">
                <div class="card-body">
                    <div class="row">
                        <x-admin-card color="info" icon="fas fa-users" title="Total Utilisateurs" value="{{ $totalUsers }}" />
                        <x-admin-card color="success" icon="fas fa-user-check" title="Taux d'utilisateurs ayant déjà passés une commande" value="{{ $usersWithOrders }}%" />
                        <x-admin-card color="warning" icon="fas fa-user-times" title="Taux d'utilisateurs n'ayant jamais commandé" value="{{ $inactiveUsers }}%" />
                        <x-admin-card color="info" icon="fas fa-user-clock" title="Utilisateurs Actifs (5h)" value="{{ $activeUsers }}" />
                    </div>
                    <h5 class="mt-4">📅 Nouveaux Utilisateurs par Mois</h5>
                    <div class="chart-container">
                        <canvas id="usersRegisteredChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingProducts">
                <h2 class="mb-0">
                    <button class="btn btn-success btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseProducts">
                        📦 Performance des Produits
                    </button>
                </h2>
            </div>
            <div id="collapseProducts" class="collapse" data-parent="#statsAccordion">
                <div class="card-body">
                    <div class="row">
                        <x-admin-card color="danger" icon="fas fa-exclamation-triangle" title="Produits en Rupture" value="{{ $outOfStockProducts }}" />
                    </div>
                    <h5 class="mt-4">🏆 Top 5 Produits Vendus</h5>
                    <ul>
                        @foreach($topProducts as $product)
                            <li><strong>{{ $product->name }}</strong> - {{ $product->total_sold }} unités vendues</li>
                        @endforeach
                    </ul>
                    <h5 class="mt-4">📊 Produits Vendus par Catégorie</h5>
                    <div class="chart-container">
                        <canvas id="productsCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingOrders">
                <h2 class="mb-0">
                    <button class="btn btn-info btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOrders">
                        🛒 Analyse des Commandes
                    </button>
                </h2>
            </div>
            <div id="collapseOrders" class="collapse" data-parent="#statsAccordion">
                <div class="card-body">
                    <div class="row">
                        <x-admin-card color="primary" icon="fas fa-boxes" title="Total Commandes" value="{{ $totalOrders }}" />
                        <x-admin-card color="danger" icon="fas fa-clock" title="Commandes en Attente" value="{{ $pendingOrders }}" />
                        <x-admin-card color="success" icon="fas fa-check-circle" title="Commandes Livrées" value="{{ $deliveredOrders }}" />
                        <x-admin-card color="info" icon="fas fa-chart-line" title="Commande la Plus Chère" value="{{ number_format($highestOrder, 2) }} €" />
                    </div>
                    <h5 class="mt-4">📊 Commandes par Mois</h5>
                    <div class="chart-container">
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingCoupons">
                <h2 class="mb-0">
                    <button class="btn btn-warning btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseCoupons">
                        🎟 Coupons & Réductions
                    </button>
                </h2>
            </div>
            <div id="collapseCoupons" class="collapse" data-parent="#statsAccordion">
                <div class="card-body">
                    <div class="row">
                        <x-admin-card color="info" icon="fas fa-ticket-alt" title="Coupons Utilisés" value="{{ $couponsUsed }}" />
                        <x-admin-card color="success" icon="fas fa-percent" title="Réduction Totale" value="{{ number_format($totalDiscount, 2) }} €" />
                    </div>
                    <h5 class="mt-4">📊 Coupons Utilisés par Mois</h5>
                    <div class="chart-container">
                        <canvas id="couponUsageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('css')
    <style>
        .chart-container {
            width: 100%;
            max-width: 450px;
            height: 250px;
            margin: auto;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 📊 Graphique des Nouveaux Utilisateurs
        new Chart(document.getElementById('usersRegisteredChart'), {
            type: 'line',
            data: {
                labels: @json($usersRegisteredPerMonth->keys()),
                datasets: [{
                    label: 'Utilisateurs Inscrits',
                    data: @json($usersRegisteredPerMonth->values()),
                    borderColor: 'blue',
                    fill: false
                }]
            }
        });

        // 📊 Graphique des Commandes par Mois
        new Chart(document.getElementById('ordersChart'), {
            type: 'line',
            data: {
                labels: @json($ordersPerMonth->keys()),
                datasets: [{
                    label: 'Nombre de Commandes',
                    data: @json($ordersPerMonth->values()),
                    borderColor: 'green',
                    fill: false
                }]
            }
        });

        // 📊 Graphique des Produits par Catégorie
        new Chart(document.getElementById('productsCategoryChart'), {
            type: 'pie',
            data: {
                labels: @json($productsSoldByCategory->keys()),
                datasets: [{
                    data: @json($productsSoldByCategory->values()),
                    backgroundColor: ['blue', 'red', 'green', 'orange', 'purple']
                }]
            }
        });

        // 📊 Graphique des Coupons Utilisés
        new Chart(document.getElementById('couponUsageChart'), {
            type: 'bar',
            data: {
                labels: @json($topCoupons->keys()),
                datasets: [{
                    label: 'Utilisation',
                    data: @json($topCoupons->values()),
                    backgroundColor: 'red'
                }]
            }
        });
    </script>
@endsection
