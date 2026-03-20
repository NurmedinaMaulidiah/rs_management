<?php
require '../config/koneksi.php';

if(isset($_POST['submit'])){
    $nama = $_POST['nama_layanan'];
    mysqli_query($conn, "INSERT INTO services (nama_layanan) VALUES ('$nama')");
    header("Location: services.php");
}
?>

<h2>Tambah Layanan RS</h2>
<form method="post">
    Nama Layanan: <input type="text" name="nama_layanan" required><br><br>
    <input type="submit" name="submit" value="Tambah Layanan">
</form>