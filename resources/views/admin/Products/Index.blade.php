<x-backend>
    <div class="d-flex justify-content-between">
        <h3>Всички продукти</h3>
        <a class="btn btn-secondary p-2 rounded-5" href="{{ route('admin.products.create') }}">
            Създай продукт
        </a>
    </div>
    <hr>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!--Product Start-->
    <section class="product">
        <div class="container">
            <div class="row">

                <div class="col-12">
                    <div class="product__items">

                        {{-- Results header --}}
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="product__showing-result">
                                    <div class="product__showing-text-box">
                                        <p class="product__showing-text">
                                            Показване на
                                            {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                                            от {{ $products->total() }} общо
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Products grid --}}
                        <div class="product__all">
                            <div class="row">

                                @forelse ($products as $product)
                                    <!--Product All Single Start-->
                                    <div class="col-xl-3 col-lg-4 col-md-6">
                                        <div class="product__all-single">
                                            <div class="product__all-img">
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
                                            </div>
                                            <div class="product__all-content">

                                            
                                                <h4 class="product__all-title">
                                                    <a href="{{ route('admin.products.show', $product->slug) }}">
                                                        {{ $product->name }}
                                                    </a>
                                                </h4>

                                                <p class="product__all-price">
                                                    ${{ number_format($product->price, 2) }}
                                                </p>

                                                {{-- Categories --}}
                                                @if ($product->categories->isNotEmpty())
                                                    <p class="small text-muted mb-1">
                                                        {{ $product->categories->pluck('name')->join(', ') }}
                                                    </p>
                                                @endif



                                                <div class="product__all-btn-box d-flex gap-2 flex-column pe-3 ps-3">
                                                    <a class="thm-btn product__all-btn p-2"
                                                        href="{{ route('admin.products.show', $product->slug) }}">
                                                        Редактирай
                                                    </a>

                                                    <form
                                                        action="{{ route('admin.products.destroy', $product) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Сигурен ли си, че искаш да изтриеш този продукт?');"
                                                        class="m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-danger text-white btn-outline-danger btn-sm rounded-5 p-2 w-100">
                                                            Изтрий
                                                        </button>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!--Product All Single End-->
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            Все още няма създадени продукти.
                                            <a href="{{ route('admin.products.create') }}">Създай първия</a>.
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

</x-backend>
