<?php
session_start();
require '../config/koneksi.php';

$id = $_GET['id'] ?? 0;

// jalankan query dan simpan hasilnya
$query = mysqli_query($conn, "DELETE FROM patients WHERE id=$id");

if($query){
    echo "<script>
            alert('Pasien berhasil dihapus!');
            window.location='patients.php';
          </script>";
}else{
    echo "<script>
            alert('Gagal menghapus pasien!');
            window.location='patients.php';
          </script>";
}
?>