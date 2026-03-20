<?php
require '../config/koneksi.php';

$service_id = $_GET['service_id'] ?? 0;
$doctors = [];

if($service_id){
    $sql = "SELECT u.id, u.nama
            FROM users u
            JOIN doctor_services ds ON u.id = ds.dokter_id
            WHERE u.role='dokter' AND ds.service_id=$service_id
            GROUP BY u.id
            ORDER BY u.nama";
    $res = mysqli_query($conn, $sql);
    while($r = mysqli_fetch_assoc($res)){
        $doctors[] = $r;
    }
}

// Kembalikan data JSON
echo json_encode($doctors);