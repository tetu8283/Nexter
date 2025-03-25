@extends('layouts.app')

@yield('inventory-index')

@section('content')
    @include('partials.modals.UserStoreModal', compact('stores'))

    @if (session('flash_msg'))
        <div class="alert alert-success">
            {{ session('flash_msg') }}
        </div>
    @endif
@endsection

@push('scripts')
    <script src=""></script>
@endpush
