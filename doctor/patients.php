<?php
session_start();
require '../config/koneksi.php';
// cek apakah user yang login adalah dokter
if($_SESSION['role'] !== 'dokter'){
    header("Location: ../index.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];// ambil id dokter dari session (user yang login)

// ambil input search dari URL + hapus spasi
$search = trim($_GET['search'] ?? '');

//intinya  Query ambil pasien dokter  berdasarkan dokter yg sedang login ini dengan filter search
$sql = "SELECT p.id, p.nama_pasien, s.nama_layanan
        FROM patients p
        JOIN services s ON p.service_id = s.id
        WHERE p.dokter_id = $doctor_id";
// jika ada pencarian
if($search !== ''){
    $sql .= " AND p.nama_pasien LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";// filter berdasarkan nama pasien (pakai LIKE)
}
// urutkan berdasarkan nama pasien by abjad
$sql .= " ORDER BY p.nama_pasien";

$result = mysqli_query($conn, $sql);// jalankan query

// Jika pasien tidak ditemukan, tampilkan alert
// if(mysqli_num_rows($result) == 0){
//     echo "<script>alert('Pasien tidak ditemukan!');</script>";
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Pasien - Dokter Healyn</title>
    <link rel="stylesheet" href="../css/style-dokter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>


<body>
<div class="topbarPatients">

<!-- kiri -->
<h3 class="page-title">Dashboard Dokter</h3>

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

<div class = "user-header">
<form method="GET" class="search-box">
    <input type="text" name="search" placeholder="Cari nama pasien...">
    <button type="submit">
        <i class="fa-solid fa-magnifying-glass"></i> Search
    </button>
</form>
</div>

<!-- table user -->
<div class = "user" >
    <?php if(mysqli_num_rows($result) == 0): ?><!-- jika tidak ada data -->
    <p><em>Belum ada pasien untuk dokter ini.</em></p>

    <?php else: ?>
    <table border='5'>
    <tr>
        <th colspan='4'>Daftar Pasien</th>
    </tr>

        <tr>
            <th>No</th>
            <th>Nama Pasien</th>
            <th>Layanan</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;//no urut dan bakal loop terus
        while($row = mysqli_fetch_assoc($result)){
        ?>

        <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_pasien'] ?></td>
        <td><?= $row['nama_layanan'] ?></td>
        <td>
            <a class="btnDetail" href="patient_detail.php?id=<?= $row['id'] ?>">
                <i class="fa-solid fa-folder-open"></i> Detail
            </a>
        </td>

        </tr>

        <?php } ?>
    </table>
    <?php endif; ?>
</div>
    </div>
</body>
</html>
