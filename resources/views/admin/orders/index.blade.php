@extends('adminlte::page')

@section('title', 'Gestion des Commandes')

@section('content_header')
    <h1>Liste des Commandes</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Toutes les commandes</h3>
        </div>

        <div class="card-body">
            <table id="ordersTable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($order->total_price, 2) }}€</td>
                        <td>
                                <span class="badge
                                    @if($order->status == 'pending') badge-warning
                                    @elseif($order->status == 'shipped') badge-primary
                                    @elseif($order->status == 'delivered') badge-success
                                    @else badge-danger @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Détails
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            if ($.fn.DataTable) {
                var table = $('#ordersTable').DataTable({
                    "order": [[2, "desc"]],
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
                    }
                });

                // Activer la recherche en temps réel sur le champ client
                $('#searchClient').on('keyup', function () {
                    table.column(1).search(this.value).draw();
                });
            } else {
                console.error("DataTables ne s'est pas chargé correctement.");
            }
        });
    </script>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
@endsection


