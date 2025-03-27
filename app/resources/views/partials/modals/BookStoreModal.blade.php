<div class="modal fade" id="bookRegisterModal" tabindex="-1" aria-labelledby="bookRegisterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title" id="bookRegisterModalLabel">商品登録</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
        </div>

        <div class="modal-body">
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div id="book-items-container">
                    <div class="book-item mb-3 border rounded p-3">
                        <div class="mb-3">
                            <label class="form-label">タイトル</label>
                            <input type="text" name="name[]" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">画像</label>
                            <input type="file" name="image[]" class="form-control" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">重さ</label>
                            <input type="text" name="weight[]" class="form-control" placeholder="※単位はkgで入力してください" required>
                        </div>
                        <input type="hidden" name="status_flag[]" value="0">
                        <button type="button" class="btn btn-danger btn-sm remove-book-button">✕ 削除</button>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-secondary" id="add-book-button">＋ 商品を追加</button>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary w-25">登録</button>
                </div>
            </form>
        </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const bookAddButton = document.getElementById('add-book-button');
        const bookContainer = document.getElementById('book-items-container');

        bookAddButton.addEventListener('click', () => {
            const newItem = document.createElement('div');
            newItem.classList.add('book-item', 'mb-3', 'border', 'rounded', 'p-3');
            // html追加
            newItem.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">タイトル</label>
                    <input type="text" name="name[]" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">画像</label>
                    <input type="file" name="image[]" class="form-control" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">重さ</label>
                    <input type="text" name="weight[]" class="form-control" placeholder="※単位はkgで入力してください" required>
                </div>
                <input type="hidden" name="status_flag[]" value="0">
                <button type="button" class="btn btn-danger btn-sm remove-book-button">✕ 削除</button>
            `;
            bookContainer.appendChild(newItem);
        });

        bookContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-book-button')) {
                const item = e.target.closest('.book-item');
                item.remove();
            }
        });
    });
</script>
