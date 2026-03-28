<?php
require '../config/koneksi.php';

$id = $_GET['id'];// Ambil ID user dari URL yang ingin dihapus

// Jalankan query DELETE untuk hapus user berdasarkan ID
$query = mysqli_query($conn,"DELETE FROM users WHERE id='$id'");
// Jika query berhasil dijalankan
if($query){
    echo "<script>
            alert('User berhasil dihapus!');
            window.location='users.php';
          </script>";
}else{
    echo "<script>
            alert('Gagal menghapus user!');
            window.location='users.php';
          </script>";
}
?>