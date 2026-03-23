<?php
require '../config/koneksi.php';

// ambil dokter_id dari URL
$dokter_id = isset($_GET['dokter_id']) ? (int)$_GET['dokter_id'] : 0;

// jalankan query delete
$query = mysqli_query($conn, "DELETE FROM doctor_services WHERE dokter_id=$dokter_id");

if($query){
    echo "<script>
            alert('Layanan dokter berhasil dihapus!');
            window.location='doctor_services.php';
          </script>";
}else{
    echo "<script>
            alert('Gagal menghapus layanan dokter!');
            window.location='doctor_services.php';
          </script>";
}
?>