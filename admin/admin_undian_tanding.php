<?php
	require_once("functions/function.php");
	get_header();
	get_sidebar();
	get_bread();

	include 'includes/connection.php';

	//cari data kelas tanding
	$sqlkelastanding = "SELECT * FROM kelastanding";
	$carikelastanding = mysqli_query($koneksi,$sqlkelastanding);

	//cari pengundian data
	$sqlhasilundian = "SELECT * FROM undian 
						INNER JOIN peserta ON peserta.ID_peserta=undian.id_peserta 
						INNER JOIN kelastanding ON peserta.kelas_tanding_FK=kelastanding.ID_kelastanding
						ORDER BY undian.id_undian DESC";
	$carihasilundian = mysqli_query($koneksi,$sqlhasilundian);
?>
<link href="css/spinner.css" rel="stylesheet" type="text/css" media="all" />

<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Pengundian Nomor Kelas Tanding</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>

		<div class="box-content">
			<form class="form-horizontal" name="UndianPeserta" id="UndianPeserta" method="post" action="admin_do_undian_tanding.php">
				<table>
					<tr>
						<td>
							<select class="form-control" name="golongan" id="golongan">
								<option value="">-- Pilih Golongan --</option>
								<option value="Usia Dini 2A">Usia Dini 2A</option>
								<option value="Usia Dini 2B">Usia Dini 2B</option>
								<option value="Pra Remaja">Pra Remaja</option>
								<option value="Remaja">Remaja</option>
								<option value="Dewasa">Dewasa</option>
							</select>
						</td>
						<td>
							<select name="jenis_kelamin" id="jenis_kelamin">
								<option value="">--- Pilih Putra/Putri ---</option>
								<option value="Putra">Putra</option>
								<option value="Putri">Putri</option>
							</select>
						</td>
						<td>
							<select name="kelas_tanding" id="kelas_tanding">
								<option value="">--- Pilih Kelas Tanding ---</option>
								<?php while($kelastanding = mysqli_fetch_array($carikelastanding)) { ?>
								<option value="<?php echo $kelastanding[0]; ?>"><?php echo $kelastanding[1]; ?></option>
								<?php } ?>
							</select>
						</td>
						<td><input type="submit" class="btn btn-info" name="kocok" value="SHUFFLE" onclick="return confirmUndi();"></td>
					</tr>
				</table>
			</form>
		</div>
	</div><!--/span-->		
</div><!--/row-->

<div id="spinner" style="visibility: hidden;"class="spinner">
	<h1>...Mengundi Peserta...</h1>
	<div class="bounce1"></div>
	<div class="bounce2"></div> 
	<div class="bounce3"></div>
</div>

<script type="text/javascript">
	$('#UndianPeserta').submit(function() {
	    $('#spinner').css('visibility', 'visible');
	    return true;
	});
</script>

<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Hasil Pengundian Kelas Tanding</h2>
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
						<th>#</th>
						<th>Nama Pesilat</th>
						<th>Golongan</th>
						<th>Kelas Tanding</th>
						<th>Kontingen</th>
						<th>No Undian</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php $no=0; while($hasilundian = mysqli_fetch_array($carihasilundian)) { $no++ ; ?>
					<tr>
						<td><?php echo $no; ?></td>
						<td><?php echo $hasilundian['nm_lengkap']; ?></td>
						<td><?php echo $hasilundian['golongan']; ?></td>
						<td>
							<?php echo $hasilundian['nm_kelastanding']; 
								if($hasilundian['jenis_kelamin']=='Putra') { echo " Putra"; } else { echo " Putri" ;}
							?>
						</td>
						<td><?php echo $hasilundian['kontingen']; ?></td>
						<td><?php echo $hasilundian['no_undian']; ?></td>
						<td class="center">
							<a class="btn btn-danger" onclick="return confirmDel()" href="admin_do_del_undian.php?id_undian=<?php echo $hasilundian['id_undian']; ?>">
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
			<h2><i class="halflings-icon white user"></i><span class="break"></span>CLEAR ALL - DATA HASIL UNDIAN KELAS TANDING</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>

		<div class="box-content">
			<form class="form-horizontal" method="post" action="do_clear_undian_tanding.php">
				<p>Dengan menekan tombol "HAPUS SEMUA" di bawah ini, maka seluruh data hasil dari proses pengundian nomor peserta pada <b>Kelas Tanding</b> akan hilang dari database.</p>
				<input type="submit" onclick="return confirmDel()" class="btn btn-danger" value="HAPUS SEMUA">
			</form>
		</div>
	</div><!--/span-->		
</div><!--/row-->

<script type="text/javascript">
function confirmUndi() {
    return confirm("Proses ini akan menghapus data undian & jadwal sebelumnya pada kelompok yang dipilih.\n\nYakin ingin mengundi ulang?");
}
</script>

<?php
	get_footer();
?>
