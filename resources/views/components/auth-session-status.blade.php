@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'bk-note bk-note--baik']) }}>
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ $status }}</span>
    </div>
@endif
