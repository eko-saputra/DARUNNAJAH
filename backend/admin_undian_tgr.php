<?php
	require_once("functions/function.php");
	get_header();
	get_sidebar();
	get_bread();

	include 'includes/connection.php';

	//cari pengundian data
	$sqlhasilundian = "SELECT * FROM undian_tgr
						INNER JOIN peserta ON peserta.kode_gr=undian_tgr.idpesertatgr
						ORDER BY undian_tgr.id_undiantgr DESC";
	$carihasilundian = mysqli_query($koneksi,$sqlhasilundian);
?>
<link href="css/spinner.css" rel="stylesheet" type="text/css" media="all" />

<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Pengundian Nomor TGR</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>

		<div class="box-content">
			<form class="form-horizontal" name="UndianPeserta" id="UndianPeserta" method="post" action="admin_do_undian_tgr.php">
				<table>
					<tr>
						<td>
							<select class="form-control" name="golongan" id="golongan">
								<option value="">-- Pilih Golongan --</option>
								<option value="Usia Dini">Usia Dini</option>
								<option value="Pra Remaja">Pra Remaja</option>
								<option value="Remaja">Remaja</option>
								<option value="Dewasa">Dewasa</option>
							</select>
						</td>
						<td>
							<select name="jenis_kelamin" id="jenis_kelamin">
								<option value="">--- Pilih Putra/Putri ---</option>
								<option value="Laki-laki">Putra</option>
								<option value="Perempuan">Putri</option>
							</select>
						</td>
						<td>
							<select name="kategori_tanding" id="kategori_tanding">
								<option value="">--- Pilih Kategori ---</option>
								<option value="Tunggal">Tunggal</option>
								<option value="Ganda">Ganda</option>
								<option value="Regu">Regu</option>
							</select>
						</td>
						<td><input type="submit" class="btn btn-info" name="kocok" value="SHUFFLE"></td>
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
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Hasil Pengundian TGR</h2>
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
						<th>Kategori</th>
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
							<?php echo $hasilundian['kategori_tanding']; 
								if($hasilundian['jenis_kelamin']=='Laki-laki') { echo " Putra"; } else { echo " Putri" ;}
							?>
						</td>
						<td><?php echo $hasilundian['kontingen']; ?></td>
						<td><?php echo $hasilundian['no_undian']; ?></td>
						<td class="center">
							<a class="btn btn-danger" onclick="return confirmDel()" href="admin_do_del_undian_tgr.php?id_undiantgr=<?php echo $hasilundian['id_undiantgr']; ?>">
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
			<h2><i class="halflings-icon white user"></i><span class="break"></span>CLEAR ALL - DATA HASIL UNDIAN KELAS TGR</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>

		<div class="box-content">
			<form class="form-horizontal" method="post" action="do_clear_undian_tgr.php">
				<p>Dengan menekan tombol "HAPUS SEMUA" di bawah ini, maka seluruh data hasil dari proses pengundian nomor peserta pada <b>Kelas TGR</b> akan hilang dari database.</p>
				<input type="submit" onclick="return confirmDel()" class="btn btn-danger" value="HAPUS SEMUA">
			</form>
		</div>
	</div><!--/span-->		
</div><!--/row-->

<?php
	get_footer();
?>