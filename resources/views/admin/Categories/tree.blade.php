<li>
    <div class="category-node">
        <strong>{{ $category['name'] }}</strong>
    </div>

    @if (!empty($category['children']))
        <ul>
            @foreach ($category['children'] as $child)
                @include('admin.Categories.tree', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>
