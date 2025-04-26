<?php
require "fungsi.php";
$jmlDataPerHal = 5;
$sqlCount = "SELECT COUNT(*) as total FROM mhs";
$resultCount = mysqli_query($koneksi, $sqlCount);
$data = mysqli_fetch_assoc($resultCount);
$jmlData = $data['total'];
$jmlHal = ceil($jmlData / $jmlDataPerHal);
$halAktif = isset($_GET['hal']) ? $_GET['hal'] : 1;
$awalData = ($jmlDataPerHal * $halAktif) - $jmlDataPerHal;
?>

<!DOCTYPE html>
<html lang="id">

<head>
	<title>Data Mahasiswa</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="bootstrap533/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/styleku.css">
	<script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
	<script src="bootstrap4/js/bootstrap.js"></script>
	<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
	<style>
		body {
			background: linear-gradient(135deg, #e3f2fd, #ffffff);
			min-height: 100vh;
		}

		.container {
			background-color: #ffffff;
			border-radius: 10px;
			padding: 30px;
			box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
		}

		/* Hapus garis-garis tabel */
		table {
			border-collapse: collapse;
			width: 100%;
		}

		table th {
			background-color: #0d6efd;
			color: #ffffff;
			text-align: center;
			padding: 12px;
			border: none;
		}

		table td {
			padding: 12px;
			border: none;
			border-bottom: 1px solid #f1f1f1;
			vertical-align: middle !important;
		}

		tr:hover td {
			background-color: #f0f8ff;
		}

		.fade-in {
			animation: fadeIn 0.5s ease-in-out;
		}

		@keyframes fadeIn {
			from {
				opacity: 0;
			}

			to {
				opacity: 1;
			}
		}

		.btn-primary {
			background-color: #0d6efd;
			border-color: #0d6efd;
		}
	</style>
</head>

<body>
	<div class="container py-5 my-4">
		<div class="text-center mb-5">
			<h1 class="fw-bold">Data Mahasiswa</h1>
		</div>

		<div class="row mb-4">
			<div class="col-md-3 mb-2">
				<a href="addMhs.php" class="btn btn-primary w-100">Tambah Data</a>
			</div>
			<div class="col-md-4 mb-2">
				<input type="text" name="cari" class="form-control" placeholder="Cari nama atau NIM...">
			</div>
			<div class="col-md-3 mb-2">
				<input type="date" name="tanggal" class="form-control" id="tanggal">
			</div>
			<div class="col-md-2 mb-2">
				<button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilter()">Reset</button>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table align-middle">
				<thead>
					<tr>
						<th>No</th>
						<th>NIM</th>
						<th>Nama</th>
						<th>Jurusan</th>
						<th>Foto</th>
						<th>Tanggal Upload</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody id="tableData">
					<!-- Data mahasiswa akan dimuat di sini -->
				</tbody>
			</table>
		</div>

		<nav class="mt-5">
			<ul class="pagination justify-content-center gap-1">
				<?php
				for ($i = 1; $i <= $jmlHal; $i++) {
					$activeClass = ($i == $halAktif) ? 'active' : '';
					echo "<li class='page-item $activeClass'><a class='page-link' href='?hal=$i'>$i</a></li>";
				}
				?>
			</ul>
		</nav>
	</div>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function() {
			loadData();

			function showLoading() {
				$('#tableData').html('<tr><td colspan="7" class="text-center"><div class="d-flex justify-content-center align-items-center"><div class="spinner-border text-primary me-2" role="status"></div><span>Mencari...</span></div></td></tr>');
			}

			function loadData() {
				showLoading();
				let keyword = $('input[name="cari"]').val();
				let tanggal = $('input[name="tanggal"]').val();

				$.ajax({
					type: "POST",
					url: "searchMhs.php",
					data: {
						keyword: keyword,
						tanggal: tanggal,
						halAktif: <?php echo $halAktif; ?>,
						jmlDataPerHal: <?php echo $jmlDataPerHal; ?>
					},
					success: function(response) {
						if (response.trim() === '') {
							$("#tableData").html('<tr><td colspan="7" class="text-center py-4 fade-in"><i class="fas fa-search me-2"></i>Data tidak ditemukan</td></tr>');
						} else {
							$("#tableData").hide().html(response).fadeIn(300);
						}
					},
					error: function() {
						alert('Terjadi kesalahan saat mengambil data');
					}
				});
			}

			let searchTimeout;
			$('input[name="cari"]').on('keyup', function() {
				clearTimeout(searchTimeout);
				searchTimeout = setTimeout(function() {
					loadData();
				}, 500);
			});

			$('input[name="tanggal"]').on('change', function() {
				loadData();
			});
		});

		function resetFilter() {
			$('input[name="cari"]').val('');
			$('input[name="tanggal"]').val('');
			loadData();
		}
	</script>
</body>

</html>