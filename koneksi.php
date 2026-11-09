<?php
$host     = "localhost";
$user     = "root";      // Sesuaikan dengan user database Anda
$password = "";          // Sesuaikan jika ada password
$db       = "evosis_smaper";

$koneksi = mysqli_connect($host, $user, $password, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
