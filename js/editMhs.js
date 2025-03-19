$(document).ready(function() {
    $('#submit').on('click', function(event) {
        event.preventDefault(); // Mencegah form dari submit biasa

        // Konfirmasi sebelum submit
        if (confirm('Apakah Anda yakin ingin menyimpan perubahan?')) {
            // Mengambil data form
            var formData = $('#editForm').serialize();

            // Mengirim data dengan AJAX
            $.ajax({
                type: 'POST',
                url: 'sv_editMhs.php',
                data: formData,
                success: function(response) {
                    // Menampilkan alert menggunakan showAlert dari alert.js
                    showAlert('Data berhasil diupdate.');
                    // Redirect jika perlu
                    //window.location.href = 'ajaxUpdateDsn.php';
                },
                error: function() {
                    showAlert('Terjadi kesalahan dalam menyimpan data.');
                }
                
            });
        }
    });
});
