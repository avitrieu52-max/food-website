<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-leaf me-2"></i>AT10 Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.food.*') ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-box me-1"></i>Sản phẩm
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="{{ route('admin.food.list') }}">Danh sách</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.food.create') }}">Thêm mới</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.getCate*') ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-tags me-1"></i>Loại sản phẩm
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="{{ route('admin.getCateList') }}">Danh sách</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.getCateAdd') }}">Thêm mới</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.order.*') ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-shopping-bag me-1"></i>Đơn hàng
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="{{ route('admin.order.list') }}">Danh sách</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.user.*') ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-users me-1"></i>Người dùng
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="{{ route('admin.user.list') }}">Danh sách</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.user.create') }}">Thêm mới</a></li>
                    </ul>
                </li>
            </ul>
            <div class="d-flex align-items-center text-white">
                @auth
                    <span class="me-3 small"><i class="fas fa-user-circle me-1"></i>{{ Auth::user()->full_name }}</span>
                    <a href="{{ route('admin.getLogout') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-sign-out-alt me-1"></i>Đăng xuất
                    </a>
                @else
                    <a href="{{ route('admin.getLogin') }}" class="btn btn-sm btn-outline-light">Đăng nhập</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
