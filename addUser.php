<!DOCTYPE html>
<html>

<head>
  <title>Sistem Informasi Akademik::Tambah Data Pengguna</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="bootstrap533/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/styleku.css">
  <script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
  <script src="bootstrap4/js/bootstrap.js"></script>
</head>

<body>
  <?php
  require "head.html";
  ?>
  <div class="utama">
    <br><br><br>
    <h3>TAMBAH DATA PENGGUNA</h3>
    <div class="alert alert-success alert-dismissible" id="success" style="display:none;">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    </div>
    <form method="post" action="sv_addUser.php" enctype="multipart/form-data">
      <div class="form-group">
        <label for="iduser">ID User:</label>
        <input class="form-control" type="number" name="iduser" id="iduser" required>
      </div>
      <div class="form-group">
        <label for="username">Username:</label>
        <input class="form-control" type="text" name="username" id="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input class="form-control" type="password" name="password" id="password" required>
      </div>
      <div class="form-group">
        <label for="status">Status:</label>
        <input class="form-control" type="text" name="status" id="status" maxlength="5" required>
      </div>
      <div>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</body>

</html>