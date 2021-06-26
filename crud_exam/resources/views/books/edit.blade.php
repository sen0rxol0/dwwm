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
    <div class="card shadow-sm col-8">
        <form method="POST" action="{{route('books.update', ['id' => $book->id])}}">
            @csrf
            @method('PUT')
            <div class="mt-4">
                <label for="title">Titre</label>
                <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" id="title" value="{{old('title') ?? $book->title}}" required autofocus>
                @error('title')
                    <div class="alert alert-danger"><small>{{$message}}</small></div>
                @enderror
            </div>
            <div class="mt-4">
                <label for="author">Nom de l&apos;author</label>
                <input class="form-control @error('author') is-invalid @enderror" type="text" name="author" id="author" value="{{old('author') ?? $book->author}}" required>
                @error('author')
                    <div class="alert alert-danger"><small>{{$message}}</small></div>
                @enderror
            </div>
            <div class="mt-4">
                <label for="comment">Avis</label>
                <textarea class="form-control @error('comment') is-invalid @enderror" name="comment" id="comment" cols="20" rows="5" required>{{old('comment') ?? $book->comment}}</textarea>
                @error('comment')
                    <div class="alert alert-danger"><small>{{$message}}</small></div>
                @enderror
            </div>
            <div class="mt-4">
                <label for="rate">Note&lpar;0/20&rpar;</label>
                <input class="form-control @error('rate') is-invalid @enderror" min="0" max="20" type="number" name="rate" id="rate" value="{{old('rate') ?? $book->rate}}" required>
                @error('rate')
                    <div class="alert alert-danger"><small>{{$message}}</small></div>
                @enderror
            </div>
            <div class="my-4 d-flex justify-content-end">
                <button class="btn btn-success" type="submit" title="Sauvegarder modification">Sauvegarder</button>
            </div>
        </form>
    </div>
@endsection