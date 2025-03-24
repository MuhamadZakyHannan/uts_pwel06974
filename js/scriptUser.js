document.addEventListener("DOMContentLoaded", function () {
  console.log("scriptUser.js berhasil dimuat dan DOM siap!");

  // Ambil elemen input pencarian
  var keyword = document.getElementById("keyword");
  var container = document.getElementById("container");

  // Cek apakah elemen ditemukan
  if (!keyword) {
    console.error("Elemen dengan ID 'keyword' tidak ditemukan! Pastikan ada <input id='keyword'> di HTML.");
    return;
  }
  if (!container) {
    console.error("Elemen dengan ID 'container' tidak ditemukan! Pastikan ada <div id='container'> di HTML.");
    return;
  }

  // Tambahkan event keyup untuk AJAX pencarian
  keyword.addEventListener("keyup", function () {
    console.log("Event keyup terdeteksi! Nilai input:", keyword.value);

    // Buat objek AJAX
    var xhr = new XMLHttpRequest();

    // Cek status request
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
        if (xhr.status == 200) {
          console.log("Response AJAX diterima!");
          container.innerHTML = xhr.responseText;
        } else {
          console.error("Terjadi kesalahan AJAX: " + xhr.status);
        }
      }
    };

    // Kirim request ke `ajax/ajaxUser.php`
    xhr.open("GET", "ajax/ajaxUser.php?keyword=" + encodeURIComponent(keyword.value), true);
    xhr.send();
  });
});
