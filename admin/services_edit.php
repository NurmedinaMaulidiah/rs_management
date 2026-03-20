<?php
require '../config/koneksi.php';

$id = $_GET['id'];
$service = mysqli_query($conn, "SELECT * FROM services WHERE id=$id");
$s = mysqli_fetch_assoc($service);

if(isset($_POST['submit'])){
    $nama = $_POST['nama_layanan'];
    mysqli_query($conn, "UPDATE services SET nama_layanan='$nama' WHERE id=$id");
    header("Location: services.php");
}
?>

<h2>Edit Layanan RS</h2>
<form method="post">
    Nama Layanan: <input type="text" name="nama_layanan" value="<?= $s['nama_layanan'] ?>" required><br><br>
    <input type="submit" name="submit" value="Update Layanan">
</form>