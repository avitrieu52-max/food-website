@extends('admin.master')

@section('title', 'Sửa danh mục')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="mb-4">Sửa danh mục</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.postCateEdit', $cate->id) }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $cate->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $cate->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">URL ảnh</label>
                        <input type="url" class="form-control" id="image" name="image" value="{{ old('image', $cate->image) }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.getCateList') }}" class="btn btn-secondary ms-2">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
