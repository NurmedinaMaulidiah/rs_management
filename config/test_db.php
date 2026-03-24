<?php
require '../config/koneksi.php'; // pastikan path ini bener

if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
} else {
    echo "Koneksi ke database berhasil!";
}