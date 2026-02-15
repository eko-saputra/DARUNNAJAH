<?php
	
	include('includes/connection.php');
	require_once("functions/function.php");
	get_header();
	get_sidebar();
	get_bread_four();

	$id_nilaiatlet = mysqli_real_escape_string($koneksi, $_GET["id_nilaiatlet"]);

	//kueri mencari peserta
	$sqldatanilai ="SELECT * FROM nilai_atlet
					WHERE id_nilaiatlet = '$id_nilaiatlet' ";
	$datanilai = mysqli_query($koneksi, $sqldatanilai);
	$nilai = mysqli_fetch_array($datanilai);

?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white edit"></i><span class="break"></span>Ubah Data Nilat Atlet</h2>
						<div class="box-icon">
							<a href="users.php" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal" method="post" action="admin_do_edit_nilai.php">
							<fieldset>
							  <div class="control-group">
								<label class="control-label" for="focusedInput">ID Nilai:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="id_nilaiatlet" id="focusedInput" readonly="1" type="text" placeholder="Username" 
								  value="<?php echo $id_nilaiatlet; ?>">
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Nama:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="nama" id="focusedInput" type="text" value="<?php echo $nilai["nama"]; ?>">
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Kontingen:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="kontingen" id="focusedInput" type="text" value="<?php echo $nilai["kontingen"]; ?>">
								</div>
							  </div>

							   <div class="control-group">
								<label class="control-label" for="focusedInput">Hukuman:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="hukuman" id="focusedInput" type="text" value="<?php echo $nilai["hukuman"]; ?>">
								</div>
							  </div>

							   <div class="control-group">
								<label class="control-label" for="focusedInput">Nilai:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="nilai" id="focusedInput" type="text" value="<?php echo $nilai["nilai"]; ?>">
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