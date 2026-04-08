<article class="schedule-card {{ $jadwal->status_key }} p-6 text-white" style="animation-delay: {{ (($delayIndex ?? 0) % 8) * 80 }}ms;">
    <div class="flex items-start justify-between gap-3">
        <span class="status-pill {{ $jadwal->status_key }}">
            <i class="bi {{ $jadwal->status_key === 'ongoing' ? 'bi-lightning-charge-fill' : ($jadwal->status_key === 'upcoming' ? 'bi-clock-history' : 'bi-check-circle') }}"></i>
            {{ $jadwal->status_label }}
        </span>
        <span class="rounded-full border border-white/10 bg-black/20 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-gray-300">
            Hari ini
        </span>
    </div>

    <div class="mt-5 flex items-start gap-3">
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-red-600 to-red-700 text-2xl text-white shadow-lg">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div class="min-w-0">
            <div class="text-3xl font-bold leading-none text-white">{{ $jadwal->jam_label }}</div>
            <h3 class="mt-3 text-2xl font-bold leading-tight text-white">{{ $jadwal->kursus?->nama ?? 'Kursus belum ditentukan' }}</h3>
        </div>
    </div>

    <div class="mt-6 grid gap-3">
        <div class="flex items-start gap-3 rounded-2xl bg-black/20 px-4 py-3">
            <i class="bi bi-diagram-3-fill mt-0.5 text-lg text-yellow-300"></i>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Program dan Level</p>
                <p class="mt-1 text-sm font-medium text-white">{{ $jadwal->program_level_label ?: 'Program atau level belum ditentukan' }}</p>
            </div>
        </div>
        <div class="flex items-start gap-3 rounded-2xl bg-black/20 px-4 py-3">
            <i class="bi bi-person-badge-fill mt-0.5 text-lg text-yellow-300"></i>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Instruktur</p>
                <p class="mt-1 text-sm font-medium text-white">{{ $jadwal->instruktur_label }}</p>
            </div>
        </div>
        <div class="grid gap-3 sm:grid-cols-2">
            <div class="flex items-start gap-3 rounded-2xl bg-black/20 px-4 py-3">
                <i class="bi bi-geo-alt-fill mt-0.5 text-lg text-red-300"></i>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Lokasi</p>
                    <p class="mt-1 text-sm font-medium text-white">{{ $jadwal->lokasi_label }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3 rounded-2xl bg-black/20 px-4 py-3">
                <i class="bi bi-door-open-fill mt-0.5 text-lg text-red-300"></i>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Kelas</p>
                    <p class="mt-1 text-sm font-medium text-white">{{ $jadwal->kelas_label }}</p>
                </div>
            </div>
        </div>
    </div>
</article>
