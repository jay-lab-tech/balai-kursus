@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Nilai Peserta - {{ $kursus->nama }}</h1>
            <a href="{{ route('instruktur.kursus.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>

        <div class="overflow-x-auto">
            <form method="GET" action="" class="mb-4 flex flex-wrap gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peserta..." class="border rounded px-3 py-2" />
                <select name="filter" class="border rounded px-3 py-2">
                    <option value="">Semua</option>
                    <option value="lulus" {{ request('filter')=='lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="tidak_lulus" {{ request('filter')=='tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Cari/Filter</button>
            </form>
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Listening</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Speaking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reading</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Writing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="nilaiTableBody">
                    @foreach($pendaftarans as $pendaftaran)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <span class="text-blue-600">{{ $pendaftaran->peserta->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pendaftaran->score->listening ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pendaftaran->score->speaking ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pendaftaran->score->reading ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pendaftaran->score->writing ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pendaftaran->score->final_score ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($pendaftaran->score)
                                <button id="edit-nilai-{{ $pendaftaran->score->id }}" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-1 px-3 rounded text-sm">Edit Nilai</button>
                                <form method="POST" action="{{ route('instruktur.nilai.destroy', $pendaftaran->score->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus nilai ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            @else
                                <button id="create-nilai-{{ $pendaftaran->id }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">Tambah Nilai</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Nilai Peserta</h3>
            <form id="createForm" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Listening</label>
                        <input type="number" name="listening" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Speaking</label>
                        <input type="number" name="speaking" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reading</label>
                        <input type="number" name="reading" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Writing</label>
                        <input type="number" name="writing" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Assignment</label>
                        <input type="number" name="assignment" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">UKTP</label>
                        <input type="number" name="uktp" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">UKAP</label>
                        <input type="number" name="ukap" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Var1</label>
                        <input type="number" name="var1" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Var2</label>
                        <input type="number" name="var2" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Var3</label>
                        <input type="number" name="var3" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Var4</label>
                        <input type="number" name="var4" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeCreateModal()" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Nilai Peserta</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Listening</label>
                        <input type="number" name="listening" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="edit_listening">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Speaking</label>
                        <input type="number" name="speaking" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="edit_speaking">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reading</label>
                        <input type="number" name="reading" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="edit_reading">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Writing</label>
                        <input type="number" name="writing" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="edit_writing">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Assignment</label>
                        <input type="number" name="assignment" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="edit_assignment">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">UKTP</label>
                        <input type="number" name="uktp" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="edit_uktp">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">UKAP</label>
                        <input type="number" name="ukap" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="edit_ukap">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Var1</label>
                        <input type="number" name="var1" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="edit_var1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Var2</label>
                        <input type="number" name="var2" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="edit_var2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Var3</label>
                        <input type="number" name="var3" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="edit_var3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Var4</label>
                        <input type="number" name="var4" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="edit_var4">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="edit_keterangan"></textarea>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeEditModal()" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit Nilai
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
                    })
                    .catch(error => alert('Gagal mengambil data nilai: ' + error));
            });
        @endif
        @if(!$pendaftaran->score)
            document.getElementById('create-nilai-{{ $pendaftaran->id }}').addEventListener('click', function() {
                document.getElementById('createForm').action = '/instruktur/nilai?pendaftaran_id={{ $pendaftaran->id }}';
                document.getElementById('createModal').classList.remove('hidden');
            });
        @endif
    @endforeach
    // Close modals
    document.getElementById('editModal').querySelector('button[onclick="closeEditModal()"]').addEventListener('click', function() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editForm').reset();
    });
    document.getElementById('createModal').querySelector('button[onclick="closeCreateModal()"]').addEventListener('click', function() {
        document.getElementById('createModal').classList.add('hidden');
        document.getElementById('createForm').reset();
    });
});
</script>
</script>
@endsection