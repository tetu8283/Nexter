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

                {{-- 所属店舗の在庫しか登録できないためhidden --}}
                <input type="hidden" name="store_id" value="{{ auth()->user()->store_id }}">

                <div id="inventory-items-container">
                    <div class="mb-3 inventory-item d-flex align-items-center gap-2">
                        <label class="form-label mb-0 w-25">商品</label>
                        <select name="book_id[]" class="form-select w-75" required>
                            @foreach ($books as $book)
                                <option value="{{ $book->id }}">{{ $book->name }}</option>
                            @endforeach
                        </select>
                        {{-- invisibleで見えないがdomには存在するようにすることでスペースを確保 --}}
                        <button type="button" class="btn btn-danger btn-sm remove-item-button invisible">✕</button>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <button type="button" class="btn btn-secondary" id="add-item-button">＋ 商品を追加</button>
                </div>

                <div class="d-flex justify-content-center w-100">
                    <button type="submit" class="btn btn-primary w-25">登録</button>
                </div>
            </form>
        </div>

        </div>
    </div>
</div>

{{-- 🔽 商品追加・削除機能用のJS --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const addButton = document.getElementById('add-item-button');
        const container = document.getElementById('inventory-items-container');

        addButton.addEventListener('click', () => {
            const newItem = document.createElement('div');
            newItem.classList.add('mb-3', 'inventory-item', 'd-flex', 'align-items-center', 'gap-2');

            // 🔽 新しい商品選択フィールド＋削除ボタンを追加
            newItem.innerHTML = `
                <label class="form-label mb-0 w-25">商品</label>
                <select name="book_id[]" class="form-select w-75" required>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-danger btn-sm remove-item-button">✕</button>
            `;

            container.appendChild(newItem);
        });

        // 🔽 削除ボタンのイベントを一括で管理
        container.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-item-button')) {
                const item = e.target.closest('.inventory-item');
                item.remove();
            }
        });
    });
</script>
