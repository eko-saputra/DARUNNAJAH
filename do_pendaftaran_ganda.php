<?php
	include 'backend/includes/connection.php';


	$kategori_tanding = mysqli_real_escape_string($koneksi, $_POST["kategori_tanding"]);
	$golongan = mysqli_real_escape_string($koneksi, $_POST["golongan"]);

	$nama = mysqli_real_escape_string($koneksi, $_POST["nama"]);
	$nama2 = mysqli_real_escape_string($koneksi, $_POST["nama2"]);
	$kode_gr = md5($nama.$nama2);

	$jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST["jenis_kelamin"]);
	
	$tpt_lahir = mysqli_real_escape_string($koneksi, $_POST["tpt_lahir"]);
	$tpt_lahir2 = mysqli_real_escape_string($koneksi, $_POST["tpt_lahir2"]);

	$tgl_lahir = mysqli_real_escape_string($koneksi, $_POST["tgl_lahir"]);
	$tgl_lahir2 = mysqli_real_escape_string($koneksi, $_POST["tgl_lahir2"]);

	$tb = mysqli_real_escape_string($koneksi, $_POST["tb"]);
	$tb2 = mysqli_real_escape_string($koneksi, $_POST["tb2"]);
	$bb = mysqli_real_escape_string($koneksi, $_POST["bb"]);
	$bb2 = mysqli_real_escape_string($koneksi, $_POST["bb2"]);
	
	
	$asal_sekolah = mysqli_real_escape_string($koneksi, $_POST["asal_sekolah"]);
	$asal_sekolah2 = mysqli_real_escape_string($koneksi, $_POST["asal_sekolah2"]);
	
	$kelas = mysqli_real_escape_string($koneksi, $_POST["kelas"]);
	$kelas2 = mysqli_real_escape_string($koneksi, $_POST["kelas2"]);

	$kontingen = mysqli_real_escape_string($koneksi, strtoupper($_POST["kontingen"]));

	$nama_baru_md5_ktp = '';
	$nama_baru_md5_akta = '';
	$nama_baru_md5_ijazah = '';

	$nama_baru_md5_ktp2 = '';
	$nama_baru_md5_akta2 = '';
	$nama_baru_md5_ijazah2 = '';

	
	if($nama == '' OR $nama2 == '' OR $jenis_kelamin == ""
		OR $tpt_lahir == "" OR $tpt_lahir2 == "" OR $tgl_lahir == "" OR $tgl_lahir2 == "" 
		OR $tb == "" OR $tb2 == "" OR $bb == "" OR $bb2 == ""
		OR $kategori_tanding == '' OR $golongan == ''
		OR $kontingen == "") {
		?>
		<script type="text/javascript">
			alert('Gagal! Data masih ada yang kosong.');
			document.location='mulai_pendaftaran.php';
		</script>
		<?php
		exit;
	}

	// ************ HANDLE FOTO UPLOAD FOTO PESILAT 1 ******************
	//kode upload
		$lokasi_file_foto = $_FILES['fotopeserta']['tmp_name'];
		$nama_file_foto = $_FILES['fotopeserta']['name'];
		$tipe_file_foto = $_FILES['fotopeserta']['type'];
		$ukuran_file_foto = $_FILES['fotopeserta']['size'];

		//cek apakah file kosong ?
		if(strlen($nama_file_foto)<1){
		?>
			<script type="text/javascript">
				alert('File Foto Tidak Ditemukan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
			exit();
		}

		//kode untuk mengganti spasi menjadi garis bawah pada nama file
		$nama_baru_foto = preg_replace("/\s+/", "_", $nama_file_foto);
		$nama_baru_md5_foto = md5($nama.$nama_baru_foto).$nama_baru_foto;

		//penempatan PATH DIREKTORI penyimpanan file bukti pembayaran
		$direktori_foto = "peserta_foto/$nama_baru_md5_foto";

		//Definisi Maksimal ukuran berkas
		$MAX_FILE_SIZE = 500000; //500kb

		//cek apakah format file adalah gambar
		$formatberkas_foto = array("image/gif", "image/jpeg", "image/jpg", "image/JPG", "image/pjpeg", "image/x-png", "image/png");
		
		if(!in_array($tipe_file_foto, $formatberkas_foto)) {
		?>
			<script type="text/javascript">
				alert('Format Berkas Tidak Diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//cek apakah ukuran file diatas 50kb
		if($ukuran_file_foto > $MAX_FILE_SIZE) {
		 ?>
			<script type="text/javascript">
				alert('Ukuran Berkas Tidak Diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

	// ************ END HERE ****************************


	// ************ HANDLE FOTO UPLOAD FOTO PESILAT 2 ******************
	//kode upload
		$lokasi_file_foto2 = $_FILES['fotopeserta2']['tmp_name'];
		$nama_file_foto2 = $_FILES['fotopeserta2']['name'];
		$tipe_file_foto2 = $_FILES['fotopeserta2']['type'];
		$ukuran_file_foto2 = $_FILES['fotopeserta2']['size'];

		//cek apakah file kosong ?
		if(strlen($nama_file_foto2)<1){
		?>
			<script type="text/javascript">
				alert('File Foto Tidak Ditemukan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
			exit();
		}

		//kode untuk mengganti spasi menjadi garis bawah pada nama file
		$nama_baru_foto2 = preg_replace("/\s+/", "_", $nama_file_foto2);
		$nama_baru_md5_foto2 = md5($nama2.$nama_baru_foto2).$nama_baru_foto2;

		//penempatan PATH DIREKTORI penyimpanan file bukti pembayaran
		$direktori_foto2 = "peserta_foto/$nama_baru_md5_foto2";

		//Definisi Maksimal ukuran berkas
		$MAX_FILE_SIZE2 = 500000; //500kb

		//cek apakah format file adalah gambar
		$formatberkas_foto2 = array("image/gif", "image/jpeg", "image/jpg", "image/JPG", "image/pjpeg", "image/x-png", "image/png");
		
		if(!in_array($tipe_file_foto2, $formatberkas_foto2)) {
		?>
			<script type="text/javascript">
				alert('Format Berkas Tidak Diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//cek apakah ukuran file diatas 50kb
		if($ukuran_file_foto2 > $MAX_FILE_SIZE2) {
		 ?>
			<script type="text/javascript">
				alert('Ukuran Berkas Tidak Diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//code untuk mengkopi Foto 1 ke SERVER
		move_uploaded_file($lokasi_file_foto, $direktori_foto);	
		//code untuk mengkopi Foto 2 ke SERVER
		move_uploaded_file($lokasi_file_foto2, $direktori_foto2);	

	// ************ END HERE ****************************


	//JIKA GOLONGAN DEWASA PAKE KTP SAJA
	if($golongan=='Dewasa'){
	// ************ HANDLE UPLOAD KTP ******************
		$lokasi_file_ktp = $_FILES['ktppeserta']['tmp_name'];
		$nama_file_ktp = $_FILES['ktppeserta']['name'];
		$tipe_file_ktp = $_FILES['ktppeserta']['type'];
		$ukuran_file_ktp = $_FILES['ktppeserta']['size'];

		//cek apakah file kosong ?
		if(strlen($nama_file_ktp)<1){
		?>
			<script type="text/javascript">
				alert('File KTP Tidak Ditemukan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
			exit();
		}

		//kode untuk mengganti spasi menjadi garis bawah pada nama file
		$nama_baru_ktp = preg_replace("/\s+/", "_", $nama_file_ktp);
		$nama_baru_md5_ktp = md5($nama.$nama_baru_ktp).$nama_baru_ktp;

		//penempatan PATH DIREKTORI penyimpanan file bukti pembayaran
		$direktori_ktp = "peserta_ktp/$nama_baru_md5_ktp";

		//Definisi Maksimal ukuran berkas
		$MAX_FILE_SIZE_ktp = 500000; //500kb

		//cek apakah format file adalah gambar
		$formatberkas_ktp = array("image/gif", "image/jpeg", "image/jpg", "image/JPG", "image/pjpeg", "image/x-png", "image/png");
		
		if(!in_array($tipe_file_ktp, $formatberkas_ktp)) {
		?>
			<script type="text/javascript">
				alert('Berkas KTP Tidak Diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//cek apakah ukuran file diatas 1mb
		if($ukuran_file_ktp > $MAX_FILE_SIZE_ktp) {
		 ?>
			<script type="text/javascript">
				alert('Ukuran berkas KTP MELEBIHI batas yang diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//code untuk mengkopi KTP ke SERVER
		move_uploaded_file($lokasi_file_ktp, $direktori_ktp);

		// ************ HANDLE UPLOAD KTP 2 ******************
		$lokasi_file_ktp2 = $_FILES['ktppeserta2']['tmp_name'];
		$nama_file_ktp2 = $_FILES['ktppeserta2']['name'];
		$tipe_file_ktp2 = $_FILES['ktppeserta2']['type'];
		$ukuran_file_ktp2 = $_FILES['ktppeserta2']['size'];

		//cek apakah file kosong ?
		if(strlen($nama_file_ktp2)<1){
		?>
			<script type="text/javascript">
				alert('File KTP Peserta 2 Tidak Ditemukan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
			exit();
		}

		//kode untuk mengganti spasi menjadi garis bawah pada nama file
		$nama_baru_ktp2 = preg_replace("/\s+/", "_", $nama_file_ktp2);
		$nama_baru_md5_ktp2 = md5($nama2.$nama_baru_ktp2).$nama_baru_ktp2;

		//penempatan PATH DIREKTORI penyimpanan file bukti pembayaran
		$direktori_ktp2 = "peserta_ktp/$nama_baru_md5_ktp2";

		//Definisi Maksimal ukuran berkas
		$MAX_FILE_SIZE_ktp2 = 500000; //500kb

		//cek apakah format file adalah gambar
		$formatberkas_ktp2 = array("image/gif", "image/jpeg", "image/jpg", "image/JPG", "image/pjpeg", "image/x-png", "image/png");
		
		if(!in_array($tipe_file_ktp2, $formatberkas_ktp2)) {
		?>
			<script type="text/javascript">
				alert('Berkas KTP Peserta 2 Tidak Diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//cek apakah ukuran file diatas 1mb
		if($ukuran_file_ktp2 > $MAX_FILE_SIZE_ktp2) {
		 ?>
			<script type="text/javascript">
				alert('Ukuran berkas KTP Peserta 2 MELEBIHI batas yang diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//code untuk mengkopi KTP ke SERVER
		move_uploaded_file($lokasi_file_ktp2, $direktori_ktp2);
		
	// ************ END HERE ****************************
	} else {
	//ELSE (UNTUK GOLONGAN SEKOLAH)
	// ************ HANDLE UPLOAD AKTA ******************
		$lokasi_file_akta = $_FILES['akta']['tmp_name'];
		$nama_file_akta = $_FILES['akta']['name'];
		$tipe_file_akta = $_FILES['akta']['type'];
		$ukuran_file_akta = $_FILES['akta']['size'];

		//cek apakah file kosong ?
		if(strlen($nama_file_akta)<1){
		?>
			<script type="text/javascript">
				alert('File akta Tidak Ditemukan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
			exit();
		}

		//kode untuk mengganti spasi menjadi garis bawah pada nama file
		$nama_baru_akta = preg_replace("/\s+/", "_", $nama_file_akta);
		$nama_baru_md5_akta = md5($nama.$nama_baru_akta).$nama_baru_akta;

		//penempatan PATH DIREKTORI penyimpanan file bukti pembayaran
		$direktori_akta= "peserta_akta/$nama_baru_md5_akta";

		//Definisi Maksimal ukuran berkas
		$MAX_FILE_SIZE_akta = 3000000; //3MB

		//cek apakah format file adalah gambar
		$formatberkas_akta = array("image/gif", "image/jpeg", "image/jpg", "image/JPG", "image/pjpeg", "image/x-png", "image/png");
		
		if(!in_array($tipe_file_akta, $formatberkas_akta)) {
		?>
			<script type="text/javascript">
				alert('Berkas akta Tidak Diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//cek apakah ukuran file diatas 1mb
		if($ukuran_file_akta > $MAX_FILE_SIZE_akta) {
		 ?>
			<script type="text/javascript">
				alert('Ukuran berkas akta MELEBIHI batas yang diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//code untuk mengkopi AKTA ke SERVER
		move_uploaded_file($lokasi_file_akta, $direktori_akta);
		
	// ************ END HERE ****************************

	// ************ HANDLE UPLOAD AKTA 2 ******************
		$lokasi_file_akta2 = $_FILES['akta2']['tmp_name'];
		$nama_file_akta2 = $_FILES['akta2']['name'];
		$tipe_file_akta2 = $_FILES['akta2']['type'];
		$ukuran_file_akta2 = $_FILES['akta2']['size'];

		//cek apakah file kosong ?
		if(strlen($nama_file_akta2)<1){
		?>
			<script type="text/javascript">
				alert('File akta peserta 2 Tidak Ditemukan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
			exit();
		}

		//kode untuk mengganti spasi menjadi garis bawah pada nama file
		$nama_baru_akta2 = preg_replace("/\s+/", "_", $nama_file_akta2);
		$nama_baru_md5_akta2 = md5($nama2.$nama_baru_akta2).$nama_baru_akta2;

		//penempatan PATH DIREKTORI penyimpanan file bukti pembayaran
		$direktori_akta2= "peserta_akta/$nama_baru_md5_akta2";

		//Definisi Maksimal ukuran berkas
		$MAX_FILE_SIZE_akta2 = 3000000; //3MB

		//cek apakah format file adalah gambar
		$formatberkas_akta2 = array("image/gif", "image/jpeg", "image/jpg", "image/JPG", "image/pjpeg", "image/x-png", "image/png");
		
		if(!in_array($tipe_file_akta2, $formatberkas_akta2)) {
		?>
			<script type="text/javascript">
				alert('Berkas akta peserta 2 Tidak Diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//cek apakah ukuran file diatas 1mb
		if($ukuran_file_akta2 > $MAX_FILE_SIZE_akta2) {
		 ?>
			<script type="text/javascript">
				alert('Ukuran berkas akta peserta 2 MELEBIHI batas yang diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//code untuk mengkopi AKTA 2 ke SERVER
		move_uploaded_file($lokasi_file_akta2, $direktori_akta2);
		
	// ************ END HERE ****************************

	// ************ HANDLE UPLOAD IJAZAH ******************
		$lokasi_file_ijazah = $_FILES['ijazah']['tmp_name'];
		$nama_file_ijazah = $_FILES['ijazah']['name'];
		$tipe_file_ijazah = $_FILES['ijazah']['type'];
		$ukuran_file_ijazah = $_FILES['ijazah']['size'];

		//cek apakah file kosong ?
		if(strlen($nama_file_ijazah)<1){
		?>
			<script type="text/javascript">
				alert('File ijazah Tidak Ditemukan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
			exit();
		}

		//kode untuk mengganti spasi menjadi garis bawah pada nama file
		$nama_baru_ijazah = preg_replace("/\s+/", "_", $nama_file_ijazah);
		$nama_baru_md5_ijazah = md5($nama.$nama_baru_ijazah).$nama_baru_ijazah;

		//penempatan PATH DIREKTORI penyimpanan
		$direktori_ijazah = "peserta_ijazah/$nama_baru_md5_ijazah";

		//Definisi Maksimal ukuran berkas
		$MAX_FILE_SIZE_ijazah = 3000000; //3MB

		//cek apakah format file adalah gambar
		$formatberkas_ijazah = array("image/gif", "image/jpeg", "image/jpg", "image/JPG", "image/pjpeg", "image/x-png", "image/png");
		
		if(!in_array($tipe_file_ijazah, $formatberkas_ijazah)) {
		?>
			<script type="text/javascript">
				alert('Berkas ijazah Tidak Diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//cek apakah ukuran file diatas 1mb
		if($ukuran_file_ijazah > $MAX_FILE_SIZE_ijazah) {
		 ?>
			<script type="text/javascript">
				alert('Ukuran berkas ijazah MELEBIHI batas yang diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//code untuk mengkopi ijazah ke SERVER
		move_uploaded_file($lokasi_file_ijazah, $direktori_ijazah);
		
	// ************ END HERE ****************************

	// ************ HANDLE UPLOAD IJAZAH 2 ******************
		$lokasi_file_ijazah2 = $_FILES['ijazah2']['tmp_name'];
		$nama_file_ijazah2 = $_FILES['ijazah2']['name'];
		$tipe_file_ijazah2 = $_FILES['ijazah2']['type'];
		$ukuran_file_ijazah2 = $_FILES['ijazah2']['size'];

		//cek apakah file kosong ?
		if(strlen($nama_file_ijazah2)<1){
		?>
			<script type="text/javascript">
				alert('File ijazah peserta 2 Tidak Ditemukan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
			exit();
		}

		//kode untuk mengganti spasi menjadi garis bawah pada nama file
		$nama_baru_ijazah2 = preg_replace("/\s+/", "_", $nama_file_ijazah2);
		$nama_baru_md5_ijazah2 = md5($nama2.$nama_baru_ijazah2).$nama_baru_ijazah2;

		//penempatan PATH DIREKTORI penyimpanan
		$direktori_ijazah2 = "peserta_ijazah/$nama_baru_md5_ijazah2";

		//Definisi Maksimal ukuran berkas
		$MAX_FILE_SIZE_ijazah2 = 3000000; //3MB

		//cek apakah format file adalah gambar
		$formatberkas_ijazah2 = array("image/gif", "image/jpeg", "image/jpg", "image/JPG", "image/pjpeg", "image/x-png", "image/png");
		
		if(!in_array($tipe_file_ijazah2, $formatberkas_ijazah2)) {
		?>
			<script type="text/javascript">
				alert('Berkas ijazah peserta 2 Tidak Diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//cek apakah ukuran file diatas 1mb
		if($ukuran_file_ijazah2 > $MAX_FILE_SIZE_ijazah2) {
		 ?>
			<script type="text/javascript">
				alert('Ukuran berkas ijazah MELEBIHI batas yang diizinkan!');
				document.location='mulai_pendaftaran.php';
			</script>
		<?php
		  exit();
		}

		//code untuk mengkopi ijazah 2 ke SERVER
		move_uploaded_file($lokasi_file_ijazah2, $direktori_ijazah2);
		
	// ************ END HERE ****************************
	}
	
	
	$insertpeserta = mysqli_query($koneksi, "INSERT INTO peserta(nm_lengkap, jenis_kelamin, tpt_lahir, tgl_lahir,
														tb, bb, kelas, asal_sekolah, 
														kategori_tanding, golongan, kode_gr,
														kontingen, foto,
														ktp, akta_lahir, ijazah)
								VALUES('$nama', '$jenis_kelamin', '$tpt_lahir', '$tgl_lahir',
										'$tb', '$bb', '$kelas', '$asal_sekolah',
										'$kategori_tanding','$golongan', '$kode_gr',
										'$kontingen', '$nama_baru_md5_foto',
										'$nama_baru_md5_ktp', '$nama_baru_md5_akta', '$nama_baru_md5_ijazah')");

	$insertpeserta2 = mysqli_query($koneksi, "INSERT INTO peserta(nm_lengkap, jenis_kelamin, tpt_lahir, tgl_lahir,
														tb, bb, kelas, asal_sekolah, 
														kategori_tanding, golongan, kode_gr,
														kontingen, foto,
														ktp, akta_lahir, ijazah)
								VALUES('$nama2', '$jenis_kelamin', '$tpt_lahir2', '$tgl_lahir2',
										'$tb2', '$bb2', '$kelas2', '$asal_sekolah2',
										'$kategori_tanding','$golongan', '$kode_gr',
										'$kontingen', '$nama_baru_md5_foto2',
										'$nama_baru_md5_ktp2', '$nama_baru_md5_akta2', '$nama_baru_md5_ijazah2')");

	?>
			<script type="text/javascript">
				alert('Data berhasil diinput!');
				document.location='mulai_pendaftaran.php';
			</script>
	<?php
?>