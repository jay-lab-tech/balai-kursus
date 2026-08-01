<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pesan Validasi
    |--------------------------------------------------------------------------
    |
    | Baris berikut adalah pesan galat bawaan validator. Beberapa aturan punya
    | beberapa versi — misalnya aturan ukuran — karena kalimatnya berbeda untuk
    | angka, teks, berkas, dan larik.
    |
    | Penerjemahan sengaja memakai sapaan langsung ("Ruas :attribute wajib
    | diisi.") agar sejalan dengan nada antarmuka yang lain.
    |
    */

    'accepted' => 'Ruas :attribute harus disetujui.',
    'accepted_if' => 'Ruas :attribute harus disetujui bila :other bernilai :value.',
    'active_url' => 'Ruas :attribute harus berupa URL yang sah.',
    'after' => 'Ruas :attribute harus berisi tanggal setelah :date.',
    'after_or_equal' => 'Ruas :attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha' => 'Ruas :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Ruas :attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => 'Ruas :attribute hanya boleh berisi huruf dan angka.',
    'array' => 'Ruas :attribute harus berupa larik.',
    'ascii' => 'Ruas :attribute hanya boleh berisi huruf, angka, dan simbol satu bita.',
    'before' => 'Ruas :attribute harus berisi tanggal sebelum :date.',
    'before_or_equal' => 'Ruas :attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => 'Ruas :attribute harus berisi antara :min sampai :max item.',
        'file' => 'Ukuran berkas :attribute harus antara :min sampai :max kilobita.',
        'numeric' => 'Ruas :attribute harus bernilai antara :min sampai :max.',
        'string' => 'Ruas :attribute harus terdiri dari :min sampai :max karakter.',
    ],
    'boolean' => 'Ruas :attribute harus bernilai benar atau salah.',
    'can' => 'Ruas :attribute berisi nilai yang tidak diizinkan.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Kata sandi yang Anda masukkan salah.',
    'date' => 'Ruas :attribute harus berisi tanggal yang sah.',
    'date_equals' => 'Ruas :attribute harus berisi tanggal yang sama dengan :date.',
    'date_format' => 'Ruas :attribute harus sesuai format :format.',
    'decimal' => 'Ruas :attribute harus memiliki :decimal angka di belakang koma.',
    'declined' => 'Ruas :attribute harus ditolak.',
    'declined_if' => 'Ruas :attribute harus ditolak bila :other bernilai :value.',
    'different' => 'Ruas :attribute dan :other harus berbeda.',
    'digits' => 'Ruas :attribute harus terdiri dari :digits angka.',
    'digits_between' => 'Ruas :attribute harus terdiri dari :min sampai :max angka.',
    'dimensions' => 'Dimensi gambar pada ruas :attribute tidak sesuai.',
    'distinct' => 'Ruas :attribute berisi nilai yang terduplikasi.',
    'doesnt_end_with' => 'Ruas :attribute tidak boleh diakhiri salah satu dari: :values.',
    'doesnt_start_with' => 'Ruas :attribute tidak boleh diawali salah satu dari: :values.',
    'email' => 'Ruas :attribute harus berupa alamat email yang sah.',
    'ends_with' => 'Ruas :attribute harus diakhiri salah satu dari: :values.',
    'enum' => 'Pilihan :attribute tidak sah.',
    'exists' => 'Pilihan :attribute tidak sah.',
    'extensions' => 'Ruas :attribute harus berekstensi salah satu dari: :values.',
    'file' => 'Ruas :attribute harus berupa berkas.',
    'filled' => 'Ruas :attribute wajib diisi.',
    'gt' => [
        'array' => 'Ruas :attribute harus berisi lebih dari :value item.',
        'file' => 'Ukuran berkas :attribute harus lebih besar dari :value kilobita.',
        'numeric' => 'Ruas :attribute harus bernilai lebih besar dari :value.',
        'string' => 'Ruas :attribute harus terdiri lebih dari :value karakter.',
    ],
    'gte' => [
        'array' => 'Ruas :attribute harus berisi :value item atau lebih.',
        'file' => 'Ukuran berkas :attribute harus :value kilobita atau lebih.',
        'numeric' => 'Ruas :attribute harus bernilai :value atau lebih.',
        'string' => 'Ruas :attribute harus terdiri dari :value karakter atau lebih.',
    ],
    'hex_color' => 'Ruas :attribute harus berupa kode warna heksadesimal yang sah.',
    'image' => 'Ruas :attribute harus berupa gambar.',
    'in' => 'Pilihan :attribute tidak sah.',
    'in_array' => 'Ruas :attribute harus ada di dalam :other.',
    'integer' => 'Ruas :attribute harus berupa bilangan bulat.',
    'ip' => 'Ruas :attribute harus berupa alamat IP yang sah.',
    'ipv4' => 'Ruas :attribute harus berupa alamat IPv4 yang sah.',
    'ipv6' => 'Ruas :attribute harus berupa alamat IPv6 yang sah.',
    'json' => 'Ruas :attribute harus berupa teks JSON yang sah.',
    'lowercase' => 'Ruas :attribute harus ditulis dengan huruf kecil.',
    'lt' => [
        'array' => 'Ruas :attribute harus berisi kurang dari :value item.',
        'file' => 'Ukuran berkas :attribute harus lebih kecil dari :value kilobita.',
        'numeric' => 'Ruas :attribute harus bernilai lebih kecil dari :value.',
        'string' => 'Ruas :attribute harus terdiri kurang dari :value karakter.',
    ],
    'lte' => [
        'array' => 'Ruas :attribute tidak boleh berisi lebih dari :value item.',
        'file' => 'Ukuran berkas :attribute tidak boleh lebih dari :value kilobita.',
        'numeric' => 'Ruas :attribute tidak boleh bernilai lebih dari :value.',
        'string' => 'Ruas :attribute tidak boleh lebih dari :value karakter.',
    ],
    'mac_address' => 'Ruas :attribute harus berupa alamat MAC yang sah.',
    'max' => [
        'array' => 'Ruas :attribute tidak boleh berisi lebih dari :max item.',
        'file' => 'Ukuran berkas :attribute tidak boleh lebih dari :max kilobita.',
        'numeric' => 'Ruas :attribute tidak boleh bernilai lebih dari :max.',
        'string' => 'Ruas :attribute tidak boleh lebih dari :max karakter.',
    ],
    'max_digits' => 'Ruas :attribute tidak boleh terdiri lebih dari :max angka.',
    'mimes' => 'Ruas :attribute harus berupa berkas bertipe: :values.',
    'mimetypes' => 'Ruas :attribute harus berupa berkas bertipe: :values.',
    'min' => [
        'array' => 'Ruas :attribute harus berisi sekurangnya :min item.',
        'file' => 'Ukuran berkas :attribute sekurangnya :min kilobita.',
        'numeric' => 'Ruas :attribute sekurangnya bernilai :min.',
        'string' => 'Ruas :attribute sekurangnya terdiri dari :min karakter.',
    ],
    'min_digits' => 'Ruas :attribute sekurangnya terdiri dari :min angka.',
    'missing' => 'Ruas :attribute tidak boleh ada.',
    'missing_if' => 'Ruas :attribute tidak boleh ada bila :other bernilai :value.',
    'missing_unless' => 'Ruas :attribute tidak boleh ada kecuali :other bernilai :value.',
    'missing_with' => 'Ruas :attribute tidak boleh ada bila :values terisi.',
    'missing_with_all' => 'Ruas :attribute tidak boleh ada bila :values semuanya terisi.',
    'multiple_of' => 'Ruas :attribute harus merupakan kelipatan dari :value.',
    'not_in' => 'Pilihan :attribute tidak sah.',
    'not_regex' => 'Format ruas :attribute tidak sesuai.',
    'numeric' => 'Ruas :attribute harus berupa angka.',
    'password' => [
        'letters' => 'Ruas :attribute harus memuat sekurangnya satu huruf.',
        'mixed' => 'Ruas :attribute harus memuat sekurangnya satu huruf besar dan satu huruf kecil.',
        'numbers' => 'Ruas :attribute harus memuat sekurangnya satu angka.',
        'symbols' => 'Ruas :attribute harus memuat sekurangnya satu simbol.',
        'uncompromised' => 'Ruas :attribute yang Anda masukkan pernah bocor dalam kebocoran data. Silakan pilih yang lain.',
    ],
    'present' => 'Ruas :attribute harus ada.',
    'present_if' => 'Ruas :attribute harus ada bila :other bernilai :value.',
    'present_unless' => 'Ruas :attribute harus ada kecuali :other bernilai :value.',
    'present_with' => 'Ruas :attribute harus ada bila :values terisi.',
    'present_with_all' => 'Ruas :attribute harus ada bila :values semuanya terisi.',
    'prohibited' => 'Ruas :attribute tidak diizinkan.',
    'prohibited_if' => 'Ruas :attribute tidak diizinkan bila :other bernilai :value.',
    'prohibited_unless' => 'Ruas :attribute tidak diizinkan kecuali :other bernilai salah satu dari :values.',
    'prohibits' => 'Ruas :attribute membuat :other tidak boleh diisi.',
    'regex' => 'Format ruas :attribute tidak sesuai.',
    'required' => 'Ruas :attribute wajib diisi.',
    'required_array_keys' => 'Ruas :attribute harus memuat entri untuk: :values.',
    'required_if' => 'Ruas :attribute wajib diisi bila :other bernilai :value.',
    'required_if_accepted' => 'Ruas :attribute wajib diisi bila :other disetujui.',
    'required_unless' => 'Ruas :attribute wajib diisi kecuali :other bernilai salah satu dari :values.',
    'required_with' => 'Ruas :attribute wajib diisi bila :values terisi.',
    'required_with_all' => 'Ruas :attribute wajib diisi bila :values semuanya terisi.',
    'required_without' => 'Ruas :attribute wajib diisi bila :values tidak terisi.',
    'required_without_all' => 'Ruas :attribute wajib diisi bila tidak satu pun dari :values terisi.',
    'same' => 'Ruas :attribute harus sama dengan :other.',
    'size' => [
        'array' => 'Ruas :attribute harus berisi :size item.',
        'file' => 'Ukuran berkas :attribute harus :size kilobita.',
        'numeric' => 'Ruas :attribute harus bernilai :size.',
        'string' => 'Ruas :attribute harus terdiri dari :size karakter.',
    ],
    'starts_with' => 'Ruas :attribute harus diawali salah satu dari: :values.',
    'string' => 'Ruas :attribute harus berupa teks.',
    'timezone' => 'Ruas :attribute harus berupa zona waktu yang sah.',
    'unique' => ':attribute sudah digunakan.',
    'uploaded' => 'Ruas :attribute gagal diunggah.',
    'uppercase' => 'Ruas :attribute harus ditulis dengan huruf besar.',
    'url' => 'Ruas :attribute harus berupa URL yang sah.',
    'ulid' => 'Ruas :attribute harus berupa ULID yang sah.',
    'uuid' => 'Ruas :attribute harus berupa UUID yang sah.',

    /*
    |--------------------------------------------------------------------------
    | Pesan Validasi Khusus
    |--------------------------------------------------------------------------
    |
    | Di sini pesan bisa ditimpa untuk pasangan ruas dan aturan tertentu dengan
    | pola "nama-ruas.nama-aturan", ketika kalimat umum di atas terasa kurang
    | jelas bagi pengguna.
    |
    */

    'custom' => [
        'password' => [
            'confirmed' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi baru.',
        ],
        'terbayar' => [
            'lte' => 'Jumlah yang dibayar tidak boleh melebihi total tagihan.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Nama Ruas
    |--------------------------------------------------------------------------
    |
    | Peta berikut mengganti nama kolom basis data dengan istilah yang dipakai
    | di layar, supaya pesan galat terbaca seperti kalimat biasa: "Ruas tanggal
    | mulai wajib diisi", bukan "Ruas tanggal_mulai wajib diisi".
    |
    */

    'attributes' => [
        // Akun dan profil
        'email' => 'alamat email',
        'password' => 'kata sandi',
        'password_confirmation' => 'konfirmasi kata sandi',
        'current_password' => 'kata sandi saat ini',
        'username' => 'nama pengguna',
        'name' => 'nama',
        'role' => 'peran',
        'no_telp' => 'nomor telepon',
        'alamat' => 'alamat',
        'provinsi' => 'provinsi',
        'kota' => 'kota',
        'foto' => 'foto',

        // Program, level, kursus
        'nama' => 'nama',
        'nama_program' => 'nama program',
        'program_id' => 'program',
        'level_id' => 'level',
        'kursus_id' => 'kursus',
        'course_id' => 'kursus',
        'keterangan' => 'keterangan',
        'deskripsi' => 'deskripsi',
        'urutan' => 'urutan',
        'warna' => 'warna',
        'periode' => 'periode',
        'kuota' => 'kuota',
        'kapasitas' => 'kapasitas',
        'harga' => 'harga',
        'harga_upi' => 'harga UPI',
        'fasilitas' => 'fasilitas',
        'nilai_min' => 'nilai minimum',
        'nilai_max' => 'nilai maksimum',
        'is_active' => 'status aktif',
        'status' => 'status',

        // Jadwal dan pertemuan
        'tanggal_mulai' => 'tanggal mulai',
        'tanggal_selesai' => 'tanggal selesai',
        'tgl_pertemuan' => 'tanggal pertemuan',
        'jam_mulai' => 'jam mulai',
        'jam_selesai' => 'jam selesai',
        'hari_id' => 'hari',
        'lokasi_id' => 'lokasi',
        'kelas_id' => 'ruang kelas',

        // Instruktur dan penilaian
        'instruktur_id' => 'instruktur',
        'nama_instr' => 'nama instruktur',
        'spesialisasi' => 'spesialisasi',
        'peserta' => 'peserta',
        'peserta_id' => 'peserta',
        'listening' => 'listening',
        'reading' => 'reading',
        'writing' => 'writing',
        'speaking' => 'speaking',
        'assignment' => 'tugas',
        'ukap' => 'UKAP',
        'uktp' => 'UKTP',
        'final_score' => 'nilai akhir',
        'evaluated_at' => 'tanggal penilaian',
        'evaluated_by' => 'penilai',

        // Pembayaran dan sertifikat
        'amount' => 'jumlah',
        'total_bayar' => 'total tagihan',
        'terbayar' => 'jumlah dibayar',
        'metode' => 'metode pembayaran',
        'issued_date' => 'tanggal terbit',
        'certificate_number' => 'nomor sertifikat',
        'serial_number' => 'nomor seri',
    ],

];
