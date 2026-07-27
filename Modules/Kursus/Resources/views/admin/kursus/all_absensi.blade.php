@extends('layouts.admin')

@section('title', 'Semua Absensi')

@section('page-title', 'Semua Absensi')

@section('page-description', 'Pantau kehadiran peserta lintas kelas beserta status dan waktu hadir yang tercatat.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-clipboard-check-fill text-sky-400"></i>
            Kehadiran Peserta
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Semua absensi peserta dalam satu daftar operasional.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Halaman ini merangkum status kehadiran peserta di setiap pertemuan, termasuk referensi risalah dan jam datang.</p>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.5rem] bg-gradient-to-br from-sky-600 to-sky-700 p-5 text-white shadow-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-100">Total Absensi</p>
                <p class="mt-3 text-4xl font-bold">{{ $absensis->count() }}</p>
                <p class="mt-2 text-sm text-sky-100/90">Jumlah seluruh kehadiran yang tercatat.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5 md:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Fokus Halaman</p>
                <p class="mt-3 text-xl font-bold text-white">Status hadir dan keterkaitan ke risalah pertemuan</p>
                <p class="mt-2 text-sm text-slate-300">Gunakan daftar ini untuk memantau konsistensi kehadiran peserta di seluruh pertemuan kelas.</p>
            </div>
        </div>
    </section>

    <section class="admin-panel overflow-hidden rounded-[2rem]">
        <div class="flex flex-col gap-3 border-b border-white/10 px-6 py-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">
                    <i class="bi bi-card-checklist mr-3 text-yellow-300"></i>Daftar Absensi
                </h2>
                <p class="mt-2 text-slate-400">Menampilkan risalah pertemuan, nama peserta, status kehadiran, dan jam datang.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">
                {{ $absensis->count() }} data absensi
            </span>
        </div>

        @if($absensis->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/5 text-4xl text-yellow-300">
                    <i class="bi bi-clipboard-x"></i>
                </div>
                <h3 class="mt-6 text-2xl font-bold text-white">Belum ada data absensi</h3>
                <p class="mt-3 text-slate-400">Absensi akan muncul di sini setelah pertemuan dan kehadiran peserta tercatat.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Risalah</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Peserta</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Jam Datang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensis as $a)
                            <tr class="transition hover:bg-white/[0.03]">
                                <td class="px-6 py-5 text-sm text-slate-300">Pertemuan {{ $a->risalah->pertemuan_ke }} - {{ $a->risalah->tgl_pertemuan->format('d M Y') }}</td>
                                <td class="px-6 py-5 text-sm font-semibold text-white">{{ $a->pendaftaran->peserta->user->name ?? '-' }}</td>
                                <td class="px-6 py-5">
                                    @php
                                        $statusClass = match(strtolower($a->status)) {
                                            'hadir' => 'border-emerald-400/20 bg-emerald-500/10 text-emerald-300',
                                            'izin' => 'border-yellow-400/20 bg-yellow-400/10 text-yellow-300',
                                            'alpha', 'tidak hadir' => 'border-sky-500/20 bg-sky-600/10 text-sky-200',
                                            default => 'border-white/10 bg-white/5 text-slate-300',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] {{ $statusClass }}">
                                        {{ $a->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $a->jam_datang ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection

