<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model slide/banner trang chủ (Slide).
 * Mỗi slide hiển thị một ảnh lớn với tiêu đề, mô tả và nút CTA (Call to Action).
 * Thứ tự hiển thị được kiểm soát bởi trường 'order'.
 */
class Slide extends Model
{
    use HasFactory;

    protected $table = 'slides';

    protected $fillable = [
        'title',       // Tiêu đề chính của slide
        'subtitle',    // Tiêu đề phụ (VD: "NEW COLLECTION")
        'description', // Mô tả ngắn hiển thị trên slide
        'image',       // Đường dẫn ảnh nền slide
        'link',        // URL khi click vào nút CTA
        'button_text', // Nội dung nút CTA (VD: "Khám phá ngay")
        'order',       // Thứ tự hiển thị (số nhỏ hơn hiển thị trước)
        'is_active',   // Trạng thái hiển thị (true = đang hiển thị)
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    /**
     * Accessor: lấy URL đầy đủ của ảnh slide.
     * Xử lý cả URL tuyệt đối (từ internet) và đường dẫn tương đối (trong public/).
     * Sử dụng: $slide->image_url
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/placeholder.svg');
        }
        if (preg_match('/^https?:\/\//i', $this->image)) {
            return $this->image; // URL tuyệt đối
        }
        return asset($this->image); // Đường dẫn tương đối
    }
}
