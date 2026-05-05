<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'type_products';

    protected $fillable = ['name', 'slug', 'description', 'image', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function foods()
    {
        return $this->hasMany(Food::class, 'category_id');
    }
}
