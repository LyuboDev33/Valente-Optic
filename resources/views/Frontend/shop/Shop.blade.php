<x-frontend>

    @section('SEO')
        <title>
            {{ $category ? $category->name . ' — Магазин' : 'Магазин' }} | Valente Optic
        </title>
        <meta name="description" content="Разгледайте нашата колекция от диоптрични рамки, слънчеви очила и стъкла във Valente Optic.">
    @endsection



    <!--Product Start-->
    <section class="product fe-section">
        <div class="container">
            <div class="row">

                {{-- ============ SIDEBAR ============ --}}
                <div class="col-xl-3 col-lg-12">
                    <div class="product__sidebar">



                        {{-- Categories tree --}}
                        <div class="shop-category product__sidebar-single">
                            <h3 class="product__sidebar-title">Категории</h3>

                            <ul class="list-unstyled shop-category__tree">

                                {{-- "All products" entry --}}
                                <li class="{{ ! $category ? 'active' : '' }}">
                                    <a href="{{ route('shop.index') }}">
                                        Всички продукти
                                    </a>
                                </li>

                                {{-- Recursive tree --}}
                                @foreach ($categoriesTree as $node)
                                    @include('Frontend.shop.partials.category-tree', [
                                        'node'       => $node,
                                        'activeSlug' => $category?->slug,
                                    ])
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>

                {{-- ============ PRODUCTS GRID ============ --}}
                <div class="col-xl-9 col-lg-12">
                    <div class="product__items">

                        {{-- Results count --}}
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="product__showing-result">
                                    <div class="product__showing-text-box">
                                        <p class="product__showing-text">
                                            Показване на
                                            {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                                            от {{ $products->total() }} продукта
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Products --}}
                        <div class="product__all">
                            <div class="row">

                                @forelse ($products as $product)
                                    <!--Product Single Start-->
                                    <div class="col-xl-4 col-lg-4 col-md-6">
                                        <div class="product__all-single">

                                            <div class="product__all-img">
                                                <a href="{{ route('shop.show', $product->slug) }}">
                                                    @if ($product->main_image)
                                                        <img src="{{ asset('assets/images/products/' . $product->main_image) }}"
                                                             alt="{{ $product->name }}" />
                                                        <img src="{{ asset('assets/images/products/' . $product->main_image) }}"
                                                             alt="{{ $product->name }}" />
                                                    @else
                                                        <img src="{{ asset('assets/images/shop/shop-product-1-1.jpg') }}"
                                                             alt="{{ $product->name }}" />
                                                        <img src="{{ asset('assets/images/shop/shop-product-1-1.jpg') }}"
                                                             alt="{{ $product->name }}" />
                                                    @endif
                                                </a>
                                            </div>

                                            <div class="product__all-content">

                                                @if ($product->categories->isNotEmpty())
                                                    <p class="small text-muted mb-1">
                                                        {{ $product->categories->pluck('name')->join(' · ') }}
                                                    </p>
                                                @endif

                                                <h4 class="product__all-title">
                                                    <a href="{{ route('shop.show', $product->slug) }}">
                                                        {{ $product->name }}
                                                    </a>
                                                </h4>

                                                <p class="product__all-price">
                                                    {{ number_format($product->price, 2) }} €.
                                                </p>

                                                <div class="product__all-btn-box">
                                                    <a class="thm-btn product__all-btn"
                                                       href="{{ route('shop.show', $product->slug) }}">
                                                        Разгледай
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!--Product Single End-->
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-info text-center">
                                            @if ($category)
                                                В категория „{{ $category->name }}" все още няма продукти.
                                            @else
                                                Все още няма налични продукти.
                                            @endif
                                        </div>
                                    </div>
                                @endforelse

                            </div>
                        </div>

                        {{-- Pagination --}}
                        @if ($products->hasPages())
                            <div class="row mt-4">
                                <div class="col-12 d-flex justify-content-center">
                                    {{ $products->links() }}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--Product End-->

</x-frontend>
