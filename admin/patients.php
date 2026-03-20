<?php
session_start();
require '../config/koneksi.php';

// ambil semua pasien dengan nama dokter & layanan
$sql = "SELECT p.*, u.nama AS dokter, s.nama_layanan 
        FROM patients p
        JOIN users u ON p.dokter_id = u.id
        JOIN services s ON p.service_id = s.id";
$result = mysqli_query($conn, $sql);
?>

<h2>Daftar Pasien</h2>
<a href="patients_add.php">Tambah Pasien</a>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Nama Pasien</th>
        <th>Jenis Kelamin</th>
        <th>Tanggal Lahir</th>
        <th>Alamat</th>
        <th>Dokter</th>
        <th>Layanan RS</th>
        <th>Aksi</th>
    </tr>
    <?php $no=1; while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_pasien'] ?></td>
        <td><?= $row['jenis_kelamin'] ?></td>
        <td><?= $row['tanggal_lahir'] ?></td>
        <td><?= $row['alamat'] ?></td>
        <td><?= $row['dokter'] ?></td>
        <td><?= $row['nama_layanan'] ?></td>
        <td>
            <a href="patients_edit.php?id=<?= $row['id'] ?>">Edit</a> |
            <a href="patients_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus pasien ini?')">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>