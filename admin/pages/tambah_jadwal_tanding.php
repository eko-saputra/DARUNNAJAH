<div class="row">
    <div class="col-12 text-light">
        <div class="card">
            <div class="card-body">
                <?php
                require_once("functions/function.php");
                include("includes/connection.php");

                // Mencari data jadwal pertandingan
                $sqljadwal = "SELECT * FROM jadwal_tanding ORDER BY id_partai ASC";
                $jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
                ?>

                <!-- SweetAlert2 CSS & JS -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <!-- Upload Jadwal Tanding -->
                <div class="row-fluid sortable">
                    <div class="box span12">
                        <div class="box-header" data-original-title>
                            <h6 class="rounded p-2 bg-dark border border-1 border-muted text-primary mb-5"><i class="halflings-icon white download"></i><span class="break"></span>Upload Jadwal Tanding</h6>
                        </div>
                        <div class="box-content">
                            <?php
                            if (isset($_POST['submit'])) {
                                // Validasi file
                                if (!isset($_FILES['filename']) || $_FILES['filename']['error'] != UPLOAD_ERR_OK) {
                                    echo "<script>
            Swal.fire({
                icon: 'error',
                text: 'Error upload file. Silakan coba lagi.',
                confirmButtonColor: '#d33'
            });
        </script>";
                                } else {
                                    // Script Upload File
                                    $allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'text/plain'];
                                    $fileType = $_FILES['filename']['type'];

                                    if (!in_array($fileType, $allowedTypes)) {
                                        echo "<script>
                Swal.fire({
                    icon: 'error',
                    text: 'File harus berformat CSV.',
                    confirmButtonColor: '#d33'
                });
            </script>";
                                    } else {
                                        // Import uploaded file to Database
                                        $handle = fopen($_FILES['filename']['tmp_name'], "r");
                                        $importCount = 0;
                                        $errorCount = 0;
                                        $errors = [];

                                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                            // Escape data untuk mencegah SQL injection
                                            $escapedData = array_map(function ($item) use ($koneksi) {
                                                return mysqli_real_escape_string($koneksi, $item);
                                            }, $data);

                                            // ===== PERBAIKAN: Konversi format tanggal dari MM/DD/YYYY ke YYYY-MM-DD =====
                                            $originalDate = trim($escapedData[0]);
                                            $mysqlDate = '';

                                            // Coba berbagai format tanggal
                                            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $originalDate, $matches)) {
                                                // Format: MM/DD/YYYY atau MM-DD-YYYY
                                                $month = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                                                $day = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                                                $year = $matches[3];

                                                $mysqlDate = "$year-$month-$day";
                                            } elseif (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $originalDate, $matches)) {
                                                // Format: YYYY-MM-DD atau YYYY/MM/DD
                                                $year = $matches[1];
                                                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                                                $day = str_pad($matches[3], 2, '0', STR_PAD_LEFT);

                                                $mysqlDate = "$year-$month-$day";
                                            } elseif (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2})$/', $originalDate, $matches)) {
                                                // Format: MM/DD/YY (2 digit tahun)
                                                $month = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                                                $day = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                                                $year = $matches[3];

                                                // Konversi tahun 2 digit ke 4 digit
                                                $year = ($year < 50) ? "20$year" : "19$year";
                                                $mysqlDate = "$year-$month-$day";
                                            } else {
                                                // Format tidak dikenali, gunakan tanggal hari ini
                                                $mysqlDate = date('Y-m-d');
                                                $errors[] = "Format tanggal tidak valid: '$originalDate', diubah ke " . date('Y-m-d');
                                            }

                                            // Validasi akhir tanggal MySQL
                                            $dateTest = DateTime::createFromFormat('Y-m-d', $mysqlDate);
                                            if (!$dateTest || $dateTest->format('Y-m-d') !== $mysqlDate) {
                                                $mysqlDate = date('Y-m-d');
                                                $errors[] = "Tanggal tidak valid: '$originalDate', diubah ke " . date('Y-m-d');
                                            }

                                            // Query insert dengan tanggal yang sudah dikonversi
                                            $import = "INSERT INTO jadwal_tanding (id_partai, tgl, kelas, gelanggang, partai, nm_merah, kontingen_merah,
                            nm_biru, kontingen_biru, babak) 
                          VALUES ('$escapedData[3]', '$mysqlDate', '$escapedData[1]', '$escapedData[2]', '$escapedData[3]', 
                                  '$escapedData[4]', '$escapedData[5]', '$escapedData[6]', '$escapedData[7]', '$escapedData[8]')";

                                            if (mysqli_query($koneksi, $import)) {
                                                $importCount++;
                                            } else {
                                                $errorCount++;
                                                $errors[] = "Error database: " . mysqli_error($koneksi);
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
                                            $htmlMessage .= "<div class='text-warning mt-3'><small>";
                                            $htmlMessage .= implode('<br>', array_slice($errors, 0, 5));
                                            if (count($errors) > 5) {
                                                $htmlMessage .= "<br>... dan " . (count($errors) - 5) . " error lainnya";
                                            }
                                            $htmlMessage .= "</small></div>";
                                        }

                                        echo "<script>
    Swal.fire({
        icon: '" . ($importCount > 0 ? 'success' : 'error') . "',
        html: `$htmlMessage`,
        confirmButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?page=tambah_jadwal';
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
                                        Download sample csv <a href="sample_jadwal.csv" class="text-info">di sini</a>.
                                        <br><strong class="text-warning">Format tanggal wajib (YYYY-MM-DD)</strong>.
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

                <!-- Input Jadwal Tanding Manual -->
                <div class="row-fluid sortable">
                    <div class="box span12">
                        <div class="box-header" data-original-title>
                            <div class="box-header" data-original-title>
                                <h6 class="rounded p-2 bg-dark border border-1 border-muted text-primary my-5"><i class="halflings-icon white download"></i><span class="break"></span>Input Jadwal Tanding Manual</h6>
                            </div>
                        </div>
                        <div class="box-content">
                            <form class="form-horizontal" method="post" action="pages/proses/post_jadwal_tanding.php" id="manualForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal<span class="text-danger">*</span></label>
                                        <input type="date" name="tgl" id="tgl" class="form-control text-muted" value="<?php echo date('Y-m-d'); ?>" required>
                                        <div class="invalid-feedback" id="tglError">Tanggal harus diisi dan tidak boleh lebih dari hari ini</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kelas/Kelompok<span class="text-danger">*</span></label>
                                        <input type="text" name="kelas" id="kelas" class="form-control text-muted" maxlength="35" placeholder="Contoh: Remaja Putra Kelas A" required>
                                        <div class="invalid-feedback" id="kelasError">Kelas harus diisi (maksimal 35 karakter)</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gelanggang<span class="text-danger">*</span></label>
                                        <input type="text" name="gelanggang" id="gelanggang" class="form-control text-muted" maxlength="35" placeholder="A / B / C" required>
                                        <div class="invalid-feedback" id="gelanggangError">Gelanggang harus diisi</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">No Partai<span class="text-danger">*</span></label>
                                        <input type="number" name="nopartai" id="nopartai" class="form-control text-muted" placeholder="1 / 2 / 3 dst..." min="1" required>
                                        <div class="invalid-feedback" id="nopartaiError">No Partai harus angka dan minimal 1</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Pesilat Merah<span class="text-danger">*</span></label>
                                        <input type="text" name="nm_merah" id="nm_merah" class="form-control text-muted" maxlength="55" placeholder="Nama pesilat sudut merah" required>
                                        <div class="invalid-feedback" id="nm_merahError">Nama pesilat merah harus diisi</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kontingen Merah<span class="text-danger">*</span></label>
                                        <input type="text" name="kont_merah" id="kont_merah" class="form-control text-muted" maxlength="55" placeholder="Kontingen pesilat sudut merah" required>
                                        <div class="invalid-feedback" id="kont_merahError">Kontingen merah harus diisi</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Pesilat Biru<span class="text-danger">*</span></label>
                                        <input type="text" name="nm_biru" id="nm_biru" class="form-control text-muted" maxlength="55" placeholder="Nama pesilat sudut biru" required>
                                        <div class="invalid-feedback" id="nm_biruError">Nama pesilat biru harus diisi</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kontingen Biru<span class="text-danger">*</span></label>
                                        <input type="text" name="kont_biru" id="kont_biru" class="form-control text-muted" maxlength="55" placeholder="Kontingen pesilat sudut biru" required>
                                        <div class="invalid-feedback" id="kont_biruError">Kontingen biru harus diisi</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">BABAK<span class="text-danger">*</span></label>
                                        <select name="babak" id="babak" class="form-control text-muted" required>
                                            <option value="">-- Pilih Babak --</option>
                                            <option value="PENYISIHAN">PENYISIHAN</option>
                                            <option value="SEMIFINAL">SEMIFINAL</option>
                                            <option value="FINAL">FINAL</option>
                                        </select>
                                        <div class="invalid-feedback" id="babakError">Pilih babak pertandingan</div>
                                    </div>
                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-info w-100">SUBMIT</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Data Jadwal Tanding -->
                <div class="row-fluid sortable">
                    <div class="box span12">
                        <div class="box-header" data-original-title>
                            <div class="box-header" data-original-title>
                                <h6 class="rounded p-2 bg-dark border border-1 border-muted text-primary my-5"><i class="halflings-icon white download"></i><span class="break"></span>Data Jadwal Tanding</h6>
                            </div>
                        </div>
                        <div class="box-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>TANGGAL</th>
                                            <th>GEL.</th>
                                            <th>PARTAI</th>
                                            <th>BABAK</th>
                                            <th>KELOMPOK</th>
                                            <th class="bg-primary text-light">SUDUT BIRU</th>
                                            <th class="bg-danger text-light">SUDUT MERAH</th>
                                            <th>AKTIF</th>
                                            <th>ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        if (mysqli_num_rows($jadwal_tanding) > 0) {
                                            while ($jadwal = mysqli_fetch_array($jadwal_tanding)) {
                                                $no++;
                                        ?>
                                                <tr>
                                                    <td class="text-light"><?php echo $no; ?></td>
                                                    <td class="text-light text-uppercase"><?php echo date('d/m/Y', strtotime($jadwal['tgl'])); ?></td>
                                                    <td class="text-light text-uppercase"><?php echo htmlspecialchars($jadwal['gelanggang']); ?></td>
                                                    <td class="text-light text-uppercase"><?php echo htmlspecialchars($jadwal['partai']); ?></td>
                                                    <td class="text-light text-uppercase"><?php echo htmlspecialchars($jadwal['babak']); ?></td>
                                                    <td class="text-light text-uppercase"><?php echo htmlspecialchars($jadwal['kelas']); ?></td>
                                                    <td class="text-light text-uppercase"><?php echo htmlspecialchars($jadwal['nm_biru']) . " - " . htmlspecialchars($jadwal['kontingen_biru']); ?></td>
                                                    <td class="text-light text-uppercase"><?php echo htmlspecialchars($jadwal['nm_merah']) . " - " . htmlspecialchars($jadwal['kontingen_merah']); ?></td>
                                                    <td class="text-light text-uppercase">
                                                        <?php if ($jadwal['aktif'] == '0'): ?>
                                                            <span class="badge bg-danger">NO</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-success">YES</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-warning btn-sm" href="?page=edit_partai&id_partai=<?php echo $jadwal['id_partai']; ?>">
                                                            <i class="halflings-icon white pencil"></i> Edit
                                                        </a>
                                                        <a class="btn btn-danger btn-sm btn-delete"
                                                            data-id="<?php echo $jadwal['id_partai']; ?>"
                                                            data-name="<?php echo htmlspecialchars($jadwal['nm_merah'] . ' vs ' . $jadwal['nm_biru']); ?>">
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
                            <div class="box-header" data-original-title>
                                <h6 class="rounded p-2 bg-dark border border-1 border-muted text-primary mt-5"><i class="halflings-icon white download"></i><span class="break"></span>Hapus Data Jadwal & Nilai</h6>
                            </div>
                            <div class="box-icon">
                                <a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
                                <a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
                                <a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
                            </div>
                        </div>
                        <div class="box-content">
                            <form class="form-horizontal" method="post" action="pages/proses/clear_jadwal_tanding.php" id="deleteAllForm">
                                <div class="alert alert-danger">
                                    <h4><i class="icon-warning-sign"></i> PERINGATAN!</h4>
                                    <p>Dengan menekan tombol "HAPUS SEMUA" di bawah ini, maka seluruh data <b>Jadwal Partai beserta Nilai Penjuriannya</b> pada <b>Kelas Tanding</b> akan hilang dari database.</p>
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

<script src="js/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.datatable').DataTable();
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
                case 'tgl':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Tanggal harus diisi';
                    }
                    break;

                case 'kelas':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Kelas harus diisi';
                    } else if (value.length > 35) {
                        isValid = false;
                        errorMessage = 'Kelas maksimal 35 karakter';
                    }
                    break;

                case 'gelanggang':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Gelanggang harus diisi';
                    }
                    break;

                case 'nopartai':
                    if (!value || isNaN(value) || parseInt(value) < 1) {
                        isValid = false;
                        errorMessage = 'No Partai harus angka minimal 1';
                    }
                    break;

                case 'nm_merah':
                case 'kont_merah':
                case 'nm_biru':
                case 'kont_biru':
                    if (!value) {
                        isValid = false;
                        const fieldName = field.id.includes('merah') ? 'Merah' : 'Biru';
                        const type = field.id.includes('nm_') ? 'Nama' : 'Kontingen';
                        errorMessage = `${type} ${fieldName} harus diisi`;
                    }
                    break;

                case 'babak':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Pilih babak pertandingan';
                    }
                    break;
            }

            // Update UI
            if (!isValid) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                document.getElementById(field.id + 'Error').textContent = errorMessage;
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
                    text: 'Apakah data sudah benar?',
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
                    html: `Apakah Anda yakin menghapus data:<br><strong>${name}</strong>?`,
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
                            html: `Berhasil dihapus!`,
                            // confirmButtonColor: '#3085d6'
                        }).then((result) => {
                            // if (result.isConfirmed) {
                            window.location.href = `pages/proses/del_partai.php?id_partai=${id}`;
                            // }
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
                    html: `
                    <div class="text-start">
                        <p class="text-danger"><strong>PERINGATAN KRITIS!</strong></p>
                        <p>Semua data berikut akan dihapus permanen:</p>
                        <ul>
                            <li>Seluruh jadwal tanding</li>
                            <li>Semua nilai penjurian</li>
                            <li>Data kelas tanding</li>
                        </ul>
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
                        if (confirmInput.value !== 'HAPUS SEMUA') {
                            Swal.showValidationMessage('Konfirmasi tidak sesuai!');
                            return false;
                        }
                        return true;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            html: `Berhasil dihapus!`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                deleteAllForm.submit();
                            }
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
</style>