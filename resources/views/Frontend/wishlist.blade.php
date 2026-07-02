<x-frontend>


    @section('SEO')

    @endsection

    
    <!--Start Wishlist Page-->
    <section class="wishlist-page">
        <div class="container">

            <div class="table-responsive-box">
                <table class="wishlist-table">
                    <tbody>

                        @forelse ($wishlist as $product)
                            <tr>

                                {{-- Product Image --}}
                                <td>
                                    <div class="product-box">
                                        <div class="img-box">
                                            <img src="{{ asset('assets/images/products/' . $product['image']) }}"
                                                alt="{{ $product['name'] }}">
                                        </div>
                                    </div>
                                </td>

                                {{-- Product Info --}}
                                <td>
                                    <div class="product-name-select-box">

                                        <div class="product-name">

                                            <h4>
                                                {{ $product['name'] }}
                                            </h4>

                                            <p>

                                                @if (!empty($product['discount']))
                                                    <del class="text-muted me-2">
                                                        {{ number_format($product['price'], 2) }} €
                                                    </del>

                                                    <span class="text-danger">
                                                        {{ number_format($product['final_price'], 2) }} €
                                                    </span>

                                                    (-{{ $product['discount'] }}%)
                                                @else
                                                    {{ number_format($product['price'], 2) }} €
                                                @endif

                                            </p>

                                        </div>

                                        <div class="product-select">
                                            <a class="thm-btn wishlist-page__btn"
                                                href="{{ $product['url'] }}">
                                                Разгледай
                                            </a>
                                        </div>

                                    </div>
                                </td>

                                {{-- Remove --}}
                                <td>
                                    <div class="cross-icon">
                                        <i class="fas fa-times remove-icon"></i>
                                    </div>
                                </td>

                            </tr>
                        @empty

                            <tr>
                                <td colspan="3">

                                    <div class="alert alert-info text-center mb-0">
                                        Все още нямате добавени продукти в любими.
                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    </section>
    <!--End Wishlist Page-->

</x-frontend>
