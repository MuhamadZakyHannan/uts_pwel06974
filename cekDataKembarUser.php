<?php
require "fungsi.php";

if (isset($_POST['iduser'])) {
  $iduser = $_POST['iduser'];

  // Menggunakan prepared statement untuk menghindari SQL injection
  $stmt = $koneksi->prepare("SELECT iduser FROM user WHERE iduser = ?");
  $stmt->bind_param("s", $iduser);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo "exists"; // ID user sudah ada
  } else {
    echo "not_exists"; // ID user belum terdaftar
  }

  $stmt->close();
}
