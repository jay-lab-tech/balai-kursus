import { chromium } from 'playwright';
import fs from 'fs';

const BASE = 'http://127.0.0.1:8000';
const OUT = 'docs/screenshots';
fs.mkdirSync(OUT, { recursive: true });

const slug = s => s.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');

// Daftar gambar: [caption, role, path]. role: 'guest'|'admin'|'instruktur'|'peserta'
const FIGS = [
  ['Tampilan Papan Informasi', 'guest', '/papan-informasi'],
  ['Tampilan Halaman Login', 'guest', '/login'],
  ['Tampilan Halaman Registrasi', 'guest', '/register'],
  ['Tampilan Halaman Lupa Kata Sandi', 'guest', '/forgot-password'],
  ['Tampilan Verifikasi Sertifikat', 'guest', '/verify/001'],

  ['Tampilan Dashboard Admin', 'admin', '/admin/dashboard'],
  ['Tampilan Manajemen Program', 'admin', '/admin/program'],
  ['Tampilan Manajemen Level', 'admin', '/admin/level'],
  ['Tampilan Manajemen Kursus', 'admin', '/admin/kursus'],
  ['Tampilan Detail Peserta Kursus', 'admin', '/admin/kursus/1/peserta'],
  ['Tampilan Manajemen Jadwal', 'admin', '/admin/jadwal'],
  ['Tampilan Daftar Risalah', 'admin', '/admin/risalah'],
  ['Tampilan Rekap Absensi', 'admin', '/admin/absensi'],
  ['Tampilan Manajemen Nilai', 'admin', '/admin/score'],
  ['Tampilan Manajemen Lokasi', 'admin', '/admin/lokasi'],
  ['Tampilan Manajemen Ruang Kelas', 'admin', '/admin/kelas'],
  ['Tampilan Manajemen Instruktur', 'admin', '/admin/instruktur'],
  ['Tampilan Manajemen Peserta', 'admin', '/admin/peserta'],
  ['Tampilan Manajemen Sertifikat', 'admin', '/admin/certificates'],
  ['Tampilan Manajemen Template Sertifikat', 'admin', '/admin/certificate-templates'],

  ['Tampilan Dashboard Instruktur', 'instruktur', '/instruktur/dashboard'],
  ['Tampilan Daftar Kursus Instruktur', 'instruktur', '/instruktur/kursus'],
  ['Tampilan Pengelolaan Risalah', 'instruktur', '/instruktur/kursus/1/risalah'],
  ['Tampilan Pencatatan Absensi', 'instruktur', '/instruktur/risalah/1/absensi'],
  ['Tampilan Penilaian Peserta', 'instruktur', '/instruktur/kursus/1/nilai'],
  ['Tampilan Jadwal Instruktur', 'instruktur', '/instruktur/jadwal'],

  ['Tampilan Dashboard Peserta', 'peserta', '/peserta/dashboard'],
  ['Tampilan Daftar Program', 'peserta', '/peserta/program'],
  ['Tampilan Detail dan Pendaftaran Program', 'peserta', '/peserta/program/1'],
  ['Tampilan Daftar Kursus', 'peserta', '/peserta/kursus'],
  ['Tampilan Kursus Saya', 'peserta', '/peserta/kursus/saya'],
  // Id kelas ikut berubah setiap kali data di-seed ulang, jadi alamatnya
  // diambil dari halaman "Kursus Saya" saat capture berjalan.
  ['Tampilan Detail Kursus dan Risalah', 'peserta', { resolveFrom: '/peserta/kursus/saya', linkPattern: '/detail' }],
  ['Tampilan Daftar Pendaftaran Peserta', 'peserta', '/peserta/pendaftaran'],
  ['Tampilan Riwayat Pembayaran', 'peserta', '/peserta/riwayat-pembayaran'],
  ['Tampilan Profil Peserta', 'peserta', '/profile'],
  ['Tampilan Daftar dan Unduh Sertifikat Peserta', 'peserta', '/profile/certificates'],
];

const CRED = {
  admin: 'admin@balai.test',
  instruktur: 'instruktur1@balai.test',
  peserta: 'peserta1@balai.test',
};

async function login(page, email) {
  await page.goto(BASE + '/login', { waitUntil: 'domcontentloaded', timeout: 45000 });
  await page.fill('#email', email);
  await page.fill('#password', 'password');
  await page.click('button[type="submit"]');
  try { await page.waitForURL(u => !u.toString().includes('/login'), { timeout: 30000 }); }
  catch (e) { /* lanjut saja */ }
  await page.waitForTimeout(1000);
}

// Cari alamat tujuan dari sebuah halaman daftar, supaya id yang berubah-ubah
// setiap kali data di-seed ulang tidak perlu ditulis manual.
async function resolvePath(page, { resolveFrom, linkPattern }) {
  try {
    await page.goto(BASE + resolveFrom, { waitUntil: 'load', timeout: 45000 });
    await page.waitForTimeout(800);
    const href = await page.evaluate(
      pattern => {
        const a = [...document.querySelectorAll('a[href]')].find(x => x.getAttribute('href').includes(pattern));
        return a ? a.getAttribute('href') : null;
      },
      linkPattern
    );
    if (!href) return null;
    return href.startsWith('http') ? href.replace(BASE, '') : href;
  } catch (e) {
    return null;
  }
}

const manifest = {};
const report = [];

const browser = await chromium.launch();

async function captureRole(role, figs) {
  const ctx = await browser.newContext({
    viewport: { width: 1366, height: 900 },
    deviceScaleFactor: 1.25,
  });
  const page = await ctx.newPage();
  if (role !== 'guest') {
    try { await login(page, CRED[role]); }
    catch (e) { report.push(`[LOGIN FAIL ${role}] ${e.message.split('\n')[0]}`); }
  }

  for (const [caption, , target] of figs) {
    const file = `${OUT}/${slug(caption)}.png`;

    let path = target;
    if (typeof target === 'object') {
      path = await resolvePath(page, target);
      if (!path) { report.push(`[FAIL] ${caption}  <- tautan ${target.linkPattern} tidak ditemukan di ${target.resolveFrom}`); continue; }
    }

    let status = 0, ok = false, err = '';
    for (let attempt = 1; attempt <= 3 && !ok; attempt++) {
      try {
        const resp = await page.goto(BASE + path, { waitUntil: 'load', timeout: 45000 });
        await page.waitForTimeout(1300);
        status = resp ? resp.status() : 0;
        ok = true;
      } catch (e) {
        err = e.message.split('\n')[0];
        await page.waitForTimeout(1000);
      }
    }
    if (!ok) { report.push(`[FAIL] ${caption}  <- ${path}  (${err})`); continue; }
    if (status >= 400) { report.push(`[SKIP ${status}] ${caption}  <- ${path} (halaman error, biarkan placeholder)`); continue; }
    await page.screenshot({ path: file, fullPage: true });
    manifest[caption] = file;
    report.push(`[OK ${status}] ${caption}  <- ${path}`);
  }
  await ctx.close();
}

for (const role of ['guest', 'admin', 'instruktur', 'peserta']) {
  await captureRole(role, FIGS.filter(f => f[1] === role));
}

await browser.close();
fs.writeFileSync(`${OUT}/manifest.json`, JSON.stringify(manifest, null, 2));
console.log(report.join('\n'));
console.log(`\nTotal tertangkap: ${Object.keys(manifest).length}/${FIGS.length}`);
