@extends('layouts.base')
@section('title', 'Ajouter nouveau livre')
@section('canonical')
    <meta name="canonical" content="{{route('books.create')}}">
@endsection
@section('breadcrumbs')
    <li class="list-group-item">
        <a class="btn bnt-link btn-sm" href="{{route('books.index')}}">Liste des livres</a>
        <strong>/</strong>
    </li>
    <li class="list-group-item">
        <a class="btn bnt-link" href="{{route('books.create')}}">Ajouter nouveau livre</a>
        <strong>/</strong>
    </li>
@endsection
@section('content')
    <div class="card col-8">
        <form method="POST" action="{{route('books.store')}}">
            @csrf
            <div class="mt-2">
                <label for="title">Titre</label>
                <input class="form-control" type="text" name="title" id="title" required autofocus>
            </div>
            <div class="mt-4">
                <label for="author">Nom de l&apos;author</label>
                <input class="form-control" type="text" name="author" id="author" required>
            </div>
            <div class="mt-4">
                <label for="comment">Avis</label>
                <textarea class="form-control" name="comment" id="comment" cols="20" rows="5" required></textarea>
            </div>
            <div class="mt-4">
                <label for="rate">Note&lpar;0/20&rpar;</label>
                <input class="form-control" min="0" max="20" type="number" name="rate" id="rate" required>
            </div>
            <div class="mt-4 d-flex justify-content-end">
                <button class="btn btn-primary" type="submit" title="Enregistrer nouveau livre">Enregistrer</button>
            </div>
        </form>
    </div>
@endsection