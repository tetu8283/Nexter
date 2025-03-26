<div class="modal fade" id="userRegisterModal" tabindex="-1" aria-labelledby="userRegisterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title" id="userRegisterModalLabel">従業員登録</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
        </div>

        <div class="modal-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div id="user-items-container">
                    <div class="user-item mb-4 border-bottom pb-3">
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
                        <button type="button" class="btn btn-danger btn-sm remove-user-button d-none">✕ 削除</button>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <button type="button" class="btn btn-secondary" id="add-user-button">＋ 従業員を追加</button>
                </div>

                <div class="d-flex justify-content-center w-100">
                    <button type="submit" class="btn btn-primary w-25">登録</button>
                </div>
            </form>
        </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const addButton = document.getElementById('add-user-button');
        const container = document.getElementById('user-items-container');

        addButton.addEventListener('click', () => {
            const newItem = document.createElement('div');
            newItem.classList.add('user-item', 'mb-4', 'border-bottom', 'pb-3');
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
</script>
