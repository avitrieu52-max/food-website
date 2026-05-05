@extends('admin.master')
@section('title', 'Sửa Loại sản phẩm')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-edit me-2"></i>Sửa Loại sản phẩm</h3>
    <a href="{{ route('admin.getCateList') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Quay lại</a>
</div>
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <form action="{{ route('admin.postCateEdit', $cate->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $cate->name) }}" class="form-control" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $cate->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Hiển thị</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $cate->description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Hình ảnh đại diện</label>
                    @if($cate->image)
                        <div class="mb-2"><img src="{{ asset($cate->image) }}" class="rounded" style="max-height:100px;" id="imgPreview"></div>
                    @else
                        <img id="imgPreview" src="#" class="mt-2 rounded d-none mb-2" style="max-height:100px;">
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImg(this)">
                    <small class="text-muted">Để trống nếu không muốn thay đổi ảnh</small>
                </div>
                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-warning px-5"><i class="fas fa-save me-2"></i>Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
function previewImg(input) {
    const p = document.getElementById('imgPreview');
    if (input.files && input.files[0]) {
        const r = new FileReader();
        r.onload = e => { p.src = e.target.result; p.classList.remove('d-none'); };
        r.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
