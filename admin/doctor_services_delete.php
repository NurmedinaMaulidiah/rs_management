<?php
require '../config/koneksi.php';// Memanggil file koneksi database

// Ambil dokter_id dari URL, jika tidak ada default 0
$dokter_id = isset($_GET['dokter_id']) ? (int)$_GET['dokter_id'] : 0;

// jalankan query delete
$query = mysqli_query($conn, "DELETE FROM doctor_services WHERE dokter_id=$dokter_id");
// Cek apakah query berhasil dijalankan
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