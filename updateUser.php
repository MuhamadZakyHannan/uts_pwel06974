<!DOCTYPE html>
<html>

<head>
  <title>Sistem Informasi Akademik::Daftar User</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="bootstrap533/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/styleku.css">
  <script src="bootstrap533/jquery/3.3.1/jquery-3.3.1.js"></script>
  <script src="bootstrap533/js/bootstrap.js"></script>
  <!-- Use fontawesome 5-->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
</head>

<body>
  <?php
  require "fungsi.php";
  require "head.html";

  // Konfigurasi
  $jmlDataPerHal = 3;

  // Cek apakah ada pencarian
  if (isset($_POST['cari'])) {
    $cari = $_POST['cari'];
    $sql = "SELECT * FROM user WHERE iduser LIKE '%$cari%' 
			OR username LIKE '%$cari%' 
			OR status LIKE '%$cari%'";
  } else {
    $sql = "SELECT * FROM user";
  }
  $qry = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
  $jmlData = mysqli_num_rows($qry);
  $jmlHal = ceil($jmlData / $jmlDataPerHal);

  $halAktif = isset($_GET['hal']) ? $_GET['hal'] : 1;
  $awalData = ($jmlDataPerHal * $halAktif) - $jmlDataPerHal;

  $kosong = $jmlData == 0;

  // Query dengan limit
  if (isset($_POST['cari'])) {
    $sql = "SELECT * FROM user WHERE iduser LIKE '%$cari%' 
			OR username LIKE '%$cari%' 
			OR status LIKE '%$cari%' 
			LIMIT $awalData, $jmlDataPerHal";
  } else {
    $sql = "SELECT * FROM user LIMIT $awalData, $jmlDataPerHal";
  }
  $hasil = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
  ?>
  <div class="utama">
    <h2 class="text-center">Daftar User</h2>
    <div class="text-center"><a href="prnUserPdf.php"><span class="fas fa-print">&nbsp;Print</span></a></div>
    <span class="float-left">
      <a class="btn btn-success" href="addUser.php">Tambah Data</a>
    </span>
    <span class="float-right">
      <form action="" method="post" class="form-inline">
        <button class="btn btn-success" type="submit">Cari</button>
        <input class="form-control mr-2 ml-2" type="text" name="cari" placeholder="Cari user..." autofocus autocomplete="off">
      </form>
    </span>
    <br><br>
    <ul class="pagination">
      <?php
      if ($halAktif > 1) {
        $back = $halAktif - 1;
        echo "<li class='page-item'><a class='page-link' href=?hal=$back>&laquo;</a></li>";
      }
      for ($i = 1; $i <= $jmlHal; $i++) {
        if ($i == $halAktif) {
          echo "<li class='page-item'><a class='page-link' href=?hal=$i style='font-weight:bold;color:red;'>$i</a></li>";
        } else {
          echo "<li class='page-item'><a class='page-link' href=?hal=$i>$i</a></li>";
        }
      }
      if ($halAktif < $jmlHal) {
        $forward = $halAktif + 1;
        echo "<li class='page-item'><a class='page-link' href=?hal=$forward>&raquo;</a></li>";
      }
      ?>
    </ul>
    <table class="table table-hover">
      <thead class="thead-light">
        <tr>
          <th>No.</th>
          <th>ID User</th>
          <th>Username</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($kosong) {
          echo '<tr><th colspan="5"><div class="alert alert-info text-center">Data tidak ada</div></th></tr>';
        } else {
          $no = $awalData + 1;
          while ($row = mysqli_fetch_assoc($hasil)) {
        ?>
            <tr>
              <td><?php echo $no; ?></td>
              <td><?php echo $row["iduser"]; ?></td>
              <td><?php echo $row["username"]; ?></td>
              <td><?php echo $row["status"]; ?></td>
              <td>
                <a class="btn btn-outline-primary btn-sm" href="editUser.php?kode=<?php echo $row['iduser']; ?>">Edit</a>
                <a class="btn btn-outline-danger btn-sm" href="hpsUser.php?kode=<?php echo $row["iduser"]; ?>" onclick="return confirm('Yakin dihapus?')">Hapus</a>
              </td>
            </tr>
        <?php
            $no++;
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</body>

</html>