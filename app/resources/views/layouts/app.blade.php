<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nexter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/template.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
    <header>
        @include('partials.header')
        @include('partials.sidebar')
    </header>

    <main>

        @include('partials.modals.ArrivalStoreModal', compact('storeId', 'stores', 'arrivalBooks'))
        @if (Auth::user()->role == 1)
            @include('partials.modals.UserStoreModal', compact('stores'))
            @include('partials.modals.BookStoreModal')
        @else
            @include('partials.modals.InventoryStoreModal', compact('books'))
        @endif


        <div class="container mt-4">
            @if (session('flash_msg'))
                <div class="alert alert-success">
                    {{ session('flash_msg') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="{{ asset('js/template.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
