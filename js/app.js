// js/app.js — Kumpulan fungsi JavaScript untuk halaman tabel.php dan form.php

// Konfirmasi sebelum menghapus data siswa (dipakai di tabel.php)
function konfirmasiHapus(id, nama) {
  var yakin = confirm('Yakin ingin menghapus data siswa "' + nama + '"?');
  if (yakin) {
    window.location.href = 'tabel.php?hapus=' + id;
  }
  return false;
}

// Preview foto sebelum diupload (dipakai di form.php)
function previewFoto(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById('preview-foto').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Validasi form sebelum disubmit (dipakai di form.php)
function validasiForm() {
  var nis     = document.getElementById('nis').value.trim();
  var nama    = document.getElementById('nama').value.trim();
  var kelas   = document.getElementById('kelas').value;
  var jurusan = document.getElementById('jurusan').value;
  var jk      = document.querySelector('input[name="jenis_kelamin"]:checked');

  if (nis === '') {
    alert('NIS wajib diisi!');
    return false;
  }
  if (nama === '') {
    alert('Nama siswa wajib diisi!');
    return false;
  }
  if (!jk) {
    alert('Jenis kelamin wajib dipilih!');
    return false;
  }
  if (kelas === '') {
    alert('Kelas wajib dipilih!');
    return false;
  }
  if (jurusan === '') {
    alert('Jurusan wajib dipilih!');
    return false;
  }
  return true;
}
