<?php
session_start();
require '../config/koneksi.php';

$dokter_id = $_GET['dokter_id'] ?? 0;

// Ambil nama dokter
$res = mysqli_query($conn, "
SELECT u.nama
FROM users u
WHERE u.id = $dokter_id
LIMIT 1
");

$row = mysqli_fetch_assoc($res);
$nama = $row['nama'] ?? '';

// Ambil semua layanan
$services = mysqli_query($conn, "SELECT * FROM services");

// Ambil layanan dokter saat ini
$current = mysqli_query($conn, "SELECT service_id FROM doctor_services WHERE dokter_id=$dokter_id");
$current_services = [];

while($c=mysqli_fetch_assoc($current)){
    $current_services[] = $c['service_id'];
}

if(isset($_POST['submit'])){

    // cek dokter
    if(empty($dokter_id)){
        echo "<script>alert('Dokter belum dipilih!');</script>";
    }

    // cek layanan
    elseif(empty($_POST['service_ids'])){
        echo "<script>alert('Pilih minimal satu layanan!');</script>";
    }

    else{

        $service_ids = $_POST['service_ids'];

        // Hapus layanan lama dokter
        mysqli_query($conn, "DELETE FROM doctor_services WHERE dokter_id=$dokter_id");

        // Insert layanan baru
        foreach($service_ids as $sid){
            mysqli_query($conn, "INSERT INTO doctor_services (dokter_id, service_id) VALUES ($dokter_id, $sid)");
        }

        echo "<script>
        alert('Layanan berhasil diperbarui');
        window.location='doctor_services.php';
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
    <title>Dashboard Edit List Layanan Dokter Admin - Healyn</title>
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
        <div class="formInputServices">

<h2>Edit Layanan Dokter</h2>
<form method="post">
    <label>Dokter</label>
    <input type="text" value="<?= $nama ?>" readonly>
    <label>Layanan</label>
    <div class="checkbox-group">
        <?php while($s=mysqli_fetch_assoc($services)){ ?>
            <div class="checkbox-item">
                <label for="service_<?= $s['id'] ?>">
                    <?= $s['nama_layanan'] ?>
                </label>

                <input 
                    type="checkbox"
                    name="service_ids[]"
                    id="service_<?= $s['id'] ?>"
                    value="<?= $s['id'] ?>"
                    <?= in_array($s['id'],$current_services) ? 'checked' : '' ?>
                >
            </div>
        <?php } ?>
    </div>

    <button type="submit" name="submit">Simpan</button>

</form>

</div>
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