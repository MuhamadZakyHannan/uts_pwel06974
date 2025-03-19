<?php
include "fungsi.php"; // Memasukkan koneksi database

// Ambil variable dari form
$npp = $_POST['npp'];
$namadosen = $_POST['namadosen'];
$homebase = $_POST['homebase'];

// Pemeriksaan apakah NPP sudah ada dalam database
$sql_check = "SELECT * FROM dosen WHERE npp = '$npp'";
$query_check = mysqli_query($koneksi, $sql_check) or die(mysqli_error($koneksi));

if (mysqli_num_rows($query_check) > 0) {
    // Jika NPP sudah ada, tampilkan pesan kesalahan
    echo "<script>
            alert('Maaf, NPP sudah ada dalam database.');
            window.location.href='addDsn.php'; // Kembali ke halaman tambah dosen
          </script>";
    exit();
} else {
    // Jika NPP belum ada, lakukan query insert
    $sql_insert = "INSERT INTO dosen (npp, namadosen, homebase) 
                   VALUES ('$npp', '$namadosen', '$homebase')";
    $query_insert = mysqli_query($koneksi, $sql_insert) or die(mysqli_error($koneksi));

    if ($query_insert) {
        // Menampilkan pesan sukses menggunakan alert JavaScript
        echo "<script>
                alert('Data dosen berhasil ditambahkan!');
                window.location.href='ajaxUpdateDsn.php'; // Redirect ke halaman update
              </script>";
    } else {
        echo "Gagal menyimpan data dosen!";
    }
}
?>
