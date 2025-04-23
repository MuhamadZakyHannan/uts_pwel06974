
<!DOCTYPE html>
<html>

<head>
	<title>Sistem Informasi Akademik::Edit Data Mahasiswa</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap533/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/styleku.css">
	<script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
	<script src="bootstrap4/js/bootstrap.js"></script>
	<style>
		.error {
			color: red;
			font-size: 0.9em;
			display: none;
		}

		#nim {
			width: 150px;
		}

		#ajaxResponse {
			margin-top: 15px;
		}
	</style>
</head>

<body>
	<?php
	require "fungsi.php";

	$id = $_GET['id'];
	$sql = "SELECT * FROM mhs WHERE id='$id'";
	$qry = mysqli_query($koneksi, $sql);
	$row = mysqli_fetch_assoc($qry);
	?>

	<div class="container">
		<div class="card">
			<div class="card-header">
				<h3>Edit Data Mahasiswa</h3>
			</div>
			<div class="card-body">
				<form method="post" action="sv_editMhs.php" enctype="multipart/form-data">
					<div class="form-group">
						<label for="nim">NIM:</label>
						<input type="text" class="form-control" id="nim" name="nim" maxlength="14"
							value="<?php echo $row['nim'] ?>" readonly>
						<span id="nimError" class="error"></span>
					</div>

					<div class="form-group">
						<label for="nama">Nama:</label>
						<input type="text" class="form-control" id="nama" name="nama"
							value="<?php echo $row['nama'] ?>" required>
					</div>

					<div class="form-group">
						<label for="jurusan">Jurusan:</label>
						<select class="form-control" id="jurusan" name="jurusan" required>
							<option value="">Pilih Jurusan</option>
							<option value="Teknik Informatika" <?php echo ($row['jurusan'] == 'Teknik Informatika') ? 'selected' : ''; ?>>
								Teknik Informatika
							</option>
							<option value="Sistem Informasi" <?php echo ($row['jurusan'] == 'Sistem Informasi') ? 'selected' : ''; ?>>
								Sistem Informasi
							</option>
							<option value="Teknik Komputer" <?php echo ($row['jurusan'] == 'Teknik Komputer') ? 'selected' : ''; ?>>
								Teknik Komputer
							</option>
							<option value="Manajemen" <?php echo ($row['jurusan'] == 'Manajemen') ? 'selected' : ''; ?>>
								Manajemen
							</option>
						</select>
					</div>

					<div class="form-group">
						<label>Foto Saat Ini:</label>
						<div class="mb-2">
							<img src="<?php echo $row['filepath'] ?>" class="img-thumbnail" style="max-width: 200px;">
						</div>
						<label for="foto">Ganti Foto:</label>
						<input type="file" class="form-control" id="foto" name="foto" accept="image/*">
						<small class="form-text text-muted">Upload foto dengan format JPG, PNG, atau GIF</small>
					</div>

					<div class="form-group">
						<input type="hidden" name="id" value="<?php echo $id ?>">
						<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
						<a href="ajaxUpdateMhs.php" class="btn btn-secondary">Batal</a>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			$('form').on('submit', function(e) {
				if (!$('#nama').val() || !$('#jurusan').val()) {
					e.preventDefault();
					alert('Semua field harus diisi!');
				}
			});
		});
	</script>
</body>

</html>
