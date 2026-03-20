<?php
require '../config/koneksi.php';

$id = $_GET['id'];

// Ambil dokter_id dari relasi
$res = mysqli_query($conn, "SELECT dokter_id FROM doctor_services WHERE id=$id LIMIT 1");
$row = mysqli_fetch_assoc($res);
$dokter_id = $row['dokter_id'];

// Ambil semua layanan
$services = mysqli_query($conn, "SELECT * FROM services");

// Ambil layanan dokter saat ini
$current = mysqli_query($conn, "SELECT service_id FROM doctor_services WHERE dokter_id=$dokter_id");
$current_services = [];
while($c=mysqli_fetch_assoc($current)){
    $current_services[] = $c['service_id'];
}

if(isset($_POST['submit'])){
    $service_ids = $_POST['service_ids'];

    // Hapus layanan lama dokter
    mysqli_query($conn, "DELETE FROM doctor_services WHERE dokter_id=$dokter_id");

    // Insert layanan baru
    foreach($service_ids as $sid){
        mysqli_query($conn, "INSERT INTO doctor_services (dokter_id, service_id) VALUES ($dokter_id, $sid)");
    }
    header("Location: doctor_services.php");
}
?>

<h2>Edit Relasi Dokter ↔ Layanan</h2>
<form method="post">
    Dokter: <?= $dokter_id ?><br><br>

    Layanan:<br>
    <?php while($s=mysqli_fetch_assoc($services)){ ?>
        <input type="checkbox" name="service_ids[]" value="<?= $s['id'] ?>" <?= in_array($s['id'],$current_services)?'checked':'' ?>> <?= $s['nama_layanan'] ?><br>
    <?php } ?>
    <br>
    <input type="submit" name="submit" value="Simpan">
</form>