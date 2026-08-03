# Arsip

Berkas yang sudah tidak dipakai lagi, dikumpulkan di satu tempat supaya akar
project dan folder `docs/` tetap bersih. Tidak ada satu pun berkas di sini yang
dirujuk oleh aplikasi, oleh `capture.mjs`, maupun oleh laporan PKL — jadi
seluruh folder ini aman dihapus kalau memang sudah tidak diperlukan.

| Folder | Isi | Kenapa diarsipkan |
| --- | --- | --- |
| `skrip-laporan/` | 15 skrip Python sekali pakai untuk menyusun, menata ulang, dan memeriksa berkas `.docx` laporan | Laporan sekarang sudah final; skrip ini menyusun versi-versi lama |
| `skrip-uji/` | `test_midtrans.php`, `verify_database.php`, `mysql-test.err` | Skrip coba-coba di luar suite `tests/`, plus satu berkas log yang tertinggal |
| `laporan-lama/` | 10 berkas `.docx` revisi laporan terdahulu | Digantikan `docs/LAPORAN PKL RPL - VERSI SIAP 2.docx` |
| `mockup-arah-lama/` | Mockup arah desain A dan B | Yang dipakai arah C ("Balai Hangat"), tetap di `docs/mockups/` |
| `tangkapan-layar-kerja/` | `fase3/`, `fase4/`, `screenshots-before/`, `screenshots-capture-archive/`, `screenshots-old-archive/` | Tangkapan layar kerja per fase dan sebelum redesain; puluhan MB dan tidak dilacak Git |

Yang **tidak** diarsipkan karena masih terpakai: `capture.mjs` (dipakai untuk
mengambil ulang tangkapan layar laporan), `docs/screenshots/`,
`docs/mockups/arah-c-*.png` dan `index.png`, `docs/erd-balai-kursus.*`,
`docs/alur-proses-balai-kursus.*`, serta seluruh berkas `.md` di `docs/`.
