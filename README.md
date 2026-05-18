# Dashboard HPL

## Deskripsi Singkat Aplikasi

Dashboard HPL adalah aplikasi berbasis web yang digunakan untuk membantu pengelolaan data pertanahan secara terstruktur dan efisien. Sistem ini mendukung pengelolaan data Hak Pengelolaan (HPL), Sertifikat Hak Milik (SHM), serta pendataan permasalahan lahan.

Aplikasi ini menyediakan fitur pengelompokan, pencarian, filtering, upload dokumen, dan monitoring data berdasarkan wilayah maupun status dokumen. Dengan tampilan yang responsif dan mudah digunakan, Dashboard HPL membantu proses administrasi, monitoring, dan pengelolaan data pertanahan menjadi lebih efektif dan terorganisir.

---

## Fitur Utama

### SHM
- Pengelolaan data Sertifikat Hak Milik
- Upload dan manajemen dokumen

### HPL
- Pendataan Hak Pengelolaan Lahan
- Monitoring status lahan
- Rekap data berdasarkan wilayah

### Permasalahan Lahan
- Pendataan sengketa/permasalahan lahan
- Monitoring tindak lanjut
- Pengelolaan dokumen pendukung

---

## Langkah Menjalankan Backend & Frontend

### Backend

Pastikan sudah terinstal:
- PHP ≥ 8.x
- Composer
- MySQL

Masuk ke folder project:

```bash
cd nama-project
```

Install dependency:

```bash
composer install
```

Atur konfigurasi database pada file `.env`

Generate application key:

```bash
php artisan key:generate
```

Jalankan migrasi database:

```bash
php artisan migrate
```

Jalankan server:

```bash
php artisan serve
```

---

### Frontend

Install dependency frontend:

```bash
npm install
```

Jalankan build frontend:

```bash
npm run dev
```

---

## Teknologi yang Digunakan

### Backend
- Laravel

### Frontend
- Blade Template
- HTML
- CSS
- JavaScript

### Database
- MySQL

### CSS Framework
- Tailwind CSS

### Web Server
- Apache

---

## Informasi Login Dummy

|      Email         |  Password  |
|--------------------|------------|
| admin@gmail.com    |   123456   |
| pimpinan@gmail.com |   qwerty   |
