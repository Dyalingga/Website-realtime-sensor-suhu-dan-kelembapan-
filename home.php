<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Skripsi Dipa</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
	<?php
	session_start();
	$nama = $_SESSION['nama'];
	$email = $_SESSION['email'];
	?>


	<h1 class="h1" align="center">Monitoring Sensor Suhu dan Kelembaban</h1>

	<?php
	include 'koneksi.php';

	$query = mysqli_query($koneksi, "SELECT * FROM tbsensor ORDER BY id DESC LIMIT 1");
	while ($data = mysqli_fetch_array($query)) {
		// code...
	?>

		Waktu update terakhir : (<?php echo date('H:i:s', $data['tanggal']) ?>) Tanggal : (<?php echo date('d-m-Y', $data['tanggal']) ?>)
		<div class="profile">
			<br>
			<h3><?php echo $nama; ?></h3>
			<p><a href=" logoutservice.php">Logout</a></p>
		</div>
		<div class="container">
			<div class="kotak">
				<h2 class="h2">Suhu</h2>
				<div class="nilai">
					<?php echo $data['suhu'] ?><font size="7">°C</font>
				</div>
			</div>
			<div class="kotak">
				<h2 class="h2">Kelembaban</h2>
				<div class="nilai">
					<?php echo $data['kelembaban'] ?><font size="7">%</font>
				</div>
			</div>
		</div>

	<?php } ?>

	<span id='reset'>Reset Data..!</span> <br><br>


</body>
<script>
	$('#reset').on('click', function() {
		if (confirm('Apakah Anda yakin untuk reset data?')) {
			window.location.href = 'hapus.php';
		} else {
			console.log('ga ok');
		}
	})
</script>

</html>