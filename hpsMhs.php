
<?php
require "fungsi.php";

// Cek apakah parameter id ada
if (!isset($_GET["id"])) {
    echo "<script>
            alert('ID tidak ditemukan!');
            window.location.href='index.php';
          </script>";
    exit;
}

$id = $_GET["id"];

// Validasi id harus numerik
if (!is_numeric($id)) {
    echo "<script>
            alert('ID tidak valid!');
            window.location.href='index.php';
          </script>";
    exit;
}

// Cek apakah data exists
$check_query = "SELECT filename, filepath, thumbpath FROM mhs WHERE id = $id";
$check_result = mysqli_query($koneksi, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    echo "<script>
            alert('Data tidak ditemukan!');
            window.location.href='ajaxupdateMhs.php';
          </script>";
    exit;
}

// Ambil data file
$data = mysqli_fetch_assoc($check_result);

// Hapus file fisik jika ada
if ($data) {
    // Hapus file original
    if (!empty($data['filepath']) && file_exists($data['filepath'])) {
        unlink($data['filepath']);
    }

    // Hapus file thumbnail
    if (!empty($data['thumbpath']) && file_exists($data['thumbpath'])) {
        unlink($data['thumbpath']);
    }
}

// Hapus data dari database
$delete_query = "DELETE FROM mhs WHERE id = $id";
$result = mysqli_query($koneksi, $delete_query);

if ($result) {
    echo "<script>
            alert('Data berhasil dihapus');
            window.location.href='ajaxupdateMhs.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');
            window.location.href='ajaxupdateMhs.php';
          </script>";
}

$koneksi->close();
?>
`