<?php
session_start();
require '../config/koneksi.php';

// Pastikan role dokter
if($_SESSION['role'] !== 'dokter'){
    header("Location: ../login.php");
    exit;
}

$doctor_id = $_SESSION['user_id']; // ID dokter login
$record_id = $_GET['id'] ?? 0;

// Ambil rekam medis yang sesuai dokter
$sql = "SELECT mr.*, p.nama_pasien, p.dokter_id 
        FROM medical_records mr
        JOIN patients p ON mr.patient_id = p.id
        WHERE mr.id=$record_id AND p.dokter_id=$doctor_id";
$res = mysqli_query($conn, $sql);
$record = mysqli_fetch_assoc($res);

if(!$record){
    echo "Rekam medis tidak ditemukan atau Anda tidak berhak mengedit!";
    exit;
}

// Jika form submit
if(isset($_POST['submit'])){
    $keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);
    $diagnosa = mysqli_real_escape_string($conn, $_POST['diagnosa']);
    $tindakan = mysqli_real_escape_string($conn, $_POST['tindakan']);

    mysqli_query($conn, "UPDATE medical_records SET 
        keluhan='$keluhan', 
        diagnosa='$diagnosa', 
        tindakan='$tindakan' 
        WHERE id=$record_id");

    header("Location: patient_detail.php?id=".$record['patient_id']);
    exit;
}
?>

<h2>Edit Rekam Medis Pasien: <?= $record['nama_pasien'] ?></h2>
<form method="post">
    Keluhan:<br>
    <textarea name="keluhan" required><?= $record['keluhan'] ?></textarea><br><br>

    Diagnosa:<br>
    <textarea name="diagnosa" required><?= $record['diagnosa'] ?></textarea><br><br>

    Tindakan:<br>
    <textarea name="tindakan" required><?= $record['tindakan'] ?></textarea><br><br>

    <input type="submit" name="submit" value="Simpan Perubahan">
</form>