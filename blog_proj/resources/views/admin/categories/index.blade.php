@extends('layouts.admin')
@section('sub_title', 'Liste des catégories')
@section('breadcrumbs')
    <a class="btn btn-link btn-sm" href="{{ route('admin.home') }}">Dashboard</a>
    <strong>/</strong>
    <a class="btn btn-link" href="{{ route('admin.categories.index') }}">Cat&eacute;gories</a>
@endsection
@section('sub_content')
    <a class="btn btn-link btn-outline-light" href="{{ route('admin.categories.create') }}">Ajouter cat&eacute;gorie</a>
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
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    {{-- <td>{{ date('d-m-Y', $category->created_at->toDateTimeString()) }}</td> --}}
                    <td>{{ $category->created_at->diffForHumans() }}</td>
                    <td>{{ $category->updated_at->diffForHumans() }}</td>
                    <td>
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.categories.edit', $category->id) }}">Modifier</a>
                        <a class="btn btn-outline-danger btn-sm" href="{{ route('admin.categories.delete', $category->id) }}"
                            onclick="event.preventDefault();if (confirm('Veuillez-vous confirmer suppression ?')) document.getElementById('detele_category_form').submit();"
                        >
                            Supprimer
                        </a>

                        <form id="detele_category_form" action="{{ route('admin.categories.delete', $category->id) }}" method="POST" class="d-none">
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
@endsection
