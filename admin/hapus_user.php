<?php
require '../config/koneksi.php';

$id = $_GET['id'];

$query = mysqli_query($conn,"DELETE FROM users WHERE id='$id'");

if($query){
    echo "<script>
            alert('User berhasil dihapus!');
            window.location='users.php';
          </script>";
}else{
    echo "<script>
            alert('Gagal menghapus user!');
            window.location='users.php';
          </script>";
}
?>