<?php
require_once("functions/function.php");
get_header();
get_sidebar();
get_bread_two();

include("includes/connection.php");

?>
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white download"></i><span class="break"></span>Cetak Jadwal Tanding SEMIFINAL</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
			<form class="form-horizontal" method="post" target="_blank" action="do_cetak_jadwal.php">
				<table>
					<tr>
						<td><input type="hidden" name="tgl" id="tgl" value=<?php echo date("Y-m-d"); ?> readonly></td>
						<td>
							<select name="gelanggang" id="gelanggang">
								<option value="">-- Pilih Gelanggang --</option>
								<option value="A">A</option>
								<option value="B">B</option>
								<option value="C">C</option>
								<option value="D">D</option>
							</select>
						</td>
						<td>
							<select name="kelas" id="kelas">
								<option value="">-- Pilih Golongan --</option>
								<option value="Usia Dini 2A">Usia Dini 2A</option>
								<option value="Usia Dini 2B">Usia Dini 2B</option>
								<option value="Pra Remaja">Pra Remaja</option>
								<option value="Remaja">Remaja</option>
								<option value="Dewasa">Dewasa</option>
							</select>
						</td>
						<td>
							<select name="jk">
								<option value="">Pilih Jenis Kelamin</option>
								<option value="Putra">Putra</option>
								<option value="Putri">Putri</option>
							</select>
						</td>
						<td>
							<select name="nama_kelas">
								<option value="">Pilih Kelas</option>
								<option value="UNDER">UNDER</option>
								<option value="Kelas A">Kelas A</option>
								<option value="Kelas B">Kelas B</option>
								<option value="Kelas C">Kelas C</option>
								<option value="Kelas D">Kelas D</option>
								<option value="Kelas E">Kelas E</option>
								<option value="Kelas F">Kelas F</option>
								<option value="Kelas G">Kelas G</option>
								<option value="Kelas H">Kelas H</option>
								<option value="Kelas I">Kelas I</option>
								<option value="Kelas J">Kelas J</option>
								<option value="Kelas K">Kelas K</option>
								<option value="Kelas L">Kelas L</option>
								<option value="Kelas M">Kelas M</option>
								<option value="Kelas N">Kelas N</option>
								<!-- Tambah sesuai kebutuhan -->
							</select>
						</td>
						<td><input type="submit" class="btn btn-info" value="Generate"></td>
					</tr>
				</table>
			</form>
		</div>
	</div><!--/span-->
</div><!--/row-->

<!-- Cetak Semua Jadwal Tanding Bye-->
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white download"></i><span class="break"></span>Cetak Jadwal Tanding FINAL</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
			<form class="form-horizontal" method="post" target="_blank" action="do_cetak_jadwal_final.php">
				<table>
					<tr>
						<td><input type="hidden" name="tgl" id="tgl" value=<?php echo date("Y-m-d"); ?> readonly></td>
						<td>
							<select name="gelanggang" id="gelanggang">
								<option value="">-- Pilih Gelanggang --</option>
								<option value="A">A</option>
								<option value="B">B</option>
								<option value="C">C</option>
								<option value="D">D</option>
							</select>
						</td>
						<td>
							<select name="kelas" id="kelas">
								<option value="">-- Pilih Golongan --</option>
								<option value="Usia Dini 2A">Usia Dini 2A</option>
								<option value="Usia Dini 2B">Usia Dini 2B</option>
								<option value="Pra Remaja">Pra Remaja</option>
								<option value="Remaja">Remaja</option>
								<option value="Dewasa">Dewasa</option>
							</select>
						</td>
						<td>
							<select name="jk">
								<option value="">Pilih Jenis Kelamin</option>
								<option value="Putra">Putra</option>
								<option value="Putri">Putri</option>
							</select>
						</td>
						<td>
							<select name="nama_kelas">
								<option value="">Pilih Kelas</option>
								<option value="UNDER">UNDER</option>
								<option value="Kelas A">Kelas A</option>
								<option value="Kelas B">Kelas B</option>
								<option value="Kelas C">Kelas C</option>
								<option value="Kelas D">Kelas D</option>
								<option value="Kelas E">Kelas E</option>
								<option value="Kelas F">Kelas F</option>
								<option value="Kelas G">Kelas G</option>
								<option value="Kelas H">Kelas H</option>
								<option value="Kelas I">Kelas I</option>
								<option value="Kelas J">Kelas J</option>
								<option value="Kelas K">Kelas K</option>
								<option value="Kelas L">Kelas L</option>
								<option value="Kelas M">Kelas M</option>
								<option value="Kelas N">Kelas N</option>
								<!-- Tambah sesuai kebutuhan -->
							</select>
						</td>
						<td><input type="submit" class="btn btn-info" value="Generate"></td>
					</tr>
				</table>
			</form>
		</div>
	</div><!--/span-->
</div><!--/row-->
<script>
	function validateForm() {
		let kategori = document.getElementById('kategori').value;

		if (kategori === "") {
			alert("⚠️ Silakan pilih kategori terlebih dahulu!");
			return false; // ⛔️ hentikan form submit
		}
		return true; // ✅ biarkan submit jika sudah pilih
	}
</script>
<?php
get_footer();
?>