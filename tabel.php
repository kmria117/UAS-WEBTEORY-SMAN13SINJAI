<?php
require_once 'auth.php';
cekLogin();
require_once 'koneksi.php';

// Proses hapus data siswa (operasi DELETE)
if (isset($_GET['hapus'])) {
    $id  = (int)$_GET['hapus'];
    $stmt = $conn->prepare("SELECT foto FROM siswa WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row && $row['foto'] && file_exists($row['foto'])) {
        unlink($row['foto']);
    }

    $hapus = $conn->prepare("DELETE FROM siswa WHERE id=?");
    $hapus->bind_param('i', $id);
    $hapus->execute();

    header('Location: tabel.php?pesan=hapus');
    exit;
}

// Filter & pencarian data siswa (operasi READ dengan kondisi)
$cari    = trim($_GET['cari'] ?? '');
$kelas   = $_GET['kelas'] ?? '';
$jurusan = $_GET['jurusan'] ?? '';

$where  = [];
$params = [];
$tipe   = '';

if ($cari !== '') {
    $where[]  = "(nis LIKE CONCAT('%',?,'%') OR nama LIKE CONCAT('%',?,'%'))";
    $params[] = $cari; $params[] = $cari;
    $tipe    .= 'ss';
}
if ($kelas !== '') {
    $where[]  = "kelas = ?";
    $params[] = $kelas;
    $tipe    .= 's';
}
if ($jurusan !== '') {
    $where[]  = "jurusan = ?";
    $params[] = $jurusan;
    $tipe    .= 's';
}

$sql = "SELECT * FROM siswa" . (count($where) ? " WHERE " . implode(" AND ", $where) : "") . " ORDER BY nama ASC";
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($tipe, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$judul        = 'Data Siswa';
$halamanAktif = 'tabel';
require 'header.php';
?>

  <h2>Data Siswa</h2>

  <?php if (isset($_GET['pesan'])): ?>
    <div class="notifikasi <?= $_GET['pesan'] === 'hapus' ? 'notif-merah' : 'notif-hijau' ?>">
      <?= $_GET['pesan'] === 'hapus' ? '🗑️ Data siswa berhasil dihapus.' : '✅ Data siswa berhasil disimpan.' ?>
    </div>
  <?php endif; ?>

  <div class="kartu">
    <!-- Search & Filter -->
    <form method="GET" action="tabel.php" class="form-filter">
      <a href="form.php" class="tombol tombol-biru">+ Tambah Siswa</a>
      <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari nama / NIS...">
      <select name="kelas">
        <option value="">Semua Kelas</option>
        <?php foreach (['X', 'XI', 'XII'] as $k): ?>
          <option <?= $kelas === $k ? 'selected' : '' ?>><?= $k ?></option>
        <?php endforeach; ?>
      </select>
      <select name="jurusan">
        <option value="">Semua Jurusan</option>
        <?php foreach (['IPA', 'IPS'] as $j): ?>
          <option <?= $jurusan === $j ? 'selected' : '' ?>><?= $j ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="tombol tombol-biru">🔍 Cari</button>
      <a href="tabel.php" class="tombol tombol-abu">Reset</a>
    </form>

    <table>
      <thead>
        <tr>
          <th>No</th><th>Foto</th><th>NIS</th><th>Nama</th>
          <th>Jenis Kelamin</th><th>Tgl Lahir</th><th>Kelas</th><th>Jurusan</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows === 0): ?>
          <tr><td colspan="9" class="kosong">Tidak ada data siswa.</td></tr>
        <?php else: ?>
          <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><img src="<?= ($row['foto'] && file_exists($row['foto'])) ? htmlspecialchars($row['foto']) : 'resource/foto.png' ?>" class="foto-siswa"></td>
            <td><?= htmlspecialchars($row['nis']) ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
            <td><?= $row['tanggal_lahir'] ? date('d/m/Y', strtotime($row['tanggal_lahir'])) : '-' ?></td>
            <td><?= htmlspecialchars($row['kelas']) ?></td>
            <td><?= htmlspecialchars($row['jurusan']) ?></td>
            <td class="kolom-aksi">
              <a href="form.php?id=<?= $row['id'] ?>" class="tombol tombol-hijau">Edit</a>
              <a href="#" class="tombol tombol-merah" onclick="return konfirmasiHapus(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>')">Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

<?php require 'footer.php'; ?>
<script src="js/app.js"></script>
