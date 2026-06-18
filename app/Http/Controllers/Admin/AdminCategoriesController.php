<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdminCategoriesController extends Controller
{


    /** Show all the categories */
    public function index()
    {

        return view('admin.Categories.Index', [
            'categories' => Category::get(),
            'categoriesTree' => Category::tree()
        ]);
    }


    /**
     * Create a category
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ], [
            'name.required'              => 'Името на категорията е задължително.',
            'name.string'                => 'Името трябва да бъде текст.',
            'name.max'                   => 'Името не може да е по-дълго от 255 символа.',
            'category_parent_id.integer' => 'Невалидна родителска категория.',
            'category_parent_id.exists'  => 'Избраната родителска категория не съществува.',
        ]);

        Category::create([
            'name'               => $request->name,
            'slug'               => Str::slug($request->name),
            'category_parent_id' => $request->category_parent_id,
        ]);

        return back()->with('success', 'Успешно създадохте категорията „' . $request->name . '"');
    }
}
