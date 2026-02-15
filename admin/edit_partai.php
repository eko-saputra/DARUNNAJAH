<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<i class="fas fa-edit mr-2"></i>Ubah Data Partai
				</h4>
				<div class="card-header-action">
					<a href="users.php" class="btn btn-icon btn-danger">
						<i class="fas fa-times"></i>
					</a>
				</div>
			</div>

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

				<form class="form-horizontal" method="post" action="admin_do_edit_partai.php">
					<!-- ID Partai -->
					<div class="form-group row">
						<label class="col-sm-3 col-form-label">ID Partai</label>
						<div class="col-sm-9">
							<input type="text"
								class="form-control"
								name="id_partai"
								readonly
								value="<?php echo $id_partai; ?>">
							<small class="form-text text-muted">ID partai tidak dapat diubah</small>
						</div>
					</div>

					<!-- Tanggal dan Gelanggang -->
					<div class="form-row">
						<div class="form-group col-md-6">
							<label>Tanggal</label>
							<input type="text"
								class="form-control"
								name="tgl"
								value="<?php echo $partai['tgl']; ?>">
						</div>
						<div class="form-group col-md-6">
							<label>Gelanggang</label>
							<input type="text"
								class="form-control"
								name="gelanggang"
								value="<?php echo $partai['gelanggang']; ?>">
						</div>
					</div>

					<!-- No Partai dan Babak -->
					<div class="form-row">
						<div class="form-group col-md-6">
							<label>No Partai</label>
							<input type="text"
								class="form-control"
								name="partai"
								value="<?php echo $partai['partai']; ?>">
						</div>
						<div class="form-group col-md-6">
							<label>Babak</label>
							<input type="text"
								class="form-control"
								name="babak"
								value="<?php echo $partai['babak']; ?>">
						</div>
					</div>

					<!-- Kelompok -->
					<div class="form-group">
						<label>Kelompok</label>
						<input type="text"
							class="form-control"
							name="kelas"
							value="<?php echo $partai['kelas']; ?>">
					</div>

					<!-- Sudut Merah -->
					<div class="card bg-light-red border-red mb-3">
						<div class="card-header bg-red text-white">
							<i class="fas fa-user mr-2"></i>Sudut Merah
						</div>
						<div class="card-body">
							<div class="form-row">
								<div class="form-group col-md-6">
									<label>Nama Pesilat</label>
									<input type="text"
										class="form-control"
										name="nm_merah"
										value="<?php echo $partai['nm_merah']; ?>">
								</div>
								<div class="form-group col-md-6">
									<label>Kontingen</label>
									<input type="text"
										class="form-control"
										name="kontingen_merah"
										value="<?php echo $partai['kontingen_merah']; ?>">
								</div>
							</div>
						</div>
					</div>

					<!-- Sudut Biru -->
					<div class="card bg-light-blue border-blue mb-3">
						<div class="card-header bg-blue text-white">
							<i class="fas fa-user mr-2"></i>Sudut Biru
						</div>
						<div class="card-body">
							<div class="form-row">
								<div class="form-group col-md-6">
									<label>Nama Pesilat</label>
									<input type="text"
										class="form-control"
										name="nm_biru"
										value="<?php echo $partai['nm_biru']; ?>">
								</div>
								<div class="form-group col-md-6">
									<label>Kontingen</label>
									<input type="text"
										class="form-control"
										name="kontingen_biru"
										value="<?php echo $partai['kontingen_biru']; ?>">
								</div>
							</div>
						</div>
					</div>

					<!-- Status dan Pemenang -->
					<div class="form-row">
						<div class="form-group col-md-6">
							<label>Status</label>
							<input type="text"
								class="form-control"
								name="status"
								value="<?php echo $partai['status']; ?>">
						</div>
						<div class="form-group col-md-6">
							<label>Pemenang</label>
							<input type="text"
								class="form-control"
								name="pemenang"
								value="<?php echo $partai['pemenang']; ?>">
						</div>
					</div>

					<!-- Aktif -->
					<div class="form-group">
						<label>Aktif</label>
						<select class="form-control" name="aktif" id="aktif">
							<option value="0" <?php if ($partai["aktif"] == "0") echo "selected"; ?>>Tidak Aktif</option>
							<option value="1" <?php if ($partai["aktif"] == "1") echo "selected"; ?>>Aktif</option>
						</select>
					</div>

					<!-- Tombol Aksi -->
					<div class="form-group row mt-4">
						<div class="col-sm-12">
							<button type="submit"
								onclick="return confirmUpdate()"
								class="btn btn-primary btn-lg btn-block">
								<i class="fas fa-save mr-2"></i>Simpan Perubahan
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- CSS untuk warna -->
<style>
	.bg-red {
		background-color: #dc3545 !important;
	}

	.bg-blue {
		background-color: #007bff !important;
	}

	.border-red {
		border-color: #dc3545 !important;
	}

	.border-blue {
		border-color: #007bff !important;
	}

	.bg-light-red {
		background-color: #f8d7da !important;
	}

	.bg-light-blue {
		background-color: #d1ecf1 !important;
	}

	.text-red {
		color: #dc3545 !important;
	}

	.text-blue {
		color: #007bff !important;
	}
</style>

<!-- JavaScript untuk konfirmasi -->
<script>
	function confirmUpdate() {
		return confirm('Apakah Anda yakin ingin menyimpan perubahan data partai?');
	}
</script>