<?php
require '../config/koneksi.php';

// Ambil dokter
$doctors = mysqli_query($conn, "SELECT * FROM users WHERE role='dokter'");

// Ambil layanan
$services = mysqli_query($conn, "SELECT * FROM services");

if(isset($_POST['submit'])){
    $dokter_id = $_POST['dokter_id'];
    $service_ids = $_POST['service_ids']; // array dari checkbox

    // Simpan semua layanan
    foreach($service_ids as $sid){
        mysqli_query($conn, "INSERT INTO doctor_services (dokter_id, service_id) VALUES ($dokter_id, $sid)");
    }
    header("Location: doctor_services.php");
}
?>

<h2>Tambah Relasi Dokter ↔ Layanan</h2>
<form method="post">
    Dokter: 
    <select name="dokter_id" required>
        <option value="">--Pilih Dokter--</option>
        <?php while($d=mysqli_fetch_assoc($doctors)){ ?>
            <option value="<?= $d['id'] ?>"><?= $d['nama'] ?></option>
        <?php } ?>
    </select><br><br>

    Layanan:<br>
    <?php while($s=mysqli_fetch_assoc($services)){ ?>
        <input type="checkbox" name="service_ids[]" value="<?= $s['id'] ?>"> <?= $s['nama_layanan'] ?><br>
    <?php } ?>
    <br>
    <input type="submit" name="submit" value="Simpan">
</form>