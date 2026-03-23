<?php
session_start();
require '../config/koneksi.php';

// Pastikan role = dokter
if($_SESSION['role'] !== 'dokter'){
    header("Location: ../login.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];
$patient_id = $_GET['id'] ?? 0;

// Cek pasien milik dokter
$sql = "SELECT p.*, s.nama_layanan
        FROM patients p
        JOIN services s ON p.service_id = s.id
        WHERE p.id = ? AND p.dokter_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $patient_id, $doctor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$patient = mysqli_fetch_assoc($result);

if(!$patient){
    echo "<p style='color:red;'>Pasien tidak ditemukan atau bukan pasien Anda!</p>";
    exit;
}

// Proses tambah rekam medis
if(isset($_POST['add_medical'])){
    $keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);
    $diagnosa = mysqli_real_escape_string($conn, $_POST['diagnosa']);
    $tindakan = mysqli_real_escape_string($conn, $_POST['tindakan']);

    $insert_sql = "INSERT INTO medical_records (patient_id, keluhan, diagnosa, tindakan) 
                   VALUES (?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($stmt_insert, "isss", $patient_id, $keluhan, $diagnosa, $tindakan);
    mysqli_stmt_execute($stmt_insert);

    header("Location: patient_detail.php?id=$patient_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Rekam Medis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f5f5f5;
        }
        h3 {
            color: #122056;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #122056;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #5B65DC;
        }
    </style>
</head>
<body>

<h3>Tambah Rekam Medis untuk Pasien: <?= htmlspecialchars($patient['nama']) ?></h3>

<form method="post">
    <label>Keluhan:</label><br>
    <textarea name="keluhan" rows="3" required></textarea><br>

    <label>Diagnosa:</label><br>
    <textarea name="diagnosa" rows="3" required></textarea><br>

    <label>Tindakan:</label><br>
    <textarea name="tindakan" rows="3" required></textarea><br>

    <input type="submit" name="add_medical" value="Simpan Rekam Medis">
</form>

</body>
</html>