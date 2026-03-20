<?php
require '../config/koneksi.php';

$id = $_GET['id'] ?? 0;

// Hapus relasi berdasarkan id
mysqli_query($conn, "DELETE FROM doctor_services WHERE id=$id");

header("Location: doctor_services.php");