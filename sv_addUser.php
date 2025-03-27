<?php
include "fungsi.php"; // Memasukkan koneksi database

// Pastikan semua input tersedia
$iduser = $_POST['iduser'];
$username = $_POST['username'];
$password = $_POST['password']; // Ambil password dari form
$status = $_POST['status'];

// Cek apakah username sudah ada
$sql_check_username = "SELECT * FROM user WHERE username = '$username'";
$query_check_username = mysqli_query($koneksi, $sql_check_username) or die(mysqli_error($koneksi));

if (mysqli_num_rows($query_check_username) > 0) {
    echo "<script>
            alert('Maaf, Username sudah digunakan. Silakan pilih yang lain.');
            window.location.href='addUser.php';
          </script>";
    exit();
}

// 🔒 Hash Password sebelum disimpan
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert Data User jika tidak ada duplikasi
$sql_insert = "INSERT INTO user (iduser, username, password, status) 
               VALUES ('$iduser', '$username', '$hashed_password', '$status')";
$query_insert = mysqli_query($koneksi, $sql_insert) or die(mysqli_error($koneksi));

if ($query_insert) {
    echo "<script>
            alert('Data user berhasil ditambahkan!');
            window.location.href='ajaxUpdateUser.php';
          </script>";
} else {
    echo "Gagal menyimpan data user!";
}
