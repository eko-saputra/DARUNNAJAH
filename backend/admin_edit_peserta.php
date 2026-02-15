<?php
	
	include('includes/connection.php');
	require_once("functions/function.php");
	get_header();
	get_sidebar();
	get_bread_four();

	$ID_peserta = mysqli_real_escape_string($koneksi, $_GET["ID_peserta"]);

	//kueri mencari peserta
	$sqlpeserta ="SELECT * FROM peserta
					WHERE peserta.ID_peserta = '$ID_peserta' ";
	$datapeserta = mysqli_query($koneksi, $sqlpeserta);
	$peserta = mysqli_fetch_array($datapeserta);

	//cari data kelas tanding
	$sqlkelastanding = "SELECT * FROM kelastanding ORDER BY nm_kelastanding ASC;";
	$carikelas = mysqli_query($koneksi, $sqlkelastanding);
?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white edit"></i><span class="break"></span>Ubah Data Peserta</h2>
						<div class="box-icon">
							<a href="users.php" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal" method="post" action="admin_do_edit_peserta.php">
							<fieldset>
							  <div class="control-group">
								<label class="control-label" for="focusedInput">ID Peserta:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="ID_peserta" id="focusedInput" readonly="1" type="text" placeholder="Username" 
								  value="<?php echo $ID_peserta; ?>">
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Nama Peserta:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="nama" id="focusedInput" type="text" value="<?php echo $peserta["nm_lengkap"]; ?>">
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Jenis Kelamin:</label>
								<div class="controls">
								  	<select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
								     	<option value="Laki-laki" <?php if($peserta["golongan"]=="Laki-laki") { echo "selected"; } ?>>Laki-laki</option>
										<option value="Perempuan" <?php if($peserta["jenis_kelamin"]=="Perempuan") { echo "selected"; } ?>>Perempuan</option>
								     </select>
								</div>
							  </div>

							   <div class="control-group">
								<label class="control-label" for="focusedInput">Golongan:</label>
								<div class="controls">
								  	<select class="form-control" name="golongan" id="golongan">
								     	<option value="Usia Dini" <?php if($peserta["golongan"]=="Usia Dini") { echo "selected"; } ?>>Usia Dini</option>
										<option value="Pra Remaja" <?php if($peserta["golongan"]=="Pra Remaja") { echo "selected"; } ?>>Pra Remaja</option>
										<option value="Remaja" <?php if($peserta["golongan"]=="Remaja") { echo "selected"; } ?>>Remaja</option>
										<option value="Dewasa" <?php if($peserta["golongan"]=="Dewasa") { echo "selected"; } ?>>Dewasa</option>
								    </select>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Kelas Tanding:</label>
								<div class="controls">
								  	<select class="form-control" name="kelas_tanding" id="kelas_tanding">
								    	<?php while($kelastanding = mysqli_fetch_array($carikelas)) { ?>
										<option value="<?php echo $kelastanding[0]; ?>" <?php if($kelastanding[0] == $peserta["kelas_tanding_FK"]) { echo "selected"; } ?>><?php echo $kelastanding[1]; ?></option>
										<?php } ?> 	
								    </select>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Kontingen:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="kontingen" id="focusedInput" type="text" value="<?php echo $peserta["kontingen"] ?>">
								</div>
							  </div>

							  <div class="form-actions">
								<button type="submit" onclick="return confirmUpdate()" class="btn btn-primary">Simpan Perubahan</button>
							  </div>
							</fieldset>
						</form>
					
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
<?php
	get_footer();
?>