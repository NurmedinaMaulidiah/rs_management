<?php
session_start();
require '../config/koneksi.php';

// Cek role dokter
if($_SESSION['role'] !== 'dokter'){
    header("Location: ../login.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? 0;

// Ambil data rekam medis + cek kepemilikan pasien
$sql = "SELECT m.*, p.dokter_id, p.id as patient_id
        FROM medical_records m
        JOIN patients p ON m.patient_id = p.id
        WHERE m.id = $id AND p.dokter_id = $doctor_id";

$res = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($res);

// Kalau data tidak ada / bukan milik dokter
if(!$data){
    echo "<script>
            alert('Data tidak ditemukan atau bukan milik Anda!');
            window.location='patients.php';
          </script>";
    exit;
}

// Proses hapus
$query = "DELETE FROM medical_records WHERE id = $id";

if(mysqli_query($conn, $query)){
    echo "<script>
            alert('Rekam medis berhasil dihapus!');
            window.location='patient_detail.php?id=".$data['patient_id']."';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus rekam medis!');
            window.history.back();
          </script>";
}
?>