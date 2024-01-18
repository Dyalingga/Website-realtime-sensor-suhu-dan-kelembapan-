<?php

use PHPMailer\PHPMailer\PHPMailer;

session_start();
include 'koneksi.php';
require_once('PHPMailer/src/PHPMailer.php');
require_once('PHPMailer/src/Exception.php');
require_once('PHPMailer/src/SMTP.php');
$email = $_POST['email'];
$password = md5($_POST['password']);

$query = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '" . $email . "' AND password = '" . $password . "'");
$total = mysqli_num_rows($query);
$data = mysqli_fetch_array($query);

if ($total == 1 || $total == '1') {

    $myOTP = rand(10000, 99999);
    $myOTPESP = rand(10000, 99999);
    $date = date('Y-m-d H:i:s');
    $expiredOTP = strtotime($date) + (9999 * 60);
    $query = mysqli_query($koneksi, "UPDATE user SET otp = '" . $myOTP . "', otpesp = '" . $myOTPESP . "', expired_otp = '" . $expiredOTP . "' WHERE id = " . $data['id']);

    $_SESSION['email'] = $data['email'];

    $mail = new PHPMailer();

    try {
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = 'ssl';                                   //Enable SMTP authentication
        $mail->Username   = 'smssk201222@gmail.com';                     //SMTP username
        $mail->Password   = 'wevrxkthzbmowoyh';          //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->addAddress($data['email']);
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'OTP Login';
        $mail->Body    = 'Berikut OTP untuk login Anda ke Sistem Monitoring Sensor Suhu dan Kelembapan: <b>' . $myOTP . '</b> <br> Berikut OTP untuk Microcontroller Anda: <b>' . $myOTPESP . '</b>';
        if ($mail->send()) {
            unset($_SESSION['message']);
            header("location:otp.php");
        } else {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        header("location:login.php");
    }

    // unset($_SESSION['message']);
    // header("location:otp.php"); // kembali ke tampil data
} else {
    $_SESSION['message'] = "Email atau password tidak sesuai!";
    header("location:login.php"); // kembali ke tampil data
}

//$sql = mysqli_query($koneksi, "INSERT INTO user (email, nama, password) VALUES ('" . $email . "', '" . $nama . "', '" . $password . "')");
