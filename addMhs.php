<!DOCTYPE html>
<html lang="en">

<head>
	<title>Tambah Data Mahasiswa</title>
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
						<h3>Tambah Data Mahasiswa</h3>
					</div>
					<div class="card-body">
						<form method="post" action="sv_addMhs.php" enctype="multipart/form-data" id="addMhsForm">
							<div class="mb-3">
								<label for="nim" class="form-label">NIM:</label>
								<input type="text" class="form-control" id="nim" name="nim" maxlength="14" required>
								<span id="nimError" class="text-danger"></span>
							</div>

							<div class="mb-3">
								<label for="nama" class="form-label">Nama:</label>
								<input type="text" class="form-control" id="nama" name="nama" required>
							</div>

							<div class="mb-3">
								<label for="jurusan" class="form-label">Jurusan:</label>
								<select class="form-select" id="jurusan" name="jurusan" required>
									<option value="">-- Pilih Jurusan --</option>
									<option value="Teknik Informatika">A11 - Teknik Informatika</option>
									<option value="Sistem Informasi">A12 - Sistem Informasi</option>
									<option value="Desain Komunikasi Visual">A14 - Desain Komunikasi Visual</option>
									<option value="Teknik Informatika D3">A22 - Teknik Informatika D3</option>
									<option value="Broadcasting">A24 - Broadcasting</option>
									<option value="Film dan Televisi">A16 - Film dan Televisi</option>
									<option value="Animasi">A17 - Animasi</option>
									<option value="Ilmu Komunikasi">A18 - Ilmu Komunikasi</option>
								</select>
							</div>

							<div class="mb-3">
								<label for="foto" class="form-label">Foto:</label>
								<input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
								<small class="form-text text-muted">Upload foto dengan format JPG, PNG, atau GIF</small>
							</div>

							<div class="d-flex justify-content-between">
								<button type="submit" class="btn btn-primary">Simpan</button>
								<button type="button" class="btn btn-secondary" onclick="window.location.href='ajaxupdateMhs.php'">Batal</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			function checkNIMExists(nim) {
				$.ajax({
					url: 'cek_data_kembar.php',
					type: 'POST',
					data: {
						nim: nim
					},
					success: function(response) {
						if (response === 'exists') {
							showError("* NIM sudah terdaftar, silakan masukkan NIM lain.");
							$("#nim").val("").focus();
						} else {
							hideError();
							$("#nama").focus();
						}
					}
				});
			}

			function validateNIM() {
				var nim = $("#nim").val();
				var errorMsg = "";
				if (nim.trim() === "") {
					errorMsg = "* NIM tidak boleh kosong!";
					showError(errorMsg);
					return false;
				} else if (nim.length !== 14) {
					errorMsg = "* NIM harus terdiri dari 14 karakter (contoh: A12.2023.12345)";
					showError(errorMsg);
					return false;
				} else if (!/^[A-Z]\d{2}\.\d{4}\.\d{5}$/.test(nim)) {
					errorMsg = "* Format NIM tidak sesuai. Gunakan format: A12.2023.12345";
					showError(errorMsg);
					return false;
				}
				return true;
			}

			function showError(message) {
				$("#nimError").text(message).show();
			}

			function hideError() {
				$("#nimError").hide();
			}

			$("#nim").on('blur', function() {
				if (validateNIM()) {
					checkNIMExists($(this).val());
				}
			});

			$("#addMhsForm").on('submit', function(e) {
				var foto = $("#foto").val();
				if (!validateNIM() || !foto) {
					e.preventDefault();
					if (!foto) {
						alert("Foto mahasiswa harus diupload.");
					}
				}
			});
		});
	</script>
</body>

</html>