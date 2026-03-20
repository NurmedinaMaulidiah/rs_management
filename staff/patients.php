<?php
session_start();
require '../config/koneksi.php';

// Pastikan role = staff
if($_SESSION['role'] !== 'staff'){
    header("Location: ../login.php");
    exit;
}

// Ambil semua pasien
$sql = "SELECT p.id, p.nama_pasien, s.nama_layanan, u.nama AS dokter
        FROM patients p
        JOIN services s ON p.service_id = s.id
        JOIN users u ON p.dokter_id = u.id
        ORDER BY p.nama_pasien";
$result = mysqli_query($conn, $sql);
?>

<h2>Daftar Pasien</h2>
<a href="patients_add_staff.php">Tambah Pasien</a>
<table border="1">
<tr>
    <th>No</th>
    <th>Nama Pasien</th>
    <th>Layanan</th>
    <th>Dokter</th>
    <th>Action</th>
</tr>
<?php $no=1; while($p = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $p['nama_pasien'] ?></td>
    <td><?= $p['nama_layanan'] ?></td>
    <td><?= $p['dokter'] ?></td>
    <td>
        <a href="patients_edit_staff.php?id=<?= $p['id'] ?>">Edit</a> |
        <a href="patients_delete_staff.php?id=<?= $p['id'] ?>" onclick="return confirm('Yakin ingin hapus pasien ini?')">Hapus</a> |
        <!-- <a href="patient_detail.php?id=<?= $p['id'] ?>">Detail</a> -->
    </td>
</tr>
<?php } ?>
</table>