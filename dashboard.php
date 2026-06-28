<?php
require_once 'auth.php';
cekLogin();
require_once 'koneksi.php';

// Hitung statistik untuk ditampilkan di kartu dashboard
$totalSiswa = $conn->query("SELECT COUNT(*) AS total FROM siswa")->fetch_assoc()['total'];
$totalIPA   = $conn->query("SELECT COUNT(*) AS total FROM siswa WHERE jurusan='IPA'")->fetch_assoc()['total'];
$totalIPS   = $conn->query("SELECT COUNT(*) AS total FROM siswa WHERE jurusan='IPS'")->fetch_assoc()['total'];

$judul        = 'Dashboard';
$halamanAktif = 'dashboard';
require 'header.php';
?>

  <h2>Dashboard</h2>

  <!-- Statistik -->
  <div class="grid-dashboard mb-30">
    <div class="kartu-menu kartu-biru">
      <h3 class="angka-besar"><?= $totalSiswa ?></h3>
      <p>Total Siswa</p>
    </div>
    <div class="kartu-menu kartu-hijau">
      <h3 class="angka-besar"><?= $totalIPA ?></h3>
      <p>Siswa IPA</p>
    </div>
    <div class="kartu-menu kartu-oranye">
      <h3 class="angka-besar"><?= $totalIPS ?></h3>
      <p>Siswa IPS</p>
    </div>
  </div>

  <!-- Menu -->
  <div class="grid-dashboard">
    <a href="tabel.php" class="kartu-menu">
      <h3>📋 Data Siswa</h3>
      <p>Lihat semua data siswa</p>
    </a>
    <a href="form.php" class="kartu-menu">
      <h3>➕ Tambah Siswa</h3>
      <p>Input data siswa baru</p>
    </a>
    <a href="logout.php" class="kartu-menu">
      <h3>🚪 Keluar</h3>
      <p>Logout dari sistem</p>
    </a>
  </div>

<?php require 'footer.php'; ?>
