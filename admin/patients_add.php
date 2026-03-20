<?php
require '../config/koneksi.php';

// Ambil semua layanan
$services_result = mysqli_query($conn, "SELECT * FROM services");

// Simpan pasien jika submit
if(isset($_POST['submit'])){
    $nama = $_POST['nama_pasien'];
    $jk = $_POST['jenis_kelamin'];
    $tgl = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $service_id = $_POST['service_id'];
    $dokter_id = $_POST['dokter_id'];

    mysqli_query($conn, "INSERT INTO patients (nama_pasien, jenis_kelamin, tanggal_lahir, alamat, dokter_id, service_id)
    VALUES ('$nama','$jk','$tgl','$alamat','$dokter_id','$service_id')");

    header("Location: patients.php");
    exit;
}
?>

<form method="post">
    Nama Pasien: <input type="text" name="nama_pasien" required><br>

    Jenis Kelamin:
    <select name="jenis_kelamin" required>
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
    </select><br>

    Tanggal Lahir: <input type="date" name="tanggal_lahir" required><br>

    Alamat: <textarea name="alamat" required></textarea><br>

    Layanan RS:
    <select id="service_id" name="service_id" required>
        <option value="">--Pilih Layanan--</option>
        <?php while($s = mysqli_fetch_assoc($services_result)){ ?>
            <option value="<?= $s['id'] ?>"><?= $s['nama_layanan'] ?></option>
        <?php } ?>
    </select><br>

    Dokter:
    <select id="dokter_id" name="dokter_id" required>
        <option value="">--Pilih Dokter--</option>
    </select><br><br>

    <input type="submit" name="submit" value="Tambah Pasien">
</form>

<!-- JQuery AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#service_id').change(function(){
    var service_id = $(this).val();
    if(service_id){
        $.get('get_doctors.php', {service_id: service_id}, function(data){
            var doctors = JSON.parse(data);
            var options = '<option value="">--Pilih Dokter--</option>';
            doctors.forEach(function(d){
                options += '<option value="'+d.id+'">'+d.nama+'</option>';
            });
            $('#dokter_id').html(options);
        });
    } else {
        $('#dokter_id').html('<option value="">--Pilih Dokter--</option>');
    }
});
</script>