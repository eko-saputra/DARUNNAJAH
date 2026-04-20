<div class="row">
    <div class="col-12 text-light">
        <div class="card">
            <div class="card-body">
                <?php
                require_once("functions/function.php");
                include("includes/connection.php");

                // Aktifkan error reporting untuk debugging
                error_reporting(E_ALL);
                ini_set('display_errors', 1);

                // Set timezone
                date_default_timezone_set('Asia/Jakarta');

                // Mencari data peserta
                $sqlpeserta = "SELECT * FROM peserta ORDER BY ID_peserta DESC";
                $peserta = mysqli_query($koneksi, $sqlpeserta);

                $sqlkelas = "SELECT * FROM kelastanding";
                $kelas_tanding = mysqli_query($koneksi, $sqlkelas);
                ?>

                <!-- SweetAlert2 CSS & JS -->
                <link rel="stylesheet" href="assets/css/sweetalert2.css">
                <script src="assets/js/sweetalert2.js"></script>

                <!-- Upload Data Peserta -->
                <div class="row-fluid sortable">
                    <div class="box span12">
                        <div class="box-header" data-original-title>
                            <h6 class="rounded p-2 bg-dark border border-1 border-muted text-primary mb-5"><i class="halflings-icon white download"></i><span class="break"></span>Upload Data Peserta</h6>
                        </div>
                        <div class="box-content">
                            <?php
                            if (isset($_POST['submit'])) {
                                // Validasi file
                                if (!isset($_FILES['filename']) || $_FILES['filename']['error'] != UPLOAD_ERR_OK) {
                                    $error_message = "Error upload file. ";
                                    switch ($_FILES['filename']['error']) {
                                        case UPLOAD_ERR_INI_SIZE:
                                            $error_message .= "File terlalu besar (max " . ini_get('upload_max_filesize') . ")";
                                            break;
                                        case UPLOAD_ERR_FORM_SIZE:
                                            $error_message .= "File terlalu besar";
                                            break;
                                        case UPLOAD_ERR_PARTIAL:
                                            $error_message .= "File hanya terupload sebagian";
                                            break;
                                        case UPLOAD_ERR_NO_FILE:
                                            $error_message .= "Tidak ada file yang dipilih";
                                            break;
                                        default:
                                            $error_message .= "Kode error: " . $_FILES['filename']['error'];
                                    }
                                    echo "<script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Upload Gagal',
                                            text: '$error_message',
                                            confirmButtonColor: '#d33'
                                        });
                                    </script>";
                                } else {
                                    // Script Upload File
                                    $allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'text/plain', 'application/csv', 'application/excel', 'application/vnd.msexcel'];
                                    $fileType = $_FILES['filename']['type'];
                                    $fileExt = strtolower(pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION));

                                    if (!in_array($fileType, $allowedTypes) && $fileExt != 'csv') {
                                        echo "<script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Format Salah',
                                                text: 'File harus berformat CSV.',
                                                confirmButtonColor: '#d33'
                                            });
                                        </script>";
                                    } else {
                                        // Import uploaded file to Database
                                        $handle = fopen($_FILES['filename']['tmp_name'], "r");
                                        if (!$handle) {
                                            echo "<script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Error',
                                                    text: 'Tidak dapat membaca file',
                                                    confirmButtonColor: '#d33'
                                                });
                                            </script>";
                                            exit;
                                        }

                                        // Lewati baris pertama (header) - dengan parameter escape untuk PHP 8.1+
                                        $firstRow = fgetcsv($handle, 1000, ",", '"', '\\');

                                        $importCount = 0;
                                        $errorCount = 0;
                                        $errors = [];
                                        $rowNumber = 1; // Mulai dari baris 2 (setelah header)

                                        // Set default values untuk field yang wajib tapi tidak ada di CSV
                                        $default_foto = "'default.jpg'";
                                        $default_ktp = "'pending_ktp.jpg'";
                                        $default_akta = "'pending_akta.jpg'";
                                        $default_ijazah = "'pending_ijazah.jpg'";

                                        // Loop data dengan parameter escape untuk PHP 8.1+
                                        while (($data = fgetcsv($handle, 1000, ",", '"', '\\')) !== FALSE) {
                                            $rowNumber++;

                                            // Cek jumlah kolom (minimal 14 kolom)
                                            if (count($data) < 14) {
                                                $errors[] = "Baris $rowNumber: Jumlah kolom kurang (" . count($data) . " dari 14)";
                                                $errorCount++;
                                                continue;
                                            }

                                            // Escape data untuk mencegah SQL injection
                                            $escapedData = array_map(function ($item) use ($koneksi) {
                                                return mysqli_real_escape_string($koneksi, trim($item));
                                            }, $data);

                                            // ===== KONVERSI FORMAT TANGGAL LAHIR =====
                                            $originalDate = trim($escapedData[3]); // tgl_lahir ada di index 3
                                            $mysqlDate = '';

                                            // Cek jika tanggal kosong
                                            if (empty($originalDate)) {
                                                $mysqlDate = date('Y-m-d');
                                                $errors[] = "Baris $rowNumber: Tanggal lahir kosong, diisi dengan " . date('Y-m-d');
                                            }
                                            // Coba berbagai format tanggal
                                            elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $originalDate, $matches)) {
                                                // Format: YYYY-MM-DD
                                                $mysqlDate = $originalDate;
                                            } elseif (preg_match('/^(\d{4})\/(\d{2})\/(\d{2})$/', $originalDate, $matches)) {
                                                // Format: YYYY/MM/DD
                                                $mysqlDate = "$matches[1]-$matches[2]-$matches[3]";
                                            } elseif (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $originalDate, $matches)) {
                                                // Format: MM/DD/YYYY
                                                $mysqlDate = "$matches[3]-$matches[1]-$matches[2]";
                                            } elseif (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $originalDate, $matches)) {
                                                // Format: MM-DD-YYYY
                                                $mysqlDate = "$matches[3]-$matches[1]-$matches[2]";
                                            } elseif (preg_match('/^(\d{2})\/(\d{2})\/(\d{2})$/', $originalDate, $matches)) {
                                                // Format: MM/DD/YY
                                                $year = $matches[3];
                                                $year = ($year < 50) ? "20$year" : "19$year";
                                                $mysqlDate = "$year-$matches[1]-$matches[2]";
                                            } else {
                                                // Format tidak dikenali
                                                $mysqlDate = date('Y-m-d');
                                                $errors[] = "Baris $rowNumber: Format tanggal tidak valid: '$originalDate', gunakan YYYY-MM-DD";
                                            }

                                            // Validasi akhir tanggal MySQL
                                            $dateTest = DateTime::createFromFormat('Y-m-d', $mysqlDate);
                                            if (!$dateTest || $dateTest->format('Y-m-d') !== $mysqlDate) {
                                                $mysqlDate = date('Y-m-d');
                                                $errors[] = "Baris $rowNumber: Tanggal tidak valid: '$originalDate', diubah ke " . date('Y-m-d');
                                            }

                                            // Handle numeric fields (tb, bb) - jika kosong set NULL
                                            $tb = !empty($escapedData[4]) ? (int)$escapedData[4] : 'NULL';
                                            $bb = !empty($escapedData[5]) ? (int)$escapedData[5] : 'NULL';
                                            $kelas = !empty($escapedData[6]) ? "'$escapedData[6]'" : 'NULL';

                                            // Query insert dengan semua field termasuk yang wajib
                                            $import = "INSERT INTO peserta (
                                                nm_lengkap, 
                                                jenis_kelamin, 
                                                tpt_lahir, 
                                                tgl_lahir, 
                                                tb, 
                                                bb, 
                                                kelas, 
                                                asal_sekolah, 
                                                kategori_tanding, 
                                                golongan, 
                                                kode_gr, 
                                                kelas_tanding_FK, 
                                                kontingen, 
                                                foto,
                                                ktp,
                                                akta_lahir,
                                                ijazah,
                                                status
                                            ) VALUES (
                                                '$escapedData[0]', 
                                                '$escapedData[1]', 
                                                '$escapedData[2]', 
                                                '$mysqlDate', 
                                                $tb, 
                                                $bb, 
                                                $kelas, 
                                                '$escapedData[7]', 
                                                '$escapedData[8]', 
                                                '$escapedData[9]', 
                                                '$escapedData[10]', 
                                                '$escapedData[11]', 
                                                '$escapedData[12]', 
                                                $default_foto,
                                                $default_ktp,
                                                $default_akta,
                                                $default_ijazah,
                                                '$escapedData[13]'
                                            )";

                                            if (mysqli_query($koneksi, $import)) {
                                                $importCount++;
                                            } else {
                                                $errorCount++;
                                                $errors[] = "Baris $rowNumber: Error database - " . mysqli_error($koneksi);
                                            }
                                        }

                                        fclose($handle);

                                        // Tampilkan pesan hasil import
                                        $htmlMessage = '';
                                        if ($importCount > 0) {
                                            $htmlMessage .= "<div class='text-success mb-2'><strong>Berhasil diimport:</strong> $importCount data</div>";
                                        }
                                        if ($errorCount > 0) {
                                            $htmlMessage .= "<div class='text-danger mb-2'><strong>Gagal:</strong> $errorCount data</div>";
                                        }
                                        if (!empty($errors)) {
                                            $htmlMessage .= "<div class='text-warning mt-3'><strong>Detail Error:</strong><br>";
                                            $htmlMessage .= "<small>" . implode('<br>', array_slice($errors, 0, 10)) . "</small>";
                                            if (count($errors) > 10) {
                                                $htmlMessage .= "<br><small>... dan " . (count($errors) - 10) . " error lainnya</small>";
                                            }
                                            $htmlMessage .= "</div>";
                                        }

                                        echo "<script>
                                            Swal.fire({
                                                icon: '" . ($importCount > 0 ? 'success' : 'error') . "',
                                                title: '" . ($importCount > 0 ? 'Import Berhasil' : 'Import Gagal') . "',
                                                html: `$htmlMessage`,
                                                confirmButtonColor: '#3085d6'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = '?page=peserta';
                                                }
                                            });
                                        </script>";
                                    }
                                }
                            }
                            ?>

                            <form enctype='multipart/form-data' action='' method='post' id="uploadForm">
                                <div class="mb-3">
                                    <p>
                                        Format kolom data pada csv harus sesuai dengan contoh.
                                        Download sample csv <a href="sample_peserta.csv" class="text-info">di sini</a>.
                                        <br><strong class="text-warning">Format tanggal lahir wajib (YYYY-MM-DD)</strong>.
                                    </p>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type='file' name='filename' id='filename' class="form-control text-muted" accept=".csv" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type='submit' name='submit' class='btn btn-primary' value='Upload'>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Input Peserta Manual -->
                <div class="row-fluid sortable">
                    <div class="box span12">
                        <div class="box-header" data-original-title>
                            <h6 class="rounded p-2 bg-dark border border-1 border-muted text-primary my-5"><i class="halflings-icon white download"></i><span class="break"></span>Input Data Peserta Manual</h6>
                        </div>
                        <div class="box-content">
                            <form class="form-horizontal" method="post" action="pages/proses/post_peserta.php" id="manualForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Lengkap<span class="text-danger">*</span></label>
                                        <input type="text" name="nm_lengkap" id="nm_lengkap" class="form-control text-muted" maxlength="35" placeholder="Nama lengkap peserta" required>
                                        <div class="invalid-feedback" id="nm_lengkapError">Nama lengkap harus diisi (maksimal 35 karakter)</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jenis Kelamin<span class="text-danger">*</span></label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control text-muted" required>
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="LAKI-LAKI">LAKI-LAKI</option>
                                            <option value="PEREMPUAN">PEREMPUAN</option>
                                        </select>
                                        <div class="invalid-feedback" id="jenis_kelaminError">Pilih jenis kelamin</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tempat Lahir<span class="text-danger">*</span></label>
                                        <input type="text" name="tpt_lahir" id="tpt_lahir" class="form-control text-muted" maxlength="55" placeholder="Tempat lahir" required>
                                        <div class="invalid-feedback" id="tpt_lahirError">Tempat lahir harus diisi</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Lahir<span class="text-danger">*</span></label>
                                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control text-muted" required>
                                        <div class="invalid-feedback" id="tgl_lahirError">Tanggal lahir harus diisi</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tinggi Badan (cm)</label>
                                        <input type="number" name="tb" id="tb" class="form-control text-muted" min="0" max="300" placeholder="Contoh: 170">
                                        <div class="invalid-feedback" id="tbError">Tinggi badan harus antara 0-300 cm</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Berat Badan (kg)</label>
                                        <input type="number" name="bb" id="bb" class="form-control text-muted" min="0" max="200" placeholder="Contoh: 65">
                                        <div class="invalid-feedback" id="bbError">Berat badan harus antara 0-200 kg</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kelas</label>
                                        <select name="kelas" id="kelas" class="form-control text-muted">
                                            <option value="">-- Pilih Kelas --</option>
                                            <?php while ($row = mysqli_fetch_assoc($kelas_tanding)) { ?>
                                                <option value="<?= $row['ID_kelastanding'] ?>"><?= $row['nm_kelastanding'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Asal Sekolah<span class="text-danger">*</span></label>
                                        <input type="text" name="asal_sekolah" id="asal_sekolah" class="form-control text-muted" maxlength="55" placeholder="Nama sekolah/instansi" required>
                                        <div class="invalid-feedback" id="asal_sekolahError">Asal sekolah harus diisi</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kategori<span class="text-danger">*</span></label>
                                        <select name="kategori_tanding" id="kategori_tanding" class="form-control text-muted" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="TANDING">TANDING</option>
                                            <option value="TUNGGAL">TUNGGAL</option>
                                            <option value="GANDA">GANDA</option>
                                            <option value="REGU">REGU</option>
                                        </select>
                                        <div class="invalid-feedback" id="kategori_tandingError">Pilih kategori tanding</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Golongan<span class="text-danger">*</span></label>
                                        <select name="golongan" id="golongan" class="form-control text-muted" required>
                                            <option value="">-- Pilih Golongan --</option>
                                            <option value="Usia Dini 2A">Usia Dini 2A</option>
                                            <option value="Usia Dini 2B">Usia Dini 2B</option>
                                            <option value="Pra Remaja">Pra Remaja</option>
                                            <option value="Remaja">Remaja</option>
                                            <option value="Dewasa">Dewasa</option>
                                        </select>
                                        <div class="invalid-feedback" id="golonganError">Pilih golongan</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kode GR<span class="text-danger">*</span></label>
                                        <input type="text" name="kode_gr" id="kode_gr" class="form-control text-muted" maxlength="32" placeholder="Kode GR" required>
                                        <div class="invalid-feedback" id="kode_grError">Kode GR harus diisi</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kelas Tanding FK<span class="text-danger">*</span></label>
                                        <input type="text" name="kelas_tanding_FK" id="kelas_tanding_FK" class="form-control text-muted" maxlength="25" placeholder="Kelas tanding" required>
                                        <div class="invalid-feedback" id="kelas_tanding_FKError">Kelas tanding FK harus diisi</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kontingen<span class="text-danger">*</span></label>
                                        <input type="text" name="kontingen" id="kontingen" class="form-control text-muted" maxlength="100" placeholder="Nama kontingen" required>
                                        <div class="invalid-feedback" id="kontingenError">Kontingen harus diisi</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status<span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control text-muted" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="AKTIF">AKTIF</option>
                                            <option value="NONAKTIF">NONAKTIF</option>
                                            <option value="CADANGAN">CADANGAN</option>
                                        </select>
                                        <div class="invalid-feedback" id="statusError">Pilih status peserta</div>
                                    </div>
                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-info w-100">SUBMIT</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Data Peserta -->
                <div class="row-fluid sortable">
                    <div class="box span12">
                        <div class="box-header" data-original-title>
                            <h6 class="rounded p-2 bg-dark border border-1 border-muted text-primary my-5"><i class="halflings-icon white download"></i><span class="break"></span>Data Peserta</h6>
                        </div>
                        <div class="box-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>JK</th>
                                            <th>TEMPAT, TGL LAHIR</th>
                                            <th>KONTINGEN</th>
                                            <th>KATEGORI</th>
                                            <th>GOLONGAN</th>
                                            <th>STATUS</th>
                                            <th>ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        if (mysqli_num_rows($peserta) > 0) {
                                            while ($row = mysqli_fetch_array($peserta)) {
                                                $no++;
                                                $tgl_lahir = date('d/m/Y', strtotime($row['tgl_lahir']));
                                        ?>
                                                <tr>
                                                    <td class="text-light text-center"><?php echo $no; ?></td>
                                                    <td class="text-light text-uppercase"><?php echo htmlspecialchars($row['nm_lengkap']); ?></td>
                                                    <td class="text-light"><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                                                    <td class="text-light"><?php echo htmlspecialchars($row['tpt_lahir']) . ', ' . $tgl_lahir; ?></td>
                                                    <td class="text-light text-uppercase"><?php echo htmlspecialchars($row['kontingen']); ?></td>
                                                    <td class="text-light"><?php echo htmlspecialchars($row['kategori_tanding']); ?></td>
                                                    <td class="text-light"><?php echo htmlspecialchars($row['golongan']); ?></td>
                                                    <td class="text-light">
                                                        <span class="badge <?php echo ($row['status'] == 'AKTIF') ? 'bg-success' : 'bg-secondary'; ?>">
                                                            <?php echo htmlspecialchars($row['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-warning btn-sm" href="?page=edit_peserta&id=<?php echo $row['ID_peserta']; ?>">
                                                            <i class="halflings-icon white pencil"></i> Edit
                                                        </a>
                                                        <a class="btn btn-danger btn-sm btn-delete"
                                                            data-id="<?php echo $row['ID_peserta']; ?>"
                                                            data-name="<?php echo htmlspecialchars($row['nm_lengkap']); ?>">
                                                            <i class="halflings-icon white trash"></i> Hapus
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clear All Data -->
                <div class="row-fluid sortable">
                    <div class="box span12">
                        <div class="box-header" data-original-title>
                            <h6 class="rounded p-2 bg-dark border border-1 border-muted text-primary mt-5"><i class="halflings-icon white download"></i><span class="break"></span>Hapus Semua Data Peserta</h6>
                        </div>
                        <div class="box-content">
                            <form class="form-horizontal" method="post" action="pages/proses/clear_peserta.php" id="deleteAllForm">
                                <div class="alert alert-danger">
                                    <h4><i class="icon-warning-sign"></i> PERINGATAN!</h4>
                                    <p>Dengan menekan tombol "HAPUS SEMUA" di bawah ini, maka seluruh data <b>Peserta</b> akan hilang dari database.</p>
                                    <p><strong>Tindakan ini tidak dapat dibatalkan!</strong></p>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-danger btn-lg" id="btnDeleteAll">
                                        <i class="halflings-icon white trash"></i> HAPUS SEMUA DATA
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="js/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            "pageLength": 25,
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "search": "Cari:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // ============ VALIDASI FORM MANUAL ============
        const manualForm = document.getElementById('manualForm');
        const inputs = manualForm.querySelectorAll('input, select');

        // Fungsi validasi individual
        function validateField(field) {
            const value = field.value.trim();
            let isValid = true;
            let errorMessage = '';

            switch (field.id) {
                case 'nm_lengkap':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Nama lengkap harus diisi';
                    } else if (value.length > 35) {
                        isValid = false;
                        errorMessage = 'Nama lengkap maksimal 35 karakter';
                    }
                    break;

                case 'jenis_kelamin':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Pilih jenis kelamin';
                    }
                    break;

                case 'tpt_lahir':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Tempat lahir harus diisi';
                    } else if (value.length > 55) {
                        isValid = false;
                        errorMessage = 'Tempat lahir maksimal 55 karakter';
                    }
                    break;

                case 'tgl_lahir':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Tanggal lahir harus diisi';
                    } else {
                        const selectedDate = new Date(value);
                        const today = new Date();
                        if (selectedDate > today) {
                            isValid = false;
                            errorMessage = 'Tanggal lahir tidak boleh melebihi hari ini';
                        }
                    }
                    break;

                case 'tb':
                    if (value && (parseInt(value) < 0 || parseInt(value) > 300)) {
                        isValid = false;
                        errorMessage = 'Tinggi badan harus antara 0-300 cm';
                    }
                    break;

                case 'bb':
                    if (value && (parseInt(value) < 0 || parseInt(value) > 200)) {
                        isValid = false;
                        errorMessage = 'Berat badan harus antara 0-200 kg';
                    }
                    break;

                case 'kelas':
                    if (value && value.length > 21) {
                        isValid = false;
                        errorMessage = 'Kelas maksimal 21 karakter';
                    }
                    break;

                case 'asal_sekolah':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Asal sekolah harus diisi';
                    } else if (value.length > 55) {
                        isValid = false;
                        errorMessage = 'Asal sekolah maksimal 55 karakter';
                    }
                    break;

                case 'kategori_tanding':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Pilih kategori tanding';
                    }
                    break;

                case 'golongan':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Pilih golongan';
                    }
                    break;

                case 'kode_gr':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Kode GR harus diisi';
                    } else if (value.length > 32) {
                        isValid = false;
                        errorMessage = 'Kode GR maksimal 32 karakter';
                    }
                    break;

                case 'kelas_tanding_FK':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Kelas tanding FK harus diisi';
                    } else if (value.length > 25) {
                        isValid = false;
                        errorMessage = 'Kelas tanding FK maksimal 25 karakter';
                    }
                    break;

                case 'kontingen':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Kontingen harus diisi';
                    } else if (value.length > 100) {
                        isValid = false;
                        errorMessage = 'Kontingen maksimal 100 karakter';
                    }
                    break;

                case 'status':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Pilih status peserta';
                    }
                    break;
            }

            // Update UI
            if (!isValid) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                if (document.getElementById(field.id + 'Error')) {
                    document.getElementById(field.id + 'Error').textContent = errorMessage;
                }
            } else {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
            }

            return isValid;
        }

        // Validasi real-time
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });

        // Validasi saat submit
        manualForm.addEventListener('submit', function(e) {
            e.preventDefault();

            let allValid = true;
            inputs.forEach(input => {
                if (!validateField(input)) {
                    allValid = false;
                }
            });

            if (allValid) {
                // Show confirmation before submitting
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah data peserta sudah benar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Periksa Lagi'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Form Tidak Valid',
                    text: 'Harap periksa kembali data yang diinput',
                    confirmButtonColor: '#d33'
                });

                // Focus ke field pertama yang error
                const firstError = manualForm.querySelector('.is-invalid');
                if (firstError) {
                    firstError.focus();
                }
            }
        });

        // ============ SWEETALERT DELETE SINGLE ============
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    html: `Apakah Anda yakin menghapus data peserta:<br><strong>${name}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data peserta berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = `pages/proses/del_peserta.php?id=${id}`;
                        });
                    }
                });
            });
        });

        // ============ SWEETALERT DELETE ALL ============
        const deleteAllForm = document.getElementById('deleteAllForm');
        const btnDeleteAll = document.getElementById('btnDeleteAll');

        if (btnDeleteAll) {
            btnDeleteAll.addEventListener('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Hapus Semua Data?',
                    html: `
                    <div class="text-start">
                        <p class="text-danger"><strong>PERINGATAN KRITIS!</strong></p>
                        <p>Semua data peserta akan dihapus permanen!</p>
                        <p><strong class="text-danger">Tindakan ini TIDAK DAPAT DIBATALKAN!</strong></p>
                        <p>Ketik <strong>HAPUS SEMUA</strong> untuk konfirmasi:</p>
                        <input type="text" id="confirmText" class="form-control text-muted" placeholder="HAPUS SEMUA">
                    </div>
                `,
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batalkan',
                    reverseButtons: true,
                    preConfirm: () => {
                        const confirmInput = document.getElementById('confirmText');
                        if (!confirmInput || confirmInput.value !== 'HAPUS SEMUA') {
                            Swal.showValidationMessage('Konfirmasi tidak sesuai!');
                            return false;
                        }
                        return true;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Semua data peserta berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            deleteAllForm.submit();
                        });
                    }
                });
            });
        }

        // ============ VALIDASI UPLOAD CSV ============
        const uploadForm = document.getElementById('uploadForm');
        const fileInput = document.getElementById('filename');

        uploadForm.addEventListener('submit', function(e) {
            const file = fileInput.files[0];

            if (!file) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'File Kosong',
                    text: 'Pilih file CSV terlebih dahulu',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            if (!file.name.toLowerCase().endsWith('.csv')) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Format Salah',
                    text: 'File harus berekstensi .csv',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            if (file.size > 5 * 1024 * 1024) { // 5MB limit
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file maksimal 5MB',
                    confirmButtonColor: '#d33'
                });
            }
        });
    });
</script>

<style>
    /* Styling untuk validasi form */
    .is-valid {
        border-color: #198754 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right calc(.375em + .1875rem) center !important;
        background-size: calc(.75em + .375rem) calc(.75em + .375rem) !important;
    }

    .is-invalid {
        border-color: #dc3545 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right calc(.375em + .1875rem) center !important;
        background-size: calc(.75em + .375rem) calc(.75em + .375rem) !important;
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: .25rem;
        font-size: .875em;
        color: #dc3545;
    }

    .is-invalid~.invalid-feedback {
        display: block;
    }

    .form-label .text-danger {
        color: #dc3545;
    }

    /* Styling untuk badge status */
    .badge {
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 4px;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-secondary {
        background-color: #6c757d !important;
    }

    /* Styling untuk tabel */
    .table {
        color: #fff !important;
    }

    .table thead th {
        background-color: #343a40;
        color: #fff;
        border-bottom: 2px solid #4a545e;
    }

    .table tbody tr:hover {
        background-color: #2c3136 !important;
    }

    /* Styling untuk tombol aksi */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        margin: 0 2px;
    }

    .btn-warning {
        color: #212529;
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .btn-danger {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    /* Styling untuk alert */
    .alert-danger {
        background-color: #2c1a1a;
        border-color: #842029;
        color: #ea868f;
    }

    /* Styling untuk form upload */
    .text-info {
        color: #0dcaf0 !important;
        text-decoration: none;
    }

    .text-info:hover {
        color: #31d2f2 !important;
        text-decoration: underline;
    }
</style>