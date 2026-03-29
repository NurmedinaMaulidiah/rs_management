<?php
session_start();// Mulai session untuk akses nama admin
require '../config/koneksi.php';

$search = trim($_GET['search'] ?? '');// Ambil input search dari URL, hapus spasi di awal/akhir
// Query ini menampilkan setiap dokter beserta semua layanan yang dia tangani,
// digabung jadi satu baris per dokter dengan layanan dipisah koma."
// datanya dari doctor services, hasil relasi users dan services
$query = "
SELECT ds.dokter_id, u.nama AS dokter,
GROUP_CONCAT(s.nama_layanan SEPARATOR ', ') AS layanan
FROM doctor_services ds
JOIN users u ON ds.dokter_id = u.id
JOIN services s ON ds.service_id = s.id
";
// kondisi ketika user mengetik di kolom pencarian, maka query akan menampilkan 
//nama/layanan yg namanya mengandung kata yg dicari user
if($search != ''){
    $query .= " WHERE u.nama LIKE '%$search%' 
                OR s.nama_layanan LIKE '%$search%'";
}
// Group berdasarkan dokter_id agar layanan tergabung per dokter dalam 1 baris
$query .= "
GROUP BY ds.dokter_id
ORDER BY u.nama
";
// Jalankan query dan simpan di result utk dipake terus
$result = mysqli_query($conn,$query);

// cek jika data kosong
if(mysqli_num_rows($result) == 0){
    echo "<script>alert('Layanan dokter tidak ditemukan!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard List Layanan Dokter Admin - Healyn</title>
    <link rel="stylesheet" href="../css/style-dashboard.css">
    <link rel="stylesheet" href="../css/style-doctor_services.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="dashboard">
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2 class="logo">Healyn</h2>
            <span class="close-btn" onclick="closeSidebar()">✖</span>
        </div>
        <ul>
            <li><i class="fa-solid fa-chart-line"></i><a href="dashboard.php">Dashboard</a></li>
            <li><i class="fa-solid fa-users"></i><a href="users.php">Users</a></li>
            <li><i class="fa-solid fa-hospital-user"></i><a href="patients.php">Patients</a></li>
            <li><i class="fa-solid fa-notes-medical"></i><a href="services.php">Layanan Rumah Sakit</a></li>
            <li><i class="fa-solid fa-stethoscope"></i> <a href="doctor_services.php">Layanan Dokter</a></li>
            <li><i class="fa-solid fa-right-from-bracket"></i><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main" id="main">
        <!-- TOPBAR -->
        <div class="topbar">
            <span class="toggle-btn" onclick="openSidebar()">☰</span>
            <h3>Dashboard Admin</h3>
            <div class="admin">
                <i class="fa-solid fa-user"></i>
                <?= $_SESSION['nama']; ?>
            </div>
        </div>

        <!-- Atur Layanan Dokter -->
<div class="user-header">
    <form method="GET" class="search-box">
    <input type="text" name="search" placeholder="Cari Layanan Dokter...">
    <button type="submit">
        <i class="fa-solid fa-magnifying-glass"></i> Search
    </button>
    </form>

    <a href="doctor_services_add.php" class="btn-add">
    <i class="fa-solid fa-plus"></i> Atur Layanan Dokter
    </a>
</div>


<!-- table layanan dookter -->
<div class = "doctor_services" >
    <table border='5'>
    <tr>
        <th colspan='5'>Daftar Layanan Dokter</th>
    </tr>

        <tr>
            <th>No</th>
            <th>Dokter</th>
            <th>Layanan yang diberikan</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1; //no urut bakal looping dan bertambahk
        while($row = mysqli_fetch_assoc($result)){
        ?>

        <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['dokter'] ?></td>
        <td><?= $row['layanan'] ?></td>
        <td>

        <a class="btn-edit" href="doctor_services_edit.php?dokter_id=<?= $row['dokter_id'] ?>">
        <i class="fa-solid fa-pen"></i> Edit
        </a>

        <a class="btn-delete" 
        href="doctor_services_delete.php?dokter_id=<?= $row['dokter_id'] ?>" 
        onclick="return confirm('Yakin ingin hapus semua layanan dokter?')">
        <i class="fa-solid fa-trash"></i> Delete
        </a>

        </td>
        </tr>

        <?php } ?>
    </table>
</div>
    </div>

<script>
    //script sidebar buka/tutup
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.querySelector('.toggle-btn');
const closeBtn = document.querySelector('.close-btn');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.add('open');
});

closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
});
</script>
</body>
</html>
