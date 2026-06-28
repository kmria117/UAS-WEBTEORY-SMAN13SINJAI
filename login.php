<?php
session_start();

// Kalau sudah login DAN token cocok, langsung ke dashboard
if (
    isset($_SESSION['loggedIn']) &&
    $_SESSION['loggedIn'] === true &&
    isset($_SESSION['token']) &&
    isset($_COOKIE['auth_token']) &&
    $_SESSION['token'] === $_COOKIE['auth_token']
) {
    header('Location: dashboard.php');
    exit;
}

// Hapus session lama
session_unset();
session_destroy();
session_start();

require_once 'koneksi.php';

$error    = '';
$pesan    = '';

// Pesan dari logout
if (isset($_GET['logout'])) {
    $pesan = 'logout';
}
// Pesan dari akses paksa halaman dashboard
if (isset($_GET['akses'])) {
    $pesan = 'akses';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi!';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, nama FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        $valid = false;
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $valid = true;
            } elseif ($password === $user['password']) {
                $valid = true;
            }
        }

        if ($valid) {
            $token = bin2hex(random_bytes(32));

            $_SESSION['loggedIn'] = true;
            $_SESSION['userId']   = $user['id'];
            $_SESSION['namaUser'] = $user['nama'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['token']    = $token;

            setcookie('auth_token', $token, 0, '/');

            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - SMAN 13 Sinjai</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="kotak-login">
    <img src="resource/logo.jpg" class="logo-login">
    <h2>Login Admin</h2>
    <p class="subjudul-login">SMAN 13 Sinjai</p>

    <?php if ($pesan === 'logout'): ?>
      <div class="notifikasi notif-kuning">✅ Anda telah berhasil keluar. Silakan login kembali.</div>
    <?php elseif ($pesan === 'akses'): ?>
      <div class="notifikasi notif-merah">⚠️ Sesi Anda telah berakhir. Silakan login kembali.</div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="notifikasi notif-merah">❌ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="tombol tombol-biru tombol-lebar">Masuk</button>
    </form>

    <p class="link-beranda"><a href="landing-page.html">← Kembali ke Beranda</a></p>
    <p class="info-default">Default login: <strong>admin</strong> / <strong>admin123</strong></p>
  </div>

</body>
</html>
