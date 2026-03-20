<?php
session_start();
require '../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_query($conn,"SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($data);

if(isset($_POST['submit'])){

$nama = $_POST['nama'];
$username = $_POST['username'];
$role = $_POST['role'];

mysqli_query($conn,"UPDATE users SET
nama='$nama',
username='$username',
role='$role'
WHERE id='$id'");

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
<div class = "dashboard">
        <!-- SIDEBAR -->
    <div class="sidebar">

<h2 class="logo">Healyn</h2>

<ul>
    <li>
        <i class="fa-solid fa-chart-line"> </i>
        <a href="dashboard.php">Dashboard</a>
    </li>

    <li>
        <i class="fa-solid fa-users"> </i>
        <a href="users.php">Users</a>
    </li>

    <li>
        <i class="fa-solid fa-hospital-user"> </i>
        <a href="patients.php">Patients</a>
</li>
<li>
    <i class="fa-solid fa-hospital-user"></i>
    <a href="services.php">Layanan</a>
</li>
<li>
    <i class="fa-solid fa-stethoscope"></i> 
    <a href="doctor_services.php">Layanan Dokter</a>
</li>
    <li>
        <i class="fa-solid fa-right-from-bracket"> </i>
        <a href="../logout.php">Logout</a>

</li>
</ul>

</div>

    <!-- MAIN CONTENT -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">

            <h3>Dashboard Admin</h3>

            <div class="admin">
                <i class="fa-solid fa-user"> </i>
                <?php echo $_SESSION['nama']; ?>  
                <!-- ngambil nama admin dari db utk ditampilkan -->
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
</body>


