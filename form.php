<?php
require_once 'auth.php';
cekLogin();
require_once 'koneksi.php';

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$errors = [];
$siswa  = ['nis' => '', 'nama' => '', 'jenis_kelamin' => '', 'tanggal_lahir' => '', 'kelas' => '', 'jurusan' => '', 'foto' => ''];

if ($isEdit) {
    $stmt = $conn->prepare("SELECT * FROM siswa WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) { header('Location: tabel.php'); exit; }
    $siswa = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis     = trim($_POST['nis'] ?? '');
    $nama    = trim($_POST['nama'] ?? '');
    $jk      = $_POST['jenis_kelamin'] ?? '';
    $tgl     = $_POST['tanggal_lahir'] ?? '';
    $kelas   = $_POST['kelas'] ?? '';
    $jurusan = $_POST['jurusan'] ?? '';

    if ($nis === '')     $errors[] = 'NIS wajib diisi.';
    if ($nama === '')    $errors[] = 'Nama wajib diisi.';
    if ($kelas === '')   $errors[] = 'Kelas wajib dipilih.';
    if ($jurusan === '') $errors[] = 'Jurusan wajib dipilih.';
    if ($jk === '')       $errors[] = 'Jenis kelamin wajib dipilih.';

    if ($nis !== '') {
        $cekNIS = $conn->prepare("SELECT id FROM siswa WHERE nis=? AND id!=?");
        $cekNIS->bind_param('si', $nis, $id);
        $cekNIS->execute();
        if ($cekNIS->get_result()->num_rows > 0) $errors[] = 'NIS sudah digunakan siswa lain.';
        $cekNIS->close();
    }

    // Upload foto (jika ada file baru)
    $fotoPath = $siswa['foto'];
    if (!empty($_FILES['foto']['name'])) {
        $ext     = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Format foto tidak didukung (gunakan jpg/png/gif).';
        } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Ukuran foto maksimal 2MB.';
        } else {
            $namaFile = 'uploads/' . uniqid('foto_') . '.' . $ext;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $namaFile)) {
                if ($fotoPath && file_exists($fotoPath)) unlink($fotoPath);
                $fotoPath = $namaFile;
            } else {
                $errors[] = 'Gagal mengupload foto.';
            }
        }
    }

    // Simpan ke database (operasi CREATE / UPDATE)
    if (empty($errors)) {
        if ($isEdit) {
            $stmt = $conn->prepare("UPDATE siswa SET nis=?, nama=?, jenis_kelamin=?, tanggal_lahir=?, kelas=?, jurusan=?, foto=? WHERE id=?");
            $stmt->bind_param('sssssssi', $nis, $nama, $jk, $tgl, $kelas, $jurusan, $fotoPath, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO siswa (nis, nama, jenis_kelamin, tanggal_lahir, kelas, jurusan, foto) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param('sssssss', $nis, $nama, $jk, $tgl, $kelas, $jurusan, $fotoPath);
        }
        $stmt->execute();
        $stmt->close();
        header('Location: tabel.php?pesan=' . ($isEdit ? 'edit' : 'tambah'));
        exit;
    }

    // Pertahankan input yang sudah diisi user jika ada error validasi
    $siswa = compact('nis', 'nama', 'tgl', 'kelas', 'jurusan') + ['jenis_kelamin' => $jk, 'tanggal_lahir' => $tgl, 'foto' => $fotoPath];
}

$judul        = $isEdit ? 'Edit Siswa' : 'Tambah Siswa';
$halamanAktif = 'form';
require 'header.php';
?>

  <h2><?= $isEdit ? '✏️ Edit Data Siswa' : '➕ Tambah Siswa' ?></h2>

  <div class="kartu">

    <?php if (!empty($errors)): ?>
      <div class="notifikasi notif-merah">
        <strong>Terdapat kesalahan:</strong>
        <ul class="daftar-error">
          <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="form.php<?= $isEdit ? '?id=' . $id : '' ?>" enctype="multipart/form-data" onsubmit="return validasiForm()">

      <div class="form-baris form-baris-atas">
        <label>Foto</label>
        <div>
          <img id="preview-foto" class="preview-foto"
               src="<?= ($siswa['foto'] && file_exists($siswa['foto'])) ? htmlspecialchars($siswa['foto']) : 'resource/foto.png' ?>">
          <input type="file" name="foto" accept="image/*" onchange="previewFoto(this)">
          <small class="teks-kecil">Maks. 2MB (jpg/png/gif)</small>
        </div>
      </div>

      <div class="form-baris">
        <label>NIS <span class="wajib">*</span></label>
        <input type="text" id="nis" name="nis" value="<?= htmlspecialchars($siswa['nis']) ?>" placeholder="Masukkan NIS">
      </div>

      <div class="form-baris">
        <label>Nama Lengkap <span class="wajib">*</span></label>
        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($siswa['nama']) ?>" placeholder="Nama lengkap siswa">
      </div>

      <div class="form-baris">
        <label>Jenis Kelamin <span class="wajib">*</span></label>
        <div class="grup-radio">
          <label><input type="radio" name="jenis_kelamin" value="L" <?= $siswa['jenis_kelamin'] === 'L' ? 'checked' : '' ?>> Laki-laki</label>
          <label><input type="radio" name="jenis_kelamin" value="P" <?= $siswa['jenis_kelamin'] === 'P' ? 'checked' : '' ?>> Perempuan</label>
        </div>
      </div>

      <div class="form-baris">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($siswa['tanggal_lahir'] ?? '') ?>">
      </div>

      <div class="form-baris">
        <label>Kelas <span class="wajib">*</span></label>
        <select id="kelas" name="kelas">
          <option value="">-- Pilih Kelas --</option>
          <?php foreach (['X', 'XI', 'XII'] as $k): ?>
            <option <?= $siswa['kelas'] === $k ? 'selected' : '' ?>><?= $k ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-baris">
        <label>Jurusan <span class="wajib">*</span></label>
        <select id="jurusan" name="jurusan">
          <option value="">-- Pilih Jurusan --</option>
          <?php foreach (['IPA', 'IPS'] as $j): ?>
            <option <?= $siswa['jurusan'] === $j ? 'selected' : '' ?>><?= $j ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-baris">
        <label></label>
        <div class="grup-tombol">
          <button type="submit" class="tombol tombol-biru">💾 <?= $isEdit ? 'Update' : 'Simpan' ?></button>
          <a href="tabel.php" class="tombol tombol-abu">Batal</a>
        </div>
      </div>

    </form>
  </div>

<?php require 'footer.php'; ?>
<script src="js/app.js"></script>
