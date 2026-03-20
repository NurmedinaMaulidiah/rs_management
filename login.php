<?php
session_start();
require 'config/koneksi.php';

if(isset($_POST['login'])){
    $user = $_POST['user']; // username input
    $password = $_POST['password'];

    // Query user dari database baru
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    $data = mysqli_fetch_assoc($query);

    if($data){
        // Password sementara masih plain text '123', nanti bisa hash
        if($password == $data['password']){
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['nama'] = $data['nama'];
            $_SESSION['role'] = $data['role'];

            // Redirect sesuai role
            if($data['role'] == "admin") header("Location: admin/dashboard.php");
            elseif($data['role'] == "staff") header("Location: staff/dashboard.php");
            elseif($data['role'] == "dokter") header("Location: doctor/patients.php");
            exit;
        } else {
            $error = "Username atau Password salah!";
        }
    } else {
        $error = "Username tidak terdaftar!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <title>Login RS Management</title>
</head>
<body>

<div class="container" id="container">

    <!-- kiri : form login -->
    <div class="box login">
        <form method="post">
            <div class="logo">
                <img src="img/HealynLogo.png" alt="Logo RS" width="50%">
            </div>

            <input type="text" name="user" placeholder="Username" class="input" required autocomplete="off">
            <input type="password" name="password" placeholder="Password" class="input" required autocomplete="off">

            <input type="submit" name="login" value="Login" class="submit"><br><br>

            <?php if(isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        </form>
    </div>

    <!-- kanan : gradient + text -->
    <div class="box side-right">
        <div class="text-right">
            <h1>RS Management</h1>
            <p>(Silakan login dengan menggunakan username dan email yang telah didaftarkan)</p>

            <ul class="fitur">
                <li>✔ Manajemen Data Pasien</li>
                <li>✔ Pengelolaan Data Dokter</li>
                <li>✔ Rekam Medis Digital</li>
                <li>✔ Akses Admin, Staff, Dokter</li>
            </ul>
        </div>

    </div>

</div>

<!-- <script src="js/main.js"></script> -->
</body>
</html>