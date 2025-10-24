<?php
require_once "config.php";
$db = new Database();
$conn = $db->connect();

// cek apakah user sudah login dan role admin
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
  header("Location: login.php");
  exit;
}

// tambah paket wisata
if(isset($_POST['submit'])){
  $nama = $_POST['nama_paket'];
  $deskripsi = $_POST['deskripsi'];
  $harga = $_POST['harga'];

  $gambar = $_FILES['gambar']['name'];
  $target = "uploads/" . basename($gambar);
  move_uploaded_file($_FILES['gambar']['tmp_name'], $target);

  $stmt = $conn->prepare("INSERT INTO tours (nama_paket, deskripsi, harga, gambar) VALUES (?,?,?,?)");
  $stmt->execute([$nama, $deskripsi, $harga, $gambar]);
  header("Location: admin.php");
  exit;
}

// hapus paket wisata
if(isset($_GET['delete'])){
  $id = $_GET['delete'];
  $conn->prepare("DELETE FROM tours WHERE id=?")->execute([$id]);
  header("Location: admin.php");
  exit;
}

// ambil semua paket wisata
$stmt = $conn->prepare("SELECT * FROM tours ORDER BY id DESC");
$stmt->execute();
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ambil semua pembelian tiket
$pembelian = $conn->query("SELECT p.*, t.nama_paket 
                           FROM pembelian p 
                           JOIN tours t ON p.id_tour = t.id 
                           ORDER BY p.id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin - ZakyTravel</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ===== NAVBAR ===== -->
<header class="navbar">
  <div class="logo">🌏 <span>ZakyTravel Admin</span></div>
  <nav>
    <a href="index.php">Home</a>
    <a href="logout.php" class="login-btn">Logout</a>
  </nav>
</header>

<!-- ===== ADMIN CONTENT ===== -->
<section class="admin-container">
  <h2 class="judul" style="margin-top:90px;">Dashboard Admin</h2>
  <p>Kelola paket wisata dan lihat daftar pembelian tiket pengguna</p>

  <!-- ===== FORM TAMBAH PAKET ===== -->
  <div class="form-admin">
    <h3>Tambah Paket Wisata</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="text" name="nama_paket" placeholder="Nama Paket" required>
      <textarea name="deskripsi" placeholder="Deskripsi Paket" required></textarea>
      <input type="number" name="harga" placeholder="Harga" required>
      <input type="file" name="gambar" accept="image/*" required>
      <button type="submit" name="submit">Tambah Paket</button>
    </form>
  </div>

  <!-- ===== DAFTAR PAKET WISATA ===== -->
  <h3>Daftar Paket Wisata</h3>
  <table>
    <tr>
      <th>ID</th>
      <th>Nama Paket</th>
      <th>Deskripsi</th>
      <th>Harga</th>
      <th>Gambar</th>
      <th>Aksi</th>
    </tr>
    <?php if(!empty($tours)): ?>
      <?php foreach($tours as $tour): ?>
        <tr>
          <td><?= $tour['id'] ?></td>
          <td><?= htmlspecialchars($tour['nama_paket']) ?></td>
          <td><?= htmlspecialchars($tour['deskripsi']) ?></td>
          <td>Rp <?= number_format($tour['harga']) ?></td>
          <td><img src="uploads/<?= htmlspecialchars($tour['gambar']); ?>" width="90" height="60"></td>
          <td>
            <a href="?delete=<?= $tour['id'] ?>" class="btn-delete" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="6">Belum ada data paket wisata.</td></tr>
    <?php endif; ?>
  </table>

  <!-- ===== DATA PEMBELIAN ===== -->
  <h3 style="margin-top:40px;">Data Pembelian Tiket</h3>
  <table>
    <tr>
      <th>ID</th>
      <th>Nama Pembeli</th>
      <th>Email</th>
      <th>No HP</th>
      <th>Paket</th>
      <th>Jumlah</th>
      <th>Tanggal</th>
    </tr>
    <?php if(!empty($pembelian)): ?>
      <?php foreach($pembelian as $beli): ?>
        <tr>
          <td><?= $beli['id'] ?></td>
          <td><?= htmlspecialchars($beli['nama']) ?></td>
          <td><?= htmlspecialchars($beli['email']) ?></td>
          <td><?= htmlspecialchars($beli['no_hp']) ?></td>
          <td><?= htmlspecialchars($beli['nama_paket']) ?></td>
          <td><?= $beli['jumlah_orang'] ?></td>
          <td><?= $beli['tanggal'] ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="7">Belum ada pembelian tiket.</td></tr>
    <?php endif; ?>
  </table>
</section>

<footer>
  <p>© 2025 ZakyTravel | Admin Panel</p>
</footer>

</body>
</html>
