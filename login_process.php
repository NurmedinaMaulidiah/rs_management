<?php
session_start(); // Memulai session untuk menyimpan data user setelah login
include "config/koneksi.php";// Memasukkan file koneksi database
// Ambil data dari form login dari form input
$username = $_POST['username'];
$password = $_POST['password'];

//Cek apakah ada user di database dengan username & password yang sama
$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
$data = mysqli_fetch_assoc($query);

if($data){ //jika data user ditemukan
    $_SESSION['user_id'] = $data['id'];// Simpan info user ke session
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['role'] = $data['role'];

    if($data['role'] == "admin"){// Arahkan user ke halaman sesuai role
        header("Location: admin/dashboard.php");
    } elseif($data['role'] == "staff"){
        header("Location: staff/dashboard.php");
    } elseif($data['role'] == "dokter"){
        header("Location: doctor/patients.php");
    }
} else {// Jika username & password salah
    echo "Login gagal! <a href='login.php'>Kembali</a>";
}
?>