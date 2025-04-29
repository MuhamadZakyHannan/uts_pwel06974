<!DOCTYPE html>
<html lang="en">

<head>
	<title>Edit Data Mahasiswa</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="bootstrap533/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
	<div class="container mt-5">
		<div class="row justify-content-center">
			<div class="col-lg-8 col-md-10">
				<div class="card shadow-lg border-0 rounded">
					<div class="card-header bg-primary text-white text-center">
						<h3>Edit Data Mahasiswa</h3>
					</div>
					<div class="card-body">
						<?php
						require "fungsi.php";
						$id = $_GET['id'];
						$sql = "SELECT * FROM mhs WHERE id='$id'";
						$qry = mysqli_query($koneksi, $sql);
						$row = mysqli_fetch_assoc($qry);
						?>
						<form method="post" action="sv_editMhs.php" enctype="multipart/form-data">
							<div class="mb-3">
								<label for="nim" class="form-label">NIM:</label>
								<input type="text" class="form-control" id="nim" name="nim" value="<?php echo $row['nim'] ?>" readonly>
							</div>

							<div class="mb-3">
								<label for="nama" class="form-label">Nama:</label>
								<input type="text" class="form-control" id="nama" name="nama" value="<?php echo $row['nama'] ?>" required>
							</div>

							<div class="mb-3">
								<label for="jurusan" class="form-label">Jurusan:</label>
								<select class="form-select" id="jurusan" name="jurusan" required>
									<option value="">-- Pilih Jurusan --</option>
									<option value="Teknik Informatika" <?php echo ($row['jurusan'] == 'Teknik Informatika') ? 'selected' : ''; ?>>A11 - Teknik Informatika</option>
									<option value="Sistem Informasi" <?php echo ($row['jurusan'] == 'Sistem Informasi') ? 'selected' : ''; ?>>A12 - Sistem Informasi</option>
									<option value="Desain Komunikasi Visual" <?php echo ($row['jurusan'] == 'Desain Komunikasi Visual') ? 'selected' : ''; ?>>A14 - Desain Komunikasi Visual</option>
									<option value="Teknik Informatika D3" <?php echo ($row['jurusan'] == 'Teknik Informatika D3') ? 'selected' : ''; ?>>A22 - Teknik Informatika D3</option>
									<option value="Broadcasting" <?php echo ($row['jurusan'] == 'Broadcasting') ? 'selected' : ''; ?>>A24 - Broadcasting</option>
									<option value="Film dan Televisi" <?php echo ($row['jurusan'] == 'Film dan Televisi') ? 'selected' : ''; ?>>A16 - Film dan Televisi</option>
									<option value="Animasi" <?php echo ($row['jurusan'] == 'Animasi') ? 'selected' : ''; ?>>A17 - Animasi</option>
									<option value="Ilmu Komunikasi" <?php echo ($row['jurusan'] == 'Ilmu Komunikasi') ? 'selected' : ''; ?>>A18 - Ilmu Komunikasi</option>
								</select>
							</div>

							<div class="mb-3">
								<label class="form-label">Foto Saat Ini:</label>
								<div class="mb-2">
									<img src="<?php echo $row['filepath'] ?>" class="img-thumbnail" style="max-width: 200px;">
								</div>
								<label for="foto" class="form-label">Ganti Foto:</label>
								<input type="file" class="form-control" id="foto" name="foto" accept="image/*">
								<small class="form-text text-muted">Upload foto dengan format JPG, PNG, atau GIF</small>
							</div>

							<div class="d-flex justify-content-between">
								<input type="hidden" name="id" value="<?php echo $id ?>">
								<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
								<a href="ajaxUpdateMhs.php" class="btn btn-secondary">Batal</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		// Add live preview for file upload (optional)
		$('#foto').change(function(e) {
			let reader = new FileReader();
			reader.onload = function(e) {
				$('.img-thumbnail').attr('src', e.target.result);
			}
			reader.readAsDataURL(this.files[0]);
		});
	</script>
</body>

</html>