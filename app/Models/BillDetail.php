<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model chi tiết đơn hàng (BillDetail).
 * Lưu từng dòng sản phẩm trong một đơn hàng:
 * sản phẩm nào, số lượng bao nhiêu, giá bao nhiêu.
 */
class BillDetail extends Model
{
    use HasFactory;

    protected $table = 'bill_details';

    protected $fillable = [
        'id_bill',    // ID đơn hàng (liên kết bảng bills)
        'id_product', // ID sản phẩm (liên kết bảng t_food)
        'quantity',   // Số lượng đặt
        'unit_price', // Giá đơn vị tại thời điểm đặt hàng
    ];

    /**
     * Quan hệ: chi tiết đơn hàng thuộc về một đơn hàng.
     */
    public function bill()
    {
        return $this->belongsTo(Bill::class, 'id_bill');
    }

    /**
     * Quan hệ: chi tiết đơn hàng liên kết với một sản phẩm.
     */
    public function food()
    {
        return $this->belongsTo(Food::class, 'id_product');
    }
}
