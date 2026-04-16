<div class="space-y-8 max-w-6xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-easel2-fill text-red-400"></i>
            {{ $template ? 'Edit Template' : 'Template Baru' }}
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">{{ $template ? 'Perbarui template sertifikat resmi.' : 'Buat template sertifikat resmi.' }}</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Template ini menjadi sumber semua elemen visual dan metadata resmi ketika sertifikat PDF digenerate untuk peserta.</p>
    </section>

    @if($errors->any())
        <div class="rounded-[1.5rem] border border-red-500/20 bg-red-600/10 px-5 py-4 text-red-100 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill mt-0.5 text-lg text-red-300"></i>
                <div>
                    <p class="font-semibold">Template belum bisa disimpan</p>
                    <ul class="mt-2 space-y-1 text-sm text-red-100/90">
                        @foreach($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ $action }}" class="space-y-6">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        <section class="grid gap-6 xl:grid-cols-[1.2fr_1fr]">
            <div class="space-y-6">
                <div class="admin-panel rounded-[2rem] p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-white">Identitas Lembaga</h2>
                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Nama Template</label>
                            <input type="text" name="name" value="{{ old('name', $template?->name) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Kota Terbit</label>
                            <input type="text" name="city" value="{{ old('city', $template?->city ?? 'Bandung') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Nama Lembaga</label>
                            <input type="text" name="institution_name" value="{{ old('institution_name', $template?->institution_name ?? 'UNIVERSITAS PENDIDIKAN INDONESIA') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Nama Unit</label>
                            <input type="text" name="unit_name" value="{{ old('unit_name', $template?->unit_name ?? 'BALAI BAHASA') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white" required>
                        </div>
                    </div>
                </div>

                <div class="admin-panel rounded-[2rem] p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-white">Penandatangan & Nomor</h2>
                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Nama Penandatangan</label>
                            <input type="text" name="signer_name" value="{{ old('signer_name', $template?->signer_name) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Jabatan</label>
                            <input type="text" name="signer_title" value="{{ old('signer_title', $template?->signer_title) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">NIP</label>
                            <input type="text" name="signer_nip" value="{{ old('signer_nip', $template?->signer_nip) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Prefix Nomor Sertifikat</label>
                            <input type="text" name="certificate_prefix" value="{{ old('certificate_prefix', $template?->certificate_prefix) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="admin-panel rounded-[2rem] p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-white">Path Aset</h2>
                    <p class="mt-2 text-sm text-slate-400">Masukkan path relatif dari folder public, misalnya <span class="font-mono text-slate-300">images/certificate/logo_upi_ttd.png</span>.</p>
                    <div class="mt-6 space-y-5">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Logo Header</label>
                            <input type="text" name="header_logo_path" value="{{ old('header_logo_path', $template?->header_logo_path) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Background / Border</label>
                            <input type="text" name="background_image_path" value="{{ old('background_image_path', $template?->background_image_path) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Tanda Tangan</label>
                            <input type="text" name="signature_image_path" value="{{ old('signature_image_path', $template?->signature_image_path) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Cap / Label TTD</label>
                            <input type="text" name="stamp_image_path" value="{{ old('stamp_image_path', $template?->stamp_image_path) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white">
                        </div>
                    </div>
                </div>

                <div class="admin-panel rounded-[2rem] p-6 sm:p-8">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="is_active" value="1" class="mt-1 rounded border-white/10 bg-slate-950/70 text-red-600 focus:ring-red-500" {{ old('is_active', $template?->is_active ?? true) ? 'checked' : '' }}>
                        <span>
                            <span class="block text-sm font-semibold text-white">Jadikan template aktif</span>
                            <span class="mt-1 block text-sm text-slate-400">Template aktif akan langsung dipakai saat admin membuat draft sertifikat baru.</span>
                        </span>
                    </label>
                </div>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600">
                <i class="bi bi-check-circle-fill"></i>
                {{ $submitLabel }}
            </button>
            <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-arrow-left"></i>
                Batal
            </a>
        </div>
    </form>
</div>
