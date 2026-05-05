<div class="product-item">
    <div class="product-img position-relative">
        <a href="{{ route('banhang.chitiet', $food->id) }}">
            <img src="{{ $food->image_url }}"
                 alt="{{ $food->name }}"
                 loading="lazy"
                 style="image-rendering: -webkit-optimize-contrast;">
        </a>

        {{-- Nút yêu thích - góc trên phải, cân đối --}}
        @auth
        <form action="{{ route('wishlist.toggle', $food->id) }}" method="POST"
              class="wishlist-btn-wrap position-absolute"
              style="top:10px; right:10px; z-index:5;">
            @csrf
            @php $inWishlist = in_array($food->id, auth()->user()->wishlistFoodIds()); @endphp
            <button type="submit"
                    class="wishlist-btn {{ $inWishlist ? 'active' : '' }}"
                    title="{{ $inWishlist ? 'Bỏ yêu thích' : 'Thêm yêu thích' }}">
                <i class="fas fa-heart"></i>
            </button>
        </form>
        @endauth

        {{-- Badge sale --}}
        @if($food->sale_price)
        <span class="position-absolute top-0 start-0 m-2 badge"
              style="background:#e74c3c; font-size:0.75rem; padding:5px 8px; border-radius:6px;">
            SALE
        </span>
        @endif
    </div>

    <div class="product-info">
        <h5>
            <a href="{{ route('banhang.chitiet', $food->id) }}" class="text-decoration-none text-dark">
                {{ $food->name }}
            </a>
        </h5>
        <p class="text-muted small mb-2">{{ Str::limit($food->description, 55) }}</p>

        <div class="d-flex align-items-center justify-content-between mt-auto pt-2">
            <div>
                @if($food->sale_price)
                    <div class="price mb-0">{{ number_format($food->sale_price) }}đ</div>
                    <div class="original-price"><s>{{ number_format($food->price) }}đ</s></div>
                @else
                    <div class="price mb-0">{{ number_format($food->price) }}đ</div>
                @endif
            </div>
            <div class="d-flex gap-1 align-items-center">
                <a href="{{ route('banhang.addtocart', $food->id) }}"
                   class="btn btn-success btn-sm add-to-cart-btn"
                   data-id="{{ $food->id }}"
                   data-url="{{ route('banhang.addtocart', $food->id) }}"
                   title="Thêm vào giỏ"
                   style="width:36px; height:36px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                    <i class="fas fa-shopping-cart" style="font-size:0.85rem;"></i>
                </a>
                <a href="{{ route('banhang.chitiet', $food->id) }}"
                   class="btn btn-outline-success btn-sm"
                   style="height:36px; padding:0 10px; display:flex; align-items:center; border-radius:8px; font-size:0.8rem; white-space:nowrap;">
                    Chi tiết
                </a>
            </div>
        </div>
    </div>
</div>
