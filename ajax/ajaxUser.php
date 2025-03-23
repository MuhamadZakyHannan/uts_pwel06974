<?php
// Memanggil file berisi fungsi-fungsi yang sering dipakai
require "../fungsi.php";
require "../head.html";

$keyword = $_GET["keyword"];
$jmlDataPerHal = 5;

/* ---- Cetak data per halaman --------- */
// Pencarian data pada tabel user
$sql = "SELECT * FROM user 
        WHERE iduser LIKE '%$keyword%' 
        OR namauser LIKE '%$keyword%' 
        OR email LIKE '%$keyword%'";
$qry = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
$jmlData = mysqli_num_rows($qry);

// Hitung jumlah halaman
$jmlHal = ceil($jmlData / $jmlDataPerHal);
if (isset($_GET['hal'])) {
  $halAktif = $_GET['hal'];
} else {
  $halAktif = 1;
}

$awalData = ($jmlDataPerHal * $halAktif) - $jmlDataPerHal;

// Jika tabel data kosong
$kosong = false;
if (!$jmlData) {
  $kosong = true;
}

// Klausa LIMIT untuk membatasi jumlah baris yang dikembalikan
$sql = "SELECT * FROM user 
        WHERE iduser LIKE '%$keyword%' 
        OR namauser LIKE '%$keyword%' 
        OR email LIKE '%$keyword%' 
        LIMIT $awalData, $jmlDataPerHal";

// Ambil data untuk ditampilkan
$hasil = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
?>

<!-- Cetak data dengan tampilan tabel -->
<table class="table table-hover">
  <thead class="thead-light">
    <tr>
      <th>No.</th>
      <th>ID User</th>
      <th>Nama</th>
      <th>Email</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    while ($row = mysqli_fetch_assoc($hasil)) {
    ?>
      <tr>
        <td><?php echo $no ?></td>
        <td><?php echo $row["iduser"] ?></td>
        <td><?php echo $row["namauser"] ?></td>
        <td><?php echo $row["email"] ?></td>
        <td>
          <a class="btn btn-outline-primary btn-sm" href="editUser.php?kode=<?php echo $row['iduser'] ?>">Edit</a>
          <a class="btn btn-outline-danger btn-sm" href="hpsUser.php?kode=<?php echo $row["iduser"] ?>" id="linkHps" onclick="return confirm('Yakin dihapus?')">Hapus</a>
        </td>
      </tr>
    <?php
      $no++;
    }
    ?>
  </tbody>
</table>
</div>
</body>

</html>