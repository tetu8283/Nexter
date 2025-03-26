<div class="modal fade" id="inventoryRegisterModal" tabindex="-1" aria-labelledby="inventoryRegisterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="inventoryRegisterModalLabel">在庫登録</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
        </div>

        <div class="modal-body">
            <form action="{{ route('inventories.store') }}" method="POST">
                @csrf

                {{-- 所属店舗の在庫しか登録できないため、hiddenで対応 --}}
                <input type="text" name="store_id" value="{{ auth()->user()->store_id }}" hidden>

                <div class="mb-3">
                    <label for="book" class="form-label">商品</label>
                    <select name="book_id" id="book_id" class="form-select" required>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-center w-100">
                    <button type="submit" class="btn btn-primary w-25">登録</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

{{-- <script>
    document.getElementById('add-item-button').addEventListener('click', function () {
        const container = document.getElementById('inventory-items-container');
        const newItem = document.createElement('div');
        newItem.classList.add('inventory-item', 'mb-3');
        newItem.innerHTML = `
            <div class="mb-2">
                <label for="item_name[]" class="form-label">商品名</label>
                <input type="text" name="item_name[]" class="form-control" required>
            </div>
            <div class="mb-2">
                <label for="quantity[]" class="form-label">数量</label>
                <input type="number" name="quantity[]" class="form-control" required>
            </div>
        `;
        container.appendChild(newItem);
    });
</script> --}}
