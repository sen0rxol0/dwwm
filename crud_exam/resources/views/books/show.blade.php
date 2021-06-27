@extends('layouts.base')
@section('title', 'Livre')
@section('canonical')
    <meta name="canonical" content="{{route('books.show', ['id' => $book->id])}}">
@endsection
@section('breadcrumbs')
    <li class="list-group-item">
        <a class="btn bnt-link btn-sm" href="{{route('books.index')}}">Liste des livres</a>
        <strong>/</strong>
    </li>
    <li class="list-group-item">
        <a class="btn bnt-link" href="{{route('books.show', ['id' => $book->id])}}">Livre&colon; {{$book->title}}</a>
        <strong>/</strong>
    </li>
@endsection
@section('content')
    <section>
        <h2>{{$book->title}}</h2>
        <p>{{$book->comment}}</p>
    </section>
@endsection