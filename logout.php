<?php
session_start();// Memulai session, supaya bisa mengakses data session user

if(isset($_SESSION['nama'])){// Cek apakah user sedang login

    // hapus semua data session
    session_unset(); // Menghapus semua variabel session
    session_destroy(); //hancurkan session
// Tampilkan pesan berhasil logout dan arahkan ke halaman login
    echo "<script>
            alert('Berhasil logout!');
            window.location='login.php';
          </script>";
    exit; //berhentikan eksekusi script

}else{// Jika user belum login

    echo "<script>
            alert('Anda belum login!');
            window.location='login.php';
          </script>";
    exit;

}
?>