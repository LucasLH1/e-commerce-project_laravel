@extends('adminlte::page')

@section('title', 'Gestion des Coupons')

@section('content_header')
    <h1>Liste des Coupons</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un Coupon
            </a>
        </div>
        <div class="card-body">
            <table id="couponsTable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Valeur</th>
                    <th>Montant Min.</th>
                    <th>Expiration</th>
                    <th>Actif</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->id }}</td>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ ucfirst($coupon->discount_type) }}</td>
                        <td>{{ $coupon->discount_type == 'percentage' ? $coupon->discount_value . '%' : $coupon->discount_value . '€' }}</td>
                        <td>{{ $coupon->min_order_amount ?? 'Aucun' }}€</td>
                        <td>
                            {{ $coupon->expiration_date ? \Carbon\Carbon::parse($coupon->expiration_date)->format('d/m/Y') : 'Aucune' }}
                        </td>
                        <td>
                    <span class="badge {{ $coupon->is_active ? 'badge-success' : 'badge-danger' }}">
                        {{ $coupon->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce coupon ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('#couponsTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
                }
            });
        });
    </script>
@endsection
