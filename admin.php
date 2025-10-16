<?php
require_once "config.php";
$db = new Database();
$conn = $db->connect();

// Cek login
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
  header("Location: login.php");
  exit;
}

// Tambah data
if(isset($_POST['submit'])){
  $nama = $_POST['nama_paket'];
  $deskripsi = $_POST['deskripsi'];
  $harga = $_POST['harga'];

  // Upload gambar
  $gambar = $_FILES['gambar']['name'];
  $target = "uploads/" . basename($gambar);
  move_uploaded_file($_FILES['gambar']['tmp_name'], $target);

  $stmt = $conn->prepare("INSERT INTO tours (nama_paket, deskripsi, harga, gambar) VALUES (?,?,?,?)");
  $stmt->execute([$nama, $deskripsi, $harga, $gambar]);
  header("Location: admin.php");
  exit;
}

// Hapus data
if(isset($_GET['delete'])){
  $id = $_GET['delete'];
  $conn->prepare("DELETE FROM tours WHERE id=?")->execute([$id]);
  header("Location: admin.php");
  exit;
}

// Ambil semua data
$stmt = $conn->prepare("SELECT * FROM tours ORDER BY id DESC");
$stmt->execute();
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <div class="logo">🌏 <span>ZakyTravel</span></div>
  <nav>
    <a href="index.php">Home</a>
    <a href="logout.php" class="login-btn">Logout</a>
  </nav>
</header>

<!-- ===== ADMIN DASHBOARD ===== -->
<section class="admin-container">
  <h2 class="judul" style="margin-top:90px;">Dashboard Admin</h2>
  <p>Kelola Paket Wisata dengan mudah</p>

  <!-- Form Tambah -->
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

  <!-- Tabel Data -->
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
    <?php foreach($tours as $tour): ?>
      <tr>
        <td><?= $tour['id'] ?></td>
        <td><?= htmlspecialchars($tour['nama_paket']) ?></td>
        <td><?= htmlspecialchars($tour['deskripsi']) ?></td>
        <td>Rp <?= number_format($tour['harga']); ?></td>
        <td><img src="uploads/<?= htmlspecialchars($tour['gambar']); ?>" width="90" height="60"></td>
        <td>
          <a href="?delete=<?= $tour['id'] ?>" class="btn-delete" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</section>

<footer>
  <p>© 2025 ZakyTravel | Admin Panel</p>
</footer>
</body>
</html>
