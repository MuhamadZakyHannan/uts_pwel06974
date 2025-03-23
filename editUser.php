<!DOCTYPE html>
<html lang="id">

<head>
  <title>Sistem Informasi Akademik :: Edit Data Pengguna</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="bootstrap533/css/bootstrap.css">
  <link rel="stylesheet" href="css/styleku.css">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <?php
  require "fungsi.php";
  require "head.html";

  // Pastikan 'kode' tersedia di URL
  if (!isset($_GET['kode']) || empty($_GET['kode'])) {
    die("Kode pengguna tidak valid.");
  }

  $iduser = $_GET['kode'];

  // Menggunakan Prepared Statement untuk keamanan
  $stmt = $koneksi->prepare("SELECT * FROM user WHERE iduser = ?");
  $stmt->bind_param("s", $iduser);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    die("Data pengguna tidak ditemukan.");
  }

  $row = $result->fetch_assoc();
  ?>

  <div class="utama">
    <h2 class="mb-3 text-center">EDIT DATA PENGGUNA</h2>
    <div class="row">
      <div class="col-sm-9">
        <form id="editForm" enctype="multipart/form-data" method="post" action="sv_editUser.php">
          <div class="form-group">
            <label for="iduser">ID User:</label>
            <input class="form-control" type="text" name="iduser" id="iduser" value="<?php echo htmlspecialchars($row['iduser']); ?>" readonly>
          </div>
          <div class="form-group">
            <label for="username">Username:</label>
            <input class="form-control" type="text" name="username" id="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
          </div>
          <div class="form-group">
            <label for="status">Status:</label>
            <input class="form-control" type="text" name="status" id="status" value="<?php echo htmlspecialchars($row['status']); ?>" required>
          </div>
          <div>
            <button class="btn btn-primary" type="submit">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="js/alert.js"></script>
  <script src="js/editUser.js"></script>
</body>

</html>