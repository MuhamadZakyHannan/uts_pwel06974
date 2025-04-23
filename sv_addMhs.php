Here's the combined code with organized folder structure:

```php
<?php
include "fungsi.php";

// Ambil data dari form
$nim = $_POST['nim'];
$nama = $_POST['nama'];
$jurusan = $_POST['jurusan'];

// Validasi format NIM di server-side
if (!preg_match('/^[A-Z]\d{2}\.\d{4}\.\d{5}$/', $nim)) {
    echo "<script>
            alert('Format NIM tidak sesuai! Gunakan format: A12.2023.12345');
            window.location.href='addMhs.php';
          </script>";
    exit;
}

// Pemeriksaan NIM dalam database
$sql_check = "SELECT * FROM mhs WHERE nim='$nim'";
$query_check = mysqli_query($koneksi, $sql_check) or die(mysqli_error($koneksi));

if (mysqli_num_rows($query_check) > 0) {
    echo "<script>
            alert('Maaf, NIM sudah ada dalam database.');
            window.location.href='addMhs.php';
          </script>";
    exit();
}

// Konfigurasi upload
$uploadOk = 1;
$mainFolder = "gambar_thumbnail/uploads/";
$thumbFolder = "gambar_thumbnail/thumbs/";

// Buat folder jika belum ada
if (!file_exists($mainFolder)) mkdir($mainFolder, 0777, true);
if (!file_exists($thumbFolder)) mkdir($thumbFolder, 0777, true);

// Validasi file
if (isset($_FILES['foto'])) {
    $file_name = basename($_FILES["foto"]["name"]);
    $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Check if image file is actual image
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if ($check === false) {
        echo "<script>
                alert('File bukan gambar.');
                window.location.href='addMhs.php';
              </script>";
        exit();
    }

    // Check file size (1MB limit)
    if ($_FILES["foto"]["size"] > 1 * 1024 * 1024) {
        echo "<script>
                alert('Ukuran file terlalu besar (maksimal 1MB).');
                window.location.href='addMhs.php';
              </script>";
        exit();
    }

    // Allow certain file formats
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed)) {
        echo "<script>
                alert('Hanya file JPG, JPEG, PNG dan GIF yang diperbolehkan.');
                window.location.href='addMhs.php';
              </script>";
        exit();
    }

    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES["foto"]["tmp_name"]);
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif'])) {
        echo "<script>
                alert('Tipe MIME tidak sesuai.');
                window.location.href='addMhs.php';
              </script>";
        exit();
    }

    // Generate unique filename
    $newFileName = $nim . "_" . time() . "." . $imageFileType;
    $targetFile = $mainFolder . $newFileName;
    $thumbFile = $thumbFolder . "thumb_" . $newFileName;

    // Upload original file
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
        // Get original dimensions
        list($width, $height) = getimagesize($targetFile);

        // Set thumbnail size
        $new_width = 200;
        $new_height = floor($height * ($new_width / $width));

        // Create thumbnail
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
        }

        $thumb = imagecreatetruecolor($new_width, $new_height);

        // Preserve transparency for PNG
        if ($imageFileType == 'png') {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
        }

        // Resize
        imagecopyresampled($thumb, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Save thumbnail
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

        // Clean up
        imagedestroy($src);
        imagedestroy($thumb);

        // Save to database using prepared statement
        $stmt = $koneksi->prepare("INSERT INTO mhs (nim, nama, jurusan, filename, filepath, thumbpath, width, height, uploaded_at) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->bind_param("ssssssii", $nim, $nama, $jurusan, $newFileName, $targetFile, $thumbFile, $width, $height);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Data mahasiswa dan foto berhasil disimpan');
                    window.location.href='index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: " . $stmt->error . "');
                    window.location.href='addMhs.php';
                  </script>";
        }
        $stmt->close();
    } else {
        echo "<script>
                alert('Maaf, terjadi kesalahan saat upload file.');
                window.location.href='addMhs.php';
              </script>";
    }
}
?>
