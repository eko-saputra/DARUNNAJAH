<?php
	
	include('includes/connection.php');
	require_once("functions/function.php");
	get_header();
	get_sidebar();
	get_bread_four();

	$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"]);

	//kueri mencari peserta
	$sqlpartai ="SELECT * FROM jadwal_tanding
					WHERE id_partai = '$id_partai' ";
	$datapartai = mysqli_query($koneksi, $sqlpartai);
	$partai = mysqli_fetch_array($datapartai);

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
						<form class="form-horizontal" method="post" action="admin_do_edit_partai.php">
							<fieldset>
							  <div class="control-group">
								<label class="control-label" for="focusedInput">ID Partai:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="id_partai" id="focusedInput" readonly="1" type="text" placeholder="Username" 
								  value="<?php echo $id_partai; ?>">
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Tanggal:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="tgl" id="focusedInput" type="text" value="<?php echo $partai["tgl"]; ?>">
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Gelanggang:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="gelanggang" id="focusedInput" type="text" value="<?php echo $partai["gelanggang"]; ?>">
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">No Partai:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="partai" id="focusedInput" type="text" value="<?php echo $partai["partai"]; ?>">
								</div>
							  </div>

							   <div class="control-group">
								<label class="control-label" for="focusedInput">Babak:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="babak" id="focusedInput" type="text" value="<?php echo $partai["babak"]; ?>">
								</div>
							  </div>

							   <div class="control-group">
								<label class="control-label" for="focusedInput">Kelompok:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="kelas" id="focusedInput" type="text" value="<?php echo $partai["kelas"]; ?>">
								</div>
							  </div>

							   <div class="control-group">
								<label class="control-label" for="focusedInput">Nama Pesilat (Sudut Merah):</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="nm_merah" id="focusedInput" type="text" value="<?php echo $partai["nm_merah"]; ?>">
								</div>
							  </div>

							   <div class="control-group">
								<label class="control-label" for="focusedInput">Kontingen (Sudut Merah):</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="kontingen_merah" id="focusedInput" type="text" value="<?php echo $partai["kontingen_merah"]; ?>">
								</div>
							  </div>

							   <div class="control-group">
								<label class="control-label" for="focusedInput">Nama Pesilat (Sudut Biru):</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="nm_biru" id="focusedInput" type="text" value="<?php echo $partai["nm_biru"]; ?>">
								</div>
							  </div>

							   <div class="control-group">
								<label class="control-label" for="focusedInput">Kontingen (Sudut Biru):</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="kontingen_biru" id="focusedInput" type="text" value="<?php echo $partai["kontingen_biru"]; ?>">
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Status:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="status" id="focusedInput" type="text" value="<?php echo $partai["status"]; ?>">
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Pemenang:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="pemenang" id="focusedInput" type="text" value="<?php echo $partai["pemenang"]; ?>">
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Aktif:</label>
								<div class="controls">
								  <select class="form-control" name="aktif" id="aktif">
										<option value="0" <?php if($partai["aktif"]=="0") { echo "selected"; } ?>>NO</option>
										<option value="1" <?php if($partai["aktif"]=="1") { echo "selected"; } ?>>YES</option>
								    </select>
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