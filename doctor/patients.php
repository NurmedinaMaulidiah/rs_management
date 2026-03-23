<?php
session_start();
require '../config/koneksi.php';

if($_SESSION['role'] !== 'dokter'){
    header("Location: ../login.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];
$search = trim($_GET['search'] ?? '');

// Mulai query dasar
$query = "SELECT p.id, p.nama_pasien, s.nama_layanan
          FROM patients p
          JOIN services s ON p.service_id = s.id
          WHERE p.dokter_id = $doctor_id";

// Tambahkan search jika ada
if($search != ''){
    $search = mysqli_real_escape_string($conn, $search);
    $query .= " AND p.nama_pasien LIKE '%$search%'";
}

// Tambahkan urutan
$query .= " ORDER BY p.nama_pasien";

$result = mysqli_query($conn, $query);

// cek jika data kosong
if(mysqli_num_rows($result) == 0){
    echo "<script>alert('Pasien tidak ditemukan!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard List Pasien Dokter - Healyn</title>
    <link rel="stylesheet" href="../css/style-dokter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>
<div class="topbarPatients">

<!-- kiri -->
<h3 class="page-title">Dashboard Staff</h3>

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

<!-- table user -->
<div class = "user" >
    <table border='5'>
    <tr>
        <th colspan='7'>Data Pasien</th>
    </tr>

        <tr>
            <th>No</th>
            <th>Nama Pasien</th>
            <th>Layanan</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        while($row = mysqli_fetch_assoc($result)){
        ?>

        <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_pasien'] ?></td>
        <td><?= $row['nama_layanan'] ?></td>
        <td>

        <a class="btn-detail" href="patient_detail.php?id=<?= $row['id'] ?>"> 
        <i class="fa-solid fa-pen"></i> Detail
        </a>
        </a>

        </td>
        </tr>

        <?php } ?>
    </table>
</div>
    </div>


</body>
</html>
