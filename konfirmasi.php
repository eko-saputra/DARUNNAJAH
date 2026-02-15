<?php 
	/*
	*---------------------------------------------------------------
	* E-REGISTRASI PENCAK SILAT
	*---------------------------------------------------------------
	* This program is free software; you can redistribute it and/or
	* modify it under the terms of the GNU General Public License
	* as published by the Free Software Foundation; either version 2
	* of the License, or (at your option) any later version.
	*
	* This program is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	* GNU General Public License for more details.
	*
	* You should have received a copy of the GNU General Public License
	* along with this program; if not, write to the Free Software
	* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
	*
	* @Author Yudha Yogasara
	* yudha.yogasara@gmail.com
	* @Contributor Sofyan Hadi, Satria Salam
	*
	* IPSI KABUPATEN TANGERANG
	* SALAM OLAHRAGA
	*/
	include "backend/includes/connection.php";

	//Mulai Multiple Autocomplete Cari nama peeserta
	$sql = "SELECT nm_lengkap FROM peserta ORDER BY nm_lengkap ASC ";
	$kueri = mysqli_query($koneksi, $sql);
	while($data = mysqli_fetch_array($kueri)) {
		$arrPesilatTanding[] = $data[0];
	}
	
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Konfirmasi Pembayaran - Registrasi Sirkuit Pencak Silat</title>
<meta name="description" content="Registrasi Online Kejuaraan Pencak Silat">
<meta name="keywords" content="registrasi,online,pencak,silat">
<meta name="robots" content="index,follow">
<meta name="author" content="Yudha Yogasara">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- jQuery UI for autocomplete -->
<link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="css/reset.css" rel="stylesheet" type="text/css" media="all" />
<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f8f9fa;
        background-image: url('https://i.imgur.com/jOP8Rzn.png');
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
        background-blend-mode: overlay;
    }
    #wrapper {
        background-color: rgba(255, 255, 255, 0.9);
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .section-title {
        color: #8B0000;
        border-bottom: 2px solid #8B0000;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .card {
        border: none;
        border-left: 4px solid #8B0000;
        margin-bottom: 20px;
    }
    .card-header {
        background-color: #8B0000;
        color: white;
        font-weight: bold;
    }
    .btn-submit {
        background-color: #8B0000;
        border-color: #8B0000;
        color: white;
        font-weight: bold;
        padding: 10px 30px;
        border-radius: 30px;
        transition: all 0.3s;
    }
    .btn-submit:hover {
        background-color: #6d0000;
        border-color: #6d0000;
        transform: scale(1.05);
        color: white;
    }
    .info-box {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .account-list {
        list-style-type: none;
        padding-left: 0;
    }
    .account-list li {
        padding: 10px 15px;
        margin-bottom: 10px;
        background-color: white;
        border-left: 3px solid #8B0000;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    #footer {
        padding: 20px 0;
        margin-top: 40px;
        border-top: 1px solid #dee2e6;
        text-align: center;
    }
    .form-control:focus, .form-select:focus {
        border-color: #8B0000;
        box-shadow: 0 0 0 0.25rem rgba(139, 0, 0, 0.25);
    }
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 9999 !important;
    }
    .required-field::after {
        content: "*";
        color: #8B0000;
        margin-left: 4px;
    }
    .form-label {
        font-weight: 500;
    }
    .captcha-container {
        background-color: white;
        padding: 10px;
        border-radius: 5px;
        display: inline-block;
        margin-top: 10px;
    }
</style>
</head>
<body>
<!-- jQuery JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Start Wrapper -->
<div class="container" id="wrapper">
  <?php 
	include "headmenu.php";
  ?>

  <div class="row mt-4">
    <div class="col-12">
      <h2 class="section-title">KONFIRMASI PEMBAYARAN</h2>
      <div class="card mb-4">
        <div class="card-body">
          <p>Setelah melakukan proses pendaftaran, langkah selanjutnya adalah melunasi biaya pendaftaran sejumlah peserta yang telah didaftarkan sebelumnya (Rp. XXX.XXX,-/peserta). Pembayaran dapat ditransfer melalui nomor-nomor rekening di bawah ini:</p>
          
          <div class="info-box mt-3">
            <ul class="account-list">
              <li>0808 0883 26542 - Bank ABC - A/N NAMA 1</li>
              <li>0809 7898 09981 - Bank ACC - A/N NAMA 2</li>
            </ul>
          </div>
          
          <p>Setelah melakukan transfer, selanjutnya ialah melakukan konfirmasi melalui formulir di bawah ini.</p>
          <p><strong>Catatan Penting:</strong> Peserta yang didaftarkan pada sistem ini <strong>TETAPI tidak dikonfirmasi biaya pendaftarannya</strong> secara otomatis akan dicoret dari keikutsertaan kejuaraan.</p>
          <p>Mengalami kesulitan? Akses menu <a href="bantuan.php" class="text-decoration-none">Bantuan</a>.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <h3 class="section-title">FORMULIR KONFIRMASI PEMBAYARAN</h3>
      <div class="info-box">
        <form name="konfirmasi" id="konfirmasi" method="POST" enctype="multipart/form-data" action="do_konfirmasi.php" class="row g-3">
          <div class="col-md-6">
            <label for="banktujuan" class="form-label required-field">Bank Tujuan</label>
            <select name="banktujuan" id="banktujuan" class="form-select">
              <option value="">--- Pilih Bank Tujuan ---</option>
              <option value="0808 0883 26542 - Bank ABC - A/N Satria Salam">0808 0883 26542 - Bank ABC - A/N Nama 1</option>
              <option value="0809 7898 09981 - Bank ACC - A/N Sofyan Hadi">0809 7898 09981 - Bank ACC - A/N Nama 2</option>
            </select>
          </div>
          
          <div class="col-md-6">
            <label for="bankpengirim" class="form-label required-field">Bank Pengirim</label>
            <input type="text" class="form-control" name="bankpengirim" id="bankpengirim" maxlength="100" placeholder="Misalnya: Bank BCA/ MANDIRI">
          </div>
          
          <div class="col-md-6">
            <label for="norekening" class="form-label required-field">Nomor Rekening Pengirim</label>
            <input type="text" class="form-control" name="norekening" id="norekening" maxlength="35">
          </div>
          
          <div class="col-md-6">
            <label for="nama" class="form-label required-field">Nama Pengirim</label>
            <input type="text" class="form-control" name="nama" id="nama" maxlength="35">
          </div>
          
          <div class="col-md-6">
            <label for="kontak" class="form-label required-field">Nomor HP</label>
            <input type="text" class="form-control" name="kontak" id="kontak" maxlength="35" placeholder="Contoh: 081234567890">
          </div>
          
          <div class="col-md-6">
            <label for="tgltransfer" class="form-label required-field">Tanggal Transfer</label>
            <input type="date" class="form-control" name="tgltransfer" id="tgltransfer">
          </div>
          
          <div class="col-md-6">
            <label for="jumlah" class="form-label required-field">Jumlah Transfer</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="text" class="form-control" name="jumlah" id="jumlah" maxlength="35">
            </div>
          </div>
          
          <div class="col-md-6">
            <label for="buktipembayaran" class="form-label required-field">Upload Bukti Pembayaran</label>
            <input type="file" class="form-control" id="buktipembayaran" name="buktipembayaran">
            <div class="form-text">File Gambar/Foto. Maksimal ukuran: 1 MB</div>
          </div>
          
          <div class="col-12">
            <label for="catatan" class="form-label required-field">Nama-nama Peserta yang Dibayarkan</label>
            <textarea class="form-control" name="catatan" id="catatan" rows="4" placeholder="Masukkan nama-nama peserta yang dibayarkan..."></textarea>
          </div>
          
          <div class="col-md-6">
            <label for="vercode" class="form-label required-field">Kode Verifikasi</label>
            <input name="vercode" type="text" class="form-control" id="vercode" maxlength="5">
            <div class="captcha-container mt-2">
              <img src="capcay.php" alt="Captcha" class="img-fluid">
            </div>
          </div>
          
          <div class="col-12 mt-4 text-center">
            <button type="submit" name="konfirmasi" class="btn btn-submit">KIRIM KONFIRMASI</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- start: footer -->
  <div id="footer">
    <p>Copyleft 2016 <?php echo " - ".date("Y"); ?> <a href="developer.php" class="text-decoration-none">IPSI Kota Dumai</a></p>
    <!-- end: footer -->
  </div>
  <!-- end: footer -->
</div>

<script>
$(function() {
    var availableTags = <?php echo json_encode($arrPesilatTanding); ?>;
    function split(val) {
        return val.split(/,\s*/);
    }
    function extractLast(term) {
        return split(term).pop();
    }

    $("#catatan")
        // don't navigate away from the field on tab when selecting an item
        .on("keydown", function(event) {
            if (event.keyCode === $.ui.keyCode.TAB &&
                    $(this).autocomplete("instance").menu.active) {
                event.preventDefault();
            }
        })
        .autocomplete({
            minLength: 0,
            source: function(request, response) {
                // delegate back to autocomplete, but extract the last term
                response($.ui.autocomplete.filter(
                    availableTags, extractLast(request.term)));
            },
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            select: function(event, ui) {
                var terms = split(this.value);
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push(ui.item.value);
                // add placeholder to get the comma-and-space at the end
                terms.push("");
                this.value = terms.join(", ");
                return false;
            }
        });
});
</script>
</body>
</html>