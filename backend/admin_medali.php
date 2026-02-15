<?php
require_once("functions/function.php");
get_header();
get_sidebar();
get_bread();

include 'includes/connection.php';

//query data pemenang semifinal
$sqlwinner = "SELECT * FROM jadwal_tanding WHERE babak='SEMIFINAL'";
$datawinner = mysqli_query($koneksi, "$sqlwinner");

//query data pemenang final
$sqlwinnerfinal = "SELECT * FROM jadwal_tanding_final WHERE babak='FINAL'";
$datawinnerfinal = mysqli_query($koneksi, "$sqlwinnerfinal");

//query data perolehan medali
$sqlmedali = "SELECT * FROM medali ORDER BY kontingen ASC";
$datamedali = mysqli_query($koneksi, "$sqlmedali");

//query data koleksi medali
$sqlkoleksimedali = "SELECT DISTINCT `kontingen` FROM medali";
$datakoleksi = mysqli_query($koneksi, "$sqlkoleksimedali");
?>
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Monitoring Partai SEMIFINAL</h2>
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
						<th>No</th>
						<th>Partai</th>
						<th>Gel.</th>
						<th>Babak</th>
						<th>Kelompok</th>
						<th>Sudut Merah</th>
						<th>Sudut Biru</th>
						<th>Skor</th>
						<th>Pemenang</th>
					</tr>
				</thead>
				<tbody>
					<?php $no = 0;
					while ($winner = mysqli_fetch_array($datawinner)) {
						$no++; ?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo $winner['partai']; ?></td>
							<td><?php echo $winner['gelanggang']; ?></td>
							<td><?php echo $winner['babak']; ?></td>
							<td><?php echo $winner['kelas']; ?></td>
							<td>
								<?php
								echo $winner['nm_merah'] . "<br>";
								echo "<b>" . $winner['kontingen_merah'] . "</b><br>";
								?>
								<form name="PostMedali" id="PostMedali" method="POST" action="admin_do_medali.php">
									<input type="hidden" name="nama" id="nama" value="<?php echo $winner["nm_merah"]; ?>">
									<input type="hidden" name="kontingen" id="kontingen" value="<?php echo $winner["kontingen_merah"]; ?>">
									<input type="hidden" name="kelas" id="kelas" value="<?php echo $winner["kelas"]; ?>">
									<input type="hidden" name="medali" id="medali" value="Perunggu">
									<input type="hidden" name="idjadwal" id="idjadwal" value="<?php echo $winner['id_partai']; ?>">
									<input type="submit" class="btn" value="Perunggu">
								</form>
							</td>
							<td>
								<?php
								echo $winner['nm_biru'] . "<br>";
								echo "<b>" . $winner['kontingen_biru'] . "</b><br>";
								?>
								<form name="PostMedali" id="PostMedali" method="POST" action="admin_do_medali.php">
									<input type="hidden" name="nama" id="nama" value="<?php echo $winner["nm_biru"]; ?>">
									<input type="hidden" name="kontingen" id="kontingen" value="<?php echo $winner["kontingen_biru"]; ?>">
									<input type="hidden" name="kelas" id="kelas" value="<?php echo $winner["kelas"]; ?>">
									<input type="hidden" name="medali" id="medali" value="Perunggu">
									<input type="hidden" name="idjadwal" id="idjadwal" value="<?php echo $winner['id_partai']; ?>">
									<input type="submit" class="btn" value="Perunggu">
								</form>
							</td>
							<td><?php echo $winner['status']; ?></td>
							<td><?php echo $winner['pemenang']; ?></td>
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
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Monitoring Partai FINAL</h2>
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
						<th>No</th>
						<th>Partai</th>
						<th>Gel.</th>
						<th>Babak</th>
						<th>Kelompok</th>
						<th>Sudut Merah</th>
						<th>Sudut Biru</th>
						<th>Skor</th>
						<th>Pemenang</th>
					</tr>
				</thead>
				<tbody>
					<?php $no = 0;
					while ($winnerfinal = mysqli_fetch_array($datawinnerfinal)) {
						$no++; ?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo $winnerfinal['partai']; ?></td>
							<td><?php echo $winnerfinal['gelanggang']; ?></td>
							<td><?php echo $winnerfinal['babak']; ?></td>
							<td><?php echo $winnerfinal['kelas']; ?></td>
							<td>
								<?php
								echo $winnerfinal['nm_merah'] . "<br>";
								echo "<b>" . $winnerfinal['kontingen_merah'] . "</b><br>";
								?>
								<form name="PostMedali" id="PostMedali" method="POST" action="admin_do_medali.php">
									<input type="hidden" name="nama" id="nama" value="<?php echo $winnerfinal["nm_merah"]; ?>">
									<input type="hidden" name="kontingen" id="kontingen" value="<?php echo $winnerfinal["kontingen_merah"]; ?>">
									<input type="hidden" name="kelas" id="kelas" value="<?php echo $winnerfinal["kelas"]; ?>">
									<input type="hidden" name="medali" id="medali" value="Perak">
									<input type="hidden" name="idjadwal" id="idjadwal" value="<?php echo $winnerfinal['id_partai']; ?>">
									<input type="submit" class="btn btn-success" value="Perak">
								</form>
								<form name="PostMedali" id="PostMedali" method="POST" action="admin_do_medali.php">
									<input type="hidden" name="nama" id="nama" value="<?php echo $winnerfinal["nm_merah"]; ?>">
									<input type="hidden" name="kontingen" id="kontingen" value="<?php echo $winnerfinal["kontingen_merah"]; ?>">
									<input type="hidden" name="kelas" id="kelas" value="<?php echo $winnerfinal["kelas"]; ?>">
									<input type="hidden" name="medali" id="medali" value="Emas">
									<input type="hidden" name="idjadwal" id="idjadwal" value="<?php echo $winnerfinal['id_partai']; ?>">
									<input type="submit" class="btn btn-warning" value="Emas">
								</form>
							</td>
							<td>
								<?php
								echo $winnerfinal['nm_biru'] . "<br>";
								echo "<b>" . $winnerfinal['kontingen_biru'] . "</b><br>";
								?>
								<form name="PostMedali" id="PostMedali" method="POST" action="admin_do_medali.php">
									<input type="hidden" name="nama" id="nama" value="<?php echo $winnerfinal["nm_biru"]; ?>">
									<input type="hidden" name="kontingen" id="kontingen" value="<?php echo $winnerfinal["kontingen_biru"]; ?>">
									<input type="hidden" name="kelas" id="kelas" value="<?php echo $winnerfinal["kelas"]; ?>">
									<input type="hidden" name="medali" id="medali" value="Perak">
									<input type="hidden" name="idjadwal" id="idjadwal" value="<?php echo $winnerfinal['id_partai']; ?>">
									<input type="submit" class="btn btn-success" value="Perak">
								</form>
								<form name="PostMedali" id="PostMedali" method="POST" action="admin_do_medali.php">
									<input type="hidden" name="nama" id="nama" value="<?php echo $winnerfinal["nm_biru"]; ?>">
									<input type="hidden" name="kontingen" id="kontingen" value="<?php echo $winnerfinal["kontingen_biru"]; ?>">
									<input type="hidden" name="kelas" id="kelas" value="<?php echo $winnerfinal["kelas"]; ?>">
									<input type="hidden" name="medali" id="medali" value="Emas">
									<input type="hidden" name="idjadwal" id="idjadwal" value="<?php echo $winnerfinal['id_partai']; ?>">
									<input type="submit" class="btn btn-warning" value="Emas">
								</form>
							</td>
							<td><?php echo $winnerfinal['status']; ?></td>
							<td><?php echo $winnerfinal['pemenang']; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div><!--/span-->
</div><!--/row-->

<div class="row-fluid sortable">
	<div class="box span12">
		<a href="medali_rekap_pesilat.php" class="btn btn-warning" role="button">Download Data</a>
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Perolehan Medali (Kelas Tanding) Perorangan</h2>
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
						<th>No</th>
						<th>Nama</th>
						<th>Kontingen</th>
						<th>Kelas</th>
						<th>Medali</th>
						<th>Control</th>
					</tr>
				</thead>
				<tbody>
					<?php $no = 0;
					while ($medali = mysqli_fetch_array($datamedali)) {
						$no++; ?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo $medali['nama']; ?></td>
							<td><?php echo $medali['kontingen']; ?></td>
							<td><?php echo $medali['kelas']; ?></td>
							<td><?php echo $medali['medali']; ?></td>
							<td>
								<a class="btn btn-danger" onclick="return confirmDel()" href="admin_del_medali.php?id_medali=<?php echo $medali['id_medali']; ?>&id_partai_FK=<?php echo $medali['id_partai_FK']; ?>">
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
		<a href="medali_rekap_kontingen.php" class="btn btn-warning" role="button">Download Data</a>
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Perolehan Medali (Kelas Tanding) Kontingen</h2>
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
						<th>KONTINGEN</th>
						<th>EMAS</th>
						<th>PERAK</th>
						<th>PERUNGGU</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($koleksimedali = mysqli_fetch_array($datakoleksi)) {; ?>
						<tr>
							<td><?php echo $koleksimedali['kontingen']; ?></td>
							<td>
								<?php
								$sqlcountemas = mysqli_query($koneksi, "SELECT COUNT(*) FROM medali 
															WHERE kontingen='$koleksimedali[kontingen]'
															AND medali='emas'");
								$countemas = mysqli_fetch_array($sqlcountemas);
								echo $countemas[0];
								?>
							</td>
							<td>
								<?php
								$sqlcountperak = mysqli_query($koneksi, "SELECT COUNT(*) FROM medali 
															WHERE kontingen='$koleksimedali[kontingen]'
															AND medali='perak'");
								$countperak = mysqli_fetch_array($sqlcountperak);
								echo $countperak[0];
								?>
							</td>
							<td>
								<?php
								$sqlcountperunggu = mysqli_query($koneksi, "SELECT COUNT(*) FROM medali 
															WHERE kontingen='$koleksimedali[kontingen]'
															AND medali='perunggu'");
								$countperunggu = mysqli_fetch_array($sqlcountperunggu);
								echo $countperunggu[0];
								?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div><!--/span-->
</div><!--/row-->


<?php
get_footer();
?>