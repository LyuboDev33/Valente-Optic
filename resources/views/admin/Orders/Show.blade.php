<x-backend>

    <section class="content-main">

        <div class="content-header mb-4">
            <div>
                <h2 class="content-title card-title">Детайли на поръчка</h2>
                <p>Информация за поръчка №: {{ $order->order_number }}</p>
            </div>
        </div>

        <div class="card">

            <header class="card-header">

                <div class="row align-items-center">

                    <div class="col-lg-6 col-md-6 mb-lg-0 mb-3">

                        <span>
                            <i class="fa fa-calendar"></i>

                            <strong>
                                {{ $order->created_at->format('d.m.Y H:i') }}
                            </strong>
                        </span>

                        <br>

                        <small class="text-muted">
                            № Поръчка: {{ $order->order_number }}
                        </small>

                    </div>

                    <div class="col-lg-6 col-md-6 text-md-end">

                        @switch($order->status)

                            @case(\App\Models\Order::STATUS_PENDING)
                                <span class="badge bg-warning text-dark p-2">
                                    Чакаща
                                </span>
                            @break

                            @case(\App\Models\Order::STATUS_PROCESSING)
                                <span class="badge bg-info text-dark p-2">
                                    В обработка
                                </span>
                            @break

                            @case(\App\Models\Order::STATUS_DELIVERED)
                                <span class="badge bg-success p-2">
                                    Доставена
                                </span>
                            @break

                            @case(\App\Models\Order::STATUS_CANCELLED)
                                <span class="badge bg-danger p-2">
                                    Отказана
                                </span>
                            @break

                            @default
                                <span class="badge bg-secondary p-2">
                                    {{ $order->status }}
                                </span>

                        @endswitch

                    </div>

                </div>

            </header>

            <div class="card-body">

                <div class="row mt-3 order-info-wrap">

                    <div class="col-md-4">

                        <article class="d-flex align-items-start gap-3">

                            <span
                                class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                style="width: 42px; height: 42px;"
                            >
                                <i class="fa fa-user text-white"></i>
                            </span>

                            <div>

                                <h6 class="mb-1">
                                    Клиент
                                </h6>

                                <p class="mb-1">

                                    {{ $order->first_name }}
                                    {{ $order->last_name }}

                                    <br>

                                    {{ $order->email }}

                                    <br>

                                    {{ $order->phone }}

                                </p>

                            </div>

                        </article>

                    </div>

                    <div class="col-md-4">

                        <article class="d-flex align-items-start gap-3">

                            <span
                                class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                style="width: 42px; height: 42px;"
                            >
                                <i class="fa fa-truck text-white"></i>
                            </span>

                            <div>

                                <h6 class="mb-1">
                                    Информация за поръчка
                                </h6>

                                <p class="mb-1">

                                    Доставка:

                                    @if ($order->delivery_method === 'office')

                                        До офис на куриер Speedy

                                    @else

                                        До адрес

                                    @endif

                                    <br>

                                    Плащане:

                                    @if ($order->payment_option === 'cash_on_delivery')

                                        При получаване

                                    @else

                                        {{ $order->payment_option }}

                                    @endif

                                    <br>

                                    Статус:

                                    @switch($order->status)

                                        @case(\App\Models\Order::STATUS_PENDING)
                                            Чакаща
                                        @break

                                        @case(\App\Models\Order::STATUS_PROCESSING)
                                            В обработка
                                        @break

                                        @case(\App\Models\Order::STATUS_DELIVERED)
                                            Доставена
                                        @break

                                        @case(\App\Models\Order::STATUS_CANCELLED)
                                            Отказана
                                        @break

                                        @default
                                            {{ $order->status }}

                                    @endswitch

                                </p>

                            </div>

                        </article>

                    </div>

                    <div class="col-md-4">

                        <article class="d-flex align-items-start gap-3">

                            <span
                                class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                style="width: 42px; height: 42px;"
                            >
                                <i class="fa fa-location-dot text-white"></i>
                            </span>

                            <div>

                                <h6 class="mb-1">
                                    Адрес за доставка
                                </h6>

                                <p class="mb-1">

                                    @if ($order->city)

                                        Град: {{ $order->city }}

                                        <br>

                                    @endif

                                    @if ($order->office_list)

                                        Офис: {{ $order->office_list }}

                                    @elseif($order->personal_address)

                                        Адрес: {{ $order->personal_address }}

                                    @else

                                        —

                                    @endif

                                </p>

                            </div>

                        </article>

                    </div>

                </div>

                <hr>

                <div class="row">

                    <div class="col-lg-8">

                        <div class="table-responsive">

                            <table class="table cart-table align-middle">

                                <thead>

                                    <tr>
                                        <th>Продукт</th>
                                        <th>Базова цена</th>
                                        <th>Отстъпка</th>
                                        <th>К-во</th>
                                        <th class="text-end">
                                            Крайна цена
                                        </th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($order->products as $product)

                                        <tr>

                                            <td>

                                                <div class="d-flex align-items-center gap-3">

                                                    <div class="img-box">

                                                        <img
                                                            src="{{ asset('/assets/images/products/' . $product->product_image) }}"
                                                            alt="{{ $product->product_name }}"
                                                            style="width: 55px; height: 55px; object-fit: cover; border-radius: 8px;"
                                                        >

                                                    </div>

                                                    <div>

                                                        <a
                                                            target="_blank"
                                                            href="{{ route('shop.show', $product->product_slug) }}"
                                                        >

                                                            <strong>
                                                                {{ $product->product_name }}
                                                            </strong>

                                                        </a>

                                                        <br>

                                                        <small class="text-muted">
                                                            ID: #{{ $product->product_id }}
                                                        </small>

                                                        @if ($product->purchase_type === 'frame_with_glasses')

                                                            <br>

                                                            <span class="badge bg-info text-dark mt-2">
                                                                Рамка с диоптрични стъкла
                                                            </span>

                                                        @endif

                                                    </div>

                                                </div>

                                            </td>

                                            <td>

                                                <strong>
                                                    {{ number_format($product->base_price ?? $product->price, 2) }}
                                                    EUR
                                                </strong>

                                            </td>

                                            <td>

                                                @if ($product->discount)

                                                    {{ $product->discount }}%

                                                @else

                                                    Няма

                                                @endif

                                            </td>

                                            <td>
                                                {{ $product->quantity }}
                                            </td>

                                            <td class="text-end">

                                                <strong>

                                                    {{ number_format(
                                                        (
                                                            (float) ($product->base_price ?? $product->price)
                                                            +
                                                            (float) ($product->glass_value_lens_index_price ?? 0)
                                                        )
                                                        * $product->quantity,
                                                        2
                                                    ) }}
                                                    EUR

                                                </strong>

                                            </td>

                                        </tr>

                                        @if ($product->purchase_type === 'frame_with_glasses')

                                            <tr>

                                                <td colspan="5">

                                                    <div class="bg-light rounded p-3">

                                                        <h6 class="mb-3">
                                                            Конфигурация на диоптричните очила
                                                        </h6>

                                                        <div class="row">

                                                            <div class="col-md-6 mb-3">

                                                                <div class="border rounded p-3 h-100 bg-white">

                                                                    <h6 class="mb-3">
                                                                        Избрани стъкла
                                                                    </h6>

                                                                    @if ($product->glass_name)

                                                                        <div class="mb-2">

                                                                            <strong>
                                                                                Тип стъкло:
                                                                            </strong>

                                                                            <br>

                                                                            {{ $product->glass_name }}

                                                                        </div>

                                                                    @endif

                                                                    @if ($product->glass_value_name)

                                                                        <div class="mb-2">

                                                                            <strong>
                                                                                Стойност / вариант:
                                                                            </strong>

                                                                            <br>

                                                                            {{ $product->glass_value_name }}

                                                                        </div>

                                                                    @endif

                                                                    @if ($product->glass_value_lens_index_name)

                                                                        <div class="mb-2">

                                                                            <strong>
                                                                                Индекс:
                                                                            </strong>

                                                                            <br>

                                                                            {{ $product->glass_value_lens_index_name }}

                                                                        </div>

                                                                    @endif

                                                                    @if ($product->glass_value_lens_index_price !== null)

                                                                        <div class="mb-0">

                                                                            <strong>
                                                                                Цена на индекс:
                                                                            </strong>

                                                                            <br>

                                                                            {{ number_format(
                                                                                $product->glass_value_lens_index_price,
                                                                                2
                                                                            ) }}
                                                                            EUR

                                                                        </div>

                                                                    @endif

                                                                </div>

                                                            </div>

                                                            <div class="col-md-6 mb-3">

                                                                <div class="border rounded p-3 h-100 bg-white">

                                                                    <h6 class="mb-3">
                                                                        Разбивка на цената
                                                                    </h6>

                                                                    <div class="d-flex justify-content-between mb-2">

                                                                        <span>
                                                                            Рамка:
                                                                        </span>

                                                                        <strong>
                                                                            {{ number_format(
                                                                                $product->base_price ?? $product->price,
                                                                                2
                                                                            ) }}
                                                                            EUR
                                                                        </strong>

                                                                    </div>

                                                                    @if ($product->glass_value_lens_index_price !== null)

                                                                        <div class="d-flex justify-content-between mb-2">

                                                                            <span>
                                                                                Индекс
                                                                                {{ $product->glass_value_lens_index_name }}:
                                                                            </span>

                                                                            <strong>
                                                                                {{ number_format(
                                                                                    $product->glass_value_lens_index_price,
                                                                                    2
                                                                                ) }}
                                                                                EUR
                                                                            </strong>

                                                                        </div>

                                                                    @endif

                                                                    <hr>

                                                                    <div class="d-flex justify-content-between">

                                                                        <span>
                                                                            <strong>
                                                                                Крайна цена:
                                                                            </strong>
                                                                        </span>

                                                                        <strong class="text-success">

                                                                            {{ number_format(
                                                                                (float) ($product->base_price ?? $product->price)
                                                                                +
                                                                                (float) ($product->glass_value_lens_index_price ?? 0),
                                                                                2
                                                                            ) }}
                                                                            EUR

                                                                        </strong>

                                                                    </div>

                                                                    @if ($product->quantity > 1)

                                                                        <div class="d-flex justify-content-between mt-2">

                                                                            <span>
                                                                                Количество:
                                                                            </span>

                                                                            <strong>
                                                                                {{ $product->quantity }}
                                                                            </strong>

                                                                        </div>

                                                                        <div class="d-flex justify-content-between mt-2">

                                                                            <span>
                                                                                <strong>
                                                                                    Общо за продукта:
                                                                                </strong>
                                                                            </span>

                                                                            <strong class="text-success">

                                                                                {{ number_format(
                                                                                    (
                                                                                        (float) ($product->base_price ?? $product->price)
                                                                                        +
                                                                                        (float) ($product->glass_value_lens_index_price ?? 0)
                                                                                    )
                                                                                    * $product->quantity,
                                                                                    2
                                                                                ) }}
                                                                                EUR

                                                                            </strong>

                                                                        </div>

                                                                    @endif

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <hr>

                                                        <h6 class="mb-3">
                                                            Данни за диоптър към продукта
                                                        </h6>

                                                        <div class="row">

                                                            <div class="col-md-4 mb-3">

                                                                <strong>
                                                                    Рецепта:
                                                                </strong>

                                                                <br>

                                                                @if ($product->prescription_image)

                                                                    <a
                                                                        href="{{ asset('/assets/images/prescriptions/' . $product->prescription_image) }}"
                                                                        target="_blank"
                                                                    >

                                                                        <img
                                                                            src="{{ asset('/assets/images/prescriptions/' . $product->prescription_image) }}"
                                                                            alt="Рецепта"
                                                                            style="width: 90px; height: 90px; object-fit: cover; border-radius: 8px; margin-top: 8px;"
                                                                        >

                                                                    </a>

                                                                @else

                                                                    <span class="text-muted">
                                                                        Няма качена рецепта
                                                                    </span>

                                                                @endif

                                                            </div>

                                                            <div class="col-md-4 mb-3">

                                                                <strong>
                                                                    Дясно око (OD):
                                                                </strong>

                                                                @if (! empty($product->right_eye))

                                                                    <ul class="list-unstyled mb-0 mt-2">

                                                                        <li>
                                                                            SPH:
                                                                            {{ $product->right_eye['sph'] ?? '—' }}
                                                                        </li>

                                                                        <li>
                                                                            CYL:
                                                                            {{ $product->right_eye['cyl'] ?? '—' }}
                                                                        </li>

                                                                        <li>
                                                                            AXIS:
                                                                            {{ $product->right_eye['axis'] ?? '—' }}
                                                                        </li>

                                                                        <li>
                                                                            ADD:
                                                                            {{ $product->right_eye['add'] ?? '—' }}
                                                                        </li>

                                                                    </ul>

                                                                @else

                                                                    <p class="text-muted mb-0 mt-2">
                                                                        Няма въведени данни
                                                                    </p>

                                                                @endif

                                                            </div>

                                                            <div class="col-md-4 mb-3">

                                                                <strong>
                                                                    Ляво око (OS):
                                                                </strong>

                                                                @if (! empty($product->left_eye))

                                                                    <ul class="list-unstyled mb-0 mt-2">

                                                                        <li>
                                                                            SPH:
                                                                            {{ $product->left_eye['sph'] ?? '—' }}
                                                                        </li>

                                                                        <li>
                                                                            CYL:
                                                                            {{ $product->left_eye['cyl'] ?? '—' }}
                                                                        </li>

                                                                        <li>
                                                                            AXIS:
                                                                            {{ $product->left_eye['axis'] ?? '—' }}
                                                                        </li>

                                                                        <li>
                                                                            ADD:
                                                                            {{ $product->left_eye['add'] ?? '—' }}
                                                                        </li>

                                                                    </ul>

                                                                @else

                                                                    <p class="text-muted mb-0 mt-2">
                                                                        Няма въведени данни
                                                                    </p>

                                                                @endif

                                                            </div>

                                                        </div>

                                                        @if ($product->pd)

                                                            <div class="mt-2">

                                                                <strong>
                                                                    PD:
                                                                </strong>

                                                                {{ $product->pd }}

                                                            </div>

                                                        @endif

                                                    </div>

                                                </td>

                                            </tr>

                                        @endif

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        <a
                            href="{{ route('admin.orders.index') }}"
                            class="btn btn-primary mt-3"
                        >
                            Назад към поръчките
                        </a>

                    </div>

                    <div class="col-lg-4">

                        <div class="box shadow-sm bg-light p-4 rounded">

                            <h6 class="mb-3">
                                Информация за плащане
                            </h6>

                            <div class="d-flex justify-content-between mb-2">

                                <span>
                                    Сума на продуктите:
                                </span>

                                <strong>

                                    {{ number_format(
                                        $order->products->sum(
                                            fn($product) =>
                                                (
                                                    (float) ($product->base_price ?? $product->price)
                                                    +
                                                    (float) ($product->glass_value_lens_index_price ?? 0)
                                                )
                                                * $product->quantity
                                        ),
                                        2
                                    ) }}
                                    EUR

                                </strong>

                            </div>

                            @if ($promoCode)

                                <div class="d-flex justify-content-between mb-2">

                                    <span>
                                        Промо код ({{ $promoCode->promo_code_name }}):
                                    </span>

                                    <strong class="text-danger">
                                        -{{ $promoCode->percentage_promo_code }}%
                                    </strong>

                                </div>

                            @endif

                            @if ($order->delivery_price > 0)

                                <div class="d-flex justify-content-between mb-2">

                                    <span>
                                        Доставка:
                                    </span>

                                    <strong>
                                        {{ number_format($order->delivery_price, 2) }}
                                        EUR
                                    </strong>

                                </div>

                            @endif

                            <hr>

                            <div class="d-flex justify-content-between">

                                <span>
                                    <strong>
                                        Обща сума:
                                    </strong>
                                </span>

                                <strong class="h5 mb-0 text-success">

                                    {{ number_format(
                                        (
                                            $order->products->sum(
                                                fn($product) =>
                                                    (
                                                        (float) ($product->base_price ?? $product->price)
                                                        +
                                                        (float) ($product->glass_value_lens_index_price ?? 0)
                                                    )
                                                    * $product->quantity
                                            )
                                            *
                                            (
                                                $promoCode
                                                    ? 1 - ((float) $promoCode->percentage_promo_code / 100)
                                                    : 1
                                            )
                                        )
                                        +
                                        (float) $order->delivery_price,
                                        2
                                    ) }}
                                    EUR

                                </strong>

                            </div>

                            <hr>

                            <p class="mb-2">

                                <strong>
                                    Фирмени данни:
                                </strong>

                            </p>

                            @if ($order->request_invoice)

                                <div>
                                    {{ $order->company_name }}
                                </div>

                                <div>
                                    {{ $order->company_mol }}
                                </div>

                                <div>
                                    {{ $order->company_bulstat }}
                                </div>

                                <div>
                                    {{ $order->company_address }}
                                </div>

                            @else

                                <p class="mb-0">
                                    Няма фирмени данни
                                </p>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</x-backend>
