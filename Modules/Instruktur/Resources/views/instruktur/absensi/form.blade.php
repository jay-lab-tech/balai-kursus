@extends('instruktur::layouts.master')

@section('title', 'Absensi pertemuan '.$risalah->pertemuan_ke)
@section('page-context', 'Instruktur · '.($risalah->kursus->nama ?? 'Kursus'))
@section('page-description', $risalah->tgl_pertemuan
    ? \Carbon\Carbon::parse($risalah->tgl_pertemuan)->translatedFormat('l, j F Y')
    : 'Tanggal pertemuan belum ditentukan')

@section('content')

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-exclamation-octagon-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="bk-note bk-note--perlu">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <div>
            <b>Periksa kembali data absensi.</b>
            <ul>
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- Aksi form dulu dikosongkan sehingga bergantung pada URL halaman saat ini;
     rutenya kebetulan sama, tapi jadi diam-diam rapuh. Sekarang ditulis tegas. --}}
<form method="POST" action="{{ route('instruktur.absensi.store', $risalah) }}">
    @csrf

    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Kehadiran pertemuan {{ $risalah->pertemuan_ke }}</h2>
                <p class="bk-panel__subtitle">Pilih status setiap peserta, lalu simpan sekali di bawah.</p>
            </div>
            <span class="bk-chip">{{ $pendaftaran->count() }} peserta</span>
        </div>

        @if ($pendaftaran->isEmpty())
            <div class="bk-empty">
                <span class="bk-empty__icon"><i class="bi bi-person-x" aria-hidden="true"></i></span>
                <h3>Belum ada peserta</h3>
                <p>Kelas ini belum memiliki peserta terdaftar, jadi belum ada kehadiran yang bisa dicatat.</p>
            </div>
        @else
            <table class="bk-table is-padat">
                <thead>
                    <tr>
                        <th class="r">No</th>
                        <th>Peserta</th>
                        <th class="nw">Status kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendaftaran as $p)
                        @php $terpilih = old("absen.{$p->id}", $statusTersimpan[$p->id] ?? ''); @endphp
                        <tr>
                            <td class="r">{{ $loop->iteration }}</td>
                            <td>
                                <b>{{ $p->peserta->user->name ?? 'Peserta tanpa nama' }}</b><br>
                                <span class="bk-muted bk-code">{{ $p->peserta->nomor_peserta ?? $p->nomor }}</span>
                            </td>
                            <td class="nw">
                                <label class="bk-sr" for="absen-{{ $p->id }}">
                                    Status kehadiran {{ $p->peserta->user->name ?? 'peserta' }}
                                </label>
                                <select id="absen-{{ $p->id }}" name="absen[{{ $p->id }}]" class="bk-select" required>
                                    <option value="">Pilih status</option>
                                    @foreach (\App\Models\Absensi::LABEL_STATUS as $kode => $label)
                                        <option value="{{ $kode }}" @selected($terpilih === $kode)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="bk-panel__foot">
                <span>Menyimpan akan menimpa kehadiran yang sudah tercatat untuk pertemuan ini.</span>
                <span class="bk-row">
                    <a href="{{ route('instruktur.kursus.show', $risalah->kursus) }}" class="bk-btn bk-btn--sm">Batal</a>
                    <button type="submit" class="bk-btn bk-btn--pri bk-btn--sm">
                        <i class="bi bi-check2" aria-hidden="true"></i> Simpan absensi
                    </button>
                </span>
            </div>
        @endif
    </section>
</form>
@endsection
