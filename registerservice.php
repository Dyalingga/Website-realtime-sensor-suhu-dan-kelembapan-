<?php

include 'koneksi.php';
session_start();

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = md5($_POST['password']);

$query = mysqli_query($koneksi, "SELECT * from user Where email='$email'");
$total = mysqli_num_rows($query);

if ($total > 0) {
    $_SESSION['message'] = "Email sudah terdaftar";
    header("location:register.php");
} else {
    $sql = mysqli_query($koneksi, "INSERT INTO user (email, nama, password) VALUES ('" . $email . "', '" . $nama . "', '" . $password . "')");
    header("location:login.php"); // kembali ke tampil data
}
