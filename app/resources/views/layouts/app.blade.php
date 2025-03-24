<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nexter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/template.css') }}">

    @stack('styles')
</head>
<body>

    <header>
        @include('partials.header')
        @include('partials.sidebar')
    </header>

    <main>
        <div class="container mt-4"></div>
        @yield('content')
    </main>

    @stack('scripts')
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
