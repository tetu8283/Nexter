<div class="modal fade" id="arrivalStoreModal" tabindex="-1" aria-labelledby="arrivalStoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="arrivalStoreModalLabel">入荷登録</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('arrivals.store') }}" method="POST">
                    @csrf
                    <!-- 自店舗ID（ログインユーザーの店舗IDを hidden で設定） -->
                    <input type="hidden" name="store_id" value="{{ $storeId }}" required>

                    <!-- 複数入荷項目を登録するためのコンテナ -->
                    <div id="arrival-items-container">
                        <div class="arrival-item mb-3 border rounded p-3">
                            <!-- 商品選択 -->
                            <div class="mb-2">
                                <label class="form-label" style="color: #333333;">商品</label>
                                <select name="book_id" class="form-select" required>
                                    @foreach ($arrivalBooks as $book)
                                        <option value="{{ $book->id }}" style="color: #333333;">{{ $book->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 入荷予定日 -->
                            <div class="mb-2">
                                <label for="arrival_date" class="form-label">入荷予定日</label>
                                <input type="date" name="arrival_date" class="form-control" required>
                            </div>

                            <input type="hidden" name="arrival_flag" value="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="button" class="btn btn-secondary" id="add-arrival-button">＋ 入荷項目を追加</button>
                    </div>

                    <div class="d-flex justify-content-center w-100 mt-3">
                        <button type="submit" class="btn btn-primary w-25">登録</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const addButton = document.getElementById('add-arrival-button');
        const container = document.getElementById('arrival-items-container');

        addButton.addEventListener('click', () => {
            const newItem = document.createElement('div');
            newItem.classList.add('arrival-item', 'mb-3', 'border', 'rounded', 'p-3');
            newItem.innerHTML = `
                <!-- 商品選択 -->
                <div class="mb-2">
                    <label class="form-label" style="color: #333333;">商品</label>
                    <select name="book_id" class="form-select" required>
                        @foreach ($arrivalBooks as $book)
                            <option value="{{ $book->id }}" style="color: #333333;">{{ $book->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- 入荷予定日 -->
                <div class="mb-2">
                    <label for="arrival_date" class="form-label">入荷予定日</label>
                    <input type="date" name="arrival_date" class="form-control" required>
                </div>
                <!-- 入荷状態：登録時は未確定（0） -->
                <input type="hidden" name="arrival_flag" value="0">
                <button type="button" class="btn btn-danger btn-sm remove-arrival-button">✕ 削除</button>
            `;
            container.appendChild(newItem);
        });

        container.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-arrival-button')) {
                const item = e.target.closest('.arrival-item');
                item.remove();
            }
        });
    });
</script>
