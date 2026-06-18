@props([
    'width' => 120,
])

<a href="/">
    <img width="{{ $width }}"
        src="{{ asset('/assets/images/logo-valente.png') }}"
        alt="Valente Logo">
</a>
