@extends('adminlte::page')

@section('title', 'Gestion des Utilisateurs')

@section('content_header')
    <h1>Liste des Utilisateurs</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="usersTable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#usersTable').DataTable({
                "dom": 'lBfrtip', // Ajoute les options de recherche et de pagination
                "order": [[0, "asc"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json"
                }
            });
        });
    </script>
@endsection
