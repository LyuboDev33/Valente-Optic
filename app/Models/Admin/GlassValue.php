<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GlassValue extends Model
{
    protected $fillable = [
        'glass_id',
        'value',
        'price'
    ];

    public function glass(): BelongsTo
    {
        return $this->belongsTo(Glass::class);
    }
}
