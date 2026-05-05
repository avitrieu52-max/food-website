<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model thông tin khách hàng đặt hàng (Customer).
 * Được tạo mới mỗi khi có đơn hàng, lưu thông tin giao hàng.
 * Khác với bảng users (tài khoản đăng nhập), bảng này lưu thông tin nhận hàng.
 */
class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'name',         // Họ tên người nhận hàng
        'gender',       // Giới tính (nam/nữ)
        'email',        // Email nhận thông báo đơn hàng
        'address',      // Địa chỉ giao hàng
        'phone_number', // Số điện thoại liên hệ
        'note',         // Ghi chú thêm cho đơn hàng
    ];
}
