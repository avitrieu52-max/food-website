<div class="product-item">
    <div class="product-img">
        <a href="{{ route('banhang.chitiet', $food->id) }}">
            <img src="{{ $food->image_url }}" alt="{{ $food->name }}">
        </a>
    </div>
    <div class="product-info">
        <h5>
            <a href="{{ route('banhang.chitiet', $food->id) }}" class="text-decoration-none text-dark">
                {{ $food->name }}
            </a>
        </h5>
        <p>{{ Str::limit($food->description, 60) }}</p>
        <div class="d-flex align-items-center justify-content-between mt-auto">
            <div>
                @if($food->sale_price)
                    <div class="price">{{ number_format($food->sale_price) }}đ</div>
                    <div class="original-price"><small><s>{{ number_format($food->price) }}đ</s></small></div>
                @else
                    <div class="price">{{ number_format($food->price) }}đ</div>
                @endif
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('banhang.addtocart', $food->id) }}" class="btn btn-success btn-sm" title="Thêm vào giỏ">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <a href="{{ route('banhang.chitiet', $food->id) }}" class="btn btn-outline-success btn-sm">Chi tiết</a>
            </div>
        </div>
    </div>
</div>