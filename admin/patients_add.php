<!-- untuk atur id dokter sesuai layanan ketika add pasien -->
<?php
require '../config/koneksi.php';

// Ambil semua layanan
$services_result = mysqli_query($conn, "SELECT * FROM services");

// Simpan pasien
if(isset($_POST['submit'])){

    $nama = $_POST['nama_pasien'];
    $jk = $_POST['jenis_kelamin'];
    $tgl = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $service_id = $_POST['service_id'];
    $dokter_id = $_POST['dokter_id'];

    mysqli_query($conn,"INSERT INTO patients 
        (nama_pasien, jenis_kelamin, tanggal_lahir, alamat, dokter_id, service_id)
        VALUES 
        ('$nama','$jk','$tgl','$alamat','$dokter_id','$service_id')");

    header("Location: patients.php");
    exit;
}
?>

<h2>Tambah Pasien</h2>

<form method="post">

Nama Pasien  
<input type="text" name="nama_pasien" required><br><br>

Jenis Kelamin  
<select name="jenis_kelamin" required>
<option value="">--Pilih--</option>
<option value="L">Laki-laki</option>
<option value="P">Perempuan</option>
</select><br><br>

Tanggal Lahir  
<input type="date" name="tanggal_lahir" required><br><br>

Alamat  
<textarea name="alamat" required></textarea><br><br>

Layanan RS  
<select id="service_id" name="service_id" required>
<option value="">--Pilih Layanan--</option>

<?php
while($s = mysqli_fetch_assoc($services_result)){
?>

<option value="<?= $s['id'] ?>">
<?= $s['nama_layanan'] ?>
</option>

<?php } ?>

</select><br><br>

Dokter  
<select id="dokter_id" name="dokter_id" required>
<option value="">--Pilih Dokter--</option>
</select><br><br>

<input type="submit" name="submit" value="Tambah Pasien">

</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$('#service_id').change(function(){

    var service_id = $(this).val();

    if(service_id){

        $.ajax({
            url:'get_doctors.php',
            type:'GET',
            data:{service_id:service_id},

            success:function(data){

                var doctors = JSON.parse(data);

                var html = '<option value="">--Pilih Dokter--</option>';

                doctors.forEach(function(d){
                    html += '<option value="'+d.id+'">'+d.nama+'</option>';
                });

                $('#dokter_id').html(html);
            }

        });

    }else{

        $('#dokter_id').html('<option value="">--Pilih Dokter--</option>');

    }

});

</script>