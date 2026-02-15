<?php
	require_once("functions/function.php");
	get_header();
	get_sidebar();
	get_bread();

	include 'includes/connection.php';

	//kueri data KONFIRMASI
	$sqlkonfirmasi = "SELECT * FROM konfirmasi ORDER BY status DESC";
	$datakonfirmasi = mysqli_query($koneksi, $sqlkonfirmasi);
?>
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Data Konfirmasi Pembayaran</h2>
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
						<th>Bank Tujuan</th>
						<th>Bank Asal</th>
						<th>Nama Pengirim</th>
						<th>Tgl TRF</th>
						<th>Jumlah</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php $no=0; while($konfirmasi = mysqli_fetch_array($datakonfirmasi)) { $no++; ?>
					<tr>
						<td><?php echo $no; ?></td>
						<td><?php echo $konfirmasi['bank_tujuan']; ?></td>
						<td><?php echo $konfirmasi['bank_pengirim']." / ".$konfirmasi['norek_pengirim']; ?></td>
						<td><?php echo $konfirmasi['nm_pengirim']." / ".$konfirmasi['kontak']; ?></td>
						<td><?php echo $konfirmasi['tgl_transfer']; ?></td>
						<td><?php echo $konfirmasi['jumlah']; ?></td>
						<td><?php echo $konfirmasi['status']; ?></td>
						<td class="center">
							<a class="btn btn-info" href="detail_konfirmasi.php?ID_konfirmasi=<?php echo $konfirmasi['ID_konfirmasi']; ?>">
								<i class="halflings-icon white eye-open"></i>  
							</a>
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