@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="book bg-light mb-3">
                <div class="book-body d-flex align-items-center">
                    <i class="fa-solid fa-people-group fa-3x text-primary me-3"></i>
                    <div>
                        <h5 class="book-title mb-1">従業員数</h5>
                        <p id="employeesNum" class="book-text fs-3 text-end text-primary mb-0">{{ $employeesNum }} 人</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="book bg-light mb-3">
                <div class="book-body d-flex align-items-center">
                    <i class="fa-solid fa-book fa-3x text-success me-3"></i>
                    <div>
                        <h5 class="book-title mb-1">在庫数</h5>
                        <p id="inventoriesNum" class="book-text fs-3 text-end text-success mb-0">{{ $inventoriesNum }} 冊</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="book bg-light mb-3">
                <div class="book-body d-flex align-items-center">
                    <i class="fa-solid fa-weight-hanging fa-3x text-warning me-3"></i>
                    <div>
                        <h5 class="book-title mb-1">総重量</h5>
                        <p id="totalBooksWeight" class="book-text fs-3 text-end text-warning mb-0">{{ $totalBooksWeight }} Kg</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="book">
        <div class="book-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">在庫一覧</h5>
            @if(Auth::user()->role === 1)
                <div class="d-flex align-items-center">
                    <select name="store_id" id="selectedStore" class="form-select" required>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}" {{ $store->id == $userStoreId ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="book-body overflow-auto" style="max-height: 400px;">
            <table class="table table-striped table-hover table-bordered text-center mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th scope="col" class="text-center">画像</th>
                        <th scope="col">商品名</th>
                        <th scope="col">重量</th>
                        <th scope="col">登録日</th>
                    </tr>
                </thead>
                <tbody id="inventoryTableBody" data-page="1">
                    @foreach ($inventories as $item)
                        <tr style="height: 80px;">
                            <td class="text-center align-middle">
                                <img src="{{ asset($item->book->image) }}" alt="本の画像" class="img-fluid rounded" style="width: 80px; height: 80px;">
                            </td>
                            <td class="align-middle">
                                <p class="mb-1">{{ $item->book ? $item->book->name : '本の情報なし' }}</p>
                            </td>
                            <td class="align-middle">
                                {{ $item->book ? $item->book->weight : '-' }} Kg
                            </td>
                            <td class="align-middle">
                                {{ $item->created_at->format('Y-m-d') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/display-store-info.js') }}"></script>
    <script src="{{ asset('js/scroll.js') }}"></script>
@endsection
