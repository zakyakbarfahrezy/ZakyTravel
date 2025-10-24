<?php
require_once "config.php";
if(!isset($_SESSION['user'])) {
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

if(isset($_POST['beli'])) {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $no_hp = $_POST['no_hp'];
  $jumlah = $_POST['jumlah'];
  $catatan = $_POST['catatan'];

  $query = $conn->prepare("INSERT INTO pembelian (id_user,id_tour,nama,email,no_hp,jumlah_orang,catatan)
                           VALUES (?,?,?,?,?,?,?)");
  $query->execute([$user['id'], $tour['id'], $nama, $email, $no_hp, $jumlah, $catatan]);

  $kode = "INV" . strtoupper(substr(md5(time()), 0, 8));
  header("Location: invoice.php?kode=$kode&user=" . urlencode($nama) . "&paket=" . urlencode($tour['nama_paket']) . "&harga=" . $tour['harga']);
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Beli Paket - <?= htmlspecialchars($tour['nama_paket']) ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
  <h2>Form Pembelian Paket</h2>
  <?php if($tour): ?>
  <p>Paket yang dipilih: <strong><?= htmlspecialchars($tour['nama_paket']) ?></strong></p>
  <p>Harga: Rp <?= number_format($tour['harga']); ?></p>
  <form method="post">
    <input type="text" name="nama" placeholder="Nama Lengkap" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="no_hp" placeholder="Nomor HP" required>
    <input type="number" name="jumlah" placeholder="Jumlah Orang" required>
    <textarea name="catatan" placeholder="Catatan Tambahan (Opsional)"></textarea>
    <button type="submit" name="beli">Lanjut ke Invoice</button>
  </form>
  <?php else: ?>
    <p>Paket tidak ditemukan.</p>
  <?php endif; ?>
</div>
</body>
</html>
