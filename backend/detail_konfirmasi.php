<?php
	
	include('includes/connection.php');
	require_once("functions/function.php");
	get_header();
	get_sidebar();
	get_bread_four();

	$ID_konfirmasi = mysqli_real_escape_string($koneksi, $_GET["ID_konfirmasi"]);

	//query data konfirmasi
	$sqlkonfirmasi = "SELECT * FROM konfirmasi WHERE ID_konfirmasi='$ID_konfirmasi'";
	$datakonfirmasi = mysqli_query($koneksi, $sqlkonfirmasi);
	$konfirmasi = mysqli_fetch_array($datakonfirmasi);
?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white edit"></i><span class="break"></span>Detail Data Konfirmasi</h2>
						<div class="box-icon">
							<a href="users.php" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal" method="post" action="approve_konfirmasi.php">
							<fieldset>
							  <div class="control-group">
								<label class="control-label" for="focusedInput">ID Konfirmasi:</label>
								<div class="controls">
								  <input class="input-xlarge focused" name="ID_konfirmasi" id="focusedInput" readonly="1" type="text" placeholder="Username" 
								  value="<?php echo $konfirmasi['ID_konfirmasi']; ?>">
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Bank Tujuan:</label>
								<div class="controls">
								  <?php echo $konfirmasi['bank_tujuan'];?>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Bank Pengirim:</label>
								<div class="controls">
								  <?php echo $konfirmasi['bank_pengirim'];?>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">No REK Pengirim:</label>
								<div class="controls">
								  <?php echo $konfirmasi['norek_pengirim'];?>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Nama Pengirim:</label>
								<div class="controls">
								  <?php echo $konfirmasi['nm_pengirim']." / ".$konfirmasi['kontak'];?>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Tgl TRF:</label>
								<div class="controls">
								  <?php echo $konfirmasi['tgl_transfer'];?>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Jumlah:</label>
								<div class="controls">
								  <?php echo $konfirmasi['jumlah'];?>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Catatan:</label>
								<div class="controls">
								  <?php echo $konfirmasi['catatan'];?>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="focusedInput">Bukti TRF:</label>
								<div class="controls">
								  <img src=../buktibayar/<?php echo $konfirmasi['bukti']; ?> height="500px"></img>
								</div>
							  </div>

							  <div class="form-actions">
								<button type="submit" onclick="return confirmUpdate()" class="btn btn-primary">Setujui Data</button>
							  </div>
							</fieldset>
						</form>
					
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
<?php
	get_footer();
?>