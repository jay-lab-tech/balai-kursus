import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */

/* Token rupa Balai Kursus — "Balai Hangat".
   Sumber tunggal untuk warna, huruf, dan radius. Nilai yang sama juga
   diterbitkan sebagai custom property di resources/css/app.css supaya bisa
   dipakai dari CSS biasa; ubah di kedua tempat kalau menggeser palet. */
const palette = {
    // Bidang
    canvas: '#fbf8f4',
    surface: '#ffffff',
    // Hijau hutan — sisi kiri, kepala halaman, aksi gelap
    forest: { DEFAULT: '#1f3a34', 600: '#2e5249', 400: '#5a7d72' },
    // Terakota — aksi utama dan hal yang menuntut perhatian
    terra: { DEFAULT: '#c05f3c', 700: '#a94f2f', 900: '#9d452a', soft: '#fbeee8' },
    // Sage — keadaan tenang, kemajuan, label netral positif
    sage: { DEFAULT: '#7a9082', 700: '#3b5c4f', soft: '#eaf1ec' },
    // Pasir — garis dan latar sekunder
    sand: { DEFAULT: '#e9e0d3', 100: '#f3ece2' },
    // Tinta
    ink: { DEFAULT: '#22312e', 2: '#6b7a75', 3: '#9aa5a0' },
    // Semantik
    amber: { DEFAULT: '#b07d21', 700: '#8a6113', soft: '#fbf2df' },
    danger: { DEFAULT: '#b3402c', 700: '#8f3020', soft: '#fbeae7' },
};

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './Modules/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: palette,

            fontFamily: {
                // Huruf display berkarakter — judul, angka besar, nama kelas
                display: ['Fraunces', 'Georgia', 'serif'],
                // Huruf kerja — badan teks, label, tombol
                sans: ['Karla', 'system-ui', 'sans-serif'],
                // Hanya untuk kode dan pengenal (K-0241, REG-20260731-A41C)
                mono: ['"IBM Plex Mono"', 'ui-monospace', 'monospace'],
            },

            fontSize: {
                // Skala kecil yang dipakai berulang di label dan tabel
                '2xs': ['0.72rem', { lineHeight: '1.4' }],
                xs: ['0.775rem', { lineHeight: '1.45' }],
                sm: ['0.84rem', { lineHeight: '1.5' }],
                base: ['0.9375rem', { lineHeight: '1.6' }],
            },

            borderRadius: {
                // Dua radius saja — panel dan elemen di dalamnya
                panel: '14px',
                field: '9px',
                pill: '99px',
            },

            boxShadow: {
                // Bayangan sangat tipis; struktur dibangun dari garis dan bidang
                panel: '0 1px 2px rgba(34, 49, 46, 0.04)',
                lift: '0 6px 18px rgba(34, 49, 46, 0.08)',
            },

            maxWidth: {
                prose: '48ch',
            },
        },
    },

    // Tetap dipakai supaya kontrol form tanpa kelas .bk-* di view yang belum
    // ditulis ulang tidak jatuh ke tampilan bawaan peramban.
    plugins: [forms],
};
