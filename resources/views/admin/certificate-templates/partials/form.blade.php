@php
    $template = $template ?? null;

    // Path aset ditulis relatif terhadap folder public. Dikumpulkan di satu
    // tempat supaya urutan, label, dan contohnya tidak terpisah-pisah.
    $aset = [
        'header_logo_path' => ['Logo kepala', 'images/certificate/logo_upi_ttd.png'],
        'background_image_path' => ['Latar / bingkai', 'images/certificate/border.png'],
        'signature_image_path' => ['Tanda tangan', 'images/certificate/ttd.png'],
        'stamp_image_path' => ['Cap lembaga', 'images/certificate/cap.png'],
    ];
@endphp

@if ($errors->any())
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-exclamation-octagon-fill bk-note__icon" aria-hidden="true"></i>
        <div>
            <b>Template belum bisa disimpan</b>
            <ul style="margin:.35rem 0 0;padding-left:1.1rem">
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Identitas lembaga</h2>
                <p class="bk-panel__subtitle">Nama yang tercetak di kepala sertifikat, apa adanya sesuai penulisan resmi.</p>
            </div>
        </div>

        <div class="bk-panel__body">
            <div class="bk-fields">
                <div>
                    <label for="name" class="bk-label">Nama template</label>
                    <input type="text" id="name" name="name" class="bk-input"
                           value="{{ old('name', $template?->name) }}" required>
                    <p class="bk-hint">Hanya dipakai admin untuk membedakan template, tidak ikut tercetak.</p>
                    @error('name')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="city" class="bk-label">Kota terbit</label>
                    <input type="text" id="city" name="city" class="bk-input"
                           value="{{ old('city', $template?->city ?? 'Bandung') }}" required>
                    @error('city')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="institution_name" class="bk-label">Nama lembaga</label>
                    <input type="text" id="institution_name" name="institution_name" class="bk-input"
                           value="{{ old('institution_name', $template?->institution_name ?? 'UNIVERSITAS PENDIDIKAN INDONESIA') }}" required>
                    @error('institution_name')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unit_name" class="bk-label">Nama unit</label>
                    <input type="text" id="unit_name" name="unit_name" class="bk-input"
                           value="{{ old('unit_name', $template?->unit_name ?? 'BALAI BAHASA') }}" required>
                    @error('unit_name')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Penandatangan &amp; penomoran</h2>
                <p class="bk-panel__subtitle">Data penandatangan disalin ke tiap sertifikat saat diterbitkan, jadi sertifikat lama tidak ikut berubah kalau pejabatnya berganti.</p>
            </div>
        </div>

        <div class="bk-panel__body">
            <div class="bk-fields">
                <div>
                    <label for="signer_name" class="bk-label">Nama penandatangan</label>
                    <input type="text" id="signer_name" name="signer_name" class="bk-input"
                           value="{{ old('signer_name', $template?->signer_name) }}" required>
                    @error('signer_name')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="signer_title" class="bk-label">Jabatan</label>
                    <input type="text" id="signer_title" name="signer_title" class="bk-input"
                           value="{{ old('signer_title', $template?->signer_title) }}" required>
                    @error('signer_title')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="signer_nip" class="bk-label">NIP</label>
                    <input type="text" id="signer_nip" name="signer_nip" class="bk-input"
                           value="{{ old('signer_nip', $template?->signer_nip) }}" required>
                    @error('signer_nip')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="certificate_prefix" class="bk-label">Kode nomor sertifikat</label>
                    <input type="text" id="certificate_prefix" name="certificate_prefix" class="bk-input"
                           value="{{ old('certificate_prefix', $template?->certificate_prefix) }}" required>
                    <p class="bk-hint">Nomor terbentuk sebagai <span class="bk-code">urutan/kode/tahun</span>, misalnya <span class="bk-code">7/BB-UPI/{{ now()->year }}</span>.</p>
                    @error('certificate_prefix')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Berkas gambar</h2>
                <p class="bk-panel__subtitle">Path relatif dari folder <span class="bk-code">public</span>. Boleh dikosongkan — bagian yang kosong tidak dicetak di PDF.</p>
            </div>
        </div>

        <div class="bk-panel__body">
            <div class="bk-fields">
                @foreach ($aset as $kolom => [$label, $contoh])
                    <div>
                        <label for="{{ $kolom }}" class="bk-label">{{ $label }} <span class="bk-muted">(opsional)</span></label>
                        <input type="text" id="{{ $kolom }}" name="{{ $kolom }}" class="bk-input"
                               value="{{ old($kolom, $template?->{$kolom}) }}" placeholder="{{ $contoh }}">
                        @error($kolom)
                            <p class="bk-error">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="bk-field--wide">
                    <label class="bk-checkcard">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $template?->is_active ?? true) ? 'checked' : '' }}>
                        <span>
                            <b>Jadikan ini template aktif</b>
                            <small>Hanya boleh ada satu template aktif. Mencentang ini otomatis menonaktifkan template lain, dan sertifikat baru langsung memakai yang ini.</small>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <div class="bk-panel__foot">
            <div class="bk-row">
                <button type="submit" class="bk-btn bk-btn--pri">
                    <i class="bi bi-check-lg" aria-hidden="true"></i> {{ $submitLabel }}
                </button>
                <a href="{{ route('admin.templates.index') }}" class="bk-btn">Batal</a>
            </div>
        </div>
    </section>
</form>
