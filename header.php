<?php
// header.php — Header + sidebar yang dipakai bersama di halaman dashboard, tabel, dan form.
// Variabel $judul dan $halamanAktif diisi oleh file pemanggil sebelum require ini.
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $judul ?> - SMAN 13 Sinjai</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <header class="header-app">
    <span>Sistem Informasi SMAN 13 Sinjai</span>
    <span class="header-user">Halo, <?= htmlspecialchars(getNamaUser()) ?> &nbsp;|&nbsp; <a href="logout.php">Keluar</a></span>
  </header>

  <div class="container">
    <div class="sidebar">
      <a href="dashboard.php" class="<?= $halamanAktif === 'dashboard' ? 'aktif' : '' ?>">Dashboard</a>
      <a href="tabel.php" class="<?= $halamanAktif === 'tabel' ? 'aktif' : '' ?>">Data Siswa</a>
      <a href="form.php" class="<?= $halamanAktif === 'form' ? 'aktif' : '' ?>">Tambah Siswa</a>
      <a href="logout.php">Keluar</a>
    </div>

    <div class="konten">
