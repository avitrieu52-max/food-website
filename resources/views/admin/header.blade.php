<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.getCateList') }}">Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.getCateList') }}">Danh mục</a></li>
            </ul>
            <div class="d-flex align-items-center text-white">
                @auth
                    <span class="me-3">Xin chào {{ Auth::user()->full_name }}</span>
                    <a href="{{ route('admin.getLogout') }}" class="btn btn-sm btn-outline-light">Đăng xuất</a>
                @else
                    <a href="{{ route('admin.getLogin') }}" class="btn btn-sm btn-outline-light">Đăng nhập</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
