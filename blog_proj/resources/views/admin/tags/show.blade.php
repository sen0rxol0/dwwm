@extends('layouts.admin')
@section('sub_title', 'Catégorie {{ $category->name }}')
@section('breadcrumbs')
    <a class="btn btn-link btn-sm" href="{{ route('admin.home') }}">Dashboard</a>
    <strong>/</strong>
    <a class="btn btn-link btn-sm" href="{{ route('admin.categories.index') }}">Cat&eacute;gories</a>
    <strong>/</strong>
    <a class="btn btn-link" href="{{ route('admin.categories.show', $category->id) }}">{{$category->name}}</a>
@endsection
@section('sub_content')

@endsection