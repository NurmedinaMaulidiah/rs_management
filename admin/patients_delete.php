<?php
session_start();
require '../config/koneksi.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM patients WHERE id=$id");
header("Location: patients.php");
?>