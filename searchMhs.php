
<?php
require "fungsi.php";

$keyword = isset($_POST['keyword']) ? mysqli_real_escape_string($koneksi, $_POST['keyword']) : '';
$halAktif = isset($_POST['halAktif']) ? (int)$_POST['halAktif'] : 1;
$jmlDataPerHal = isset($_POST['jmlDataPerHal']) ? (int)$_POST['jmlDataPerHal'] : 5;
$awalData = ($jmlDataPerHal * $halAktif) - $jmlDataPerHal;

if (!empty($keyword)) {
  $sql = "SELECT * FROM mhs WHERE 
            nim LIKE '%$keyword%' OR 
            nama LIKE '%$keyword%' OR 
            jurusan LIKE '%$keyword%' 
            ORDER BY id 
            LIMIT $awalData, $jmlDataPerHal";
} else {
  $sql = "SELECT * FROM mhs ORDER BY id LIMIT $awalData, $jmlDataPerHal";
}

$hasil = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($hasil) > 0) {
  while ($row = mysqli_fetch_assoc($hasil)) {
?>
    <tr>
      <td><?php echo $row["id"] ?></td>
      <td><?php echo $row["nim"] ?></td>
      <td><?php echo $row["nama"] ?></td>
      <td><?php echo $row["jurusan"] ?></td>
      <td>
        <?php
        if ($row["filepath"]) {
          echo "<img src='" . $row["filepath"] . "' height='50'>";
        } else {
          echo "No Image";
        }
        ?>
      </td>
      <td>
        <a class="btn btn-outline-primary btn-sm" href="editMhs.php?id=<?php echo $row['id'] ?>">Ubah</a>
        <a class="btn btn-outline-danger btn-sm" href="hpsMhs.php?id=<?php echo $row['id'] ?>" onclick="return confirm('Yakin menghapus data ini?')">Hapus</a>
      </td>
    </tr>
<?php
  }
} else {
  echo "<tr><td colspan='6' class='text-center'>Tidak ada data yang ditemukan</td></tr>";
}
?>
