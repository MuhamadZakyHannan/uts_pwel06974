<!DOCTYPE html>
<html>
	<head>
		<title>Sistem Informasi Akademik::Tambah Data Dosen</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css	 " href="bootstrap533/css/bootstrap.css">
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
			<h3>TAMBAH DATA DOSEN</h3>
			<div class="alert alert-success alert-dismissible" id="success" style="display:none;">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			</div>	
			<form method="post" action="sv_addDsn.php" enctype="multipart/form-data">
				<div class="form-group">
					<label for="npp">NPP:</label>
					<input class="form-control" type="text" name="npp" id="npp" required>
				</div>
				<div class="form-group">
					<label for="namadosen">Nama:</label>
					<input class="form-control" type="text" name="namadosen" id="namadosen">
				</div>
				<div class="form-group">
					<label for="homebase">Homebase:</label>
					<input class="form-control" type="text" name="homebase" id="homebase">
				</div>	
				<div>		
					<button type="submit" class="btn btn-primary" value="Simpan">Simpan</button>
				</div>
			</form>
		</div>
	</body>
</html>