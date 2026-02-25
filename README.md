---

# 🏛️ SI-KGB Kendari (Sistem Informasi Kenaikan Gaji Berkala)

SI-KGB Kendari adalah sistem informasi berbasis web yang dikembangkan khusus untuk mengelola data kepegawaian, memonitoring jadwal **Kenaikan Gaji Berkala (KGB)**, **Kenaikan Pangkat**, serta **Batas Usia Pensiun (BUP)** bagi pegawai (PNS, PPPK, PPPK Paruh Waktu, dan Honorer).

Aplikasi ini juga dilengkapi dengan fitur **Arsip Digital Surat Keputusan (SK)**.

---

## ✨ Fitur Utama

* 📊 **Dashboard Pintar**
  Menampilkan statistik pegawai dan peringatan otomatis (H-60) untuk jadwal Kenaikan Pangkat, Gaji Berkala, dan masa Persiapan Pensiun (1 Tahun sebelum BUP).

* 👥 **Manajemen Pegawai**
  Pengelolaan biodata lengkap pegawai dengan validasi khusus berdasarkan status kepegawaian, lengkap dengan foto profil.

* 📂 **Arsip Digital SK**
  Upload, simpan, dan unduh dokumen SK pegawai dalam format PDF/Gambar.
  Dilengkapi fitur **Auto-Update Pangkat/Golongan** ke master data pegawai.

* 📥 **Export Laporan Excel**
  Unduh rekapitulasi pegawai dalam format `.xls` yang dilengkapi dengan **Smart Color-Coding** (warna otomatis pada sel Excel jika pegawai mendekati jadwal naik pangkat/gaji/pensiun).

* 🔍 **Fast Search**
  Pencarian dropdown pegawai secara instan menggunakan library *Tom Select* tanpa membebani server.

---

## 🛠️ Teknologi yang Digunakan

* **Backend:** PHP 8.1 & Laravel Framework (v12.46.0)
* **Database:** MySQL
* **Frontend:** Bootstrap 5 (via CDN), HTML/CSS/Vanilla JS (Tanpa NPM/Node.js)
* **Library Tambahan:** Tom Select (Dropdown Search)

---

## 💻 Persyaratan Sistem (Prerequisites)

Pastikan sistem/komputer Anda telah terinstall:

* PHP >= 8.1
* Composer
* MySQL (XAMPP/Laragon atau sejenisnya)

---

## 🚀 Panduan Instalasi & Konfigurasi

### 1️⃣ Clone Repositori

```bash
git clone https://github.com/RyianDzuhri/si-kepegawaian
cd si-kepegawaian
```

---

### 2️⃣ Install Dependensi PHP

```bash
composer install
```

---

### 3️⃣ Konfigurasi Environment

Gandakan file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_kepegawaian
DB_USERNAME=root
DB_PASSWORD=
```

> Pastikan Anda telah membuat database kosong bernama `db_kepegawaian` sebelum lanjut ke langkah berikutnya.

---

### 4️⃣ Generate Application Key

```bash
php artisan key:generate
```

---

### 5️⃣ Migrasi Database

```bash
php artisan migrate
```

---

### 6️⃣ Link Storage (Untuk Upload Foto & File SK)

```bash
php artisan storage:link
```

---

## 🔐 Pembuatan Akun Akses (Admin)

Aplikasi ini menggunakan **1 level user (Administrator)** dan tidak memakai Database Seeder.

Gunakan Tinker untuk membuat akun pertama:

```bash
php artisan tinker
```

Setelah masuk ke console Tinker (`>`), jalankan:

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::truncate();

User::create([
    'username' => 'admin',
    'password' => Hash::make('admin123')
]);
```

Ketik:

```bash
exit
```

### 🔑 Akun Login

* **Username:** `admin`
* **Password:** `admin123`

---

## 🏃‍♂️ Menjalankan Aplikasi

```bash
php artisan serve
```

Akses melalui browser:

```
http://localhost:8000
```

---


