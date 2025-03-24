<nav class="navbar navbar-expand-lg navbar-dark main-green fixed-top px-5">
    <a class="navbar-brand" href="{{ route('inventories.index') }}">Nexter</a>
    <button class="btn btn-outline-light me-3" id="menu-toggle">
        ☰
    </button>
    <div class="ms-auto dropdown">
        <a href="#" class="text-white text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            {{ Auth::user()->name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li></li>
            <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn dropdown-item text-center">ログアウト</button>
            </form>
            </li>
        </ul>
    </div>
</nav>
