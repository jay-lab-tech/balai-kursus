<script>
    /* Daftar peserta bergantung pada kelas yang dipilih, jadi diambil lewat
       endpoint terpisah setiap kali pilihan kelas berubah. Dipakai bersama
       oleh halaman buat dan ubah draft sertifikat. */
    (function () {
        const pilihKelas = document.getElementById('course_id');
        const pilihPeserta = document.getElementById('participant_id');
        const alamat = @json(route('admin.certificates.participants'));

        if (! pilihKelas || ! pilihPeserta) return;

        const isi = (teks) => { pilihPeserta.innerHTML = `<option value="">${teks}</option>`; };

        function muat(idKelas, idTerpilih) {
            if (! idKelas) return isi('Pilih kelas dulu');

            // Pilihan lama sengaja dibiarkan utuh selama permintaan berjalan.
            // Kalau langsung dikosongkan, peserta yang sedang tersimpan hilang
            // dari form begitu halaman ubah dibuka — dan ikut terkirim kosong
            // seandainya admin menyimpan sebelum daftarnya sempat datang.
            pilihPeserta.disabled = true;

            fetch(`${alamat}?course_id=${encodeURIComponent(idKelas)}`, {
                headers: { 'Accept': 'application/json' },
            })
                .then((jawab) => {
                    if (! jawab.ok) throw new Error('gagal');

                    return jawab.json();
                })
                .then((daftar) => {
                    if (! Array.isArray(daftar) || ! daftar.length) {
                        return isi('Tidak ada peserta yang memenuhi syarat');
                    }

                    isi('Pilih peserta');

                    daftar.forEach((peserta) => {
                        const pilihan = document.createElement('option');
                        pilihan.value = peserta.id;
                        pilihan.textContent = `${peserta.nomor_peserta} — ${peserta.nama}`;
                        pilihan.selected = String(idTerpilih) === String(peserta.id);
                        pilihPeserta.append(pilihan);
                    });
                })
                .catch(() => isi('Gagal memuat peserta — coba muat ulang halaman'))
                .finally(() => { pilihPeserta.disabled = false; });
        }

        pilihKelas.addEventListener('change', function () {
            muat(this.value, null);
        });

        // Kelas sudah terisi saat halaman ubah dibuka atau saat form kembali
        // dengan galat validasi; pesertanya ikut dimuat ulang sekali di awal.
        if (pilihKelas.value) {
            muat(pilihKelas.value, pilihPeserta.dataset.terpilih);
        }
    })();
</script>
