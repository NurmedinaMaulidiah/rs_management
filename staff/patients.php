<?php
session_start();
require '../config/koneksi.php';

// Pastikan role = staff
if($_SESSION['role'] !== 'staff'){
    header("Location: ../login.php");
    exit;
}
// searching
$search = trim($_GET['search'] ?? '');

// query ambil pasien + nama dokter
$query = "SELECT p.*, u.nama AS dokter, s.nama_layanan
          FROM patients p
          JOIN users u ON p.dokter_id = u.id
          JOIN services s ON p.service_id = s.id";

if($search != ''){
    $query .= " WHERE p.nama_pasien LIKE '%$search%'";
}

$result = mysqli_query($conn,$query);

// cek jika data kosong
if(mysqli_num_rows($result) == 0){
    echo "<script>alert('Pasien tidak ditemukan!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard List Pasien Staff - Healyn</title>
    <link rel="stylesheet" href="../css/style-staff.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    

<div class="topbarPatients">

<!-- kiri -->
<h3 class="page-title">Dashboard Staff</h3>

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


        <!-- Tambah Pasien -->
<div class="user-header">
<form method="GET" class="search-box">
    <input type="text" name="search" placeholder="Cari nama pasien...">
    <button type="submit">
        <i class="fa-solid fa-magnifying-glass"></i> Search
    </button>
</form>

<a href="patients_add_staff.php" class="btn-add">
    <i class="fa-solid fa-plus"></i> Tambah Pasien
</a>

</div>

<!-- table user -->
<div class = "user" >
    <table border='5'>
    <tr>
        <th colspan='7'>Data Pasien</th>
    </tr>

        <tr>
            <th>No</th>
            <th>Nama Pasien</th>
            <th>Jenis Kelamin</th>
            <th>Tanggal Lahir</th>
            <th>Alamat</th>
            <th>Dokter</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        while($row = mysqli_fetch_assoc($result)){
        ?>

        <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_pasien'] ?></td>
        <td><?= $row['jenis_kelamin'] ?></td>
        <td><?= $row['tanggal_lahir'] ?></td>
        <td><?= $row['alamat'] ?></td>
        <td><?= $row['dokter'] ?></td>
        <td>

        <a class="btn-edit" href="patients_edit_staff.php?id=<?= $row['id'] ?>">
        <i class="fa-solid fa-pen"></i> Edit
        </a>

        <a class="btn-delete" href="patients_delete_staff.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus Pasien?')">
        <i class="fa-solid fa-trash"></i> Delete
        </a>

        </td>
        </tr>

        <?php } ?>
    </table>
</div>
    </div>

  

</body>
</html>

