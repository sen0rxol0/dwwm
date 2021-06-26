@extends('layouts.base')
@section('title', 'Liste des livres')
@section('canonical')
    <meta name="canonical" content="{{route('books.index')}}">
@endsection
@section('scripts')
    <script defer>
        document.onreadystatechange = () => {
            if (document.readyState === 'complete') {
                const tableDeleteBtns = document.querySelectorAll('a[id*="btn_delete_book-"]');
                tableDeleteBtns.forEach((aBtn) => {
                    aBtn.addEventListener('click', (ev) => {
                        ev.preventDefault();
                        document.querySelector(`form[action="${ev.target.getAttribute('href')}"`)
                            .submit();
                    });
                })
            }
        }
    </script>
@endsection
@section('breadcrumbs')
    <li class="list-group-item">
        <a class="btn bnt-link" href="{{route('books.index')}}">Liste des livres</a>
        <strong>/</strong>
    </li>
@endsection
@section('content')
    <div class="col-12 d-flex flex-row justify-content-end">
        <a class="btn btn-link" href="{{route('books.create')}}">Ajouter nouveau livre</a>
    </div>
    <div class="col-12">
        @if($books)
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Author</th>
                        <th>Avis</th>
                        <th>Note</th>
                        <th>Date de modification</th>
                        <th>Date d&apos;ajout</th>
                        <th>&nldr;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        <tr>
                            <td>{{$book->title}}</td>
                            <td>{{$book->author}}</td>
                            <td>{{$book->comment}}</td>
                            <td>{{$book->rate}}</td>
                            <td>{{$book->created_at->format('d/m/Y à H:m:s')}}</td>
                            <td>{{$book->updated_at->format('d/m/Y à H:m:s')}}</td>
                            <td>
                                <div>
                                    <a href="{{route('books.edit', ['id' => $book->id])}}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                    <a id="btn_delete_book-{{$book->id}}" href="{{route('books.delete', ['id' => $book->id])}}" class="btn btn-sm btn-outline-danger">Supprimer</a>
                                    <form action="{{route('books.delete', ['id' => $book->id])}}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <nav aria-label="Nagivation des livres par page">
                <ul class="pagination flex-row justify-content-end">
                  <li class="page-item">
                    <a class="page-link" href="#" aria-label="Précédent">
                      <span aria-hidden="true">&laquo;</span>
                    </a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item">
                    <a class="page-link" href="#" aria-label="Suivant">
                      <span aria-hidden="true">&raquo;</span>
                    </a>
                  </li>
                </ul>
            </nav>
            @if (!count($books))
                <small>Aucun livre dans les enregistrements.</small>
            @endif
        @endif
    </div>
@endsection