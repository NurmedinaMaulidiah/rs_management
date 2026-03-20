<?php
session_start();
require '../config/koneksi.php';

// Pastikan role staff
if($_SESSION['role'] !== 'staff'){
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

// Pastikan pasien ada
$check = mysqli_query($conn, "SELECT * FROM patients WHERE id=$id");
if(mysqli_num_rows($check) > 0){
    mysqli_query($conn, "DELETE FROM patients WHERE id=$id");
}

// Kembali ke daftar pasien
header("Location: patients.php");
exit;