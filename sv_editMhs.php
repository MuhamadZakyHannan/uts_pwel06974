<?php
require "fungsi.php";

// Cek apakah ada kiriman form dari method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$id = $_POST["id"];
	$nama = htmlspecialchars($_POST["nama"]);
	$jurusan = htmlspecialchars($_POST["jurusan"]);
	$uploadOk = 1;

	// Cek apakah ada foto yang diupload
	if (!isset($_FILES["foto"]) || $_FILES["foto"]["error"] == 4) {
		// Update data tanpa foto jika tidak ada file yang diupload
		$sql = "UPDATE mhs SET 
                nama = '$nama',
                jurusan = '$jurusan'
                WHERE id = '$id'";

		if (mysqli_query($koneksi, $sql)) {
			echo "<script>
                    alert('Data berhasil diupdate');
                    window.location.href='ajaxUpdateMhs.php';
                  </script>";
		} else {
			echo "<script>
                    alert('Gagal mengupdate data: " . mysqli_error($koneksi) . "');
                    window.location.href='editMhs.php?kode=" . $id . "'; 
                  </script>";
		}
	} else {
		// Jika ada upload foto baru
		$mainFolder = "gambar_thumbnail/uploads/";
		$thumbFolder = "gambar_thumbnail/thumbs/";

		// Get old file info
		$sql = "SELECT filename, filepath, thumbpath, uploaded_at FROM mhs WHERE id='$id'";
		$result = mysqli_query($koneksi, $sql);
		$row = mysqli_fetch_assoc($result);

		// Jika belum ada foto sebelumnya, buat foto baru
		if (!$row) {
			$newFileName = $id . "_" . time() . ".jpg";
		} else {
			$newFileName = $id . "_edit_" . time() . ".jpg";
		}

		$targetFile = $mainFolder . $newFileName;
		$thumbFile = $thumbFolder . "thumb_" . $newFileName;

		// Proses upload file foto
		$fileExtension = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));

		// Validasi ukuran file
		if ($_FILES["foto"]["size"] > 2000000) {
			echo "<script>
                    alert('File terlalu besar (max 2MB)');
                    window.location.href='editMhs.php?kode=" . $id . "';
                  </script>";
			exit();
		}

		// Validasi format file
		if ($fileExtension != "jpg" && $fileExtension != "jpeg" && $fileExtension != "png" && $fileExtension != "gif") {
			echo "<script>
                    alert('Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan');
                    window.location.href='editMhs.php?kode=" . $id . "';
                  </script>";
			exit();
		}

		// Pindahkan file ke folder tujuan
		if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
			// Membuat thumbnail
			list($width, $height) = getimagesize($targetFile);
			$new_width = 200;
			$new_height = floor($height * ($new_width / $width));

			$thumb = imagecreatetruecolor($new_width, $new_height);

			switch ($fileExtension) {
				case 'jpg':
				case 'jpeg':
					$source = imagecreatefromjpeg($targetFile);
					break;
				case 'png':
					$source = imagecreatefrompng($targetFile);
					imagealphablending($thumb, false);
					imagesavealpha($thumb, true);
					break;
				case 'gif':
					$source = imagecreatefromgif($targetFile);
					break;
			}

			imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			switch ($fileExtension) {
				case 'jpg':
				case 'jpeg':
					imagejpeg($thumb, $thumbFile, 80);
					break;
				case 'png':
					imagepng($thumb, $thumbFile, 9);
					break;
				case 'gif':
					imagegif($thumb, $thumbFile);
					break;
			}

			imagedestroy($source);
			imagedestroy($thumb);

			// Hapus file lama jika sedang edit
			if ($row) {
				if (file_exists($row['filepath'])) {
					unlink($row['filepath']);
				}
				if (file_exists($row['thumbpath'])) {
					unlink($row['thumbpath']);
				}
			}

			// Update data dengan foto baru
			$sql = "UPDATE mhs SET 
                    nama = '$nama',
                    jurusan = '$jurusan',
                    filename = '$newFileName',
                    filepath = '$targetFile',
                    thumbnail = '" . basename($thumbFile) . "',
                    thumbpath = '$thumbFile',
                    width = $new_width,
                    height = $new_height,
                    uploaded_at = NOW()
                    WHERE id = '$id'";

			if (mysqli_query($koneksi, $sql)) {
				echo "<script>
                        alert('Data dan foto berhasil diupdate');
                        window.location.href='ajaxUpdateMhs.php';
                      </script>";
			} else {
				echo "<script>
                        alert('Gagal mengupdate data: " . mysqli_error($koneksi) . "');
                        window.location.href='editMhs.php?kode=" . $id . "'; 
                      </script>";
			}
		} else {
			echo "<script>
                    alert('Gagal mengupload file');
                    window.location.href='editMhs.php?kode=" . $id . "';
                  </script>";
		}
	}
} else {
	header("location:ajaxUpdateMhs.php");
}

mysqli_close($koneksi);
