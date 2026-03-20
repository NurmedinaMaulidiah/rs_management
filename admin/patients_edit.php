<?php
session_start();
require '../config/koneksi.php';

$id = $_GET['id'];

// ambil data pasien
$patient = mysqli_query($conn, "SELECT * FROM patients WHERE id=$id");
$p = mysqli_fetch_assoc($patient);

// ambil daftar dokter & layanan
$doctors = mysqli_query($conn, "SELECT id, nama FROM users WHERE role='dokter'");
$services = mysqli_query($conn, "SELECT id, nama_layanan FROM services");

if(isset($_POST['submit'])){
    $nama = $_POST['nama_pasien'];
    $jk = $_POST['jenis_kelamin'];
    $tgl = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $dokter = $_POST['dokter_id'];
    $service = $_POST['service_id'];

    $query = "UPDATE patients SET 
              nama_pasien='$nama',
              jenis_kelamin='$jk',
              tanggal_lahir='$tgl',
              alamat='$alamat',
              dokter_id=$dokter,
              service_id=$service
              WHERE id=$id";
    mysqli_query($conn, $query);
    header("Location: patients.php");
}
?>

<h2>Edit Pasien</h2>
<form method="post">
    Nama Pasien: <input type="text" name="nama_pasien" value="<?= $p['nama_pasien'] ?>" required><br>
    Jenis Kelamin: 
    <select name="jenis_kelamin" required>
        <option value="L" <?= $p['jenis_kelamin']=='L'?'selected':'' ?>>Laki-laki</option>
        <option value="P" <?= $p['jenis_kelamin']=='P'?'selected':'' ?>>Perempuan</option>
    </select><br>
    Tanggal Lahir: <input type="date" name="tanggal_lahir" value="<?= $p['tanggal_lahir'] ?>" required><br>
    Alamat: <textarea name="alamat" required><?= $p['alamat'] ?></textarea><br>
    Dokter: 
    <select name="dokter_id" required>
        <?php while($d = mysqli_fetch_assoc($doctors)){ ?>
            <option value="<?= $d['id'] ?>" <?= $p['dokter_id']==$d['id']?'selected':'' ?>><?= $d['nama'] ?></option>
        <?php } ?>
    </select><br>
    Layanan RS: 
    <select name="service_id" required>
        <?php while($s = mysqli_fetch_assoc($services)){ ?>
            <option value="<?= $s['id'] ?>" <?= $p['service_id']==$s['id']?'selected':'' ?>><?= $s['nama_layanan'] ?></option>
        <?php } ?>
    </select><br><br>
    <input type="submit" name="submit" value="Update Pasien">
</form>