<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'short_description',
        'description',
        'main_image',
        'image_1',
        'image_2',
        'image_3',
        'image_4',
        'image_5',
        'video_url',
        'price',
        'discount',
        'net_price',
        'unit',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'net_price' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
