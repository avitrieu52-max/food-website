@extends('admin.master')

@section('title', 'Thêm sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fas fa-plus me-2"></i>Thêm sản phẩm mới</h3>
    <a href="{{ route('admin.food.list') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Quay lại
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <form action="{{ route('admin.food.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Giá gốc (đ) <span class="text-danger">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" class="form-control" min="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Giá khuyến mãi (đ)</label>
                    <input type="number" name="sale_price" value="{{ old('sale_price') }}" class="form-control" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tồn kho <span class="text-danger">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" class="form-control" min="0" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Hình ảnh</label>
                    <input type="file" name="image" class="form-control" accept="image/*"
                           onchange="previewImage(this)">
                    <img id="imagePreview" src="#" alt="Preview" class="mt-2 rounded d-none"
                         style="max-height:150px;">
                </div>
                <div class="col-md-6 d-flex align-items-end gap-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                               {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="status"
                               {{ old('status', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Hiển thị</label>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-success px-5">
                        <i class="fas fa-save me-2"></i>Lưu sản phẩm
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.classList.remove('d-none'); };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
