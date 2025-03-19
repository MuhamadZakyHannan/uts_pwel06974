<?php
include "fungsi.php"; // Masukkan koneksi DB

// Ambil data dari form
$nim = $_POST['nim'];
$nama = $_POST['nama'];
$email = $_POST['email'];

// Validasi format NIM di server-side
if (!preg_match('/^[A-Z]\d{2}\.\d{4}\.\d{5}$/', $nim)) {
    echo "<script>
            alert('Format NIM tidak sesuai! Gunakan format: A12.2023.12345');
            window.location.href='addMhs.php';
          </script>";
    exit;
}

// Pemeriksaan apakah NIM sudah ada dalam database
$sql_check = "SELECT * FROM mhs WHERE nim='$nim'";
$query_check = mysqli_query($koneksi, $sql_check) or die(mysqli_error($koneksi));

if (mysqli_num_rows($query_check) > 0) {
    echo "<script>
            alert('Maaf, NIM sudah ada dalam database.');
            window.location.href='addMhs.php';
          </script>";
    exit();
}

// Validasi dan upload foto
$uploadOk = 1;
$folderupload = "foto/";
$fileupload = $folderupload . basename($_FILES['foto']['name']);
$filefoto = basename($_FILES['foto']['name']);
$jenisfilefoto = strtolower(pathinfo($fileupload, PATHINFO_EXTENSION));

// Cek jika file foto sudah ada
if (file_exists($fileupload)) {
    echo "<script>alert('Maaf, file foto sudah ada.');</script>";
    $uploadOk = 0;
}

// Cek ukuran file (max 1MB)
if ($_FILES["foto"]["size"] > 1000000) {
    echo "<script>alert('Maaf, ukuran file foto harus kurang dari 1 MB.');</script>";
    $uploadOk = 0;
}

// Hanya izinkan file JPG, JPEG, PNG, dan GIF
if ($jenisfilefoto != "jpg" && $jenisfilefoto != "png" && $jenisfilefoto != "jpeg" && $jenisfilefoto != "gif") {
    echo "<script>alert('Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.');</script>";
    $uploadOk = 0;
}

// Jika ada kesalahan dalam upload foto, hentikan proses
if ($uploadOk == 0) {
    echo "<script>alert('Maaf, file tidak dapat diupload.');</script>";
    exit();
}

// Jika semua validasi lolos, pindahkan file foto ke folder dan simpan ke database
if (move_uploaded_file($_FILES["foto"]["tmp_name"], $fileupload)) {
    $sql_insert = "INSERT INTO mhs(nim, nama, email, foto) VALUES ('$nim', '$nama', '$email', '$filefoto')";
    if (mysqli_query($koneksi, $sql_insert)) {
        echo "<script>
                alert('Data berhasil ditambahkan!');
                window.location.href='ajaxUpdateMhs.php';
              </script>";
    } else {
        echo "Terjadi kesalahan: " . mysqli_error($koneksi);
    }
} else {
    echo "<script>alert('Terjadi kesalahan saat mengunggah file.');</script>";
}
