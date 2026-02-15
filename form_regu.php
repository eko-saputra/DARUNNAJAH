<h1>FORMULIR PENDAFTARAN - <?php echo "Kategori: ".$kategori_tanding." - Golongan: ".$golongan; ?></h1>

<div align="center">
	<form name="InputPeserta" id="InputPeserta" method="POST" enctype="multipart/form-data" action="do_pendaftaran_regu.php">
	<table border="0">
	<tr>
		<td>PESILAT 1</td>
		<td>PESILAT 2</td>
		<td>PESILAT 3</td>
		<input type="hidden" name="kategori_tanding" id="kategori_tanding" value="<?php echo $kategori_tanding; ?>">
		<input type="hidden" name="golongan" id="golongan" value="<?php echo $golongan; ?>">
	</tr>
	<tr>
		<td>Foto Peserta : <input type="file" id="fotopeserta" name="fotopeserta"></br>File Gambar/Foto. Max size: 500 KB</td>
		<td>Foto Peserta : <input type="file" id="fotopeserta2" name="fotopeserta2"></br>File Gambar/Foto. Max size: 500 KB</td>
		<td>Foto Peserta : <input type="file" id="fotopeserta3" name="fotopeserta3"></br>File Gambar/Foto. Max size: 500 KB</td>
	</tr>
	<tr>
		<td>Nama Lengkap : <input type="text" name="nama" id="nama" maxlength="35"></td>
		<td>Nama Lengkap : <input type="text" name="nama2" id="nama2" maxlength="35"></td>
		<td>Nama Lengkap : <input type="text" name="nama3" id="nama3" maxlength="35"></td>
	</tr>
	<tr bgcolor="#F5DEB3">
		<td colspan="3"><center>Jenis Kelamin :
			<select name="jenis_kelamin" id="jenis_kelamin">
			<option value="Laki-laki">Laki-laki</option>
			<option value="Perempuan">Perempuan</option>
			</select></center>
		</td>
	</tr>
	<tr>
		<td>Tempat Lahir : <input type="text" name="tpt_lahir" id="tpt_lahir"></td>
		<td>Tempat Lahir : <input type="text" name="tpt_lahir2" id="tpt_lahir2"></td>
		<td>Tempat Lahir : <input type="text" name="tpt_lahir3" id="tpt_lahir3"></td>
	</tr>
	<tr>
		<td>Tanggal Lahir : <input type="text" name="tgl_lahir" id="tgl_lahir" value="<?php echo $today; ?>"></br>Format (YYYY-MM-DD)</td>
		<td>Tanggal Lahir : <input type="text" name="tgl_lahir2" id="tgl_lahir2" value="<?php echo $today; ?>"></br>Format (YYYY-MM-DD)</td>
		<td>Tanggal Lahir : <input type="text" name="tgl_lahir3" id="tgl_lahir3" value="<?php echo $today; ?>"></br>Format (YYYY-MM-DD)</td>
	</tr>
	<tr>
		<td>Tinggi Badan : <input type="text" name="tb" id="tb"> cm</td>
		<td>Tinggi Badan : <input type="text" name="tb2" id="tb2"> cm</td>
		<td>Tinggi Badan : <input type="text" name="tb3" id="tb3"> cm</td>
	</tr>
	<tr>
		<td>Berat Badan : <input type="text" name="bb" id="bb"> kg</td>
		<td>Berat Badan : <input type="text" name="bb2" id="bb2"> kg</td>
		<td>Berat Badan : <input type="text" name="bb3" id="bb3"> kg</td>
	</tr>
	<?php
		if($golongan == 'Dewasa') {
			echo "<input type=hidden name=asal_sekolah id=asal_sekolah value=>";
			echo "<input type=hidden name=kelas id=kelas value=>";
			echo "<input type=hidden name=asal_sekolah2 id=asal_sekolah2 value=>";
			echo "<input type=hidden name=kelas2 id=kelas2 value=>";
			echo "<input type=hidden name=asal_sekolah3 id=asal_sekolah3 value=>";
			echo "<input type=hidden name=kelas3 id=kelas3 value=>";
			echo "<tr>
					<td>Foto/Scan KTP : <input type=file id='ktppeserta' name='ktppeserta'></br>File Gambar/Foto. Max size: 500 KB</td>
					<td>Foto/Scan KTP : <input type=file id='ktppeserta2' name='ktppeserta2'></br>File Gambar/Foto. Max size: 500 KB</td>
					<td>Foto/Scan KTP : <input type=file id='ktppeserta3' name='ktppeserta3'></br>File Gambar/Foto. Max size: 500 KB</td>
				  </tr>";
		} else {
			include "asal_sekolah_regu.php";
		}
	?>
	<tr bgcolor="#F5DEB3">
		<td colspan="3"><center>Kontingen:
			<input type="text" name="kontingen" id="kontingen">
		</center>
		</td>
	</tr>
	<tr>
		<td colspan="3"><input type="submit" name="daftar" value="DAFTAR"></td>
	</tr>
	</table>
	</form>
</div>

	<script>
      var arrAsalKontingen = <?php echo json_encode($arrAsalKontingen); ?>;
      $(document).ready(function() { 
        $("#kontingen").autocomplete({
          source: arrAsalKontingen
        });
      });
  	</script>