@props(['items'])

@foreach ($items as $item)

    <li  @class([
        'dropdown' => !empty($item['children']),
    ])>

        <a href="/shop/category/{{ $item['slug'] }}">
            {{ $item['name'] }}
        </a>

        @if (!empty($item['children']))

            <ul>

                <x-category-menu :items="$item['children']" />

            </ul>

        @endif

    </li>

@endforeach
