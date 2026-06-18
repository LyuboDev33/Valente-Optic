<x-frontend>
    <!--Start Product Details-->
    <section class="product-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-6">
                    <div class="product-details__img">
                        <img src="{{ asset('/assets/images/products/' . $product->main_image) }}"
                            alt="{{ $product->name }}" />
                    </div>
                </div>

                <div class="col-lg-6 col-xl-6">
                    <div class="product-details__top">
                        <h3 class="product-details__title">
                            {{ $product->name }}
                            <br>
                            Цена:
                            @if ($product->discount)
                                <span>
                                    <del class="text-muted">
                                        {{ number_format($product->price, 2) }} €
                                    </del>

                                    <span class="text-danger ms-2">
                                        {{ number_format($product->price - ($product->price * $product->discount) / 100, 2) }}
                                        €
                                    </span>

                                    <span class="badge bg-danger ms-2 rounded-pill text-color">
                                        -{{ $product->discount }}%
                                    </span>
                                </span>
                            @else
                                <span>
                                    {{ number_format($product->price, 2) }} €
                                </span>
                            @endif
                        </h3>
                    </div>
                    <div class="product-details__content d-none d-lg-block">
                        <div class="product-details__content-text1">
                            {!! $product->description !!}
                        </div>

                    </div>

                    @if ($product->attributeValues->count())
                        <div class="product-details__attributes mt-4">
                            <h3 class="product-details__quantity-title">Характеристики</h3>

                            <ul class="list-unstyled">
                                @foreach ($product->attributeValues as $attributeValue)
                                    <li>
                                        <p>
                                            <strong>{{ $attributeValue->type?->name }}:</strong>
                                            {{ $attributeValue->value }}
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <hr>
                    <p
                        class="product-details__content-text2 rounded-pill alert {{ (int) $product->stock > 0 ? 'alert-success' : 'alert-danger' }} p-2 d-inline-block">
                        @if ((int) $product->stock > 0)
                            Наличен продукт ({{ $product->stock }} бр.)
                        @else
                            Няма наличност
                        @endif
                    </p>

                    @if ((int) $product->stock > 0)
                        <div class="d-flex mt-3 mb-3 justify-content-center">
                            <div class="product-details__quantity d-flex flex-column">
                                <h3 class="product-details__quantity-title">Изберете брой</h3>

                                <div class="quantity-box">
                                    <button type="button" class="sub">
                                        <i class="fa fa-minus"></i>
                                    </button>

                                    <input type="number" name="quantity" value="1" min="1"
                                        max="{{ (int) $product->stock }}" />

                                    <button type="button" class="add">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="product-details__buttons">
                                <div class="product-details__buttons-2">
                                    <a class="thm-btn" href="#">Добави в количката</a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="product-details__buttons">
                            <button class="thm-btn" disabled>
                                Няма наличност
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!--End Product Details-->

    <!--Start Product Description-->
    <section class="product-description d-lg-none">
        <div class="container">
            <h3 class="product-description__title">Описание</h3>

            <div class="product-description__text1">
                {!! $product->description !!}
            </div>

            {{-- @if ($product->categories->count())
                <div class="product-description__list">
                    <h4>Категории</h4>

                    <ul class="list-unstyled">
                        @foreach ($product->categories as $category)
                            <li>
                                <p>
                                    <span class="icon-arrow-right-two"></span>
                                    {{ $category->name }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}
        </div>
    </section>
    <!--End Product Description-->
</x-frontend>
