<x-backend>
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Стъкла</h3>

        <a class="btn btn-secondary p-2 rounded-5" href="{{ route('admin.products.index') }}">
            Назад към всички
        </a>
    </div>

    <hr>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container">
        <div class="row g-4">

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <strong>Нов тип зрение</strong>
                        <small class="text-white-50 d-block">
                            Напр. „За близо“, „За далеч“
                        </small>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.vision-types.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="vision_type_name" class="form-label">Тип зрение</label>

                                <input type="text" id="vision_type_name" name="name"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="За далеч"
                                    value="{{ old('name') }}" required>

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success rounded-5 px-4">
                                + Добави тип зрение
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ================= CREATE GLASS ================= --}}
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <strong>Нов тип стъкло</strong>
                        <small class="text-white-50 d-block">
                            Напр. „Хелиоматични стъкла“, „Антирефлексни стъкла“
                        </small>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.glasses.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="vision_type_id" class="form-label">
                                    Тип зрение
                                </label>

                                <select id="vision_type_id" name="vision_type_id"
                                    class="form-select @error('vision_type_id') is-invalid @enderror" required>

                                    <option value="">
                                        — Изберете тип зрение —
                                    </option>

                                    @foreach ($visionTypes as $visionType)
                                        <option value="{{ $visionType->id }}" @selected(old('vision_type_id') == $visionType->id)>
                                            {{ $visionType->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('vision_type_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">
                                    Основна категория
                                </label>

                                <select id="category_id" name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror" required>

                                    <option value="">
                                        — Изберете категория —
                                    </option>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="glass_name" class="form-label">
                                    Име на стъклото
                                </label>

                                <input type="text" id="glass_name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Хелиоматични стъкла" required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success rounded-5 px-4">
                                + Добави стъкло
                            </button>
                        </form>
                    </div>
                </div>
            </div>



            {{-- ================= CREATE GLASS VALUE BY CATEGORY ================= --}}
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <strong>Добавяне на стъкла</strong>
                        <small class="text-white-50 d-block">
                            Добави хелиоматични или бели стъкла
                        </small>
                    </div>

                    <div class="card-body d-flex flex-column gap-3">
                        @foreach ($categories as $category)
                            <div class="border rounded-3 p-3 mb-3">
                                <h6 class="mb-3">{{ $category->name }}</h6>

                                @if ($glasses->where('category_id', $category->id)->isEmpty())
                                    <p class="text-muted mb-0">
                                        Няма добавени стъкла за тази категория.
                                    </p>
                                @else
                                    <form action="{{ route('admin.glass-values.store', $category) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Тип стъкло</label>

                                            <select name="glass_id"
                                                class="form-select @error('glass_id') is-invalid @enderror" required>
                                                <option value="">— Избери тип стъкло —</option>

                                                @foreach ($glasses->where('category_id', $category->id) as $glass)
                                                    <option value="{{ $glass->id }}">
                                                        {{ $glass->visionType?->name }} - {{ $glass->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('glass_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">
                                                Стойност / покритие
                                            </label>

                                            <input type="text" name="value"
                                                class="form-control @error('value') is-invalid @enderror"
                                                placeholder="до 80% потъмняване" value="{{ old('value') }}" required>

                                            @error('value')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>



                                        <button type="submit" class="btn btn-success rounded-5 px-4">
                                            + Добави стойност
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-3">

        {{-- ================= LIST OF ALL GLASSES + VALUES BY CATEGORY ================= --}}
        <h4 class="mb-3">Съществуващи стъкла</h4>

        @foreach ($visionTypes as $visionType)
            <div class="glasses-category-card">
                <div class="glasses-category-card__header">
                    <h5>{{ $visionType->name }}</h5>
                </div>

                <div class="glasses-category-card__body">
                    @if ($glasses->where('vision_type_id', $visionType->id)->isEmpty())
                        <div class="alert alert-info mb-0">
                            Все още няма създадени стъкла за този тип зрение.
                        </div>
                    @else
                        <div class="glasses-list">
                            @foreach ($glasses->where('vision_type_id', $visionType->id) as $glass)
                                <div class="glass-admin-card">
                                    <div class="glass-admin-card__header">
                                        <div>
                                            <h6 class="mb-1">
                                                {{ $glass->name }}
                                            </h6>

                                            @if ($glass->category)
                                                <small class="text-muted">
                                                    {{ $glass->category->name }}
                                                </small>
                                            @endif
                                        </div>

                                        <form action="{{ route('admin.glasses.destroy', $glass) }}" method="POST"
                                            onsubmit="return confirm('Сигурен ли си? Това ще изтрие типа стъкло и всичките му стойности.');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-5">
                                                Изтрий тип
                                            </button>
                                        </form>
                                    </div>


                                    <div class="glass-admin-card__body">
                                        @if ($glass->values->isEmpty())
                                            <p class="text-muted mb-0 fst-italic">
                                                Няма добавени стойности.
                                            </p>
                                        @else
                                            <div class="glass-values-list">
                                                @foreach ($glass->values as $value)
                                                    <div class="glass-value-box glass-admin-card">

                                                        <div class="glass-admin-card__header" role="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#glass-value-{{ $value->id }}"
                                                            aria-expanded="false"
                                                            aria-controls="glass-value-{{ $value->id }}">

                                                            <span>{{ $value->value }}</span>

                                                            <i class="fa-solid fa-chevron-down"></i>
                                                        </div>

                                                        <div id="glass-value-{{ $value->id }}"
                                                            class="collapse glass-admin-card__body bg-white">

                                                            {{-- ================= GLASS VALUE UPDATE ================= --}}
                                                            <div class="glass-value-row">
                                                                <form
                                                                    action="{{ route('admin.glass-values.update', $value) }}"
                                                                    method="POST"
                                                                    class="glass-value-row__form glass-value-update-form">
                                                                    @csrf

                                                                    <div class="glass-value-row__field">
                                                                        <label>Стойност</label>

                                                                        <input type="text" name="value"
                                                                            class="form-control"
                                                                            value="{{ $value->value }}" required>
                                                                    </div>

                                                                    <div class="glass-value-row__actions">
                                                                        <button type="submit"
                                                                            class="btn btn-outline-primary rounded-5">
                                                                            Запази
                                                                        </button>
                                                                    </div>
                                                                </form>

                                                                <form
                                                                    action="{{ route('admin.glass-values.destroy', $value) }}"
                                                                    method="POST" class="glass-value-row__delete"
                                                                    onsubmit="return confirm('Изтрий тази стойност и всички нейни индекси?');">
                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <button type="submit"
                                                                        class="btn btn-outline-danger rounded-5">
                                                                        Изтрий
                                                                    </button>
                                                                </form>
                                                            </div>

                                                            {{-- ================= LENS INDEXES ================= --}}
                                                            <div class="glass-value-lens-indexes mt-4">

                                                                <div
                                                                    class="d-flex justify-content-between align-items-center mb-3">

                                                                    {{-- ================= CREATE LENS INDEX ================= --}}
                                                                    <div class="lens-index-create-box col-lg-3">
                                                                        <h6 class="mb-3">
                                                                            Добави нов индекс
                                                                        </h6>

                                                                        <form
                                                                            action="{{ route('admin.glass-value-lens-indexes.store') }}"
                                                                            method="POST"
                                                                            class="lens-index-create-form">
                                                                            @csrf

                                                                            <input type="hidden"
                                                                                name="glass_value_id"
                                                                                value="{{ $value->id }}">

                                                                            <div class="glass-value-row__field">
                                                                                <label>Индекс</label>

                                                                                <input type="text" name="name"
                                                                                    class="form-control rounded-pill"
                                                                                    placeholder="Напр. 1.60" required>
                                                                            </div>

                                                                            <div class="glass-value-row__field">
                                                                                <label>Цена</label>

                                                                                <input type="number" step="0.01"
                                                                                    name="price"
                                                                                    class="form-control rounded-pill"
                                                                                    placeholder="Напр. 40.00"
                                                                                    min="0" required>
                                                                            </div>

                                                                            <button type="submit"
                                                                                class="btn btn-success rounded-5 mt-3 mb-3">
                                                                                + Добави индекс
                                                                            </button>
                                                                        </form>
                                                                    </div>

                                                                    {{-- ================= EXISTING LENS INDEXES ================= --}}
                                                                    <div
                                                                        class="glass-value-lens-index-list d-flex gap-3 mb-4 flex-wrap">

                                                                        @foreach ($value->lensIndexes as $lensIndex)
                                                                            @include(
                                                                                'admin.components.lens-index',
                                                                                [
                                                                                    'lensIndex' => $lensIndex,
                                                                                ]
                                                                            )
                                                                        @endforeach

                                                                    </div>

                                                                    <div
                                                                        class="glass-value-lens-index-empty alert alert-light border mb-3 rounded-pill
                                    @if ($value->lensIndexes->isNotEmpty()) d-none @endif">

                                                                        Няма добавени индекси към тази стойност.

                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <hr>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach


        <hr class="my-3">


        <h4 class="mb-3">Съществуващи типове зрение</h4>


        @if ($visionTypes->isEmpty())
            <div class="alert alert-info">
                Все още няма създадени типове зрение.
            </div>
        @else
            <div class="row g-3">
                @foreach ($visionTypes as $visionType)
                    <div class="col-md-6 col-lg-4">
                        <div class="glass-admin-card h-100">
                            <div class="glass-admin-card__header">
                                <h6>{{ $visionType->name }}</h6>
                            </div>

                            <div class="glass-admin-card__body">
                                <form action="{{ route('admin.vision-types.update', $visionType) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="glass-value-row__field mb-3">
                                        <label>Тип зрение</label>

                                        <input type="text" name="name" class="form-control"
                                            value="{{ old("visionTypes.$visionType->id.name", $visionType->name) }}"
                                            required>
                                    </div>

                                    <button type="submit" class="btn btn-outline-primary rounded-5 w-100">
                                        Запази
                                    </button>
                                </form>

                                <form action="{{ route('admin.vision-types.destroy', $visionType) }}" method="POST"
                                    class="mt-3"
                                    onsubmit="return confirm('Сигурен ли си, че искаш да изтриеш този тип зрение?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-outline-danger rounded-5 w-100">
                                        Изтрий
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif


    </div>



    <script>
        $(document).ready(function() {
            createLensIndex();
            updateLensIndex();
            updateGlassValue();
        });

        function showSuccessMessage(form, message) {
            form.find('.ajax-success-message').remove();

            const successMessage = $('<p>', {
                class: 'ajax-success-message text-success mt-2 mb-0',
                text: message
            });

            form.append(successMessage);

            setTimeout(function() {
                successMessage.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }

        function createLensIndex() {
            $(document).on('submit', '.lens-index-create-form', function(e) {
                e.preventDefault();

                const form = $(this);
                const container = form.closest('.glass-value-lens-indexes');
                const button = form.find('button[type="submit"]');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),

                    beforeSend: function() {
                        button.prop('disabled', true);
                        form.find('.ajax-success-message').remove();
                    },

                    success: function(response) {
                        if (response.success) {
                            container.find('.glass-value-lens-index-list').append(response.html);
                            container.find('.glass-value-lens-index-empty').hide();

                            form[0].reset();

                            showSuccessMessage(form, response.message);
                        }
                    },

                    error: function(xhr) {
                        if (xhr.status === 422) {
                            alert(Object.values(xhr.responseJSON.errors).flat().join('\n'));
                            return;
                        }

                        alert('Възникна грешка при добавянето на индекса.');
                    },

                    complete: function() {
                        button.prop('disabled', false);
                    }
                });
            });
        }

        function updateLensIndex() {
            $(document).on('submit', '.lens-index-admin-row__form', function(e) {
                e.preventDefault();

                const form = $(this);
                const button = form.find('button[type="submit"]');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),

                    beforeSend: function() {
                        button.prop('disabled', true);
                        form.find('.ajax-success-message').remove();
                    },

                    success: function(response) {
                        if (response.success) {
                            showSuccessMessage(form, response.message);
                        }
                    },

                    error: function(xhr) {
                        if (xhr.status === 422) {
                            alert(Object.values(xhr.responseJSON.errors).flat().join('\n'));
                            return;
                        }

                        alert('Възникна грешка при обновяването на индекса.');
                    },

                    complete: function() {
                        button.prop('disabled', false);
                    }
                });
            });
        }

        function updateGlassValue() {
            $(document).on('submit', '.glass-value-update-form', function(e) {
                e.preventDefault();

                const form = $(this);
                const button = form.find('button[type="submit"]');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),

                    beforeSend: function() {
                        button.prop('disabled', true);
                        form.find('.ajax-success-message').remove();
                    },

                    success: function(response) {
                        if (response.success) {
                            const glassValueBox = form.closest('.glass-value-box');

                            glassValueBox
                                .children('.glass-admin-card__header')
                                .find('span')
                                .first()
                                .text(response.value);

                            showSuccessMessage(form, response.message);
                        }
                    },

                    error: function(xhr) {
                        if (xhr.status === 422) {
                            alert(Object.values(xhr.responseJSON.errors).flat().join('\n'));
                            return;
                        }

                        alert('Възникна грешка при обновяването на стойността.');
                    },

                    complete: function() {
                        button.prop('disabled', false);
                    }
                });
            });
        }
    </script>

</x-backend>
