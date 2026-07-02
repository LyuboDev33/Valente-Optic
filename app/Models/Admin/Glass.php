<?php

namespace App\Models\Admin;

use App\Models\Admin\GlassValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Glass extends Model
{
    protected $fillable = [
        'name',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(GlassValue::class);
    }
}
