@extends('layouts.admin')
@section('sub_title', 'Liste des articles')
@section('scripts')
<script defer>
    function onDeletePost(ev) {
        ev.preventDefault();
        if (ev.target.classList.contains('btn__delete_post')) {
            if (confirm('Veuillez-vous confirmer corbeille ?')) {
                document.getElementById(ev.target.getAttribute('data-for')).submit();
            }
        } 
        
        if (ev.target.classList.contains('btn__restore_post')) {
            if (confirm('Veuillez-vous confirmer remettre ?')) {
                document.getElementById(ev.target.getAttribute('data-for')).submit();
            }
        }
    }

    document.onreadystatechange = function() {
        if (document.readyState == 'complete') {
            const deleteBtns = document.querySelectorAll('a.btn__delete_post');
            const restoreBtns = document.querySelectorAll('a.btn__restore_post');
            if (deleteBtns.length) {
                deleteBtns.forEach((btnDelete) => {
                    btnDelete.addEventListener('click', onDeletePost);
                });
            }
            
            if (restoreBtns.length) {
                restoreBtns.forEach((btnRestore) => {
                    btnRestore.addEventListener('click', onDeletePost);
                });
            }

            // document.getElementById('btn_empty_trash').addEventListener('click', (ev) => {
            // });
        }
    }
</script>    
@endsection
@section('breadcrumbs')
    <a class="btn btn-link btn-sm" href="{{ route('admin.home') }}">Dashboard</a>
    @if(request('corbeille') == 1)
        <strong>/</strong>
        <a class="btn btn-link btn-sm" href="{{ route('admin.posts.index') }}">Liste des articles</a>
        <strong>/</strong>
        <a class="btn btn-link" href="{{ route('admin.posts.index') }}?corbeille=1">Corbeille</a>
    @else
        <strong>/</strong>
        <a class="btn btn-link" href="{{ route('admin.posts.index') }}">Liste des articles</a>
    @endif
@endsection
@section('sub_content')
    <a class="btn btn-link btn-outline-light" href="{{ route('admin.posts.create') }}">Ajouter article</a>
    @if(request('corbeille') == 1)
        <a id="btn_empty_trash" class="btn btn-danger" href="{{ route('admin.posts.index') }}?corbeille=1&empty=1">Vider corbeille</a>
        @if (isset($posts[0]))
            <form id="form_empty_trash" action="{{route('admin.posts.delete', ['id' => $posts[0]->id])}}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
                <input type="hidden" name="empty_deletes" value="1">
            </form>
        @endif
    @else
        <a class="btn btn-light" href="{{ route('admin.posts.index') }}?corbeille=1">Voir corbeille</a>
    @endif
    @if(count($posts))
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
                        @if (!$post->trashed())
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.posts.edit', ['id' => $post->id]) }}">Modifier</a>
                        @endif
                        <a
                            class="btn__delete_post btn btn-outline-danger btn-sm" 
                            href="{{ route('admin.posts.delete', ['id' => $post->id]) }}"
                            data-for="form__corbeille_post-{{$post->id}}"
                        >
                            @if($post->trashed()) Supprimer @else Corbeille @endif
                        </a>
                        <form id="form__corbeille_post-{{$post->id}}" action="{{ route('admin.posts.delete', ['id' => $post->id]) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                            @if($post->trashed())
                                <input type="hidden" name="permanent_delete" value="1">
                            @endif
                        </form>

                        @if($post->trashed())
                            <a
                                class="btn__restore_post btn btn-outline-danger btn-sm" 
                                href="{{ route('admin.posts.delete', ['id' => $post->id]) }}"
                                data-for="form__restore_post-{{$post->id}}"
                            >
                                Remettre
                            </a>

                            <form id="form__restore_post-{{$post->id}}" action="{{ route('admin.posts.delete', ['id' => $post->id]) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="restore_delete" value="1">
                            </form>
                        @endif
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
    @endif
@endsection
