<?php
session_start();
include 'koneksi.php'; // masukan konekasi DB
// ambil variable ID dari URL
//Proses query hapus data
$del = mysqli_query($koneksi, "TRUNCATE TABLE tbsensor");
$sql = mysqli_query($koneksi, "INSERT INTO tbsensor (id, suhu, kelembaban, tanggal)
VALUES (1, 0, 0, 0)");
header("location:index.php"); // kembali ke tampil data
