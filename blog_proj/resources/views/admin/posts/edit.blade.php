@extends('layouts.admin')
@section('sub_title', 'Modifier article')
@section('breadcrumbs')
    <a class="btn btn-link btn-sm" href="{{ route('admin.home') }}">Dashboard</a>
    <strong>/</strong>
    <a class="btn btn-link btn-sm" href="{{ route('admin.posts.index') }}">Articles</a>
    <strong>/</strong>
    <a class="btn btn-link" href="{{ route('admin.posts.edit', ['id' => $post->id]) }}">Modifier article</a>
@endsection
@section('sub_content')
    <form method="POST" action="{{ route('admin.posts.update', ['id' => $post->id]) }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <label for="title" class="col-md-4 col-form-label text-md-right">Titre</label>
            <div class="col-md-6">
                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') ?? $post->title }}" autofocus />
                @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="category_id" class="col-md-4 col-form-label text-md-right">Cat&eacute;gorie</label>
            <div class="col-md-6">
                <select id="category_id" type="text" class="form-control @error('category_id') is-invalid @enderror" name="category_id" value="{{ old('category_id') ?? $post->category_id }}">
                    @foreach ($categories as $category)
                        <option @if ($category->id == $post->category_id) selected @endif value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="image" class="col-md-4 col-form-label text-md-right">Image</label>
            <div class="col-md-6">
                <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" value="{{ old('image') }}" />
                @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="content" class="col-md-4 col-form-label text-md-right">Contenu</label>
            <div class="col-md-6">
                <textarea id="content" class="form-control @error('content') is-invalid @enderror" name="content">{{ old('content') ?? $post->content }}</textarea>
                @error('content')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <a href="{{route('admin.posts.index')}}" title="Annuler modifier" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-success">Sauvegarder</button>
            </div>
        </div>
    </form>
@endsection
