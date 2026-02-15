<!-- Form Tunggal -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        FORMULIR PENDAFTARAN <?php echo strtoupper($kategori_tanding)." - ".strtoupper($golongan); ?>
      </div>
      <div class="card-body">
        <form name="InputPeserta" id="InputPeserta" method="POST" enctype="multipart/form-data" action="do_pendaftaran_tunggal.php" class="registration-form">
          
          <div class="form-group row mb-3">
            <label for="fotopeserta" class="col-sm-3 col-form-label form-label">Foto Peserta</label>
            <div class="col-sm-9">
              <input type="file" id="fotopeserta" name="fotopeserta" class="form-control">
              <small class="form-text text-muted">File Gambar/Foto. Max size: 500 KB</small>
            </div>
          </div>
          
          <div class="form-group row mb-3">
            <label for="nama" class="col-sm-3 col-form-label form-label">Nama Lengkap</label>
            <div class="col-sm-9">
              <input type="text" name="nama" id="nama" maxlength="35" class="form-control">
              <input type="hidden" name="kategori_tanding" id="kategori_tanding" value="<?php echo $kategori_tanding; ?>">
              <input type="hidden" name="golongan" id="golongan" value="<?php echo $golongan; ?>">
            </div>
          </div>
          
          <div class="form-group row mb-3">
            <label for="jenis_kelamin" class="col-sm-3 col-form-label form-label">Jenis Kelamin</label>
            <div class="col-sm-9">
              <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
              </select>
            </div>
          </div>
          
          <div class="form-group row mb-3">
            <label for="tpt_lahir" class="col-sm-3 col-form-label form-label">Tempat Lahir</label>
            <div class="col-sm-9">
              <input type="text" name="tpt_lahir" id="tpt_lahir" class="form-control">
            </div>
          </div>
          
          <div class="form-group row mb-3">
            <label for="tgl_lahir" class="col-sm-3 col-form-label form-label">Tanggal Lahir</label>
            <div class="col-sm-9">
              <input type="date" name="tgl_lahir" id="tgl_lahir" value="<?php echo $today; ?>" class="form-control">
              <small class="form-text text-muted">Format (YYYY-MM-DD)</small>
            </div>
          </div>
          
          <div class="form-group row mb-3">
            <label for="tb" class="col-sm-3 col-form-label form-label">Tinggi Badan</label>
            <div class="col-sm-9">
              <div class="input-group">
                <input type="number" name="tb" id="tb" class="form-control">
                <span class="input-group-text">cm</span>
              </div>
            </div>
          </div>
          
          <div class="form-group row mb-3">
            <label for="bb" class="col-sm-3 col-form-label form-label">Berat Badan</label>
            <div class="col-sm-9">
              <div class="input-group">
                <input type="number" name="bb" id="bb" class="form-control">
                <span class="input-group-text">kg</span>
              </div>
            </div>
          </div>
          
          <?php
            if($golongan == 'Dewasa') {
              echo '<input type="hidden" name="asal_sekolah" id="asal_sekolah" value="">';
              echo '<input type="hidden" name="kelas" id="kelas" value="">';
              echo '<div class="form-group row mb-3">
                      <label for="ktppeserta" class="col-sm-3 col-form-label form-label">Foto/Scan KTP</label>
                      <div class="col-sm-9">
                        <input type="file" id="ktppeserta" name="ktppeserta" class="form-control">
                        <small class="form-text text-muted">File Gambar/Foto. Max size: 500 KB</small>
                      </div>
                    </div>';
            } else {
              include "asal_sekolah_tanding.php";
            }
          ?>
          
          <div class="form-group row mb-3">
            <label for="kontingen" class="col-sm-3 col-form-label form-label">Kontingen</label>
            <div class="col-sm-9">
              <input type="text" name="kontingen" id="kontingen" class="form-control">
            </div>
          </div>
          
          <div class="form-group row">
            <div class="col-12 text-center">
              <button type="submit" name="daftar" class="btn btn-daftar">DAFTAR</button>
            </div>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</div>

<!-- jQuery and jQuery UI for autocomplete -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
  var arrAsalKontingen = <?php echo json_encode($arrAsalKontingen); ?>;
  $(document).ready(function() { 
    $("#kontingen").autocomplete({
      source: arrAsalKontingen
    });
  });
</script>