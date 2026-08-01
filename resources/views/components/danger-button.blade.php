<button {{ $attributes->merge(['type' => 'submit', 'class' => 'bk-btn bk-btn--danger']) }}>
    {{ $slot }}
</button>
