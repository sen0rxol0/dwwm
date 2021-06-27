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
    <script>
        function onImportBooks() {
            const endpoint = document.querySelector('button[data-import-endpoint]').getAttribute('data-import-endpoint');
            function onProcessBooks(olBooks) {
                const books = [];
                olBooks.works.forEach((work) => {
                    books.push({
                        title: work.title,
                        author: work.authors[0].name,
                        comment: work.subject.join(),
                        rate: 20,
                    });
                });
                fetch(endpoint, {
                    method: 'POST',
                    body: JSON.stringify(books),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => {
                    if (res.status === 200) {
                        window.location.reload();
                        // res.json().then(data => {});
                    }
                });
            }

            fetch('https://openlibrary.org/subjects/love.json?limit=100&details=true')
            .then(res => res.json()).then(onProcessBooks);
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
        <button type="button" onclick="onImportBooks()" data-import-endpoint="{{route('books.import')}}" class="btn btn-light">Importer livres</button>
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
                            <td style="max-width: 256px">{{$book->title}}</td>
                            <td style="max-width: 256px">{{$book->author}}</td>
                            <td><a class="btn btn-link btn-sm btn-outline-light" href="{{route('books.show', ['id' => $book->id])}}">Voir avis</a></td>
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
            @if ($books->hasPages())
                <nav aria-label="Nagivation des livres par page">
                    <ul class="pagination flex-row justify-content-end">
                    <li class="page-item">
                        <a class="page-link" href="{{$books->previousPageUrl()}}" aria-label="Précédent"><span aria-hidden="true">&laquo;</span></a>
                    </li>
                    @for ($i = 1; $i <= $books->lastPage(); $i++)
                        <li class="page-item"><a class="page-link" href="{{$books->url($i)}}">{{$i}}</a></li>
                    @endfor
                    <li class="page-item">
                        <a class="page-link" href="{{$books->nextPageUrl()}}" aria-label="Suivant"><span aria-hidden="true">&raquo;</span></a>
                    </li>
                    </ul>
                </nav>
            @endif
            @if (!count($books))
                <small>Aucun livre dans les enregistrements.</small>
            @endif
        @endif
    </div>
@endsection