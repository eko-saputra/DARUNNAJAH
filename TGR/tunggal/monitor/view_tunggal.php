<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex" />
    <title>Monitor Seni Ganda | IPSI Kota Dumai</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="shortcut icon" href="../../../assets/img/LogoIPSI.png" />
    <script src="../../../assets/jquery/jquery.min.js"></script>
    <script src="../../../assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Gaya untuk tombol fullscreen di sudut kiri bawah */
        .fullscreen-buttons {
            position: fixed;
            left: 10px;
            bottom: 70px;
            z-index: 1000;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 5px;
            padding: 5px;
        }

        #openfull,
        #exitfull {
            background: 0 0;
            border: none;
            cursor: pointer;
            padding: 0;
            margin: 0;
            text-align: center;
            width: 30px;
            height: 30px;
            line-height: 30px;
        }

        #openfull:active,
        #exitfull:active,
        #openfull:focus,
        #exitfull:focus {
            outline: 0;
        }

        #openfull svg,
        #exitfull svg {
            vertical-align: middle;
        }

        #exitfull {
            display: none;
        }

        /* Gaya untuk header */
        .main-header {
            background: linear-gradient(135deg, #0d6efd 0%, #198754 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-bottom: 5px solid #dc3545;
        }

        .silat-logo {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Gaya untuk kontainer informasi pesilat */
        .peserta-info-container {
            background: linear-gradient(to right, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 20px;
            margin: 20px auto;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .peserta-info-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, #dc3545 0%, #fd7e14 50%, #198754 100%);
        }

        .flag-animation {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.2));
            animation: wave 3s infinite alternate ease-in-out;
        }

        @keyframes wave {
            0% {
                transform: translateY(-50%) rotate(0deg);
            }

            100% {
                transform: translateY(-50%) rotate(5deg);
            }
        }

        /* Gaya untuk nama pesilat */
        .pesilat-name {
            font-size: 3.5rem !important;
            font-weight: 900 !important;
            color: #212529 !important;
            text-shadow: 3px 3px 0 rgba(13, 110, 253, 0.2);
            letter-spacing: 1px;
            line-height: 1.2;
            margin-bottom: 5px !important;
            background: linear-gradient(45deg, #212529 30%, #495057 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .kontingen-name {
            font-size: 2rem !important;
            font-weight: 700 !important;
            color: #dc3545 !important;
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.1);
            letter-spacing: 0.5px;
            padding-left: 10px;
            border-left: 5px solid #198754;
            border-right: 5px solid #198754;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 20px;
            border-radius: 10px;
            display: inline-block;
            margin-top: 10px;
        }

        /* Gaya untuk timer */
        .timer-container {
            background: linear-gradient(135deg, #212529 0%, #343a40 100%);
            border-radius: 20px;
            padding: 25px 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 5px solid #dc3545;
            position: relative;
            overflow: hidden;
        }

        .timer-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.05) 50%, transparent 70%);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        #timer {
            font-size: 4.5rem !important;
            font-weight: 900 !important;
            font-family: 'Arial Black', 'Segoe UI', sans-serif;
            color: #ffffff;
            text-shadow: 0 0 20px rgba(13, 110, 253, 0.8),
                0 0 40px rgba(13, 110, 253, 0.4),
                0 0 60px rgba(13, 110, 253, 0.2);
            letter-spacing: 3px;
            margin: 0;
            line-height: 1;
            animation: timer-glow 1.5s infinite alternate;
        }

        @keyframes timer-glow {
            from {
                text-shadow: 0 0 20px rgba(13, 110, 253, 0.8);
            }

            to {
                text-shadow: 0 0 30px rgba(13, 110, 253, 1),
                    0 0 50px rgba(13, 110, 253, 0.5);
            }
        }

        .timer-label {
            font-size: 1.2rem;
            font-weight: 600;
            color: #adb5bd;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        /* Gaya untuk kategori dan kelas */
        .event-info {
            font-weight: 700 !important;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            min-width: 200px;
            text-align: center;
            padding: 5px;
        }

        .event-info.babak {
            color: #ffffff;
        }

        .event-info.kategori {
            color: #ffffff;
        }

        .event-info.kelas {
            color: #ffffff;
        }

        /* Gaya untuk tabel nilai */
        #tabelnilai {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        #tabelnilai thead tr:first-child {
            background: linear-gradient(135deg, #343a40 0%, #495057 100%);
        }

        #tabelnilai thead tr:first-child td {
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            padding: 20px 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 3px solid #0d6efd;
        }

        #tabelnilai tbody tr:first-child td {
            font-size: 2.5rem;
            font-weight: 900;
            padding: 25px 15px;
            color: #212529;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transition: all 0.3s ease;
        }

        #tabelnilai tbody tr:first-child td:hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            transform: translateY(-2px);
        }

        .judge-header {
            min-width: 100px;
            position: relative;
            overflow: hidden;
        }

        .judge-header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 10%;
            right: 10%;
            height: 3px;
            background: linear-gradient(to right, transparent, #0d6efd, transparent);
        }

        .bg-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important;
            color: white;
        }

        .bg-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #6f42c1 100%) !important;
            color: white;
            animation: ready-pulse 2s infinite;
        }

        @keyframes ready-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
            }
        }

        /* Gaya untuk statistik fullscreen */
        .rekap-fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            z-index: 9999;
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .rekap-content {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            padding: 40px;
            width: 90%;
            max-width: 1200px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            /* border: 10px solid #0d6efd; */
        }

        .rekap-title {
            text-align: center;
            margin-bottom: 40px;
            color: #0d6efd;
            font-size: 3.5rem;
            font-weight: 900;
            text-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .rekap-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-bottom: 40px;
        }

        .rekap-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 5px solid #198754;
            transition: all 0.3s ease;
        }

        .rekap-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .rekap-item-label {
            font-size: 1rem;
            font-weight: 700;
            color: #495057;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .rekap-item-value {
            font-size: 4rem;
            font-weight: 900;
            color: #212529;
        }

        .rekap-total {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 15px 30px rgba(25, 135, 84, 0.3);
            /* border: 5px solid #dc3545; */
        }

        .rekap-total-label {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .rekap-total-value {
            font-size: 4rem;
            font-weight: 900;
            color: white;
            text-shadow: 3px 3px 0 rgba(0, 0, 0, 0.2);
        }

        .close-rekap {
            position: absolute;
            top: 30px;
            right: 30px;
            background: #dc3545;
            color: white;
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            z-index: 10000;
            /* box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4); */
            transition: all 0.3s ease;
        }

        .close-rekap:hover {
            background: #c82333;
            transform: scale(1.1);
        }

        /* Gaya untuk footer marquee */
        .footer-marquee {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(135deg, #212529 0%, #343a40 100%);
            color: white;
            padding: 15px 0;
            overflow: hidden;
            white-space: nowrap;
            border-top: 3px solid #0d6efd;
            z-index: 100;
        }

        .marquee-content {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 25s linear infinite;
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }


        .ipsi {
            width: 25%;
        }

        .dumai {
            width: 20%;
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .pesilat-name {
                font-size: 2.8rem !important;
            }

            .kontingen-name {
                font-size: 1.6rem !important;
            }

            #timer {
                font-size: 3.5rem !important;
            }

            .ipsi {
                width: 50%;
            }

            .dumai {
                width: 40%;
            }

            .rekap-grid {
                grid-template-columns: 1fr;
            }

            .rekap-title {
                font-size: 2.5rem;
            }

            .rekap-item-value {
                font-size: 3.5rem;
            }

            .rekap-total-value {
                font-size: 4.5rem;
            }
        }

        @media (max-width: 768px) {
            .pesilat-name {
                font-size: 2.2rem !important;
            }

            .kontingen-name {
                font-size: 1.3rem !important;
            }

            #timer {
                font-size: 2.8rem !important;
            }

            .event-info {
                font-size: 1.3rem !important;
                min-width: 150px;
            }

            .ipsi {
                width: 50%;
            }

            .dumai {
                width: 40%;
            }

            .rekap-content {
                padding: 20px;
            }

            .rekap-title {
                font-size: 2rem;
            }

            .rekap-item {
                padding: 20px;
            }

            .rekap-item-value {
                font-size: 2.8rem;
            }

            .rekap-total-value {
                font-size: 3.5rem;
            }
        }

        /* Winner Modal */
        .winner-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 10000;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.5s ease;
        }

        .winner-modal-content {
            text-align: center;
            padding: 50px;
            border-radius: 30px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            box-shadow: 0 0 50px rgba(255, 215, 0, 0.5);
            border: 5px solid gold;
            max-width: 800px;
            width: 90%;
        }

        .winner-modal-content.biru {
            border-color: #00e5ff;
            box-shadow: 0 0 50px rgba(0, 229, 255, 0.5);
            background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
        }

        .winner-modal-content.merah {
            border-color: #ff4757;
            box-shadow: 0 0 50px rgba(255, 71, 87, 0.5);
            background: linear-gradient(135deg, #b71c1c 0%, #d32f2f 100%);
        }

        .winner-title {
            font-size: 4rem;
            font-weight: 900;
            color: gold;
            text-shadow: 3px 3px 0 rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            letter-spacing: 5px;
        }

        .winner-sudut {
            font-size: 6rem;
            font-weight: 900;
            color: white;
            text-shadow: 3px 3px 0 rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        .winner-nilai {
            font-size: 5rem;
            font-weight: 900;
            color: #ffd700;
            text-shadow: 3px 3px 0 rgba(0, 0, 0, 0.3);
            font-family: 'Courier New', monospace;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <!-- Container untuk tombol fullscreen -->
    <div class="fullscreen-buttons">
        <button aria-label="Open Fullscreen" id="openfull" onclick="openFullscreen();">
            <svg width="24" height="24" viewBox="0 0 24 24">
                <path fill="#fff" d="M5,5H10V7H7V10H5V5M14,5H19V10H17V7H14V5M17,14H19V19H14V17H17V14M10,17V19H5V14H7V17H10Z" />
            </svg>
        </button>
        <button aria-label="Exit Fullscreen" id="exitfull" onclick="closeFullscreen();">
            <svg width="24" height="24" viewBox="0 0 24 24">
                <path fill="#fff" d="M14,14H19V16H16V19H14V14M5,14H10V19H8V16H5V14M8,5H10V10H5V8H8V5M19,8V10H14V5H16V8H19Z" />
            </svg>
        </button>
    </div>

    <!-- Modal Winner Fullscreen -->
    <div class="winner-modal" id="winnerModal" style="display: none;">
        <div class="winner-modal-content" id="winnerModalContent">
            <div class="winner-title">🏆 PEMENANG 🏆</div>
            <div class="winner-sudut" id="winnerSudut">SUDUT BIRU</div>
            <div class="winner-nilai" id="winnerNilai">0.00</div>
        </div>
    </div>

    <!-- Container untuk rekap nilai fullscreen -->
    <div class="rekap-fullscreen" id="rekapFullscreen">
        <!-- <button class="close-rekap" onclick="closeRekapFullscreen()">×</button> -->
        <div class="rekap-content">
            <h1 class="rekap-title">REKAPITULASI NILAI</h1>

            <div class="rekap-grid">
                <div class="rekap-item">
                    <div class="rekap-item-label">MEDIAN</div>
                    <div class="rekap-item-value" id="rekap-median">0.00</div>
                </div>

                <div class="rekap-item">
                    <div class="rekap-item-label">PENALTY</div>
                    <div class="rekap-item-value" id="rekap-penalty">0</div>
                </div>

                <div class="rekap-item">
                    <div class="rekap-item-label">TIME PERFORMANCE</div>
                    <div class="rekap-item-value" id="rekap-time">180</div>
                </div>

                <div class="rekap-item">
                    <div class="rekap-item-label">STANDAR DEVIASI</div>
                    <div class="rekap-item-value" id="rekap-stdev">0.000000</div>
                </div>
            </div>

            <div class="rekap-total">
                <div class="rekap-total-label">TOTAL NILAI AKHIR</div>
                <div class="rekap-total-value" id="rekap-total">0.00</div>
            </div>
        </div>
    </div>

    <!-- Header utama -->
    <div class="container-fluid main-header py-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <!-- Logo Kota Dumai -->
                <div class="col-3 text-center">
                    <img src="../../../assets/img/Lambang_Kota_Dumai.png" width="20%" class="mb-2 dumai">
                    <p class="m-0"><small class="text-light fw-bold">KOTA DUMAI</small></p>
                </div>

                <!-- Judul utama -->
                <div class="col-6 text-center">
                    <div class="bg-gradient bg-dark rounded-4 py-2 px-4">
                        <h3 class="fw-bold text-white mb-1">PENCAK <img src="../../../assets/img/silat.png" width="75"
                                class="silat-logo"> SILAT</h3>
                    </div>
                    <div class="row g-2 justify-content-center py-3">
                        <div class="col-auto">
                            <div class="event-info kategori text-center"></div>
                        </div>
                        <div class="col-auto">
                            <div class="event-info babak text-center"></div>
                        </div>
                        <div class="col-auto">
                            <div class="event-info kelas text-center"></div>
                        </div>
                    </div>
                </div>

                <div class="col-3 text-center">
                    <img src="../../../assets/img/LogoIPSI.png" class="mb-2 ipsi">
                    <p class="m-0"><small class="text-light fw-bold">IPSI</small></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Container informasi pesilat dan timer -->
    <div class="container-fluid mt-4">
        <div class="row align-items-center">
            <!-- Informasi Pesilat -->
            <div class="col-lg-8 col-md-7 mb-4">
                <div class="peserta-info-container position-relative text-center">
                    <div class="ps-5">
                        <h1 class="pesilat-name nm text-uppercase mb-0">?</h1>
                        <div class="mt-3">
                            <span class="kontingen-name kontingen">Kontingen : ?</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timer -->
            <div class="col-lg-4 col-md-5 mb-4">
                <div class="timer-container text-center">
                    <div id="timer" class="mb-2">00:00</div>
                    <div class="timer-label">WAKTU TAMPIL</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Nilai Juri -->
    <div class="container-fluid mt-4 mb-5">
        <div class="row">
            <div class="col">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <table id="tabelnilai" class="table mb-0">
                            <thead>
                                <tr id="judgeHeaders"></tr>
                                <tr id="judgeScores"></tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Smooth Marquee Footer -->
    <div class="footer-marquee">
        <div class="marquee-content">
            APLIKASI DigitalScore Pencak Silat Kota Dumai &copy; 2025 - IPSI Kota
            Dumai
        </div>
    </div>

    <script>
        // Variabel untuk menyimpan status juri dan nilai
        let judgeStatus = Array(4).fill(false);
        let judgeElements = [];
        let currentTimer = 0;
        let totalNilaiJuri = [];

        // Tambahkan variabel global untuk menyimpan nilai dewan
        let dewanValues = {
            hukum_1: 0,
            hukum_2: 0,
            hukum_3: 0,
            hukum_4: 0,
            hukum_5: 0
        };

        // Flag untuk menandai apakah rekap harus ditampilkan (hanya dari broadcast_selesai_seni)
        let shouldShowRekap = false;

        // Fungsi untuk menampilkan modal winner
        let winnerModalTimer = null;

        function showWinnerModal(sudut, nilai) {
            // Hapus timer sebelumnya jika ada
            if (winnerModalTimer) {
                clearTimeout(winnerModalTimer);
                winnerModalTimer = null;
            }

            const modal = document.getElementById('winnerModal');
            const content = document.getElementById('winnerModalContent');
            const sudutEl = document.getElementById('winnerSudut');
            const nilaiEl = document.getElementById('winnerNilai');

            // Set warna sesuai sudut
            content.className = 'winner-modal-content'; // reset class
            if (sudut.toLowerCase() === 'biru') {
                content.classList.add('biru');
                sudutEl.textContent = 'SUDUT BIRU';
            } else {
                content.classList.add('merah');
                sudutEl.textContent = 'SUDUT MERAH';
            }

            // Set nilai
            nilaiEl.textContent = parseFloat(nilai).toFixed(2);

            // Tampilkan modal
            modal.style.display = 'flex';

            // Auto close setelah 3 detik
            winnerModalTimer = setTimeout(() => {
                modal.style.display = 'none';
                winnerModalTimer = null;
            }, 3000);
        }

        // Fungsi untuk menghitung total penalty dari dewan
        function calculateDewanPenalty(dewanData) {
            if (!dewanData) return 0;

            // Jumlahkan semua nilai hukum (biasanya negatif)
            const total = Object.keys(dewanData).reduce((sum, key) => {
                if (key.startsWith('hukum_')) {
                    return sum + (parseFloat(dewanData[key]) || 0);
                }
                return sum;
            }, 0);

            console.log('📊 Total penalty dari dewan:', total);
            return Math.abs(total); // Mengembalikan nilai absolut untuk penalty
        }

        // Fungsi untuk menyimpan semua data ke localStorage
        function saveAllData() {
            // Simpan status juri ready
            const readyJuri = [];
            judgeStatus.forEach((status, index) => {
                if (status) readyJuri.push(index + 1);
            });
            localStorage.setItem('juri_ready', JSON.stringify(readyJuri));

            // Simpan total nilai juri
            localStorage.setItem('total_nilai_juri', JSON.stringify(totalNilaiJuri));

            // Simpan timer
            localStorage.setItem('current_timer', currentTimer.toString());

            // Simpan status juri
            localStorage.setItem('judge_status', JSON.stringify(judgeStatus));

            // Simpan nilai dewan
            localStorage.setItem('dewan_values', JSON.stringify(dewanValues));

            // console.log('All data saved to localStorage');
        }

        // Fungsi untuk memuat semua data dari localStorage
        function loadAllData() {
            // Load juri ready
            const storedReady = JSON.parse(localStorage.getItem('juri_ready') || '[]');
            storedReady.forEach((id_juri) => {
                if (id_juri >= 1 && id_juri <= 4) {
                    judgeStatus[id_juri - 1] = true;
                }
            });

            // Load timer
            currentTimer = parseInt(localStorage.getItem('current_timer') || '0');
            if (currentTimer > 0) {
                document.getElementById("timer").textContent = formatTime(currentTimer);
            }

            // Load judge status jika ada
            const storedJudgeStatus = JSON.parse(localStorage.getItem('judge_status') || 'null');
            if (storedJudgeStatus && storedJudgeStatus.length === 4) {
                judgeStatus = storedJudgeStatus;
            }

            // Load nilai dewan
            const storedDewan = JSON.parse(localStorage.getItem('dewan_values') || 'null');
            if (storedDewan) {
                dewanValues = storedDewan;
            }

            // Cek apakah ada partai yang sedang aktif
            const currentPartai = JSON.parse(localStorage.getItem('currentPartai') || 'null');
            if (currentPartai && ws && ws.readyState === WebSocket.OPEN) {
                // Minta data nilai terkini dari server
                const dataNilai = {
                    type: 'ambil_nilai_terkini_monitor',
                    id_jadwal: currentPartai.partai,
                    sudut: currentPartai.peserta.sudut,
                };

                const dataNilai1 = {
                    type: 'ambil_nilai_dewan_monitor',
                    id_jadwal: currentPartai.partai,
                    sudut: currentPartai.peserta.sudut,
                };

                console.log('📤 Memuat ulang: meminta data nilai dari server');
                ws.send(JSON.stringify(dataNilai));
                ws.send(JSON.stringify(dataNilai1));
            }

            // console.log('All data loaded from localStorage');
            return totalNilaiJuri;
        }

        // Fungsi untuk membuat tabel juri dengan nilai yang sudah ada
        function generateJudgeTable() {
            const judgeHeaders = document.getElementById("judgeHeaders");
            const judgeScores = document.getElementById("judgeScores");

            judgeHeaders.innerHTML = "";
            judgeScores.innerHTML = "";
            judgeElements = [];

            for (let i = 1; i <= 4; i++) {
                const headerCell = document.createElement("td");
                headerCell.textContent = `JURI ${i}`;
                headerCell.className = "judge-header bg-secondary text-light text-center";
                judgeHeaders.appendChild(headerCell);

                const scoreCell = document.createElement("td");

                // Cari nilai untuk juri ini dari data yang sudah ada
                const existingScore = totalNilaiJuri.find(item => item.juri === i);
                if (existingScore && existingScore.total !== undefined) {
                    scoreCell.textContent = parseFloat(existingScore.total).toFixed(2);
                } else {
                    scoreCell.textContent = "9.90";
                }

                scoreCell.className = "bg-secondary text-light text-center";
                scoreCell.id = `judge-score-${i}`;
                judgeScores.appendChild(scoreCell);

                judgeElements.push({
                    header: headerCell,
                    score: scoreCell,
                });
            }
        }

        // Fungsi untuk mengupdate tampilan juri
        function updateJudgeDisplay() {
            judgeStatus.forEach((isReady, index) => {
                const judge = judgeElements[index];
                if (judge) {
                    if (isReady) {
                        judge.header.className = "judge-header bg-primary text-light text-center";
                        judge.score.className = "bg-primary text-light text-center";
                    } else {
                        judge.header.className = "judge-header bg-secondary text-light text-center";
                        judge.score.className = "bg-secondary text-light text-center";
                    }
                }
            });

            // Simpan perubahan status juri
            saveAllData();
        }

        // Fungsi format waktu
        function formatTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        }

        // Fungsi untuk perhitungan statistik
        function calculateStats(penalty = 0) {
            if (totalNilaiJuri.length === 0) {
                console.log('No juri data available');
                return;
            }

            const totalValues = totalNilaiJuri.map(d => parseFloat(d.total)).sort((a, b) => a - b);

            if (totalValues.length === 0) {
                console.log('No total values available');
                return;
            }

            // Hitung Median
            let median = 0;
            const mid = Math.floor(totalValues.length / 2);
            if (totalValues.length % 2 === 0) {
                median = (totalValues[mid - 1] + totalValues[mid]) / 2;
            } else {
                median = totalValues[mid];
            }

            // Total akhir
            const totalAkhir = parseFloat((median - Math.abs(penalty)).toFixed(2));

            // Time performance default
            const timePerformance = 180;

            // Standar Deviasi
            const mean = totalValues.reduce((a, b) => a + b, 0) / totalValues.length;
            const variance = totalValues.reduce((acc, val) => acc + Math.pow(val - mean, 2), 0) / totalValues.length;
            const stdDev = parseFloat(Math.sqrt(variance).toFixed(10));

            // Simpan hasil perhitungan
            const stats = {
                median: median.toFixed(2),
                penalty: penalty,
                time: timePerformance,
                total: totalAkhir.toFixed(2),
                stdev: stdDev.toFixed(6)
            };

            localStorage.setItem('current_stats', JSON.stringify(stats));

            // Update tampilan rekap jika sedang terbuka
            updateRekapDisplay(stats);

            return stats;
        }

        // Variabel global untuk timer
        let rekapAutoHideTimer = null;

        // Fungsi untuk menampilkan rekap fullscreen dengan auto-hide
        function showRekapFullscreen() {
            const stats = JSON.parse(localStorage.getItem('current_stats') || '{}');
            if (Object.keys(stats).length > 0) {
                updateRekapDisplay(stats);
            }

            const rekapElement = document.getElementById('rekapFullscreen');
            rekapElement.style.display = 'flex';

            // Hapus timer sebelumnya jika ada
            if (rekapAutoHideTimer) {
                clearTimeout(rekapAutoHideTimer);
                rekapAutoHideTimer = null;
            }

            // Set timer untuk auto-hide setelah 5 detik
            rekapAutoHideTimer = setTimeout(() => {
                closeRekapFullscreen();
            }, 5000);

            // Untuk debugging
            console.log('Rekap ditampilkan, auto-hide dalam 5 detik');
        }

        // Fungsi untuk menutup rekap fullscreen
        function closeRekapFullscreen() {
            const rekapElement = document.getElementById('rekapFullscreen');
            rekapElement.style.display = 'none';

            // Hapus timer
            if (rekapAutoHideTimer) {
                clearTimeout(rekapAutoHideTimer);
                rekapAutoHideTimer = null;
            }

            console.log('Rekap ditutup');
        }

        // Fungsi untuk update tampilan rekap
        function updateRekapDisplay(stats) {
            document.getElementById('rekap-median').textContent = stats.median || '0.00';
            document.getElementById('rekap-penalty').textContent = stats.penalty || '0';
            document.getElementById('rekap-time').textContent = stats.time || '180';
            document.getElementById('rekap-stdev').textContent = stats.stdev || '0.000000';
            document.getElementById('rekap-total').textContent = stats.total || '0.00';
        }

        // WebSocket
        const hostname = window.location.hostname;
        const ws = new WebSocket('ws://' + hostname + ':3000');

        $(document).ready(function() {
            // Load semua data yang tersimpan
            loadAllData();

            // Generate tabel juri dengan data yang sudah ada
            generateJudgeTable();
            updateJudgeDisplay();

            // Setup event untuk auto-save
            setInterval(saveAllData, 5000); // Auto-save setiap 5 detik

            // Setup keyboard shortcut untuk rekap (Ctrl + R)
            $(document).keydown(function(e) {
                if (e.ctrlKey && e.key === 'r') {
                    e.preventDefault();
                    showRekapFullscreen();
                }

                if (e.key === 'Escape') {
                    closeRekapFullscreen();
                }
            });

            // WebSocket connection
            ws.onopen = function() {
                console.log('WebSocket connected');
                saveAllData(); // Simpan state saat koneksi terbuka

                // Ambil data partai dari localStorage
                const dataString = localStorage.getItem('currentPartai');
                if (dataString) {
                    try {
                        const data = JSON.parse(dataString);
                        $('.nm').text(data.peserta.nama.toUpperCase());
                        $('.kontingen').text(`Kontingen: ${data.peserta.kontingen.toUpperCase()}`);
                        $('.babak').text(data.babak.toUpperCase());
                        $('.kategori').text("SENI " + data.kategori.toUpperCase());
                        $('.kelas').text(data.kelas.toUpperCase());

                        // Minta data nilai terkini dari server saat koneksi terbuka
                        const dataNilai = {
                            type: 'ambil_nilai_terkini_monitor',
                            id_jadwal: data.partai,
                            sudut: data.peserta.sudut,
                        };

                        const dataNilai1 = {
                            type: 'ambil_nilai_dewan_monitor',
                            id_jadwal: data.partai,
                            sudut: data.peserta.sudut,
                        };

                        console.log('📤 Koneksi terbuka: meminta data nilai dari server');
                        ws.send(JSON.stringify(dataNilai));
                        ws.send(JSON.stringify(dataNilai1));
                    } catch (e) {
                        console.error("Error parsing data:", e);
                    }
                }
            };

            // WebSocket message handler
            ws.onmessage = function(event) {
                try {
                    const message = JSON.parse(event.data);

                    if (message.type === 'partai_data_tunggal') {
                        // Reset data untuk partai baru
                        judgeStatus.fill(false);
                        totalNilaiJuri = [];
                        dewanValues = {
                            hukum_1: 0,
                            hukum_2: 0,
                            hukum_3: 0,
                            hukum_4: 0,
                            hukum_5: 0
                        };

                        // Bersihkan localStorage untuk partai baru
                        localStorage.removeItem('juri_ready');
                        localStorage.removeItem('total_nilai_juri');
                        localStorage.removeItem('skor_per_jurus');
                        localStorage.removeItem('jurus_aktif');
                        localStorage.removeItem('hasil_per_gerakan');
                        localStorage.removeItem('selected_stamina');
                        localStorage.removeItem('current_stats');
                        localStorage.removeItem('dewan_values');
                        localStorage.removeItem('penalty');

                        // Reset flag rekap
                        shouldShowRekap = false;

                        generateJudgeTable();
                        updateJudgeDisplay();

                        const p = message.data;
                        console.log('📥 Data partai diterima:', p);
                        localStorage.setItem('partai', p.partai);
                        localStorage.setItem('currentPartai', JSON.stringify(p));

                        // Update UI dengan data baru
                        $('.nm').text(p.peserta.nama.toUpperCase());
                        $('.babak').text(p.babak.toUpperCase());
                        $('.kontingen').text(`Kontingen: ${p.peserta.kontingen.toUpperCase()}`);
                        $('.kategori').text(p.kategori.toUpperCase());
                        $('.kelas').text(p.kelas.toUpperCase());

                        // Minta data nilai terkini dari server
                        const dataNilai = {
                            type: 'ambil_nilai_terkini_monitor',
                            id_jadwal: p.partai,
                            sudut: p.peserta.sudut,
                        };

                        const dataNilai1 = {
                            type: 'ambil_nilai_dewan_monitor',
                            id_jadwal: p.partai,
                            sudut: p.peserta.sudut,
                        };

                        ws.send(JSON.stringify(dataNilai));
                        ws.send(JSON.stringify(dataNilai1));
                        saveAllData();
                    }

                    if (message.type === 'partai_selesai') {
                        console.log('🏆 Partai selesai, pemenang:', message);
                        // message memiliki properti: partai, pemenang, nilai_biru, nilai_merah
                        const sudut = message.pemenang; // 'biru' atau 'merah'
                        const nilai = sudut === 'biru' ? message.nilai_biru : message.nilai_merah;
                        showWinnerModal(sudut, nilai);
                    }

                    if (message.type === 'ambil_nilai_terkini_monitor_success') {
                        console.log('📥 Nilai terkini diterima dari server:', message.data);

                        // Reset totalNilaiJuri
                        totalNilaiJuri = [];

                        // Proses data dari server
                        if (message.data && Array.isArray(message.data) && message.data.length > 0) {
                            // Konversi data dari server ke format yang digunakan aplikasi
                            message.data.forEach(item => {
                                const wrong = parseFloat(item.wrong) || 0;
                                const stamina = parseFloat(item.stamina) || 0;

                                // Rumus: total = 9.90 - (wrong * 0.01) + stamina
                                const total = (9.90 - (wrong * 0.01)) + stamina;

                                totalNilaiJuri.push({
                                    juri: parseInt(item.id_juri),
                                    id_nilai: item.id_nilai,
                                    wrong: wrong,
                                    stamina: stamina,
                                    total: parseFloat(total)
                                });
                            });

                            console.log('✅ Data nilai setelah diproses:', totalNilaiJuri);

                            // Update tampilan nilai juri
                            totalNilaiJuri.forEach((item) => {
                                const juriIndex = item.juri - 1;
                                if (judgeElements[juriIndex]) {
                                    judgeElements[juriIndex].score.textContent = item.total.toFixed(2);
                                }
                            });

                            // Hitung statistik jika semua juri sudah ada nilainya
                            if (totalNilaiJuri.length === 4) {
                                // Ambil penalty dari dewan yang sudah tersimpan
                                const totalPenalty = calculateDewanPenalty(dewanValues);
                                localStorage.setItem('penalty', totalPenalty.toString());
                                calculateStats(totalPenalty);
                            }

                            // Simpan ke localStorage sebagai cache
                            saveAllData();
                        } else {
                            console.log('⚠️ Data nilai kosong dari server');
                            // Reset nilai ke 9.90 untuk semua juri
                            for (let i = 1; i <= 4; i++) {
                                totalNilaiJuri.push({
                                    juri: i,
                                    wrong: 0,
                                    stamina: 0,
                                    total: 9.90
                                });

                                const juriIndex = i - 1;
                                if (judgeElements[juriIndex]) {
                                    judgeElements[juriIndex].score.textContent = "9.90";
                                }
                            }
                            saveAllData();
                        }
                    }

                    // Handler untuk data dewan
                    if (message.type === 'ambil_nilai_terkini_dewan_monitor_success') {
                        // console.log('📥 Nilai dewan terkini diterima dari server:', message.data);

                        // Simpan nilai dewan
                        if (message.data && message.data.length > 0) {
                            const dewanData = message.data[0]; // Ambil data pertama
                            console.log('📥 Nilai dewan:', dewanData);
                            dewanValues = {
                                hukum_1: parseFloat(dewanData.hukum_1) || 0,
                                hukum_2: parseFloat(dewanData.hukum_2) || 0,
                                hukum_3: parseFloat(dewanData.hukum_3) || 0,
                                hukum_4: parseFloat(dewanData.hukum_4) || 0,
                                hukum_5: parseFloat(dewanData.hukum_5) || 0
                            };

                            // Hitung total penalty
                            const totalPenalty = calculateDewanPenalty(dewanValues);
                            localStorage.setItem('penalty', totalPenalty.toString());
                            localStorage.setItem('dewan_values', JSON.stringify(dewanValues));

                            console.log('✅ Nilai dewan tersimpan:', dewanValues);
                            console.log('✅ Total penalty:', totalPenalty);

                            // Update statistik jika data juri sudah ada
                            if (totalNilaiJuri.length === 4) {
                                calculateStats(totalPenalty);
                            }
                        }
                    }

                    // if (message.type === 'ambil_nilai_terkini_dewan_monitor_success') {
                    //     console.log('📥 Nilai dewan terkini diterima dari server:', message.data);

                    //     // Proses data jurus aktif dan hasil per gerakan
                    //     if (message.data && message.data.jurus_aktif) {
                    //         localStorage.setItem('jurus_aktif', JSON.stringify(message.data.jurus_aktif));
                    //     }

                    //     if (message.data && message.data.hasil_per_gerakan) {
                    //         localStorage.setItem('hasil_per_gerakan', JSON.stringify(message.data.hasil_per_gerakan));
                    //     }
                    // }

                    if (message.type === 'update_total_nilai') {
                        // Update total nilai juri dari server
                        console.log('📥 Update total nilai:', message.data);

                        message.data.forEach(newItem => {
                            const index = totalNilaiJuri.findIndex(item => item.juri === newItem.juri);
                            if (index !== -1) {
                                totalNilaiJuri[index] = newItem;
                            } else {
                                totalNilaiJuri.push(newItem);
                            }

                            // Update tampilan langsung
                            const juriIndex = newItem.juri - 1;
                            if (judgeElements[juriIndex]) {
                                judgeElements[juriIndex].score.textContent = parseFloat(newItem.total).toFixed(2);
                            }
                        });

                        saveAllData();
                    }

                    if (message.type === 'broadcast_selesai_seni') {
                        const penalty = message.data.penalty || 0;
                        localStorage.setItem('penalty', penalty);

                        // Transform data
                        const transformed = message.data.rekap_nilai.map(item => ({
                            juri: parseInt(item.id_juri),
                            rata_rata_jurus: item.rata_rata_jurus,
                            stamina: item.stamina,
                            total: item.total,
                            penalty: penalty,
                        }));

                        // Update data
                        totalNilaiJuri = transformed;

                        // Update tampilan nilai juri
                        transformed.forEach((item) => {
                            const juriIndex = item.juri - 1;
                            if (judgeElements[juriIndex]) {
                                judgeElements[juriIndex].score.textContent = parseFloat(item.total).toFixed(2);
                            }
                        });

                        // Hitung dan tampilkan statistik
                        const stats = calculateStats(penalty);

                        // Set flag bahwa rekap harus ditampilkan
                        shouldShowRekap = true;

                        // Tampilkan rekap fullscreen secara otomatis
                        setTimeout(() => {
                            showRekapFullscreen();

                            // Auto-close setelah 5 detik
                            setTimeout(() => {
                                closeRekapFullscreen();
                            }, 5000);
                        }, 1000);

                        saveAllData();
                    }

                    if (message.type === "juri_ready") {
                        const id_juri = parseInt(message.id_juri);
                        if (!isNaN(id_juri) && id_juri >= 1 && id_juri <= 4) {
                            let readyList = JSON.parse(localStorage.getItem('juri_ready') || '[]');
                            if (!readyList.includes(id_juri)) {
                                readyList.push(id_juri);
                                localStorage.setItem('juri_ready', JSON.stringify(readyList));
                            }
                            judgeStatus[id_juri - 1] = true;
                            updateJudgeDisplay();
                        }
                    }

                    if (message.type === "tick") {
                        currentTimer = message.remaining;
                        const timerElement = document.getElementById("timer");
                        if (timerElement) {
                            timerElement.textContent = formatTime(currentTimer);
                            // Tambahkan efek peringatan saat waktu hampir habis
                            if (currentTimer <= 30) {
                                timerElement.style.color = '#dc3545';
                                timerElement.style.animation = 'timer-glow 0.5s infinite alternate';
                            } else {
                                timerElement.style.color = '#ffffff';
                                timerElement.style.animation = 'timer-glow 1.5s infinite alternate';
                            }
                        }
                        saveAllData();
                    }

                    if (message.type === "stopped") {
                        currentTimer = 0;
                        const timerElement = document.getElementById("timer");
                        if (timerElement) {
                            timerElement.textContent = '00:00';
                            timerElement.style.color = '#ffffff';
                        }
                        saveAllData();
                    }

                } catch (e) {
                    console.error('Error parsing message:', e);
                }
            };

            ws.onclose = function() {
                console.log('WebSocket disconnected');
                saveAllData(); // Simpan data saat koneksi ditutup
            };
        });

        // Fullscreen functions
        var elem = document.documentElement;

        function openFullscreen() {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
            document.getElementById("openfull").style.display = "none";
            document.getElementById("exitfull").style.display = "block";
        }

        function closeFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            document.getElementById("openfull").style.display = "block";
            document.getElementById("exitfull").style.display = "none";
        }

        // Event listener untuk fullscreen change
        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('mozfullscreenchange', handleFullscreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
        document.addEventListener('msfullscreenchange', handleFullscreenChange);

        function handleFullscreenChange() {
            if (!document.fullscreenElement && !document.mozFullScreenElement &&
                !document.webkitFullscreenElement && !document.msFullscreenElement) {
                document.getElementById("openfull").style.display = "block";
                document.getElementById("exitfull").style.display = "none";
            }
        }

        // Initialize on load
        window.onload = function() {
            // Load data terlebih dahulu
            loadAllData();
            generateJudgeTable();
            updateJudgeDisplay();

            // HAPUS bagian yang menampilkan rekap otomatis saat load
            // Rekap hanya akan muncul dari event broadcast_selesai_seni
            // atau dari shortcut keyboard Ctrl+R

            // Inisialisasi marquee
            function createSmoothMarquee() {
                const marqueeContent = document.querySelector(".marquee-content");
                const contentWidth = marqueeContent.scrollWidth;
                const containerWidth =
                    document.querySelector(".footer-marquee").offsetWidth;

                // Duplikat konten untuk perulangan mulus
                marqueeContent.innerHTML += " &nbsp; " + marqueeContent.innerHTML;

                // Sesuaikan durasi animasi berdasarkan panjang konten
                const duration = Math.max(20, contentWidth / 50);
                marqueeContent.style.animationDuration = duration + "s";
            }

            createSmoothMarquee();
        };
    </script>
</body>

</html>