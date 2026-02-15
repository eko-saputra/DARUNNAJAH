	<?php
	require_once("functions/function.php");
	get_header();
	get_sidebar();
	get_bread();

	include 'includes/connection.php';

	//kueri data peserta tanding
	$sqlpeserta = "SELECT * FROM peserta
						INNER JOIN kelastanding ON peserta.kelas_tanding_FK=kelastanding.ID_kelastanding
						ORDER BY peserta.jenis_kelamin,peserta.kelas_tanding_FK ASC";
	$datapeserta = mysqli_query($koneksi, $sqlpeserta);

	//kueri data peserta TGR
	$sqlpesertatgr = "SELECT * FROM peserta
						WHERE peserta.kategori_tanding <> 'Tanding'
						ORDER BY peserta.kode_gr,peserta.kontingen ASC";
	$datapesertatgr = mysqli_query($koneksi, $sqlpesertatgr);

	?>
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="halflings-icon white user"></i><span class="break"></span>Data Pendaftar Tanding</h2>
				<div class="box-icon">
					<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
					<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
					<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
				</div>
			</div>
			<br>
			<a href="tambah_peserta.php" class="btn btn-primary">+ Tambah Peserta</a>
			<div class="box-content">
				<table class="table table-striped table-bordered bootstrap-datatable datatable">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama</th>
							<th>Jen.Kelamin</th>
							<th>TB</th>
							<th>BB</th>
							<th>Keterangan</th>
							<th>Kontingen</th>
							<th>Kelas Tanding</th>
							<th>Golongan</th>
							<th>Lunas</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 0;
						$paid = 0;
						while ($peserta = mysqli_fetch_array($datapeserta)) {
							$no++; ?>
							<tr>
								<td><?php echo $no; ?></td>
								<td><?php echo $peserta['nm_lengkap']; ?></td>
								<td><?php echo $peserta['jenis_kelamin']; ?></td>
								<td><?php echo $peserta['tb']; ?></td>
								<td><?php echo $peserta['bb']; ?></td>
								<td>
									<?php
									echo $peserta['asal_sekolah'];
									if ($peserta['asal_sekolah'] <> '') {
										echo ", Kelas ";
									}
									echo $peserta['kelas'];
									?>
								</td>
								<td><?php echo $peserta['kontingen']; ?></td>
								<td><?php echo $peserta['nm_kelastanding']; ?></td>
								<td><?php echo $peserta['golongan']; ?></td>
								<td>
									<?php
									if ($peserta['status'] == 'PAID') {
										echo "YES";
										$paid = $paid + 1;
									} else {
										echo "NO";
									}
									?>
								</td>
								<td class="center">
									<a class="btn btn-info" href="admin_do_paid.php?ID_peserta=<?php echo $peserta['ID_peserta']; ?>">
										<i class="halflings-icon white usd"></i>
									</a>
									<a class="btn btn-info" href="admin_edit_peserta.php?ID_peserta=<?php echo $peserta['ID_peserta']; ?>">
										<i class="halflings-icon white pencil"></i>
									</a>
									<a class="btn btn-danger" onclick="return confirmDel()" href="admin_do_del_peserta.php?ID_peserta=<?php echo $peserta['ID_peserta']; ?>">
										<i class="halflings-icon white trash"></i>
									</a>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				Pendaftar kelas Tanding yang telah lunas/terverifikasi datanya ialah sebanyak <?php echo $paid; ?> dari <?php echo $no; ?>.
			</div>
		</div><!--/span-->
	</div><!--/row-->

	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="halflings-icon white user"></i><span class="break"></span>Data Pendaftar TGR</h2>
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
							<th>Jen.Kelamin</th>
							<th>Keterangan</th>
							<th>Kontingen</th>
							<th>Kategori</th>
							<th>Golongan</th>
							<th>Lunas</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $notgr = 0;
						$paidtgr = 0;
						while ($pesertatgr = mysqli_fetch_array($datapesertatgr)) {
							$notgr++; ?>
							<tr>
								<td><?php echo $notgr; ?></td>
								<td><?php echo $pesertatgr['nm_lengkap']; ?></td>
								<td><?php echo $pesertatgr['jenis_kelamin']; ?></td>
								<td>
									<?php
									echo $pesertatgr['asal_sekolah'];
									if ($pesertatgr['asal_sekolah'] <> '') {
										echo ", Kelas ";
									}
									echo $pesertatgr['kelas'];
									?>
								</td>
								<td><?php echo $pesertatgr['kontingen']; ?></td>
								<td><?php echo $pesertatgr['kategori_tanding']; ?></td>
								<td><?php echo $pesertatgr['golongan']; ?></td>
								<td>
									<?php
									if ($pesertatgr['status'] == 'PAID') {
										echo "YES";
										$paidtgr = $paidtgr + 1;
									} else {
										echo "NO";
									}
									?>
								</td>
								<td class="center">
									<a class="btn btn-info" href="admin_do_paid.php?ID_peserta=<?php echo $pesertatgr['ID_peserta']; ?>">
										<i class="halflings-icon white usd"></i>
									</a>
									<a class="btn btn-info" href="admin_edit_peserta_tgr.php?ID_peserta=<?php echo $pesertatgr['ID_peserta']; ?>">
										<i class="halflings-icon white pencil"></i>
									</a>
									<a class="btn btn-danger" onclick="return confirmDel()" href="admin_do_del_peserta.php?ID_peserta=<?php echo $pesertatgr['ID_peserta']; ?>">
										<i class="halflings-icon white trash"></i>
									</a>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				Pendaftar TGR yang telah lunas/terverifikasi datanya ialah sebanyak <?php echo $paidtgr; ?> dari <?php echo $notgr; ?>.
			</div>
		</div><!--/span-->
	</div><!--/row-->

	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="halflings-icon white remove"></i><span class="break"></span>CLEAR ALL DATABASE</h2>
				<div class="box-icon">
					<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
					<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
					<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
				</div>
			</div>

			<div class="box-content">
				<form class="form-horizontal" method="post" action="do_clear_db.php">
					<p>Dengan menekan tombol "HAPUS SELURUH DATABASE" di bawah ini, maka seluruh Data Peserta, Nomor Undian, Bagan dan Jadwal Pertandingan (beserta nilai penjuriannya) akan terhapus. Sehingga Aplikasi dapat digunakan kembali dari awal untuk kegiatan kejuaraan lainnya.</p>
					<input type="submit" onclick="return confirmDel()" class="btn btn-danger" value="HAPUS SELURUH DATABASE">
				</form>
			</div>
		</div><!--/span-->
	</div><!--/row-->

	<?php
	get_footer();
	?>