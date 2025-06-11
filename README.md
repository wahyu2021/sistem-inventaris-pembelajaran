<p align="center">
  <img src="public/images/icon-web.png" alt="SISINPEM Logo" width="150"/>
</p>

<h1 align="center">🚀 SISINPEM - Sistem Informasi Inventaris Pembelajaran 🚀</h1>

<p align="center">
  <strong>Kelola Aset Pembelajaran Berbasis Lokasi dengan Cerdas, Efisien, dan Modern!</strong><br>
  Dibangun dengan ❤️ oleh Kelompok 2 MIC2023 untuk tujuan pembelajaran.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Livewire-✓-FB70A9?style=for-the-badge&logo=livewire" alt="Livewire">
  <img src="https://img.shields.io/badge/Jetstream-✓-14B8A6?style=for-the-badge" alt="Jetstream">
  <img src="https://img.shields.io/badge/Tailwind_CSS-✓-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php" alt="PHP 8.2+">
</p>

## 👋 Selamat Datang di SISINPEM!

Pernahkah Anda kesulitan melacak kondisi dan aset penting di berbagai lokasi pembelajaran seperti laboratorium atau ruang kelas? SISINPEM hadir sebagai solusi! Aplikasi web inovatif ini dirancang khusus untuk institusi pendidikan yang ingin memodernisasi cara mereka mengelola lokasi dan melaporkan kerusakan aset di dalamnya. Lupakan kerumitan pencatatan manual dan sambut era digital pengelolaan inventaris yang terintegrasi.

SISINPEM memberdayakan **administrator** dengan alat komprehensif untuk mendata, memantau, dan mengelola setiap lokasi—mulai dari laboratorium, ruang teori, hingga fasilitas lainnya. Di sisi lain, **mahasiswa** mendapatkan kemudahan untuk melihat daftar lokasi yang ada dan melaporkan kerusakan secara instan, memastikan proses pembelajaran berjalan lancar tanpa hambatan.

Dilengkapi dengan **notifikasi otomatis** untuk laporan kerusakan baru, SISINPEM memastikan semua pihak terkait selalu mendapatkan informasi terkini.

✨ **Catatan:** Untuk pengalaman pengguna terbaik dan akses ke semua fitur manajemen yang kaya, kami sangat merekomendasikan penggunaan SISINPEM pada perangkat desktop.

## 🌟 Fitur Unggulan yang Membuat Perbedaan

SISINPEM dikemas dengan fitur-fitur canggih untuk memaksimalkan efisiensi Anda:

### 👨‍💻 Untuk Admin Juara:

-   📊 **Dashboard Ringkasan Interaktif:** Pantau denyut nadi inventaris Anda! Statistik kunci jumlah lokasi, progres laporan kerusakan (terbuka/selesai), dan ringkasan pengguna, semuanya dalam satu tampilan dinamis.
-   🏢 **Manajemen Lokasi Detail:** Kendalikan setiap lokasi! CRUD (Create, Read, Update, Delete) lengkap dengan dukungan unggah gambar, nama lokasi, kapasitas, dan deskripsi aset yang ada di dalamnya.
-   🛠️ **Manajemen Laporan Kerusakan Proaktif:** Tangani laporan kerusakan dari mahasiswa atau buat laporan sendiri. Perbarui status secara _real-time_ (diverifikasi, dalam perbaikan, selesai, dihapuskan), tambahkan catatan penting, dan lihat detail lengkap termasuk tipe kerusakan dan bukti foto.
-   🔔 **Pusat Notifikasi Cerdas:** Jangan lewatkan informasi penting! Lihat daftar notifikasi sistem (laporan kerusakan baru), tandai sudah dibaca/belum dibaca, dan kelola histori notifikasi Anda.
-   👤 **Kontrol Pengguna Terpusat:** Sistem secara otomatis membedakan peran admin dan mahasiswa. Pembuatan akun pengguna baru dilakukan secara aman oleh administrator sistem.

### 🧑‍🎓 Untuk Mahasiswa Aktif & Peduli:

-   🏠 **Dashboard Mahasiswa Personal:** Lihat ringkasan laporan kerusakan yang pernah Anda buat dan akses cepat ke fitur-fitur penting.
-   🔍 **Eksplorasi Lokasi Mudah:** Cari dan filter daftar lokasi yang tersedia dengan cepat untuk menemukan ruangan yang Anda tuju.
-   📝 **Pelaporan Kerusakan Instan:** Menemukan kerusakan di sebuah lokasi? Laporkan secara detail! Pilih lokasi, tentukan tipe kerusakan (ringan, sedang, berat), berikan deskripsi, dan unggah foto kerusakan sebagai bukti.

### ⚡ Fitur Sistem Canggih:

-   🤖 **Notifikasi Otomatis Real-time:** Admin langsung tahu saat ada laporan kerusakan baru yang dikirim oleh mahasiswa.
-   🔐 **Keamanan Berbasis Peran:** Setiap pengguna mendapatkan akses dan fungsionalitas yang sesuai dengan perannya (Admin atau Mahasiswa).
-   🎨 **Desain Antarmuka Modern & Bersih:** Tampilan yang didesain dengan tema warna biru (`blue-700`) yang profesional dan fokus pada kemudahan penggunaan.
-   💻 **Optimalisasi Desktop:** Rekomendasi kuat untuk penggunaan desktop guna memaksimalkan semua fitur manajemen.

## 🛠️ Dibangun Dengan Teknologi Terkini

SISINPEM memanfaatkan kekuatan teknologi web modern untuk performa dan pengalaman pengguna terbaik:

-   **Framework Backend:** Laravel 11
-   **Framework Frontend Dinamis:** Livewire
-   **Scaffolding Autentikasi & UI:** Laravel Jetstream
-   **Styling:** Tailwind CSS
-   **Ikon:** Heroicons (via `blade-ui-kit/blade-heroicons`)
-   **Database:** MySQL (Fleksibel untuk database relasional lain yang didukung Laravel)
-   **PHP:** Versi 8.2+
-   **Manajemen Dependensi:** Composer (PHP), NPM/Yarn (JavaScript)

## 🚀 Siap Memulai? Panduan Instalasi Cepat

Ikuti langkah-langkah ini untuk menjalankan SISINPEM di lingkungan lokal Anda:

1.  **Clone Repositori GIT:**

    ```bash
    git clone [https://github.com/wahyu2021/sistem-inventaris-pembelajaran.git](https://github.com/wahyu2021/sistem-inventaris-pembelajaran.git) sisinpem
    cd sisinpem
    ```

2.  **Instal Dependensi PHP:**

    ```bash
    composer install
    ```

3.  **Persiapan File Environment:**
    Salin `.env.example` menjadi `.env`:

    ```bash
    cp .env.example .env
    ```

4.  **Generate Kunci Aplikasi:**

    ```bash
    php artisan key:generate
    ```

5.  **Atur Koneksi ke Database Anda di file `.env`:**

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=db_sisinpem # Sesuaikan!
    DB_USERNAME=root      # Sesuaikan!
    DB_PASSWORD=          # Sesuaikan!

    APP_URL=http://localhost:8000 # Penting untuk URL yang benar!
    ```

6.  **Jalankan Migrasi Database:**
    Perintah ini akan membuat semua tabel yang diperlukan seperti `users`, `locations`, dan `damage_reports`.

    ```bash
    php artisan migrate
    ```

7.  **(Opsional) Jalankan Seeder untuk Membuat Akun Admin Awal:**

    ```bash
    php artisan db:seed
    ```

    Perintah ini akan membuat satu akun Admin default agar Anda bisa langsung login.

8.  **Instal Dependensi Frontend:**

    ```bash
    npm install && npm run dev
    # atau
    # yarn install && yarn dev
    ```

    Untuk lingkungan produksi, gunakan: `npm run build`

9.  **Buat Storage Link:**
    Perintah ini penting agar gambar yang diunggah dapat diakses publik.

    ```bash
    php artisan storage:link
    ```

10. **Jalankan Development Server:**

    ```bash
    php artisan serve
    ```

    🎉 Aplikasi Anda sekarang siap diakses di `http://localhost:8000`! 🎉

11. **(Opsional) Jalankan Queue Worker:**
    Diperlukan agar notifikasi dapat diproses di latar belakang.
    ```bash
    php artisan queue:work
    ```

## 💡 Cara Menggunakan

Setelah proses instalasi berhasil:

-   Buka aplikasi di browser Anda (`http://localhost:8000`).
-   **Login Akun:**
    -   🔒 Ingat! Tidak ada registrasi publik. Akun dibuat oleh Admin melalui menu **Manajemen Pengguna** atau melalui database secara langsung.
    -   **Akun Admin Default (jika Anda menjalankan seeder):**
        -   Email: `test@example.com`
        -   Password: `password`
    -   **Akun Mahasiswa:**
        -   Harus dibuat terlebih dahulu oleh Admin dari dashboard manajemen pengguna.

## ✍️ Catatan Penting Mengenai Pendaftaran Pengguna

Untuk menjaga integritas dan keamanan data, SISINPEM tidak menyediakan fitur pendaftaran publik. Pembuatan akun Admin dan Mahasiswa dilakukan secara terpusat oleh administrator sistem melalui antarmuka **Manajemen Pengguna** di dalam aplikasi.

## 🙏 Ucapan Terima Kasih & Tim Pengembang

Proyek ini merupakan hasil karya dan dedikasi untuk pembelajaran dari:
**Kelompok 2 MIC Angkatan 2023**
