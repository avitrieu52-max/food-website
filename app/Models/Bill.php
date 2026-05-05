<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bills';

    protected $fillable = [
        'id_customer',
        'date_order',
        'total',
        'payment',
        'note',
        'status',
        'coupon_code',
        'discount_amount',
        'shipping_fee',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function details()
    {
        return $this->hasMany(BillDetail::class, 'id_bill');
    }

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

    public function getStatusLabelAttribute()
    {
        return self::statusLabels()[$this->status] ?? ['label' => $this->status, 'class' => 'secondary'];
    }
}

