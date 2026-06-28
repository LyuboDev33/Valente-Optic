<x-frontend>
    <!--Start Product Details-->
    <form action="{{ route('product.cart.add', $product) }}" method="POST" enctype="multipart/form-data"
        class="product-details">
        @csrf

        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-6">
                    <div class="product-details__img">
                        <img data-fancybox="product-gallery"
                            src="{{ asset('/assets/images/products/' . $product->main_image) }}"
                            alt="{{ $product->name }}" />
                    </div>

                    <div class="product-gallery mt-3">

                        {{-- Gallery images --}}
                        @foreach ($product->gallery as $image)
                            <a href="{{ asset('/assets/images/product_gallery/' . $image) }}"
                                class="product-gallery__item" data-fancybox="product-gallery">
                                <img src="{{ asset('/assets/images/product_gallery/' . $image) }}"
                                    alt="{{ $product->name }}">
                            </a>
                        @endforeach

                    </div>

                    <div class="product-description__text1 mt-3 d-none d-lg-block">
                        {!! $product->description !!}
                    </div>
                </div>

                <div class="col-lg-6 col-xl-6">
                    <div class="product-details__top">

                        <h3 class="product-details__title">
                            {{ $product->name }}

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
                    <h6 class="alert alert-info w-fit p-2 rounded-pill mt-3">Номенклатура: {{ $product->sku }}</h6>
                    <hr>

                    @if ($product->variants->isNotEmpty() || $product->variantParent->isNotEmpty())
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">

                                <h5 class="mb-3">
                                    Варианти на продукта
                                </h5>

                                <div class="d-flex flex-wrap gap-3">

                                    @foreach ($product->variantParent as $parent)
                                        <a href="{{ route('shop.show', $parent->slug) }}"
                                            class="product-variant-card">

                                            <img src="{{ asset('assets/images/products/' . $parent->main_image) }}"
                                                alt="{{ $parent->name }}">

                                            <span>Основен</span>
                                        </a>
                                    @endforeach

                                    {{-- Current product --}}
                                    <a href="{{ route('shop.show', $product->slug) }}"
                                        class="product-variant-card active">

                                        <img src="{{ asset('assets/images/products/' . $product->main_image) }}"
                                            alt="{{ $product->name }}">

                                        <span>
                                            @if ($product->variantParent->isEmpty())
                                                Основен
                                            @else
                                                {{ $product->name }}
                                            @endif
                                        </span>
                                    </a>

                                    @foreach ($product->variants as $variant)
                                        <a href="{{ route('shop.show', $variant->slug) }}"
                                            class="product-variant-card">

                                            <img src="{{ asset('assets/images/products/' . $variant->main_image) }}"
                                                alt="{{ $variant->name }}">

                                            <span>{{ $variant->name }}</span>
                                        </a>
                                    @endforeach

                                </div>

                            </div>
                        </div>
                    @endif


                    <p
                        class="product-details__content-text2 mb-3 rounded-pill alert {{ (int) $product->stock > 0 ? 'alert-success' : 'alert-danger' }} p-2 d-inline-block">
                        @if ((int) $product->stock > 0)
                            Наличен продукт
                        @else
                            Няма наличност
                        @endif
                    </p>

                    @error('stock')
                        <p class="field-error">{{ $message }}</p>
                    @enderror

                    @if ((int) $product->stock > 0)


                        @if ($isProductDioptric)

                            <div class="prescription-box mt-4 mb-4">
                                <p class="prescription-box__notice">
                                    За да добавите този продукт в количката е нужно да предоставите снимка с рецепта
                                    за диоптър или въведете ръчно данните ако ги знаете.
                                </p>

                                @error('prescription')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror

                                <ul class="nav nav-tabs prescription-tabs" id="prescriptionTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="upload-prescription-tab"
                                            data-bs-toggle="tab" data-bs-target="#upload-prescription" type="button"
                                            role="tab">
                                            Качи рецепта
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="manual-prescription-tab" data-bs-toggle="tab"
                                            data-bs-target="#manual-prescription" type="button" role="tab">
                                            Избери ръчно
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="prescriptionTabsContent">
                                    <div class="tab-pane fade show active" id="upload-prescription" role="tabpanel"
                                        aria-labelledby="upload-prescription-tab">

                                        <div class="prescription-upload">
                                            <label for="prescription_image" class="form-label fw-bold">
                                                Прикачете рецепта
                                            </label>

                                            <input type="file" id="prescription_image" name="prescription_image"
                                                class="form-control @error('prescription_image') is-invalid @enderror"
                                                accept="image/*,.pdf">

                                            @error('prescription_image')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="manual-prescription" role="tabpanel"
                                        aria-labelledby="manual-prescription-tab">

                                        <table class="prescription-table">
                                            <thead>
                                                <tr>
                                                    <th>Око</th>
                                                    <th>Сфера (SPH)</th>
                                                    <th>Цилиндър (CYL)</th>
                                                    <th>Градус (AXIS)</th>
                                                    <th>ADD</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td data-label="Око">
                                                        <strong>Дясно (OD)</strong>
                                                    </td>

                                                    <td data-label="Сфера (SPH)">
                                                        <select name="right_eye[sph]" class="form-select">
                                                            <option value="">Изберете</option>
                                                            @foreach ($sphValues as $value)
                                                                <option value="{{ $value }}"
                                                                    @selected(old('right_eye.sph') == $value)>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td data-label="Цилиндър (CYL)">
                                                        <select name="right_eye[cyl]" class="form-select">
                                                            <option value="">Изберете</option>
                                                            @foreach ($cylValues as $value)
                                                                <option value="{{ $value }}"
                                                                    @selected(old('right_eye.cyl') == $value)>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td data-label="Градус (AXIS)">
                                                        <select name="right_eye[axis]" class="form-select">
                                                            <option value="">Изберете</option>
                                                            @foreach ($axisValues as $value)
                                                                <option value="{{ $value }}"
                                                                    @selected(old('right_eye.axis') == $value)>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td data-label="ADD">
                                                        <select name="right_eye[add]" class="form-select">
                                                            <option value="">Изберете</option>
                                                            @foreach ($addValues as $value)
                                                                <option value="{{ $value }}"
                                                                    @selected(old('right_eye.add') == $value)>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td data-label="Око">
                                                        <strong>Ляво (OS)</strong>
                                                    </td>

                                                    <td data-label="Сфера (SPH)">
                                                        <select name="left_eye[sph]" class="form-select">
                                                            <option value="">Изберете</option>
                                                            @foreach ($sphValues as $value)
                                                                <option value="{{ $value }}"
                                                                    @selected(old('left_eye.sph') == $value)>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td data-label="Цилиндър (CYL)">
                                                        <select name="left_eye[cyl]" class="form-select">
                                                            <option value="">Изберете</option>
                                                            @foreach ($cylValues as $value)
                                                                <option value="{{ $value }}"
                                                                    @selected(old('left_eye.cyl') == $value)>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td data-label="Градус (AXIS)">
                                                        <select name="left_eye[axis]" class="form-select">
                                                            <option value="">Изберете</option>
                                                            @foreach ($axisValues as $value)
                                                                <option value="{{ $value }}"
                                                                    @selected(old('left_eye.axis') == $value)>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td data-label="ADD">
                                                        <select name="left_eye[add]" class="form-select">
                                                            <option value="">Изберете</option>
                                                            @foreach ($addValues as $value)
                                                                <option value="{{ $value }}"
                                                                    @selected(old('left_eye.add') == $value)>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        @endif

                        <div class="d-flex mt-3 mb-3 justify-content-center">
                            <div class="product-details__quantity d-flex flex-column">
                                <h3 class="product-details__quantity-title">Изберете брой</h3>

                                <div class="quantity-box">
                                    <button type="button" class="sub">
                                        <i class="fa fa-minus"></i>
                                    </button>

                                    <input type="number" name="quantity" value="{{ old('quantity', 1) }}"
                                        min="1" max="{{ (int) $product->stock }}" />

                                    <button type="button" class="add">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>

                                @error('quantity')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="product-details__buttons">
                                <div class="product-details__buttons-2">
                                    <button type="submit" class="thm-btn">
                                        Добави в количката
                                    </button>
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
    </form>
    <!--End Product Details-->

    <!--Start Product Description-->
    <section class="product-description d-lg-none">
        <div class="container">
            <h3 class="product-description__title">Описание</h3>

            <div class="product-description__text1">
                {!! $product->description !!}
            </div>
        </div>
    </section>
    <!--End Product Description-->

    @if (session('success'))
        <div class="modal fade cart-feedback-modal" id="cartSuccessModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content position-relative">
                    <button type="button" class="btn-close cart-feedback-modal__close" data-bs-dismiss="modal"
                        aria-label="Close"></button>

                    <div class="modal-body text-center p-5">
                        <div class="cart-feedback-modal__icon cart-feedback-modal__icon--success">
                            <i class="fas fa-check"></i>
                        </div>

                        <h4>Продуктът е добавен</h4>

                        <p class="mb-0">
                            {{ session('success') }}
                        </p>

                        <div class="cart-feedback-modal__actions">
                            <a href="{{ route('checkout') }}" class="thm-btn">
                                Към поръчка
                            </a>

                            <a href="{{ route('cart') }}" class="thm-btn">
                                Към количката
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- @if ($errors->any())
        <div class="modal fade cart-feedback-modal" id="cartErrorModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content position-relative">
                    <button type="button" class="btn-close cart-feedback-modal__close" data-bs-dismiss="modal"
                        aria-label="Close"></button>

                    <div class="modal-body text-center p-5">
                        <div class="cart-feedback-modal__icon cart-feedback-modal__icon--error">
                            <i class="fas fa-exclamation"></i>
                        </div>

                        <h4>Неуспешно добавяне</h4>


                        <div class="cart-feedback-modal__actions">
                            <button type="button" class="thm-btn" data-bs-dismiss="modal">
                                Затвори
                            </button>

                            <a href="{{ route('cart') }}" class="thm-btn">
                                Към количката
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                new bootstrap.Modal(document.getElementById('cartSuccessModal')).show();
            @endif

            @if ($errors->any())
                new bootstrap.Modal(document.getElementById('cartErrorModal')).show();
            @endif
        });
    </script>
</x-frontend>
