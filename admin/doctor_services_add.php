<?php
session_start();// Memulai session untuk akses user
require '../config/koneksi.php';

// Ambil dokter dari tabel user
$doctors = mysqli_query($conn, "SELECT * FROM users WHERE role='dokter'");

// Ambil layanan dari tabel services
$services = mysqli_query($conn, "SELECT * FROM services");

if(isset($_POST['submit'])){// Jika tombol submit ditekan

    $dokter_id = $_POST['dokter_id'] ?? '';// Ambil dokter yang dipilih
    $service_ids = $_POST['service_ids'] ?? [];// Ambil layanan yang dicentang

    /* VALIDASI */

    // dokter harus dipilih
    if($dokter_id == ""){
        echo "<script>
                alert('Pilih dokter terlebih dahulu!');
                window.history.back();
              </script>";
        exit;
    }

    // minimal 1 layanan harus dipilih
    if(empty($service_ids)){
        echo "<script>
                alert('Minimal pilih satu layanan!');
                window.history.back();
              </script>";
        exit;
    }

    /* SIMPAN DATA */

    foreach($service_ids as $sid){// Simpan tiap layanan ke tabel doctor_services
        mysqli_query($conn, "INSERT INTO doctor_services (dokter_id, service_id) 
                             VALUES ($dokter_id, $sid)");
    }
//redirect ke halaman doctor_services
    echo "<script>
            alert('Layanan dokter berhasil ditambahkan!');
            window.location='doctor_services.php';
          </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Layanan Dokter Admin - Healyn</title>
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

        <div class="boxTambahServices">
    <div class="formInputServices"><!-- form input doctor services -->
        <h2>Atur Layanan Dokter</h2>
        <form method="post">
            <label>Dokter</label>
            <select name="dokter_id" required>
                <option value="">--Pilih Dokter--</option>
                <?php mysqli_data_seek($doctors,0);// Reset pointer hasil query layanan ke baris pertama
                while($d = mysqli_fetch_assoc($doctors)){ ?><!-- looping semua data dokter -->
                    <option value="<?= $d['id'] ?>"><?= $d['nama'] ?></option><!-- Buat opsi dropdown untuk tiap dokter -->
                <?php } ?>
            </select>

            <label>Layanan</label>
            <div class="checkbox-group">
                <?php mysqli_data_seek($services,0); while($s = mysqli_fetch_assoc($services)){ ?><!-- looping semua data layanan -->
                    <div class="checkbox-item">
                        <label for="service_<?= $s['id'] ?>"><?= $s['nama_layanan'] ?></label><!-- Label untuk checkbox, menampilkan nama layanan -->
                        <input type="checkbox" name="service_ids[]" id="service_<?= $s['id'] ?>" value="<?= $s['id'] ?>"><!-- Checkbox untuk memilih layanan, name[] supaya bisa pilih banyak -->
                    </div>
                <?php } ?>
            </div>

            <button type="submit" name="submit">Simpan</button>
        </form>
    </div>
</div>
<!-- Script untuk toggle sidebar -->
<script>
    const sidebar = document.getElementById('sidebar');//ambil elemen sidebar dari halaman untuk bisa dibuka atau ditutup lewat JavaScript.
    const toggleBtn = document.querySelector('.toggle-btn');//ambil tombol yang dipakai untuk membuka sidebar.
    const closeBtn = document.querySelector('.close-btn');//ambil tombol yang dipakai untuk menutup sidebar.

    toggleBtn.addEventListener('click', () => {// Buka sidebar
        sidebar.classList.add('open');
    });

    closeBtn.addEventListener('click', () => {// tutup sidebar
        sidebar.classList.remove('open');
    });
</script>
