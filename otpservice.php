<?php
session_start();
include 'koneksi.php';

$email = $_POST['email'];
$otp = $_POST['otp'];

$query = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '" . $email . "' AND otp = '" . $otp . "'");
$total = mysqli_num_rows($query);
$data = mysqli_fetch_array($query);

// echo '<pre>';
// print_r($data);
if ($total == 1 || $total == '1') {
    // $_SESSION['email'] = $data['email'];
    if (time() > $data['expired_otp']) {
        $_SESSION['message'] = "OTP expired";
        header("location:login.php");
        // echo 'bener udah lewat';
    } else {
        // echo 'bener tapi masih dalam waktu';
        $_SESSION['nama'] = $data['nama'];
        header("location:index.php"); // kembali ke tampil data
    }
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '" . $email . "'");
    $data = mysqli_fetch_array($query);

    if (time() > $data['expired_otp']) {
        $_SESSION['message'] = "OTP expired";
        header("location:login.php");
        // echo 'salah tapi waktu udh lewat';
    } else {
        $_SESSION['message'] = "OTP yang dimasukkan salah";
        header("location:otp.php"); // kembali ke tampil data
        // echo 'salah tapi masih dalam waktu';
    }
    // header("location:login.php");
}

//$sql = mysqli_query($koneksi, "INSERT INTO user (email, nama, password) VALUES ('" . $email . "', '" . $nama . "', '" . $password . "')");
