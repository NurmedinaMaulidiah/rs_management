<?php
require '../config/koneksi.php';  // Hubungkan ke database

$service_id = intval($_GET['service_id'] ?? 0);// Ambil service_id dari parameter GET, pastikan bertipe integer

$doctors = [];// Siapkan array kosong untuk menyimpan hasil dokter

if($service_id){// Jika service_id ada (tidak nol)
// Query untuk ambil dokter (nama dan id) dari tabel user  yang menyediakan layanan tertentu (servicesid), lalu tampilkna berdasrkan nama dokter
    $sql = "SELECT u.id, u.nama
            FROM users u
            JOIN doctor_services ds ON u.id = ds.dokter_id
            WHERE u.role='dokter' AND ds.service_id = $service_id
            ORDER BY u.nama";
//intinya menampilkna nama dokter berdasarkan layanan atau services yg dipilih
    $result = mysqli_query($conn,$sql);
// Masukkan setiap dokter ke array $doctors
    while($row = mysqli_fetch_assoc($result)){
        $doctors[] = $row;
    }
}

echo json_encode($doctors);// mengubah data menjadi format JSON agar bisa dipakai di AJAX/JavaScript.