<?php
session_start();

setcookie('auth_token', '', time() - 3600, '/');
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>
<script>
  alert('Anda telah berhasil keluar!');
  window.location.href = 'login.php';
</script>
</body>
</html>
