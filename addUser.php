<!DOCTYPE html>
<html>

<head>
  <title>Sistem Informasi Akademik::Tambah Data User</title>
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
  </style>
  <script>
    $(document).ready(function() {
      // Membuat fungsi untuk mengecek username pada tabel user
      function checkUsernameExists(username) {
        $.ajax({
          url: 'cekDataKembarUser.php',
          type: 'POST',
          data: {
            username: username
          },
          success: function(response) {
            if (response === 'exists') {
              showError("* Username sudah ada, silakan gunakan yang lain");
              $("#username").val("").focus();
            } else {
              hideError();
            }
          }
        });
      }

      function validateUsername() {
        var username = $("#username").val().trim();
        if (username === "") {
          showError("* Username tidak boleh kosong!");
          return false;
        } else if (username.length < 5) {
          showError("* Username harus minimal 5 karakter");
          return false;
        }
        return true;
      }

      function validatePassword() {
        var password = $("#password").val().trim();
        if (password === "") {
          showError("* Password tidak boleh kosong!");
          return false;
        } else if (password.length < 6) {
          showError("* Password harus minimal 6 karakter");
          return false;
        }
        return true;
      }

      function showError(message) {
        $("#userError").text(message).show();
      }

      function hideError() {
        $("#userError").hide();
      }

      $("#username").on("blur", function() {
        if (validateUsername()) {
          checkUsernameExists($(this).val());
        }
      });

      $("#userForm").on("submit", function(event) {
        event.preventDefault();
        if (!validateUsername() || !validatePassword()) {
          return false;
        }
        var formData = new FormData(this);
        $.ajax({
          url: 'sv_addUser.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            $("#ajaxResponse").html(response);
          },
          error: function() {
            $("#ajaxResponse").html("Terjadi kesalahan saat mengirim data.");
          }
        });
      });
    });
  </script>
</head>

<body>
  <?php require "head.html"; ?>
  <div class="utama">
    <br><br><br>
    <h3>TAMBAH DATA USER</h3>
    <div class="alert alert-success alert-dismissible" id="success" style="display:none;">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    </div>
    <form id="userForm" method="post" action="sv_addUser.php" enctype="multipart/form-data">
      <div class="form-group">
        <label for="username">Username:</label>
        <input class="form-control" type="text" name="username" id="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input class="form-control" type="password" name="password" id="password" required>
      </div>
      <div class="form-group">
        <label for="status">Status:</label>
        <input class="form-control" type="text" name="status" id="status">
      </div>
      <div>
        <button type="submit" class="btn btn-primary" value="Simpan">Simpan</button>
      </div>
    </form>
    <p id="userError" class="error"></p>
  </div>
</body>

</html>