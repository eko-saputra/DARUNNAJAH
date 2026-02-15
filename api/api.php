<?php

include "../backend/includes/connection.php";

// REQUIRED 
// agar bisa di akses oleh android API
header('Access-Control-Allow-Origin: *');

// get ACTION 
$param = isset($_GET['a']) ? $_GET['a'] : '';

if ("" != $param) {
	switch ($param) {

		case "ceksettingan":

			if ($username !== $_GET['username']) {
				echo json_encode(['status' => 'error', 'messages' => 'Settingan username salah silahkan dicoba kembali']);

				return false;
			}

			if ($password !== $_GET['password']) {
				echo json_encode(['status' => 'error', 'messages' => 'Settingan Password salah silahkan dicoba kembali']);

				return false;
			}

			if ($nama_database !== $_GET['database']) {
				echo json_encode(['status' => 'error', 'messages' => 'Settingan Database salah silahkan dicoba kembali']);

				return false;
			}

			echo json_encode(['status' => 'success']);

			break;
		case "partai":
			echo partai();
			break;

		case "juri":
			echo juri();
			break;

		case "login":


			$id_juri = $_GET['id'];
			$password = md5($_GET['password']);

			$sql = "SELECT * FROM wasit_juri WHERE id_juri='{$id_juri}' and pass_juri='{$password}'";

			$exec = mysqli_query($koneksi, $sql);

			$row = mysqli_fetch_row($exec);

			if ($row) {
				echo json_encode(['status' => 'success']);
			} else {
				echo json_encode(['status' => 'error']);
			}
			break;
		case "jadwal":

			$id = $_GET['id_partai'];

			$sql = "SELECT * FROM jadwal_tanding WHERE id_partai='{$id}'";

			$exec = mysqli_query($koneksi, $sql);

			$row = mysqli_fetch_assoc($exec);

			if ($row)
				echo json_encode($row);
			else
				echo json_encode([]);
			break;
		case "history":

			$id_juri 	= $_GET['id_juri'];
			$id_jadwal 	= $_GET['id_jadwal'];
			$sudut 		= $_GET['sudut'];
			$babak 		= $_GET['babak'];

			// $sql = mysqli_query($koneksi, "SELECT nilai_tanding_log.*, w.nm_juri FROM nilai_tanding inner join wasit_juri w on w.id_juri=nilai_tanding.id_juri  WHERE id_jadwal='{$id_jadwal}' AND nilai_tanding.id_juri='{$id_juri}' AND sudut='{$sudut}' AND babak='{$babak}' ORDER BY id_nilai DESC");

			$sql = mysqli_query($koneksi, "SELECT * FROM nilai_tanding_log WHERE id_jadwal='{$id_jadwal}' AND id_juri='{$id_juri}' AND sudut='{$sudut}' AND babak='{$babak}'");

			$key = 0;
			$data = [];
			while ($result = mysqli_fetch_array($sql)) {
				$data[$key] = $result;
				$key++;
			}

			if ($data)
				echo json_encode($data);
			else
				echo json_encode([]);
			break;

		case "update_verifikasi":
			$id_verifikasi 	= $_POST['idverifikasi'];
			$button = $_POST['button'];

			$sql = mysqli_query($koneksi, "UPDATE verifikasi_juri SET pilihan='{$button}' WHERE id_verifikasi={$id_verifikasi}");

			echo json_encode([
				'status' => 'success',
				'message' => 'verifikasi berhasil diupdate'
			]);
			break;

		case "hapus_verifikasi":

			$sql = mysqli_query($koneksi, "DELETE FROM verifikasi_juri ORDER BY id_verifikasi DESC LIMIT 3");

			echo json_encode([
				'status' => 'success',
				'message' => 'verifikasi berhasil dihapus'
			]);
			break;

		case "history_dewan":

			$id_juri 	= $_GET['id_juri'];
			$id_jadwal 	= $_GET['id_jadwal'];
			$sudut 		= $_GET['sudut'];
			$babak 		= $_GET['babak'];
			$tombol 	= $_GET['tombol'];

			// $sql = mysqli_query($koneksi, "SELECT nilai_tanding_log.*, w.nm_juri FROM nilai_tanding inner join wasit_juri w on w.id_juri=nilai_tanding.id_juri  WHERE id_jadwal='{$id_jadwal}' AND nilai_tanding.id_juri='{$id_juri}' AND sudut='{$sudut}' AND babak='{$babak}' ORDER BY id_nilai DESC");

			$sql = mysqli_query($koneksi, "SELECT * FROM nilai_dewan WHERE id_jadwal='{$id_jadwal}' AND id_juri='{$id_juri}' AND sudut='{$sudut}' AND babak='{$babak}' AND button='{$tombol}'");

			$key = 0;
			$data = [];
			while ($result = mysqli_fetch_array($sql)) {
				$data[$key] = $result;
				$key++;
			}

			if ($data)
				echo json_encode($data);
			else
				echo json_encode([]);
			break;
		case "submit_skor":
			$id_jadwal  = $_POST['id_jadwal'];
			$id_juri    = $_POST['id_juri'];
			$button     = $_POST['button']; // jenis aksi: pukulan/tendangan
			$nilai      = $_POST['nilai'];
			$sudut      = $_POST['sudut'];
			$babak      = $_POST['babak'];

			// Set zona waktu
			date_default_timezone_set('Asia/Jakarta');
			$sekarang = date('Y-m-d H:i:s');
			$waktu_awal = date('Y-m-d H:i:s', strtotime($sekarang) - 3);

			// 1. Cek apakah juri sudah menginput nilai yang sama dalam 3 detik terakhir
			$cek_duplikat_juri = mysqli_query($koneksi, "
					SELECT id_juri,id_nilai,sudut FROM nilai_tanding_log
					WHERE id_jadwal = '$id_jadwal'
					  AND id_juri = '$id_juri'
					  AND nilai = '$nilai'
					  AND created_at BETWEEN '$waktu_awal' AND '$sekarang'
					  AND status_sah = 'pending'
					LIMIT 1
				");

			if (mysqli_num_rows($cek_duplikat_juri)) {
				// Jika sudah ada, update timestamp-nya
				// $row = mysqli_fetch_assoc($cek_duplikat_juri);
				// $id_log = $row['id_juri'];
				// $id_nilai = $row['id_nilai'];
				// $sudut = $row['sudut'];

				// mysqli_query($koneksi, "
				// 		UPDATE nilai_tanding_log
				// 		SET nilai='$nilai',created_at = '$sekarang'
				// 		WHERE id_juri = $id_log AND id_nilai=$id_nilai AND sudut=$sudut
				// 	");
			} else {
				// Jika belum ada, insert baru
				mysqli_query($koneksi, "
						INSERT INTO nilai_tanding_log 
							(id_jadwal, id_juri, button, nilai, sudut, babak, created_at, status_sah)
						VALUES
							('$id_jadwal', '$id_juri', '$button', $nilai, '$sudut', '$babak', '$sekarang', 'pending')
					");
			}

			// 2. Cek apakah ada minimal 2 juri memberi nilai & jenis aksi sama dalam 5 detik terakhir
			$cek_dua_juri = mysqli_query($koneksi, "
					SELECT nilai, button, COUNT(DISTINCT id_juri) as jumlah_juri
					FROM nilai_tanding_log
					WHERE id_jadwal = '$id_jadwal'
					  AND babak = '$babak'
					  AND sudut = '$sudut'
					  AND status_sah = 'pending'
					  AND created_at BETWEEN '$waktu_awal' AND '$sekarang'
					GROUP BY nilai, button
					HAVING jumlah_juri >= 2
					LIMIT 1
				");

			if (mysqli_num_rows($cek_dua_juri)) {
				$sah = mysqli_fetch_assoc($cek_dua_juri);
				$nilai_sah = $sah['nilai'];
				$aksi_sah  = $sah['button'];

				// sleep(5);

				// 3. Simpan ke nilai_tanding (hanya sekali untuk kombinasi sah)
				mysqli_query($koneksi, "
						INSERT INTO nilai_tanding
							(id_jadwal, nilai, button, sudut, babak, created_at)
						VALUES
							('$id_jadwal', $nilai_sah, '$aksi_sah', '$sudut', '$babak', '$sekarang')
					");

				// 4. Tandai log juri yang berkontribusi pada nilai sah
				mysqli_query($koneksi, "
						UPDATE nilai_tanding_log
						SET status_sah = 'sah'
						WHERE id_jadwal = '$id_jadwal'
						  AND babak = '$babak'
						  AND sudut = '$sudut'
						  AND button = '$aksi_sah'
						  AND nilai = $nilai_sah
						  AND created_at BETWEEN '$waktu_awal' AND '$sekarang'
					");

				echo json_encode([
					'status' => 'success',
					'message' => 'nilai sah dan disimpan',
					'nilai' => $nilai_sah,
					'aksi' => $aksi_sah
				]);
			} else {
				echo json_encode([
					'status' => 'pending',
					'message' => 'menunggu input juri lain dengan nilai dan aksi yang sama'
				]);
			}

			break;
		case "submit_skor_dewan":
			$id_jadwal  = $_POST['id_jadwal'];
			$id_juri    = $_POST['id_juri'];
			$button     = $_POST['button']; // jenis aksi: pukulan/tendangan
			$sudut      = $_POST['sudut'];
			$babak      = $_POST['babak'];

			// Set zona waktu
			date_default_timezone_set('Asia/Jakarta');
			$sekarang = date('Y-m-d H:i:s');

			// Validasi pembatasan jumlah data
			$stop = false;

			// Gantilah button dan nilai sesuai dengan kondisi yang diinginkan
			if ($button == 2) {
				$cek = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nilai_dewan WHERE id_jadwal='$id_jadwal' AND button=2 AND sudut='$sudut' AND babak='$babak'");
				$data = mysqli_fetch_assoc($cek);
				if ($data['total'] >= 2) {
					$stop = true;
				}
				$nilai      = 0;
			} elseif ($button == 3) {
				$cek = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nilai_dewan WHERE id_jadwal='$id_jadwal' AND button=3 AND sudut='$sudut' AND babak='$babak'");
				$data = mysqli_fetch_assoc($cek);
				if ($data['total'] >= 1) {
					$stop = true;
				}
				$nilai      = 1;
			} elseif ($button == 4) {
				$cek = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nilai_dewan WHERE id_jadwal='$id_jadwal' AND button=4 AND sudut='$sudut' AND babak='$babak'");
				$data = mysqli_fetch_assoc($cek);
				if ($data['total'] >= 1) {
					$stop = true;
				}
				$nilai      = 2;
			} elseif ($button == 5) {
				$cek = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nilai_dewan WHERE id_jadwal='$id_jadwal' AND button=5 AND sudut='$sudut' AND babak='$babak'");
				$data = mysqli_fetch_assoc($cek);
				if ($data['total'] >= 1) {
					$stop = true;
				}
				$nilai      = 5;
			} elseif ($button == 6) {
				$cek = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nilai_dewan WHERE id_jadwal='$id_jadwal' AND button=6 AND sudut='$sudut' AND babak='$babak'");
				$data = mysqli_fetch_assoc($cek);
				if ($data['total'] >= 1) {
					$stop = true;
				}
				$nilai      = 10;
			} elseif ($button == 7) {
				$cek = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nilai_dewan WHERE id_jadwal='$id_jadwal' AND button=7 AND sudut='$sudut' AND babak='$babak'");
				$data = mysqli_fetch_assoc($cek);
				if ($data['total'] >= 1) {
					$stop = true;
				}
				$nilai      = 0;
			} elseif ($button == 1) {
				$cek = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nilai_dewan WHERE id_jadwal='$id_jadwal' AND button=7 AND sudut='$sudut' AND babak='$babak'");
				$data = mysqli_fetch_assoc($cek);
				$nilai      = 3;
			}

			if (!$stop) {
				// Lanjut simpan jika belum melebihi batas
				mysqli_query($koneksi, "
        INSERT INTO nilai_dewan (id_jadwal, id_juri, button, nilai, sudut, babak, created_at)
        VALUES ('$id_jadwal', '$id_juri', '$button', $nilai, '$sudut', '$babak', '$sekarang')
    ");
			} else {
				// Jika perlu, tambahkan pesan error atau logika lain saat diblok
				echo "Limit nilai untuk kombinasi ini telah tercapai.";
			}

			echo json_encode([
				'status' => 'success',
			]);
			break;
		case "delete_skor":
			// nilai/delete_last.php
			$id_juri = $_POST['id_juri'];
			$id_partai = $_POST['id_partai'];
			$sudut = $_POST['sudut'];
			$babak = $_POST['babak'];

			// Ambil ID nilai terakhir
			$sql = "SELECT * FROM nilai_tanding_log 
						WHERE id_juri = '$id_juri' AND id_jadwal = '$id_partai' AND sudut = '$sudut' AND babak = '$babak'
						ORDER BY id_nilai DESC LIMIT 1";

			$result = mysqli_query($koneksi, $sql);
			$row = mysqli_fetch_assoc($result);

			if ($row) {
				$id_nilai = $row['id_nilai'];

				// Hapus skor terakhir
				$delete_sql = "DELETE FROM nilai_tanding_log WHERE id_nilai = '$id_nilai' ORDER BY id_nilai DESC LIMIT 1";
				mysqli_query($koneksi, $delete_sql);

				echo json_encode(["success" => true, "id_nilai" => $id_nilai]);
			} else {
				echo json_encode(["success" => false, "message" => "Tidak ditemukan skor terakhir."]);
			}
			break;
		case "delete_skor_dewan":
			// nilai/delete_last.php
			$id_juri = $_POST['id_juri'];
			$id_partai = $_POST['id_partai'];
			$sudut = $_POST['sudut'];
			$babak = $_POST['babak'];

			// Ambil ID nilai terakhir
			$sql = "SELECT * FROM nilai_dewan 
						WHERE id_juri = '$id_juri' AND id_jadwal = '$id_partai' AND sudut = '$sudut' AND babak = '$babak'
						ORDER BY id_nilai DESC LIMIT 1";

			$result = mysqli_query($koneksi, $sql);
			$row = mysqli_fetch_assoc($result);

			if ($row) {
				$id_nilai = $row['id_nilai'];

				// Hapus skor terakhir
				$delete_sql = "DELETE FROM nilai_dewan WHERE id_nilai = '$id_nilai' ORDER BY id_nilai DESC LIMIT 1";
				mysqli_query($koneksi, $delete_sql);

				echo json_encode(["success" => true, "id_nilai" => $id_nilai]);
			} else {
				echo json_encode(["success" => false, "message" => "Tidak ditemukan skor terakhir."]);
			}
			break;
		case "delete_nilai":
			// get id_nilai
			$id_nilai = $_GET['id_nilai'];

			$result = mysqli_query($koneksi, "DELETE FROM nilai_tanding WHERE id_nilai={$id_nilai}");

			if ($result)
				echo json_encode(['status' => 'success']);
			else
				echo json_encode(['status' => 'error']);
			break;
		case "juri_button":
			date_default_timezone_set('Asia/Jakarta');

			$sekarang     = date('Y-m-d H:i:s');
			$waktu_awal   = date('Y-m-d H:i:s', strtotime($sekarang) - 3);
			$id_partai    = $_GET['id_partai'];
			// sleep(2);
			// Ambil semua penilaian dari semua juri dalam 5 detik terakhir
			$cek_juri = mysqli_query($koneksi, "
    SELECT id_juri, sudut, nilai 
    FROM nilai_tanding_log
    WHERE id_jadwal = '$id_partai'
      AND created_at BETWEEN '$waktu_awal' AND '$sekarang'
");

			// Simpan dalam array
			$data = [];
			while ($row = mysqli_fetch_assoc($cek_juri)) {
				$data[] = $row;
			}

			// Keluarkan sebagai JSON array
			echo json_encode($data);
			break;
		case "set_babak":
			$babak = $_POST['babak'];

			// Pastikan nilai babak adalah angka untuk mencegah SQL Injection ringan
			$babak = intval($babak);

			mysqli_query($koneksi, "TRUNCATE TABLE babak");

			$simpanbabak = mysqli_query($koneksi, "INSERT INTO babak(babak) VALUES('$babak')");

			if ($simpanbabak) {
				echo json_encode(['status' => 'ok']);
			} else {
				echo json_encode(['status' => 'error', 'message' => mysqli_error($koneksi)]);
			}
			break;
		case "set_start":
			$status = $_POST['status'];

			// Pastikan nilai babak adalah angka untuk mencegah SQL Injection ringan
			$status = intval($status);

			$simpanstart = mysqli_query($koneksi, "UPDATE babak SET status='$status'");

			if ($simpanstart) {
				echo json_encode(['status' => 'ok']);
			} else {
				echo json_encode(['status' => 'error', 'message' => mysqli_error($koneksi)]);
			}
			break;
		case "get_data_view_tanding":
			get_data_view_tanding();
			break;
		case "get_status_tanding":

			// Dapatkan ID jadwal pertandingan
			$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"]);
			$babak = mysqli_real_escape_string($koneksi, $_GET["babak"]);

			mysqli_query($koneksi, "INSERT INTO statustanding (id_jadwal,babak,status) VALUES('$id_partai','$babak',1)");
			echo json_encode([
				'status' => 1,
				'babak'	=> $babak,
			]);
			break;
		case "verifikasi":

			// Dapatkan ID jadwal pertandingan dan data lainnya
			$id_partai = mysqli_real_escape_string($koneksi, $_POST["id_partai"]);
			$babak = mysqli_real_escape_string($koneksi, $_POST["babak"]);
			$jenis = mysqli_real_escape_string($koneksi, $_POST["jenis"]);

			// Ambil data juri dari tabel wasit_juri yang mengandung kata 'JURI'
			$query_juri = mysqli_query($koneksi, "SELECT id_juri FROM wasit_juri WHERE nm_juri LIKE '%JURI%'");

			// Hitung jumlah juri
			$jumlah_juri = mysqli_num_rows($query_juri);

			// Lakukan insert sebanyak jumlah juri
			while ($juri = mysqli_fetch_assoc($query_juri)) {
				$id_juri = $juri['id_juri'];

				mysqli_query($koneksi, "INSERT INTO verifikasi_juri (id_partai, id_juri, babak, jenis) VALUES ('$id_partai', '$id_juri', '$babak', '$jenis')");
			}

			// Kirim respon JSON
			echo json_encode([
				'success' => 'Berhasil kirim permintaan verifikasi',
			]);
			break;

		case "get_start":
			$cek_start = mysqli_query($koneksi, "SELECT * FROM babak");

			if (mysqli_num_rows($cek_start)) {
				// Jika sudah ada, update timestamp-nya
				$row = mysqli_fetch_assoc($cek_start);
				echo json_encode($row);
			}
			break;
		case "get_data_view_monitoring":
			get_data_view_monitoring();
			break;
	}
}

/**
 * [partai description]
 * @return [type] [description]
 */
function partai()
{
	include "../backend/includes/connection.php";
	$sql = "SELECT * FROM jadwal_tanding WHERE aktif='1' AND status='-' ORDER BY (0 + partai) ASC";

	$exec = mysqli_query($koneksi, $sql);

	$result = [];

	$key = 0;
	while ($item = mysqli_fetch_array($exec)):
		$result[$key]['id'] = $item['id_partai'];
		$result[$key]['name'] = $item['partai'];
		$result[$key]['kelas'] = $item['kelas'];
		$result[$key]['gelanggang'] = $item['gelanggang'];
		$key++;
	endwhile;

	return json_encode($result);
}

function juri()
{
	include "../backend/includes/connection.php";
	$sql = "SELECT * FROM wasit_juri";

	$exec = mysqli_query($koneksi, $sql);

	$result = [];

	$key = 0;
	while ($item = mysqli_fetch_array($exec)):
		$result[$key]['id'] = $item['id_juri'];
		$result[$key]['name'] = $item['nm_juri'];
		$key++;
	endwhile;

	return json_encode($result);
}

/**
 * [get_data_view_monitoring description]
 * @return [type] [description]
 */
function get_data_view_monitoring()
{
	include "../backend/includes/connection.php";
	//dapatkan ID jadwal pertandingan
	$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"]);

	ob_start();
?>

	<table class="table table-bordered">
		<tr class="text-center" style="font-weight: bold;" bgcolor="#e6e6e6">
			<td colspan="10"> BABAK 1</td>
		</tr>
		<tr class="text-center" class="text-center" style="font-weight: bold;">
			<td colspan="2">JURI 1</td>
			<td colspan="2" bgcolor="#e6e6e6">JURI 2</td>
			<td colspan="2">JURI 3</td>
			<td colspan="2" bgcolor="#e6e6e6">JURI 4</td>
			<td colspan="2">JURI 5</td>
		</tr>
		<tr class="text-center">
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
		</tr>
		<tr class="text-center">
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=1 AND id_juri=1 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=1 AND id_juri=1 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=1 AND id_juri=2 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=1 AND id_juri=2 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=1 AND id_juri=3 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=1 AND id_juri=3 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=1 AND id_juri=4 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=1 AND id_juri=4 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=1 AND id_juri=5 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=1 AND id_juri=5 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

		</tr>
	</table>

	<table class="table table-bordered">
		<tr class="text-center" style="font-weight: bold;" bgcolor="#e6e6e6">
			<td colspan="10"> BABAK 2</td>
		</tr>
		<tr class="text-center" style="font-weight: bold;">
			<td colspan="2">JURI 1</td>
			<td colspan="2" bgcolor="#e6e6e6">JURI 2</td>
			<td colspan="2">JURI 3</td>
			<td colspan="2" bgcolor="#e6e6e6">JURI 4</td>
			<td colspan="2">JURI 5</td>
		</tr>
		<tr class="text-center">
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
		</tr>
		<tr class="text-center">

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=2 AND id_juri=1 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=2 AND id_juri=1 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=2 AND id_juri=2 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=2 AND id_juri=2 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=2 AND id_juri=3 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=2 AND id_juri=3 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=2 AND id_juri=4 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=2 AND id_juri=4 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=2 AND id_juri=5 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=2 AND id_juri=5 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

		</tr>
	</table>

	<table class="table table-bordered">
		<tr class="text-center" style="font-weight: bold;" bgcolor="#e6e6e6">
			<td colspan="10"> BABAK 3</td>
		</tr>
		<tr class="text-center" style="font-weight: bold;">
			<td colspan="2">JURI 1</td>
			<td colspan="2" bgcolor="#e6e6e6">JURI 2</td>
			<td colspan="2">JURI 3</td>
			<td colspan="2" bgcolor="#e6e6e6">JURI 4</td>
			<td colspan="2">JURI 5</td>
		</tr>
		<tr class="text-center">
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
			<td bgcolor="#ff4d4d">M</td>
			<td bgcolor="#4d94ff">B</td>
		</tr>
		<tr class="text-center">
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=3 AND id_juri=1 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=3 AND id_juri=1 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=3 AND id_juri=2 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=3 AND id_juri=2 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=3 AND id_juri=3 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=3 AND id_juri=3 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=3 AND id_juri=4 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=3 AND id_juri=4 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>

			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=3 AND id_juri=5 AND sudut='merah' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
			<td>
				<table style="width: 100%;">
					<?php
					$sqljadwal = "SELECT id_nilai,id_jadwal,button FROM nilai_tanding WHERE id_jadwal={$id_partai} AND babak=3 AND id_juri=5 AND sudut='biru' ORDER BY id_nilai DESC";
					$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
					while ($item = mysqli_fetch_array($jadwal_tanding)):
					?>
						<tr>
							<th class="text-center"><?= $item['button'] ?></th>
						</tr>
					<?php endwhile; ?>
				</table>
			</td>
		</tr>
	</table>
<?php

	$out1 = ob_get_contents();

	ob_end_clean();

	echo $out1;
}

/**
 * [get_data_view_tanding description]
 * @return [type] [description]
 */
function get_data_view_tanding()
{
	include "../backend/includes/connection.php";

	// Dapatkan ID jadwal pertandingan
	$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"]);

	// Mencari data jadwal pertandingan
	// $sqljadwal = "SELECT * FROM jadwal_tanding WHERE id_partai='$id_partai'";
	// $jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
	// $jadwal = mysqli_fetch_array($jadwal_tanding);

	// Nilai BIRU
	$sqlnilaibiru = "SELECT COALESCE(SUM(nilai), 0) as na FROM nilai_tanding WHERE id_jadwal='$id_partai' AND sudut='BIRU'";
	$querynilaibiru = mysqli_query($koneksi, $sqlnilaibiru);
	$nilaibiru = mysqli_fetch_array($querynilaibiru);
	$nilaiakhirbiru = $nilaibiru['na'];

	// // Nilai MERAH
	$sqlnilaimerah = "SELECT COALESCE(SUM(nilai), 0) as na FROM nilai_tanding WHERE id_jadwal='$id_partai' AND sudut='MERAH'";
	$querynilaimerah = mysqli_query($koneksi, $sqlnilaimerah);
	$nilaimerah = mysqli_fetch_array($querynilaimerah);
	$nilaiakhirmerah = $nilaimerah['na'];

	// // Hukuman BIRU
	$jatuhanbiru = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'BIRU' AND button = 1 AND id_jadwal='$id_partai'");
	$binaanbiru = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'BIRU' AND button = 2 AND id_jadwal='$id_partai'");
	$teguran1biru = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'BIRU' AND button = 3 AND id_jadwal='$id_partai'");
	$teguran2biru = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'BIRU' AND button = 4 AND id_jadwal='$id_partai'");
	$peringatan1biru = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'BIRU' AND button = 5 AND id_jadwal='$id_partai'");
	$peringatan2biru = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'BIRU' AND button = 6 AND id_jadwal='$id_partai'");
	$peringatan3biru = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'BIRU' AND button = 7 AND id_jadwal='$id_partai'");

	// // // Ambil hasil sekali dan simpan untuk BIRU
	$jatuhan_biru = mysqli_fetch_assoc($jatuhanbiru)['jumlah'];
	$binaan_biru = mysqli_fetch_assoc($binaanbiru)['jumlah'];
	$teguran1_biru = mysqli_fetch_assoc($teguran1biru)['jumlah'];
	$teguran2_biru = mysqli_fetch_assoc($teguran2biru)['jumlah'];
	$peringatan1_biru = mysqli_fetch_assoc($peringatan1biru)['jumlah'];
	$peringatan2_biru = mysqli_fetch_assoc($peringatan2biru)['jumlah'];
	$peringatan3_biru = mysqli_fetch_assoc($peringatan3biru)['jumlah'];

	// // Logika untuk mengurangi nilai BIRU
	if ($teguran1_biru == 1) {
		$nilaiakhirbiru -= 1;
	}
	if ($teguran2_biru == 1) {
		$nilaiakhirbiru -= 2;
	}
	if ($peringatan1_biru == 1) {
		$nilaiakhirbiru -= 5;
	}
	if ($peringatan2_biru == 1) {
		$nilaiakhirbiru -= 10;
	}
	if ($jatuhan_biru >= 1) {
		$nilaiakhirbiru += $jatuhan_biru * 3;
	}

	// Hukuman MERAH
	$jatuhanmerah = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'MERAH' AND button = 1 AND id_jadwal='$id_partai'");
	$binaanmerah = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'MERAH' AND button = 2 AND id_jadwal='$id_partai'");
	$teguran1merah = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'MERAH' AND button = 3 AND id_jadwal='$id_partai'");
	$teguran2merah = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'MERAH' AND button = 4 AND id_jadwal='$id_partai'");
	$peringatan1merah = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'MERAH' AND button = 5 AND id_jadwal='$id_partai'");
	$peringatan2merah = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'MERAH' AND button = 6 AND id_jadwal='$id_partai'");
	$peringatan3merah = mysqli_query($koneksi, "SELECT COALESCE(COUNT(*), 0) AS jumlah FROM nilai_dewan WHERE sudut = 'MERAH' AND button = 7 AND id_jadwal='$id_partai'");

	// // Ambil hasil sekali dan simpan untuk MERAH
	$jatuhan_merah = mysqli_fetch_assoc($jatuhanmerah)['jumlah'];
	$binaan_merah = mysqli_fetch_assoc($binaanmerah)['jumlah'];
	$teguran1_merah = mysqli_fetch_assoc($teguran1merah)['jumlah'];
	$teguran2_merah = mysqli_fetch_assoc($teguran2merah)['jumlah'];
	$peringatan1_merah = mysqli_fetch_assoc($peringatan1merah)['jumlah'];
	$peringatan2_merah = mysqli_fetch_assoc($peringatan2merah)['jumlah'];
	$peringatan3_merah = mysqli_fetch_assoc($peringatan3merah)['jumlah'];

	// Logika untuk mengurangi nilai MERAH
	if ($teguran1_merah == 1) {
		$nilaiakhirmerah -= 1;
	}
	if ($teguran2_merah == 1) {
		$nilaiakhirmerah -= 2;
	}
	if ($peringatan1_merah == 1) {
		$nilaiakhirmerah -= 5;
	}
	if ($peringatan2_merah == 1) {
		$nilaiakhirmerah -= 10;
	}
	if ($jatuhan_merah >= 1) {
		$nilaiakhirmerah += $jatuhan_merah * 3;
	}

	// Kirim hasil ke frontend dalam format JSON
	header('Content-Type: application/json');
	echo json_encode([
		'biru' => $nilaiakhirbiru,
		'merah' => $nilaiakhirmerah,
		'BIRU' => [
			'jatuhan' => $jatuhan_biru,
			'binaan' => $binaan_biru,
			'teguran1' => $teguran1_biru,
			'teguran2' => $teguran2_biru,
			'peringatan1' => $peringatan1_biru,
			'peringatan2' => $peringatan2_biru,
			'peringatan3' => $peringatan3_biru,
		],
		'MERAH' => [
			'jatuhan' => $jatuhan_merah,
			'binaan' => $binaan_merah,
			'teguran1' => $teguran1_merah,
			'teguran2' => $teguran2_merah,
			'peringatan1' => $peringatan1_merah,
			'peringatan2' => $peringatan2_merah,
			'peringatan3' => $peringatan3_merah,
		]
	]);
}
?>