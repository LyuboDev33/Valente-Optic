<?php

namespace App\Models;

use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'slug',
        'stock',
        'discount',
        'category_id',
        'description',
        'price',
        'main_image',
        'gallery',
    ];

    protected $casts = [
        'gallery'          => 'array',
    ];


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_value')->withTimestamps();
    }

}
