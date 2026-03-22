<?php
session_start();
require '../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_query($conn,"SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($data);

if(isset($_POST['submit'])){

$nama = trim($_POST['nama']);
$username = trim($_POST['username']);
$role = $_POST['role'];

/* VALIDASI */

// nama wajib
if(empty($nama)){
    echo "<script>alert('Nama harus diisi!');window.history.back();</script>";
    exit;
}

// username wajib
if(empty($username)){
    echo "<script>alert('Username harus diisi!');window.history.back();</script>";
    exit;
}

// nama hanya huruf
if(!preg_match("/^[a-zA-Z ]*$/",$nama)){
    echo "<script>alert('Nama hanya boleh huruf!');window.history.back();</script>";
    exit;
}

// cek username sudah digunakan user lain
$cek = mysqli_query($conn,"SELECT * FROM users WHERE username='$username' AND id != '$id'");

if(mysqli_num_rows($cek) > 0){
    echo "<script>alert('Username sudah digunakan!');window.history.back();</script>";
    exit;
}

/* UPDATE DATA */

$query = mysqli_query($conn,"UPDATE users SET
nama='$nama',
username='$username',
role='$role'
WHERE id='$id'");

if($query){
    echo "<script>
            alert('User berhasil diupdate!');
            window.location='users.php';
          </script>";
}else{
    echo "<script>
            alert('Gagal mengupdate user!');
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
            <li><i class="fa-solid fa-notes-medical"></i><a href="patients.php">Layanan Rumah Sakit</a></li>
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


        <div class="boxTambahUser">
    <div class="formInput">
        <h2>Edit User Account</h2>
        <form method="POST">
            <!-- Nama -->
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($user['nama']); ?>">

            <!-- Username -->
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>">

            <!-- Role -->
            <label for="role">Role</label>
            <select name="role" id="role">
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : ''; ?>>Staff</option>
                <option value="dokter" <?= $user['role'] == 'dokter' ? 'selected' : ''; ?>>Dokter</option>
            </select>

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


