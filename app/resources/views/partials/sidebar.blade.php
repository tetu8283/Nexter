<!-- サイドバー -->
<div class="sidebar" id="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('arrivals.index') }}">入荷予定一覧</a></li>
        <li class="nav-item">
            <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#arrivalStoreModal">入荷予定登録</a>
        </li>
        @if (Auth::user()->role == 1)
            <li class="nav-item">
                <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#bookRegisterModal">商品登録</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#userRegisterModal">従業員登録</a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#inventoryRegisterModal">在庫登録</a>
            </li>
        @endif
    </ul>
</div>
