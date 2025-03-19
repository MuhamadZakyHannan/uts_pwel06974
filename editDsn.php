<!DOCTYPE html>
<html>
<head>
	<title>Sistem Informasi Akademik::Edit Data Dosen</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap533/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/styleku.css">
	<script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
	<script src="bootstrap4/js/bootstrap.js"></script>
</head>
<body>
    <?php
    require "fungsi.php";
    require "head.html";
    $npp = $_GET['kode'];
    $sql = "select * from dosen where npp='$npp'";
    $qry = mysqli_query($koneksi, $sql);
    $row = mysqli_fetch_assoc($qry);
    ?>
    <div class="utama">
        <h2 class="mb-3 text-center">EDIT DATA DOSEN</h2>
        <div class="row">
            <div class="col-sm-9">
                <form id="editForm" enctype="multipart/form-data" method="post" action="sv_editDsn.php">
                    <div class="form-group">
                        <label for="npp">NPP:</label>
                        <input class="form-control" type="text" name="npp" id="npp" value="<?php echo $row['npp']?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="namadosen">Nama:</label>
                        <input class="form-control" type="text" name="namadosen" id="namadosen" value="<?php echo $row['namadosen']?>">
                    </div>
                    <div class="form-group">
                        <label for="homebase">Homebase:</label>
                        <input class="form-control" type="text" name="homebase" id="homebase" value="<?php echo $row['homebase']?>">
                    </div>
                    <div>
                        <button class="btn btn-primary" type="submit" id="submit">Simpan</button>
                    </div>
                    <input type="hidden" name="idDosen" id="idDosen" value="<?php echo $idDosen?>">
                </form>
            </div>
        </div>
    </div>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="js/alert.js"></script>
    <script src="js/editDsn.js"></script>
</body>
</html>
