<div class="row">
    <div class="col-12 text-light">
        <div class="card">
            <div class="card-body">
                <?php
                require_once("functions/function.php");
                include 'includes/connection.php';

                // SweetAlert2 CSS & JS
                echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
                      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

                //query data pemenang semifinal
                $sqlwinner = "SELECT * FROM jadwal_tanding WHERE babak='SEMIFINAL'";
                $datawinner = mysqli_query($koneksi, "$sqlwinner");

                //query data pemenang final
                $sqlwinnerfinal = "SELECT * FROM jadwal_tanding WHERE babak='FINAL'";
                $datawinnerfinal = mysqli_query($koneksi, "$sqlwinnerfinal");

                //query data perolehan medali
                $sqlmedali = "SELECT * FROM medali ORDER BY kontingen ASC";
                $datamedali = mysqli_query($koneksi, "$sqlmedali");

                //query data koleksi medali
                $sqlkoleksimedali = "SELECT DISTINCT `kontingen` FROM medali";
                $datakoleksi = mysqli_query($koneksi, "$sqlkoleksimedali");
                ?>

                <!-- Monitoring Partai SEMIFINAL -->
                <div class="row-fluid sortable">
                    <div class="box span12">
                        <div class="box-header" data-original-title>
                            <h6 class="rounded p-2 bg-dark border border-1 border-muted text-primary mb-3">
                                <i class="halflings-icon white medal"></i><span class="break"></span>Monitoring Partai SEMIFINAL
                            </h6>
                        </div>

                        <div class="box-content">
                            <div class="table-responsive">
                                <table class="table table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th width="5%">Partai</th>
                                            <th>Gel.</th>
                                            <th>Babak</th>
                                            <th>Kelompok</th>
                                            <th class="bg-primary text-light">Sudut Biru</th>
                                            <th class="bg-danger text-light">Sudut Merah</th>
                                            <th>Status</th>
                                            <th>Pemenang</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        if (mysqli_num_rows($datawinner) > 0) {
                                            while ($winner = mysqli_fetch_array($datawinner)) {
                                                $no++;

                                                // Cek apakah sudah ada medali untuk partai ini
                                                $id_partai = $winner['id_partai'];
                                                $sqlMedaliExist = "SELECT * FROM medali WHERE id_partai_FK = '$id_partai' AND medali = 'Perunggu'";
                                                $medaliExist = mysqli_query($koneksi, $sqlMedaliExist);
                                                $medaliData = mysqli_fetch_assoc($medaliExist);

                                                $medaliDiberikan = false;
                                                $namaPenerima = '';
                                                $kontingenPenerima = '';
                                                $sidePenerima = '';

                                                if ($medaliData) {
                                                    $medaliDiberikan = true;
                                                    $namaPenerima = $medaliData['nama'];
                                                    $kontingenPenerima = $medaliData['kontingen'];
                                                    $sidePenerima = ($namaPenerima == $winner['nm_merah']) ? 'merah' : 'biru';
                                                }
                                        ?>
                                                <tr>
                                                    <td class="text-uppercase text-center"><?php echo htmlspecialchars($winner['partai']); ?></td>
                                                    <td class="text-uppercase"><?php echo htmlspecialchars($winner['gelanggang']); ?></td>
                                                    <td class="text-uppercase"><?php echo htmlspecialchars($winner['babak']); ?></td>
                                                    <td class="text-uppercase"><?php echo htmlspecialchars($winner['kelas']); ?></td>
                                                    <td class="text-uppercase">
                                                        <div class="fw-bold <?php echo ($sidePenerima == 'biru') ? 'text-warning' : ''; ?>">
                                                            <?php echo htmlspecialchars($winner['nm_biru']); ?>
                                                            ( <small><?php echo htmlspecialchars($winner['kontingen_biru']); ?></small> )<br><br>
                                                            <?php if ($medaliDiberikan && $sidePenerima == 'biru'): ?>
                                                                <span class="badge bg-danger ms-1">🏅 Perunggu</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td class="text-uppercase">
                                                        <div class="fw-bold <?php echo ($sidePenerima == 'merah') ? 'text-warning' : ''; ?>">
                                                            <?php echo htmlspecialchars($winner['nm_merah']); ?>
                                                            ( <small><?php echo htmlspecialchars($winner['kontingen_merah']); ?></small> )<br><br>
                                                            <?php if ($medaliDiberikan && $sidePenerima == 'merah'): ?>
                                                                <span class="badge bg-danger ms-1">🏅 Perunggu</span>
                                                            <?php endif; ?>
                                                        </div>

                                                    </td>
                                                    <td class="text-center text-uppercase">
                                                        <span class="badge bg-<?php echo $winner['status'] ? 'success' : 'secondary'; ?>">
                                                            <?php echo $winner['status'] ? htmlspecialchars($winner['status']) : '-'; ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center text-uppercase">
                                                        <span class="badge bg-info text-dark"><?php echo $winner['pemenang'] ? htmlspecialchars($winner['pemenang']) : '-'; ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="">
                                                            <?php if (!$medaliDiberikan): ?>
                                                                <!-- Tombol untuk memberikan medali pertama kali -->
                                                                <button type="button" class="btn btn-sm btn-bronze btn-give-medal"
                                                                    data-id="<?php echo $winner['id_partai']; ?>"
                                                                    data-name="<?php echo htmlspecialchars($winner['nm_merah']); ?>"
                                                                    data-cont="<?php echo htmlspecialchars($winner['kontingen_merah']); ?>"
                                                                    data-kelas="<?php echo htmlspecialchars($winner['kelas']); ?>"
                                                                    data-medali="Perunggu"
                                                                    data-side="merah">
                                                                    <i class="halflings-icon white gift"></i> Beri ke Merah
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-bronze btn-give-medal"
                                                                    data-id="<?php echo $winner['id_partai']; ?>"
                                                                    data-name="<?php echo htmlspecialchars($winner['nm_biru']); ?>"
                                                                    data-cont="<?php echo htmlspecialchars($winner['kontingen_biru']); ?>"
                                                                    data-kelas="<?php echo htmlspecialchars($winner['kelas']); ?>"
                                                                    data-medali="Perunggu"
                                                                    data-side="biru">
                                                                    <i class="halflings-icon white gift"></i> Beri ke Biru
                                                                </button>
                                                            <?php else: ?>
                                                                <!-- Tombol untuk mengganti/roker medali -->
                                                                <div class="text-center mb-2">
                                                                    <span class="badge bg-info text-dark">Medali sudah diberikan</span>
                                                                </div>
                                                                <?php if ($sidePenerima == 'merah'): ?>
                                                                    <button type="button" class="btn btn-sm btn-warning btn-swap-medal"
                                                                        data-id="<?php echo $winner['id_partai']; ?>"
                                                                        data-from-name="<?php echo htmlspecialchars($winner['nm_merah']); ?>"
                                                                        data-from-cont="<?php echo htmlspecialchars($winner['kontingen_merah']); ?>"
                                                                        data-to-name="<?php echo htmlspecialchars($winner['nm_biru']); ?>"
                                                                        data-to-cont="<?php echo htmlspecialchars($winner['kontingen_biru']); ?>"
                                                                        data-kelas="<?php echo htmlspecialchars($winner['kelas']); ?>"
                                                                        data-medali="Perunggu"
                                                                        data-from-side="merah"
                                                                        data-to-side="biru"
                                                                        data-medali-id="<?php echo $medaliData['id_medali']; ?>">
                                                                        <i class="halflings-icon white refresh"></i> Pindah ke Biru
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-danger btn-remove-medal"
                                                                        data-id="<?php echo $winner['id_partai']; ?>"
                                                                        data-name="<?php echo htmlspecialchars($winner['nm_merah']); ?>"
                                                                        data-medali-id="<?php echo $medaliData['id_medali']; ?>">
                                                                        <i class="halflings-icon white trash"></i> Hapus Medali
                                                                    </button>
                                                                <?php else: ?>
                                                                    <button type="button" class="btn btn-sm btn-warning btn-swap-medal"
                                                                        data-id="<?php echo $winner['id_partai']; ?>"
                                                                        data-from-name="<?php echo htmlspecialchars($winner['nm_biru']); ?>"
                                                                        data-from-cont="<?php echo htmlspecialchars($winner['kontingen_biru']); ?>"
                                                                        data-to-name="<?php echo htmlspecialchars($winner['nm_merah']); ?>"
                                                                        data-to-cont="<?php echo htmlspecialchars($winner['kontingen_merah']); ?>"
                                                                        data-kelas="<?php echo htmlspecialchars($winner['kelas']); ?>"
                                                                        data-medali="Perunggu"
                                                                        data-from-side="biru"
                                                                        data-to-side="merah"
                                                                        data-medali-id="<?php echo $medaliData['id_medali']; ?>">
                                                                        <i class="halflings-icon white refresh"></i> Pindah ke Merah
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-danger btn-remove-medal"
                                                                        data-id="<?php echo $winner['id_partai']; ?>"
                                                                        data-name="<?php echo htmlspecialchars($winner['nm_biru']); ?>"
                                                                        data-medali-id="<?php echo $medaliData['id_medali']; ?>">
                                                                        <i class="halflings-icon white trash"></i> Hapus Medali
                                                                    </button>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monitoring Partai FINAL -->
                <div class="row-fluid sortable mt-5">
                    <div class="box span12">
                        <div class="box-header" data-original-title>
                            <h6 class="rounded p-2 bg-dark border border-1 border-muted text-primary my-3">
                                <i class="halflings-icon white trophy"></i><span class="break"></span>Monitoring Partai FINAL
                            </h6>
                        </div>

                        <div class="box-content">
                            <div class="table-responsive">
                                <table class="table table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th width="5%">Partai</th>
                                            <th>Gel.</th>
                                            <th>Babak</th>
                                            <th>Kelompok</th>
                                            <th class="bg-primary text-light">Sudut Biru</th>
                                            <th class="bg-danger text-light">Sudut Merah</th>
                                            <th>Pemenang</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        if (mysqli_num_rows($datawinnerfinal) > 0) {
                                            while ($winnerfinal = mysqli_fetch_array($datawinnerfinal)) {
                                                $no++;

                                                // Cek medali untuk partai final
                                                $id_partai_final = $winnerfinal['id_partai'];
                                                $sqlMedaliFinal = "SELECT * FROM medali WHERE id_partai_FK = '$id_partai_final'";
                                                $medaliFinalQuery = mysqli_query($koneksi, $sqlMedaliFinal);

                                                $medaliEmas = null;
                                                $medaliPerak = null;

                                                while ($medaliRow = mysqli_fetch_assoc($medaliFinalQuery)) {
                                                    if ($medaliRow['medali'] == 'Emas') {
                                                        $medaliEmas = $medaliRow;
                                                    } else if ($medaliRow['medali'] == 'Perak') {
                                                        $medaliPerak = $medaliRow;
                                                    }
                                                }

                                                $merahEmas = ($medaliEmas && $medaliEmas['nama'] == $winnerfinal['nm_merah']);
                                                $biruEmas = ($medaliEmas && $medaliEmas['nama'] == $winnerfinal['nm_biru']);
                                                $merahPerak = ($medaliPerak && $medaliPerak['nama'] == $winnerfinal['nm_merah']);
                                                $biruPerak = ($medaliPerak && $medaliPerak['nama'] == $winnerfinal['nm_biru']);
                                        ?>
                                                <tr>
                                                    <td class="text-uppercase text-center"><?php echo htmlspecialchars($winnerfinal['partai']); ?></td>
                                                    <td class="text-uppercase"><?php echo htmlspecialchars($winnerfinal['gelanggang']); ?></td>
                                                    <td class="text-uppercase"><?php echo htmlspecialchars($winnerfinal['babak']); ?></td>
                                                    <td class="text-uppercase"><?php echo htmlspecialchars($winnerfinal['kelas']); ?></td>
                                                    <td class="text-uppercase">
                                                        <div class="fw-bold">
                                                            <?php echo htmlspecialchars($winnerfinal['nm_biru']); ?>
                                                            <small class="text-muted"><?php echo htmlspecialchars($winnerfinal['kontingen_biru']); ?></small><br>
                                                            <br>
                                                            <?php if ($biruEmas): ?>
                                                                <span class="badge bg-warning text-dark ms-1">🥇 Emas</span>
                                                            <?php endif; ?>
                                                            <?php if ($biruPerak): ?>
                                                                <span class="badge bg-secondary ms-1">🥈 Perak</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td class="text-uppercase">
                                                        <div class="fw-bold">
                                                            <?php echo htmlspecialchars($winnerfinal['nm_merah']); ?>
                                                            <small class="text-muted"><?php echo htmlspecialchars($winnerfinal['kontingen_merah']); ?></small><br>
                                                            <br>
                                                            <?php if ($merahEmas): ?>
                                                                <span class="badge bg-warning ms-1 text-dark">🥇 Emas</span>
                                                            <?php endif; ?>
                                                            <?php if ($merahPerak): ?>
                                                                <span class="badge bg-secondary ms-1">🥈 Perak</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td class="text-center text-uppercase">
                                                        <span class="badge bg-info text-dark"><?php echo $winnerfinal['pemenang'] ? htmlspecialchars($winnerfinal['pemenang']) : '-'; ?></span>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <!-- Tombol untuk Emas -->
                                                            <?php if (!$medaliEmas): ?>
                                                                <div class="btn-group btn-group-sm" role="group">
                                                                    <button type="button" class="btn btn-gold btn-give-medal"
                                                                        data-id="<?php echo $winnerfinal['id_partai']; ?>"
                                                                        data-name="<?php echo htmlspecialchars($winnerfinal['nm_merah']); ?>"
                                                                        data-cont="<?php echo htmlspecialchars($winnerfinal['kontingen_merah']); ?>"
                                                                        data-kelas="<?php echo htmlspecialchars($winnerfinal['kelas']); ?>"
                                                                        data-medali="Emas"
                                                                        data-side="merah">
                                                                        🥇 Merah
                                                                    </button>
                                                                    <button type="button" class="btn btn-gold btn-give-medal"
                                                                        data-id="<?php echo $winnerfinal['id_partai']; ?>"
                                                                        data-name="<?php echo htmlspecialchars($winnerfinal['nm_biru']); ?>"
                                                                        data-cont="<?php echo htmlspecialchars($winnerfinal['kontingen_biru']); ?>"
                                                                        data-kelas="<?php echo htmlspecialchars($winnerfinal['kelas']); ?>"
                                                                        data-medali="Emas"
                                                                        data-side="biru">
                                                                        🥇 Biru
                                                                    </button>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="text-center mb-1">
                                                                    <?php if ($medaliEmas['nama'] == $winnerfinal['nm_merah']): ?>
                                                                        <button type="button" class="btn btn-sm btn-warning btn-swap-medal"
                                                                            data-id="<?php echo $winnerfinal['id_partai']; ?>"
                                                                            data-from-name="<?php echo htmlspecialchars($winnerfinal['nm_merah']); ?>"
                                                                            data-from-cont="<?php echo htmlspecialchars($winnerfinal['kontingen_merah']); ?>"
                                                                            data-to-name="<?php echo htmlspecialchars($winnerfinal['nm_biru']); ?>"
                                                                            data-to-cont="<?php echo htmlspecialchars($winnerfinal['kontingen_biru']); ?>"
                                                                            data-kelas="<?php echo htmlspecialchars($winnerfinal['kelas']); ?>"
                                                                            data-medali="Emas"
                                                                            data-from-side="merah"
                                                                            data-to-side="biru"
                                                                            data-medali-id="<?php echo $medaliEmas['id_medali']; ?>">
                                                                            <i class="halflings-icon white refresh"></i> Emas ke Biru
                                                                        </button>
                                                                    <?php else: ?>
                                                                        <button type="button" class="btn btn-sm btn-warning btn-swap-medal"
                                                                            data-id="<?php echo $winnerfinal['id_partai']; ?>"
                                                                            data-from-name="<?php echo htmlspecialchars($winnerfinal['nm_biru']); ?>"
                                                                            data-from-cont="<?php echo htmlspecialchars($winnerfinal['kontingen_biru']); ?>"
                                                                            data-to-name="<?php echo htmlspecialchars($winnerfinal['nm_merah']); ?>"
                                                                            data-to-cont="<?php echo htmlspecialchars($winnerfinal['kontingen_merah']); ?>"
                                                                            data-kelas="<?php echo htmlspecialchars($winnerfinal['kelas']); ?>"
                                                                            data-medali="Emas"
                                                                            data-from-side="biru"
                                                                            data-to-side="merah"
                                                                            data-medali-id="<?php echo $medaliEmas['id_medali']; ?>">
                                                                            <i class="halflings-icon white refresh"></i> Emas ke Merah
                                                                        </button>
                                                                    <?php endif; ?>
                                                                    <button type="button" class="btn btn-sm btn-danger btn-remove-medal"
                                                                        data-id="<?php echo $winnerfinal['id_partai']; ?>"
                                                                        data-name="<?php echo htmlspecialchars($medaliEmas['nama']); ?>"
                                                                        data-medali-id="<?php echo $medaliEmas['id_medali']; ?>">
                                                                        <i class="halflings-icon white trash"></i> Hapus
                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>

                                                            <!-- Tombol untuk Perak -->
                                                            <?php if (!$medaliPerak): ?>
                                                                <div class="btn-group btn-group-sm" role="group">
                                                                    <button type="button" class="btn btn-silver btn-give-medal"
                                                                        data-id="<?php echo $winnerfinal['id_partai']; ?>"
                                                                        data-name="<?php echo htmlspecialchars($winnerfinal['nm_merah']); ?>"
                                                                        data-cont="<?php echo htmlspecialchars($winnerfinal['kontingen_merah']); ?>"
                                                                        data-kelas="<?php echo htmlspecialchars($winnerfinal['kelas']); ?>"
                                                                        data-medali="Perak"
                                                                        data-side="merah">
                                                                        🥈 Merah
                                                                    </button>
                                                                    <button type="button" class="btn btn-silver btn-give-medal"
                                                                        data-id="<?php echo $winnerfinal['id_partai']; ?>"
                                                                        data-name="<?php echo htmlspecialchars($winnerfinal['nm_biru']); ?>"
                                                                        data-cont="<?php echo htmlspecialchars($winnerfinal['kontingen_biru']); ?>"
                                                                        data-kelas="<?php echo htmlspecialchars($winnerfinal['kelas']); ?>"
                                                                        data-medali="Perak"
                                                                        data-side="biru">
                                                                        🥈 Biru
                                                                    </button>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="text-center mt-1">
                                                                    <?php if ($medaliPerak['nama'] == $winnerfinal['nm_merah']): ?>
                                                                        <button type="button" class="btn btn-sm btn-warning btn-swap-medal"
                                                                            data-id="<?php echo $winnerfinal['id_partai']; ?>"
                                                                            data-from-name="<?php echo htmlspecialchars($winnerfinal['nm_merah']); ?>"
                                                                            data-from-cont="<?php echo htmlspecialchars($winnerfinal['kontingen_merah']); ?>"
                                                                            data-to-name="<?php echo htmlspecialchars($winnerfinal['nm_biru']); ?>"
                                                                            data-to-cont="<?php echo htmlspecialchars($winnerfinal['kontingen_biru']); ?>"
                                                                            data-kelas="<?php echo htmlspecialchars($winnerfinal['kelas']); ?>"
                                                                            data-medali="Perak"
                                                                            data-from-side="merah"
                                                                            data-to-side="biru"
                                                                            data-medali-id="<?php echo $medaliPerak['id_medali']; ?>">
                                                                            <i class="halflings-icon white refresh"></i> Perak ke Biru
                                                                        </button>
                                                                    <?php else: ?>
                                                                        <button type="button" class="btn btn-sm btn-warning btn-swap-medal"
                                                                            data-id="<?php echo $winnerfinal['id_partai']; ?>"
                                                                            data-from-name="<?php echo htmlspecialchars($winnerfinal['nm_biru']); ?>"
                                                                            data-from-cont="<?php echo htmlspecialchars($winnerfinal['kontingen_biru']); ?>"
                                                                            data-to-name="<?php echo htmlspecialchars($winnerfinal['nm_merah']); ?>"
                                                                            data-to-cont="<?php echo htmlspecialchars($winnerfinal['kontingen_merah']); ?>"
                                                                            data-kelas="<?php echo htmlspecialchars($winnerfinal['kelas']); ?>"
                                                                            data-medali="Perak"
                                                                            data-from-side="biru"
                                                                            data-to-side="merah"
                                                                            data-medali-id="<?php echo $medaliPerak['id_medali']; ?>">
                                                                            <i class="halflings-icon white refresh"></i> Perak ke Merah
                                                                        </button>
                                                                    <?php endif; ?>
                                                                    <button type="button" class="btn btn-sm btn-danger btn-remove-medal"
                                                                        data-id="<?php echo $winnerfinal['id_partai']; ?>"
                                                                        data-name="<?php echo htmlspecialchars($medaliPerak['nama']); ?>"
                                                                        data-medali-id="<?php echo $medaliPerak['id_medali']; ?>">
                                                                        <i class="halflings-icon white trash"></i> Hapus
                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Perolehan Medali Perorangan -->
                <div class="row-fluid sortable">
                    <div class="box span12">
                        <div class="box-header" data-original-title>
                            <h6 class="rounded p-2 bg-dark border border-1 border-muted text-primary my-3">
                                <i class="halflings-icon white user"></i><span class="break"></span>Perolehan Medali (Kelas Tanding) Perorangan
                            </h6>
                            <!-- <div class="float-end"> -->
                            <a href="medali_rekap_pesilat.php" class="btn btn-warning btn-sm" role="button">
                                <i class="halflings-icon white download"></i> Download Data
                            </a>
                            <!-- </div> -->
                        </div>

                        <div class="box-content">
                            <div class="table-responsive">
                                <table class="table table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Nama</th>
                                            <th>Kontingen</th>
                                            <th>Kelas</th>
                                            <th>Medali</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        if (mysqli_num_rows($datamedali) > 0) {
                                            while ($medali = mysqli_fetch_array($datamedali)) {
                                                $no++;
                                                $badgeClass = '';

                                                // Perbaikan 1: untuk strtolower()
                                                $medaliType = strtolower($medali['medali'] ?? '');

                                                switch ($medaliType) {
                                                    case 'emas':
                                                        $badgeClass = 'bg-warning text-dark';
                                                        break;
                                                    case 'perak':
                                                        $badgeClass = 'bg-secondary';
                                                        break;
                                                    case 'perunggu':
                                                        $badgeClass = 'bg-danger';
                                                        break;
                                                    default:
                                                        $badgeClass = 'bg-info text-dark';
                                                }
                                        ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $no; ?></td>
                                                    <!-- Perbaikan 2: untuk htmlspecialchars() -->
                                                    <td class="text-uppercase"><?php echo htmlspecialchars($medali['nama'] ?? ''); ?></td>
                                                    <td class="text-uppercase"><?php echo htmlspecialchars($medali['kontingen'] ?? ''); ?></td>
                                                    <td class="text-uppercase"><?php echo htmlspecialchars($medali['kelas'] ?? ''); ?></td>
                                                    <td>
                                                        <span class="badge <?php echo $badgeClass; ?>">
                                                            <!-- Perbaikan 3: untuk medali -->
                                                            <?php echo htmlspecialchars($medali['medali'] ?? 'Tidak ada'); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm btn-delete-medali"
                                                            data-id="<?php echo $medali['id_medali']; ?>"
                                                            data-name="<?php echo htmlspecialchars($medali['nama'] ?? ''); ?>"
                                                            data-medali="<?php echo htmlspecialchars($medali['medali'] ?? ''); ?>"
                                                            data-partai="<?php echo $medali['id_partai_FK']; ?>">
                                                            <i class="halflings-icon white trash"></i> Hapus
                                                        </button>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Perolehan Medali Kontingen -->
                <div class="row-fluid sortable">
                    <div class="box span12">
                        <div class="box-header" data-original-title>
                            <h6 class="rounded p-2 bg-dark border border-1 border-muted text-primary my-3">
                                <i class="halflings-icon white flag"></i><span class="break"></span>Perolehan Medali (Kelas Tanding) Kontingen
                            </h6>
                            <!-- <div class="float-end"> -->
                            <a href="medali_rekap_kontingen.php" class="btn btn-warning btn-sm" role="button">
                                <i class="halflings-icon white download"></i> Download Data
                            </a>
                            <!-- </div> -->
                        </div>

                        <div class="box-content">
                            <div class="table-responsive">
                                <table class="table table-bordered bootstrap-datatable datatable">
                                    <thead>
                                        <tr>
                                            <th>KONTINGEN</th>
                                            <th class="text-center">EMAS</th>
                                            <th class="text-center">PERAK</th>
                                            <th class="text-center">PERUNGGU</th>
                                            <th class="text-center">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $totalEmas = $totalPerak = $totalPerunggu = 0;
                                        if (mysqli_num_rows($datakoleksi) > 0) {
                                            while ($koleksimedali = mysqli_fetch_array($datakoleksi)) {
                                                // Hitung medali per kontingen
                                                $kontingen = mysqli_real_escape_string($koneksi, $koleksimedali['kontingen']);

                                                $sqlcountemas = mysqli_query($koneksi, "SELECT COUNT(*) FROM medali WHERE kontingen='$kontingen' AND medali='emas'");
                                                $countemas = mysqli_fetch_array($sqlcountemas)[0];

                                                $sqlcountperak = mysqli_query($koneksi, "SELECT COUNT(*) FROM medali WHERE kontingen='$kontingen' AND medali='perak'");
                                                $countperak = mysqli_fetch_array($sqlcountperak)[0];

                                                $sqlcountperunggu = mysqli_query($koneksi, "SELECT COUNT(*) FROM medali WHERE kontingen='$kontingen' AND medali='perunggu'");
                                                $countperunggu = mysqli_fetch_array($sqlcountperunggu)[0];

                                                $total = $countemas + $countperak + $countperunggu;

                                                $totalEmas += $countemas;
                                                $totalPerak += $countperak;
                                                $totalPerunggu += $countperunggu;
                                        ?>
                                                <tr>
                                                    <td class="text-uppercase fw-bold"><?php echo htmlspecialchars($koleksimedali['kontingen']); ?></td>
                                                    <td class="text-center">
                                                        <span class="badge bg-warning text-dark" style="min-width: 30px;"><?php echo $countemas; ?></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-secondary" style="min-width: 30px;"><?php echo $countperak; ?></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-danger" style="min-width: 30px;"><?php echo $countperunggu; ?></span>
                                                    </td>
                                                    <td class="text-center fw-bold">
                                                        <span class="badge bg-info text-dark" style="min-width: 30px;"><?php echo $total; ?></span>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                    <tr>
                                        <td class="fw-bold">TOTAL</td>
                                        <td class="text-center fw-bold">
                                            <span class="badge bg-warning text-dark"><?php echo $totalEmas; ?></span>
                                        </td>
                                        <td class="text-center fw-bold">
                                            <span class="badge bg-secondary"><?php echo $totalPerak; ?></span>
                                        </td>
                                        <td class="text-center fw-bold">
                                            <span class="badge bg-danger"><?php echo $totalPerunggu; ?></span>
                                        </td>
                                        <td class="text-center fw-bold">
                                            <span class="badge bg-info text-dark"><?php echo $totalEmas + $totalPerak + $totalPerunggu; ?></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Form for Medal Submission -->
<form id="medalForm" method="POST" action="pages/proses/admin_medali.php" style="display: none;">
    <input type="hidden" name="nama" id="medalName">
    <input type="hidden" name="kontingen" id="medalCont">
    <input type="hidden" name="kelas" id="medalKelas">
    <input type="hidden" name="medali" id="medalType">
    <input type="hidden" name="idjadwal" id="medalIdJadwal">
</form>

<script src="js/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.datatable').DataTable();
    });
    document.addEventListener('DOMContentLoaded', function() {
        // ============ TOMBOL BERI MEDALI BARU (GIVE) ============
        const giveButtons = document.querySelectorAll('.btn-give-medal');
        giveButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const cont = this.getAttribute('data-cont');
                const kelas = this.getAttribute('data-kelas');
                const medali = this.getAttribute('data-medali');
                const side = this.getAttribute('data-side');

                Swal.fire({
                    html: `Berikan medali <strong class="text-warning">${medali}</strong> kepada:<br>
                       <div class="text-start mt-2">
                           <strong>${name}</strong><br>
                           Kontingen: <strong>${cont}</strong><br>
                           Kelas: <strong>${kelas}</strong><br>
                           Sudut: <strong class="text-${side === 'merah' ? 'danger' : 'primary'}">${side.toUpperCase()}</strong>
                       </div>`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Berikan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitMedal(id, name, cont, kelas, medali);
                    }
                });
            });
        });

        // ============ TOMBOL PINDAH MEDALI (SWAP) ============
        const swapButtons = document.querySelectorAll('.btn-swap-medal');
        swapButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const fromName = this.getAttribute('data-from-name');
                const fromCont = this.getAttribute('data-from-cont');
                const toName = this.getAttribute('data-to-name');
                const toCont = this.getAttribute('data-to-cont');
                const kelas = this.getAttribute('data-kelas');
                const medali = this.getAttribute('data-medali');
                const fromSide = this.getAttribute('data-from-side');
                const toSide = this.getAttribute('data-to-side');
                const medaliId = this.getAttribute('data-medali-id');

                Swal.fire({
                    html: `Pindahkan medali <strong class="text-warning">${medali}</strong>:<br>
                       <div class="text-start mt-2">
                           <div class="mb-2">
                               <span class="text-danger">Dari:</span><br>
                               <strong>${fromName}</strong><br>
                               (${fromCont}) - Sudut ${fromSide.toUpperCase()}
                           </div>
                           <div>
                               <span class="text-success">Ke:</span><br>
                               <strong>${toName}</strong><br>
                               (${toCont}) - Sudut ${toSide.toUpperCase()}
                           </div>
                       </div>`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Pindahkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Hapus medali lama, beri yang baru
                        Swal.fire({
                            title: 'Memproses...',
                            html: 'Sedang memindahkan medali...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                                // Hapus medali lama dulu
                                fetch(`admin_del_medali.php?id_medali=${medaliId}`, {
                                    method: 'GET'
                                }).then(response => {
                                    if (response.ok) {
                                        // Setelah berhasil dihapus, tambah yang baru
                                        submitMedal(id, toName, toCont, kelas, medali);
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            text: 'Gagal memindahkan medali'
                                        });
                                    }
                                }).catch(error => {
                                    Swal.fire({
                                        icon: 'error',
                                        text: 'Terjadi kesalahan saat memindahkan medali'
                                    });
                                });
                            }
                        });
                    }
                });
            });
        });

        // ============ TOMBOL HAPUS MEDALI (REMOVE) ============
        const removeButtons = document.querySelectorAll('.btn-remove-medal');
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const medaliId = this.getAttribute('data-medali-id');

                Swal.fire({
                    html: `Apakah Anda yakin menghapus medali dari:<br>
                       <strong>${name}</strong>?`,
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `pages/proses/admin_del_medali.php?id_medali=${medaliId}`;
                    }
                });
            });
        });

        // ============ TOMBOL HAPUS DARI TABEL MEDALI ============
        const deleteMedaliButtons = document.querySelectorAll('.btn-delete-medali');
        deleteMedaliButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const medali = this.getAttribute('data-medali');
                const partai = this.getAttribute('data-partai');

                Swal.fire({
                    html: `Apakah Anda yakin menghapus medali <strong>${medali}</strong><br>
                       dari <strong>${name}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `pages/proses/admin_del_medali.php?id_medali=${id}&id_partai_FK=${partai}`;
                    }
                });
            });
        });

        // ============ FUNGSI BANTU ============
        function submitMedal(id, name, cont, kelas, medali) {
            Swal.fire({
                title: 'Memproses...',
                html: 'Sedang menambahkan medali...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();

                    // Buat form dinamis
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'pages/proses/admin_medali.php';
                    form.style.display = 'none';

                    // Tambahkan input fields
                    const inputs = [{
                            name: 'nama',
                            value: name
                        },
                        {
                            name: 'kontingen',
                            value: cont
                        },
                        {
                            name: 'kelas',
                            value: kelas
                        },
                        {
                            name: 'medali',
                            value: medali
                        },
                        {
                            name: 'idjadwal',
                            value: id
                        }
                    ];

                    inputs.forEach(input => {
                        const inputField = document.createElement('input');
                        inputField.type = 'hidden';
                        inputField.name = input.name;
                        inputField.value = input.value;
                        form.appendChild(inputField);
                    });

                    // Submit form
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    });

    // Hapus event listener lama untuk menghindari konflik
    document.querySelectorAll('.btn-medali').forEach(button => {
        button.removeEventListener('click', null);
    });
</script>

<!-- Styling untuk tombol medali -->
<style>
    .btn-bronze {
        background-color: #CD7F32 !important;
        color: white !important;
        border: 1px solid #8B4513 !important;
    }

    .btn-bronze:hover {
        background-color: #B87333 !important;
        color: white !important;
    }

    .btn-silver {
        background-color: #C0C0C0 !important;
        color: black !important;
        border: 1px solid #808080 !important;
    }

    .btn-silver:hover {
        background-color: #A9A9A9 !important;
        color: black !important;
    }

    .btn-gold {
        background-color: #FFD700 !important;
        color: black !important;
        border: 1px solid #DAA520 !important;
    }

    .btn-gold:hover {
        background-color: #E6BE8A !important;
        color: black !important;
    }

    .btn-warning {
        background-color: #ffc107 !important;
        color: black !important;
        border: 1px solid #d39e00 !important;
    }

    .btn-danger {
        background-color: #dc3545 !important;
        color: white !important;
        border: 1px solid #bd2130 !important;
    }

    .btn-group .btn {
        margin: 0 2px !important;
    }

    /* Animasi untuk badge medali */
    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .badge.bg-warning,
    .badge.bg-secondary,
    .badge.bg-danger {
        animation: pulse 2s infinite;
    }

    /* Highlight untuk penerima medali */
    .text-warning strong {
        position: relative;
    }

    .text-warning strong::after {
        content: "🏆";
        position: absolute;
        right: -25px;
        top: 50%;
        transform: translateY(-50%);
    }
</style>