<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('canonical')
        <title>@yield('title') &mdash; {{config('app.name')}}</title>
        <!-- Fonts -->
        <!-- Styles -->
        <link rel="preconnect" href="https://cdn.jsdelivr.net">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        @yield('styles')
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" defer></script>
        @yield('scripts')
    </head>
    <body class="container py-5">
        <header class="row">
            <h1>@yield('title')</h1>
        </header>
        <nav class="my-2 row">
            <ul class="list-group col flex-row">
                @yield('breadcrumbs')
            </ul>
        </nav>
        <main class="row justify-content-center">
            @yield('content')
        </main>
    </body>
</html>
