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

                    <div class="d-flex justify-content-center w-100 mt-3">
                        <button type="submit" class="btn btn-primary w-25">登録</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
{{--
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const addButton = document.getElementById('add-user-button');
        const container = document.getElementById('user-items-container');

        addButton.addEventListener('click', () => {
            const newItem = document.createElement('div');
            newItem.classList.add('user-item', 'mb-4', 'border-bottom', 'pb-3');
            // ユーザ登録用のhtmlを追加
            newItem.innerHTML = `
                <div class="mb-2">
                    <label class="form-label">氏名</label>
                    <input type="text" name="name[]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">メールアドレス</label>
                    <input type="email" name="email[]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">パスワード</label>
                    <input type="password" name="password[]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">店舗</label>
                    <select name="store_id[]" class="form-select" required>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="role[]" value="0">
                <button type="button" class="btn btn-danger btn-sm remove-user-button">✕ 削除</button>
            `;
            container.appendChild(newItem);
        });

        container.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-user-button')) {
                e.target.closest('.user-item').remove();
            }
        });
    });
</script> --}}
