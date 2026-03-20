<?php
session_start();
include "config/koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

// cek user
$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
$data = mysqli_fetch_assoc($query);

if($data){
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['role'] = $data['role'];

    if($data['role'] == "admin"){
        header("Location: admin/dashboard.php");
    } elseif($data['role'] == "staff"){
        header("Location: staff/dashboard.php");
    } elseif($data['role'] == "dokter"){
        header("Location: doctor/patients.php");
    }
} else {
    echo "Login gagal! <a href='login.php'>Kembali</a>";
}
?>