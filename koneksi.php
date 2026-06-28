<?php
// 1. Membuat nilai tetap untuk konfigurasi database server
define('DB_HOST', 'localhost'); // Host server (karena pakai XAMPP lokal)
define('DB_USER', 'root');      // Username default dari XAMPP
define('DB_PASS', '');          // Password default XAMPP (biasanya kosong)
define('DB_NAME', 'db_sman13'); // Nama database yang kita buat di phpMyAdmin

// 2. Membuat objek koneksi menggunakan ekstensi mysqli
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 3. Validasi apakah koneksi berhasil atau gagal
if ($conn->connect_error) {
    // Jika gagal, hentikan program (die) dan tampilkan pesan error yang informatif
    die("<div style='font-family:Arial;padding:30px;color:red;'>
        <h2>❌ Gagal Koneksi ke Database</h2>\n        <p>" . $conn->connect_error . "</p>\n        <p>Pastikan XAMPP (Apache + MySQL) sudah dijalankan.</p>\n    </div>");
}

// 4. Mengatur charset ke utf8mb4 agar mendukung karakter teks yang luas (seperti emoji atau simbol khusus)
$conn->set_charset('utf8mb4');