<?php
session_start();// Mulai session untuk akses data login
require '../config/koneksi.php';

// Ambil semua data layanan dari tabel services
$services_result = mysqli_query($conn, "SELECT * FROM services");

if(isset($_POST['submit'])){// Jika tombol submit ditekan

    $nama = trim($_POST['nama_pasien']);// Ambil data dari form
    $jk = $_POST['jenis_kelamin'];
    $tgl = $_POST['tanggal_lahir'];
    $alamat = trim($_POST['alamat']);
    $service_id = $_POST['service_id'];
    $dokter_id = $_POST['dokter_id'];

    /* VALIDASI */
    if(// Cek semua input harus diisi
        $nama == "" ||
        $jk == "" ||
        $tgl == "" ||
        $alamat == "" ||
        $service_id == "" ||
        $dokter_id == ""
    ){
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

    /* INSERT DATA KE TABEL PASIEN*/
    $query = mysqli_query($conn,"INSERT INTO patients 
        (nama_pasien, jenis_kelamin, tanggal_lahir, alamat, dokter_id, service_id)
        VALUES 
        ('$nama','$jk','$tgl','$alamat','$dokter_id','$service_id')");

    if($query){// Jika berhasil
        echo "<script>
                alert('Pasien berhasil ditambahkan!');
                window.location='patients.php';
              </script>";
    }else{// Jika gagal
        echo "<script>
                alert('Gagal menambahkan pasien!');
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
    <title>Add Pasien</title>
    <link rel="stylesheet" href="../css/style-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>


<body>
<div class="dashboard">
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2 class="logo">Healyn</h2>
            <span class="close-btn" onclick="closeSidebar()">✖</span>
        </div>
        <ul>
            <li><i class="fa-solid fa-chart-line"></i><a href="dashboard.php">Dashboard</a></li>
            <li><i class="fa-solid fa-users"></i><a href="users.php">Users</a></li>
            <li><i class="fa-solid fa-hospital-user"></i><a href="patients.php">Patients</a></li>
            <li><i class="fa-solid fa-notes-medical"></i><a href="services.php">Layanan Rumah Sakit</a></li>
            <li><i class="fa-solid fa-stethoscope"></i> <a href="doctor_services.php">Layanan Dokter</a></li>
            <li><i class="fa-solid fa-right-from-bracket"></i><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main" id="main">
        <!-- TOPBAR -->
        <div class="topbarPatients">
            <span class="toggle-btn" onclick="openSidebar()">☰</span>
            <h3>Dashboard Admin</h3>
            <div class="admin">
                <i class="fa-solid fa-user"></i>
                <?= $_SESSION['nama']; ?>
            </div>
        </div>

    <div class ="boxTambahUser">
        <div class="formInput">
        <h2>Tambah Pasien Baru</h2>
        <form method="POST">

            <label>Nama Pasien</label>
            <input type="text" name="nama_pasien" placeholder="Nama Pasien">

            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin">
            <option value="">--Pilih--</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
            </select>

            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir">

            <label>Alamat</label>
            <textarea name="alamat" placeholder="Alamat Pasien"></textarea>

            <label>Layanan RS</label>
            <select id="service_id" name="service_id">

            <option value="">--Pilih Layanan--</option>

            <?php
            // Loop data layanan dari database, jadi data layanan semuanya akan tampil dari tabel services
            while($s = mysqli_fetch_assoc($services_result)){
            ?>

            <option value="<?= $s['id'] ?>"><!-- tampilkan nama layanan -->
            <?= $s['nama_layanan'] ?>
            </option>

            <?php } ?>

            </select>
 <!-- Dropdown dokter (akan diisi otomatis via AJAX) -->
 <!-- pake ajax biar dokter yang tampil selalu berubah sesuai layanan yang dipilih,tanpa reload -->
            <label>Dokter</label>
            <select id="dokter_id" name="dokter_id">
            <option value="">--Pilih Dokter--</option>
            </select>

            <button type="submit" name="submit">Simpan</button>

        </form>
        </div>

    </div>


<!-- library jcquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
//ketika user pilih layanan baru maka fungsi ini jalan, yaitu...
$('#service_id').change(function(){

    var service_id = $(this).val();// ambil id layanan yang dipilih

    if(service_id){//cek apakah user pilih layanan, kalau iya jalankan ajax, kalo ga pilih layanan reset dropdown jadi pilih dokter
//jalankan ajax
        $.ajax({// AJAX ambil dokter sesuai layanan
            url:'get_doctors.php',// server php ambil data dokter sesuai layanan dari db, trs data dokter dikembalikan ke json biar bisa dipake ajax
            type:'GET',
            data:{service_id:service_id},//tanpa relaod
// jika server berhasil merespon
            success:function(data){//fungsi untuk kembalikan data dokter dalam format json biar bisa di looping sama js

                var doctors = JSON.parse(data);// ubah  data dokter dari JSON string jadi array JavaScript.

                var html = '<option value="">--Pilih Dokter--</option>';//reset dropdown biar user pilih dokter

                doctors.forEach(function(d){// masukkan data dokter array biar di looping untuk dropdown
                    html += '<option value="'+d.id+'">'+d.nama+'</option>';
                });

                $('#dokter_id').html(html);// tampilkan ke dropdown dokter dengan reload otomatis
            }
            //Pilih layanan → server PHP (get_doctors.php) ambil dokter →
            // kirim data JSON → diubah jadi array JS → looping isi dropdown dengan ID & nama dokter 
            //→ dropdown berubah otomatis tanpa reload.
        });
        //pake ajax Supaya dropdown dokter bisa berubah otomatis tanpa reload halaman,
//Saat user pilih layanan, sistem kirim service_id ke file PHP lewat AJAX,
// lalu PHP ambil data dokter yang sesuai, kirim balik dalam bentuk JSON, 
//dan hasilnya langsung ditampilkan ke dropdown dokter.
    }else{
// reset dropdown dokter jika tidak pilih layanan
        $('#dokter_id').html('<option value="">--Pilih Dokter--</option>');

    }

});

</script>
    
        <script> 

            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.toggle-btn');
            const closeBtn = document.querySelector('.close-btn');

            toggleBtn.addEventListener('click', () => {
                sidebar.classList.add('open');
            });

            closeBtn.addEventListener('click', () => {
                sidebar.classList.remove('open');
            });
            </script>

</body>
</html>

<!-- JSON format standar untuk bertukar data antara server dan browser. -->
<!-- //server ke browser kirim data dalam bentuk json
//jadi harus di ubah ke array biar bisa dilooping sama js -->
