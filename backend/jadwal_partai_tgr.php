<?php
	require_once("functions/function.php");
	get_header();
	get_sidebar();
	get_bread_two();

	include("includes/connection.php");

	//Mencari data jadwal pertandingan
	$sqljadwal = "SELECT * FROM jadwal_tgr ORDER BY id_partai DESC";
	$jadwal_tgr = mysqli_query($koneksi,$sqljadwal);
	
?>
			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white download"></i><span class="break"></span>Input Jadwal Tunggal</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal" method="post" action="do_post_jadwal_tunggal.php">
							<table>
						<tr>
							<td>Tanggal</td>
							<td><input type="date" name="tgl" id="tgl" maxlength="35" placeholder="Contoh: 2019-12-29" required></td>
							<td>No Partai</td>
							<td><input type="text" name="partai" id="partai" maxlength="35" placeholder="1 / 2 / 3 dst..." required></td>
						</tr>
						<tr>
							<td>Kategori</td>
							<td><input type="text" name="kategori" id="kategori" maxlength="35" value="TUNGGAL" readonly></td>
							<td>Golongan</td>
							<td><input type="text" name="golongan" id="golongan" maxlength="35" placeholder="Contoh: Remaja / Dewasa" required></td>
						</tr>
						<tr>
							<td>Nama Pesilat Merah</td>
							<td><input type="text" name="nm_merah" id="nm_merah" maxlength="55" placeholder="Nama pesilat sudut merah" required></td>
							<td>Kontingen Merah</td>
							<td><input type="text" name="kontingen_merah" id="kontingen_merah" maxlength="55" placeholder="Kontingen pesilat sudut merah" required></td>
						</tr>
						<tr>
							<td>Nama Pesilat Biru</td>
							<td><input type="text" name="nm_biru" id="nm_biru" maxlength="55" placeholder="Nama pesilat sudut biru" required></td>
							<td>Kontingen Biru</td>
							<td><input type="text" name="kontingen_biru" id="kontingen_biru" maxlength="55" placeholder="Kontingen pesilat sudut biru" required></td>
						</tr>
						<tr>
							<td>Babak</td>
							<td><input type="text" name="babak" id="babak" maxlength="55" placeholder="PENYISIHAN / SEMIFINAL / FINAL" required></td>
							<td colspan="2"><input type="submit" class="btn btn-info" value="SUBMIT"></td>
						</tr>
							</table>
						</form>
					</div>
				</div><!--/span-->
			</div><!--/row-->

			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white download"></i><span class="break"></span>Input Jadwal Ganda</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal" method="post" action="do_post_jadwal_ganda.php">
							<table>
						<tr>
							<td>Tanggal</td>
							<td><input type="date" name="tgl" id="tgl" maxlength="35" placeholder="Contoh: 2019-12-29" required></td>
							<td>No Partai</td>
							<td><input type="text" name="partai" id="partai" maxlength="35" placeholder="1 / 2 / 3 dst..." required></td>
						</tr>
						<tr>
							<td>Kategori</td>
							<td><input type="text" name="kategori" id="kategori" maxlength="35" value="GANDA" readonly></td>
							<td>Golongan</td>
							<td><input type="text" name="golongan" id="golongan" maxlength="35" placeholder="Contoh: Remaja / Dewasa" required></td>
						</tr>
						<tr>
							<td>Nama Pesilat Merah 1</td>
							<td><input type="text" name="nm_merah1" id="nm_merah1" maxlength="55" placeholder="Nama pesilat sudut merah 1" required></td>
							<td>Nama Pesilat Merah 2</td>
							<td><input type="text" name="nm_merah2" id="nm_merah2" maxlength="55" placeholder="Nama pesilat sudut merah 2" required></td>
							<td>Kontingen Merah</td>
							<td><input type="text" name="kontingen_merah" id="kontingen_merah" maxlength="55" placeholder="Kontingen pesilat sudut merah" required></td>
						</tr>
						<tr>
							<td>Nama Pesilat Biru 1</td>
							<td><input type="text" name="nm_biru1" id="nm_biru1" maxlength="55" placeholder="Nama pesilat sudut biru 1" required></td>
							<td>Nama Pesilat Biru 2</td>
							<td><input type="text" name="nm_biru2" id="nm_biru2" maxlength="55" placeholder="Nama pesilat sudut biru 2" required></td>
							<td>Kontingen Biru</td>
							<td><input type="text" name="kontingen_biru" id="kontingen_biru" maxlength="55" placeholder="Kontingen pesilat sudut biru" required></td>
						</tr>
						<tr>
							<td>Babak</td>
							<td><input type="text" name="babak" id="babak" maxlength="55" placeholder="PENYISIHAN / SEMIFINAL / FINAL" required></td>
							<td colspan="5"><input type="submit" class="btn btn-info" value="SUBMIT"></td>
						</tr>
							</table>
						</form>
					</div>
				</div><!--/span-->
			</div><!--/row-->

			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white download"></i><span class="break"></span>Input Jadwal Regu</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal" method="post" action="do_post_jadwal_regu.php">
							<table>
							<tr>
								<td>Tanggal</td>
								<td><input type="date" name="tgl" id="tgl" maxlength="35" placeholder="Contoh: 2019-12-29" required></td>
								<td>No Partai</td>
								<td><input type="text" name="partai" id="partai" maxlength="35" placeholder="1 / 2 / 3 dst..." required></td>
							</tr>
							<tr>
								<td>Kategori</td>
								<td><input type="text" name="kategori" id="kategori" maxlength="35" value="REGU" readonly></td>
								<td>Golongan</td>
								<td><input type="text" name="golongan" id="golongan" maxlength="35" placeholder="Contoh: Remaja / Dewasa" required></td>
							</tr>
							<tr>
								<td>Nama Pesilat Merah 1</td>
								<td><input type="text" name="nm_merah1" id="nm_merah1" maxlength="55" placeholder="Nama pesilat sudut merah 1" required></td>
								<td>Nama Pesilat Merah 2</td>
								<td><input type="text" name="nm_merah2" id="nm_merah2" maxlength="55" placeholder="Nama pesilat sudut merah 2" required></td>
								<td>Nama Pesilat Merah 3</td>
								<td><input type="text" name="nm_merah3" id="nm_merah3" maxlength="55" placeholder="Nama pesilat sudut merah 3" required></td>
								<td>Kontingen Merah</td>
								<td><input type="text" name="kontingen_merah" id="kontingen_merah" maxlength="55" placeholder="Kontingen pesilat sudut merah" required></td>
							</tr>
							<tr>
								<td>Nama Pesilat Biru 1</td>
								<td><input type="text" name="nm_biru1" id="nm_biru1" maxlength="55" placeholder="Nama pesilat sudut biru 1" required></td>
								<td>Nama Pesilat Biru 2</td>
								<td><input type="text" name="nm_biru2" id="nm_biru2" maxlength="55" placeholder="Nama pesilat sudut biru 2" required></td>
								<td>Nama Pesilat Biru 3</td>
								<td><input type="text" name="nm_biru3" id="nm_biru3" maxlength="55" placeholder="Nama pesilat sudut biru 3" required></td>
								<td>Kontingen Biru</td>
								<td><input type="text" name="kontingen_biru" id="kontingen_biru" maxlength="55" placeholder="Kontingen pesilat sudut biru" required></td>
							</tr>
							<tr>
								<td>Babak</td>
								<td><input type="text" name="babak" id="babak" maxlength="55" placeholder="PENYISIHAN / SEMIFINAL / FINAL" required></td>
								<td colspan="7"><input type="submit" class="btn btn-info" value="SUBMIT"></td>
							</tr>
							</table>
						</form>
					</div>
				</div><!--/span-->
			</div><!--/row-->

			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white download"></i><span class="break"></span>Data Jadwal TGR</h2>
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
								<th>NO</th>
								<th>Tanggal</th>
								<th>Partai</th>
								<th>Kategori</th>
								<th>Golongan</th>
								<th>Nama Merah</th>
								<th>Kontingen Merah</th>
								<th>Nama Biru</th>
								<th>Kontingen Biru</th>
								<th>Status</th>
								<th>Pemenang</th>
								<th>Babak</th>
								<th>Medali</th>
								<th>ACTIONS</th>
							</tr>
						</thead>
						<tbody>
							<?php $no=0; while($jadwal = mysqli_fetch_array($jadwal_tgr)) { $no++;?>
							<tr>
								<td><?php echo $no; ?></td>
								<td><?php echo $jadwal['tgl']; ?></td>
								<td><?php echo $jadwal['partai']; ?></td>
								<td><?php echo $jadwal['kategori']; ?></td>
								<td><?php echo $jadwal['golongan']; ?></td>
								<td><?php echo $jadwal['nm_merah']; ?></td>
								<td><?php echo $jadwal['kontingen_merah']; ?></td>
								<td><?php echo $jadwal['nm_biru']; ?></td>
								<td><?php echo $jadwal['kontingen_biru']; ?></td>
								<td><?php echo $jadwal['status']; ?></td>
								<td><?php echo $jadwal['pemenang']; ?></td>
								<td><?php echo $jadwal['babak']; ?></td>
								<td><?php echo $jadwal['medali']; ?></td>
								<td class="center">
									<a class="btn btn-danger" onclick="return confirmDel()" href="del_partai_tgr.php?id_partai=<?php echo $jadwal['id_partai'];?>">
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
			<h2><i class="halflings-icon white remove"></i><span class="break"></span>CLEAR ALL - JADWAL & NILAI KELAS TGR</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>

		<div class="box-content">
			<form class="form-horizontal" method="post" action="do_clear_jadwal_tgr.php">
				<p>Dengan menekan tombol "HAPUS SEMUA" di bawah ini, maka seluruh data <b>Jadwal Partai beserta Nilai Penjuriannya</b> pada <b>Kelas TGR</b> akan hilang dari database.</p>
				<input type="submit" onclick="return confirmDel()" class="btn btn-danger" value="HAPUS SEMUA">
			</form>
		</div>
	</div><!--/span-->		
</div><!--/row-->


<?php
	get_footer();
?>