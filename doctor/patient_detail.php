<?php
session_start();
require '../config/koneksi.php';

if($_SESSION['role'] !== 'dokter'){
    header("Location: ../login.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];
$patient_id = $_GET['id'] ?? 0;

// Pastikan pasien milik dokter
$sql = "SELECT p.*, s.nama_layanan
        FROM patients p
        JOIN services s ON p.service_id = s.id
        WHERE p.id = $patient_id AND p.dokter_id = $doctor_id";
$res = mysqli_query($conn, $sql);
$patient = mysqli_fetch_assoc($res);

if(!$patient){
    echo "Pasien tidak ditemukan atau bukan pasien Anda!";
    exit;
}

// Ambil rekam medis pasien
$medicals = mysqli_query($conn, "SELECT * FROM medical_records WHERE patient_id = $patient_id ORDER BY created_at DESC");

// Tambah rekam medis
if(isset($_POST['add_medical'])){
    $keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);
    $diagnosa = mysqli_real_escape_string($conn, $_POST['diagnosa']);
    $tindakan = mysqli_real_escape_string($conn, $_POST['tindakan']);

    mysqli_query($conn, "INSERT INTO medical_records (patient_id, keluhan, diagnosa, tindakan)
    VALUES ('$patient_id','$keluhan','$diagnosa','$tindakan')");

    header("Location: patient_detail.php?id=$patient_id");
    exit;
}
?>

<h2>Detail Pasien</h2>
<p>Nama: <?= $patient['nama_pasien'] ?></p>
<p>Layanan: <?= $patient['nama_layanan'] ?></p>

<h3>Rekam Medis</h3>
<table border="1">
<tr>
    <th>Tanggal</th>
    <th>Keluhan</th>
    <th>Diagnosa</th>
    <th>Tindakan</th>
    <th>Aksi</th>
</tr>
<?php while($m = mysqli_fetch_assoc($medicals)) { ?>
<tr>
    <td><?= $m['created_at'] ?></td>
    <td><?= $m['keluhan'] ?></td>
    <td><?= $m['diagnosa'] ?></td>
    <td><?= $m['tindakan'] ?></td>
    <td>
        <!-- Link ke halaman edit rekam medis -->
        <a href="medical_edit.php?id=<?= $m['id'] ?>">Edit</a>
    </td>
</tr>
<?php } ?>
</table>

<h3>Tambah Rekam Medis</h3>
<form method="post">
    Keluhan:<br><textarea name="keluhan" required></textarea><br>
    Diagnosa:<br><textarea name="diagnosa" required></textarea><br>
    Tindakan:<br><textarea name="tindakan" required></textarea><br><br>
    <input type="submit" name="add_medical" value="Simpan Rekam Medis">
</form>