<?php
session_start();

if(isset($_SESSION['nama'])){

    // hapus semua session
    session_unset();
    session_destroy();

    echo "<script>
            alert('Berhasil logout!');
            window.location='login.php';
          </script>";
    exit;

}else{

    echo "<script>
            alert('Anda belum login!');
            window.location='login.php';
          </script>";
    exit;

}
?>