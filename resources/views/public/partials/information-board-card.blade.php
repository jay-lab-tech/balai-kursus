<article class="bk-sesi bk-sesi--{{ $jadwal->status_key }}">
    <div>
        <span class="bk-tag {{ $jadwal->status_key === 'ongoing' ? 'bk-tag--perlu' : ($jadwal->status_key === 'upcoming' ? 'bk-tag--jalan' : 'bk-tag--diam') }}">
            {{ $jadwal->status_label }}
        </span>
        <span class="bk-sesi__jam">{{ $jadwal->jam_label }}</span>
        <h3 class="bk-sesi__nama">{{ $jadwal->kursus?->nama ?? 'Kursus belum ditentukan' }}</h3>
    </div>

    <div class="bk-sesi__list">
        <div>
            <i class="bi bi-diagram-3" aria-hidden="true"></i>
            <span>{{ $jadwal->program_level_label ?: 'Program atau level belum ditentukan' }}</span>
        </div>
        <div>
            <i class="bi bi-person-badge" aria-hidden="true"></i>
            <span><b>{{ $jadwal->instruktur_label }}</b></span>
        </div>
        <div>
            <i class="bi bi-geo-alt" aria-hidden="true"></i>
            <span>{{ $jadwal->lokasi_label }} · {{ $jadwal->kelas_label }}</span>
        </div>
    </div>
</article>
