<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = ['name', 'email', 'message', 'status', 'replied_at'];

    protected $casts = [
        'replied_at' => 'datetime',
    ];
}
