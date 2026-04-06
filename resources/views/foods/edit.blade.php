@extends('layouts.app')

@section('title', "Chỉnh sửa - $food->name")

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Chỉnh sửa sản phẩm: {{ $food->name }}</h3>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="error-message">
                        <strong>Có lỗi xảy ra:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('foods.update', $food) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $food->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" id="slug" 
                               class="form-control @error('slug') is-invalid @enderror" 
                               value="{{ old('slug', $food->slug) }}" required>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('category', $food->category) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Giá gốc (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" id="price" 
                               class="form-control @error('price') is-invalid @enderror" 
                               value="{{ old('price', $food->price) }}" required step="0.01">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sale_price" class="form-label">Giá khuyến mãi (VNĐ)</label>
                        <input type="number" name="sale_price" id="sale_price" 
                               class="form-control @error('sale_price') is-invalid @enderror" 
                               value="{{ old('sale_price', $food->sale_price) }}" step="0.01">
                        @error('sale_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Số lượng tồn kho</label>
                        <input type="number" name="stock" id="stock" 
                               class="form-control @error('stock') is-invalid @enderror" 
                               value="{{ old('stock', $food->stock) }}">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $food->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh</label>
                        @if($food->image_url)
                            <div class="mb-2">
                                <strong>Ảnh hiện tại:</strong>
                                <div class="mt-2 mb-3">
                                    <img src="{{ $food->image_url }}" alt="{{ $food->name }}" style="max-width: 200px; border-radius: 0.5rem;">
                                </div>
                            </div>
                        @endif
                        <input type="file" name="image" id="image" 
                               class="form-control @error('image') is-invalid @enderror" 
                               accept="image/*">
                        <small class="form-text text-muted">Để trống nếu không muốn thay đổi ảnh</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_featured" id="is_featured" 
                               class="form-check-input" value="1" {{ old('is_featured', $food->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="status" id="status" 
                               class="form-check-input" value="1" {{ old('status', $food->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Hiển thị sản phẩm</label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                        <a href="{{ route('foods.show', $food) }}" class="btn btn-secondary">Xem chi tiết</a>
                        <a href="{{ route('foods.index') }}" class="btn btn-outline-secondary">Quay lại danh sách</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
