@extends('layouts.admin')
@section('sub_title', 'Modifier tag')
@section('breadcrumbs')
    <a class="btn btn-link btn-sm" href="{{ route('admin.home') }}">Dashboard</a>
    <strong>/</strong>
    <a class="btn btn-link btn-sm" href="{{ route('admin.tags.index') }}">Liste des tags</a>
    <strong>/</strong>
    <a class="btn btn-link" href="{{ route('admin.tags.edit', $tag->id) }}">Modifier tag&colon; {{$tag->name}}</a>
@endsection
@section('sub_content')
    <form method="POST" action="{{ route('admin.tags.update', $tag->id) }}">
        @csrf
        <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-right">Nom</label>
            <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $tag->name }}" required autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <a href="{{route('admin.tags.index')}}" title="Annuler modifier" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-success">Sauvegarder</button>
            </div>
        </div>
    </form>
@endsection