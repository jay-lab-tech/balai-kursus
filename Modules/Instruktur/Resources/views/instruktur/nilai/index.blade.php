@extends('instruktur::layouts.master')

@section('title', 'Nilai peserta')
@section('page-context', 'Instruktur · '.$kursus->nama)
@section('page-description', 'Rekap nilai akhir kursus per peserta — bukan nilai per pertemuan.')

@section('content')

@php
    $sudah = $pendaftarans->filter(fn ($p) => $p->courseScore)->count();
@endphp

{{--
    Dulu halaman ini memuat dua modal terpisah (tambah dan ubah) dengan grid
    sebelas isian yang identik, ditambah satu penyimak klik per baris yang
    dihasilkan @foreach. Sekarang satu modal dipakai untuk keduanya: mode
    ditentukan saat tombol ditekan, dan seluruh keadaannya dipegang Alpine.
--}}
<div x-data="formNilai()">

@include('instruktur::instruktur.partials.tab-kursus', ['kursus' => $kursus])

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="bk-note bk-note--perlu">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <div>
            <b>Nilai gagal disimpan.</b>
            <ul>
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="bk-stats bk-stats--3">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-people" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Peserta</span>
        <p class="bk-stat__value">{{ $pendaftarans->count() }}</p>
    </article>
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Sudah dinilai</span>
        <p class="bk-stat__value">{{ $sudah }}</p>
    </article>
    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Belum dinilai</span>
        <p class="bk-stat__value">{{ $pendaftarans->count() - $sudah }}</p>
    </article>
</div>

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Nilai akhir kursus</h2>
            <p class="bk-panel__subtitle">
                Nilai akhir dihitung otomatis dari rata-rata komponen yang terisi.
                Ambang lulus {{ \App\Models\Score::NILAI_LULUS }}.
            </p>
        </div>
        <div class="bk-tools">
            <form method="GET" action="{{ route('instruktur.nilai.index', $kursus) }}" class="bk-tools">
                <div class="bk-pillfield bk-pillfield--cari">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <label for="search" class="bk-sr">Cari peserta</label>
                    <input type="search" id="search" name="search" value="{{ request('search') }}" placeholder="Nama peserta">
                    <button type="submit" class="bk-sr">Cari</button>
                </div>
                <label for="filter" class="bk-sr">Saring status nilai</label>
                <select id="filter" name="filter" class="bk-pillselect" onchange="this.form.submit()">
                    <option value="">Semua status</option>
                    <option value="lulus" @selected(request('filter') === 'lulus')>Lulus</option>
                    <option value="tidak_lulus" @selected(request('filter') === 'tidak_lulus')>Belum lulus</option>
                    <option value="belum" @selected(request('filter') === 'belum')>Belum dinilai</option>
                </select>
            </form>
            <a href="{{ route('instruktur.nilai.export', $kursus) }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-download" aria-hidden="true"></i> Unduh CSV
            </a>
        </div>
    </div>

    @if ($pendaftarans->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-clipboard-x" aria-hidden="true"></i></span>
            <h3>Tidak ada peserta yang cocok</h3>
            <p>Ubah kata kunci atau saringan, atau tunggu admin menempatkan peserta ke kelas ini.</p>
        </div>
    @else
        <table class="bk-table is-padat">
            <thead>
                <tr>
                    <th class="r">No</th>
                    <th>Peserta</th>
                    @foreach ($komponen as $label)
                        <th class="r nw">{{ $label }}</th>
                    @endforeach
                    <th class="r nw">Nilai akhir</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pendaftarans as $p)
                    @php $nilai = $p->courseScore; @endphp
                    <tr>
                        <td class="r">{{ $loop->iteration }}</td>
                        <td>
                            <b>{{ $p->peserta->user->name ?? 'Peserta tanpa nama' }}</b><br>
                            <span class="bk-muted bk-code">{{ $p->peserta->nomor_peserta ?? $p->nomor }}</span>
                        </td>
                        @foreach (array_keys($komponen) as $kolom)
                            <td class="r nw">{{ $nilai && ! is_null($nilai->{$kolom}) ? $nilai->{$kolom} : '—' }}</td>
                        @endforeach
                        <td class="r nw">
                            @if (! $nilai || is_null($nilai->final_score))
                                <span class="bk-tag bk-tag--diam">Belum ada</span>
                            @elseif ($nilai->isLulus())
                                <span class="bk-tag">{{ $nilai->final_score }} · Lulus</span>
                            @else
                                <span class="bk-tag bk-tag--perlu">{{ $nilai->final_score }} · Belum lulus</span>
                            @endif
                        </td>
                        <td class="r nw">
                            @if ($nilai)
                                <button type="button" class="bk-iconbtn"
                                        title="Ubah nilai {{ $p->peserta->user->name ?? 'peserta' }}"
                                        @click="buka({{ Js::from([
                                            'peserta' => $p->peserta->user->name ?? 'Peserta',
                                            'aksi' => route('instruktur.nilai.update', $nilai),
                                            'metode' => 'PUT',
                                            'nilai' => $nilai->only(array_merge(array_keys($komponen), ['keterangan'])),
                                        ]) }})">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                    <span class="bk-sr">Ubah nilai</span>
                                </button>
                                <form method="POST" action="{{ route('instruktur.nilai.destroy', $nilai) }}" style="display:inline"
                                      onsubmit="return confirm('Hapus nilai {{ $p->peserta->user->name ?? 'peserta ini' }}? Nilai akhirnya kembali kosong.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bk-iconbtn bk-iconbtn--danger" title="Hapus nilai">
                                        <i class="bi bi-trash3" aria-hidden="true"></i>
                                        <span class="bk-sr">Hapus nilai</span>
                                    </button>
                                </form>
                            @else
                                <button type="button" class="bk-btn bk-btn--sm"
                                        @click="buka({{ Js::from([
                                            'peserta' => $p->peserta->user->name ?? 'Peserta',
                                            'aksi' => route('instruktur.nilai.store'),
                                            'metode' => 'POST',
                                            'pendaftaran' => $p->id,
                                            'nilai' => [],
                                        ]) }})">
                                    <i class="bi bi-plus-lg" aria-hidden="true"></i> Isi nilai
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</section>

<div x-cloak x-show="tampil" class="bk-modal" @keydown.escape.window="tutup()">
    <div class="bk-modal__box" @click.outside="tutup()">
        <form :action="aksi" method="POST">
            @csrf
            <input type="hidden" name="_method" :value="metode">
            <input type="hidden" name="pendaftaran_id" :value="pendaftaran">

            <div class="bk-modal__head">
                <div>
                    <h2 class="bk-panel__title" x-text="judul"></h2>
                    <p class="bk-panel__subtitle" x-text="peserta"></p>
                </div>
                <button type="button" class="bk-iconbtn" @click="tutup()" title="Tutup">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                    <span class="bk-sr">Tutup</span>
                </button>
            </div>

            <div class="bk-modal__body">
                <div class="bk-fields">
                    @foreach ($komponen as $kolom => $label)
                        <div class="bk-field">
                            <label class="bk-label" for="nilai-{{ $kolom }}">{{ $label }}</label>
                            <input type="number" id="nilai-{{ $kolom }}" name="{{ $kolom }}"
                                   class="bk-input" min="0" max="100" step="0.01"
                                   x-model="isian.{{ $kolom }}" placeholder="0–100">
                        </div>
                    @endforeach

                    <div class="bk-field bk-field--wide">
                        <label class="bk-label" for="nilai-keterangan">Keterangan</label>
                        <textarea id="nilai-keterangan" name="keterangan" rows="3" class="bk-textarea"
                                  x-model="isian.keterangan"
                                  placeholder="Catatan singkat untuk peserta ini."></textarea>
                    </div>
                </div>
                <p class="bk-hint">
                    Komponen yang dikosongkan tidak ikut dihitung. Nilai 0 tetap dihitung sebagai nol.
                </p>
            </div>

            <div class="bk-modal__foot">
                <span class="bk-muted">Ambang lulus {{ \App\Models\Score::NILAI_LULUS }}.</span>
                <span class="bk-row">
                    <button type="button" class="bk-btn bk-btn--sm" @click="tutup()">Batal</button>
                    <button type="submit" class="bk-btn bk-btn--pri bk-btn--sm">
                        <i class="bi bi-check2" aria-hidden="true"></i> Simpan nilai
                    </button>
                </span>
            </div>
        </form>
    </div>
</div>

</div>
@endsection

@section('scripts')
<script>
    const KOLOM_NILAI = @json(array_keys($komponen));

    function formNilai() {
        const kosong = () => {
            const isian = { keterangan: '' };
            KOLOM_NILAI.forEach((k) => { isian[k] = ''; });
            return isian;
        };

        return {
            tampil: false,
            judul: '',
            peserta: '',
            aksi: '',
            metode: 'POST',
            pendaftaran: '',
            isian: kosong(),

            buka(data) {
                this.judul = data.metode === 'PUT' ? 'Ubah nilai akhir' : 'Isi nilai akhir';
                this.peserta = data.peserta;
                this.aksi = data.aksi;
                this.metode = data.metode;
                this.pendaftaran = data.pendaftaran ?? '';

                // Nilai dibawa langsung dari baris tabel, bukan lewat fetch ke
                // endpoint show seperti sebelumnya — datanya sudah ada di
                // halaman ini. Penggabungan pakai ?? agar angka 0 yang sah
                // tidak berubah jadi kolom kosong.
                const isian = kosong();
                Object.entries(data.nilai ?? {}).forEach(([kolom, angka]) => {
                    isian[kolom] = angka ?? '';
                });
                this.isian = isian;
                this.tampil = true;
            },

            tutup() {
                this.tampil = false;
            },
        };
    }
</script>
@endsection
