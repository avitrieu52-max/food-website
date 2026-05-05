<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingFee extends Model
{
    use HasFactory;

    protected $table = 'shipping_fees';

    protected $fillable = ['name', 'min_order_value', 'fee', 'is_active'];

    protected $casts = [
        'min_order_value' => 'decimal:2',
        'fee'             => 'decimal:2',
        'is_active'       => 'boolean',
    ];
}
