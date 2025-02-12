@extends('adminlte::page')

@section('title', 'Ajouter un Produit')

@section('content_header')
    <h1>Ajouter un Produit</h1>
@endsection

@section('content')
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Nom du produit</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label>Prix</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Catégorie</label>
            <select name="category_id" class="form-control">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Images</label>
            <input type="file" name="images[]" multiple class="form-control">
        </div>

        <button type="submit" class="btn btn-success mt-3">Ajouter</button>
    </form>
@endsection
