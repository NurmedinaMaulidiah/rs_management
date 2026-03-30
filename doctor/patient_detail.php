<?php
session_start();
require '../config/koneksi.php';

if($_SESSION['role'] !== 'dokter'){//cek apakah yg login dokter
    header("Location: ../index.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];// ambil id dokter yang login
$patient_id = $_GET['id'] ?? 0; // ambil id pasien dari url

// ambil data pasien + nama layanan
// sekaligus memastikan pasien milik dokter yang login 
//intinya query utk ambil data pasein +nama layanan berdasarkan dokternya
$sql = "SELECT p.*, s.nama_layanan
        FROM patients p
        JOIN services s ON p.service_id = s.id
        WHERE p.id = $patient_id AND p.dokter_id = $doctor_id";
//jalankan query
$res = mysqli_query($conn, $sql);
$patient = mysqli_fetch_assoc($res);
// jika pasien tidak ditemukan atau bukan milik dokter
if(!$patient){
    echo "Pasien tidak ditemukan atau bukan pasien Anda!";
    exit;
}

// ambil semua rekam medis pasien berdasarkan id pasien dan ururt descending dari yg oaling baru
$medicals = mysqli_query($conn, "SELECT * FROM medical_records WHERE patient_id = $patient_id ORDER BY created_at DESC");

// Tambah rekam medis
// if(isset($_POST['add_medical'])){
//     $keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);
//     $diagnosa = mysqli_real_escape_string($conn, $_POST['diagnosa']);
//     $tindakan = mysqli_real_escape_string($conn, $_POST['tindakan']);

//     mysqli_query($conn, "INSERT INTO medical_records (patient_id, keluhan, diagnosa, tindakan)
//     VALUES ('$patient_id','$keluhan','$diagnosa','$tindakan')");

//     header("Location: patient_detail.php?id=$patient_id");
//     exit;
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pasien - Dokter Healyn</title>
    <link rel="stylesheet" href="../css/style-dokter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="topbarPatients">
<h3>
            <a href="patients.php" style="margin-right:10px;">
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

<!-- Tambah Rekam Medis -->
<div class="user-header">
<a href="medical_add.php?patient_id=<?= $patient_id ?>" class="btn-add"> <!-- jgn lupa ambil id biar bisa nambah kalo gapake muncul pasien ini buka pasien dokter ini -->
    <i class="fa-solid fa-plus"></i> Tambah Rekam Medis
</a>
</div>

<!-- Detail -->
<div class="detailPasien">
<table class="detailTable">
    <tr>
        <th colspan="4">
            <i class="fa-solid fa-user"></i> Detail Pasien
        </th>
    </tr>

    <tr>
        <td><strong>Nama</strong></td>
        <td><?= htmlspecialchars($patient['nama_pasien']) ?></td>
<!-- spesialchars buat amanin data pasien supaya apa pun yang diketik user ditampilkan sebagai teks biasa, -->
        <td><strong>Layanan</strong></td>
        <td><?= htmlspecialchars($patient['nama_layanan']) ?></td>
    </tr>

    <tr>
        <td><strong>Jenis Kelamin</strong></td>
        <td><?= ($patient['jenis_kelamin'] === 'L') ? 'Pria' : 'Wanita' ?></td>

        <td><strong>Tanggal Lahir</strong></td>
        <td><?= date('d-m-Y', strtotime($patient['tanggal_lahir'])) ?></td>
    </tr>
</table>
</div>

<!-- table user -->
<!-- table rekam medis -->
<div class="user">
    <table border="1">
        <tr>
            <th colspan="6">Rekam Medis Pasien</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keluhan</th>
            <th>Diagnosa</th>
            <th>Tindakan</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        while($m = mysqli_fetch_assoc($medicals)){
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $m['created_at'] ?></td>
            <td><?= $m['keluhan'] ?></td>
            <td><?= $m['diagnosa'] ?></td>
            <td><?= $m['tindakan'] ?></td>

            <td>
            <a class="btn-edit" href="medical_edit.php?id=<?= $m['id']  ?>">
            <i class="fa-solid fa-pen"></i> Edit
            </a>

            <a class="btn-delete" href="medical_delete.php?id=<?= $m['id']  ?>" onclick="return confirm('Yakin hapus rekam medis?')">
            <i class="fa-solid fa-trash"></i> Delete
        </a>
            </td>
        </tr>
        <?php } ?>
<!-- jika belum ada rekam medis -->
        <?php if(mysqli_num_rows($medicals) == 0): ?>
        <tr>
            <td colspan="5"><em>Belum ada rekam medis untuk pasien ini.</em></td>
        </tr>
        <?php endif; ?>
        
    </table>
</div>


</body>
</html>
