<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',

        'product_name',
        'product_slug',
        'product_image',

        'price',
        'discount',
        'final_price',
        'quantity',

        'prescription_image',
        'right_eye',
        'left_eye',
    ];

    protected $casts = [
        'price' => 'float',
        'final_price' => 'float',
        'discount' => 'integer',
        'quantity' => 'integer',

        'right_eye' => 'array',
        'left_eye' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }



    
}
