<?php
require_once "config.php";
$db = new Database();
$conn = $db->connect();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'user')");
    if ($query->execute([$nama, $email, $password])) {
        header("Location: login.php");
        exit;
    } else {
        $message = "Pendaftaran gagal!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Register - ZakyTravel</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
<h2>Register</h2>
<form method="POST">
  <input type="text" name="nama" placeholder="Nama Lengkap" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Daftar</button>
  <p class="message"><?php echo $message; ?></p>
  <p>Sudah punya akun? <a href="login.php">Login</a></p>
</form>
</div>
</body>
</html>
