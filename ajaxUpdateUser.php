<!DOCTYPE html>
<html>

<head>
    <title>Sistem Informasi Akademik::Daftar User</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap533/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/styleku.css">
    <script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
    <script src="bootstrap4/js/bootstrap.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
</head>

<body>
    <?php
    require "fungsi.php";
    require "head.html";

    $jmlDataPerHal = 5;

    $cari = isset($_POST['cari']) ? $_POST['cari'] : '';
    $sql = "SELECT * FROM user";
    if (!empty($cari)) {
        $sql .= " WHERE iduser LIKE '%$cari%' OR username LIKE '%$cari%' OR status LIKE '%$cari%'";
    }

    $qry = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
    $jmlData = mysqli_num_rows($qry);
    $jmlHal = ceil($jmlData / $jmlDataPerHal);
    $halAktif = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;
    $awalData = ($jmlDataPerHal * $halAktif) - $jmlDataPerHal;
    $kosong = ($jmlData == 0);

    $sql .= " LIMIT $awalData, $jmlDataPerHal";
    $hasil = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
    ?>

    <div class="utama">
        <h2 class="text-center">Daftar Pengguna</h2>
        <div class="text-center">
            <a href="printuser.php"><span class="fas fa-print">&nbsp;Print</span></a>
        </div>
        <span class="float-left">
            <a class="btn btn-success" href="addUser.php">Tambah Data</a>
        </span>
        <span class="float-right">
            <form action="" method="post" class="form-inline">
                <button class="btn btn-success" type="submit">Cari</button>
                <input class="form-control mr-2 ml-2" type="text" id="keyword" name="cari" placeholder="Cari pengguna..." autofocus autocomplete="off">
            </form>
        </span>
        <br><br>


        <ul class="pagination">
            <?php
            if ($halAktif > 1) {
                echo "<li class='page-item'><a class='page-link' href='?hal=" . ($halAktif - 1) . "'>&laquo;</a></li>";
            }
            for ($i = 1; $i <= $jmlHal; $i++) {
                $active = ($i == $halAktif) ? "style='font-weight:bold;color:red;'" : "";
                echo "<li class='page-item'><a class='page-link' href='?hal=$i' $active>$i</a></li>";
            }
            if ($halAktif < $jmlHal) {
                echo "<li class='page-item'><a class='page-link' href='?hal=" . ($halAktif + 1) . "'>&raquo;</a></li>";
            }
            ?>
        </ul>

        <div id="container">
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
                        echo "<tr><th colspan='5' class='text-center'>Data tidak ada</th></tr>";
                    } else {
                        $no = $awalData + 1;
                        while ($row = mysqli_fetch_assoc($hasil)) {
                            echo "<tr>
                                    <td>$no</td>
                                    <td>{$row["iduser"]}</td>
                                    <td>{$row["username"]}</td>
                                    <td>{$row["status"]}</td>
                                    <td>
                                        <a class='btn btn-outline-primary btn-sm' href='editUser.php?kode={$row["iduser"]}'>Edit</a>
                                        <a class='btn btn-outline-danger btn-sm' href='hpsUser.php?kode={$row["iduser"]}' onclick='return confirm(\"Yakin dihapus?\")'>Hapus</a>
                                    </td>
                                  </tr>";
                            $no++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="js/scriptUser.js"></script>
</body>

</html>