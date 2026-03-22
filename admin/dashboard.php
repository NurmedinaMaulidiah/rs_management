<?php
session_start();
require '../config/koneksi.php';
if($_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
}

// / Total semua user
$totalUsersQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$totalUsers = mysqli_fetch_assoc($totalUsersQuery)['total'];

// Total admin
$totalAdminQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='admin'");
$totalAdmin = mysqli_fetch_assoc($totalAdminQuery)['total'];

// Total staff
$totalStaffQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='staff'");
$totalStaff = mysqli_fetch_assoc($totalStaffQuery)['total'];

// Total dokter
$totalDoctorQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='dokter'");
$totalDoctor = mysqli_fetch_assoc($totalDoctorQuery)['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Admin - Healyn</title>
<link rel="stylesheet" href="../css/style-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <li><i class="fa-solid fa-stethoscope"></i> <a href="doctor_services.php">Dokter dan Layanan</a></li>
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


        <!-- CARDS -->
        <div class="cards">

            <div class="card">
                <h4>Total Users</h4>
                <p><?= $totalUsers ?></p>
            </div>

            <div class="card">
                <h4>Total Patients</h4>
                <p>40</p>
            </div>

            <div class="card">
                <h4>Total Doctors</h4>
                <p><?= $totalDoctor ?></p>
            </div>

        </div>


        <!-- CONTENT AREA -->
        <!-- <div class="content">

            <h3>Welcome to Healyn Admin Dashboard</h3>

            <p>
            Sistem manajemen pasien rumah sakit untuk membantu
            pengelolaan data pasien, dokter, dan rekam medis secara digital.
            </p>

        </div> -->


    </div>

</div>

<script>
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