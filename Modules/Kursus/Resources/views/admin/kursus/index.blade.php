@extends('layouts.admin')

@section('title', 'Manajemen Kursus')

@section('page-title', 'Manajemen Kursus')

@section('content')
<div class="space-y-6">
	<div class="flex justify-between items-center">
		<h2 class="text-2xl font-bold text-gray-900"><i class="bi bi-book me-2"></i>Manajemen Kursus</h2>
		<a href="{{ route('admin.kursus.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"><i class="bi bi-plus-circle me-2"></i>Tambah Kursus</a>
	</div>

	<div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
		@php
			$kursus_selesai = $kursus->filter(function($k) {
				return $k->tanggal_selesai && \Carbon\Carbon::parse($k->tanggal_selesai)->lt(now());
			});
		@endphp
		@if($kursus_selesai->count())
			<div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border-l-4 border-yellow-400 text-yellow-800 px-6 py-4 mx-6 mt-6 rounded-md shadow-sm">
				<div class="flex items-center">
					<div class="flex-shrink-0">
						<i class="bi bi-exclamation-triangle-fill text-yellow-400"></i>
					</div>
					<div class="ml-3">
						<p class="text-sm font-medium">
							<strong>Pemberitahuan:</strong> Ada {{ $kursus_selesai->count() }} kursus yang sudah melewati tanggal selesai.
						</p>
					</div>
				</div>
			</div>
		@endif
		<div class="overflow-x-auto">
			<table class="min-w-full divide-y divide-gray-300">
				<thead class="bg-gradient-to-r from-gray-50 to-gray-100">
					<tr>
						<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
							<div class="flex items-center">
								<i class="bi bi-hash mr-2 text-gray-400"></i>
								No
							</div>
						</th>
						<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
							<div class="flex items-center">
								<i class="bi bi-book mr-2 text-gray-400"></i>
								Nama Kursus
							</div>
						</th>
						<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
							<div class="flex items-center">
								<i class="bi bi-diagram-3 mr-2 text-gray-400"></i>
								Program
							</div>
						</th>
						<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
							<div class="flex items-center">
								<i class="bi bi-bar-chart mr-2 text-gray-400"></i>
								Level
							</div>
						</th>
						<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
							<div class="flex items-center">
								<i class="bi bi-person mr-2 text-gray-400"></i>
								Instruktur
							</div>
						</th>
						<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
							<div class="flex items-center">
								<i class="bi bi-cash mr-2 text-gray-400"></i>
								Harga
							</div>
						</th>
						<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
							<div class="flex items-center">
								<i class="bi bi-people mr-2 text-gray-400"></i>
								Kuota
							</div>
						</th>
						<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
							<div class="flex items-center">
								<i class="bi bi-gear mr-2 text-gray-400"></i>
								Aksi
							</div>
						</th>
					</tr>
				</thead>
				<tbody class="bg-white divide-y divide-gray-200">
					@foreach($kursus as $k)
						<tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 {{ $k->tanggal_selesai && \Carbon\Carbon::parse($k->tanggal_selesai)->lt(now()) ? 'bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-400' : 'hover:shadow-sm' }}">
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $kursus->firstItem() + $loop->index }}</td>
							<td class="px-6 py-4 whitespace-nowrap">
								<div class="flex items-center">
									<div class="flex-shrink-0 h-10 w-10">
										<div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
											<i class="bi bi-book text-white text-sm"></i>
										</div>
									</div>
									<div class="ml-4">
										<div class="text-sm font-semibold text-gray-900">{{ $k->nama }}</div>
										<div class="text-sm text-gray-500">{{ $k->periode ?? 'Tidak ada periode' }}</div>
									</div>
								</div>
							</td>
							<td class="px-6 py-4 whitespace-nowrap">
								<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
									<i class="bi bi-diagram-3 mr-1"></i>
									{{ $k->program->nama }}
								</span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap">
								<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
									<i class="bi bi-bar-chart mr-1"></i>
									{{ $k->level->nama }}
								</span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
								<div class="flex items-center">
									<i class="bi bi-person-circle text-gray-400 mr-2"></i>
									{{ $k->instruktur->nama_instr }}
									@if($k->instruktur_id_2)
										<br><small class="text-gray-500">{{ $k->instruktur2->nama_instr }}</small>
									@endif
								</div>
							</td>
							<td class="px-6 py-4 whitespace-nowrap">
								<span class="text-lg font-bold text-green-600">Rp {{ number_format($k->harga) }}</span>
								@if($k->harga_upi)
									<br><small class="text-gray-500">UPI: Rp {{ number_format($k->harga_upi) }}</small>
								@endif
							</td>
							<td class="px-6 py-4 whitespace-nowrap">
								<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 border-2 border-blue-200">
									<i class="bi bi-people-fill mr-1"></i>
									{{ $k->kuota }}
								</span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
								<div class="flex items-center space-x-3">
									<a href="{{ route('admin.kursus.edit', $k->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-150">
										<i class="bi bi-pencil-square mr-1"></i>
										Edit
									</a>
									<form action="{{ route('admin.kursus.destroy', $k->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus kursus ini?')">
										@csrf
										@method('DELETE')
										<button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150">
											<i class="bi bi-trash mr-1"></i>
											Hapus
										</button>
									</form>
									<a href="{{ route('admin.kursus.peserta', $k->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
										<i class="bi bi-people mr-1"></i>
										Peserta
									</a>
									<a href="/admin/kursus/{{ $k->id }}/jadwal" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-150">
										<i class="bi bi-calendar mr-1"></i>
										Jadwal
									</a>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@if($kursus->hasPages())
			<div class="bg-white border-t border-gray-200 px-4 py-5 sm:px-6">
				{{ $kursus->links() }}
			</div>
		@endif
	</div>
</div>
@endsection
