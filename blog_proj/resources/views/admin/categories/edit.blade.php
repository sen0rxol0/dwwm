@extends('layouts.admin')
@section('sub_title', 'Modifier catégorie')
@section('breadcrumbs')
    <a class="btn btn-link btn-sm" href="{{ route('admin.home') }}">Dashboard</a>
    <strong>/</strong>
    <a class="btn btn-link btn-sm" href="{{ route('admin.categories.index') }}">Cat&eacute;gories</a>
    <strong>/</strong>
    <a class="btn btn-link" href="{{ route('admin.categories.edit', $category->id) }}">Modifier cat&eacute;gorie</a>
@endsection
@section('sub_content')
    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
        @csrf
        <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-right">Nom</label>
            <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $category->name }}" required autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">Sauvegarder</button>
            </div>
        </div>
    </form>
@endsection