```markdown
# 📦 Sistem Inventaris Pembelajaran (PHP Native)

Sistem Inventaris Pembelajaran ini dikembangkan menggunakan PHP Native (tanpa framework) untuk mempermudah pengelolaan data barang inventaris dalam lingkungan pembelajaran seperti laboratorium, ruang kelas, atau sekolah.

## 📁 Struktur Direktori

```plaintext
inventaris-pembelajaran/
│
├── assets/           # File statis seperti CSS, JavaScript, dan gambar
│   ├── css/
│   ├── js/
│   └── img/
│
├── config/           # File konfigurasi seperti koneksi database
│   └── database.php
│
├── includes/         # Komponen umum seperti header, footer, dan auth
│   ├── header.php
│   ├── footer.php
│   └── auth.php
│
├── pages/            # Halaman utama aplikasi
│   ├── dashboard.php
│   ├── login.php
│   ├── logout.php
│   ├── barang/
│   │   ├── data.php
│   │   ├── tambah.php
│   │   ├── edit.php
│   │   └── hapus.php
│   ├── kategori/
│   ├── pengguna/
│   └── laporan/
│
├── proses/           # File proses CRUD (Create, Read, Update, Delete)
│   ├── barang_proses.php
│   ├── kategori_proses.php
│   └── login_proses.php
│
├── uploads/          # Tempat penyimpanan gambar/file yang diunggah
│   └── barang/
│
├── index.php         # Entry point aplikasi (redirect ke login/dashboard)
└── .htaccess         # Opsional, digunakan untuk keamanan folder tertentu
```

## ⚙️ Instalasi

1. **Clone repositori atau download source code** ke dalam folder `htdocs` atau direktori server lokal Anda.
2. **Buat database** di PHPMyAdmin (contoh nama: `inventaris_db`).
3. **Import file SQL** (jika tersedia) untuk membuat tabel-tabel yang dibutuhkan.
4. **Edit konfigurasi database** di `config/database.php`:

```php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'inventaris_db';
```

5. **Akses aplikasi** melalui browser:

```
http://localhost/inventaris-pembelajaran/
```

## 🔐 Fitur Sistem

* Login & Logout User
* Manajemen Barang Inventaris
* Manajemen Kategori Barang
* Laporan & Riwayat Penggunaan
* Upload Gambar Barang (opsional)
* Multi-Level User (opsional)

## 💡 Teknologi yang Digunakan

* PHP Native
* MySQL/MariaDB
* HTML + CSS (Bootstrap opsional)
* JavaScript (opsional)

## 🙌 Kontribusi

Pull request, kritik, dan saran sangat diterima untuk pengembangan sistem ini ke depannya.

---

> Dibuat oleh: **Kelompok 2**  
> Politeknik Negeri Sriwijaya
```
