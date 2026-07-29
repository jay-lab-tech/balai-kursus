@extends('instruktur::layouts.master')

@section('title', 'Nilai Akhir Kelas')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#cfc8bb] pb-4">
            <a href="{{ route('instruktur.kursus.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#526875] transition hover:text-[#0d9488]"><i class="bi bi-arrow-left"></i> Kembali ke kursus saya</a>
            <span class="font-mono text-xs uppercase tracking-[.16em] text-[#6c7c82]">Kursus / {{ $kursus->id }} / Nilai</span>
        </div>
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="font-mono text-xs uppercase tracking-[.18em] text-[#0d9488]">{{ $kursus->program->nama ?? 'Program' }} / {{ $kursus->level->nama ?? 'Level' }}</p>
                <h1 class="mt-2 text-4xl tracking-tight text-[#173f5f]">Nilai Akhir Kelas</h1>
                <p class="mt-3 max-w-3xl text-sm text-gray-400">
                    Halaman ini menyimpan <span class="font-semibold text-white">nilai akhir kursus per peserta</span>.
                    Jadi saat ini nilainya belum dibuat per pertemuan, melainkan rekap akhir setelah proses belajar di kelas berjalan atau selesai.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('instruktur.kursus.show', $kursus) }}" class="inline-flex items-center rounded-xl border border-[#cfc8bb] bg-[#fffefa] px-4 py-3 font-semibold text-[#173f5f] hover:border-[#0d9488] transition">
                    <i class="bi bi-arrow-left mr-2"></i>Kembali ke Detail Kelas
                </a>
                <a href="{{ route('instruktur.nilai.export', $kursus->id) }}" class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-3 font-semibold text-white hover:bg-emerald-500 transition">
                    <i class="bi bi-download mr-2"></i>Export Nilai
                </a>
            </div>
        </div>

        <nav class="flex flex-wrap gap-1 border-b border-[#cfc8bb]" aria-label="Navigasi kursus">
            <a href="{{ route('instruktur.kursus.show', $kursus) }}" class="px-4 py-3 text-sm font-semibold text-[#6c7c82] transition hover:text-[#0d9488]">Ringkasan</a>
            <a href="{{ route('instruktur.risalah.index', $kursus) }}" class="px-4 py-3 text-sm font-semibold text-[#6c7c82] transition hover:text-[#0d9488]">Pertemuan &amp; Risalah</a>
            <a href="{{ route('instruktur.nilai.index', $kursus) }}" class="border-b-2 border-[#a84a2a] px-4 py-3 text-sm font-semibold text-[#173f5f]">Nilai Peserta</a>
        </nav>

        @if(session('success'))
            <div class="rounded-2xl border border-green-500/30 bg-green-500/10 px-5 py-4 text-green-100">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-5 md:grid-cols-3">
            <div class="rounded-3xl bg-gradient-to-br from-slate-800 to-slate-900 p-6 text-white shadow-xl">
                <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Total Peserta</p>
                <p class="mt-3 text-4xl font-bold">{{ $pendaftarans->count() }}</p>
            </div>
            <div class="rounded-3xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 text-white shadow-xl">
                <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Sudah Dinilai</p>
                <p class="mt-3 text-4xl font-bold">{{ $pendaftarans->filter(fn($item) => $item->score)->count() }}</p>
            </div>
            <div class="rounded-3xl bg-gradient-to-br from-amber-500 to-orange-700 p-6 text-white shadow-xl">
                <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Belum Dinilai</p>
                <p class="mt-3 text-4xl font-bold">{{ $pendaftarans->filter(fn($item) => !$item->score)->count() }}</p>
            </div>
        </div>

        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <form method="GET" action="" class="flex flex-col gap-3 lg:flex-row">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peserta..." class="w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white placeholder:text-gray-500 focus:border-yellow-400 focus:outline-none">
                <select name="filter" class="rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white focus:border-yellow-400 focus:outline-none">
                    <option value="">Semua Status Nilai</option>
                    <option value="lulus" @selected(request('filter') == 'lulus')>Lulus</option>
                    <option value="tidak_lulus" @selected(request('filter') == 'tidak_lulus')>Tidak Lulus</option>
                </select>
                <button type="submit" class="rounded-xl bg-yellow-500 px-5 py-3 font-semibold text-gray-900 hover:bg-yellow-400">Filter</button>
            </form>
        </div>

        <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-black/20 text-left text-xs uppercase tracking-[0.25em] text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Peserta</th>
                            <th class="px-6 py-4">Listening</th>
                            <th class="px-6 py-4">Speaking</th>
                            <th class="px-6 py-4">Reading</th>
                            <th class="px-6 py-4">Writing</th>
                            <th class="px-6 py-4">Nilai Akhir</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-sm text-gray-200">
                        @forelse($pendaftarans as $pendaftaran)
                            <tr class="hover:bg-white/5">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-white">{{ $pendaftaran->peserta->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $pendaftaran->peserta->nomor_peserta ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">{{ $pendaftaran->score->listening ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $pendaftaran->score->speaking ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $pendaftaran->score->reading ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $pendaftaran->score->writing ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full bg-white/10 px-3 py-1 text-xs uppercase text-white">{{ $pendaftaran->score->final_score ?? 'Belum ada' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @if($pendaftaran->score)
                                            <button id="edit-nilai-{{ $pendaftaran->score->id }}" type="button" class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-500">Edit Nilai</button>
                                            <form method="POST" action="{{ route('instruktur.nilai.destroy', $pendaftaran->score->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus nilai ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg bg-sky-600 px-3 py-2 text-xs font-semibold text-white hover:bg-sky-500">Hapus</button>
                                            </form>
                                        @else
                                            <button id="create-nilai-{{ $pendaftaran->id }}" type="button" class="rounded-lg bg-yellow-500 px-3 py-2 text-xs font-semibold text-gray-900 hover:bg-yellow-400">Tambah Nilai</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">Belum ada peserta pada kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="createModal" class="fixed inset-0 z-[60] hidden bg-black/70 px-4 py-10">
    <div class="mx-auto max-w-3xl rounded-3xl border border-white/10 bg-gray-900 p-6 shadow-2xl">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-white">Tambah Nilai Akhir</h3>
            <button type="button" onclick="closeCreateModal()" class="rounded-full bg-white/10 p-2 text-white hover:bg-white/20">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form id="createForm" method="POST" action="{{ route('instruktur.nilai.store') }}" class="mt-6 space-y-5">
            @csrf
            <input type="hidden" id="create_pendaftaran_id" name="pendaftaran_id">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-300">Listening</label>
                    <input type="number" name="listening" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Speaking</label>
                    <input type="number" name="speaking" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Reading</label>
                    <input type="number" name="reading" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Writing</label>
                    <input type="number" name="writing" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Assignment</label>
                    <input type="number" name="assignment" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">UKTP</label>
                    <input type="number" name="uktp" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">UKAP</label>
                    <input type="number" name="ukap" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Var1</label>
                    <input type="number" name="var1" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Var2</label>
                    <input type="number" name="var2" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Var3</label>
                    <input type="number" name="var3" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Var4</label>
                    <input type="number" name="var4" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300">Keterangan</label>
                <textarea name="keterangan" rows="3" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeCreateModal()" class="rounded-xl bg-white/10 px-5 py-3 font-semibold text-white hover:bg-white/20">Batal</button>
                <button type="submit" class="rounded-xl bg-yellow-500 px-5 py-3 font-semibold text-gray-900 hover:bg-yellow-400">Simpan Nilai</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-[60] hidden bg-black/70 px-4 py-10">
    <div class="mx-auto max-w-3xl rounded-3xl border border-white/10 bg-gray-900 p-6 shadow-2xl">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-white">Edit Nilai Akhir</h3>
            <button type="button" onclick="closeEditModal()" class="rounded-full bg-white/10 p-2 text-white hover:bg-white/20">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="mt-6 space-y-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-300">Listening</label>
                    <input type="number" name="listening" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white" id="edit_listening">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Speaking</label>
                    <input type="number" name="speaking" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white" id="edit_speaking">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Reading</label>
                    <input type="number" name="reading" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white" id="edit_reading">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Writing</label>
                    <input type="number" name="writing" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white" id="edit_writing">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Assignment</label>
                    <input type="number" name="assignment" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white" id="edit_assignment">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">UKTP</label>
                    <input type="number" name="uktp" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white" id="edit_uktp">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">UKAP</label>
                    <input type="number" name="ukap" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white" id="edit_ukap">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Var1</label>
                    <input type="number" name="var1" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white" id="edit_var1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Var2</label>
                    <input type="number" name="var2" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white" id="edit_var2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Var3</label>
                    <input type="number" name="var3" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white" id="edit_var3">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">Var4</label>
                    <input type="number" name="var4" min="0" max="100" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white" id="edit_var4">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300">Keterangan</label>
                <textarea name="keterangan" rows="3" class="mt-2 block w-full rounded-xl border border-white/10 bg-black/20 px-4 py-3 text-white" id="edit_keterangan"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="rounded-xl bg-white/10 px-5 py-3 font-semibold text-white hover:bg-white/20">Batal</button>
                <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-3 font-semibold text-white hover:bg-indigo-500">Perbarui Nilai</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @foreach($pendaftarans as $pendaftaran)
        @if($pendaftaran->score)
            document.getElementById('edit-nilai-{{ $pendaftaran->score->id }}').addEventListener('click', function() {
                fetch('/instruktur/nilai/{{ $pendaftaran->score->id }}')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_listening').value = data.listening || '';
                        document.getElementById('edit_speaking').value = data.speaking || '';
                        document.getElementById('edit_reading').value = data.reading || '';
                        document.getElementById('edit_writing').value = data.writing || '';
                        document.getElementById('edit_assignment').value = data.assignment || '';
                        document.getElementById('edit_uktp').value = data.uktp || '';
                        document.getElementById('edit_ukap').value = data.ukap || '';
                        document.getElementById('edit_var1').value = data.var1 || '';
                        document.getElementById('edit_var2').value = data.var2 || '';
                        document.getElementById('edit_var3').value = data.var3 || '';
                        document.getElementById('edit_var4').value = data.var4 || '';
                        document.getElementById('edit_keterangan').value = data.keterangan || '';
                        document.getElementById('editForm').action = '/instruktur/nilai/{{ $pendaftaran->score->id }}';
                        document.getElementById('editModal').classList.remove('hidden');
                    });
            });
        @else
            document.getElementById('create-nilai-{{ $pendaftaran->id }}').addEventListener('click', function() {
                document.getElementById('create_pendaftaran_id').value = '{{ $pendaftaran->id }}';
                document.getElementById('createModal').classList.remove('hidden');
            });
        @endif
    @endforeach
});

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.getElementById('createForm').reset();
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editForm').reset();
}
</script>
@endsection
