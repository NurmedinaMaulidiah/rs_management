<?php
session_start();
require '../config/koneksi.php';

if($_SESSION['role'] !== 'dokter'){
    header("Location: ../login.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];

// Ambil pasien dokter ini
$sql = "SELECT p.id, p.nama_pasien, s.nama_layanan
        FROM patients p
        JOIN services s ON p.service_id = s.id
        WHERE p.dokter_id = $doctor_id
        ORDER BY p.nama_pasien";
$result = mysqli_query($conn, $sql);
?>

<h2>Daftar Pasien</h2>
<table border="1">
<tr>
    <th>No</th>
    <th>Nama Pasien</th>
    <th>Layanan</th>
    <th>Action</th>
</tr>
<?php $no=1; while($p = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $p['nama_pasien'] ?></td>
    <td><?= $p['nama_layanan'] ?></td>
    <td>
        <a href="patient_detail.php?id=<?= $p['id'] ?>">Detail / Rekam Medis</a>
    </td>
</tr>
<?php } ?>
</table>