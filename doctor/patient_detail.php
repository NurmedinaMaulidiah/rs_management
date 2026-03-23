<?php
session_start();
require '../config/koneksi.php';

if($_SESSION['role'] !== 'dokter'){
    header("Location: ../login.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];
$patient_id = $_GET['id'] ?? 0;
$search = trim($_GET['search'] ?? '');

// Pastikan pasien milik dokter
$sql = "SELECT p.*, s.nama_layanan
        FROM patients p
        JOIN services s ON p.service_id = s.id
        WHERE p.id = $patient_id AND p.dokter_id = $doctor_id";
$res = mysqli_query($conn, $sql);
$patient = mysqli_fetch_assoc($res);

if(!$patient){
    echo "Pasien tidak ditemukan atau bukan pasien Anda!";
    exit;
}

// Ambil rekam medis pasien dengan search
$medicals_query = "SELECT * FROM medical_records WHERE patient_id = $patient_id";

if($search != ''){
    $search_esc = mysqli_real_escape_string($conn, $search);
    $medicals_query .= " AND (keluhan LIKE '%$search_esc%' OR diagnosa LIKE '%$search_esc%' OR tindakan LIKE '%$search_esc%')";
}

$medicals_query .= " ORDER BY created_at DESC";
$medicals = mysqli_query($conn, $medicals_query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Detail Pasien - Dokter</title>
<link rel="stylesheet" href="../css/style-dokter.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    
<div class="topbarPatients">
<h3 class="page-title">Dashboard Dokter</h3>

<div class="topbar-right">
    <div class="admin">
        <i class="fa-solid fa-user"></i>
        <?= $_SESSION['nama']; ?>
    </div>
    <a href="../logout.php" class="btn-logout" onclick="return confirm('Yakin ingin logout?')">
        <i class="fa-solid fa-right-from-bracket"></i>
    </a>
</div>
</div>

<!-- tambah rekam medis -->
<div class="user-header">
    <form method="GET" class="search-add-container">
        <input type="hidden" name="id" value="<?= $patient_id ?>">
        <input type="text" name="search" placeholder="Cari Rekam Medis..." value="<?= htmlspecialchars($search) ?>" class="search-input">
        <button type="submit" class="btn-search">
            <i class="fa-solid fa-magnifying-glass"></i> Cari
        </button>
        <a href="medical_add.php" class="btn-add">
            <i class="fa-solid fa-plus"></i> Tambah Rekam Medis
        </a>
    </form>
</div>

<div class ="detailPasien">
    <h2>Detail Pasien</h2>
    <p>Nama: <?= $patient['nama_pasien'] ?></p>
    <p>Layanan: <?= $patient['nama_layanan'] ?></p>
</div>

<!-- Table Rekam Medis -->
<div class="user">
    <table border='5'>
        <tr>
            <th colspan='6'>Daftar Rekam Medis Pasien</th>
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
                <a href="medical_edit.php?id=<?= $m['id'] ?>">Edit</a> |
                <a href="medical_delete.php?id=<?= $m['id'] ?>" onclick="return confirm('Yakin hapus rekam medis?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>

