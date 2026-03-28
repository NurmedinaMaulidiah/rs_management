<?php
session_start();
require '../config/koneksi.php';

// Pastikan role dokter
if($_SESSION['role'] !== 'dokter'){
    header("Location: ../login.php");
    exit;
}

$doctor_id = $_SESSION['user_id']; // ID dokter login
$record_id = $_GET['id'] ?? 0;  //ambil id rekam medis dari url

// ambil data rekam medis + data pasien
// sekaligus memastikan rekam medis milik pasien dokter ini
$sql = "SELECT mr.*, p.nama_pasien, p.dokter_id 
        FROM medical_records mr
        JOIN patients p ON mr.patient_id = p.id
        WHERE mr.id=$record_id AND p.dokter_id=$doctor_id";
$res = mysqli_query($conn, $sql);//jalanlan query
$record = mysqli_fetch_assoc($res);

if(!$record){// jika data tidak ditemukan atau bukan milik dokter
    echo "Rekam medis tidak ditemukan atau Anda tidak berhak mengedit!";
    exit;
}

// Jika form submit
if(isset($_POST['submit'])){
    $keluhan = trim($_POST['keluhan']);
    $diagnosa = trim($_POST['diagnosa']);
    $tindakan = trim($_POST['tindakan']);

    // VALIDASI WAJIB ISI
    if($keluhan === ''){
        echo "<script>alert('Keluhan harus diisi!'); window.history.back();</script>";
        exit;
    }

    if($diagnosa === ''){
        echo "<script>alert('Diagnosa harus diisi!'); window.history.back();</script>";
        exit;
    }

    if($tindakan === ''){
        echo "<script>alert('Tindakan harus diisi!'); window.history.back();</script>";
        exit;
    }

     // amankan input dari SQL Injection
     //Pengamanan ini supaya input user tidak bisa merusak atau membobol database.
    $keluhan = mysqli_real_escape_string($conn, $keluhan);
    $diagnosa = mysqli_real_escape_string($conn, $diagnosa);
    $tindakan = mysqli_real_escape_string($conn, $tindakan);

    // Update data
    $query = "UPDATE medical_records SET 
                keluhan='$keluhan', 
                diagnosa='$diagnosa', 
                tindakan='$tindakan' 
              WHERE id=$record_id";

    if(mysqli_query($conn, $query)){ //jika berhasil
        echo "<script>
                alert('Rekam medis berhasil diupdate!');
                window.location='patient_detail.php?id=".$record['patient_id']."';
              </script>";
        exit;
    } else {
        echo "<script>//jika gagal
                alert('Gagal mengupdate rekam medis!');
                window.history.back();
              </script>";
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
    <title>Edit Rekam Medis - Dokter Healyn</title>
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
        <!-- FORM EDIT -->
        <form method="POST">

            <label>
                <i class="fa-solid fa-notes-medical"></i> Keluhan
            </label>
            <!-- otomatis keiisi berdasarkan db -->
            <textarea name="keluhan" required><?= htmlspecialchars($record['keluhan']) ?></textarea>

            <label>
                <i class="fa-solid fa-stethoscope"></i> Diagnosa
            </label>
            <textarea name="diagnosa" required><?= htmlspecialchars($record['diagnosa']) ?></textarea>

            <label>
                <i class="fa-solid fa-syringe"></i> Tindakan
            </label>
            <textarea name="tindakan" required><?= htmlspecialchars($record['tindakan']) ?></textarea>

            <!-- BUTTON -->
            <div style="display:flex; gap:10px; margin-top:10px;">
                
                <button type="submit" name="submit">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>

                <a href="patient_detail.php?id=<?= $record['patient_id'] ?>" 
                   class="btn-delete"
                   style="background:#6c757d;">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>

            </div>

        </form>
    </div>
</div>
</body>
</html>

<!-- 
        Kenapa pakai $record?
        Karena halaman ini adalah EDIT REKAM MEDIS, jadi data utama yang diambil berasal dari tabel medical_records.
        Query yang digunakan sudah JOIN ke tabel patients dan services,
        sehingga semua data pasien (nama, jenis kelamin, dll) sudah ikut dalam $record.
        
        Jadi:
        $record = gabungan data rekam medis + data pasien
        Tidak perlu lagi pakai $patient.
        -->
