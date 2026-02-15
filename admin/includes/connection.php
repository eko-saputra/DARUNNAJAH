<?php
$koneksi = mysqli_connect("localhost", "skordigital", "skordigital", "skordigital");
// Check connection
if (mysqli_connect_errno()) {
	echo "Koneksi ke database gagal : " . mysqli_connect_error();
}
