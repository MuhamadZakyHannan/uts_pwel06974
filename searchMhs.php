<?php
require "fungsi.php";

// Hitung posisi data
$keyword = isset($_POST['keyword']) ? mysqli_real_escape_string($koneksi, $_POST['keyword']) : '';
$tanggal = isset($_POST['tanggal']) ? mysqli_real_escape_string($koneksi, $_POST['tanggal']) : '';
$halAktif = isset($_POST['halAktif']) ? (int)$_POST['halAktif'] : 1;
$jmlDataPerHal = isset($_POST['jmlDataPerHal']) ? (int)$_POST['jmlDataPerHal'] : 5;

$awalData = ($jmlDataPerHal * $halAktif) - $jmlDataPerHal;
$no = $awalData + 1;

// Ubah query untuk hanya mencari berdasarkan nim dan nama
$sql = "SELECT * FROM mhs WHERE 1=1";

if (!empty($keyword)) {
  $sql .= " AND (nim LIKE '%$keyword%' OR nama LIKE '%$keyword%')";
}

if (!empty($tanggal)) {
  $sql .= " AND DATE(uploaded_at) = '$tanggal'";
}

$sql .= " ORDER BY id LIMIT $awalData, $jmlDataPerHal";

try {
  $hasil = mysqli_query($koneksi, $sql);

  if (!$hasil) {
    throw new Exception(mysqli_error($koneksi));
  }

  // Cek jika tidak ada hasil
  if (mysqli_num_rows($hasil) == 0) {
    // Tidak perlu menampilkan apa-apa, karena kita akan menangani kasus kosong di JavaScript
    exit;
  }

  while ($row = mysqli_fetch_assoc($hasil)) {
    echo "<tr class='fade-in'>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . htmlspecialchars($row["nim"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["nama"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["jurusan"]) . "</td>";
    echo "<td class='text-center'>";
    if ($row["filepath"] && file_exists($row["filepath"])) {
      echo "<img src='" . htmlspecialchars($row["filepath"]) . "' class='img-thumbnail' style='height: 80px; width: 50px; object-fit: cover;'>";
    } else {
      echo "<i class='fas fa-user-circle fa-2x text-muted'></i>";
    }
    echo "</td>";
    echo "<td>" . date('d-m-Y H:i', strtotime($row["uploaded_at"])) . "</td>";
    echo "<td class='text-center'>";
    echo "<div class='btn-group btn-group-sm'>";
    echo "<a href='editMhs.php?id=" . $row['id'] . "' class='btn btn-outline-primary' title='Edit'>";
    echo "<i class='fas fa-edit'></i> Edit</a> ";
    echo "<a href='hpsMhs.php?id=" . $row['id'] . "' class='btn btn-outline-danger' title='Hapus' ";
    echo "onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>";
    echo "<i class='fas fa-trash'></i> Hapus</a>";
    echo "</div>";
    echo "</td>";
    echo "</tr>";
  }
} catch (Exception $e) {
  echo "<tr><td colspan='7' class='text-center text-danger'>Error: " . $e->getMessage() . "</td></tr>";
}
