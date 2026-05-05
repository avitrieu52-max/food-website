@extends('admin.master')
@section('title', 'Quản lý Liên hệ')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-envelope me-2"></i>Quản lý Liên hệ</h3>
</div>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <form action="{{ route('admin.contact.list') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="unread" {{ request('status')=='unread'?'selected':'' }}>Chưa phản hồi</option>
                    <option value="replied" {{ request('status')=='replied'?'selected':'' }}>Đã phản hồi</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i>Lọc</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.contact.list') }}" class="btn btn-outline-secondary w-100">Xóa lọc</a>
            </div>
        </form>
    </div>
</div>
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr><th class="ps-3">#</th><th>Họ tên</th><th>Email</th><th>Nội dung</th><th>Trạng thái</th><th>Ngày gửi</th><th class="text-center">Thao tác</th></tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr>
                    <td class="ps-3">{{ $contact->id }}</td>
                    <td><strong>{{ $contact->name }}</strong></td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($contact->message, 80) }}</td>
                    <td>
                        @if($contact->status === 'replied')
                            <span class="badge bg-success">Đã phản hồi</span>
                        @else
                            <span class="badge bg-warning text-dark">Chưa phản hồi</span>
                        @endif
                    </td>
                    <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.contact.reply', $contact->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-reply me-1"></i>Phản hồi
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Không có liên hệ nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $contacts->links() }}</div>
@endsection
