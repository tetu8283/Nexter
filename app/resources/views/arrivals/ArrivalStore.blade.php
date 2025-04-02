@extends('layouts.app')

@include('partials.StoreArrivalInfo')
@section('content')
    <p>自店入荷予定登録</p>
    <div class="container">
        <form action="{{ route('arrivals.store') }}">
            @csrf
            @method('POST')

            <div class="mb-2">
                <input type="hidden" name="store_id" class="form-control" value="{{ $storeId }}" readonly required>
            </div>

            <div class="mb-2">
                <label class="form-label mb-0 w-25" style="color: #333333;">商品</label>
                <select name="book_id[]" class="form-select w-75" required>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" style="color: #333333;">{{ $book->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label for="arrival_date">入荷予定日</label>
                <input type="date" name="arrival_date" class="form-control" required>
            </div>

            {{-- 状態は入荷登録状態 --}}
            <input type="text" hidden name="arrival_flag" value="0">

            <button type="submit" class="btn btn-primary">登録</button>

        </form>
    </div>
@endsection
