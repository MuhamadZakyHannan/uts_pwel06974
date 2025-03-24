<?php
include "fungsi.php"; // Memasukkan koneksi database

// Ambil variable dari form
$iduser = $_POST['iduser'];
$username = $_POST['username'];
$status = $_POST['status'];

// Pemeriksaan apakah ID User sudah ada dalam database
$sql_check = "SELECT * FROM user WHERE iduser = '$iduser'";
$query_check = mysqli_query($koneksi, $sql_check) or die(mysqli_error($koneksi));

if (mysqli_num_rows($query_check) > 0) {
    // Jika ID User sudah ada, tampilkan pesan kesalahan
    echo "<script>
            alert('Maaf, ID User sudah ada dalam database.');
            window.location.href='addUser.php'; // Kembali ke halaman tambah user
          </script>";
    exit();
} else {
    // Jika ID User belum ada, lakukan query insert
    $sql_insert = "INSERT INTO user (iduser, username, status) 
                   VALUES ('$iduser', '$username', '$status')";
    $query_insert = mysqli_query($koneksi, $sql_insert) or die(mysqli_error($koneksi));

    if ($query_insert) {
        // Menampilkan pesan sukses menggunakan alert JavaScript
        echo "<script>
                alert('Data user berhasil ditambahkan!');
                window.location.href='ajaxUpdateUser.php'; // Redirect ke halaman update
              </script>";
    } else {
        echo "Gagal menyimpan data user!";
    }
}
