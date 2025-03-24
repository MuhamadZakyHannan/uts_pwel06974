<?php
// Memanggil file koneksi database
require "fungsi.php";

// Memindahkan data kiriman dari form ke variabel
$iduser = $_POST["iduser"];
$username = $_POST["username"];
$status = $_POST["status"];

// Membuat query update
$sql = "UPDATE user SET username='$username', status='$status' WHERE iduser='$iduser'";

// Eksekusi query
mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));

// Redirect kembali ke halaman AJAX update user
header("location:ajaxupdateUser.php");
