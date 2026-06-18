<li class="{{ ($activeSlug ?? null) === $node['slug'] ? 'active' : '' }}">
    <a href="{{ route('shop.category', $node['slug']) }}">
        {{ $node['name'] }}
    </a>

    @if (!empty($node['children']))
        <ul class="shop-category__sublist">
            @foreach ($node['children'] as $child)
                @include('Frontend.shop.partials.category-tree', [
                    'node'       => $child,
                    'activeSlug' => $activeSlug ?? null,
                ])
            @endforeach
        </ul>
    @endif
</li>
