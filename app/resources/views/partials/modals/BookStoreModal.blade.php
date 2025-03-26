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
            <div class="mb-3">
                <label for="name" class="form-label">タイトル</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">画像</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label for="weight" class="form-label">重さ</label>
                <input type="text" name="weight" id="weight" class="form-control" placeholder="※単位はkgで入力してください" required>
            </div>
            <input type="text" name="status_flag" value="0" hidden>

            <div class="text-end">
                <div class="d-flex justify-content-center w-100">
                    <button type="submit" class="btn btn-primary w-25">登録</button>
                </div>
            </div>
            </form>
        </div>
        </div>
    </div>
</div>
