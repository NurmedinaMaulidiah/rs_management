<?php
session_start();
require '../config/koneksi.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM patients WHERE id=$id");
header("Location: patients.php");
?>

if($query){
    echo "<script>
            alert('User berhasil dihapus!');
            window.location='patients.php';
          </script>";
}else{
    echo "<script>
            alert('Gagal menghapus user!');
            window.location='patients.php';
          </script>";
}
?>