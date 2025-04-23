
<?php
require "fungsi.php";
require "head.html";

$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$jmlDataPerHal = 2;

// Base query
$sql = "SELECT * FROM mhs WHERE 
        nim LIKE '%$keyword%' OR 
        nama LIKE '%$keyword%' OR 
        jurusan LIKE '%$keyword%'";

$qry = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
$jmlData = mysqli_num_rows($qry);
$jmlHal = ceil($jmlData / $jmlDataPerHal);
$halAktif = isset($_GET['hal']) ? $_GET['hal'] : 1;
$awalData = ($jmlDataPerHal * $halAktif) - $jmlDataPerHal;

// Query with limit
$sql .= " LIMIT $awalData, $jmlDataPerHal";
$hasil = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
?>

<!DOCTYPE html>
<html>

<head>
	<title>Sistem Informasi Akademik::Daftar Mahasiswa</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap533/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/styleku.css">
	<script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
	<script src="bootstrap4/js/bootstrap.js"></script>
</head>

<body>
	<div class="container">
		<div class="card">
			<div class="card-header">
				<h3>Daftar Mahasiswa</h3>
			</div>
			<div class="card-body">
				<!-- Form pencarian -->
				<form class="form-inline mb-3">
					<input class="form-control mr-2" type="text" id="keyword" name="keyword" value="<?php echo $keyword; ?>" placeholder="Masukkan keyword" autocomplete="off">
					<button class="btn btn-primary" type="submit">Cari</button>
				</form>

				<!-- Tabel data mahasiswa -->
				<table class="table table-hover">
					<thead class="thead-light">
						<tr>
							<th>No.</th>
							<th>NIM</th>
							<th>Nama</th>
							<th>Jurusan</th>
							<th>Foto</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (mysqli_num_rows($hasil) > 0) {
							$no = $awalData + 1;
							while ($row = mysqli_fetch_assoc($hasil)) {
						?>
								<tr>
									<td><?php echo $no++; ?></td>
									<td><?php echo $row["nim"]; ?></td>
									<td><?php echo $row["nama"]; ?></td>
									<td><?php echo $row["jurusan"]; ?></td>
									<td>
										<img src="<?php echo $row['thumbpath']; ?>" height="50" class="img-thumbnail">
									</td>
									<td>
										<a class="btn btn-outline-primary btn-sm" href="editMhs.php?kode=<?php echo $row['id']; ?>">Edit</a>
										<a class="btn btn-outline-danger btn-sm" href="hpsMhs.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
									</td>
								</tr>
						<?php
							}
						} else {
							echo "<tr><td colspan='6' class='text-center'>Tidak ada data yang ditemukan</td></tr>";
						}
						?>
					</tbody>
				</table>

				<!-- Pagination -->
				<?php if ($jmlHal > 1) : ?>
					<nav aria-label="Page navigation">
						<ul class="pagination justify-content-center">
							<?php
							for ($i = 1; $i <= $jmlHal; $i++) {
								$aktif = ($i == $halAktif) ? "active" : "";
							?>
								<li class="page-item <?php echo $aktif; ?>">
									<a class="page-link" href="?hal=<?php echo $i; ?>&keyword=<?php echo $keyword; ?>"><?php echo $i; ?></a>
								</li>
							<?php
							}
							?>
						</ul>
					</nav>
				<?php endif; ?>

				<!-- Tombol Tambah Data -->
				<a href="addMhs.php" class="btn btn-success">Tambah Data</a>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			// Auto submit form when search input changes
			$('#keyword').on('keyup', function() {
				$('form').submit();
			});
		});
	</script>
</body>

</html>
