<?php
session_start();
require '../config/koneksi.php';

$id = $_GET['id'] ?? 0; //ambil id pasein default 0 jika tidak ada

// Jalankan query untuk menghapus data pasien berdasarkan ID
$query = mysqli_query($conn, "DELETE FROM patients WHERE id=$id");

if($query){// Jika berhasil dihapus
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