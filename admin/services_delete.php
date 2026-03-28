<?php
require '../config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;//ambil id services berdasarkan id, defaultnya 0

// jalankan query delete
$query = mysqli_query($conn, "DELETE FROM services WHERE id=$id");

if($query){ //jika berhasil
    echo "<script>
            alert('Layanan berhasil dihapus!');
            window.location='services.php';
          </script>";
}else{//jika gagal
    echo "<script>
            alert('Gagal menghapus layanan!');
            window.location='services.php';
          </script>";
}
?>