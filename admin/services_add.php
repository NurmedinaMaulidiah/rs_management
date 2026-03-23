<?php
session_start();
require '../config/koneksi.php';

if(isset($_POST['submit'])){

    $nama = trim($_POST['nama_layanan']);

    // cek jika kosong
    if(empty($nama)){
        echo "<script>alert('Nama layanan harus diisi!');</script>";
    }else{

        // cek apakah layanan sudah ada
        $cek = mysqli_query($conn, "SELECT * FROM services WHERE nama_layanan='$nama'");

        if(mysqli_num_rows($cek) > 0){
            echo "<script>alert('Layanan sudah ada!');</script>";
        }else{

            // insert layanan
            $query = mysqli_query($conn, "INSERT INTO services (nama_layanan) VALUES ('$nama')");

            if($query){
                echo "<script>
                        alert('Layanan berhasil ditambahkan!');
                        window.location='services.php';
                      </script>";
            }else{
                echo "<script>
                        alert('Gagal menambahkan layanan!');
                      </script>";
            }

        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Add Layanan Admin - Healyn</title>
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
            <li><i class="fa-solid fa-notes-medical"></i><a href="services.php">Layanan Rumah Sakit</a></li>
            <li><i class="fa-solid fa-stethoscope"></i> <a href="doctor_services.php">Layanan Dokter</a></li>
            <li><i class="fa-solid fa-right-from-bracket"></i><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="mainPatients" id="main">
        <!-- TOPBAR -->
        <div class="topbarPatients">
            <span class="toggle-btn" onclick="openSidebar()">☰</span>
            <h3>Dashboard Admin</h3>
            <div class="admin">
                <i class="fa-solid fa-user"></i>
                <?= $_SESSION['nama']; ?>
            </div>
        </div>

        <div class ="boxTambahUser">
        <div class="formInput">
        <h2>Tambah Layanan RS</h2>
        <form method="post">
            <label>Nama Layanan</label>
            <input type="text" name="nama_layanan" required><br><br>
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
</html>
