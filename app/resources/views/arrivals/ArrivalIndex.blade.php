@extends('layouts.app')

@include('partials.StoreArrivalInfo')

@section('content')
<div class="container mt-5">
    <div class="book">
        <div class="book-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">入荷予定一覧</h5>
            <!-- 検索フォーム -->
            <form action="#" class="d-flex align-items-center gap-2 my-3" id="searchForm">
                @csrf

                <input type="text" name="name" id="searchName" class="form-control" placeholder="商品名">
                <input type="date" name="start_date" id="startDate" class="form-control">
                <input type="date" name="end_date" id="endDate" class="form-control">
                <input type="text" value="{{ $storeId }}" name="store_id" id="selectedStore" class="form-control" hidden>

                <button type="submit" class="btn btn-primary w-50">検索</button>
            </form>

            <button class="btn btn-primary w-22" id="confirmArrivals">入荷登録確定</button>
        </div>

        <!-- 在庫一覧テーブル -->
        <div class="book-body overflow-auto" style="max-height: 450px;">
            <table class="table table-striped table-hover table-bordered text-center mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th></th>
                        <th>画像</th>
                        <th>商品名</th>
                        <th>入荷予定日</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="inventoryTableBody">

                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('js/arrival.js') }}"></script>
@endsection
