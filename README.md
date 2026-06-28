# 📚 Cara Install di XAMPP — SMAN 13 Sinjai

## Langkah 1 — Jalankan XAMPP
Buka XAMPP Control Panel, klik **Start** pada:
- ✅ Apache
- ✅ MySQL

---

## Langkah 2 — Taruh Project di htdocs
Salin folder `uas_php` ke:
```
C:\xampp\htdocs\uas_php\
```
Struktur akhirnya:
```
C:\xampp\htdocs\uas_php\
  ├── css\
  │   └── style.css
  ├── js\
  │   └── app.js
  ├── resource\
  ├── uploads\          ← foto siswa tersimpan di sini
  ├── koneksi.php
  ├── auth.php
  ├── login.php
  ├── logout.php
  ├── header.php        ← partial: header + sidebar (dipakai bersama)
  ├── footer.php        ← partial: penutup halaman + footer
  ├── dashboard.php
  ├── tabel.php
  ├── form.php
  ├── landing-page.html
  └── database.sql
```

---

## Langkah 3 — Import Database
1. Buka browser → **http://localhost/phpmyadmin**
2. Klik **Import** (menu atas)
3. Pilih file **database.sql** dari folder project
4. Klik **Go / Impor**

Database `db_sman13` akan otomatis dibuat beserta tabelnya.

---

## Langkah 4 — Buka Aplikasi
Buka browser → **http://localhost/uas_php/landing-page.html**

Atau langsung login → **http://localhost/uas_php/login.php**

### Akun Default:
| Username | Password  | Role          |
|----------|-----------|---------------|
| admin    | admin123  | Administrator |
| guru     | guru123   | Guru          |

---

## Fitur yang Tersedia
- 🔐 **Login/Logout** dengan session PHP
- 📊 **Dashboard** dengan statistik jumlah siswa
- 📋 **Data Siswa** — tabel dengan search & filter kelas/jurusan
- ➕ **Tambah Siswa** — form dengan upload foto
- ✏️ **Edit Siswa** — ubah data siswa
- 🗑️ **Hapus Siswa** — dengan konfirmasi modal

---

## Troubleshoot
| Masalah | Solusi |
|---------|--------|
| "Gagal koneksi database" | Pastikan MySQL sudah Start di XAMPP |
| Foto tidak bisa diupload | Pastikan folder `uploads/` ada dan writeable |
| Halaman 404 | Pastikan folder ada di `htdocs\uas_php\` |
