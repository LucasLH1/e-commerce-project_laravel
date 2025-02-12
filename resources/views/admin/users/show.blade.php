@extends('adminlte::page')

@section('title', 'Détails de l\'Utilisateur')

@section('content_header')
    <h1>Détails de l'Utilisateur</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Informations Utilisateur</h3>
                </div>
                <div class="card-body">
                    <p><strong>Nom :</strong> {{ $user->name }}</p>
                    <p><strong>Email :</strong> {{ $user->email }}</p>
                    <p><strong>Date d'inscription :</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title">Gestion des Rôles</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.updateRoles', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Rôles attribués :</label>
                            <select name="roles[]" class="form-control select2" multiple="multiple">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                            @if($user->hasRole($role->name)) selected @endif>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">Historique des Commandes</h3>
                </div>
                <div class="card-body">
                    @if ($orders->count() > 0)
                        <table id="ordersTable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Statut</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
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
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Aucune commande passée.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Activer Select2 pour la gestion des rôles
            $('.select2').select2({
                width: '100%'
            });

            // Activer DataTables pour l'historique des commandes
            $('#ordersTable').DataTable({
                "dom": 'lBfrtip', // Active la recherche et la pagination
                "order": [[1, "desc"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
                }
            });
        });
    </script>
@endsection
