<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model liên hệ (Contact).
 * Lưu thông tin khách hàng gửi form liên hệ từ trang chủ.
 * Trạng thái: 'unread' (chưa đọc) → 'replied' (đã phản hồi).
 */
class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'name',       // Họ tên người liên hệ
        'email',      // Email để admin phản hồi
        'message',    // Nội dung liên hệ
        'status',     // Trạng thái: 'unread' hoặc 'replied'
        'replied_at', // Thời điểm admin gửi phản hồi
    ];

    protected $casts = [
        'replied_at' => 'datetime', // Tự động cast sang Carbon datetime
    ];
}
