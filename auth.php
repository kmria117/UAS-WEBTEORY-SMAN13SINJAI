<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function cekLogin() {
    if (
        !isset($_SESSION['loggedIn']) ||
        $_SESSION['loggedIn'] !== true ||
        !isset($_SESSION['token']) ||
        !isset($_COOKIE['auth_token']) ||
        $_SESSION['token'] !== $_COOKIE['auth_token']
    ) {
        session_unset();
        session_destroy();
        ?>
        <!DOCTYPE html>
        <html>
        <head><meta charset="UTF-8"></head>
        <body>
        <script>
          alert('Sesi Anda telah berakhir! Silakan login kembali.');
          window.location.href = 'login.php';
        </script>
        </body>
        </html>
        <?php
        exit;
    }
}

function getNamaUser() {
    return $_SESSION['namaUser'] ?? 'User';
}
