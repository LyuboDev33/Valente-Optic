<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    protected $table = 'promocodes';

    protected $fillable = [
        'is_active',
        'promo_code_name',
        'percentage_promo_code',
    ];

 
}
