<?php
	require_once("functions/function.php");
	get_header();
	get_sidebar();
	get_bread();
	include 'includes/connection.php';
	
	if(isset($_GET['status']) && isset($_GET['message'])) {
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
	
	$sqlJuri = "SELECT * FROM ip_server ORDER BY id_ip ASC";
	$dataJuri = mysqli_query($koneksi, $sqlJuri);
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="fas fa-server"></i><span class="break"></span>Data Alamat Ip</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
			<div class="text-right mb-3" style="margin-bottom: 15px;">
				<button id="btnAddJuri" class="btn btn-primary">
					<i class="halflings-icon white plus"></i> Tambah Alamat Ip
				</button>
			</div>
			
			<div id="ipTable">
				<table class="table table-striped table-bordered bootstrap-datatable datatable">
					<thead>
						<tr>
							<th>No</th>
							<th>Alamat Ip</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $no=0; while($juri = mysqli_fetch_array($dataJuri)) { $no++; ?>
						<tr id="row_<?php echo $juri['id_ip']; ?>">
							<td><?php echo $no; ?></td>
							<td id="name_<?php echo $juri['id_ip']; ?>"><?php echo $juri['ip']; ?></td>
							<td class="center">
							<a class="btn btn-success" href="#" id="edit_<?php echo $juri['id_ip']; ?>" onclick="editRow(<?php echo $juri['id_ip']; ?>, '<?php echo $juri['ip']; ?>', '')">
									<i class="halflings-icon white edit"></i>
								</a>
								<a class="btn btn-danger" href="#" onclick="confirmDelete(<?php echo $juri['id_ip']; ?>, '<?php echo $juri['ip']; ?>')">
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
							<label class="control-label" for="ip">Alamat Ip</label>
							<div class="controls">
								<input type="text" class="input-xlarge" id="ip" name="ip" required>
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
document.getElementById('btnAddJuri').addEventListener('click', function() {
    document.getElementById('ipTable').style.display = 'none';
    document.getElementById('addJuriForm').style.display = 'block';
    document.getElementById('btnAddJuri').style.display = 'none';
});

document.getElementById('btnCancelAdd').addEventListener('click', function() {
    document.getElementById('ipTable').style.display = 'block';
    document.getElementById('addJuriForm').style.display = 'none';
    document.getElementById('btnAddJuri').style.display = 'inline-block';
    document.getElementById('formAddJuri').reset();
});

document.getElementById('formAddJuri').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var ip = document.getElementById('ip').value;
    
    if(ip.trim() === '') {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Nama juri tidak boleh kosong!'
        });
        return;
    }
    
    Swal.fire({
        title: 'Konfirmasi Tambah',
        text: 'Apakah yakin ingin menambah Alamat ip ' + ip + '?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Tambah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "add_ip.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    var response = JSON.parse(this.responseText);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Berhasil Menambahkan Aalamat Ip ' + ip,
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
            xhr.send("ip=" + encodeURIComponent(ip));
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
            window.location.href = "data_ip.php?delete_id=" + id;
        }
    });
}

function editRow(id, name) {
    var editButton = document.getElementById('edit_' + id);
    
    if (editButton.innerHTML.includes('edit')) {
        document.getElementById('name_' + id).innerHTML =
            '<input type="text" id="input_name_' + id + '" value="' + name + '" class="input-medium">';
        
        editButton.innerHTML = '<i class="halflings-icon white ok"></i>';
        editButton.className = 'btn btn-primary';
    } else {
        var newName = document.getElementById('input_name_' + id).value;
        
        if (newName.trim() === '') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'IP tidak boleh kosong!'
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Perubahan',
            text: 'Yakin ingin ubah IP ' + name + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "update_ip.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                        var response = JSON.parse(this.responseText);
                        if (response.success) {
                            Swal.fire('Berhasil', 'IP berhasil diubah', 'success');
                            document.getElementById('name_' + id).innerHTML = newName;
                            editButton.innerHTML = '<i class="halflings-icon white edit"></i>';
                            editButton.className = 'btn btn-success';
                        } else {
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    }
                };
                xhr.send("id_juri=" + id + "&ip=" + encodeURIComponent(newName));
            }
        });
    }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	if (window.location.search.includes('status=')) {
		window.history.replaceState(null, null, window.location.pathname);
	}
});
</script>

<?php
	get_footer();
?>