@extends('layouts.admin')
@section('sub_title')
Article: {{ $post->title }}
@endsection
@section('breadcrumbs')
    <a class="btn btn-link btn-sm" href="{{ route('admin.home') }}">Dashboard</a>
    <strong>/</strong>
    <a class="btn btn-link btn-sm" href="{{ route('admin.posts.index') }}">Articles</a>
    <strong>/</strong>
    <a class="btn btn-link" href="{{ route('admin.posts.show', ['id' => $post->id]) }}">{{$post->title}}</a>
@endsection
@section('sub_content')
    <div>
        <p>{{$post->content}}</p>
    </div>
@endsection