<?php
session_start();
require '../config/koneksi.php';

// Pastikan role staff
if($_SESSION['role'] !== 'staff'){
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

// Ambil data pasien
$patient_res = mysqli_query($conn, "SELECT * FROM patients WHERE id=$id");
$patient = mysqli_fetch_assoc($patient_res);

if(!$patient){
    echo "Pasien tidak ditemukan!";
    exit;
}

// Ambil semua layanan
$services = mysqli_query($conn, "SELECT * FROM services");

// Ambil semua dokter
$doctors = mysqli_query($conn, "SELECT id, nama FROM users WHERE role='dokter' ORDER BY nama");

// Jika form submit simpan perubahan
if(isset($_POST['submit'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama_pasien']);
    $jk = $_POST['jenis_kelamin'];
    $tgl = $_POST['tanggal_lahir'];
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $service_id = $_POST['service_id'];
    $dokter_id = $_POST['dokter_id'];

    mysqli_query($conn, "UPDATE patients SET 
        nama_pasien='$nama', 
        jenis_kelamin='$jk', 
        tanggal_lahir='$tgl', 
        alamat='$alamat', 
        service_id='$service_id', 
        dokter_id='$dokter_id'
        WHERE id=$id");

    header("Location: patients.php");
    exit;
}
?>

<h2>Edit Pasien</h2>
<form method="post">
    Nama Pasien:<br>
    <input type="text" name="nama_pasien" value="<?= $patient['nama_pasien'] ?>" required><br><br>

    Jenis Kelamin:<br>
    <select name="jenis_kelamin" required>
        <option value="L" <?= $patient['jenis_kelamin']=='L'?'selected':'' ?>>Laki-laki</option>
        <option value="P" <?= $patient['jenis_kelamin']=='P'?'selected':'' ?>>Perempuan</option>
    </select><br><br>

    Tanggal Lahir:<br>
    <input type="date" name="tanggal_lahir" value="<?= $patient['tanggal_lahir'] ?>" required><br><br>

    Alamat:<br>
    <textarea name="alamat" required><?= $patient['alamat'] ?></textarea><br><br>

    Layanan RS:<br>
    <select name="service_id" required>
        <?php while($s = mysqli_fetch_assoc($services)){ ?>
            <option value="<?= $s['id'] ?>" <?= $patient['service_id']==$s['id']?'selected':'' ?>>
                <?= $s['nama_layanan'] ?>
            </option>
        <?php } ?>
    </select><br><br>

    Dokter:<br>
    <select name="dokter_id" required>
        <?php while($d = mysqli_fetch_assoc($doctors)){ ?>
            <option value="<?= $d['id'] ?>" <?= $patient['dokter_id']==$d['id']?'selected':'' ?>>
                <?= $d['nama'] ?>
            </option>
        <?php } ?>
    </select><br><br>

    <input type="submit" name="submit" value="Simpan Perubahan">
</form>