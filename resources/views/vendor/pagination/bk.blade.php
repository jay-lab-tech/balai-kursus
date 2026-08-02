{{-- Penomoran halaman Balai Hangat. Dipasang sebagai bawaan di
     AppServiceProvider, jadi semua ->links() memakai tampilan ini. --}}
@if ($paginator->hasPages())
    <nav class="bk-pager" role="navigation" aria-label="Penomoran halaman">
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" style="opacity:.4">
                <i class="bi bi-chevron-left" aria-hidden="true"></i>
                <span class="bk-sr">Sebelumnya</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">
                <i class="bi bi-chevron-left" aria-hidden="true"></i>
                <span class="bk-sr">Halaman sebelumnya</span>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span aria-hidden="true">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="is-kini" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">
                <i class="bi bi-chevron-right" aria-hidden="true"></i>
                <span class="bk-sr">Halaman berikutnya</span>
            </a>
        @else
            <span aria-disabled="true" style="opacity:.4">
                <i class="bi bi-chevron-right" aria-hidden="true"></i>
                <span class="bk-sr">Berikutnya</span>
            </span>
        @endif
    </nav>
@endif
