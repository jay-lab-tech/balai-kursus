{{--
    Satu penangan pembayaran untuk seluruh ruang peserta.

    Dulu ada tiga fungsi bernama beda — startPayment, payAssignedClass, dan
    startCoursePayment — yang isinya sama persis baris demi baris, masing-masing
    ditempel di bawah satu view. Ketiganya juga menulis sendiri alamat
    /peserta/pembayaran-online/... dan /peserta/pembayaran-success/... sebagai
    teks, sehingga tidak ikut berubah kalau prefix rutenya digeser.

    Sekarang tombol cukup diberi atribut:
        <button data-bk-bayar data-pendaftaran="7" data-tagihan="450000">
    Klik ditangkap di tingkat dokumen, jadi tombol yang muncul belakangan
    (misalnya di dalam modal) tetap ikut bekerja tanpa pendaftaran ulang.
--}}
<script>
    (() => {
        const RUTE_BUAT = @json(url('/peserta/pembayaran-online'));
        const RUTE_SUKSES = @json(url('/peserta/pembayaran-success'));
        const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        async function bayar(tombol) {
            const pendaftaran = Number(tombol.dataset.pendaftaran);
            const tagihan = Number(tombol.dataset.tagihan);

            if (!pendaftaran || !(tagihan >= 1)) {
                alert('Tagihan kelas ini tidak valid. Muat ulang halaman lalu coba lagi.');
                return;
            }

            if (typeof snap === 'undefined') {
                alert('Layanan pembayaran belum siap. Periksa koneksi Anda lalu muat ulang halaman.');
                return;
            }

            const isiAwal = tombol.innerHTML;
            tombol.disabled = true;
            tombol.innerHTML = '<i class="bi bi-arrow-repeat" aria-hidden="true"></i> Menyiapkan...';

            const pulihkan = () => {
                tombol.disabled = false;
                tombol.innerHTML = isiAwal;
            };

            try {
                const jawaban = await fetch(`${RUTE_BUAT}/${pendaftaran}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': TOKEN },
                    body: JSON.stringify({ amount: tagihan }),
                });

                // Respons non-JSON dulu meledak jadi galat parser yang tidak
                // terbaca; sekarang statusnya diperiksa lebih dulu.
                if (!jawaban.ok) {
                    throw new Error('Server menolak permintaan pembayaran (' + jawaban.status + ').');
                }

                const data = await jawaban.json();
                if (data.error) throw new Error(data.error);

                snap.pay(data.snap_token, {
                    onSuccess: () => { window.location.href = `${RUTE_SUKSES}/${data.order_id}`; },
                    onPending: () => { window.location.reload(); },
                    onError: () => { alert('Pembayaran gagal diproses.'); pulihkan(); },
                    onClose: pulihkan,
                });
            } catch (galat) {
                alert(galat.message);
                pulihkan();
            }
        }

        document.addEventListener('click', (peristiwa) => {
            const tombol = peristiwa.target.closest('[data-bk-bayar]');
            if (tombol) bayar(tombol);
        });
    })();
</script>
