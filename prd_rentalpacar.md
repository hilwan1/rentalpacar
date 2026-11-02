# Product Requirements Document (PRD) — RentalPacar

## 1. Ikhtisar Produk (Product Overview)
* **Nama Proyek:** RentalPacar
* **Deskripsi Singkat:** Platform direktori dan pemesanan layanan teman kencan virtual (*online dating companion*) berbasis web.
* **Tujuan Utama:** Memudahkan *customer* memesan jasa *talent* secara instan tanpa perlu membuat akun (*guest checkout*), di mana pemesanan langsung diarahkan ke WhatsApp Admin menggunakan kode pesanan unik dan tercatat otomatis sebagai daftar pesanan masuk di dashboard Admin.
* **Tech Stack:**
  * **Backend & Framework:** Laravel 12 (PHP 8.2+)
  * **Database:** MySQL
  * **Frontend / UI:** Blade + Tailwind CSS (opsional Livewire/Alpine.js atau Filament)
  * **Integrasi Eksternal:** WhatsApp Click-to-Chat (`wa.me`) untuk pengalihan pesan otomatis ke Admin.

---

## 2. Peran Pengguna & Hak Akses (User Roles & Permissions)

| Role | Akses Autentikasi | Hak Akses Utama |
| :--- | :--- | :--- |
| **Customer (Guest)** | Tanpa Login / Register | Melihat katalog *talent*, detail layanan & tarif, halaman testimoni, dan mengisi form pemesanan. |
| **Talent** | Login & Register | Mengelola profil pribadi, menambah/mengedit jenis layanan & tarif milik sendiri, mengatur status aktif/nonaktif layanan. |
| **Admin** | Hanya Login (Seeder) | Melihat rekap/daftar pesanan yang masuk beserta total jumlahnya, memantau *talent*, menambah/mengedit jasa, dan mengelola halaman testimoni. |

---

## 3. Alur Pengguna Utama (Core User Flows)

### A. Alur Pemesanan Customer (Tanpa Login)
1. **Eksplorasi:** Customer membuka halaman utama, melihat daftar *talent* yang berstatus aktif beserta deskripsi dan tarifnya, serta bisa melihat halaman **Testimoni**.
2. **Pilih Talent & Layanan:** Customer mengklik salah satu profil *talent* dan memilih tipe/jenis tarif yang diinginkan (misal: *Chat Only*, *Voice Call*, *Video Call*, atau *Main Game Bareng*).
3. **Isi Form Pemesanan:** Customer mengisi data singkat pada form pemesanan (Nama/Inisial Customer, Nomor WhatsApp Customer, Tanggal/Waktu Booking, dan Catatan Tambahan).
4. **Simpan Pesanan, Generate Kode & Redirect WA:**
   * Sistem menyimpan data pesanan ke database MySQL sebagai daftar pesanan masuk dan menghasilkan kode unik (contoh: `RP-261101-A8F9`).
   * Sistem langsung mengarahkan (*redirect*) browser customer ke WhatsApp Admin dengan format pesan otomatis yang mencantumkan kode pesanan serta detail pemesanan.

### B. Alur Talent
1. **Registrasi & Login:** Talent mendaftar akun dan masuk ke *dashboard* khusus Talent.
2. **Manajemen Layanan Pribadi:** Talent menambahkan daftar paket jasa/tarif untuk dirinya sendiri beserta deskripsi dan durasinya.
3. **Kontrol Ketersediaan (Toggle Status):** Talent dapat sewaktu-waktu mengedit atau menonaktifkan layanan miliknya jika sedang tidak tersedia.

### C. Alur Admin
1. **Monitoring Pesanan Masuk:** Admin membuka menu **Daftar Pesanan Masuk** untuk melihat seluruh riwayat pesanan yang disubmit customer (tanpa perlu mengubah status pesanan) dan mencocokkannya dengan chat yang masuk di WhatsApp menggunakan kode pesanan (`RP-XXXX`).
2. **Rekap Jumlah Pesanan:** Admin melihat total pesanan masuk (hari ini, minggu ini, bulan ini, atau keseluruhan).
3. **Manajemen Talent, Jasa & Testimoni:** Admin memantau daftar *talent*, dapat menambahkan/mengedit layanan jasa, serta menambahkan testimoni pelanggan ke halaman publik.

---

## 4. Spesifikasi Fitur Fungsional (Functional Requirements)

### 4.1. Halaman Publik (Customer View)
* **Landing Page & Katalog Talent:** Menampilkan *card* berisi foto *talent*, nama panggilan, deskripsi singkat, daftar/harga mulai dari (*starting price*), dan indikator ketersediaan.
* **Halaman Detail Talent & Form Checkout:** Menampilkan deskripsi lengkap *talent*, pilihan jenis tarif/layanan, dan form input pemesanan.
* **Generator Pesan WhatsApp:** Format URL otomatis saat form dikirim:
  `https://wa.me/628xxxxxx?text=Halo%20Admin%20RentalPacar,%20saya%20ingin%20pesan%20dengan%20kode%20*RP-261101-XXXX*...`
* **Halaman Testimoni:** Galeri berisi ulasan pelanggan sebelumnya (nama samaran, nama *talent*, isi testimoni, atau gambar *screenshot* chat).

### 4.2. Panel Talent (Talent Dashboard)
* **Autentikasi:** Halaman Register dan Login khusus Talent.
* **Profil Saya:** Edit nama panggung, foto profil, deskripsi/bio, dan tombol *toggle* aktif/nonaktif profil.
* **Manajemen Jasa & Tarif Pribadi:**
  * Tambah paket layanan milik sendiri (Nama paket, deskripsi, durasi, tarif).
  * Edit rincian dan tarif paket.
  * *Toggle* Aktif / Nonaktif layanan sewaktu-waktu.

### 4.3. Panel Admin (Admin Dashboard)
* **Ringkasan Analitik (Overview):** Menampilkan jumlah total pesanan masuk (harian/bulanan/total), jumlah *talent* terdaftar, dan jumlah layanan aktif.
* **Daftar Pesanan Masuk (Read & Search/Delete Only):**
  * Tabel daftar pesanan yang masuk secara *real-time* tanpa kolom status transaksi.
  * Fitur pencarian berdasarkan `order_code` (`RP-XXXX`), nama *customer*, atau filter berdasarkan *talent* dan rentang tanggal.
  * Opsi hapus data jika ada pesanan *spam*.
* **Manajemen Talent & Jasa:** Melihat semua *talent*, mengaktifkan/menonaktifkan akun *talent*, serta menambah atau mengedit layanan jasa untuk *talent*.
* **Manajemen Testimoni (CRUD):** Tambah, edit, dan hapus testimoni yang tampil di halaman publik.

---

## 5. Rancangan Skema Database (MySQL Schema)

Berikut adalah struktur tabel untuk migrasi di Laravel 12:

### 1. Tabel `users`
Menyimpan akun untuk Admin dan Talent.
* `id` (BigInt, PK)
* `name` (Varchar) — Nama tampilan / nama panggung
* `email` (Varchar, Unique)
* `password` (Varchar)
* `role` (Enum: `'admin'`, `'talent'`)
* `phone` (Varchar, Nullable) — Nomor WA Talent
* `avatar` (Varchar, Nullable) — Foto profil
* `bio` (Text, Nullable) — Deskripsi singkat talent
* `is_active` (Boolean, Default: `true`) — Status ketersediaan talent
* `timestamps`

### 2. Tabel `services` (Jasa & Tarif)
Menyimpan jenis layanan dan tarif milik masing-masing talent.
* `id` (BigInt, PK)
* `talent_id` (ForeignId -> `users.id`, Cascade on Delete)
* `service_name` (Varchar) — Contoh: "Voice Call 1 Jam", "Chat Seharian"
* `description` (Text, Nullable) — Deskripsi layanan
* `duration` (Varchar) — Contoh: "60 Menit", "1 Hari"
* `price` (UnsignedBigInteger) — Tarif dalam Rupiah
* `is_active` (Boolean, Default: `true`) — Status layanan (aktif/nonaktif)
* `timestamps`

### 3. Tabel `orders` (Daftar Pesanan Masuk)
Menyimpan log pesanan yang disubmit customer (tanpa kolom status pesanan).
* `id` (BigInt, PK)
* `order_code` (Varchar, Unique, Indexed) — Contoh: `RP-261101-X7B2`
* `talent_id` (ForeignId -> `users.id`)
* `service_id` (ForeignId -> `services.id`)
* `customer_name` (Varchar)
* `customer_whatsapp` (Varchar)
* `booking_schedule` (DateTime) — Jadwal booking yang dipilih customer
* `notes` (Text, Nullable) — Catatan tambahan dari customer
* `service_name_snapshot` (Varchar) — Nama layanan saat dipesan (agar riwayat tetap akurat bila layanan diubah)
* `price_snapshot` (UnsignedBigInteger) — Tarif layanan saat dipesan
* `timestamps` (`created_at` digunakan sebagai waktu pesanan masuk)

### 4. Tabel `testimonials`
Menyimpan daftar testimoni yang dikelola oleh Admin.
* `id` (BigInt, PK)
* `talent_id` (ForeignId -> `users.id`, Nullable)
* `customer_alias` (Varchar) — Contoh: "Anonim" atau "R***"
* `rating` (TinyInt, Default: 5) — Skala 1 sampai 5
* `content` (Text) — Isi testimoni
* `screenshot_path` (Varchar, Nullable) — Gambar bukti chat (opsional)
* `is_published` (Boolean, Default: `true`)
* `timestamps`

---

## 6. Keamanan & Kebutuhan Non-Fungsional (Non-Functional Requirements)

1. **Pencegahan Spam pada Form Tanpa Login:**
   * Pasang **Laravel Rate Limiter** (`throttle:5,1` — maksimal 5 kali submit per menit per IP) pada rute submit pemesanan agar tabel `orders` dan WhatsApp Admin tidak dibanjiri *spam*.
2. **Otorisasi Berbasis Policy & Middleware:**
   * Gunakan **Laravel Policy** (`ServicePolicy`) untuk memastikan Talent hanya bisa menambah, mengedit, atau menonaktifkan layanan miliknya sendiri (`talent_id === auth()->id()`), sedangkan Admin memiliki akses penuh.
   * Pisahkan rute dashboard menggunakan middleware role (`/admin/*` dan `/talent/*`).
3. **Generasi Kode Pesanan Unik:**
   * Gunakan format kombinasi tanggal dan string acak (contoh: `'RP-' . now()->format('ymd') . '-' . strtoupper(Str::random(4))`) dengan validasi unik sebelum disimpan ke tabel `orders`.

---

## 7. Tahapan Pengembangan (Development Roadmap)

1. **Fase 1: Setup & Database (Hari 1–2)**
   * Instalasi Laravel 12, konfigurasi database MySQL, pembuatan *migration*, *model*, dan *Database Seeder* untuk akun Admin serta sampel Talent.
2. **Fase 2: Autentikasi & Panel Role (Hari 3–5)**
   * Setup Login & Register untuk Talent serta Login untuk Admin.
   * Pembuatan fitur kelola jasa untuk Talent, serta halaman rekap pesanan masuk, kelola talent/jasa, dan kelola testimoni untuk Admin.
3. **Fase 3: Halaman Publik & Integrasi WA (Hari 6–8)**
   * Pembuatan halaman katalog *talent*, detail *talent* + form pemesanan, halaman testimoni, dan logika *redirect* otomatis ke URL WhatsApp Admin beserta kode unik.
4. **Fase 4: Proteksi Spam & Finalisasi (Hari 9–10)**
   * Pemasangan *rate limiting* pada form publik, uji coba alur pemesanan di perangkat *mobile*, dan *deployment*.