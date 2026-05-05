<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model đơn hàng (Bill/Hóa đơn).
 * Lưu thông tin đơn hàng: khách hàng, tổng tiền, phương thức thanh toán,
 * trạng thái, mã giảm giá và phí vận chuyển.
 */
class Bill extends Model
{
    use HasFactory;

    protected $table = 'bills';

    protected $fillable = [
        'id_customer',    // ID khách hàng (liên kết bảng customers)
        'date_order',     // Ngày đặt hàng
        'total',          // Tổng tiền cuối (đã tính phí ship và giảm giá)
        'payment',        // Phương thức thanh toán
        'note',           // Ghi chú của khách
        'status',         // Trạng thái đơn hàng
        'coupon_code',    // Mã giảm giá đã áp dụng
        'discount_amount', // Số tiền được giảm
        'shipping_fee',   // Phí vận chuyển
    ];

    /**
     * Quan hệ: đơn hàng thuộc về một khách hàng.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    /**
     * Quan hệ: đơn hàng có nhiều chi tiết sản phẩm.
     */
    public function details()
    {
        return $this->hasMany(BillDetail::class, 'id_bill');
    }

    /**
     * Danh sách trạng thái đơn hàng kèm nhãn tiếng Việt và màu badge Bootstrap.
     */
    public static function statusLabels()
    {
        return [
            'pending'   => ['label' => 'Chờ xác nhận', 'class' => 'warning'],
            'confirmed' => ['label' => 'Đã xác nhận',  'class' => 'info'],
            'shipping'  => ['label' => 'Đang giao',    'class' => 'primary'],
            'delivered' => ['label' => 'Đã giao',      'class' => 'success'],
            'cancelled' => ['label' => 'Đã hủy',       'class' => 'danger'],
        ];
    }

    /**
     * Accessor: lấy nhãn trạng thái của đơn hàng hiện tại.
     * Sử dụng: $bill->status_label['label'] hoặc $bill->status_label['class']
     */
    public function getStatusLabelAttribute()
    {
        return self::statusLabels()[$this->status] ?? ['label' => $this->status, 'class' => 'secondary'];
    }
}
