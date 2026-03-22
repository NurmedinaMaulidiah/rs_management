<!-- ini halaman list user -->

<?php
session_start();
require '../config/koneksi.php';

$result = mysqli_query($conn,"SELECT * FROM users");


// searching
$search = trim($_GET['search'] ?? '');

$query = "SELECT * FROM users";

if($search != ''){
    $query .= " WHERE nama LIKE '%$search%' 
                OR username LIKE '%$search%'";
}
$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result) == 0){
    echo "<script>alert('User tidak ditemukan!');</script>";
}
$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard List User Admin - Healyn</title>
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


        <!-- Tambah User -->
<div class="user-header">
<form method="GET" class="search-box">
    <input type="text" name="search" placeholder="Cari nama user...">
    <button type="submit">
        <i class="fa-solid fa-magnifying-glass"></i> Search
    </button>
</form>

<a href="tambah_user.php" class="btn-add">
    <i class="fa-solid fa-plus"></i> Tambah User
</a>

</div>

        
<!-- table user -->
<div class = "user" >
    <table border='5'>
    <tr>
        <th colspan='5'>Data User</th>
    </tr>

        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        while($row = mysqli_fetch_assoc($result)){
        ?>

        <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['username'] ?></td>
        <td><?= $row['role'] ?></td>
        <td>

        <a class="btn-edit" href="edit_user.php?id=<?= $row['id'] ?>">
        <i class="fa-solid fa-pen"></i> Edit
        </a>

        <a class="btn-delete" href="hapus_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus user?')">
        <i class="fa-solid fa-trash"></i> Delete
        </a>

        </td>
        </tr>

        <?php } ?>
    </table>
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


