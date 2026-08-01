@props(['value'])

<label {{ $attributes->merge(['class' => 'bk-label']) }}>
    {{ $value ?? $slot }}
</label>
