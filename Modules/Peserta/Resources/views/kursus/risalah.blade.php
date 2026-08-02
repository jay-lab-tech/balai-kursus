@extends('peserta::layouts.student')

@section('title', 'Risalah '.$kursus->nama)
@section('page-context', 'Peserta · Risalah')
@section('page-description', 'Catatan tiap pertemuan kelas '.$kursus->nama.'.')

@section('content')

{{--
    Dulu halaman ini mencetak satu modal per risalah, lalu menambal
    <style>[id^="risalahModal"]{display:flex!important}</style> — yang justru
    mengalahkan .hidden milik Tailwind, sehingga semua modal tampil sekaligus
    begitu halaman dibuka. Sekarang cukup satu modal yang isinya diganti
    lewat Alpine, sama seperti halaman nilai instruktur.
--}}
<div x-data="risalahKelas()">

    <div class="bk-panel__head" style="border:0;padding-left:0;padding-right:0">
        <div>
            <p class="bk-eyebrow">{{ $kursus->program->nama ?? 'Program' }}</p>
            <h1 class="bk-panel__title">Risalah {{ $kursus->nama }}</h1>
            <p class="bk-panel__subtitle">Catatan pertemuan yang diisi pengajar, terbaru di atas.</p>
        </div>
        <a href="{{ route('peserta.kursus.detail', $kursus) }}" class="bk-btn bk-btn--sm">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke kelas
        </a>
    </div>

    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">{{ $risalahs->count() }} pertemuan tercatat</h2>
                @if (request('search'))
                    <p class="bk-panel__subtitle">Hasil pencarian &ldquo;{{ request('search') }}&rdquo;.</p>
                @endif
            </div>
            {{-- action="" dulu dikosongkan; di URL berparameter itu membuat query lama ikut terbawa. --}}
            <form method="GET" action="{{ route('peserta.kursus.risalah', $kursus) }}" class="bk-tools">
                <label class="bk-pillfield bk-pillfield--cari">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <span class="bk-sr">Cari materi atau catatan</span>
                    <input type="search" name="search" value="{{ request('search') }}"
                           placeholder="Cari materi atau catatan">
                </label>
                <button type="submit" class="bk-btn bk-btn--sm">Cari</button>
                @if (request('search'))
                    <a href="{{ route('peserta.kursus.risalah', $kursus) }}" class="bk-linkbtn">Bersihkan</a>
                @endif
            </form>
        </div>

        @if ($risalahs->isEmpty())
            <div class="bk-empty">
                <span class="bk-empty__icon"><i class="bi bi-journal" aria-hidden="true"></i></span>
                <h3>{{ request('search') ? 'Tidak ada risalah yang cocok' : 'Belum ada risalah' }}</h3>
                <p>
                    @if (request('search'))
                        Coba kata kunci lain, atau bersihkan pencarian untuk melihat seluruh pertemuan.
                    @else
                        Pengajar menambahkan catatan pertemuan setelah kelas berlangsung.
                    @endif
                </p>
            </div>
        @else
            <table class="bk-table">
                <thead>
                    <tr>
                        <th class="nw">Pertemuan</th>
                        <th class="nw">Tanggal</th>
                        <th>Materi</th>
                        <th class="r nw">Hadir</th>
                        <th class="r nw">Rincian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($risalahs as $r)
                        <tr>
                            <td class="nw"><b class="bk-num">{{ $r->pertemuan_ke }}</b></td>
                            <td class="nw">{{ $r->tgl_pertemuan?->translatedFormat('j M Y') ?? '—' }}</td>
                            <td>
                                @if ($r->materi)
                                    {{ \Illuminate\Support\Str::limit($r->materi, 70) }}
                                @else
                                    <span class="bk-muted">Belum dicatat</span>
                                @endif
                            </td>
                            <td class="r nw bk-num">{{ $r->jumlah_hadir }}</td>
                            <td class="r nw">
                                @if ($r->dokumen)
                                    <a href="{{ route('instruktur.risalah.download', $r) }}" class="bk-iconbtn"
                                       title="Unduh dokumen pertemuan {{ $r->pertemuan_ke }}">
                                        <i class="bi bi-download" aria-hidden="true"></i>
                                        <span class="bk-sr">Unduh dokumen pertemuan {{ $r->pertemuan_ke }}</span>
                                    </a>
                                @endif
                                <button type="button" class="bk-iconbtn"
                                        title="Lihat rincian pertemuan {{ $r->pertemuan_ke }}"
                                        @click="buka({{ Js::from([
                                            'pertemuan' => $r->pertemuan_ke,
                                            'tanggal' => $r->tgl_pertemuan?->translatedFormat('j F Y') ?? 'Belum ditentukan',
                                            'materi' => $r->materi,
                                            'catatan' => $r->catatan,
                                            'hadir' => $r->jumlah_hadir,
                                            'dokumen' => $r->dokumen ? route('instruktur.risalah.download', $r) : null,
                                        ]) }})">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                    <span class="bk-sr">Lihat rincian pertemuan {{ $r->pertemuan_ke }}</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

    <div x-cloak x-show="tampil" class="bk-modal" @keydown.escape.window="tutup()">
        <div class="bk-modal__box" @click.outside="tutup()">
            <div class="bk-modal__head">
                <div>
                    <h2 class="bk-panel__title">Pertemuan <span x-text="isi.pertemuan"></span></h2>
                    <p class="bk-panel__subtitle" x-text="isi.tanggal"></p>
                </div>
                <button type="button" class="bk-iconbtn" @click="tutup()" title="Tutup">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                    <span class="bk-sr">Tutup</span>
                </button>
            </div>

            <div class="bk-modal__body">
                <dl class="bk-facts">
                    <div><dt>Kelas</dt><dd>{{ $kursus->nama }}</dd></div>
                    <div><dt>Peserta hadir</dt><dd class="bk-num" x-text="isi.hadir"></dd></div>
                </dl>

                <h3 class="bk-eyebrow">Materi</h3>
                <p x-text="isi.materi || 'Belum ada materi yang dicatat.'"></p>

                <h3 class="bk-eyebrow">Catatan pengajar</h3>
                <p x-text="isi.catatan || 'Tidak ada catatan.'"></p>
            </div>

            <div class="bk-modal__foot">
                <template x-if="isi.dokumen">
                    <a :href="isi.dokumen" class="bk-linkbtn">
                        <i class="bi bi-download" aria-hidden="true"></i> Unduh dokumen
                    </a>
                </template>
                <template x-if="! isi.dokumen">
                    <span class="bk-muted">Tidak ada dokumen terlampir.</span>
                </template>
                <button type="button" class="bk-btn bk-btn--sm" @click="tutup()">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function risalahKelas() {
        return {
            tampil: false,
            isi: { pertemuan: '', tanggal: '', materi: '', catatan: '', hadir: 0, dokumen: null },
            buka(data) {
                this.isi = data;
                this.tampil = true;
            },
            tutup() {
                this.tampil = false;
            },
        };
    }
</script>
@endsection
