<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                <?php
                include('includes/connection.php');
                include("functions/function.php");

                $id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"]);

                // Kueri mencari peserta
                $sqlpartai = "SELECT * FROM jadwal_tanding WHERE id_partai = '$id_partai'";
                $datapartai = mysqli_query($koneksi, $sqlpartai);
                $partai = mysqli_fetch_array($datapartai);
                ?>

                <form class="row g-3" method="post" action="pages/proses/admin_do_edit_partai.php">
                    <!-- ID Partai (Readonly) -->
                    <div class="col-md-6">
                        <label for="id_partai" class="form-label fw-bold">ID Partai</label>
                        <input type="text" class="form-control text-muted" name="id_partai" id="id_partai"
                            value="<?php echo htmlspecialchars($id_partai); ?>" readonly>
                    </div>

                    <!-- Gelanggang -->
                    <div class="col-md-6">
                        <label for="gelanggang" class="form-label fw-bold">Gelanggang</label>
                        <input type="text" class="form-control bg-light text-muted" name="gelanggang" id="gelanggang"
                            value="<?php echo htmlspecialchars($partai["gelanggang"] ?? ''); ?>">
                    </div>

                    <!-- Babak -->
                    <div class="col-md-6">
                        <label for="babak" class="form-label fw-bold">Babak</label>
                        <select class="form-select" name="babak" id="babak">
                            <?php
                            $babak_options = ['SEMIFINAL', 'FINAL'];
                            foreach ($babak_options as $option) {
                                $selected = ($partai["babak"] ?? '') == $option ? 'selected' : '';
                                echo "<option value='$option' $selected>$option</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Kelompok -->
                    <div class="col-md-6">
                        <label for="kelas" class="form-label fw-bold">Kelompok</label>
                        <input type="text" class="form-control bg-light text-muted" name="kelas" id="kelas"
                            value="<?php echo htmlspecialchars($partai["kelas"] ?? ''); ?>">
                    </div>

                    <!-- Pesilat Sudut Merah -->
                    <div class="col-12">
                        <h6 class="text-danger text-uppercase">
                            <i class="fas fa-user me-2"></i>Sudut Merah
                        </h6>
                    </div>

                    <div class="col-md-6 bg-danger rounded p-2">
                        <label for="nm_merah" class="form-label">Nama Pesilat</label>
                        <input type="text" class="form-control bg-light text-muted" name="nm_merah" id="nm_merah"
                            value="<?php echo htmlspecialchars($partai["nm_merah"] ?? ''); ?>">
                    </div>

                    <div class="col-md-6 bg-danger rounded p-2">
                        <label for="kontingen_merah" class="form-label">Kontingen</label>
                        <input type="text" class="form-control bg-light text-muted" name="kontingen_merah" id="kontingen_merah"
                            value="<?php echo htmlspecialchars($partai["kontingen_merah"] ?? ''); ?>">
                    </div>

                    <!-- Pesilat Sudut Biru -->
                    <div class="col-12 mt-4">
                        <h6 class="text-primary text-uppercase">
                            <i class="fas fa-user me-2"></i>Sudut Biru
                        </h6>
                    </div>

                    <div class="col-md-6 bg-primary rounded p-2">
                        <label for="nm_biru" class="form-label">Nama Pesilat</label>
                        <input type="text" class="form-control bg-light text-muted" name="nm_biru" id="nm_biru"
                            value="<?php echo htmlspecialchars($partai["nm_biru"] ?? ''); ?>">
                    </div>

                    <div class="col-md-6 bg-primary rounded p-2">
                        <label for="kontingen_biru" class="form-label">Kontingen</label>
                        <input type="text" class="form-control bg-light text-muted" name="kontingen_biru" id="kontingen_biru"
                            value="<?php echo htmlspecialchars($partai["kontingen_biru"] ?? ''); ?>">
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select class="form-select" name="status" id="status">
                            <?php
                            $status_options = ['Belum Dimulai', 'Berlangsung', 'Selesai'];
                            foreach ($status_options as $option) {
                                $selected = ($partai["status"] ?? '') == $option ? 'selected' : '';
                                echo "<option value='$option' $selected>$option</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Pemenang -->
                    <div class="col-md-6">
                        <label for="pemenang" class="form-label fw-bold">Pemenang</label>
                        <select class="form-select" name="pemenang" id="pemenang">
                            <option value="">Belum Ada</option>
                            <option value="Merah" <?php echo (($partai["pemenang"] ?? '') == 'Merah') ? 'selected' : ''; ?>>Sudut Merah</option>
                            <option value="Biru" <?php echo (($partai["pemenang"] ?? '') == 'Biru') ? 'selected' : ''; ?>>Sudut Biru</option>
                            <option value="Draw" <?php echo (($partai["pemenang"] ?? '') == 'Draw') ? 'selected' : ''; ?>>Draw</option>
                        </select>
                    </div>

                    <!-- Aktif -->
                    <div class="col-md-6">
                        <label for="aktif" class="form-label fw-bold">Aktif</label>
                        <select class="form-select" name="aktif" id="aktif">
                            <option value="0" <?php echo (($partai["aktif"] ?? '') == "0") ? 'selected' : ''; ?>>Tidak Aktif</option>
                            <option value="1" <?php echo (($partai["aktif"] ?? '') == "1") ? 'selected' : ''; ?>>Aktif</option>
                        </select>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-between">
                            <a href="users.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" onclick="return confirmUpdate()" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk konfirmasi update -->
<script>
    function confirmUpdate() {
        return confirm('Apakah Anda yakin ingin menyimpan perubahan data partai?');
    }
</script>

<!-- Style tambahan -->
<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    .form-label {
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        padding: 0.5rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.15s ease-in-out;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5c636a;
        border-color: #565e64;
    }

    .border-bottom {
        border-bottom: 2px solid #dee2e6 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-primary {
        color: #0d6efd !important;
    }
</style>