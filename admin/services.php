<?php
require '../config/koneksi.php';

$result = mysqli_query($conn, "SELECT * FROM services");
?>

<h2>Daftar Layanan RS</h2>
<a href="services_add.php">Tambah Layanan</a>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Nama Layanan</th>
        <th>Aksi</th>
    </tr>
    <?php $no=1; while($row = mysqli_fetch_assoc($result)){ ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_layanan'] ?></td>
        <td>
            <a href="services_edit.php?id=<?= $row['id'] ?>">Edit</a> |
            <a href="services_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus layanan ini?')">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>