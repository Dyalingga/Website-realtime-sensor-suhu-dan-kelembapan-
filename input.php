<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Input Data</title>
</head>

<body>

	<?php
	include('koneksi.php');
	$startPcs = hrtime(true);

	// PHP code:
	$method = "AES-128-CBC";
	$key = "aaaaaaaaaaaaaaaa";
	$iv = "aaaaaaaaaaaaaaaa";

	// encode the message with base64 encoder (optional, make the same as we did with Arduino).
	$enc = $_GET['encrypted'];

	$result = base64_decode(str_replace(" ", "+", $enc));
	$result = openssl_decrypt($result, $method, $key, OPENSSL_NO_PADDING, $iv);
	$decoded_msg = base64_decode($result);

	$parse = explode('&', $decoded_msg);

	$tanggal = time();
	$suhu = explode('=', $parse[0])[1];
	$kelembaban = explode('=', $parse[1])[1];
	$otpesp = explode('=', $parse[2])[1];

	//if (isset($_GET['otpesp'])) {
	if ($otpesp != '') {
		$query = mysqli_query($koneksi, "SELECT * FROM user WHERE otpesp = '" . $otpesp . "'");
		$total = mysqli_num_rows($query);
		$data = mysqli_fetch_array($query);

		if ($total == 1) {
			// $_SESSION['email'] = $data['email'];
			if (time() > $data['expired_otp']) {
				echo 'Expired OTP';
				// echo 'bener udah lewat';
			} else {
				// echo 'bener tapi masih dalam waktu';
				$enc_clean = str_replace(" ", "+", $enc);
				$byte = strlen($enc_clean) . " byte";

				$kirim = mysqli_query($koneksi, "INSERT INTO tbsensor (tanggal, suhu, kelembaban, encrypted_data, besar_data) VALUES (" . $tanggal . ", " . $suhu . ", " . $kelembaban . ", '" . $enc_clean . "', '" . $byte . "')");

				$endPcs = hrtime(true);
				$eta = $endPcs - $startPcs;

				$tm = $eta / 1e+9;
				$secs = number_format((float)$tm, 2, '.', '');
				$mils = ($secs * 1000) . 'ms';

				if ($kirim) {
					$last_id = mysqli_insert_id($koneksi);
					mysqli_query($koneksi, "UPDATE tbsensor SET time_processing = '" . $mils . "' WHERE id = " . $last_id);
					echo "Data berhasil diinput";
				} else {
					echo "Gagal diinput";
				}
			}
		} else {
			echo 'OTP Salah, Silahkan Coba Lagi!';
			// header("location:login.php");
		}
	} else {
		echo 'Unauthorized!';
	}
	// } else {
	// 	echo 'Unauthorized!';
	// }

	?>

</body>

</html>