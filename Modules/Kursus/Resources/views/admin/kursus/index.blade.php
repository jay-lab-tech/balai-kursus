@extends('layouts.admin')

@section('title', 'Manajemen Kelas')
@section('page-title', 'Manajemen Kelas')
@section('page-description', 'Kelola kelas program, kuota, jadwal, dan peserta dalam satu daftar operasional.')

@section('content')
<div class="space-y-7">
    <header class="flex flex-col gap-5 border-b border-[#cfc8bb] pb-6 lg:flex-row lg:items-end lg:justify-between">
        <div><p class="font-mono text-xs uppercase tracking-[.18em] text-[#0d9488]">Akademik / Kelas program</p><h1 class="mt-2 text-4xl tracking-tight text-[#173f5f]">Kelas yang sedang dikelola</h1><p class="mt-3 max-w-2xl text-sm leading-6 text-[#6c7c82]">Pantau penempatan peserta, kapasitas, periode, dan instruktur dari satu halaman kerja.</p></div>
        <div class="flex flex-wrap gap-2"><a href="{{ route('admin.jadwal.all') }}" class="admin-btn admin-btn-secondary"><i class="bi bi-calendar3"></i>Jadwal</a><a href="{{ route('admin.kursus.create') }}" class="admin-btn admin-btn-primary"><i class="bi bi-plus-lg"></i>Tambah kelas</a></div>
    </header>

    @if(session('success'))<div class="admin-alert border-l-4 border-[#0d9488] bg-[#dff2ef] text-[#0f766e]"><i class="bi bi-check-circle"></i><div><p class="font-semibold">Perubahan berhasil disimpan</p><p class="mt-1 text-sm">{{ session('success') }}</p></div></div>@endif

    <section class="grid gap-4 sm:grid-cols-3" aria-label="Ringkasan kelas"><div class="border border-[#cfc8bb] bg-[#fffefa] p-5"><p class="font-mono text-xs uppercase tracking-[.15em] text-[#6c7c82]">Total kelas</p><p class="mt-2 text-3xl font-semibold text-[#173f5f]">{{ $kursus->total() }}</p><p class="mt-1 text-sm text-[#6c7c82]">Seluruh kelas program</p></div><div class="border border-[#cfc8bb] bg-[#fffefa] p-5"><p class="font-mono text-xs uppercase tracking-[.15em] text-[#6c7c82]">Halaman</p><p class="mt-2 text-3xl font-semibold text-[#173f5f]">{{ $kursus->currentPage() }}</p><p class="mt-1 text-sm text-[#6c7c82]">Dari daftar berhalaman</p></div><div class="border border-[#cfc8bb] bg-[#fffefa] p-5"><p class="font-mono text-xs uppercase tracking-[.15em] text-[#6c7c82]">Per halaman</p><p class="mt-2 text-3xl font-semibold text-[#173f5f]">{{ $kursus->perPage() }}</p><p class="mt-1 text-sm text-[#6c7c82]">Baris data ditampilkan</p></div></section>

    <section class="admin-panel overflow-hidden">
        <div class="flex flex-col gap-2 border-b border-[#cfc8bb] px-5 py-4 sm:flex-row sm:items-end sm:justify-between"><div><p class="font-mono text-xs uppercase tracking-[.15em] text-[#0d9488]">Daftar kerja</p><h2 class="mt-1 text-2xl text-[#173f5f]">Semua kelas</h2></div><span class="text-sm text-[#6c7c82]">{{ $kursus->total() }} kelas ditemukan</span></div>
        @if($kursus->isEmpty())<div class="px-6 py-16 text-center"><i class="bi bi-book text-3xl text-[#0d9488]"></i><h3 class="mt-3 text-xl text-[#173f5f]">Belum ada kelas program</h3><p class="mt-2 text-sm text-[#6c7c82]">Tambahkan kelas untuk mulai menempatkan peserta.</p></div>@else
            <div class="overflow-x-auto"><table class="admin-table"><thead><tr><th>Kelas</th><th>Program / Level</th><th>Periode</th><th>Peserta</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
                @foreach($kursus as $kelas)
                    <tr><td><p class="font-semibold text-[#173f5f]">{{ $kelas->nama }}</p><p class="mt-1 text-sm text-[#6c7c82]">Rp {{ number_format($kelas->harga, 0, ',', '.') }}</p></td><td><p class="text-sm text-[#1e2d36]">{{ $kelas->program->nama ?? '-' }}</p><p class="mt-1 text-xs text-[#6c7c82]">{{ $kelas->level->nama ?? '-' }}</p></td><td class="text-sm text-[#526875]">{{ $kelas->periode ?? '-' }}</td><td><span class="font-semibold text-[#173f5f]">{{ $kelas->pendaftarans_count }}</span><span class="text-[#6c7c82]"> / {{ $kelas->kuota }}</span></td><td><span class="admin-badge {{ $kelas->status === 'buka' ? 'admin-badge-info' : ($kelas->status === 'berjalan' ? 'admin-badge-warning' : 'admin-badge-muted') }}">{{ ucfirst($kelas->status) }}</span></td><td><div class="flex flex-wrap gap-2"><a href="{{ route('admin.kursus.edit', $kelas) }}" class="admin-btn admin-btn-secondary admin-btn-sm"><i class="bi bi-pencil"></i>Edit</a><a href="{{ route('admin.kursus.peserta', $kelas) }}" class="admin-btn admin-btn-secondary admin-btn-sm"><i class="bi bi-people"></i>Peserta</a><form action="{{ route('admin.kursus.destroy', $kelas) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash"></i>Hapus</button></form></div></td></tr>
                @endforeach
            </tbody></table></div><div class="border-t border-[#cfc8bb] px-5 py-4">{{ $kursus->links() }}</div>
        @endif
    </section>
</div>
@endsection
