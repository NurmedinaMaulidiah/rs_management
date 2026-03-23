<?php
require '../config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// jalankan query delete
$query = mysqli_query($conn, "DELETE FROM services WHERE id=$id");

if($query){
    echo "<script>
            alert('Layanan berhasil dihapus!');
            window.location='services.php';
          </script>";
}else{
    echo "<script>
            alert('Gagal menghapus layanan!');
            window.location='services.php';
          </script>";
}
?>