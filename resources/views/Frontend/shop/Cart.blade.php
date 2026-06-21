<x-frontend>
    <!--Start Cart Page-->
    <section class="cart-page">
        <div class="container">
            @if (count($products))
                <div class="table-responsive">
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th>Продукт</th>
                                <th>Цена</th>
                                <th>Количество</th>
                                <th>Общо</th>
                                <th>Премахни</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr>
                                    <td>
                                        <div class="product-box">
                                            <div class="img-box">
                                                <img src="{{ asset('/assets/images/products/' . $product['image']) }}"
                                                    alt="{{ $product['name'] }}" />
                                            </div>

                                            <h3>
                                                <a href="{{ route('shop.show', $product['slug']) }}">
                                                    {{ $product['name'] }}
                                                </a>
                                            </h3>
                                        </div>
                                    </td>

                                    <td>
                                        @if ((int) $product['discount'] > 0)
                                            <del class="text-muted">
                                                {{ number_format($product['price'], 2) }} €
                                            </del>

                                            <span class="text-danger ms-2">
                                                {{ number_format($product['final_price'], 2) }} €
                                            </span>

                                            <span class="badge bg-danger ms-2 rounded-pill text-color">
                                                -{{ $product['discount'] }}%
                                            </span>
                                        @else
                                            {{ number_format($product['price'], 2) }} €
                                        @endif
                                    </td>

                                    <td>
                                        <div class="quantity-box">
                                            <p class="text-center">
                                                {{ $product['quantity'] }}
                                            </p>
                                        </div>
                                    </td>

                                    <td>
                                        {{ number_format($product['final_price'] * $product['quantity'], 2) }} €
                                    </td>

                                    <td>
                                        <form
                                        class="d-flex justify-content-end"
                                        action="{{ route('cart.remove', $key) }}"
                                        method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="cross-icon border-0 bg-transparent">
                                             <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-xl-8 col-lg-7"></div>

                    <div class="col-xl-4 col-lg-5 d-flex flex-column">
                        <a
                         class="thm-btn mb-4"
                         href="{{ route('checkout') }}">
                            Премини към чекаут
                        </a>
                        <ul class="cart-total list-unstyled">
                            <li>
                                <span>Междинна сума</span>
                                <span>{{ number_format($subtotal, 2) }} €</span>
                            </li>



                            <li>
                                <span>Общо</span>
                                <span class="cart-total-amount">
                                    {{ number_format($subtotal, 2) }} €
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-5">
                    Количката е празна.
                </div>
            @endif
        </div>
    </section>
    <!--End Cart Page-->
</x-frontend>
