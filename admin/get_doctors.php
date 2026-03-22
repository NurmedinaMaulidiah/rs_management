<?php
require '../config/koneksi.php';

$service_id = intval($_GET['service_id'] ?? 0);

$doctors = [];

if($service_id){

    $sql = "SELECT u.id, u.nama
            FROM users u
            JOIN doctor_services ds ON u.id = ds.dokter_id
            WHERE u.role='dokter' AND ds.service_id = $service_id
            ORDER BY u.nama";

    $result = mysqli_query($conn,$sql);

    while($row = mysqli_fetch_assoc($result)){
        $doctors[] = $row;
    }
}

echo json_encode($doctors);