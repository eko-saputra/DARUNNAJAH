<?php
	require_once("functions/function.php");
	get_header();
	get_sidebar();
	get_bread();

	include 'includes/connection.php';

	//query data perolehan medali
	$sqlriwayatnilai = "SELECT * FROM nilai_atlet ORDER BY no_partai ASC";
	$riwayatnilai = mysqli_query($koneksi, "$sqlriwayatnilai");

	//query data koleksi medali
	$sqlatletmvp = "SELECT nama,kontingen, SUM(hukuman) as total_hukuman, SUM(nilai) as total_nilai from nilai_atlet GROUP BY kontingen,nama ORDER BY SUM(nilai) DESC";
	$datamvp = mysqli_query($koneksi, "$sqlatletmvp");
?>
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Data Riwayat Nilai Pesilat</h2>
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
						<th>No Partai</th>
						<th>Nama Atlet</th>
						<th>Kontingen</th>
						<th>Hukuman</th>
						<th>Nilai</th>
						<th>Control</th>
					</tr>
				</thead>
				<tbody>
					<?php $no=0; while($datanilai = mysqli_fetch_array($riwayatnilai)) { $no++;?>
					<tr>
						<td><?php echo $no; ?></td>
						<td><?php echo $datanilai['no_partai']; ?></td>
						<td><?php echo $datanilai['nama']; ?></td>
						<td><?php echo $datanilai['kontingen']; ?></td>
						<td><?php echo $datanilai['hukuman']; ?></td>
						<td><?php echo $datanilai['nilai']; ?></td>
						<td>
							<a class="btn btn-info" href="edit_nilai_atlet.php?id_nilaiatlet=<?php echo $datanilai['id_nilaiatlet'];?>">
								<i class="halflings-icon white pencil"></i>  
							</a>
							<a class="btn btn-danger" onclick="return confirmDel()" href="admin_del_nilaiatlet.php?id_nilaiatlet=<?php echo $datanilai['id_nilaiatlet'];?>">
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
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Peringkat Pesilat Terbaik</h2>
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
						<th>RANK</th>
						<th>NAMA</th>
						<th>KONTINGEN</th>
						<th>HUKUMAN</th>
						<th>NILAI</th>
					</tr>
				</thead>
				<tbody>
					<?php $no=0; while($atletmvp = mysqli_fetch_array($datamvp)) { $no++;?>
					<tr>
						<td><?php echo $no; ?></td>
						<td><?php echo $atletmvp['nama']; ?></td>
						<td><?php echo $atletmvp['kontingen']; ?></td>
						<td><?php echo $atletmvp['total_hukuman']; ?></td>
						<td><?php echo $atletmvp['total_nilai']; ?></td>
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