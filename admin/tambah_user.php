<?php
session_start();
require '../config/koneksi.php';

if(isset($_POST['submit'])){

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

mysqli_query($conn,"INSERT INTO users (nama,username,password,role)
VALUES('$nama','$username','$password','$role')");

header("Location: users.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="../css/style-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            <li><i class="fa-solid fa-hospital-user"></i><a href="services.php">Layanan</a></li>
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

        <div class ="boxTambahUser">
            <div class="formInput">
                <h2>Register New User Account</h2>
            <form method="POST">
            <label for="">Nama</label> 
                <input type="text" name="nama" placeholder="Nama"><br>

                <label for="">Username</label> 
                <input type="text" name="username" placeholder="Username"><br>

                <label for="">Password </label> 
                <input type="password" name="password" placeholder="Password"><br>

                <select name="role">
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="dokter">Dokter</option>
                </select><br>

                <button type="submit" name="submit">Simpan</button>

</form>
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