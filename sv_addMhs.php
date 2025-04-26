<?php
include "fungsi.php";

// Ambil data dari form
$nim = $_POST['nim'];
$nama = $_POST['nama'];
$jurusan = $_POST['jurusan'];

// Validasi format NIM
if (!preg_match('/^[A-Z]\d{2}\.\d{4}\.\d{5}$/', $nim)) {
    echo "<script>
            alert('Format NIM tidak sesuai! Gunakan format: A12.2023.12345');
            window.location.href='addMhs.php';
          </script>";
    exit;
}

// Cek apakah NIM sudah ada
$sql_check = "SELECT * FROM mhs WHERE nim = ?";
$stmt_check = $koneksi->prepare($sql_check);
$stmt_check->bind_param("s", $nim);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo "<script>
            alert('Maaf, NIM sudah ada dalam database.');
            window.location.href='addMhs.php';
          </script>";
    exit();
}
$stmt_check->close();

// Insert data dasar dulu (tanpa foto)
$stmt_insert = $koneksi->prepare("INSERT INTO mhs (nim, nama, jurusan, uploaded_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
$stmt_insert->bind_param("sss", $nim, $nama, $jurusan);

if (!$stmt_insert->execute()) {
    echo "<script>
            alert('Gagal menyimpan data mahasiswa: " . $stmt_insert->error . "');
            window.location.href='addMhs.php';
          </script>";
    exit();
}

$last_id = mysqli_insert_id($koneksi);
$stmt_insert->close();

// --- Mulai Upload Foto ---
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $mainFolder = "gambar_thumbnail/uploads/";
    $thumbFolder = "gambar_thumbnail/thumbs/";

    if (!file_exists($mainFolder)) mkdir($mainFolder, 0777, true);
    if (!file_exists($thumbFolder)) mkdir($thumbFolder, 0777, true);

    $file_name = basename($_FILES["foto"]["name"]);
    $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Validasi ekstensi
    if (!in_array($imageFileType, $allowed_extensions)) {
        echo "<script>
                alert('Hanya file JPG, JPEG, PNG, atau GIF yang diperbolehkan.');
                window.location.href='addMhs.php';
              </script>";
        exit();
    }

    // Validasi ukuran file (maks 1MB)
    if ($_FILES["foto"]["size"] > (1 * 1024 * 1024)) {
        echo "<script>
                alert('Ukuran file terlalu besar (maksimal 1MB).');
                window.location.href='addMhs.php';
              </script>";
        exit();
    }

    // Generate nama file baru
    $timestamp = time();
    $newFileName = $last_id . "_" . $timestamp . "." . $imageFileType;
    $targetFile = $mainFolder . $newFileName;
    $thumbFile = $thumbFolder . "thumb_" . $newFileName;
    $thumbnailName = "thumb_" . $newFileName;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
        // Buat thumbnail
        list($width, $height) = getimagesize($targetFile);
        $new_width = 200;
        $new_height = floor($height * ($new_width / $width));

        switch ($imageFileType) {
            case 'jpg':
            case 'jpeg':
                $src = imagecreatefromjpeg($targetFile);
                break;
            case 'png':
                $src = imagecreatefrompng($targetFile);
                break;
            case 'gif':
                $src = imagecreatefromgif($targetFile);
                break;
            default:
                $src = null;
        }

        if ($src) {
            $thumb = imagecreatetruecolor($new_width, $new_height);

            if ($imageFileType === 'png') {
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
            }

            imagecopyresampled($thumb, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            switch ($imageFileType) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($thumb, $thumbFile, 80);
                    break;
                case 'png':
                    imagepng($thumb, $thumbFile);
                    break;
                case 'gif':
                    imagegif($thumb, $thumbFile);
                    break;
            }

            imagedestroy($src);
            imagedestroy($thumb);
        }

        // Update database dengan info file dan thumbnail
        $stmt_update = $koneksi->prepare("UPDATE mhs 
            SET filename = ?, filepath = ?, thumbnail = ?, thumbpath = ?, width = ?, height = ? 
            WHERE id = ?");
        $stmt_update->bind_param("ssssiii", $newFileName, $targetFile, $thumbnailName, $thumbFile, $width, $height, $last_id);

        if ($stmt_update->execute()) {
            echo "<script>
                    alert('Data mahasiswa dan foto berhasil disimpan.');
                    window.location.href='ajaxUpdateMhs.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal mengupdate file: " . $stmt_update->error . "');
                    window.location.href='addMhs.php';
                  </script>";
        }
        $stmt_update->close();
    } else {
        echo "<script>
                alert('Gagal upload file foto.');
                window.location.href='addMhs.php';
              </script>";
    }
} else {
    // Jika tidak upload foto
    echo "<script>
            alert('Data mahasiswa berhasil disimpan tanpa foto.');
            window.location.href='ajaxUpdateMhs.php';
          </script>";
}
