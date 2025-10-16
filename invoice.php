<?php
$kode = $_GET['kode'] ?? 'INV0000';
$user = $_GET['user'] ?? 'Guest';
$paket = $_GET['paket'] ?? 'Paket Tidak Diketahui';
$harga = $_GET['harga'] ?? 0;
$tanggal = date("d M Y, H:i");

function generateBarcode($text) {
    $bars = '';
    $bits = str_split(md5($text));
    foreach($bits as $b){
        $bars .= (ord($b) % 2) ? '<rect width="2" height="40" x="'.(strlen($bars)*2).'" y="0" fill="black"/>' : '';
    }
    return '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="40">'.$bars.'</svg>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice - <?= htmlspecialchars($kode) ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="invoice">
  <div class="invoice-header">
    🌏 <span>ZakyTravel</span>
    <h3>E-Tiket / Invoice</h3>
  </div>

  <div class="invoice-body">
    <p><strong>Kode Booking:</strong> <?= htmlspecialchars($kode) ?></p>
    <p><strong>Nama:</strong> <?= htmlspecialchars($user) ?></p>
    <p><strong>Paket Wisata:</strong> <?= htmlspecialchars($paket) ?></p>
    <p><strong>Harga:</strong> Rp <?= number_format($harga) ?></p>
    <p><strong>Tanggal:</strong> <?= $tanggal ?></p>

    <div class="barcode">
      <?= generateBarcode($kode) ?>
    </div>
  </div>

  <a href="javascript:window.print()" class="btn-print">🖨️ Cetak Tiket</a>
  <p class="invoice-footer">ZakyTravel © 2025 — Selamat Berlibur 🏖️</p>
</div>

</body>
</html>
