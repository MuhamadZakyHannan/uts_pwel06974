
<!DOCTYPE html>
<html>

<head>
	<title>Sistem Informasi Akademik::Tambah Data Mahasiswa</title>
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
	<div class="container">
		<div class="card">
			<div class="card-header">
				<h3>Tambah Data Mahasiswa</h3>
			</div>
			<div class="card-body">
				<form method="post" action="sv_addMhs.php" enctype="multipart/form-data">
					<div class="form-group">
						<label for="nim">NIM:</label>
						<input type="text" class="form-control" id="nim" name="nim" maxlength="14" required>
						<span id="nimError" class="error"></span>
					</div>

					<div class="form-group">
						<label for="nama">Nama:</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>

					<div class="form-group">
						<label for="jurusan">Jurusan:</label>
						<select class="form-control" id="jurusan" name="jurusan" required>
							<option value="">Pilih Jurusan</option>
							<option value="Teknik Informatika">Teknik Informatika</option>
							<option value="Sistem Informasi">Sistem Informasi</option>
							<option value="Teknik Komputer">Teknik Komputer</option>
							<option value="Manajemen">Manajemen</option>
						</select>
					</div>

					<div class="form-group">
						<label for="foto">Foto:</label>
						<input type="file" class="form-control" id="foto" name="foto" accept="image/*">
						<small class="form-text text-muted">Upload foto dengan format JPG, PNG, atau GIF</small>
					</div>

					<div class="form-group mt-3">
						<button type="submit" class="btn btn-primary">Simpan</button>
						<button type="button" class="btn btn-secondary" onclick="window.location.href='ajaxupdateMhs.php'">Batal</button>
					</div>
				</form>
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
							showError("* Data sudah ada, silahkan isikan yang lain");
							$("#nim").val("").focus();
							return false;
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

			$("form").on('submit', function(e) {
				if (!validateNIM()) {
					e.preventDefault();
					return false;
				}
			});
		});
	</script>
</body>

</html>
`