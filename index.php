<?php
require_once "config.php";
$db = new Database();
$conn = $db->connect();

$query = $conn->prepare("SELECT * FROM tours");
$query->execute();
$tours = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ZakyTravel - Paket Wisata</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ===== NAVBAR ===== -->
<header class="navbar">
  <div class="logo">
    🌏 <span>ZakyTravel</span>
  </div>
  <nav>
    <a href="#">Home</a>
    <?php if(isset($_SESSION['user'])): ?>
      <a href="admin.php">Dashboard</a>
      <a href="logout.php" class="login-btn">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php" class="login-btn">Register</a>
    <?php endif; ?>
  </nav>
</header>

<!-- ===== HERO SECTION ===== -->
<section class="hero">
  <img src="bgw.jpg" alt="Bali" class="hero-bg">
  <div class="overlay"></div>
  <div class="hero-content">
    <h1>Temukan Petualanganmu Sekarang!</h1>
    <p>Eksplor destinasi terbaik bersama ZakyTravel</p>
  </div>
</section>

<!-- ===== MAIN CONTENT ===== -->
<main>
  <h2 class="judul">Paket Wisata Pilihan</h2>
  <div class="tour-container">
    <?php foreach ($tours as $tour): ?>
      <div class="tour-card">
        <img src="uploads/<?php echo htmlspecialchars($tour['gambar']); ?>" alt="">
        <h3><?php echo htmlspecialchars($tour['nama_paket']); ?></h3>
        <p><?php echo htmlspecialchars($tour['deskripsi']); ?></p>
        <p class="harga">Rp <?php echo number_format($tour['harga']); ?></p>

        <?php if(isset($_SESSION['user'])): ?>
          <a href="beli.php?id=<?php echo $tour['id']; ?>" class="beli-btn">Beli Paket</a>
        <?php else: ?>
          <a href="login.php" class="beli-btn">Beli Paket</a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<!-- ===== FOOTER ===== -->
<footer>
  <p>© 2025 ZakyTravel | Your Travel Partner ✈️</p>
</footer>

</body>
</html>
