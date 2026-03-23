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

    $nama = trim($_POST['nama_pasien']);
    $jk = $_POST['jenis_kelamin'];
    $tgl = $_POST['tanggal_lahir'];
    $alamat = trim($_POST['alamat']);
    $dokter = $_POST['dokter_id'];
    $service = $_POST['service_id'];

    /* VALIDASI */

    // semua harus diisi
    if($nama == "" || $jk == "" || $tgl == "" || $alamat == "" || $dokter == "" || $service == ""){
        echo "<script>
                alert('Semua data harus diisi!');
                window.history.back();
              </script>";
        exit;
    }

    // nama hanya huruf
    if(!preg_match("/^[a-zA-Z ]*$/",$nama)){
        echo "<script>
                alert('Nama pasien hanya boleh huruf!');
                window.history.back();
              </script>";
        exit;
    }

    // tanggal lahir tidak boleh lebih dari hari ini
    if($tgl > date("Y-m-d")){
        echo "<script>
                alert('Tanggal lahir tidak valid!');
                window.history.back();
              </script>";
        exit;
    }

    /* UPDATE DATA */

    $query = "UPDATE patients SET 
              nama_pasien='$nama',
              jenis_kelamin='$jk',
              tanggal_lahir='$tgl',
              alamat='$alamat',
              dokter_id=$dokter,
              service_id=$service
              WHERE id=$id";

    $result = mysqli_query($conn, $query);

    if($result){
        echo "<script>
                alert('Data pasien berhasil diupdate!');
                window.location='patients.php';
              </script>";
    }else{
        echo "<script>
                alert('Gagal mengupdate data pasien!');
                window.history.back();
              </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pasien - Staff</title>
    <link rel="stylesheet" href="../css/style-staff.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- MAIN CONTENT -->
    <div class="mainPatients" id="main">
        <!-- TOPBAR -->
        <div class="topbarPatients">
        <h3>
            <a href="patients.php" style="margin-right:10px;">
            <i class="fa-solid fa-arrow-left"></i>
            </a>
            Dashboard Staff
        </h3>
            <div class="admin">
                <i class="fa-solid fa-user"></i>
                <?= $_SESSION['nama']; ?>
            </div>
        </div>

        <!-- nama classnya sama semua kaya input user -->
        <div class="boxTambahUser">
        <div class="formInput">

            <h2>Edit Pasien</h2>

            <form method="POST">

            <label>Nama Pasien</label>
            <input type="text" name="nama_pasien" 
            value="<?= $p['nama_pasien'] ?>" 
            placeholder="Nama Pasien">

            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin">
            <option value="">--Pilih--</option>
            <option value="L" <?= $p['jenis_kelamin']=='L'?'selected':'' ?>>Laki-laki</option>
            <option value="P" <?= $p['jenis_kelamin']=='P'?'selected':'' ?>>Perempuan</option>
            </select>

            <label>Tanggal Lahir</label>
            <input type="date" 
            name="tanggal_lahir" 
            value="<?= $p['tanggal_lahir'] ?>" 
            max="<?= date('Y-m-d') ?>">

            <label>Alamat</label>
            <textarea name="alamat" placeholder="Alamat Pasien"><?= $p['alamat'] ?></textarea>

            <label>Dokter</label>
            <select name="dokter_id">
            <option value="">--Pilih Dokter--</option>

            <?php while($d = mysqli_fetch_assoc($doctors)){ ?>
            <option value="<?= $d['id'] ?>" <?= $p['dokter_id']==$d['id']?'selected':'' ?>>
            <?= $d['nama'] ?>
            </option>
            <?php } ?>
            </select>

            <label>Layanan RS</label>
            <select name="service_id">
            <option value="">--Pilih Layanan--</option>

            <?php while($s = mysqli_fetch_assoc($services)){ ?>
            <option value="<?= $s['id'] ?>" <?= $p['service_id']==$s['id']?'selected':'' ?>>
            <?= $s['nama_layanan'] ?>
            </option>
            <?php } ?>

            </select>
            <button type="submit" name="submit">Update Pasien</button>

        </form>

</div>
</div>
</body>
</html>




