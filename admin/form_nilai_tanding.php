<?php
	require_once("functions/function.php");
	get_header();
	get_sidebar();
	get_bread_two();
	include("includes/connection.php");
?>
<div class="row-fluid sortable">		
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white download"></i><span class="break"></span>Cetak Form Nilai Tanding</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
<form id="generateForm" class="form-inline">
    <div class="form-row" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <div class="form-group">
            <select id="form_type" class="form-control" required>
                <option value="">-- Pilih Hasil Partai --</option>
                <option value="kategori_pertandingan">Kategori Pertandingan</option>
                <option value="dewan_score">Dewan Score</option>
                <option value="score_juri">Score Juri</option>
            </select>
        </div>
        <div class="form-group">
            <select id="id_partai" class="form-control" required>
                <option value="">-- Pilih Partai --</option>
                <?php
                include "../backend/includes/connection.php";
                $sql = mysqli_query($koneksi, "SELECT * FROM jadwal_tanding ORDER BY id_partai ASC");
                while($row = mysqli_fetch_assoc($sql)) {
                    echo '<option value="'.$row['id_partai'].'">'.$row['gelanggang'].' - '.$row['partai'].'</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <button  type="submit"  class="btn btn-info">Generate</button>
        </div>
    </div>
</form>
<script>
document.getElementById("generateForm").addEventListener("submit", function(e) {
    e.preventDefault(); // Hindari submit default

    const formType = document.getElementById("form_type").value;
    const idPartai = document.getElementById("id_partai").value;

    if (formType && idPartai) {
        // Tambahkan parameter &pdf=1 supaya langsung generate PDF
        const url = `${formType}.php?id_partai=${idPartai}&pdf=1`;
        window.open(url, "_blank"); // buka tab baru
    } else {
        alert("Silakan pilih jenis formulir dan partai terlebih dahulu.");
    }
});
</script>
		</div>
	</div><!--/span-->
</div><!--/row-->
<script>
function updateFormAction() {
    const targetPage = document.getElementById("target_page").value;
    const form = document.querySelector("form");
    form.action = targetPage;
}
</script>
<?php
	get_footer();
?>
