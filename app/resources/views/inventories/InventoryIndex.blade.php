@extends('layouts.app')

@yield('inventory-index')

@section('content')

    @include('partials.modals.UserStoreModal', compact('stores'))
@endsection

@push('scripts')
    <script src=""></script>
@endpush
