<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = [
        'name',
        'slug',
        'category_parent_id',
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 'category_parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_parent_id');
    }

    public static function tree()
    {
        $categories = static::with('children')
            ->whereNull('category_parent_id')
            ->get();

        return self::buildTree($categories);
    }

    private static function buildTree($categories): array
    {
        $tree = [];

        foreach ($categories as $category) {
            $tree[] = [
                'id' => $category->id,
                'name' => $category->name,
                'children' => self::buildTree($category->children)
            ];
        }

        return $tree;
    }
}
