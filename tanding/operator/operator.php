<?php
include "../../backend/includes/connection.php";

$id_partai = isset($_GET["id_partai"]) ? mysqli_real_escape_string($koneksi, $_GET["id_partai"]) : '';

// Ambil partai saat ini
$sqljadwal = "SELECT * FROM jadwal_tanding WHERE id_partai='$id_partai'";
$jadwal_tanding = mysqli_query($koneksi, $sqljadwal);
$jadwal = mysqli_fetch_array($jadwal_tanding);

// Cek status pertandingan dan pemenang
$status_pertandingan = $jadwal['status'] ?? 'belum';
$pemenang = $jadwal['pemenang'] ?? ''; // TIDAK DIGUNAKAN LAGI - Pemenang dari server WebSocket

// Ambil partai sebelumnya
$sql_prev = "SELECT id_partai FROM jadwal_tanding WHERE id_partai < '$id_partai' ORDER BY id_partai DESC LIMIT 1";
$result_prev = mysqli_query($koneksi, $sql_prev);
$prev_partai = mysqli_fetch_assoc($result_prev);

// Ambil partai berikutnya
$sql_next = "SELECT id_partai FROM jadwal_tanding WHERE id_partai > '$id_partai' ORDER BY id_partai ASC LIMIT 1";
$result_next = mysqli_query($koneksi, $sql_next);
$next_partai = mysqli_fetch_assoc($result_next);

// Hitung total partai
$sql_total = "SELECT COUNT(*) as total FROM jadwal_tanding";
$result_total = mysqli_query($koneksi, $sql_total);
$total_partai = mysqli_fetch_assoc($result_total)['total'];

// Hitung partai selesai
$sql_selesai = "SELECT COUNT(*) as selesai FROM jadwal_tanding WHERE status = 'selesai'";
$result_selesai = mysqli_query($koneksi, $sql_selesai);
$selesai = mysqli_fetch_assoc($result_selesai)['selesai'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Operator Pertandingan - TANDING</title>
  <link rel="shortcut icon" href="../../assets/img/LogoIPSI.png" />
  <link href="../../assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #0a0e17;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .main-card {
      background: linear-gradient(145deg, #1e2532 0%, #141b26 100%);
      border-radius: 40px;
      box-shadow: 0 25px 50px -8px rgba(0, 0, 0, 0.6), inset 0 2px 4px rgba(255, 255, 255, 0.05);
      padding: 2.5rem;
      max-width: 1200px;
      width: 100%;
      border: 1px solid rgba(0, 180, 255, 0.15);
    }

    /* Status Selesai Banner */
    .status-banner {
      background: linear-gradient(135deg, #059669, #10b981);
      color: white;
      padding: 15px 25px;
      border-radius: 60px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
      animation: slideDown 0.5s ease;
    }

    .status-banner.selesai {
      background: linear-gradient(135deg, #b91c1c, #dc2626);
      box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3);
    }

    @keyframes slideDown {
      from {
        transform: translateY(-20px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .banner-icon {
      font-size: 2rem;
      margin-right: 15px;
    }

    .banner-text {
      font-size: 1.3rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .banner-subtext {
      font-size: 1rem;
      opacity: 0.9;
    }

    /* Notifikasi Partai Selesai dari Server */
    .server-notification {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      padding: 20px;
      border-radius: 20px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
      animation: slideInRight 0.5s ease, pulse-notif 2s infinite;
      border-left: 5px solid #fbbf24;
    }

    @keyframes slideInRight {
      from {
        transform: translateX(50px);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes pulse-notif {
      0% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
      }

      70% {
        box-shadow: 0 0 0 15px rgba(16, 185, 129, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
      }
    }

    .notif-icon {
      font-size: 2.5rem;
      background: rgba(255, 255, 255, 0.2);
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .notif-content {
      flex: 1;
    }

    .notif-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 5px;
    }

    .notif-message {
      font-size: 1.5rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .notif-close {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      color: white;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .notif-close:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: scale(1.1);
    }

    /* Header Styles */
    .header-title {
      color: #fff;
      font-weight: 600;
      letter-spacing: 1px;
      margin: 0;
      text-transform: uppercase;
      background: linear-gradient(135deg, #7dd3fc, #38bdf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-size: 1.8rem;
    }

    .fullscreen-btn {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 10px 16px;
      transition: all 0.3s ease;
      color: #94a3b8;
      cursor: pointer;
    }

    .fullscreen-btn:hover {
      background: rgba(255, 255, 255, 0.1);
      border-color: #38bdf8;
      color: #38bdf8;
      transform: scale(1.05);
    }

    /* Connection Status */
    .connection-status {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(0, 0, 0, 0.3);
      padding: 8px 20px;
      border-radius: 40px;
      border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .connection-indicator {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: #666;
      transition: all 0.3s ease;
    }

    .connection-indicator.connected {
      background-color: #10b981;
      box-shadow: 0 0 20px #10b981;
      animation: pulse-green 2s infinite;
    }

    .connection-indicator.disconnected {
      background-color: #ef4444;
      box-shadow: 0 0 20px #ef4444;
    }

    @keyframes pulse-green {
      0% {
        opacity: 1;
      }

      50% {
        opacity: 0.6;
      }

      100% {
        opacity: 1;
      }
    }

    .connection-text {
      color: #94a3b8;
      font-size: 0.9rem;
      font-weight: 500;
    }

    .connection-text.connected {
      color: #10b981;
    }

    .connection-text.disconnected {
      color: #ef4444;
    }

    /* Info Cards */
    .info-card {
      background: rgba(0, 0, 0, 0.3);
      border: 2px solid rgba(56, 189, 248, 0.2);
      border-radius: 24px;
      padding: 1.2rem;
      backdrop-filter: blur(5px);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .info-card.selesai {
      border-color: #10b981;
      background: rgba(16, 185, 129, 0.1);
    }

    .info-card:hover {
      border-color: #38bdf8;
      box-shadow: 0 0 30px rgba(56, 189, 248, 0.2);
    }

    .info-label {
      color: #94a3b8;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 5px;
    }

    .info-value {
      color: #e2e8f0;
      font-size: 1.3rem;
      font-weight: 700;
    }

    .status-badge-db {
      position: absolute;
      top: 10px;
      right: 10px;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .status-badge-db.belum {
      background: rgba(148, 163, 184, 0.2);
      color: #94a3b8;
      border: 1px solid #94a3b8;
    }

    .status-badge-db.proses {
      background: rgba(245, 158, 11, 0.2);
      color: #f59e0b;
      border: 1px solid #f59e0b;
      animation: blink 1.5s infinite;
    }

    .status-badge-db.selesai {
      background: rgba(16, 185, 129, 0.2);
      color: #10b981;
      border: 1px solid #10b981;
    }

    @keyframes blink {
      0% {
        opacity: 1;
      }

      50% {
        opacity: 0.5;
      }

      100% {
        opacity: 1;
      }
    }

    /* Player Cards dengan Pemenang */
    .player-card {
      border-radius: 30px;
      padding: 2rem 1.5rem;
      transition: all 0.3s ease;
      height: 100%;
      position: relative;
      overflow: hidden;
    }

    .player-card.blue {
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.3), rgba(59, 130, 246, 0.2));
      border: 2px solid rgba(59, 130, 246, 0.3);
    }

    .player-card.red {
      background: linear-gradient(135deg, rgba(220, 38, 38, 0.3), rgba(239, 68, 68, 0.2));
      border: 2px solid rgba(239, 68, 68, 0.3);
    }

    .player-card.winner {
      position: relative;
      border-width: 4px;
      animation: winner-pulse 2s infinite;
    }

    .player-card.blue.winner {
      border-color: #10b981;
      box-shadow: 0 0 50px rgba(16, 185, 129, 0.5);
    }

    .player-card.red.winner {
      border-color: #10b981;
      box-shadow: 0 0 50px rgba(16, 185, 129, 0.5);
    }

    .player-card.winner::before {
      content: "👑 PEMENANG";
      position: absolute;
      top: -10px;
      left: 50%;
      transform: translateX(-50%);
      background: linear-gradient(135deg, #fbbf24, #f59e0b);
      color: #000;
      padding: 5px 25px;
      border-radius: 40px;
      font-size: 1rem;
      font-weight: 800;
      letter-spacing: 2px;
      box-shadow: 0 5px 20px rgba(251, 191, 36, 0.5);
      z-index: 10;
      white-space: nowrap;
    }

    @keyframes winner-pulse {
      0% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.02);
      }

      100% {
        transform: scale(1);
      }
    }

    .player-name {
      font-size: 2.2rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 2px;
      margin-bottom: 10px;
      line-height: 1.2;
    }

    .player-card.blue .player-name {
      color: #60a5fa;
      text-shadow: 0 2px 20px rgba(96, 165, 250, 0.5);
    }

    .player-card.red .player-name {
      color: #f87171;
      text-shadow: 0 2px 20px rgba(248, 113, 113, 0.5);
    }

    .player-kontingen {
      color: #94a3b8;
      font-size: 1.1rem;
      margin-bottom: 0;
    }

    .winner-icon {
      font-size: 3rem;
      margin-top: 10px;
      animation: winner-icon-spin 2s infinite;
    }

    @keyframes winner-icon-spin {
      0% {
        transform: rotate(0deg);
      }

      25% {
        transform: rotate(10deg);
      }

      75% {
        transform: rotate(-10deg);
      }

      100% {
        transform: rotate(0deg);
      }
    }

    /* Babak Bar */
    .babak-bar {
      display: flex;
      flex-direction: column;
      gap: 15px;
      align-items: center;
      padding: 10px;
    }

    .babak-btn {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      border: 3px solid rgba(56, 189, 248, 0.3);
      background: rgba(0, 0, 0, 0.3);
      color: #94a3b8;
      font-size: 1.8rem;
      font-weight: 700;
      transition: all 0.3s ease;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .babak-btn:hover:not(:disabled) {
      border-color: #38bdf8;
      color: #38bdf8;
      transform: scale(1.1);
      box-shadow: 0 0 30px rgba(56, 189, 248, 0.3);
    }

    .babak-btn.active {
      background: linear-gradient(135deg, #f59e0b, #fbbf24);
      border-color: #fbbf24;
      color: #000;
      box-shadow: 0 0 40px rgba(251, 191, 36, 0.4);
    }

    .babak-btn.completed {
      border-color: #10b981;
      color: #10b981;
      position: relative;
    }

    .babak-btn.completed::after {
      content: "✓";
      position: absolute;
      top: -5px;
      right: -5px;
      background: #10b981;
      color: white;
      width: 25px;
      height: 25px;
      border-radius: 50%;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Timer Display */
    .timer-card {
      background: rgba(0, 0, 0, 0.4);
      border-radius: 30px;
      padding: 1.5rem;
      border: 2px solid rgba(56, 189, 248, 0.2);
      margin-top: 2rem;
      transition: all 0.3s ease;
    }

    .timer-card.selesai {
      border-color: #10b981;
      background: rgba(16, 185, 129, 0.1);
      animation: glow-green 2s infinite;
    }

    .timer-card.server-selesai {
      border-color: #fbbf24;
      background: rgba(251, 191, 36, 0.1);
      animation: glow-yellow 2s infinite;
    }

    @keyframes glow-green {
      0% {
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
      }

      50% {
        box-shadow: 0 0 40px rgba(16, 185, 129, 0.6);
      }

      100% {
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
      }
    }

    @keyframes glow-yellow {
      0% {
        box-shadow: 0 0 20px rgba(251, 191, 36, 0.3);
      }

      50% {
        box-shadow: 0 0 40px rgba(251, 191, 36, 0.6);
      }

      100% {
        box-shadow: 0 0 20px rgba(251, 191, 36, 0.3);
      }
    }

    #waktu {
      font-family: 'Courier New', monospace;
      font-size: 5rem;
      font-weight: 800;
      color: #38bdf8;
      text-shadow: 0 0 40px rgba(56, 189, 248, 0.5);
      letter-spacing: 8px;
    }

    #waktu.warning {
      color: #fbbf24;
      text-shadow: 0 0 40px rgba(251, 191, 36, 0.5);
    }

    #waktu.danger {
      color: #f87171;
      text-shadow: 0 0 40px rgba(248, 113, 113, 0.5);
      animation: pulse 1s infinite;
    }

    #status {
      font-size: 1.2rem;
      font-weight: 600;
      padding: 8px 25px;
      border-radius: 40px;
      display: inline-block;
    }

    .status-berjalan {
      background: rgba(16, 185, 129, 0.2);
      color: #10b981;
      border: 1px solid #10b981;
    }

    .status-jeda {
      background: rgba(245, 158, 11, 0.2);
      color: #f59e0b;
      border: 1px solid #f59e0b;
    }

    .status-selesai {
      background: rgba(239, 68, 68, 0.2);
      color: #ef4444;
      border: 1px solid #ef4444;
    }

    .status-menunggu {
      background: rgba(148, 163, 184, 0.2);
      color: #94a3b8;
      border: 1px solid #94a3b8;
    }

    /* Server Status Display */
    .server-status {
      margin-top: 15px;
      padding: 15px;
      border-radius: 15px;
      background: rgba(0, 0, 0, 0.3);
      border-left: 5px solid #38bdf8;
    }

    .server-status.selesai {
      border-left-color: #10b981;
      background: rgba(16, 185, 129, 0.1);
    }

    .server-message {
      font-size: 1.2rem;
      font-weight: 600;
      color: #e2e8f0;
    }

    .server-message i {
      margin-right: 10px;
    }

    /* Action Buttons */
    .action-btn {
      background: rgba(255, 255, 255, 0.03);
      border: 2px solid rgba(56, 189, 248, 0.2);
      color: #e2e8f0;
      border-radius: 50px;
      padding: 12px 25px;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .action-btn:hover:not(:disabled):not(.disabled) {
      border-color: #38bdf8;
      color: #38bdf8;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(56, 189, 248, 0.2);
    }

    .action-btn:disabled,
    .action-btn.disabled {
      opacity: 0.4;
      cursor: not-allowed;
      pointer-events: none;
    }

    /* Navigation Buttons */
    .nav-btn {
      background: rgba(56, 189, 248, 0.1);
      border: 2px solid rgba(56, 189, 248, 0.2);
      color: #e2e8f0;
      border-radius: 50px;
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
    }

    .nav-btn:hover:not(.disabled):not(.nav-disabled) {
      background: rgba(56, 189, 248, 0.2);
      border-color: #38bdf8;
      color: #38bdf8;
      transform: translateY(-2px);
    }

    .nav-btn.disabled,
    .nav-btn.nav-disabled {
      opacity: 0.3;
      cursor: not-allowed;
      pointer-events: none;
      background: rgba(100, 100, 100, 0.1);
      border-color: rgba(150, 150, 150, 0.2);
    }

    /* Progress Bar */
    .progress {
      background: rgba(0, 0, 0, 0.3);
      height: 8px;
      border-radius: 4px;
      overflow: hidden;
    }

    .progress-bar {
      background: linear-gradient(90deg, #38bdf8, #8b5cf6);
      transition: width 0.3s ease;
    }

    @media (max-width: 768px) {
      .main-card {
        padding: 1.5rem;
      }

      .player-name {
        font-size: 1.5rem;
      }

      #waktu {
        font-size: 3rem;
      }

      .babak-btn {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
      }

      .status-banner {
        flex-direction: column;
        text-align: center;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="main-card">

          <!-- Notifikasi dari Server -->
          <div id="serverNotification" class="server-notification" style="display: none;">
            <div class="notif-icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="notif-content">
              <div class="notif-title">Pesan dari Server</div>
              <div class="notif-message" id="serverMessage"></div>
            </div>
            <button class="notif-close" onclick="closeNotification()">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <!-- Status Banner Container (akan diupdate realtime) -->
          <div id="statusBannerContainer"></div>

          <!-- Header with Connection Status -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
              <h1 class="header-title">
                <i class="fas fa-ring me-3 text-primary"></i>
                Operator TANDING
              </h1>
              <div class="connection-status" id="connectionStatus">
                <span id="connectionIndicator" class="connection-indicator disconnected"></span>
                <span id="connectionText" class="connection-text disconnected">Server: Disconnected</span>
              </div>
            </div>
            <div class="d-flex gap-2">
              <button id="openfull" onclick="openFullscreen();" class="fullscreen-btn" title="Fullscreen">
                <i class="fas fa-expand fa-lg"></i>
              </button>
              <button id="exitfull" onclick="closeFullscreen();" class="fullscreen-btn" style="display: none;" title="Exit Fullscreen">
                <i class="fas fa-compress fa-lg"></i>
              </button>
            </div>
          </div>

          <!-- Info Cards Container (akan diupdate realtime) -->
          <div class="row g-3 mb-4" id="infoCardsContainer"></div>

          <!-- Action Buttons -->
          <div class="d-flex flex-wrap gap-3 mb-4">
            <button class="action-btn tukarpartai" id="tukarPartaiBtn">
              <i class="fas fa-exchange-alt me-2"></i>Tukar Partai
            </button>
            <button class="action-btn" id="refreshBtn">
              <i class="fas fa-sync-alt me-2"></i>Refresh Juri
            </button>
            <a href="../monitor/view_tanding.php" target="_blank" class="action-btn">
              <i class="fas fa-tv me-2"></i>Layar Monitor
            </a>
          </div>

          <!-- Players and Babak -->
          <div class="row g-4">
            <!-- Blue Player -->
            <div class="col-md-5">
              <div class="player-card blue text-center" id="playerBlueCard">
                <div class="mb-3">
                  <span class="badge bg-primary bg-gradient px-4 py-2 fs-6">BIRU</span>
                </div>
                <h2 class="player-name">
                  <?= htmlspecialchars($jadwal['nm_biru'] ?? '-') ?>
                </h2>
                <p class="player-kontingen">
                  <i class="fas fa-flag me-2"></i>
                  <?= htmlspecialchars($jadwal['kontingen_biru'] ?? '-') ?>
                </p>
                <div id="blueWinnerIcon" class="winner-icon" style="display: none;">
                  <i class="fas fa-crown text-warning"></i>
                </div>
              </div>
            </div>

            <!-- Babak Bar -->
            <div class="col-md-2 d-flex align-items-center justify-content-center">
              <div id="babak-bar" class="babak-bar"></div>
            </div>

            <!-- Red Player -->
            <div class="col-md-5">
              <div class="player-card red text-center" id="playerRedCard">
                <div class="mb-3">
                  <span class="badge bg-danger bg-gradient px-4 py-2 fs-6">MERAH</span>
                </div>
                <h2 class="player-name">
                  <?= htmlspecialchars($jadwal['nm_merah'] ?? '-') ?>
                </h2>
                <p class="player-kontingen">
                  <i class="fas fa-flag me-2"></i>
                  <?= htmlspecialchars($jadwal['kontingen_merah'] ?? '-') ?>
                </p>
                <div id="redWinnerIcon" class="winner-icon" style="display: none;">
                  <i class="fas fa-crown text-warning"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Timer & Status -->
          <div class="timer-card" id="timerCard">
            <div class="row align-items-center">
              <div class="col-md-6 text-center">
                <div id="waktu" class="display-1 fw-bold">00:00</div>
                <div id="dbStatusMessage" class="mt-2"></div>
              </div>
              <div class="col-md-6 text-center">
                <div id="status" class="status-menunggu">
                  <i class="fas fa-hourglass-half me-2"></i>Menunggu...
                </div>
                <div class="mt-2 text-secondary">
                  Babak <span id="current-babak">1</span>/<span id="total-babak">3</span>
                </div>

                <!-- Server Status Message -->
                <div id="serverStatusMessage" class="server-status" style="display: none;">
                  <div class="server-message">
                    <i class="fas fa-check-circle text-success"></i>
                    <span id="serverStatusText"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Navigation & Progress -->
          <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <!-- Tombol Previous -->
              <?php if ($prev_partai): ?>
                <a href="?id_partai=<?= $prev_partai['id_partai'] ?>" class="nav-btn" id="tombol_previous">
                  <i class="fas fa-chevron-left"></i> Previous
                </a>
              <?php else: ?>
                <span class="nav-btn disabled" id="tombol_previous_disabled">
                  <i class="fas fa-chevron-left"></i> Previous
                </span>
              <?php endif; ?>

              <div class="text-center">
                <span class="badge bg-secondary p-3" id="partaiInfo">
                  Partai <?= $id_partai ?> dari <?= $total_partai ?>
                  <span class="text-light mx-2">|</span>
                  Selesai: <span id="selesaiCount"><?= $selesai ?></span>
                </span>
              </div>

              <!-- Tombol Next -->
              <?php if ($next_partai): ?>
                <a href="?id_partai=<?= $next_partai['id_partai'] ?>" class="nav-btn" id="tombol_next">
                  Next <i class="fas fa-chevron-right"></i>
                </a>
              <?php else: ?>
                <span class="nav-btn disabled" id="tombol_next_disabled">
                  Next <i class="fas fa-chevron-right"></i>
                </span>
              <?php endif; ?>
            </div>

            <!-- Progress Bar -->
            <div class="progress">
              <div class="progress-bar"
                id="progressBar"
                role="progressbar"
                style="width: <?= ($total_partai > 0) ? ($id_partai / $total_partai * 100) : 0; ?>%;">
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script src="../../assets/jquery/jquery.min.js"></script>
  <script src="../../assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const hostname = window.location.hostname;
    let ws;
    let reconnectAttempts = 0;
    const maxReconnectAttempts = 10;
    let reconnectTimeout;

    let currentBabak = parseInt(localStorage.getItem('babak')) || 1;
    let remaining = 0;
    let status = 'Menunggu...';
    let isTimerRunning = false;
    let statusDB = '<?= $status_pertandingan ?>';
    let serverSelesai = false;
    let currentPartai = <?= $id_partai ?>;
    let totalPartai = <?= $total_partai ?>;

    // Pemenang dari server WebSocket (TIDAK dari database)
    let pemenang = '<?= $pemenang ?>'; // 'biru', 'merah', atau '' (dari server WebSocket)
    console.log('Initial pemenang (from WebSocket only):', pemenang);

    // Data partai
    let partaiData = {
      id: <?= $id_partai ?>,
      kelas: '<?= htmlspecialchars($jadwal['kelas'] ?? '-') ?>',
      gelanggang: '<?= htmlspecialchars($jadwal['gelanggang'] ?? '-') ?>',
      partai: '<?= htmlspecialchars($jadwal['partai'] ?? '-') ?>',
      babak: '<?= htmlspecialchars($jadwal['babak'] ?? '-') ?>',
      nm_biru: '<?= htmlspecialchars($jadwal['nm_biru'] ?? '-') ?>',
      kontingen_biru: '<?= htmlspecialchars($jadwal['kontingen_biru'] ?? '-') ?>',
      nm_merah: '<?= htmlspecialchars($jadwal['nm_merah'] ?? '-') ?>',
      kontingen_merah: '<?= htmlspecialchars($jadwal['kontingen_merah'] ?? '-') ?>'
    };

    function connectWebSocket() {
      // Clear existing timeout
      if (reconnectTimeout) {
        clearTimeout(reconnectTimeout);
      }

      // Determine WebSocket URL
      const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
      let wsUrl;

      // Try multiple possible URLs
      const possibleUrls = [
        `${protocol}//${hostname}:3000`,
        `${protocol}//localhost:3000`,
        `${protocol}//127.0.0.1:3000`,
        `ws://${hostname}:3000`,
        `ws://localhost:3000`,
        `ws://127.0.0.1:3000`
      ];

      // Use the first one that works (we'll try in order)
      wsUrl = possibleUrls[0];
      console.log('Attempting to connect to WebSocket at:', wsUrl);

      try {
        ws = new WebSocket(wsUrl);

        ws.onopen = () => {
          console.log('✅ Connected to server at', wsUrl);
          updateConnectionStatus(true);
          reconnectAttempts = 0;

          $('#tukarPartaiBtn').prop('disabled', false);

          // Kirim data awal
          sendInitialData();

          // Pemenang awal kosong (dari server WebSocket)
          updatePemenangDisplay();
        };

        ws.onmessage = (e) => {
          try {
            const msg = JSON.parse(e.data);
            console.log('Received:', msg.type, msg);

            if (typeof msg.remaining === 'number') {
              remaining = msg.remaining;
              updateTimerDisplay();
            }

            switch (msg.type) {
              case 'tick':
              case 'started':
                status = 'Berjalan';
                isTimerRunning = true;
                updateNavigationButtons(true);
                updateTukarPartaiButton(true); // Disable tukar partai
                break;
              case 'resumed':
                status = 'Berjalan';
                isTimerRunning = true;
                updateNavigationButtons(true);
                updateTukarPartaiButton(true); // Disable tukar partai
                break;
              case 'paused':
                status = 'Jeda';
                isTimerRunning = false;
                updateNavigationButtons(false);
                updateTukarPartaiButton(false); // Enable tukar partai
                break;
              case 'stopped':
                status = 'Selesai';
                isTimerRunning = false;
                remaining = 0;
                updateNavigationButtons(false);
                updateTukarPartaiButton(false); // Enable tukar partai
                break;
              case 'ended':
                status = 'Selesai';
                isTimerRunning = false;
                updateNavigationButtons(false);
                updateTukarPartaiButton(false); // Enable tukar partai
                break;
              case 'set_round':
                currentBabak = parseInt(msg.round);
                localStorage.setItem('babak', currentBabak);
                setBabakUI(currentBabak);
                $('#current-babak').text(currentBabak);
                break;
              case 'set_jumlah_babak':
                localStorage.setItem('jumlahBabak', msg.jumlah);
                renderBabakButtons(msg.jumlah);
                $('#total-babak').text(msg.jumlah);
                break;
              case 'babak_data':
                setBabakUI(msg.round);
                $('#current-babak').text(msg.round);
                break;
              case 'response':
                if (msg.message === 'selesai') {
                  console.log('Server mengirim: partai selesai dengan pemenang:', msg.pemenang);
                  serverSelesai = true;

                  // Set pemenang dari server WebSocket
                  if (msg.pemenang) {
                    pemenang = msg.pemenang; // 'biru' atau 'merah'
                    updatePemenangDisplay();
                  }

                  $('#tukarPartaiBtn').prop('disabled', false);

                  showServerNotification('Partai Selesai', 'Pertandingan telah dinyatakan selesai oleh server');
                  showServerStatus('Partai Selesai (Server)');
                  $('#timerCard').addClass('server-selesai');
                  updateStatus();
                }
                break;
              case 'db_status_update':
                if (msg.status) {
                  statusDB = msg.status;
                  updateDBStatusDisplay();
                  console.log('DB Status updated:', msg.status);
                }
                // KOMENTAR: Jangan update pemenang dari database
                // if (msg.pemenang) {
                //   pemenang = msg.pemenang;
                //   updatePemenangDisplay();
                //   console.log('Pemenang updated from DB:', msg.pemenang);
                // }
                break;
              case 'winner_update':
                // Tambahan: menerima update pemenang langsung dari server
                if (msg.pemenang) {
                  console.log('Winner update from server:', msg.pemenang);
                  pemenang = msg.pemenang;
                  updatePemenangDisplay();
                }
                break;
            }

            updateStatus();
            updateTimerDisplay();
          } catch (error) {
            console.log('Error parsing message:', error);
          }
        };

        ws.onclose = (event) => {
          console.log('❌ WebSocket disconnected. Code:', event.code, 'Reason:', event.reason);
          updateConnectionStatus(false);

          // Exponential backoff for reconnection
          const timeout = Math.min(1000 * Math.pow(2, reconnectAttempts), 30000);
          reconnectAttempts++;

          if (reconnectAttempts <= maxReconnectAttempts) {
            console.log(`Attempting to reconnect in ${timeout / 1000}s... (attempt ${reconnectAttempts}/${maxReconnectAttempts})`);
            reconnectTimeout = setTimeout(connectWebSocket, timeout);
          } else {
            console.log('Max reconnection attempts reached. Please refresh the page.');
            showServerNotification('Koneksi Gagal', 'Tidak dapat terhubung ke server. Silakan refresh halaman.');
          }
        };

        ws.onerror = (error) => {
          console.log('❌ WebSocket error:', error);
          updateConnectionStatus(false);

          // Try next URL if this one failed
          if (reconnectAttempts === 0) {
            // Try next URL in the list
            const nextUrl = possibleUrls[1];
            if (nextUrl) {
              console.log('Trying next URL:', nextUrl);
              wsUrl = nextUrl;
            }
          }
        };
      } catch (e) {
        console.log('❌ WebSocket connection error:', e);
        updateConnectionStatus(false);
      }
    }

    function sendInitialData() {
      if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
          type: 'request_status',
          id_partai: currentPartai
        }));
        refreshpartai();
      }
    }

    // Fungsi untuk update tampilan pemenang (HANYA dari server WebSocket)
    function updatePemenangDisplay() {
      // Hapus semua class winner terlebih dahulu
      $('#playerBlueCard, #playerRedCard').removeClass('winner');
      $('#blueWinnerIcon, #redWinnerIcon').hide();

      if (pemenang === 'biru') {
        $('#playerBlueCard').addClass('winner');
        $('#blueWinnerIcon').show();
        console.log('Menampilkan pemenang dari server: BIRU');
      } else if (pemenang === 'merah') {
        $('#playerRedCard').addClass('winner');
        $('#redWinnerIcon').show();
        console.log('Menampilkan pemenang dari server: MERAH');
      }
    }

    // Fungsi untuk mengupdate status tombol tukar partai
    function updateTukarPartaiButton(disable) {
      const tukarBtn = $('#tukarPartaiBtn');
      if (disable || statusDB === 'selesai' || serverSelesai) {
        tukarBtn.prop('disabled', true).addClass('disabled');
      } else {
        tukarBtn.prop('disabled', false).removeClass('disabled');
      }
    }

    // Initialize WebSocket
    $(document).ready(function() {
      connectWebSocket();

      // Initial render
      const jumlahBabak = parseInt(localStorage.getItem('jumlahBabak')) || 3;
      renderBabakButtons(jumlahBabak);
      updateDBStatusDisplay();
      updatePemenangDisplay(); // Tampilkan pemenang (awalnya kosong)
      updateTukarPartaiButton(isTimerRunning); // Set initial state tukar partai
    });

    function updateConnectionStatus(connected) {
      const indicator = document.getElementById('connectionIndicator');
      const text = document.getElementById('connectionText');

      if (connected) {
        indicator.className = 'connection-indicator connected';
        text.className = 'connection-text connected';
        text.innerHTML = 'Server: Connected';
      } else {
        indicator.className = 'connection-indicator disconnected';
        text.className = 'connection-text disconnected';
        text.innerHTML = 'Server: Disconnected';
      }
    }

    function updateDBStatusDisplay() {
      // Update banner
      let bannerHtml = '';
      if (statusDB === 'selesai') {
        bannerHtml = `
                    <div class="status-banner selesai">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle banner-icon"></i>
                            <div>
                                <div class="banner-text">Pertandingan Selesai</div>
                                <div class="banner-subtext">Partai ini telah dinyatakan selesai di database</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-flag-checkered fa-2x"></i>
                        </div>
                    </div>
                `;
      } else if (statusDB === 'proses') {
        bannerHtml = `
                    <div class="status-banner" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-play-circle banner-icon"></i>
                            <div>
                                <div class="banner-text">Pertandingan Sedang Berlangsung</div>
                                <div class="banner-subtext">Partai sedang dalam proses</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                        </div>
                    </div>
                `;
      } else {
        bannerHtml = '';
        $('#tukarPartaiBtn').prop('disabled', isTimerRunning);
      }
      $('#statusBannerContainer').html(bannerHtml);

      // Update info cards
      let infoCardsHtml = `
                <div class="col-md-3">
                    <div class="info-card ${statusDB === 'selesai' ? 'selesai' : ''}">
                        <div class="info-label">
                            <i class="fas fa-tag me-2"></i>Kelas
                        </div>
                        <div class="info-value">
                            ${partaiData.kelas}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card ${statusDB === 'selesai' ? 'selesai' : ''}">
                        <div class="info-label">
                            <i class="fas fa-ring me-2"></i>Gelanggang
                        </div>
                        <div class="info-value">
                            ${partaiData.gelanggang}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card ${statusDB === 'selesai' ? 'selesai' : ''}">
                        <div class="info-label">
                            <i class="fas fa-flag-checkered me-2"></i>Partai
                        </div>
                        <div class="info-value">
                            ${partaiData.partai}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card ${statusDB === 'selesai' ? 'selesai' : ''}">
                        <div class="info-label">
                            <i class="fas fa-clock me-2"></i>Status Partai
                        </div>
                        <div class="info-value status-partai">
                        </div>
                    </div>
                </div>
            `;
      $('#infoCardsContainer').html(infoCardsHtml);

      // Update player cards
      if (statusDB === 'selesai') {
        $('#timerCard').addClass('selesai');
        $('#dbStatusMessage').html('<div class="text-success"><i class="fas fa-check-circle me-1"></i> Database: Pertandingan selesai</div>');
      } else {
        $('#timerCard').removeClass('selesai');
        $('#dbStatusMessage').html('');
      }
    }

    function updateNavigationButtons(disable) {
      const prevBtn = $('#tombol_previous');
      const nextBtn = $('#tombol_next');

      if (disable) {
        prevBtn.addClass('nav-disabled').css('pointer-events', 'none').attr('tabindex', '-1');
        nextBtn.addClass('nav-disabled').css('pointer-events', 'none').attr('tabindex', '-1');
      } else {
        if (currentPartai > 1) {
          prevBtn.removeClass('nav-disabled').css('pointer-events', 'auto').attr('tabindex', '0');
        }
        if (currentPartai < totalPartai) {
          nextBtn.removeClass('nav-disabled').css('pointer-events', 'auto').attr('tabindex', '0');
        }
      }
    }

    function showServerNotification(title, message) {
      $('#serverMessage').text(message);
      $('#serverNotification').fadeIn(300);

      setTimeout(function() {
        $('#serverNotification').fadeOut(300);
      }, 5000);
    }

    function closeNotification() {
      $('#serverNotification').fadeOut(300);
    }

    function showServerStatus(message) {
      $('#serverStatusText').text(message);
      $('#serverStatusMessage').fadeIn(300).addClass('selesai');
    }

    function renderBabakButtons(jumlah) {
      const bar = $('#babak-bar');
      bar.empty();
      $('#total-babak').text(jumlah);

      for (let i = jumlah; i >= 1; i--) {
        const btn = $(`<button class="babak-btn" data-babak="${i}">${i}</button>`);
        btn.on('click', function() {
          setBabakUI(i);
          localStorage.setItem('babak', i);
          sendBabakToServer(i);
        });
        bar.append(btn);
      }

      const finishedRounds = JSON.parse(localStorage.getItem('finishedRounds') || '[]');
      finishedRounds.forEach(round => {
        $(`[data-babak="${round}"]`).addClass('completed');
      });

      setBabakUI(currentBabak > jumlah ? 1 : currentBabak);
      $('#current-babak').text(currentBabak);
    }

    function setBabakUI(babak) {
      currentBabak = babak;
      $('.babak-btn').each(function() {
        const btn = $(this);
        if (parseInt(btn.data('babak')) === babak) {
          btn.removeClass('btn-light').addClass('active');
        } else {
          btn.removeClass('active');
        }
      });
      $('#current-babak').text(babak);
    }

    function sendBabakToServer(babak) {
      if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
          type: 'set_round',
          round: babak,
          partai: "<?= htmlspecialchars($id_partai) ?>"
        }));
      }
    }

    function updateTimerDisplay() {
      const m = Math.floor(remaining / 60);
      const s = remaining % 60;
      const timeStr = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
      $('#waktu').text(timeStr);

      $('#waktu').removeClass('warning danger');
      if (remaining <= 10) {
        $('#waktu').addClass('danger');
      } else if (remaining <= 30) {
        $('#waktu').addClass('warning');
      }
    }

    function updateStatus() {
      const statusEl = $('#status');
      const statusP = $('.status-partai');
      statusEl.removeClass('status-berjalan status-jeda status-selesai status-menunggu');

      if (serverSelesai) {
        statusEl.addClass('status-selesai');
        statusEl.html('<i class="fas fa-check-circle me-2"></i>Selesai (Server)');
        statusP.text('Selesai');
      } else if (statusDB === 'selesai') {
        statusEl.addClass('status-selesai');
        statusEl.html('<i class="fas fa-check-circle me-2"></i>Selesai (DB)');
        statusP.text('Selesai');
      } else if (status === 'Berjalan') {
        statusEl.addClass('status-berjalan');
        statusEl.html('<i class="fas fa-play-circle me-2"></i>Berjalan');
        statusP.text('Sedang Berjalan');
      } else if (status === 'Jeda') {
        statusEl.addClass('status-jeda');
        statusEl.html('<i class="fas fa-pause-circle me-2"></i>Jeda');
        statusP.text('Sedang Berjalan');
      } else if (status === 'Selesai') {
        statusEl.addClass('status-selesai');
        statusEl.html('<i class="fas fa-stop-circle me-2"></i>Selesai');
        statusP.text('Selesai');
      } else {
        statusEl.addClass('status-menunggu');
        statusEl.html('<i class="fas fa-hourglass-half me-2"></i>Menunggu...');
        statusP.text('Belum Dimulai');
      }
    }

    function refreshpartai() {
      if (ws && ws.readyState === WebSocket.OPEN) {
        const data = {
          type: 'set_partai',
          partai: partaiData.partai,
          gelanggang: partaiData.gelanggang,
          babak: currentBabak,
          bbk: partaiData.babak,
          st: statusDB,
          kelas: partaiData.kelas,
          biru: {
            nama: partaiData.nm_biru,
            kontingen: partaiData.kontingen_biru
          },
          merah: {
            nama: partaiData.nm_merah,
            kontingen: partaiData.kontingen_merah
          }
        };

        ws.send(JSON.stringify(data));
        sendBabakToServer(currentBabak);
      }
    }

    // Event Listeners
    $('.tukarpartai').on('click', function() {
      localStorage.setItem('babak', 0);
      localStorage.removeItem('finishedRounds');

      if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
          type: 'tukar_partai'
        }));
      }

      document.location.href = "daftar.php?status=";
    });

    $('#refreshBtn').on('click', refreshpartai);

    // Fullscreen functions
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
      $('#exitfull').hide();
      $('#openfull').show();
    }

    // Fullscreen change listener
    document.addEventListener('fullscreenchange', handleFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
    document.addEventListener('mozfullscreenchange', handleFullscreenChange);
    document.addEventListener('MSFullscreenChange', handleFullscreenChange);

    function handleFullscreenChange() {
      if (document.fullscreenElement ||
        document.webkitFullscreenElement ||
        document.mozFullScreenElement ||
        document.msFullscreenElement) {
        $('#openfull').hide();
        $('#exitfull').show();
      } else {
        $('#openfull').show();
        $('#exitfull').hide();
      }
    }

    // Prevent text selection
    document.addEventListener('selectstart', e => e.preventDefault());

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
      if (ws) {
        ws.close();
      }
      if (reconnectTimeout) {
        clearTimeout(reconnectTimeout);
      }
    });
  </script>
</body>

</html>