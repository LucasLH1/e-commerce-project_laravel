@extends('adminlte::page')

@section('title', 'Modifier un Produit')

@section('content_header')
    <h1>Modifier un Produit</h1>
@endsection

@section('content')
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nom du produit</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
        </div>

        <div class="form-group">
            <label>Prix</label>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
        </div>

        <div class="form-group">
            <label>Catégorie</label>
            <select name="category_id" class="form-control">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Images actuelles</label>
            <div>
                @foreach($product->images as $image)
                    <img src="{{ asset('storage/' . $image->image_path) }}" width="50">
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label>Nouvelles images</label>
            <input type="file" name="images[]" multiple class="form-control">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Modifier</button>
    </form>
@endsection
