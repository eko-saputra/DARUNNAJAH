<?php
require_once("functions/function.php");

include("includes/connection.php");

//Mencari data jadwal pertandingan
$sqljadwal = "SELECT * FROM jadwal_tanding ORDER BY id_partai DESC";
$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);

?>
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white download"></i><span class="break"></span>Upload Jadwal Tanding</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
			<?php
			if (isset($_POST['submit'])) { //Script akan berjalan jika di tekan tombol submit..

				//Script Upload File..
				if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
					echo "<h1>" . "File " . $_FILES['filename']['name'] . " Berhasil di Upload" . "</h1>";
					echo "<h2>Menampilkan Hasil Upload:</h2>";
					readfile($_FILES['filename']['tmp_name']);
				}

				//Import uploaded file to Database, Letakan dibawah sini..
				$handle = fopen($_FILES['filename']['tmp_name'], "r"); //Membuka file dan membacanya
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$import = "INSERT into jadwal_tanding (id_partai, tgl, kelas, gelanggang, partai, nm_merah, kontingen_merah,
																				nm_biru, kontingen_biru,babak) 
													VALUES (NULL,'$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]',
																'$data[6]','$data[7]','$data[8]')"; //data array sesuaikan dengan jumlah kolom pada CSV anda mulai dari “0” bukan “1”
					mysqli_query($koneksi, $import) or die(mysqli_error($koneksi)); //Melakukan Import
				}

				fclose($handle); //Menutup CSV file
				echo "<br><strong>Import data selesai.</strong>";
			} else { //Jika belum menekan tombol submit, form dibawah akan muncul.. 
			?>
				<form enctype='multipart/form-data' action='' method='post'>
					<p>
						Format kolom data pada csv harus sesuai dengan contoh.
						Download sample csv <A HREF="sample_jadwal.csv">di sini</A>.
						<br><strong>Format tanggal wajib (YYYY-MM-DD)</strong>.
					</p>
					<table>
						<tr>
							<td>
								<input type='file' name='filename' size='100'>
							</td>
							<td><input type='submit' name='submit' value='Upload'></td>
						</tr>
					</table>
				</form>
			<?php } //mysql_close(); //Menutup koneksi SQL
			?>
		</div>
	</div><!--/span-->
</div><!--/row-->

<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white download"></i><span class="break"></span>Input Jadwal Tanding</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
			<form class="form-horizontal" method="post" action="do_post_jadwal_tanding.php">
				<table>
					<tr>
						<td>Tanggal</td>
						<td>
							<input type="text" name="tanggal_display" value="<?php echo date('Y-m-d'); ?>" readonly>
						</td>
						<td>Kelas/Kelompok</td>
						<td><input type="text" name="kelas" id="kelas" maxlength="35" placeholder="Contoh: Dewasa Putra Kelas A"></td>
					</tr>
					<tr>
						<td>Gelanggang</td>
						<td><input type="text" name="gelanggang" id="gelanggang" maxlength="35" placeholder="A / B / C"></td>
						<td>No Partai</td>
						<td><input type="text" name="nopartai" id="nopartai" maxlength="35" placeholder="1 / 2 / 3 dst..."></td>
					</tr>
					<tr>
						<td>Nama Pesilat Merah</td>
						<td><input type="text" name="nm_merah" id="nm_merah" maxlength="55" placeholder="Nama pesilat sudut merah"></td>
						<td>Kontingen Merah</td>
						<td><input type="text" name="kont_merah" id="kont_merah" maxlength="55" placeholder="Kontingen pesilat sudut merah"></td>
					</tr>
					<tr>
						<td>Nama Pesilat Biru</td>
						<td><input type="text" name="nm_biru" id="nm_biru" maxlength="55" placeholder="Nama pesilat sudut biru"></td>
						<td>Kontingen Biru</td>
						<td><input type="text" name="kont_biru" id="kont_biru" maxlength="55" placeholder="Kontingen pesilat sudut biru"></td>
					</tr>
					<tr>
						<td>BABAK</td>
						<td><input type="text" name="babak" id="babak" maxlength="55" placeholder="PENYISIHAN / SEMIFINAL / FINAL"></td>
						<td colspan="2"><input type="submit" class="btn btn-info" value="SUBMIT"></td>
					</tr>
				</table>
			</form>
		</div>
	</div><!--/span-->
</div><!--/row-->

<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white download"></i><span class="break"></span>Data Jadwal Tanding</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable datatable">
				<thead>
					<tr>
						<th>NO</th>
						<th>GEL.</th>
						<th>PARTAI</th>
						<th>BABAK</th>
						<th>KELOMPOK</th>
						<th>SUDUT MERAH</th>
						<th>SUDUT BIRU</th>
						<th>AKTIF</th>
						<th>ACTIONS</th>
					</tr>
				</thead>
				<tbody>
					<?php $no = 0;
					while ($jadwal = mysqli_fetch_array($jadwal_tanding)) {
						$no++; ?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo $jadwal['gelanggang']; ?></td>
							<td><?php echo $jadwal['partai']; ?></td>
							<td><?php echo $jadwal['babak']; ?></td>
							<td><?php echo $jadwal['kelas']; ?></td>
							<td><?php echo $jadwal['nm_merah'] . " - " . $jadwal['kontingen_merah']; ?></td>
							<td><?php echo $jadwal['nm_biru'] . " - " . $jadwal['kontingen_biru']; ?></td>
							<td>
								<?php
								if ($jadwal['aktif'] == '0') {
									echo "NO";
								} else {
									echo "YES";
								}
								?>
							</td>
							<td class="center">
								<a class="btn btn-info" href="edit_partai.php?id_partai=<?php echo $jadwal['id_partai']; ?>">
									<i class="halflings-icon white pencil"></i>
								</a>
								<a class="btn btn-danger" onclick="return confirmDel()" href="del_partai.php?id_partai=<?php echo $jadwal['id_partai']; ?>">
									<i class="halflings-icon white trash"></i>
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div><!--/span-->
</div><!--/row-->

<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white remove"></i><span class="break"></span>CLEAR ALL - JADWAL & NILAI KELAS TANDING</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>

		<div class="box-content">
			<form class="form-horizontal" method="post" action="do_clear_jadwal_tanding.php">
				<p>Dengan menekan tombol "HAPUS SEMUA" di bawah ini, maka seluruh data <b>Jadwal Partai beserta Nilai Penjuriannya</b> pada <b>Kelas Tanding</b> akan hilang dari database.</p>
				<input type="submit" onclick="return confirmDel()" class="btn btn-danger" value="HAPUS SEMUA">
			</form>
		</div>
	</div><!--/span-->
</div><!--/row-->