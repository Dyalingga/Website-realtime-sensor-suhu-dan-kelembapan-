<?php
include 'koneksi.php';
session_start();
mysqli_query($koneksi, "UPDATE user SET otpesp = '' WHERE email = '" . $_SESSION['email'] . "'");
session_destroy();
session_unset();
header("location:login.php");
