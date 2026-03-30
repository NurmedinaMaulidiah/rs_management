<?php
require '../config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Ambil id layanan, default 0

// Cek apakah ada pasien yang menggunakan layanan ini
$cek = mysqli_query($conn, "SELECT COUNT(*) AS total FROM patients WHERE service_id = $id");
$data = mysqli_fetch_assoc($cek);

if($data['total'] > 0){
    // Ada pasien yang pakai layanan, jangan hapus
    echo "<script>
            alert('Layanan masih digunakan oleh pasien! Hapus pasien dulu atau ubah layanan mereka.');
            window.location='services.php';
          </script>";
    exit;
}

// Jika tidak ada pasien, hapus layanan
$query = mysqli_query($conn, "DELETE FROM services WHERE id = $id");

if($query){
    echo "<script>
            alert('Layanan berhasil dihapus!');
            window.location='services.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus layanan!');
            window.location='services.php';
          </script>";
}
?>