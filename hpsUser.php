<?php
// Memanggil file fungsi.php untuk koneksi database
require "fungsi.php";

// Pastikan parameter 'kode' tersedia
if (!isset($_GET["kode"]) || empty($_GET["kode"])) {
  die("Kode pengguna tidak valid.");
}

// Mengambil ID User dari parameter URL
$iduser = $_GET["kode"];

// Menggunakan Prepared Statement untuk keamanan
$stmt = $koneksi->prepare("DELETE FROM user WHERE iduser = ?");
$stmt->bind_param("s", $iduser);

if ($stmt->execute()) {
  // Redirect ke halaman update setelah penghapusan berhasil
  header("Location: ajaxUpdateUser.php");
  exit();
} else {
  echo "Gagal menghapus data pengguna.";
}

$stmt->close();
$koneksi->close();
