<?php
session_start();
require '../config/koneksi.php';

$id = $_GET['id'];

// Ambil role user
$user = mysqli_query($conn, "SELECT role FROM users WHERE id='$id'");
$data_user = mysqli_fetch_assoc($user);

if(!$data_user){
    echo "<script>
            alert('User tidak ditemukan!');
            window.location='users.php';
          </script>";
    exit;
}

// hanya cek pasien jika dokter, Jika user adalah dokter, cek dulu pasien
if($data_user['role'] == 'dokter'){
    $cek = mysqli_query($conn, "SELECT COUNT(*) as total FROM patients WHERE dokter_id='$id'");
    $data = mysqli_fetch_assoc($cek);

    if($data['total'] > 0){
        echo "<script>
                alert('Dokter masih memiliki pasien! Hapus pasien dulu.');
                window.location='users.php';
              </script>";
        exit;
    }
}

// Baru hapus user
$query = mysqli_query($conn,"DELETE FROM users WHERE id='$id'");

if($query){
    echo "<script>
            alert('User berhasil dihapus!');
            window.location='users.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus user!');
            window.location='users.php';
          </script>";
}
?>