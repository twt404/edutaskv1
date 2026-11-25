# EduTask — Sistem E-Learning

EduTask adalah aplikasi **e-learning** berbasis **CodeIgniter 3** untuk mengelola pembelajaran di sekolah: kelas, mata pelajaran, materi, tugas (individu & kelompok), pengumpulan, penilaian, hingga notifikasi. Aplikasi mendukung tiga peran pengguna: **Admin**, **Guru**, dan **Siswa**.

![PHP](https://img.shields.io/badge/PHP-7.x-777BB4?logo=php&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3.x-EF4223?logo=codeigniter&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL%2FMariaDB-utf8mb4-4479A1?logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Tech Stack](#tech-stack)
- [Hak Akses & Arsitektur](#hak-akses--arsitektur)
- [Skema Database](#skema-database)
- [Struktur Folder](#struktur-folder)
- [Instalasi](#instalasi)
- [Akun Default](#akun-default)
- [Cara Menjalankan](#cara-menjalankan)
- [Fitur Sinkronisasi 1-Klik](#fitur-sinkronisasi-1-klik)
- [Keamanan](#keamanan)
- [Lisensi](#lisensi)
- [Screenshot](#screenshot)

---

## Fitur Utama

| Peran | Fitur |
| :--- | :--- |
| **Auth** | Login multi-peran, registrasi siswa, logout. |
| **Admin** | Dashboard statistik, manajemen pengguna (tambah/edit/hapus, batch, hapus paksa), manajemen kelas, manajemen mapel, penetapan mapel ke kelas, laporan (rekap nilai & pengumpulan), profil, **sinkronisasi 1-klik**. |
| **Guru** | Dashboard, kelola mapel, kelola materi (upload file), kelola tugas, penilaian & feedback pengumpulan, pengelolaan kelompok, notifikasi, profil. |
| **Siswa** | Dashboard, kelas saya, gabung kelas via kode, daftar mapel, materi, tugas & pengumpulan (individu/kelompok + revisi), notifikasi, profil. |

### Detail Tugas

- Jenis tugas: **individu** dan **kelompok**.
- Mode pembentukan kelompok: **acak**, **ditentukan guru**, atau **dipilih siswa**.
- Mendukung **deadline**, **revisi** (`allow_revisi`), **wajib kontribusi** anggota, dan **lock kelompok**.
- Status tugas: **draft**, **published**, **scheduled** (`publish_at`).
- Status pengumpulan: `menunggu`, `dinilai`, `terlambat`.

---

## Tech Stack

- **Backend:** PHP, CodeIgniter 3
- **Database:** MySQL / MariaDB (charset `utf8mb4`)
- **Frontend:** Tailwind CSS (via CDN), FontAwesome
- **Font:** Inter & Plus Jakarta Sans (self-hosted di `assets/fonts`)
- **Library:** sesi (`session`), `form_validation`, `upload`, `pagination`, `dbutil`

---

## Hak Akses & Arsitektur

Kontrol akses berbasis peran diterapkan melalui `application/core/MY_Controller.php`:

- `Admin_Controller` — hanya `role = admin`
- `Guru_Controller` — hanya `role = guru`
- `Siswa_Controller` — hanya `role = siswa`

Setiap controller peran (`Admin`, `Guru`, `Siswa`) mewarisi base controller terkait dan otomatis mengarahkan ke halaman login bila sesi tidak valid atau peran tidak sesuai.

---

## Skema Database

Database default: **`elearning`** (file `elearning.sql`).

| Tabel | Deskripsi |
| :--- | :--- |
| `users` | Data pengguna (admin/guru/siswa) + foto, tingkat, jurusan. |
| `kelas` | Rombongan belajar (contoh: XI PPLG 1) dengan `kode_kelas` unik. |
| `kelas_siswa` | Keanggotaan siswa pada kelas (satu siswa satu kelas). |
| `mapel` | Mata pelajaran dalam kelas, diampu guru. |
| `mapel_siswa` | Enrollment siswa pada mapel (mencerminkan `kelas_siswa`). |
| `materi` | Materi pembelajaran per mapel (judul, deskripsi, file, urutan). |
| `tugas` | Tugas individu/kelompok, deadline, revisi, status publish. |
| `kelompok` | Kelompok untuk tugas bertipe kelompok. |
| `kelompok_anggota` | Anggota kelompok. |
| `pengumpulan_tugas` | Jawaban siswa/kelompok, nilai, feedback, status. |
| `notifikasi` | Notifikasi pengguna. |

Relasi inti: `kelas` → `mapel` → `tugas` → `pengumpulan_tugas`, dengan `users` sebagai guru/siswa.

> Terdapat beberapa file migrasi opsional di root (mis. `elearning_rombel_update.sql`, `elearning_notifikasi_guru.sql`, `elearning_tugas_items_update.sql`, `elearning_revisi_update.sql`, dll.) untuk pembaruan skema/fitur tertentu.

---

## Struktur Folder

```
elearningci3/
├── application/
│   ├── config/          # Konfigurasi (database.php, config.php, routes.php)
│   ├── controllers/     # Admin, Auth, Guru, Siswa, SyncApi
│   ├── core/            # MY_Controller + guard peran
│   ├── libraries/       # SyncManager (fitur sinkronisasi)
│   ├── models/          # User, Kelas, Mapel, Materi, Tugas, Kelompok, Rombel, Notifikasi, Siswa
│   └── views/           # admin/, guru/, siswa/, auth/
├── assets/              # CSS, fonts, vendor (FontAwesome)
├── uploads/             # File upload (foto, materi, tugas, sync_backups)
├── system/              # Core CodeIgniter 3
├── elearning.sql        # Skema + seed data
├── index.php            # Front controller
└── README.md
```

---

## Instalasi

### Requirement

- PHP **7.x** (atau versi yang didukung CodeIgniter 3)
- Ekstensi PHP: **`mysqli`**, **`zip`**, **`curl`**
- MySQL / MariaDB
- Web server: Laragon / XAMPP / Apache, atau `php -S`

### Langkah

1. **Clone repository** ke web root (mis. `C:\laragon6\www\elearningci3`).

2. **Import database**:
   ```bash
   mysql -u root -p < elearning.sql
   ```
   atau import `elearning.sql` via phpMyAdmin. File ini otomatis membuat database `elearning` beserta seed data.

3. **Konfigurasi database** di `application/config/database.php`:
   ```php
   'hostname' => '127.0.0.1',
   'username' => 'root',
   'password' => '',
   'database' => 'elearning',
   'dbdriver' => 'mysqli',
   ```

4. **Konfigurasi aplikasi** di `application/config/config.php`:
   - `base_url` — sesuaikan dengan domain.
   - `index_page` — `'index.php'` bila tanpa mod_rewrite, atau kosongkan `''` bila memakai `.htaccess`.

5. **Permission folder** `uploads/` (dan `uploads/sync_backups/`) agar *writable* (mis. `0777`/`0755`).

---

## Akun Default

Tersedia dari seed `elearning.sql`:

| Peran | Email | Password |
| :--- | :--- | :--- |
| Admin | `admin@edutask.id` | `admin123` |
| Guru | `guru@edutask.id` | `guru123` |
| Guru | `guru2@edutask.id` | `guru123` |
| Siswa | `siswa1@edutask.id` s/d `siswa5@edutask.id` | `siswa123` |

> **Penting:** segera ganti password default setelah instalasi.

---

## Cara Menjalankan

**Dengan Laragon / XAMPP (Apache):**
- Letakkan proyek di web root lalu akses `http://localhost/elearningci3/`.

**Dengan built-in server PHP:**
```bash
php -S localhost:8000
```
Lalu buka `http://localhost:8000/`. Bila routing tidak memakai `.htaccess`, gunakan URL dengan `index.php`, mis. `http://localhost:8000/index.php/auth/login`.

---

## Fitur Sinkronisasi 1-Klik

Sinkronisasi **dua arah** antara server **Hosting (remote)** dan **Lokal**, mencakup **database SQL** dan **folder `uploads/`** secara otomatis.

- **PULL** (Hosting → Lokal): menarik database + assets dari hosting ke lokal.
- **PUSH** (Lokal → Hosting): mengirim database + assets dari lokal ke hosting.
- **Riwayat:** status & waktu sinkronisasi terakhir ditampilkan di halaman admin.

### File Terkait

| File | Fungsi |
| :--- | :--- |
| `application/controllers/SyncApi.php` | Endpoint remote: `export`, `import` (push), `download`. |
| `application/libraries/SyncManager.php` | Export ZIP, import/restore DB, `pullFromRemote`, `pushToRemote`, log. |
| `application/controllers/Admin.php` (`sync()`) | Memproses aksi PULL/PUSH dari UI. |
| `application/views/admin/sync/index.php` | UI form PULL/PUSH + panel riwayat. |
| `uploads/sync_backups/` | Penyimpanan paket `.zip` sementara & `sync_info.json`. |

### Konfigurasi

Atur **Secret Key** pada `application/controllers/SyncApi.php` (sama persis di lokal & hosting):

```php
private $secretKey = 'SECRET_SYNC_KEY_12345'; // Ganti dengan key unik Anda
```

### Catatan

- Proyek **belum menyertakan `.htaccess`**. Bila tanpa mod_rewrite, akses endpoint memakai `index.php` (mis. `https://domain/index.php/syncApi/export`).
- Yang disinkronkan adalah **database** dan **isi `uploads/`**, **bukan** source code aplikasi.

---

## Keamanan

- Ganti `$secretKey` pada `SyncApi.php` sebelum digunakan di produksi.
- Jangan commit kredensial database atau secret ke repository publik.
- Batasi akses endpoint sinkronisasi (IP allowlist / HTTPS).
- Password saat ini disimpan dengan **MD5** (bawaan seed). Untuk produksi, pertimbangkan migrasi ke `password_hash()`.

---

## Lisensi

Proyek ini dirilis di bawah lisensi **MIT** (mengikuti kerangka CodeIgniter 3).

---

## Screenshot

> Tambahkan screenshot Anda di sini setelah aplikasi berjalan.

| Halaman | Preview |
| :--- | :--- |
| Login | _placeholder_ |
| Dashboard Admin | _placeholder_ |
| Dashboard Guru | _placeholder_ |
| Dashboard Siswa | _placeholder_ |
| Sinkronisasi | _placeholder_ |
