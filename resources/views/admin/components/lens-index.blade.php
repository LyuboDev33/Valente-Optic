@props(['lensIndex'])

<div class="lens-index-admin-row">

    <form action="{{ route('admin.glass-value-lens-indexes.update', $lensIndex) }}"
        method="POST"
        class="lens-index-admin-row__form">

        @csrf

        <div class="glass-value-row__field">
            <label>Индекс</label>

            <input type="text" name="name"
                class="form-control"
                value="{{ $lensIndex->name }}"
                placeholder="Напр. 1.60"
                required>
        </div>

        <div class="glass-value-row__field">
            <label>Цена</label>

            <input type="number" step="0.01"
                name="price"
                class="form-control"
                value="{{ $lensIndex->price }}"
                min="0"
                required>
        </div>

        <button type="submit"
            class="btn btn-primary text-white rounded-5 w-100 mt-2 mb-2">
            Запази
        </button>
    </form>

    <form action="{{ route('admin.glass-value-lens-indexes.destroy', $lensIndex) }}"
        method="POST"
        onsubmit="return confirm('Сигурен ли си, че искаш да изтриеш този индекс?');">

        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-outline-danger rounded-5">
            Изтрий
        </button>
    </form>

</div>
