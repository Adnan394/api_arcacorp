README.md - Laravel API (Backend)

Reimbursement API - Laravel Backend

Sebuah API backend menggunakan Laravel yang menyediakan fitur pengajuan reimbursement, manajemen kategori, autentikasi berbasis Sanctum, upload file, dan pengiriman email menggunakan Laravel Queue & SMTP (Gmail/Mailtrap).

📌 Arsitektur Solusi
[Client (Next.js)]
(HTTP Request dengan Bearer Token Sanctum + FormData)
     
[API Laravel]
Auth Sanctum
Validasi & Simpan Data
Upload file (via multipart/form-data)
Simpan ke database
Trigger Mail Queue (antrian email notifikasi)
[Database & Email Queue System]

🎨 Penjelasan Desain

🔐 Autentikasi

Menggunakan Laravel Sanctum untuk autentikasi berbasis token Bearer.

FE mengirim token ke endpoint API Laravel dalam header Authorization: Bearer {token}.

💵 Perhitungan Remunerasi

Pengajuan disimpan dengan nominal (amount) dan status (pending, approved, rejected).

Data disiapkan agar mendukung perhitungan total reimbursement pengguna per kategori dan waktu di kemudian hari.

📩 Email Notification

Laravel menggunakan Queue (database) untuk proses pengiriman email.

Email dikirim saat pengajuan berhasil dilakukan.

⚙️ Setup & Deploy

1. Clone Repo

2. Instalasi

composer install
cp .env.example .env
php artisan key:generate

3. Konfigurasi .env
bisa dilihat pada .env-example

4. Migrasi & Queue

php artisan migrate
php artisan migrate:fresh --seed
php artisan queue:table
php artisan queue:work

5. Jalankan Server

php artisan serve

🚧 Tantangan & Solusi

Tantangan

Solusi

Autentikasi token Sanctum

FE harus kirim Authorization: Bearer {token} secara eksplisit

Kirim file dari Next.js

Gunakan FormData di FE dan Request::file() di Laravel

Gagal login SMTP

Gunakan App Password, bukan password biasa

Proses email berat

Gunakan Laravel Queue agar proses asinkron

✅ Fitur

Auth: Login, Register (Sanctum)
CRUD user
CRUD role
CRUD category
CRUD Pengajuan

Upload file

Email Notification (queued)

Relasi tabel

Filter by user via $request->user()->id
