# UI/UX Design Specification — RentalPacar

## 1. Filosofi & Konsep Desain

* **Pendekatan Utama:** *Mobile-First Container* (tampilan terpusat seperti aplikasi mobile / *Linktree* meskipun dibuka di layar desktop).

* **Gaya Visual:** Romantis modern, *clean*, ramah pengguna (*friendly*), dan fokus pada konversi cepat tanpa kebingungan navigasi.

* **Lebar Kontainer Utama (Public View):** Maksimal `480px` (`max-w-md` di Tailwind CSS), berada di tengah layar (`mx-auto`), dengan tinggi minimal satu layar penuh (`min-h-screen`) dan bayangan halus di sisi kanan-kiri saat dibuka di desktop.

## 2. Palet Warna (Color Palette)

Menggunakan warna utama **Merah (Rose/Crimson Red)** yang memberikan kesan hangat, menarik perhatian, dan sesuai dengan tema *dating companion*.

| Peran Warna | Nama / Kode Tailwind | Kode Hex | Penggunaan | 
| ----- | ----- | ----- | ----- | 
| **Primary (Utama)** | `rose-600` / `red-600` | `#E11D48` | Tombol utama (CTA), header aksen, border aktif, badge harga. | 
| **Primary Hover** | `rose-700` | `#BE123C` | Efek *hover* atau *active press* pada tombol utama. | 
| **Secondary / Soft** | `rose-50` / `rose-100` | `#FFF1F2` | Latar belakang dalam kontainer mobile, *highlight* pilihan paket tarif. | 
| **Outer Background** | `neutral-900` / `rose-950` | `#171717` | Latar belakang luar (area kosong di kiri-kanan saat dibuka di layar desktop). | 
| **Surface / Card** | `white` | `#FFFFFF` | Latar belakang *card* talent, kartu testimoni, dan modal/form pemesanan. | 
| **Text Primary** | `slate-800` | `#1E293B` | Judul, nama talent, teks utama. | 
| **Text Secondary** | `slate-500` | `#64748B` | Deskripsi bio, durasi layanan, teks footer. | 
| **WhatsApp Accent** | `emerald-500` | `#10B981` | Indikator *online* / tersedia dan aksen khusus tombol konfirmasi WA. | 

## 3. Tipografi & Ikonografi

* **Font Tunggal (Wajib):** **Plus Jakarta Sans** (Google Fonts) — digunakan secara eksklusif untuk seluruh teks (Heading, Body, Button, hingga Dashboard Admin/Talent) agar konsisten, modern, dan sangat nyaman dibaca di layar HP.

* **Variasi Ketebalan Font (Font Weights):**

  * `400` (*Regular*) — Deskripsi layanan, bio talent, catatan.

  * `500` (*Medium*) — Label input form, teks sekunder.

  * `600` (*SemiBold*) — Teks tombol menu Linktree, sub-judul, nama paket tarif.

  * `700` (*Bold*) — Nama Brand (**RentalPacar**), nama talent, nominal harga.

* **Ukuran Teks:**

  * Judul Brand (Header): `20px` (`text-xl`), `font-bold`

  * Nama Talent / Judul Halaman: `18px` (`text-lg`), `font-bold`

  * Teks Tombol Linktree: `15px` (`text-sm` / `text-base`), `font-semibold`

  * Deskripsi & Bio: `14px` (`text-sm`), `font-normal`

* **Setup Font di Blade / CSS (Laravel 12 + Tailwind v4):**
  ```html
  <!-- Tambahkan di <head> layout utama -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  ```
  ```css
  /* Di dalam resources/css/app.css */
  @import "tailwindcss";

  @theme {
    --font-sans: 'Plus Jakarta Sans', sans-serif;
  }
  ```

* **Ikon:** Menggunakan **Heroicons** atau **Lucide Icons** dengan sudut membulat (*rounded stroke*).

## 4. Tata Letak Halaman Publik (Customer View)

### 4.1. Halaman Utama (Home — Gaya Linktree)

Tampilan pertama saat *customer* membuka website tanpa *navbar* rumit. Semua elemen tersusun vertikal di tengah.

```
+---------------------------------------+
|                                       |
|             [ LOGO BULAT ]            |
|              RentalPacar              |
|     "Teman Cerita & Kencan Online"    |
|                                       |
|  +---------------------------------+  |
|  | ❤️  Daftar Jasa & Talent        |  |  <-- Button Utama (Solid Merah)
|  +---------------------------------+  |
|                                       |
|  +---------------------------------+  |
|  | ⭐  Testimoni Customer          |  |  <-- Button Kedua (Putih Border Merah)
|  +---------------------------------+  |
|                                       |
|  +---------------------------------+  |
|  | 💬  Hubungi WA RentalPacar      |  |  <-- Button Ketiga (Putih Border Merah)
|  +---------------------------------+  |
|                                       |
|                                       |
|                                       |
|     ------------------------------    |
|      © 2026 RentalPacar Official      |
|           Login Talent / Admin        |
+---------------------------------------+

```

**Detail Komponen Halaman Utama:**

1. **Header Profil (Atas):**

   * **Logo:** Lingkaran (`w-24 h-24 rounded-full`) dengan border merah tipis (`ring-4 ring-rose-200`), bayangan lembut (`shadow-md`).

   * **Nama Brand:** **RentalPacar** dengan ikon *verified badge* kecil di sebelahnya.

   * **Tagline Singkat:** 1–2 baris deskripsi singkat tentang layanan.

2. **Daftar Tombol Menu (Tengah):**

   * Menggunakan bentuk kapsul/membulat penuh (`rounded-2xl` atau `rounded-full`), tinggi nyaman disentuh jari (`py-4 px-6`), dengan efek membal saat ditekan (`active:scale-95 transition`).

   * **Button 1 — Menu Daftar Jasa:** Latar merah solid (`bg-rose-600 text-white shadow-lg shadow-rose-600/30`), mengarah ke halaman katalog `/talents`.

   * **Button 2 — Menu Testimoni:** Latar putih dengan garis tepi merah (`bg-white text-rose-600 border-2 border-rose-600`), mengarah ke `/testimonials`.

   * **Button 3 — Menu WA (RentalPacar):** Latar putih/merah muda lembut (`bg-rose-100 text-rose-700 font-semibold`), langsung membuka *chat* ke nomor WhatsApp Admin untuk tanya-tanya umum.

3. **Footer Sederhana (Bawah):**

   * Berada di bagian paling bawah (`mt-auto py-6 text-center`).

   * Teks hak cipta kecil (`text-xs text-slate-400`) dan tautan kecil tidak mencolok untuk **Login Talent** jika diperlukan.

### 4.2. Halaman Daftar Jasa & Talent (`/talents`)

Saat pengguna menekan tombol **Daftar Jasa & Talent**, halaman tetap mempertahankan ukuran *mobile container*:

* **Top Bar:** Tombol kembali (`<- Kembali ke Menu`) di kiri atas dan judul **"Pilih Talent"** di tengah.

* **Card Talent (Grid 1 atau 2 Kolom):**

  * Foto Talent dengan rasio potret (`aspect-[4/5] rounded-xl object-cover`).

  * Badge status di pojok atas foto (`🟢 Tersedia`).

  * Nama Talent & deskripsi singkat (maksimal 2 baris, `line-clamp-2`).

  * Harga mulai dari (contoh: `Mulai Rp 35.000`).

  * Tombol penuh di bagian bawah *card*: **"Lihat Tarif & Pesan"** (`bg-rose-600 text-white rounded-xl py-2.5`).

### 4.3. Halaman Detail Talent & Form Pemesanan (`/talents/{id}`)

Satu halaman vertikal yang menggabungkan profil *talent*, pilihan tarif, dan form pemesanan:

1. **Profil Talent:** Foto utama, nama, bio lengkap.

2. **Pilih Jenis Tarif (Radio Cards):**

   * Daftar layanan milik *talent* ditampilkan sebagai kotak pilihan.

   * Saat diklik, kotak berubah menjadi berbingkai merah tebal (`border-2 border-rose-600 bg-rose-50`).

   * Menampilkan Nama Jasa (misal: *Voice Call 1 Jam*), deskripsi singkat, dan Harga.

3. **Form Pemesanan Singkat:**

   * Input **Nama / Panggilan Kamu**

   * Input **Nomor WhatsApp Kamu**

   * Input **Jadwal Tanggal & Jam**

   * Input **Catatan Tambahan (Opsional)**

4. **Sticky Bottom Button (Tombol Kirim ke WA):**

   * Tombol merah besar di bagian bawah: **"Kirim Pesanan ke WhatsApp"** yang otomatis mencatat kode unik `RP-XXXX` ke database dan mengalihkan ke WA Admin.

### 4.4. Halaman Testimoni (`/testimonials`)

* **Top Bar:** Tombol kembali (`<- Kembali`) dan judul **"Testimoni Customer"**.

* **Daftar Card Testimoni:**

  * Menampilkan nama samaran customer (misal: *A* - Bandung\*), nama *talent* yang disewa, ikon bintang kuning (`⭐⭐⭐⭐⭐`), ulasan teks, serta gambar *screenshot* chat (jika diunggah oleh Admin).

## 5. Tata Letak Dashboard (Talent & Admin View)

Untuk halaman setelah login (`/talent/*` dan `/admin/*`), tampilan menggunakan tata letak dashboard responsif dengan nuansa warna merah (`rose-600`) yang senada:

* **Dashboard Talent (Fokus Mobile & Desktop):**

  * Navigasi bawah (*Bottom Bar*) atau *Header* sederhana karena kebanyakan *talent* akan mengelola jasa melalui HP mereka.

  * Tombol cepat **"Tambah Jasa Baru"** dan tombol *switch toggle* (Merah = Aktif, Abu-abu = Nonaktif) pada setiap kartu layanan milik *talent*.

* **Dashboard Admin (Lebar Penuh / Desktop Friendly):**

  * *Sidebar* kiri dengan aksen merah tua/putih.

  * **Kartu Ringkasan Atas:** Total Pesanan Masuk, Total Talent, Total Jasa Aktif.

  * **Tabel Daftar Pesanan Masuk:** Menampilkan kolom `Kode Pesanan (RP-XXXX)`, `Waktu Masuk`, `Nama Customer`, `No WA`, `Talent`, `Paket & Harga`, dan `Catatan` (tanpa kolom status).

## 6. Komponen UI Standar (Tailwind CSS Reference)

Berikut adalah panduan kelas Tailwind CSS yang siap dipakai di *file* Blade Laravel 12:

* **Wrapper Utama Mobile (Layout Guest):**
  `min-h-screen bg-neutral-900 flex justify-center font-sans`

* **Container Dalam (Layar HP):**
  `w-full max-w-md min-h-screen bg-rose-50/60 flex flex-col justify-between p-6 shadow-2xl`

* **Tombol Menu Utama (Linktree Style - Primary):**
  `w-full py-4 px-6 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-semibold rounded-2xl shadow-lg shadow-rose-600/25 flex items-center justify-center gap-3 transition-all duration-150`

* **Tombol Menu Sekunder (Linktree Style - Outline):**
  `w-full py-4 px-6 bg-white hover:bg-rose-50 active:scale-[0.98] text-rose-600 border-2 border-rose-600 font-semibold rounded-2xl shadow-sm flex items-center justify-center gap-3 transition-all duration-150`