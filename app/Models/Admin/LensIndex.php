<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class LensIndex extends Model
{
    protected $table = 'lens_indexes';

    protected $fillable = [
        'name',
        'price'
    ];


}
