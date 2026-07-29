@extends('instruktur::layouts.master')

@section('title', 'Jadwal Mengajar')

@section('content')
<div class="min-h-screen bg-[#f7f8f6] px-4 py-8 sm:px-6 lg:px-10">
    <div class="mx-auto max-w-7xl">
        <header class="mb-8 flex flex-col gap-5 border-b border-[#dce7e5] pb-7 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="font-mono text-xs uppercase tracking-[0.22em] text-[#0d9488]">Ruang instruktur / kalender</p><h1 class="mt-2 text-4xl font-bold tracking-tight text-[#173f5f]">Jadwal mengajar</h1><p class="mt-3 max-w-2xl text-[#718596]">Semua pertemuan dari kelas yang ditugaskan kepada Anda.</p></div>
            <a href="{{ route('instruktur.dashboard') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-[#c8d9d6] bg-white px-4 py-3 text-sm font-semibold text-[#40627d] transition hover:border-[#0d9488] hover:text-[#0f766e]"><i class="bi bi-arrow-left"></i>Kembali ke ringkasan</a>
        </header>

        @if(count($jadwals) > 0)
            <section class="overflow-hidden border border-[#dce7e5] bg-white shadow-[0_14px_35px_rgba(23,63,95,.06)]">
                <div class="flex flex-col gap-2 border-b border-[#e8efed] px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6"><div><p class="font-mono text-xs uppercase tracking-[0.18em] text-[#0d9488]">Agenda kelas</p><h2 class="mt-1 text-xl font-bold text-[#173f5f]">{{ count($jadwals) }} pertemuan terjadwal</h2></div><span class="text-sm text-[#718596]">Gunakan scroll horizontal di layar kecil.</span></div>
                <div class="overflow-x-auto"><table class="min-w-full divide-y divide-[#e8efed]"><thead class="bg-[#f3f7f6]"><tr>
                    <th class="px-5 py-4 text-left font-mono text-[11px] uppercase tracking-[0.16em] text-[#718596]">Tanggal</th><th class="px-5 py-4 text-left font-mono text-[11px] uppercase tracking-[0.16em] text-[#718596]">Waktu</th><th class="px-5 py-4 text-left font-mono text-[11px] uppercase tracking-[0.16em] text-[#718596]">Pertemuan</th><th class="px-5 py-4 text-left font-mono text-[11px] uppercase tracking-[0.16em] text-[#718596]">Kursus</th><th class="px-5 py-4 text-left font-mono text-[11px] uppercase tracking-[0.16em] text-[#718596]">Tempat</th>
                </tr></thead><tbody class="divide-y divide-[#e8efed]">
                    @foreach($jadwals as $j)<tr class="transition hover:bg-[#f3f7f6]"><td class="whitespace-nowrap px-5 py-5 text-sm font-semibold text-[#173f5f]">{{ optional($j->tgl_pertemuan)->format('d M Y') ?? '-' }}<span class="mt-1 block text-xs font-normal text-[#718596]">{{ $j->hari->nama ?? '-' }}</span></td><td class="whitespace-nowrap px-5 py-5 text-sm text-[#40627d]">@if($j->jam_mulai && $j->jam_selesai){{ substr($j->jam_mulai,0,5) }} – {{ substr($j->jam_selesai,0,5) }}@else-@endif</td><td class="px-5 py-5"><span class="rounded-full bg-[#fef3c7] px-3 py-1 font-mono text-xs font-semibold text-[#b45309]">Pertemuan {{ $j->pertemuan_ke ?? '-' }}</span></td><td class="min-w-[220px] px-5 py-5 text-sm"><p class="font-semibold text-[#173f5f]">{{ $j->kursus->nama_kursus ?? $j->kursus->nama ?? 'N/A' }}</p><p class="mt-1 text-xs text-[#718596]">{{ $j->kursus->program->nama_program ?? $j->kursus->program->nama ?? '' }}</p></td><td class="px-5 py-5 text-sm text-[#40627d]">{{ $j->lokasi->nama ?? '-' }}<span class="mt-1 block text-xs text-[#718596]">{{ $j->kela->nama ?? '-' }}</span></td></tr>@endforeach
                </tbody></table></div>
            </section>
        @else
            <div class="border border-dashed border-[#b8cbc8] bg-white px-6 py-16 text-center"><i class="bi bi-calendar-x text-4xl text-[#0d9488]"></i><h2 class="mt-4 text-2xl font-bold text-[#173f5f]">Belum ada jadwal</h2><p class="mt-2 text-[#718596]">Jadwal mengajar akan muncul setelah admin menetapkannya.</p></div>
        @endif
    </div>
</div>
@endsection
