<?php
require_once("functions/function.php");
get_header();
get_sidebar();
get_bread();
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
        <div class="box-header" data-original-title>
            <h2><i class="halflings-icon white user"></i><span class="break"></span>Data User</h2>
            <div class="box-icon">
                <a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
                <a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
                <a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
            </div>
        </div>
        <div class="box-content">
            <div class="text-right mb-3" style="margin-bottom: 15px;">
                <button id="btnAddJuri" class="btn btn-primary">
                    <i class="halflings-icon white plus"></i> Tambah User
                </button>
            </div>

            <div id="juriTable">
                <table class="table table-striped table-bordered datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama User</th>
                            <th>Password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0;
                        while ($juri = mysqli_fetch_array($dataJuri)) {
                            $no++; ?>
                            <tr id="row_<?php echo $juri['id_juri']; ?>">
                                <td><?php echo $no; ?></td>
                                <td id="name_<?php echo $juri['id_juri']; ?>"><?php echo $juri['nm_juri']; ?></td>
                                <td id="pass_<?php echo $juri['id_juri']; ?>"><?php echo $juri['pass_juri']; ?></td>
                                <td class="center">
                                    <a class="btn btn-success" href="#" id="edit_<?php echo $juri['id_juri']; ?>" onclick="editRow(<?php echo $juri['id_juri']; ?>, '<?php echo $juri['nm_juri']; ?>', '<?php echo $juri['pass_juri']; ?>')">
                                        <i class="halflings-icon white edit"></i>
                                    </a>
                                    <a class="btn btn-danger" href="#" onclick="confirmDelete(<?php echo $juri['id_juri']; ?>, '<?php echo $juri['nm_juri']; ?>')">
                                        <i class="halflings-icon white trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div id="addJuriForm" style="display: none;">
                <form id="formAddJuri" class="form-horizontal">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="nm_juri">Nama Juri</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="nm_juri" name="nm_juri" required>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pass_juri">Password</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="pass_juri" name="pass_juri" required>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" id="btnCancelAdd" class="btn">Batal</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div><!--/span-->
</div><!--/row-->

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
            title: 'Konfirmasi Tambah',
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
                xhr.open("POST", "add_juri.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                        var response = JSON.parse(this.responseText);
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
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
            title: 'Konfirmasi Hapus',
            text: 'Apakah yakin ingin menghapus  ' + name + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "data_juri.php?delete_id=" + id;
            }
        });
    }

    function editRow(id, name, password) {
        var editButton = document.getElementById('edit_' + id);

        if (editButton.innerHTML.includes('edit')) {
            document.getElementById('name_' + id).innerHTML = '<input type="text" id="input_name_' + id + '" value="' + name + '" class="input-medium">';
            document.getElementById('pass_' + id).innerHTML = '<input type="text" id="input_pass_' + id + '" value="' + password + '" class="input-medium">';

            editButton.innerHTML = '<i class="halflings-icon white ok"></i>';
            editButton.className = 'btn btn-primary';
        } else {
            var newName = document.getElementById('input_name_' + id).value;
            var newPass = document.getElementById('input_pass_' + id).value;

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
                title: 'Konfirmasi Perubahan',
                text: 'Apakah yakin ingin mengubah data juri ' + name + '?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "update_juri.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                            var response = JSON.parse(this.responseText);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Berhasil mengupdate data juri ' + newName
                                });

                                document.getElementById('name_' + id).innerHTML = newName;
                                document.getElementById('pass_' + id).innerHTML = newPass;

                                editButton.innerHTML = '<i class="halflings-icon white edit"></i>';
                                editButton.className = 'btn btn-success';
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
    }
</script>

<?php
get_footer();
?>