<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProductVariants extends Model
{

    protected $fillable = [
        'variant_product_id',
        'parent_product_id'
    ];


}
