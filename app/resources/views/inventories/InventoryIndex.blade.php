@extends('layouts.app')

@include('partials.StoreInventoryInfo', [
    'employeesNum' => $employeesNum,
    'inventoriesNum' => $inventoriesNum,
    'totalBooksWeight' => $totalBooksWeight
])

@section('content')
<div class="container mt-5">
    <div class="book">
        <div class="book-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">在庫一覧</h5>
            <!-- 検索フォーム（店舗選択・商品名・日付） -->
            <form action="#" class="d-flex align-items-center gap-2 my-3" id="searchForm">
                @csrf
                <input type="text" name="name" id="searchName" class="form-control" placeholder="商品名">
                <input type="date" name="start_date" id="startDate" class="form-control">
                <input type="date" name="end_date" id="endDate" class="form-control">
                @if(Auth::user()->role === 1)
                    <select name="store_id" id="selectedStore" class="form-select" required>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}" {{ $store->id == $storeId ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <select name="store_id" id="selectedStore" class="form-select" required>
                        <option value="{{ $store->id }}" selected>
                            {{ $store->name }}
                        </option>
                    </select>
                @endif
                <button type="submit" class="btn btn-primary w-50">検索</button>
            </form>
        </div>

        <!-- 在庫一覧テーブル -->
        <div class="book-body overflow-auto" style="max-height: 450px;">
            <table class="table table-striped table-hover table-bordered text-center mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th>画像</th>
                        <th>商品名</th>
                        <th>重量</th>
                        <th>登録日</th>
                    </tr>
                </thead>
                <tbody id="inventoryTableBody">

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/inventory.js') }}"></script>
@endsection
