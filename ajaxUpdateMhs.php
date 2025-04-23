
<?php
require "fungsi.php";

$jmlDataPerHal = 5;
$kosong = false;

$sqlCount = "SELECT COUNT(*) as total FROM mhs";
$resultCount = mysqli_query($koneksi, $sqlCount);
$data = mysqli_fetch_assoc($resultCount);
$jmlData = $data['total'];

$jmlHal = ceil($jmlData / $jmlDataPerHal);

if (isset($_GET['hal'])) {
	$halAktif = $_GET['hal'];
} else {
	$halAktif = 1;
}

$awalData = ($jmlDataPerHal * $halAktif) - $jmlDataPerHal;

if ($jmlData == 0) {
	$kosong = true;
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Sistem Informasi Akademik::Daftar Mahasiswa</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/styleku.css">

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
</head>

<body>
	<div class="container">
		<div class="card">
			<div class="card-header">
				<h3>Data Mahasiswa</h3>
			</div>
			<div class="card-body">
				<button type="button" class="btn btn-primary mb-3" onclick="window.location.href='addMhs.php'">Tambah</button>

				<form method="post">
					<div class="input-group mb-3">
						<input type="text" name="cari" class="form-control" placeholder="Ketik nama atau NIM...">
						<button class="btn btn-primary" type="submit">Cari</button>
					</div>
				</form>

				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>NIM</th>
							<th>Nama</th>
							<th>Jurusan</th>
							<th>Foto</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (!$kosong) {
							if (isset($_POST['cari'])) {
								$cari = $_POST['cari'];
								$sql = "SELECT * FROM mhs WHERE 
                                    nim LIKE '%$cari%' OR 
                                    nama LIKE '%$cari%' OR  
                                    jurusan LIKE '%$cari%' 
                                    ORDER BY id 
                                    LIMIT $awalData, $jmlDataPerHal";
							} else {
								$sql = "SELECT * FROM mhs ORDER BY id LIMIT $awalData, $jmlDataPerHal";
							}

							$hasil = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
							while ($row = mysqli_fetch_assoc($hasil)) {
						?>
								<tr>
									<td><?php echo $row["id"] ?></td>
									<td><?php echo $row["nim"] ?></td>
									<td><?php echo $row["nama"] ?></td>
									<td><?php echo $row["jurusan"] ?></td>
									<td>
										<?php
										if ($row["filepath"]) {
											echo "<img src='" . $row["filepath"] . "' height='50'>";
										} else {
											echo "No Image";
										}
										?>
									</td>
									<td>
										<a class="btn btn-outline-primary btn-sm" href="editMhs.php?id=<?php echo $row['id'] ?>">Ubah</a>
										<a class="btn btn-outline-danger btn-sm" href="hpsMhs.php?id=<?php echo $row['id'] ?>" onclick="return confirm('Yakin menghapus data ini?')">Hapus</a>
									</td>
								</tr>
						<?php
							}
						}
						?>
					</tbody>
				</table>

				<nav aria-label="Page navigation">
					<ul class="pagination justify-content-center">
						<?php
						for ($i = 1; $i <= $jmlHal; $i++) {
							if ($i == $halAktif) {
								echo "<li class='page-item active'><a class='page-link' href='?hal=$i'>$i</a></li>";
							} else {
								echo "<li class='page-item'><a class='page-link' href='?hal=$i'>$i</a></li>";
							}
						}
						?>
					</ul>
				</nav>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {
			$('input[name="cari"]').keyup(function() {
				var keyword = $(this).val();
				$.ajax({
					type: "POST",
					url: "searchMhs.php",
					data: {
						keyword: keyword,
						halAktif: <?php echo $halAktif; ?>,
						jmlDataPerHal: <?php echo $jmlDataPerHal; ?>
					},
					success: function(data) {
						$("tbody").html(data);
					}
				});
			});

			$('form').submit(function(e) {
				e.preventDefault();
			});
		});
	</script>
</body>

</html>
