@extends('instruktur::layouts.master')

@section('title', 'Absensi Pertemuan '.$risalah->pertemuan_ke)

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-10">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('instruktur.kursus.show', $risalah->kursus) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#526875] transition hover:text-[#0d9488]"><i class="bi bi-arrow-left"></i> Kembali ke kursus</a>
        <span class="font-mono text-xs uppercase tracking-[.16em] text-[#6c7c82]">Pertemuan / {{ $risalah->pertemuan_ke }}</span>
    </div>

    <header class="border-b border-[#cfc8bb] pb-6"><p class="font-mono text-xs uppercase tracking-[.18em] text-[#0d9488]">Pencatatan kehadiran</p><h2 class="mt-2 text-4xl tracking-tight text-[#173f5f]">Pertemuan {{ $risalah->pertemuan_ke }}</h2><p class="mt-2 text-[#6c7c82]">{{ $risalah->kursus->nama ?? 'Kursus' }} <span class="mx-2 text-[#cfc8bb]">/</span> {{ $risalah->tgl_pertemuan ? \Carbon\Carbon::parse($risalah->tgl_pertemuan)->format('d F Y') : 'Tanggal belum ditentukan' }}</p></header>

    @if(session('success'))<div class="mt-6 border-l-4 border-[#0d9488] bg-[#dff2ef] px-4 py-3 text-sm font-semibold text-[#0f766e]">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="mt-6 border-l-4 border-[#a84a2a] bg-[#f8e5d8] px-4 py-3 text-sm text-[#8a3b22]"><p class="font-semibold">Periksa kembali data absensi.</p><ul class="mt-1 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <form method="POST" class="mt-8 overflow-hidden border border-[#cfc8bb] bg-[#fffefa]">
        @csrf
        <div class="flex flex-col gap-3 border-b border-[#cfc8bb] bg-[#f5f2ea] px-5 py-4 sm:flex-row sm:items-center sm:justify-between"><div><h3 class="text-xl text-[#173f5f]">Daftar peserta</h3><p class="mt-1 text-sm text-[#6c7c82]">Pilih status kehadiran untuk setiap peserta.</p></div><span class="font-mono text-sm text-[#a84a2a]">{{ $pendaftaran->count() }} peserta</span></div>
        <div class="divide-y divide-[#e5e0d6]">
            @foreach($pendaftaran as $p)
                @php $current = $risalah->absensis()->where('pendaftaran_id', $p->id)->value('status'); @endphp
                <div class="grid gap-4 px-5 py-4 md:grid-cols-[3rem_minmax(0,1fr)_14rem] md:items-center">
                    <span class="font-mono text-sm text-[#a84a2a]">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <div><p class="font-semibold text-[#173f5f]">{{ $p->peserta->user->name }}</p><p class="mt-1 text-xs text-[#6c7c82]">{{ $p->peserta->nomor_peserta ?? 'Nomor peserta belum tersedia' }}</p></div>
                    <label class="sr-only" for="absen-{{ $p->id }}">Status {{ $p->peserta->user->name }}</label><select id="absen-{{ $p->id }}" class="w-full border border-[#cfc8bb] bg-white px-3 py-2.5 text-sm text-[#173f5f] focus:border-[#0d9488] focus:outline-none" name="absen[{{ $p->id }}]" required><option value="">Pilih status</option><option value="H" @selected($current === 'H')>Hadir</option><option value="S" @selected($current === 'S')>Sakit</option><option value="I" @selected($current === 'I')>Izin</option><option value="A" @selected($current === 'A')>Alpha</option></select>
                </div>
            @endforeach
        </div>
        <div class="flex flex-wrap justify-end gap-3 border-t border-[#cfc8bb] bg-[#f5f2ea] px-5 py-4"><a href="{{ route('instruktur.kursus.show', $risalah->kursus) }}" class="inline-flex items-center gap-2 border border-[#cfc8bb] bg-[#fffefa] px-4 py-2.5 text-sm font-semibold text-[#526875]">Batal</a><button type="submit" class="inline-flex items-center gap-2 bg-[#0d9488] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0f766e]"><i class="bi bi-check2"></i>Simpan absensi</button></div>
    </form>
</div>
@endsection
