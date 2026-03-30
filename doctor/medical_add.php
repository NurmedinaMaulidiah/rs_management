<?php
session_start();
require '../config/koneksi.php';

// cek apakah yang login adalah dokter
if($_SESSION['role'] !== 'dokter'){
    header("Location: ../index.php");// jika bukan dokter, redirect
    exit;
}

$doctor_id = $_SESSION['user_id'];// ambil id dokter login
$patient_id = $_GET['patient_id'] ?? 0;// ambil id pasien dari URL

// ambil data pasien + layanan, sekaligus cek apakah pasien milik dokter
$sql = "SELECT p.*, s.nama_layanan
        FROM patients p
        JOIN services s ON p.service_id = s.id
        WHERE p.id = $patient_id AND p.dokter_id = $doctor_id";
$res = mysqli_query($conn, $sql);//jalankan query
$patient = mysqli_fetch_assoc($res);

if(!$patient){// ambil data pasien + layanan, sekaligus cek apakah pasien milik dokter
    echo "<script>alert('Pasien tidak ditemukan atau bukan pasien Anda!'); window.location='patients.php';</script>";
    exit;
}

// Proses form tambah rekam medis
if(isset($_POST['submit'])){
    $keluhan = trim($_POST['keluhan']);
    $diagnosa = trim($_POST['diagnosa']);
    $tindakan = trim($_POST['tindakan']);

    // Validasi
    if($keluhan === '' || $diagnosa === '' || $tindakan === ''){
        echo "<script>alert('Semua field harus diisi!'); window.history.back();</script>";
        exit;
    }

    // Simpan ke database
    $keluhan = mysqli_real_escape_string($conn, $keluhan);
    $diagnosa = mysqli_real_escape_string($conn, $diagnosa);
    $tindakan = mysqli_real_escape_string($conn, $tindakan);

    $query = "INSERT INTO medical_records (patient_id, keluhan, diagnosa, tindakan)
              VALUES ('$patient_id', '$keluhan', '$diagnosa', '$tindakan')";

    if(mysqli_query($conn, $query)){
        echo "<script>alert('Rekam medis berhasil disimpan!'); window.location='patient_detail.php?id=$patient_id';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan rekam medis!'); window.history.back();</script>";
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Rekam Medis pasien - Dokter Healyn</title>
    <link rel="stylesheet" href="../css/style-dokter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    
<div class="topbarPatients">
<h3>
<a href="patient_detail.php?id=<?= $patient_id ?>" style="margin-right:10px;">
    <i class="fa-solid fa-arrow-left"></i>
    </a>
    Dashboard Dokter
    </h3>

<!-- kanan -->
<div class="topbar-right">

    <div class="admin">
        <i class="fa-solid fa-user"></i>
        <?= $_SESSION['nama']; ?>
    </div>

    <a href="../logout.php" class="btn-logout"
    onclick="return confirm('Yakin ingin logout?')">
    <i class="fa-solid fa-right-from-bracket"></i>
    </a>
</div>
</div>

<div class="boxTambahUser">
    <div class="formInput">
        <h2>Tambah Rekam Medis Pasien</h2>
<!-- tampilkan info pasien -->
        <div class="infoPasien">
    <p>
        <strong><i class="fa-solid fa-user"></i> Nama:</strong> 
        <?= htmlspecialchars($patient['nama_pasien']) ?>
    </p>

    <p>
        <!-- spesialchars itu fungsi di PHP untuk mengamankan teks supaya tidak dibaca sebagai kode HTML. -->
        <strong><i class="fa-solid fa-stethoscope"></i> Layanan:</strong> 
        <?= htmlspecialchars($patient['nama_layanan']) ?>
    </p>

    <p>
        <strong><i class="fa-solid fa-venus-mars"></i> Gender:</strong> 
        <?= ($patient['jenis_kelamin'] === 'L') ? 'Laki-laki' : 'Perempuan' ?>
    </p>

    <p>
        <strong><i class="fa-solid fa-calendar-days"></i> Tanggal Lahir:</strong> 
        <?= date('d-m-Y', strtotime($patient['tanggal_lahir'])) ?>
    </p>
</div>
        <form method="POST">
            <label><i class="fa-solid fa-notes-medical"></i> Keluhan</label> 
            <textarea name="keluhan"></textarea><br>
            <label><i class="fa-solid fa-stethoscope"></i>Diagnosa</label> 
            <textarea name="diagnosa"></textarea><br>
            <label><i class="fa-solid fa-syringe"></i>Tindakan</label> 
            <textarea name="tindakan"></textarea><br><br>

                <button type="submit" name="submit">Simpan</button>
        </form>
    </div>
</div>
</body>
</html>
