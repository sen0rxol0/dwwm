@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-3 bg-dark">
        <nav class="pt-5">
            <ul class="nav flex-column">
                {{-- <li class="pb-2 nav-item">
                    <a class="nav-link btn btn-outline-primary active" href="{{ route('admin.home') }}">Dashboard</a>
                </li> --}}
                <li class="pb-2 nav-item">
                    <a class="nav-link btn btn-outline-primary" href="{{ route('admin.categories.index') }}">Cat&eacute;gories</a>
                </li>
                <li class="pb-2 nav-item">
                    <a class="nav-link btn btn-outline-primary" href="{{ route('admin.posts.index') }}">Articles</a>
                </li>
                <li class="pb-2 nav-item">
                    <a class="nav-link btn btn-outline-primary" href="{{ route('admin.tags.index') }}">Tags</a>
                </li>
                <li class="pb-2 nav-item">
                    <a class="nav-link btn btn-outline-primary" href="{{ route('admin.users.index') }}">Utilisateurs</a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-dark text-light">{{ __('Dashboard') }}</div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                {{ __('Welcome admin!') }}
            </div>
        </div>
    </div>
</div>
@endsection
