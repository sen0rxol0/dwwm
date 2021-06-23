@extends('layouts.admin')
@section('sub_title', 'Liste des tags')
@section('breadcrumbs')
    <a class="btn btn-link btn-sm" href="{{ route('admin.home') }}">Dashboard</a>
    <strong>/</strong>
    <a class="btn btn-link" href="{{ route('admin.tags.index') }}">Liste des tags</a>
@endsection
@section('sub_content')
    <a class="btn btn-link btn-outline-light" href="{{ route('admin.tags.create') }}">Ajouter nouveau tag</a>
    @if(count($tags))
    <table class="mt-2 table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Ajout&eacute;</th>
                <th>Modifi&eacute;</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tags as $tag)
                <tr>
                    <td>{{ $tag->name }}</td>
                    <td>{{ $tag->created_at->diffForHumans() }}</td>
                    <td>{{ $tag->updated_at->diffForHumans() }}</td>
                    <td>
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.tags.edit', $tag->id) }}">Modifier</a>
                        <a class="btn btn-outline-danger btn-sm" href="{{ route('admin.tags.delete', $tag->id) }}"
                            onclick="event.preventDefault();if (confirm('Veuillez-vous confirmer suppression ?')) document.getElementById('form_delete_tag-{{$tag->id}}').submit();"
                        >
                            Supprimer
                        </a>

                        <form id="form_delete_tag-{{$tag->id}}" action="{{ route('admin.tags.delete', $tag->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <nav aria-label="Catégories navigation par pages">
        <ul class="pagination justify-content-end">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
          </li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#">Next</a>
          </li>
        </ul>
    </nav>
    @endif
@endsection
