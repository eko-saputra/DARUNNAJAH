<table id="jadwalTable" class="table table-bordered">
    <thead>
        <tr>
            <th>Gelanggang</th>
            <th>Partai</th>
            <th>Babak</th>
            <th>Kelas</th>
            <th>Pesilat Biru</th>
            <th>Pesilat Merah</th>
            <th>Status</th>
            <th>Pemenang</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <!-- data akan diisi oleh JS -->
    </tbody>
</table>

<script>
    function loadJadwal(status) {
        $.ajax({
            url: 'get_jadwal.php',
            type: 'GET',
            data: {
                status: status
            },
            dataType: 'json',
            success: function(data) {
                let tbody = '';
                data.forEach(jadwal => {
                    tbody += `<tr>
                    <td rowspan="2" class="text-center align-middle">${jadwal.gelanggang}</td>
                    <td rowspan="2" class="text-center align-middle">${jadwal.partai}</td>
                    <td rowspan="2" class="text-center align-middle">${jadwal.status_babak != '' ? jadwal.status_babak : jadwal.babak}</td>
                    <td rowspan="2" class="align-middle">${jadwal.kelas}</td>
                    <td class="bg-primary bg-gradient text-white text-uppercase">${jadwal.nm_biru}</td>
                    <td class="bg-danger bg-gradient text-white text-uppercase">${jadwal.nm_merah}</td>
                    <td rowspan="2" class="text-center align-middle">${jadwal.status.charAt(0).toUpperCase() + jadwal.status.slice(1)}</td>
                    <td rowspan="2" class="text-center align-middle">
                        ${jadwal.pemenang.toLowerCase() === 'biru' ? '<span class="badge bg-primary p-2">Biru</span>' :
                          jadwal.pemenang.toLowerCase() === 'merah' ? '<span class="badge bg-danger p-2">Merah</span>' :
                          '<span class="badge bg-secondary -2">-</span>'}
                    </td>
                    <td rowspan="2" class="text-center align-middle">
                        ${jadwal.status === 'selesai' ? 'Pertandingan Selesai' :
                          `<a href="operator.php?id_partai=${jadwal.id_partai}" class="btn btn-success bg-gradient btn-sm">Masuk</a>`}
                    </td>
                </tr>
                <tr>
                    <td class="bg-light bg-gradient text-dark text-uppercase">${jadwal.kontingen_biru}</td>
                    <td class="bg-light bg-gradient text-dark text-uppercase">${jadwal.kontingen_merah}</td>
                </tr>`;
                });
                $('#jadwalTable tbody').html(tbody);
            },
            error: function(xhr, status, err) {
                console.error('Gagal load jadwal:', err);
            }
        });
    }

    // Panggil loadJadwal saat halaman siap
    $(document).ready(function() {
        loadJadwal('-'); // misal status awal 'selesai'
    });
</script>