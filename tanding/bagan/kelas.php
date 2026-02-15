<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Pilih Kelas</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h3>Pilih Kelas</h3>
    <select id="selectKelas">
        <option value="">-- Pilih Kelas --</option>
    </select>

    <script>
        const ws = new WebSocket('ws://localhost:3000');

        ws.onopen = () => {
            console.log('Connected to WebSocket server');
        };

        // Ambil data kelas dari server PHP
        $.getJSON('get_kelas.php', function(response) {
            if (response.success) {
                response.kelas.forEach(kelas => {
                    $('#selectKelas').append(`<option value="${kelas}">${kelas}</option>`);
                });
            } else {
                alert('Gagal memuat data kelas');
            }
        });

        $('#selectKelas').on('change', function() {
            const kelas = $(this).val();
            if (kelas) {
                ws.send(JSON.stringify({
                    type: 'selectKelas',
                    kelas: kelas
                }));
                console.log('Kirim kelas:', kelas);
            }
        });
    </script>
</body>

</html>