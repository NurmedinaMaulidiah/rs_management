<?php
require '../config/koneksi.php';

// Ambil semua relasi
$query = "
SELECT ds.id, u.nama AS dokter, GROUP_CONCAT(s.nama_layanan SEPARATOR ', ') AS layanan
FROM doctor_services ds
JOIN users u ON ds.dokter_id = u.id
JOIN services s ON ds.service_id = s.id
GROUP BY ds.dokter_id
ORDER BY u.nama
";
$result = mysqli_query($conn, $query);
?>

<h2>Relasi Dokter ↔ Layanan</h2>
<a href="doctor_services_add.php">Tambah Relasi</a>
<table border="1" cellpadding="5">
<tr>
    <th>No</th>
    <th>Dokter</th>
    <th>Layanan</th>
    <th>Aksi</th>
</tr>
<?php $no=1; while($row=mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $row['dokter'] ?></td>
    <td><?= $row['layanan'] ?></td>
    <td>
        <a href="doctor_services_edit.php?id=<?= $row['id'] ?>">Edit</a> | 
        <a href="doctor_services_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus relasi?')">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>