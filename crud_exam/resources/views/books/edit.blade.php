@extends('layouts.base')
@section('title', 'Modifier livre')
@section('canonical')
    <meta name="canonical" content="{{route('books.edit', ['id' => $book->id])}}">
@endsection
@section('breadcrumbs')
    <li class="list-group-item">
        <a class="btn bnt-link btn-sm" href="{{route('books.index')}}">Liste des livres</a>
        <strong>/</strong>
    </li>
    <li class="list-group-item">
        <a class="btn bnt-link" href="{{route('books.edit', ['id' => $book->id])}}">Modifier livre</a>
        <strong>/</strong>
    </li>
@endsection
@section('content')
    <div class="card col-8">
        <form method="POST" action="{{route('books.update')}}">
            @csrf
            <div class="mt-2">
                <label for="title">Titre</label>
                <input class="form-control" type="text" name="title" id="title" value="{{$book->title}}" required autofocus>
            </div>
            <div class="mt-4">
                <label for="author">Nom de l&apos;author</label>
                <input class="form-control" type="text" name="author" id="author" value="{{$book->author}}" required>
            </div>
            <div class="mt-4">
                <label for="comment">Avis</label>
                <textarea class="form-control" name="comment" id="comment" cols="20" rows="5" required>{{$book->comment}}</textarea>
            </div>
            <div class="mt-4">
                <label for="rate">Note&lpar;0/20&rpar;</label>
                <input class="form-control" min="0" max="20" type="number" name="rate" id="rate" value="{{$book->rate}}" required>
            </div>
            <div class="mt-4 d-flex justify-content-end">
                <button class="btn btn-primary" type="submit" title="Sauvegarder modification">Sauvegarder</button>
            </div>
        </form>
    </div>
@endsection