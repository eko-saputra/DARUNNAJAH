<?php
include "../../../backend/includes/connection.php";

$id_partai = mysqli_real_escape_string($koneksi, $_GET["id_partai"]);
$sqljadwal = "SELECT * FROM jadwal_tgr WHERE id_partai='$id_partai'";
$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
$jadwal = mysqli_fetch_array($jadwal_tanding);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Operator Pertandingan</title>
  <link rel="shortcut icon" href="../../../assets/img/LogoIPSI.png" />
  <link rel="stylesheet" href="../../../assets/bootstrap/dist/css/bootstrap.min.css" />
  <style>
    #openfull,
    #exitfull {
      background: none;
      border: none;
      cursor: pointer;
      width: 30px;
      height: 55px;
    }

    #exitfull {
      display: none;
    }
  </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
  <div class="container-fluid">
    <h1 class="text-muted text-center mb-5"><b>PERTANDINGAN</b></h1>
    <table class="table table-bordered table-striped">
      <tr>
        <td class="text-end">
          <div class="row">
            <div class="col-9 d-flex align-items-center">
              <button class="btn btn-secondary bg-gradient tukar-partai me-1" onclick="tukarpartai()">← Tukar
                Partai</button>
              <!-- <button class="btn btn-secondary bg-gradient refresh-partai me-1" onclick="refreshpartai()">Refresh Partai
                Juri</button> -->
              <a href="../monitor/view_tunggal.html" target="_blank" class="btn btn-primary bg-gradient">🖥️ Layar
                Monitor</a>
              <button id="openfull" onclick="openFullscreen();" class="btn btn-outline-secondary p-1">
                <svg width="24" height="24" viewBox="0 0 24 24">
                <path d="M14,5H19V10H17V7H14V5M17,14H19V19H14V17H17V14M10,17V19H5V14H7V17H10Z" />

                </svg>
              </button>
              <button id="exitfull" onclick="closeFullscreen();" class="btn btn-outline-secondary p-1">
                <svg width="24" height="24" viewBox="0 0 24 24">
                  <path fill="#2d2d2d"
                    d="M14,14H19V16H16V19H14V14M5,14H10V19H8V16H5V14M8,5H10V10H5V8H8V5M19,8V10H14V5H16V8H19Z" />
                </svg>
              </button>
              <div>
                <div class="row d-flex align-items-center justif-content-center ms-3"> 
                  <div class="col">
                    <input type="time" id="input-time" class="form-control" onchange="setRemaining()">
                  </div>
                </div>
              </div>

            </div>
            <div class="col-3">
              <h5>Golongan :
                <?= htmlspecialchars($jadwal['kategori']) . "/" . htmlspecialchars($jadwal['golongan'])."-".htmlspecialchars($jadwal['id_partai']); ?>
              </h5>
            </div>
          </div>
        </td>
      </tr>

      <tr>
        <td colspan="5" class="text-center bg-light">
          <div id="waktu" style="font-size: 50px; font-weight: bold; letter-spacing: 1px;">02:00</div>
        </td>
      </tr>

      <tr>
        <td colspan="5" class="text-center py-4">
          <div id="button-controls">
            <button id="btn-start" class="btn btn-success fw-bold rounded-pill px-4 py-3 me-2"
              onclick="startTimer()">Start</button>
            <button id="btn-pause" class="btn btn-warning fw-bold rounded-pill px-4 py-3 me-2"
              onclick="pauseTimer()">Pause</button>
            <button id="btn-resume" class="btn btn-info fw-bold rounded-pill px-4 py-3 me-2"
              onclick="resumeTimer()">Resume</button>
            <button id="btn-stop" class="btn btn-danger fw-bold rounded-pill px-4 py-3"
              onclick="stopTimer()">Stop</button>
          </div>
        </td>
      </tr>
    </table>

    <table class="table table-bordered table-striped">
      <tr class="text-center">
        <td><strong
            class="text-primary text-uppercase"><?= htmlspecialchars($jadwal['nm_biru'] ?? '-') ?></strong><br><small
            class="text-muted text-uppercase"><?= htmlspecialchars($jadwal['kontingen_biru'] ?? '-') ?></small><br>
          <button class="btn btn-primary bg-gradient text-light text-uppercase btn-sm pilih-button" data-sudut="BIRU">Pilih</button></td>
        <td><strong
            class="text-danger text-uppercase"><?= htmlspecialchars($jadwal['nm_merah'] ?? '-') ?></strong><br><small
            class="text-muted text-uppercase"><?= htmlspecialchars($jadwal['kontingen_merah'] ?? '-') ?></small><br>
            <button class="btn btn-danger bg-gradient text-light text-uppercase btn-sm pilih-button" data-sudut="MERAH">Pilih</button></td>
        <td valign="middle"><?= htmlspecialchars($jadwal['babak'] ?? '-') ?></td>
      </tr>
    </table>
  </div>

<script src="../../../assets/jquery/jquery.min.js"></script>
<script>
    const waktuElem = $('#waktu');
    const btnStart = $('#btn-start');
    const btnPause = $('#btn-pause');
    const btnResume = $('#btn-resume');
    const btnStop = $('#btn-stop');
    const pilihButtons = $('.pilih-button');
    const is_login = localStorage.getItem('is_login');
    const operator = localStorage.getItem('operator');
    const nama_operator = localStorage.getItem('nama_operator');
    localStorage.setItem('waktu','03:00');
    localStorage.setItem('timer',180);

    let remaining = 180; // Default 2 minutes

    // Store static partai data from PHP for easy access
    const staticPartaiData = {
        id_partai: "<?= htmlspecialchars($jadwal['id_partai']) ?>",
        partai: "<?= htmlspecialchars($jadwal['partai'] ?? '') ?>",
        kategori: "<?= htmlspecialchars($jadwal['kategori'] ?? '') ?>",
        golongan: "<?= htmlspecialchars($jadwal['golongan'] ?? '') ?>",
        babak: "<?= htmlspecialchars($jadwal['babak'] ?? '') ?>",
        nm_biru: "<?= htmlspecialchars($jadwal['nm_biru'] ?? '') ?>",
        kontingen_biru: "<?= htmlspecialchars($jadwal['kontingen_biru'] ?? '') ?>",
        nm_merah: "<?= htmlspecialchars($jadwal['nm_merah'] ?? '') ?>",
        kontingen_merah: "<?= htmlspecialchars($jadwal['kontingen_merah'] ?? '') ?>"
    };

    // Initialize waktu and input-time on page load
    document.addEventListener('DOMContentLoaded', () => {
        const savedTime = localStorage.getItem('waktu');
        if (savedTime) {
            document.getElementById('input-time').value = savedTime;
            const [minutes, seconds] = savedTime.split(':').map(Number);
            remaining = (minutes * 60) + seconds;
        } else {
            localStorage.setItem('waktu', '03:00');
            document.getElementById('input-time').value = '03:00';
        }
        updateDisplay(); // Update display with initial remaining time
        updateButtons('stopped'); // Set initial button state

        // The logic for checking selectedPartai and enabling/disabling buttons
        // should remain here, but the refreshpartai() call moves to ws.onopen
        const selectedPartaiData = JSON.parse(localStorage.getItem('currentPartai'));
        if (selectedPartaiData && selectedPartaiData.id_partai === staticPartaiData.id_partai) {
            const selectedSudutOnLoad = selectedPartaiData.sudut;
            pilihButtons.each(function() {
                if ($(this).data('sudut') === selectedSudutOnLoad) {
                    $(this).text('✔️');
                    $(this).prop('disabled', true);
                } else {
                    $(this).text('Pilih');
                    $(this).prop('disabled', false);
                }
            });
            // Don't call refreshpartai() here directly
            // It will be called in ws.onopen once connection is ready
        } else {
            pilihButtons.text('Pilih').prop('disabled', false);
            btnStart.prop('disabled', true);
        }
    });

    function setRemaining() {
        const input = document.getElementById('input-time');
        const timeValue = input.value;

        if (timeValue) {
            const [minutes, seconds] = timeValue.split(':').map(Number);
            remaining = (minutes * 60) + seconds;
            console.log('Remaining time set to:', remaining, 'seconds');
            localStorage.setItem('timer', remaining);
            localStorage.setItem('waktu', timeValue);
            updateDisplay(); // Update display immediately after setting time
        } else {
            alert('Pilih waktu terlebih dahulu!');
        }
    }

    const ws = new WebSocket('ws://192.168.100.133:3000');

    // Send partai data to server after WebSocket connection is established
    ws.onopen = () => {
        console.log("Server terhubung.");
        // Now that the WebSocket is open, we can safely send data
        const selectedPartaiData = JSON.parse(localStorage.getItem('currentPartai'));
        if (selectedPartaiData && selectedPartaiData.id_partai === staticPartaiData.id_partai) {
            refreshpartai(); // Call refreshpartai() only when WebSocket is open
        }
    };

    ws.onmessage = (event) => {
        const data = JSON.parse(event.data);
        if (typeof data.remaining === 'number') {
        remaining = data.remaining;
      }
        if (data.type === 'tick') {
            remaining = data.remaining;
            updateDisplay();
            updateButtons('tick');
        } else if (data.type === 'stopped') {
            updateButtons('stopped');
        } else if (data.type === 'paused') {
            updateButtons('paused');
        } else if (data.type === 'resumed') {
            updateButtons('resumed');
        } else if (data.type === 'ended') {
            updateButtons('ended');
        } else if (data.type === 'partai_data_tunggal') { // This is for incoming partai data from server
            // Your existing logic for handling incoming 'partai_data_tunggal'
            const p = data.data; // Note: In your node.js server, if you send { type: 'partai_data_tunggal', data: p_obj }, then it's data.data here
            console.log("Data partai diterima dari WebSocket:", p);

            // Simpan data baru ke localStorage
            localStorage.setItem('partai', p.partai); // This 'partai' is just the partai number
            localStorage.setItem('currentPartai', JSON.stringify(p)); // This is the full object

            // Tampilkan data ke DOM (if this screen needs to update based on incoming partai data)
            // If this is an operator screen, it probably just pushes data, not updates itself from other clients.
            // If the intention is to update the operator screen based on what the server sends (e.g., from another operator), then uncomment:
            // $('.nm').text(p.peserta.nama);
            // $('.kontingen').text(p.peserta.kontingen);
            // $('.kategori').text(p.kategori);
            // $('.kelas').text(p.kelas);

            // if (p.partai === '?') {
            //   $('.wrong').prop('disabled', true);
            //   $('.move').prop('disabled', true);
            // } else {
            //   $('.wrong').prop('disabled', false);
            //   $('.move').prop('disabled', false);
            // }
        }
    };

    ws.onclose = function () {
        console.log('WebSocket disconnected');
    };

    function updateDisplay() {
        const m = Math.floor(remaining / 60);
        const s = remaining % 60;
        waktuElem.text(`${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`);
    }

    function updateButtons(state) {
        btnStart.hide();
        btnPause.hide();
        btnResume.hide();
        btnStop.hide();

        if (state === 'stopped' || state === 'ended') {
            btnStart.show();
            const storedPartai = JSON.parse(localStorage.getItem('currentPartai'));
            if (storedPartai && storedPartai.id_partai === staticPartaiData.id_partai) {
                btnStart.prop('disabled', false);
            } else {
                btnStart.prop('disabled', true);
            }
            $('.tukar-partai').prop('disabled', false);
        } else if (state === 'started' || state === 'resumed' || state === 'tick') {
            btnPause.show();
            btnStop.show();
            $('.tukar-partai').prop('disabled', true);
        } else if (state === 'paused') {
            btnResume.show();
            btnStop.show();
            $('.tukar-partai').prop('disabled', true);
        }
    }

    pilihButtons.on('click', function() {
        const selectedSudut = $(this).data('sudut');

        let namaPesilat = '';
        let kontingenSudut = '';

        if (selectedSudut === 'BIRU') {
          remaining = 180;
            namaPesilat = staticPartaiData.nm_biru;
            kontingenSudut = staticPartaiData.kontingen_biru;
        } else if (selectedSudut === 'MERAH') {
          remaining = 180;
            namaPesilat = staticPartaiData.nm_merah;
            kontingenSudut = staticPartaiData.kontingen_merah;
        }

        const partaiToSave = {
            id_partai: staticPartaiData.id_partai,
            kategori: staticPartaiData.kategori,
            kelas: staticPartaiData.golongan, // Changed from 'kelas' to 'golongan' based on staticPartaiData
            sudut: selectedSudut,
            nama: namaPesilat,
            kontingen: kontingenSudut
        };
        localStorage.setItem('currentPartai', JSON.stringify(partaiToSave));

        pilihButtons.text('Pilih').prop('disabled', false);
        $(this).text('✔️').prop('disabled', true);

        btnStart.prop('disabled', false);
        refreshpartai(); // Call refreshpartai() here because a new selection was made
    });

    function refreshpartai() {
        // Ensure WebSocket is open before sending
        if (ws.readyState === WebSocket.OPEN) {
            const selectedPartaiData = JSON.parse(localStorage.getItem('currentPartai'));

            // Only send if there's selected data and it matches the current partai
            if (selectedPartaiData && selectedPartaiData.id_partai === staticPartaiData.id_partai) {
                const partaiDataToSend = {
                    type: 'set_partai_tunggal',
                    partai: staticPartaiData.partai, // Use partai from static data
                    kategori: staticPartaiData.kategori,
                    babak: staticPartaiData.babak,
                    kelas: staticPartaiData.golongan, // Use golongan from static data
                    peserta: {
                        nama: selectedPartaiData.nama,
                        kontingen: selectedPartaiData.kontingen,
                        sudut: selectedPartaiData.sudut
                    }
                };
                ws.send(JSON.stringify(partaiDataToSend));
                console.log('Partai data sent to monitor:', partaiDataToSend);
            } else {
                console.log("No matching selected partai data in localStorage to refresh, or current partai is different.");
                // Optionally send a clear command to monitor if no valid selection for this partai
                ws.send(JSON.stringify({ type: 'clear_partai_data' }));
            }
        } else {
            console.warn("WebSocket is not open. Cannot send refreshpartai data.");
        }
    }

    function tukarpartai() {
        console.log("Tukar partai");
        localStorage.removeItem('currentPartai');
        localStorage.removeItem('waktu');
        localStorage.removeItem('timer');
        // localStorage.setItem('is_login',is_login);
        // localStorage.setItem('operator',operator);
        // localStorage.setItem('nama_operator',nama_operator);
        if (ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({ type: 'tukar_partai_tunggal' }));
        } else {
            console.warn("WebSocket not open for tukar_partai_tunggal.");
        }
        document.location.href = "http://192.168.100.133/skordigital/TGR/regu/operator/daftar.php?status=";
    }

    function startTimer() {
        $('.tukar-partai').prop('disabled', true);
        const currentSelectedPartai = JSON.parse(localStorage.getItem('currentPartai'));
        if (!currentSelectedPartai) {
            alert('Mohon pilih peserta terlebih dahulu!');
            return;
        }
        if (ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({
                type: 'start',
                remaining: localStorage.getItem('timer'),
                partai: currentSelectedPartai.id_partai,
                sudut: currentSelectedPartai.sudut
            }));
        } else {
            console.warn("WebSocket not open for start timer.");
        }
    }

    function pauseTimer() {
        const currentSelectedPartai = JSON.parse(localStorage.getItem('currentPartai'));
        if (!currentSelectedPartai) return;
        if (ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({
                type: 'pause',
                partai: currentSelectedPartai.id_partai
            }));
        } else {
            console.warn("WebSocket not open for pause timer.");
        }
    }

    function resumeTimer() {
        const currentSelectedPartai = JSON.parse(localStorage.getItem('currentPartai'));
        if (!currentSelectedPartai) return;
        if (ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({
                type: 'resume',
                partai: currentSelectedPartai.id_partai
            }));
        } else {
            console.warn("WebSocket not open for resume timer.");
        }
    }

    function stopTimer() {
        $('.tukar-partai').prop('disabled', false);
        const currentSelectedPartai = JSON.parse(localStorage.getItem('currentPartai'));
        if (!currentSelectedPartai) return;
        if (ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({
                type: 'stop',
                partai: currentSelectedPartai.id_partai
            }));
        } else {
            console.warn("WebSocket not open for stop timer.");
        }
    }

    function openFullscreen() {
        const elem = document.documentElement;
        if (elem.requestFullscreen) elem.requestFullscreen();
        else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
        else if (elem.msRequestFullscreen) elem.msRequestFullscreen();
        $('#openfull').hide();
        $('#exitfull').show();
    }

    function closeFullscreen() {
        if (document.exitFullscreen) document.exitFullscreen();
        else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
        else if (document.msExitFullscreen) document.msExitFullscreen();
        $('#openfull').show();
        $('#exitfull').hide();
    }
</script>
</body>

</html>
