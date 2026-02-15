<?php
	session_start();
	include 'backend/includes/connection.php';

	
	$banktujuan = mysqli_real_escape_string($koneksi, $_POST["banktujuan"]);
	$bankpengirim = mysqli_real_escape_string($koneksi, $_POST["bankpengirim"]);
	$norekening = mysqli_real_escape_string($koneksi, $_POST["norekening"]);
	$nama = mysqli_real_escape_string($koneksi, $_POST["nama"]);
	$kontak = mysqli_real_escape_string($koneksi, $_POST["kontak"]);
	$tgltransfer = mysqli_real_escape_string($koneksi, $_POST["tgltransfer"]);
	$jumlah = mysqli_real_escape_string($koneksi, $_POST["jumlah"]);
	$catatan = mysqli_real_escape_string($koneksi, $_POST["catatan"]);
	$vercode = $_POST['vercode'];
	$datetime = gmdate("Y-m-d H:i:s", time()+60*60*7);

	if($banktujuan == "" OR $bankpengirim == "" OR $norekening == "" OR $nama == "" OR $kontak == ""
		OR $tgltransfer == "" OR $jumlah == "" OR $vercode == "") {
		?>
		<script type="text/javascript">
			alert('KONFIRMASI GAGAL. Data Wajib Dilengkapi semua!');
			document.location='konfirmasi.php';
		</script>
		<?php
		exit;
	}

	if($vercode != $_SESSION['capcay']) {
		?>
		<script type="text/javascript">
			alert('KONFIRMASI GAGAL. Jawaban Soal Penjumlahan Tidak Benar!');
			document.location='konfirmasi.php';
		</script>
		<?php
		exit;
	}

	
	//kode upload
		$lokasi_file = $_FILES['buktipembayaran']['tmp_name'];
		$nama_file = $_FILES['buktipembayaran']['name'];
		$tipe_file = $_FILES['buktipembayaran']['type'];
		$ukuran_file = $_FILES['buktipembayaran']['size'];

		//cek apakah file kosong ?
		if(strlen($nama_file)<1){
		?>
			<script type="text/javascript">
				alert('Bukti Pembayaran Tidak Ditemukan!');
				document.location='konfirmasi.php';
			</script>
		<?php
			exit();
		}

		//kode untuk mengganti spasi menjadi garis bawah pada nama file
		$nama_baru = preg_replace("/\s+/", "_", $nama_file);
		$nama_baru_md5 = md5($norekening.$nama_baru).$nama_baru;

		//penempatan PATH DIREKTORI penyimpanan file bukti pembayaran
		$direktori = "buktibayar/$nama_baru_md5";

		//Definisi Maksimal ukuran berkas
		$MAX_FILE_SIZE = 1000000; //1mb


		//cek apakah format file adalah gambar
		$formatberkas = array("image/gif", "image/jpeg", "image/jpg", "image/JPG", "image/pjpeg", "image/x-png", "image/png");
		
		if(!in_array($tipe_file, $formatberkas)) {
		?>
			<script type="text/javascript">
				alert('Format Berkas Tidak Diizinkan!');
				document.location='konfirmasi.php';
			</script>
		<?php
		  exit();
		}

		//cek apakah ukuran file diatas 1mb
		if($ukuran_file > $MAX_FILE_SIZE) {
		 ?>
			<script type="text/javascript">
				alert('Ukuran Berkas Tidak Diizinkan!');
				document.location='konfirmasi.php';
			</script>
		<?php
		  exit();
		}

		//code untuk mengkopi berkas ke folder berkas
		move_uploaded_file($lokasi_file, $direktori);	
	
	
	$insertkonfirmasi = mysqli_query($koneksi, "INSERT INTO konfirmasi(bank_tujuan, bank_pengirim, norek_pengirim, 
									nm_pengirim, kontak, 
									tgl_transfer, jumlah,
									bukti, catatan, datetime)
								VALUES('$banktujuan', '$bankpengirim', '$norekening', 
										'$nama', '$kontak', 
										'$tgltransfer', '$jumlah', 
										'$nama_baru_md5', '$catatan', '$datetime')");

	?>
			<script type="text/javascript">
				alert('Konfirmasi terkirim! Kami akan menghubungi Anda jika data sudah diverifikasi.');
				document.location='konfirmasi.php';
			</script>
	<?php
?>