            <div class="row">
                <div class="col-12 text-light">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            require_once("functions/function.php");
                            include 'includes/connection.php';

                            if (isset($_GET['status']) && isset($_GET['message'])) {
                                $status = $_GET['status'];
                                $message = $_GET['message'];

                                echo "
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				Swal.fire({
					icon: '$status',
					title: '" . ($status == 'success' ? 'Berhasil!' : 'Gagal!') . "',
					text: '$message',
				});
			});
		</script>";
                            }

                            $sqlJuri = "SELECT * FROM wasit_juri ORDER BY id_juri ASC";
                            $dataJuri = mysqli_query($koneksi, $sqlJuri);
                            ?>

                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                            <div class="row-fluid sortable">
                                <div class="box span12">
                                    <div class="box-content">
                                        <div class="text-right mb-3" style="margin-bottom: 15px;">
                                            <button id="btnAddJuri" class="btn btn-primary">
                                                <i class="mdi mdi-account-plus"></i> TAMBAH
                                            </button>
                                        </div>

                                        <div id="juriTable">
                                            <table class="table table-bordered datatable">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">No</th>
                                                        <th>Nama User</th>
                                                        <th>Password</th>
                                                        <th width="10%">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 0;
                                                    while ($juri = mysqli_fetch_array($dataJuri)) {
                                                        $no++; ?>
                                                        <tr id="row_<?php echo $juri['id_juri']; ?>">
                                                            <td><?php echo $no; ?></td>
                                                            <td id="name_<?php echo $juri['id_juri']; ?>"><?php echo htmlspecialchars($juri['nm_juri']); ?></td>
                                                            <td id="pass_<?php echo $juri['id_juri']; ?>"><?php echo htmlspecialchars($juri['pass_juri']); ?></td>
                                                            <td class="center">
                                                                <button class="btn btn-warning" id="edit_<?php echo $juri['id_juri']; ?>" onclick="editRow(<?php echo $juri['id_juri']; ?>, '<?php echo addslashes($juri['nm_juri']); ?>', '<?php echo addslashes($juri['pass_juri']); ?>')">
                                                                    <i class="mdi mdi-grease-pencil"></i>
                                                                </button>
                                                                <a class="btn btn-danger" href="#" onclick="confirmDelete(<?php echo $juri['id_juri']; ?>, '<?php echo addslashes($juri['nm_juri']); ?>')">
                                                                    <i class="mdi mdi-close-circle"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div id="addJuriForm" style="display: none;">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title text-muted border-bottom pb-3">
                                                        Masukkan data user baru untuk Juri, Dewan, atau Operator
                                                    </h6>

                                                    <form id="formAddJuri" class="mt-4">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-12">
                                                                <div class="form-group">
                                                                    <label>Nama Juri</label>
                                                                    <input type="text" class="form-control text-muted"
                                                                        id="nm_juri" name="nm_juri"
                                                                        placeholder="Contoh: Juri Lomba A" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-12">
                                                                <div class="form-group">
                                                                    <label>Password</label>
                                                                    <input type="text" class="form-control text-muted"
                                                                        id="pass_juri" name="pass_juri"
                                                                        placeholder="Minimal 6 karakter" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-4">
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-end">
                                                                    <button type="button" id="btnCancelAdd" class="btn btn-light me-1">
                                                                        <i class="mdi mdi-arrow-left mr-1"></i> Kembali
                                                                    </button>
                                                                    <button type="submit" class="btn btn-primary">
                                                                        <i class="mdi mdi-content-save mr-1"></i> Simpan Data
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="mt-4"></div> <!-- Spacer sebelum tabel -->
                                        </div>
                                    </div>
                                </div><!--/span-->
                            </div><!--/row-->
                            <script src="js/jquery-3.6.0.min.js"></script>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('.datatable').DataTable();
                                });

                                document.getElementById('btnAddJuri').addEventListener('click', function() {
                                    document.getElementById('juriTable').style.display = 'none';
                                    document.getElementById('addJuriForm').style.display = 'block';
                                    document.getElementById('btnAddJuri').style.display = 'none';
                                });

                                document.getElementById('btnCancelAdd').addEventListener('click', function() {
                                    document.getElementById('juriTable').style.display = 'block';
                                    document.getElementById('addJuriForm').style.display = 'none';
                                    document.getElementById('btnAddJuri').style.display = 'inline-block';
                                    document.getElementById('formAddJuri').reset();
                                });

                                document.getElementById('formAddJuri').addEventListener('submit', function(e) {
                                    e.preventDefault();

                                    var nm_juri = document.getElementById('nm_juri').value;
                                    var pass_juri = document.getElementById('pass_juri').value;

                                    if (nm_juri.trim() === '') {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: 'Nama juri tidak boleh kosong!'
                                        });
                                        return;
                                    }

                                    if (pass_juri.trim() === '') {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: 'Password tidak boleh kosong!'
                                        });
                                        return;
                                    }

                                    Swal.fire({
                                        text: 'Apakah yakin ingin menambah juri ' + nm_juri + '?',
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Ya, Tambah!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            var xhr = new XMLHttpRequest();
                                            xhr.open("POST", "pages/proses/add_juri.php", true);
                                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                            xhr.onreadystatechange = function() {
                                                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                                                    var response = JSON.parse(this.responseText);
                                                    if (response.success) {
                                                        Swal.fire({
                                                            icon: 'success',
                                                            text: 'Berhasil menambah juri ' + nm_juri,
                                                            confirmButtonText: 'OK'
                                                        }).then((result) => {
                                                            // Refresh halaman setelah tambah berhasil
                                                            window.location.reload();
                                                        });
                                                    } else {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Gagal!',
                                                            text: 'Gagal menambah juri: ' + response.message
                                                        });
                                                    }
                                                }
                                            };
                                            xhr.send("nm_juri=" + encodeURIComponent(nm_juri) + "&pass_juri=" + encodeURIComponent(pass_juri));
                                        }
                                    });
                                });

                                function confirmDelete(id, name) {
                                    Swal.fire({
                                        text: 'Apakah yakin ingin menghapus  ' + name + '?',
                                        icon: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: '#d33',
                                        cancelButtonColor: '#3085d6',
                                        confirmButtonText: 'Ya, Hapus!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "pages/proses/delete_juri.php?delete_id=" + id;
                                        }
                                    });
                                }

                                function editRow(id, name, password) {
                                    var editButton = document.getElementById('edit_' + id);

                                    // Check current state by checking the icon class
                                    var isEditMode = editButton.innerHTML.includes('mdi-grease-pencil');

                                    if (isEditMode) {
                                        // Switch to edit mode
                                        document.getElementById('name_' + id).innerHTML = '<input type="text" id="input_name_' + id + '" value="' + name + '" class="form-control input-medium text-muted">';
                                        document.getElementById('pass_' + id).innerHTML = '<input type="text" id="input_pass_' + id + '" value="' + password + '" class="form-control input-medium text-muted">';

                                        // Change button to save mode
                                        editButton.innerHTML = '<i class="mdi mdi-check"></i>';
                                        editButton.className = 'btn btn-success';

                                        // Remove onclick attribute temporarily to prevent re-triggering
                                        editButton.onclick = function() {
                                            saveRow(id);
                                        };
                                    } else {
                                        // Call save function
                                        saveRow(id);
                                    }
                                }

                                // Separate function for saving
                                function saveRow(id) {
                                    var newName = document.getElementById('input_name_' + id).value;
                                    var newPass = document.getElementById('input_pass_' + id).value;
                                    var originalName = document.getElementById('input_name_' + id).defaultValue;

                                    if (newName.trim() === '') {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: 'Nama juri tidak boleh kosong!'
                                        });
                                        return;
                                    }

                                    if (newPass.trim() === '') {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: 'Password tidak boleh kosong!'
                                        });
                                        return;
                                    }

                                    Swal.fire({
                                        text: 'Apakah yakin ingin mengubah data juri ' + originalName + '?',
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Ya, Ubah!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            var xhr = new XMLHttpRequest();
                                            xhr.open("POST", "pages/proses/update_juri.php", true);
                                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                            xhr.onreadystatechange = function() {
                                                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                                                    var response = JSON.parse(this.responseText);
                                                    if (response.success) {
                                                        // Update the display
                                                        document.getElementById('name_' + id).innerHTML = newName;
                                                        document.getElementById('pass_' + id).innerHTML = newPass;

                                                        // Reset button to edit mode
                                                        var editButton = document.getElementById('edit_' + id);
                                                        editButton.innerHTML = '<i class="mdi mdi-grease-pencil"></i>';
                                                        editButton.className = 'btn btn-warning';

                                                        // Restore the onclick function
                                                        editButton.onclick = function() {
                                                            editRow(id, newName, newPass);
                                                        };

                                                        Swal.fire({
                                                            icon: 'success',
                                                            html: 'Berhasil mengupdate data juri ' + newName,
                                                            confirmButtonColor: '#3085d6'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                window.location.href = `?page=users`;
                                                            }
                                                        });
                                                    } else {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Gagal!',
                                                            text: 'Gagal mengupdate data juri: ' + response.message
                                                        });
                                                    }
                                                }
                                            };
                                            xhr.send("id_juri=" + id + "&nm_juri=" + encodeURIComponent(newName) + "&pass_juri=" + encodeURIComponent(newPass));
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            </div>