@extends('layouts.admin')
@section('sub_title', 'Liste des articles')
@section('breadcrumbs')
    <a class="btn btn-link btn-sm" href="{{ route('admin.home') }}">Dashboard</a>
    <strong>/</strong>
    <a class="btn btn-link" href="{{ route('admin.posts.index') }}">Articles</a>
@endsection
@section('sub_content')
    <a class="btn btn-link btn-outline-light" href="{{ route('admin.posts.create') }}">Ajouter article</a>
    <table class="mt-2 table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Titre</th>
                <th>Cat&eacute;gorie</th>
                <th>Contenu</th>
                <th>Publication</th>
                <th>Ajout&eacute;</th>
                <th>Modifi&eacute;</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td><img width="92px" height="92px" src="{{asset($post->image)}}" alt="{{$post->title}}" /></td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->category->name }}</td>
                    <td><a href="{{route('admin.posts.show', ['id' => $post->id])}}">Voir</a></td>
                    <td>
                        {{ $post->published == 1 ? 'Publié' : 'Non publié' }}
                        <form 
                            id="form_publish-{{$post->id}}" 
                            action="{{ route('admin.posts.update', ['id' => $post->id])}}" 
                            method="POST"
                        >
                            @csrf
                            <input type="hidden" name="published" value="{{$post->published}}" />
                            <input
                                form="form_publish-{{$post->id}}" 
                                type="checkbox"  
                                @if ($post->published == 1) checked @endif 
                                name="publish"
                                id="publish-{{$post->id}}" 
                                onchange="
                                    if (this.form.published.value == 1) {
                                        this.form.published.value = 0;
                                    } else {
                                        this.form.published.value = 1;
                                    }
                                    this.form.submit();"
                            />
                        </form>
                    </td>
                    <td>{{  $post->created_at->diffForHumans() }}</td>
                    <td>{{  $post->updated_at->diffForHumans() }}</td>
                    <td>
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.posts.edit', ['id' => $post->id]) }}">Modifier</a>
                        <a class="btn btn-outline-danger btn-sm" href="{{ route('admin.posts.delete', ['id' => $post->id]) }}"
                            onclick="this.preventDefault();if (confirm('Veuillez-vous confirmer suppression ?')) document.getElementById('detele_post_form').submit();"
                        >
                            Supprimer
                        </a>

                        <form id="detele_post_form" action="{{ route('admin.posts.delete', ['id' => $post->id]) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <nav aria-label="Navigation des articles par pages">
        <ul class="pagination justify-content-end">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Pr&egrave;cedent</a>
          </li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#">Suivant</a>
          </li>
        </ul>
    </nav>
@endsection
