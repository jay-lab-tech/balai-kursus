<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pesan Autentikasi
    |--------------------------------------------------------------------------
    |
    | Baris berikut dipakai saat proses masuk gagal. Sengaja tidak menyebut
    | ruas mana yang salah supaya tidak membocorkan email mana yang terdaftar.
    |
    */

    'failed' => 'Email atau kata sandi yang Anda masukkan tidak cocok.',
    'password' => 'Kata sandi yang Anda masukkan salah.',
    'throttle' => 'Terlalu banyak percobaan masuk. Silakan coba lagi dalam :seconds detik.',

    // Muncul bila perangkat belum pernah dipercaya, sehingga masuk tanpa
    // mengisi kata sandi tidak diizinkan.
    'password_required' => 'Kata sandi wajib diisi untuk masuk dari perangkat ini.',

];
