<?php
require_once "config.php";
$db = new Database();
$conn = $db->connect();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->execute([$email]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit;
    } else {
        $message = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login - ZakyTravel</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
<h2>Login</h2>
<form method="POST">
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Login</button>
  <p class="message"><?php echo $message; ?></p>
  <p>Belum punya akun? <a href="register.php">Register</a></p>
</form>
</div>
</body>
</html>
