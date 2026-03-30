<?php
session_start();
require '../config/koneksi.php';

// Cek role dokter
if($_SESSION['role'] !== 'dokter'){
    header("Location: ../index.php");
    exit;
}

$doctor_id = $_SESSION['user_id']; //ambil id dokter dari login
$id = $_GET['id'] ?? 0; //ambil idrekam medis dari url

// ambil data rekam medis + data pasien
// sekaligus cek apakah rekam medis milik dokter ini
$sql = "SELECT m.*, p.dokter_id, p.id as patient_id
        FROM medical_records m
        JOIN patients p ON m.patient_id = p.id
        WHERE m.id = $id AND p.dokter_id = $doctor_id";

$res = mysqli_query($conn, $sql); //jalankan query
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

if(mysqli_query($conn, $query)){ //jika berhasil
    echo "<script>
            alert('Rekam medis berhasil dihapus!');
            window.location='patient_detail.php?id=".$data['patient_id']."';
          </script>";
} else { //jika gagal
    echo "<script>
            alert('Gagal menghapus rekam medis!');
            window.history.back();
          </script>";
}
?>