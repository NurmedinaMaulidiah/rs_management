<?php
session_start();//session start ambil data user yg login
require '../config/koneksi.php';

$id = $_GET['id'];//ambil id layanan dari url
$service = mysqli_query($conn, "SELECT * FROM services WHERE id=$id");// ambil data layanan berdasarkan id
$s = mysqli_fetch_assoc($service);

if(isset($_POST['submit'])){ //jika submit
    $nama = trim($_POST['nama_layanan']);// ambil inputan + hapus spasi

    // VALIDASI KOSONG
    if($nama == ""){
        echo "<script>
                alert('Nama layanan harus diisi!');
                window.history.back();
              </script>";
        exit;
    }

    // CEK DUPLIKAT LAYANAN (kecuali id yang sedang diedit)
    $cek = mysqli_query($conn, "SELECT * FROM services 
                                WHERE nama_layanan='$nama' 
                                AND id != $id");

    if(mysqli_num_rows($cek) > 0){ // jika nama layanan sudah ada
        echo "<script>
                alert('Layanan sudah ada!');
                window.history.back();
              </script>";
        exit;
    }

    // UPDATE DATA LAYANAN
    $query = mysqli_query($conn, "UPDATE services SET nama_layanan='$nama' WHERE id=$id");

    if($query){ //jika berhasil
        echo "<script>
                alert('Layanan berhasil diupdate!');
                window.location='services.php';
              </script>";
    }else{//jika gagal
        echo "<script>
                alert('Gagal mengupdate layanan!');
                window.history.back();
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Layanan</title>
    <link rel="stylesheet" href="../css/style-dashboard.css">
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


<div class="boxTambahUser">
    <div class="formInput">

        <h2>Edit Layanan Rumah Sakit</h2>

        <form method="POST">

            <label>Nama Layanan</label><!-- isi otomatis dari database -->
            <input 
                type="text" 
                name="nama_layanan" 
                value="<?= $s['nama_layanan'] ?>" 
                placeholder="Nama Layanan"
            >

            <button type="submit" name="submit">
                Update Layanan
            </button>
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
