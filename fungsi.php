<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$password = "";
$database = "akademik06974";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8");

function sanitize($data)
{
    global $koneksi;
    return mysqli_real_escape_string($koneksi, trim($data));
}

function formatDate($date)
{
    return date('d/m/Y H:i', strtotime($date));
}
