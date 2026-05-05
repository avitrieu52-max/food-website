@extends('admin.master')
@section('title', 'Phản hồi liên hệ')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-reply me-2"></i>Phản hồi liên hệ</h3>
    <a href="{{ route('admin.contact.list') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Quay lại</a>
</div>
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="row g-4">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white fw-bold">Thông tin liên hệ</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:35%">Họ tên:</th><td>{{ $contact->name }}</td></tr>
                    <tr><th class="text-muted">Email:</th><td>{{ $contact->email }}</td></tr>
                    <tr><th class="text-muted">Ngày gửi:</th><td>{{ $contact->created_at->format('d/m/Y H:i') }}</td></tr>
                    <tr><th class="text-muted">Trạng thái:</th>
                        <td>
                            @if($contact->status === 'replied')
                                <span class="badge bg-success">Đã phản hồi</span>
                            @else
                                <span class="badge bg-warning text-dark">Chưa phản hồi</span>
                            @endif
                        </td>
                    </tr>
                </table>
                <hr>
                <strong>Nội dung tin nhắn:</strong>
                <div class="mt-2 p-3 bg-light rounded">{{ $contact->message }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white fw-bold">Soạn phản hồi</div>
            <div class="card-body">
                <form action="{{ route('admin.contact.reply.send', $contact->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gửi đến: <span class="text-primary">{{ $contact->email }}</span></label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nội dung phản hồi <span class="text-danger">*</span></label>
                        <textarea name="reply_message" class="form-control" rows="8" required placeholder="Nhập nội dung phản hồi...">{{ old('reply_message') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-paper-plane me-2"></i>Gửi phản hồi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
