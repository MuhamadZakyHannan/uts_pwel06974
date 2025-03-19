<?php
    //memanggil file pustaka fungsi
    require "fungsi.php";

    //memindahkan data kiriman dari form ke var biasa
    $npp=$_GET["kode"];

    $sql=$koneksi->query("select * from dosen where npp='$npp'");
    $data=$sql->fetch_assoc();

    $sql=$koneksi->query("select * from dosen where npp='$npp'");
    //membuat query hapus data
    $sql="delete from dosen where npp ='$npp'";
    mysqli_query($koneksi,$sql);
    header("location:ajaxUpdateDsn.php");
?>
