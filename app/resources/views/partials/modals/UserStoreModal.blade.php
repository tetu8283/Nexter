<div class="modal fade" id="userRegisterModal" tabindex="-1" aria-labelledby="userRegisterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="userRegisterModalLabel">従業員登録</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
        </div>

        <div class="modal-body">
            <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">氏名</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">パスワード</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="store" class="form-label">店舗</label>
                <select name="store" id="store" class="form-select" required>
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach
                </select>
            </div>
            <input type="hidden" name="role" value="0">

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
