<?php
require_once "config.php";
if(!isset($_SESSION['user'])){
  header("Location: login.php");
  exit;
}

$db = new Database();
$conn = $db->connect();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM tours WHERE id=?");
$stmt->execute([$id]);
$tour = $stmt->fetch(PDO::FETCH_ASSOC);
$user = $_SESSION['user'];
$kode = "INV" . strtoupper(substr(md5(time()), 0, 8));
$tanggal = date("d M Y, H:i");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Beli Paket - ZakyTravel</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="checkout-container">
  <?php if($tour): ?>
    <div class="checkout-card">
      <h2>🎉 Pembelian Berhasil!</h2>
      <p class="success-text">Terima kasih, <strong><?= htmlspecialchars($user['nama']) ?></strong>!</p>
      <p>Kamu telah membeli paket:</p>

      <div class="tour-summary">
        <img src="uploads/<?= htmlspecialchars($tour['gambar']) ?>" alt="">
        <div class="info">
          <h3><?= htmlspecialchars($tour['nama_paket']) ?></h3>
          <p><?= htmlspecialchars($tour['deskripsi']) ?></p>
          <p class="harga">Harga: Rp <?= number_format($tour['harga']); ?></p>
        </div>
      </div>

      <p>Kode Booking: <strong><?= $kode ?></strong></p>
      <p>Tanggal: <?= $tanggal ?></p>

      <div class="next-steps">
        <h4>Langkah Selanjutnya:</h4>
        <ul>
          <li>📧 E-tiket akan dikirim ke email kamu.</li>
          <li>📞 Tim kami akan menghubungi untuk konfirmasi.</li>
        </ul>
      </div>

      <a href="invoice.php?kode=<?= $kode ?>&user=<?= urlencode($user['nama']) ?>&paket=<?= urlencode($tour['nama_paket']) ?>&harga=<?= $tour['harga'] ?>" class="btn">🎫 Lihat Tiket / Invoice</a>
      <a href="index.php" class="btn">Kembali ke Beranda</a>
    </div>
  <?php else: ?>
    <div class="checkout-card">
      <h2>❌ Paket Tidak Ditemukan</h2>
      <a href="index.php" class="btn">Kembali</a>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
