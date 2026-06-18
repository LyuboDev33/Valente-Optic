<x-backend>

    <div class="d-flex justify-content-between">
        <h3>Редактиране на продукт: {{ $product->name }}</h3>
        <div>
            <a target="_blank" class="btn btn-info p-2 rounded-5 text-white" href="{{ route('shop.show', $product->slug) }}">
                Преглед на продукта
            </a>
            <a class="btn btn-secondary p-2 rounded-5" href="{{ route('admin.products.index') }}">
                Назад към всички
            </a>
        </div>
    </div>
    <hr>

    {{-- ================= FLASH MESSAGES ================= --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ================= PRODUCT EDIT FORM ================= --}}
    <div class="container">

        <form method="POST" action="{{ route('admin.product.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">

                {{-- ================= BASIC INFO ================= --}}
                <div class="col-lg-4">
                    <label>Име на продукта</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}"
                        required>
                </div>

                <div class="col-lg-4">
                    <label>Категория</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Избери категория</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category['id'] }}"
                                {{ old('category_id', $product->category_id) == $category['id'] ? 'selected' : '' }}>
                                {{ $category['path'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <label>Наличност</label>
                    <input type="text" name="stock" class="form-control"
                        value="{{ old('stock', $product->stock) }}">
                </div>

                    <div class="col-lg-2">
                    <label>Отстъпка</label>
                    <input type="number" name="discount" class="form-control" value="{{ old('discount', $product->discount) }}">
                </div>

                <div class="col-lg-12">
                    <label>Описание на продукта</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- ================= PRICING ================= --}}
                <div class="col-lg-4">
                    <label>Цена</label>
                    <input type="number" step="0.01" name="price" class="form-control"
                        value="{{ old('price', $product->price) }}" required>
                </div>

                {{-- ================= MAIN IMAGE ================= --}}
                <div class="col-lg-4">
                    <label>Главна снимка</label>

                    @if ($product->main_image)
                        <div class="mb-2">
                            <img src="{{ asset('assets/images/products/' . $product->main_image) }}"
                                alt="{{ $product->name }}" style="max-height: 80px; border-radius: 8px;">
                        </div>
                    @endif

                    <input type="file" name="main_image" class="form-control" accept="image/*">
                    <small class="text-muted">Качи нова снимка, за да замениш текущата.</small>
                </div>

                {{-- ================= GALLERY ================= --}}
                <div class="col-lg-4">
                    <label>Галерия (множество снимки)</label>

                    @if (!empty($product->gallery))
                        <div class="mb-2 d-flex flex-wrap gap-2">
                            @foreach ($product->gallery as $galleryImage)
                                <img src="{{ asset('assets/images/product_gallery/' . $galleryImage) }}"
                                    alt="Gallery image" style="max-height: 80px; border-radius: 6px;">
                            @endforeach
                        </div>
                    @endif

                    <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">Качването на нови снимки ще ги добави към съществуващите.</small>
                </div>

                @if ($attributeTypes->isNotEmpty())
                    <div class="col-lg-12">
                        <hr>
                        <h5 class="mb-3">Атрибути на продукта</h5>
                    </div>

                    @foreach ($attributeTypes as $type)
                        <div class="col-lg-4">
                            <label>
                                {{ $type->name }}
                                @if ($type->is_multiple)
                                    <small class="text-muted">(множествен избор)</small>
                                @endif
                            </label>

                            @php
                                // Fall back chain: old input > already-selected on this product > nothing
                                $currentlySelected = old('attribute_values', $selectedAttributeValueIds);
                            @endphp

                            @if ($type->is_multiple)
                                {{-- multi-select --}}
                                <select name="attribute_values[]" class="form-control" multiple size="4">
                                    @foreach ($type->values as $value)
                                        <option value="{{ $value->id }}"
                                            {{ in_array($value->id, $currentlySelected) ? 'selected' : '' }}>
                                            {{ $value->value }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                {{-- single-select --}}
                                <select name="attribute_values[]" class="form-control">
                                    <option value="">— Избери —</option>
                                    @foreach ($type->values as $value)
                                        <option value="{{ $value->id }}"
                                            {{ in_array($value->id, $currentlySelected) ? 'selected' : '' }}>
                                            {{ $value->value }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    @endforeach
                @endif

                {{-- ================= SUBMIT + DELETE ================= --}}
                <div class="col-lg-12 d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary rounded-5 px-4">
                        Запази промените
                    </button>
                </div>

            </div>
        </form>

        <form method="POST" action="{{ route('admin.products.destroy', $product->slug) }}"
            onsubmit="return confirm('Сигурен ли си, че искаш да изтриеш този продукт?');" class="mt-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger rounded-5 px-4">
                Изтрий продукта
            </button>
        </form>

    </div>

</x-backend>
