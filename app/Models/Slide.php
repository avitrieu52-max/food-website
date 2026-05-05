<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slide extends Model
{
    use HasFactory;

    protected $table = 'slides';

    protected $fillable = [
        'title', 'subtitle', 'description', 'image', 'link', 'button_text', 'order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/placeholder.svg');
        }
        if (preg_match('/^https?:\/\//i', $this->image)) {
            return $this->image;
        }
        return asset($this->image);
    }
}
