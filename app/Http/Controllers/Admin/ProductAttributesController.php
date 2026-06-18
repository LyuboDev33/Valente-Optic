<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeType;
use App\Models\AttributeValue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductAttributesController extends Controller
{
    /** Show all attribute types with their values */
    public function index(): View
    {
        $types = AttributeType::with('values')
            ->orderBy('name')
            ->get();
        return view('admin.ProductAttributes.Index', compact('types'));
    }


    public function storeType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:attribute_types,name'],
        ]);

        AttributeType::create($validated);

        return back()->with('success', 'Типът атрибут беше създаден.');
    }

    public function destroyType(AttributeType $type): RedirectResponse
    {
        $type->delete();
        return back()->with('success', 'Атрибутът беше изтрит.');
    }


    public function storeValue(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'attribute_type_id' => ['required', 'exists:attribute_types,id'],
            'value' => [
                'required', 'string', 'max:255',
                Rule::unique('attribute_values', 'value')->where(
                    fn ($q) => $q->where('attribute_type_id', $request->attribute_type_id)
                ),
            ],
        ], [
            'value.unique' => 'Тази стойност вече съществува.',
        ]);

        AttributeValue::create($validated);

        return back()->with('success', 'Атрибутът беше създаден.');
    }

    public function destroyValue(AttributeValue $value): RedirectResponse
    {
        $value->delete();
        return back()->with('success', 'Атрибутът беше изтрит.');
    }
}
