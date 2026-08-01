<button {{ $attributes->merge(['type' => 'submit', 'class' => 'bk-btn bk-btn--pri']) }}>
    {{ $slot }}
</button>
