# Catatan Refactor Level & Instruktur per Kursus

## Perubahan Konsep
- Level pada kursus tidak dipilih saat pendaftaran, tapi di-assign setelah tes/seleksi.
- Satu kursus bisa punya banyak level, dan setiap level bisa dipegang instruktur berbeda.
- Relasi peserta–kursus–level dan instruktur–kursus–level menggunakan tabel pivot:
  - peserta_kursus_levels
  - instruktur_kursus_levels

## Perubahan Database
- Migrasi: tambah tabel peserta_kursus_levels & instruktur_kursus_levels.
- Model: tambah relasi ke pivot pada Peserta, Kursus, Level, Instruktur.
- Seeder: data pivot diisi otomatis saat seeding.

## Perubahan Kode
- Model Kursus tidak lagi menyimpan kolom level_id, instruktur_id, instruktur_id_2.
- Semua query dan tampilan yang butuh level/instruktur peserta kursus diambil dari pivot.
- UI pendaftaran kursus tidak lagi meminta level.
- Penentuan level peserta dilakukan setelah tes/seleksi.

## Status
- Seeder, model, dan tampilan pendaftaran peserta sudah disesuaikan.
- Selanjutnya: sesuaikan form pendaftaran, detail kursus, dan fitur lain yang terkait level/instruktur.

APP_URL=http://localhost:8000
