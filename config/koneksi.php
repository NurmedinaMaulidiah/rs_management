<?php
$host = "localhost"; // Nama server database,
$user = "root"; // Username untuk masuk ke database
$pass = "";// Password untuk database 
$db   = "rs_management"; // Nama database yang ingin digunakan
// Membuat koneksi ke database dengan mysqli_connect, jika koneksi gagal, program akan berhenti dan menampilkan pesan error."
$conn = mysqli_connect($host, $user, $pass, $db);
// Mengecek apakah koneksi gagal
if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());//kalo gagal  hentikan program dan tampilkan pesan error
}
?>