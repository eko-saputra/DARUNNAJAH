<?php
include "../../backend/includes/connection.php";

$id_partai = isset($_GET["id_partai"]) ? mysqli_real_escape_string($koneksi, $_GET["id_partai"]) : '';
$babak = isset($_GET['babak']) ? $_GET['babak'] : '';

// if ($babak == 'SEMIFINAL') {
$sqljadwal = "SELECT * FROM jadwal_tanding WHERE id_partai='$id_partai'";
// } else if ($babak == 'FINAL') {
// $sqljadwal = "SELECT * FROM jadwal_tanding WHERE id_partai='$id_partai' AND babak='FINAL'";
// } else {
//   die("Ada kesalahan: babak tidak valid");
// }

$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
$jadwal = mysqli_fetch_array($jadwal_tanding);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Operator Pertandingan - Partai</title>
  <link rel="shortcut icon" href="../../assets/img/LogoIPSI.png" />
  <link href="../../assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    #babak-bar button[aria-selected="true"] {
      font-weight: bold;
    }
  </style>
</head>

<body class="bg-dark d-flex align-items-center justify-content-center">

  <div class="container-fluid bg-dark bg-gradient border-secondary rounded-5 p-5 m-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="text-light text-uppercase">Operator PARTAI</h3>
      <div>
        <button id="openfull" onclick="openFullscreen();" class="btn btn-outline-secondary me-2" title="Fullscreen">
          <svg width="24" height="24" viewBox="0 0 24 24">
            <path fill="#ffffff"
              d="M5,5H10V7H7V10H5V5M14,5H19V10H17V7H14V5M17,14H19V19H14V17H17V14M10,17V19H5V14H7V17H10Z" />
          </svg>
        </button>
        <button id="exitfull" onclick="closeFullscreen();" class="btn btn-outline-secondary d-none" title="Exit Fullscreen">
          <svg width="24" height="24" viewBox="0 0 24 24">
            <path fill="#ffffff"
              d="M14,14H19V16H16V19H14V14M5,14H10V19H8V16H5V14M8,5H10V10H5V8H8V5M19,8V10H14V5H16V8H19Z" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Kategori, Gelanggang, Partai -->
    <div class="mb-4 shadow-sm border-0 rounded">
      <div class="card-body text-light d-flex flex-wrap justify-content-between text-center gap-3">
        <div class="border border-2 border-light rounded p-3 flex-fill" style="min-width:150px;">
          <small class="text-light text-uppercase">Kategori Pertandingan</small>
          <div class="fs-5 fw-bold text-uppercase"><?= htmlspecialchars($jadwal['kelas'] ?? '-') ?> / <?= htmlspecialchars($jadwal['babak'] ?? '-') ?></div>
        </div>
        <div class="border border-2 border-light rounded p-3 flex-fill" style="min-width:150px;">
          <small class="text-light text-uppercase">Gelanggang</small>
          <div class="fs-5 fw-bold text-uppercase"><?= htmlspecialchars($jadwal['gelanggang'] ?? '') ?></div>
        </div>
        <div class="border border-2 border-light rounded p-3 flex-fill" style="min-width:150px;">
          <small class="text-light text-uppercase">Partai</small>
          <div class="fs-5 fw-bold text-uppercase"><?= htmlspecialchars($jadwal['partai'] ?? '') ?></div>
        </div>
      </div>
    </div>

    <!-- Buttons -->
    <div class="mb-4 d-flex flex-wrap gap-2">
      <button class="btn btn-light flex-grow-1 flex-md-grow-0 tukarpartai">Tukar Partai</button>
      <button class="btn btn-light flex-grow-1 flex-md-grow-0" id="refreshBtn">Refresh Partai Juri</button>
      <a href="../monitor/view_tanding.php" target="_blank" class="btn btn-light flex-grow-1 flex-md-grow-0">Layar Monitor</a>
    </div>

    <!-- Peserta dan Babak -->
    <div class="row">
      <div class="col-4 bg-primary bg-gradient text-white rounded p-3 flex-fill text-center" id="player-blue">
        <h1 class="text-uppercase"><?= htmlspecialchars($jadwal['nm_biru'] ?? '-') ?></h1>
        <h6 class="text-uppercase"><?= htmlspecialchars($jadwal['kontingen_biru'] ?? '-') ?></h6>
      </div>

      <!-- Babak vertical bar -->
      <div class="col-2">
        <div id="babak-bar" class="d-flex flex-column gap-3 align-items-center" style="min-width: 60px;"></div>
      </div>

      <div class="col-4 bg-danger bg-gradient text-white rounded p-3 flex-fill text-center" id="player-red">
        <h1 class="text-uppercase"><?= htmlspecialchars($jadwal['nm_merah'] ?? '-') ?></h1>
        <h6 class="text-uppercase"><?= htmlspecialchars($jadwal['kontingen_merah'] ?? '-') ?></h6>
      </div>
    </div>

    <!-- Timer & Status -->
    <div class="card shadow-sm mt-4 p-4 text-center bg-dark text-success">
      <div id="waktu" class="display-1 fw-bold">00:00</div>
      <div id="status" class="fs-5 mt-2 text-muted">Menunggu...</div>
    </div>
    <div class="container my-4">
      <div class="d-flex justify-content-between align-items-center">
        <?php
        // Asumsikan Anda punya fungsi untuk mendapatkan jumlah partai terakhir
        // $totalPartai = getTotalPartai(); // Fungsi ini harus Anda buat

        // Jika tidak ada data, kita bisa cek dari database
        // Contoh dengan query langsung:
        include '../../backend/includes/connection.php';
        $query = "SELECT MAX(id_partai) as max_id FROM jadwal_tanding WHERE babak = 'SEMIFINAL'";
        $result = mysqli_query($koneksi, $query);
        $row = mysqli_fetch_assoc($result);
        $maxId = $row['max_id'] ?? 0;

        $query_proses = "SELECT COUNT(id_partai) as jum_proses FROM jadwal_tanding WHERE status = 'proses'";
        $result_proses = mysqli_query($koneksi, $query_proses);
        $row_proses = mysqli_fetch_assoc($result_proses);

        $query_selesai = "SELECT COUNT(id_partai) as jum_selesai FROM jadwal_tanding WHERE status = 'selesai'";
        $result_selesai = mysqli_query($koneksi, $query_selesai);
        $row_selesai = mysqli_fetch_assoc($result_selesai);

        $cek = "SELECT babak FROM jadwal_tanding WHERE id_partai = '$_GET[id_partai] + 1'";
        $result_cek = mysqli_query($koneksi, $cek);
        $row_cek = mysqli_fetch_assoc($result_cek);

        $currentId = $_GET['id_partai'] ?? 0;
        // $babak = $_GET['babak'] ?? 'SEMIFINAL';
        // $babak = $row_cek['babak'];
        ?>

        <!-- Tombol Previous -->
        <a href="?id_partai=<?= max(0, $currentId - 1); ?>"
          class="btn btn-outline-primary <?= ($currentId <= 1) ? 'disabled' : ''; ?>"
          id="tombol_previous">
          <i class="fas fa-chevron-left me-2"></i> Previous
        </a>
        <!-- Indicator -->
        <div class="text-center">
          <span class="badge bg-secondary p-2">
            <b id="stat"></b>
            <span class="text-light mx-2">|</span>
            Partai Selesai <?= ($row_selesai['jum_selesai'] ? $row_selesai['jum_selesai'] : 0); ?>
          </span>
        </div>

        <!-- Tombol Next -->
        <a href="?id_partai=<?= $_GET['id_partai'] + 1; ?>"
          class="btn btn-outline-primary" id="tombol_next">
          Next <i class="fas fa-chevron-right ms-2"></i>
        </a>
      </div>

      <!-- Progress Bar -->
      <div class="progress mt-3" style="height: 8px;">
        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"
          role="progressbar"
          style="width: <?=
                        // Jika hanya ada 1 partai, progress 100%
                        (($maxId + 1) <= 1) ? 100 :
                          // Rumus yang benar: (posisi saat ini) / (total partai) * 100
                          (($currentId) / ($maxId)) * 100;
                        ?>%;"
          aria-valuenow="<?= $currentId + 1; ?>"
          aria-valuemin="1"
          aria-valuemax="<?= $maxId + 1; ?>">
        </div>
      </div>
    </div>
  </div>


  <script src="../../assets/jquery/jquery.min.js"></script>
  <script src="../../assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const ws = new WebSocket('ws://localhost:3000');
    let currentBabak = parseInt(localStorage.getItem('babak')) || 1;
    let remaining = 0;
    let status = '';
    let isTimerRunning = false; // Status timer berjalan

    function updateStatus() {
      $('#status').text(status).removeClass('text-success text-warning text-danger text-muted').addClass(
        status === 'Berjalan' ? 'text-success' :
        status === 'Jeda' ? 'text-warning' :
        status === 'Selesai' ? 'text-danger' : 'text-muted'
      );

      // Kontrol tombol berdasarkan status
      if (status === 'Berjalan') {
        disableNavigationButtons(true);
        isTimerRunning = true;
      } else if (status === 'Selesai') {
        disableNavigationButtons(false);
        isTimerRunning = false;
      } else if (status === 'Jeda') {
        disableNavigationButtons(false);
        isTimerRunning = false;
      }
    }

    function disableNavigationButtons(disable) {
      if (disable) {
        $('#tombol_previous').addClass('disabled btn-disabled');
        $('#tombol_next').addClass('disabled btn-disabled');

        // Juga nonaktifkan secara HTML
        $('#tombol_previous').attr('aria-disabled', 'true');
        $('#tombol_next').attr('aria-disabled', 'true');
      } else {
        // Hapus disabled hanya jika bukan karena partai pertama/terakhir
        const currentId = <?= $currentId ?>;
        const maxId = <?= $maxId ?>;

        // Previous button
        if (currentId > 1) {
          $('#tombol_previous').removeClass('disabled btn-disabled');
          $('#tombol_previous').attr('aria-disabled', 'false');
        }

        // Next button
        if (currentId < maxId) {
          $('#tombol_next').removeClass('disabled btn-disabled');
          $('#tombol_next').attr('aria-disabled', 'false');
        }
      }
    }

    function renderBabakButtons(jumlah) {
      const bar = $('#babak-bar');
      bar.empty();
      for (let i = jumlah; i >= 1; i--) {
        const btn = $(`<button class="btn btn-light rounded-3 py-2 w-100" data-babak="${i}">${i}</button>`);
        btn.on('click keypress', function(e) {
          if (e.type === 'click' || (e.type === 'keypress' && (e.key === 'Enter' || e.key === ' '))) {
            setBabakUI(i);
            localStorage.setItem('babak', i);
            sendBabakToServer(i);
          }
        });
        bar.append(btn);
      }
      setBabakUI(currentBabak > jumlah ? 1 : currentBabak);
    }

    function setBabakUI(babak) {
      currentBabak = babak;
      $('#babak-bar button').each(function() {
        const btn = $(this);
        if (parseInt(btn.data('babak')) === babak) {
          btn.removeClass('btn-light').addClass('btn-warning text-dark').attr({
            'aria-selected': 'true',
            tabindex: 0
          });
        } else {
          btn.removeClass('btn-warning text-dark').addClass('btn-light').attr({
            'aria-selected': 'false',
            tabindex: -1
          });
        }
      });
    }

    function sendBabakToServer(babak) {
      ws.readyState === WebSocket.OPEN && ws.send(JSON.stringify({
        type: 'set_round',
        round: babak,
        partai: "<?= htmlspecialchars($id_partai) ?>"
      }));
    }

    function updateTimerDisplay() {
      const m = Math.floor(remaining / 60),
        s = remaining % 60;
      $('#waktu').text(`${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`);
    }

    function updateStatus() {
      $('#status').text(status).removeClass('text-success text-warning text-danger text-muted').addClass(
        status === 'Berjalan' ? 'text-success' : status === 'Jeda' ? 'text-warning' : status === 'Selesai' ? 'text-danger' : 'text-muted'
      );
    }

    function refreshpartai() {
      const data = <?= json_encode([
                      'type' => 'set_partai',
                      'partai' => $jadwal['partai'],
                      'gelanggang' => $jadwal['gelanggang'],
                      'babak' => $currentBabak ?? 1,
                      'bbk' => $jadwal['babak'] ?? '',
                      'st' => $jadwal['status_babak'] ?? '',
                      'kelas' => $jadwal['kelas'] ?? '',
                      'biru' => [
                        'nama' => $jadwal['nm_biru'] ?? '',
                        'kontingen' => $jadwal['kontingen_biru'] ?? ''
                      ],
                      'merah' => [
                        'nama' => $jadwal['nm_merah'] ?? '',
                        'kontingen' => $jadwal['kontingen_merah'] ?? ''
                      ]
                    ], JSON_UNESCAPED_UNICODE) ?>;

      ws.readyState === WebSocket.OPEN && ws.send(JSON.stringify(data));
      sendBabakToServer(currentBabak);
    }

    $('.tukarpartai').on('click', function() {
      localStorage.setItem('babak', 0);
      ws.readyState === WebSocket.OPEN && ws.send(JSON.stringify({
        type: 'tukar_partai'
      }));
      localStorage.removeItem('finishedRounds');
      document.location.href = "daftar.php?status=";
    });

    $('#refreshBtn').on('click', refreshpartai);

    ws.onopen = () => {
      console.log('Connected');
      renderBabakButtons(parseInt(localStorage.getItem('jumlahBabak')) || 3);
      console.log('Babak', currentBabak);
      sendBabakToServer(currentBabak);
      refreshpartai();

      $('#stat').html('Partai <?= $_GET['id_partai'] ?>');
    };

    ws.onmessage = (e) => {
      const msg = JSON.parse(e.data);
      if (typeof msg.remaining === 'number') remaining = msg.remaining;
      switch (msg.type) {
        case 'tick':
        case 'started':
          ws.send(JSON.stringify({
            type: "set_status",
            partai: <?= $_GET['id_partai'] ?>,
          }));
          break;
        case 'resumed':
          status = 'Berjalan';
          isTimerRunning = true;
          disableNavigationButtons(true);
          break;
        case 'paused':
          status = 'Jeda';
          break;
        case 'ended':
          $('.tukarpartai').prop('disabled', false);
          if (localStorage.getItem('babak') == 3) {
            isTimerRunning = false;
            disableNavigationButtons(false);
          }
          break;
        case 'stopped':
          status = 'Selesai';
          remaining = 0;
          $('#stat').html('Partai <?= $_GET['id_partai'] ?> selesai');
          break;
        case 'set_round':
          localStorage.setItem('babak', parseInt(msg.round));
          break;
        case 'set_jumlah_babak':
          localStorage.setItem('jumlahBabak', msg.jumlah);
          renderBabakButtons(msg.jumlah);
          break;
        case 'babak_data':
          setBabakUI(msg.round);
          break;
        case 'response':
          localStorage.setItem('status', msg.message)
          $('#stat').html('Partai <?= $_GET['id_partai'] ?> ' + msg.message);
          break;
      }
      updateTimerDisplay();
      updateStatus();
    };

    function openFullscreen() {
      const elem = document.documentElement;
      if (elem.requestFullscreen) elem.requestFullscreen();
      else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
      else if (elem.msRequestFullscreen) elem.msRequestFullscreen();
      $('#openfull').addClass('d-none');
      $('#exitfull').removeClass('d-none');
    }

    function closeFullscreen() {
      if (document.exitFullscreen) document.exitFullscreen();
      else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
      else if (document.msExitFullscreen) document.msExitFullscreen();
      $('#exitfull').addClass('d-none');
      $('#openfull').removeClass('d-none');
    }

    document.addEventListener('selectstart', e => e.preventDefault());
  </script>
</body>

</html>