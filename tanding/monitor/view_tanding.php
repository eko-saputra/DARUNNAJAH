<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex">
	<title>Monitor Tanding | IPSI Kota Dumai</title>
	<link rel="shortcut icon" href="../../assets/img/LogoIPSI.png">
	<link rel="stylesheet" href="../../assets/bootstrap/dist/css/bootstrap.min.css">
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

		/* Gaya lainnya tetap sama */
		#tabelnilai {
			width: 100%;
			border-collapse: collapse;
			font-size: 1.2rem;
		}

		#tabelnilai td {
			padding: 10px 12px;
			text-align: center;
			border-bottom: 1px solid #dee2e6;
		}

		#tabelnilai tr:first-child td {
			font-weight: bold;
			border-bottom: 2px solid #dee2e6;
		}

		.judge-header {
			min-width: 70px;
		}

		.bg-secondary {
			background-color: #6c757d !important;
			color: white;
		}

		.bg-primary {
			background-color: #0d6efd !important;
			color: white;
		}

		.footer-marquee {
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			background-color: #212529;
			color: white;
			padding: 10px 0;
			overflow: hidden;
			white-space: nowrap;
		}

		.marquee-content {
			display: inline-block;
			padding-left: 100%;
			animation: marquee 20s linear infinite;
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
	</style>
</head>

<body>
	<!-- Container untuk tombol fullscreen -->
	<div class="fullscreen-buttons">
		<button aria-label="Open Fullscreen" id="openfull" onclick="openFullscreen();">
			<svg width="24" height="24" viewBox="0 0 24 24">
				<path fill="#fff"
					d="M5,5H10V7H7V10H5V5M14,5H19V10H17V7H14V5M17,14H19V19H14V17H17V14M10,17V19H5V14H7V17H10Z" />
			</svg>
		</button>
		<button aria-label="Exit Fullscreen" id="exitfull" onclick="closeFullscreen();">
			<svg width="24" height="24" viewBox="0 0 24 24">
				<path fill="#fff"
					d="M14,14H19V16H16V19H14V14M5,14H10V19H8V16H5V14M8,5H10V10H5V8H8V5M19,8V10H14V5H16V8H19Z" />
			</svg>
		</button>
	</div>

	<!-- Konten utama tetap sama -->
	<!-- <div class="container-fluid shadow-sm main-header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-6 m-auto bg-gradient bg-dark text-center fw-bold py-2 text-white rounded-bottom-5">
					<h4 class="fw-bold">PENCAK <img src="../../assets/img/silat.png" width="50"> SILAT</h4>
				</div>
			</div>
			<div class="row py-2">
				<div class="col-2 text-center">
					<img src="../../assets/img/Lambang_Kota_Dumai.png" width="25%">
					<p class="mt-2"><small class="text-light fw-bold">KOTA DUMAI</small></p>
				</div>
				<div class="col-2 text-white justify-content-center fw-bold text-uppercase d-flex align-items-center">
					<h5 id="gelanggang"></h5>
				</div>
				<div class="col-2 justify-content-center fw-bold text-uppercase text-white d-flex align-items-center">
					<h5 id="bbk"></h5>
				</div>
				<div class="col-4 justify-content-center fw-bold text-uppercase text-white d-flex align-items-center">
					<h5 id="kelas"></h5>
				</div>
				<div class="col-2 text-center">
					<img src="../../assets/img/LogoIPSI.png" width="32%">
					<p class="mt-2">
						<small class="text-light fw-bold">IPSI</small>
					</p>
				</div>
			</div>
		</div>
	</div> -->
	<div class="container-fluid main-header py-3">
		<div class="container-fluid">
			<div class="row align-items-center">
				<!-- Logo Kota Dumai -->
				<div class="col-3 text-center">
					<img src="../../assets/img/Lambang_Kota_Dumai.png" width="20%" class="mb-2 dumai">
					<p class="m-0"><small class="text-light fw-bold">KOTA DUMAI</small></p>
				</div>

				<!-- Judul utama -->
				<div class="col-6 text-center">
					<div class="bg-gradient bg-dark rounded-4 py-2 px-4">
						<h3 class="fw-bold text-white mb-1">PENCAK <img src="../../assets/img/silat.png" width="75"
								class="silat-logo"> SILAT</h3>
					</div>
					<div class="row g-2 justify-content-center py-3">
						<div class="col-auto">
							<div class="event-info text-light text-uppercase gelanggang text-center p-1" id="gelanggang"></div>
						</div>
						<div class="col-auto">
							<div class="event-info text-light text-uppercase bbk text-center p-1" id="bbk"></div>
						</div>
						<div class="col-auto">
							<div class="event-info text-light text-uppercase kelas text-center p-1 px-3" id="kelas"></div>
						</div>
					</div>
				</div>

				<div class="col-3 text-center">
					<img src="../../assets/img/LogoIPSI.png" class="mb-2 ipsi">
					<p class="m-0"><small class="text-light fw-bold">IPSI</small></p>
				</div>
			</div>
		</div>
	</div>
	<div class=" my-2" style="width:97%;margin:0 auto;">
		<div class="row">
			<div class="col-5 text-start text-uppercase fw-bold">
				<div class="row">
					<!-- <div class="col-lg-5 col-md-12 p-1 d-flex align-items-center" style="position:relative;">
						<img src="../../assets/img/biru.png" width="75"
							class="rounded-circle" style="margin-left:70px;">
						<img src="../../assets/img/bendera.gif" width="100"
							style="position: absolute; z-index:-1; margin-right:30px">
					</div> -->
					<div class="col-lg-12 col-md-12">
						<div class="d-flex align-items-center p-2">
							<!-- Logo di kiri -->
							<div class="logo-silat d-flex align-items-center justify-content-center me-3"
								style="width: 50px; height: 50px; background: linear-gradient(135deg, #1e3c72 0%, #668bea 100%); border-radius:25px; color: white;">
								<img src="../../assets/img/silat.png" width="100%">
							</div>

							<div>
								<h3 class="text-muted nm_biru mb-0 fw-bold"></h3>
								<span class="text-primary fw-bold text-uppercase kontingen_biru"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-2 text-center fw-bold">
				<h1 id="timer" class="bg-dark border border-1 border-muted text-light rounded py-2"
					style="font-size: 50px">00:00</h1>
			</div>
			<div class="col-5 text-end text-uppercase fw-bold">
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="d-flex align-items-center justify-content-end p-2">
							<!-- Teks di kiri (rata kanan) -->
							<div class="text-end me-3">
								<h3 class="text-muted nm_merah mb-0"></h3>
								<span class="text-danger fw-bold text-uppercase kontingen_merah"></span>
							</div>

							<!-- Logo di kanan -->
							<div class="logo-silat d-flex align-items-center justify-content-center"
								style="width: 50px; height: 50px; background: linear-gradient(135deg, #8B0000 0%, #FF0000 100%); border-radius:25px; color: white;">
								<img src="../../assets/img/silat.png" width="100%">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<style>
				.kiri,
				.kanan {
					flex: 0 0 calc((100% - (100% / 12)) / 2);
					/* (100% - col-1) / 2 */
					max-width: calc((100% - (100% / 12)) / 2);
				}
			</style>
			<div class="row">
				<div class="col kiri">
					<table width="100%" class="table border">
						<tr>
							<td class="border binaan1 text-center" width="15%" valign="middle"><img
									src="../../assets/img/binaan.png" width="30"></td>
							<td class="border binaan2 text-center" width="15%" valign="middle"><img
									src="../../assets/img/binaan.png" width="30"></td>
							<td class="border text-uppercase text-light shadow-sm fw-bold nilai_biru text-center"
								rowspan="4" valign="middle" width="70%" style="height:550px; font-size:400px; font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;background: #1f95de;
background: linear-gradient(0deg,rgba(31, 149, 222, 1) 0%, rgba(31, 0, 0, 1) 100%);"></td>
						</tr>
						<tr>
							<td class="border teguran1 text-center" valign="middle"><img src="../../assets/img/teguran1.png"
									width="30">
							</td>
							<td class="border teguran2 text-center" valign="middle"><img src="../../assets/img/teguran2.png"
									width="30">
							</td>
						</tr>
						<tr>
							<td class="border peringatan1 text-center" valign="middle"><img
									src="../../assets/img/peringatan.png" width="30"></td>
							<td class="border peringatan2 text-center" valign="middle"><img
									src="../../assets/img/peringatan.png" width="30"></td>
						</tr>
						<tr>
							<td class="border peringatan3" colspan="2" valign="middle" align="center"><img
									src="../../assets/img/peringatan.png" width="30"></td>
						</tr>
					</table>
				</div>
				<div class="col-1 justify-content-center d-flex align-items-center">
					<table width="100%" align="center" class="fw-bold text-dark">
						<tbody id="babakContainer"></tbody>
					</table>
				</div>
				<div class="col kanan">
					<table width="100%" class="table border">
						<tr>
							<td class="border text-uppercase text-light shadow-sm fw-bold nilai_merah text-center"
								rowspan="4" valign="middle" width="70%" style="height:550px; font-size:400px; font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;background: #1f95de;
background: linear-gradient(0deg,rgba(222, 31, 31, 1) 0%, rgba(31, 0, 0, 1) 100%);"></td>
							<td class="borde binaan1merah text-center" width="15%" valign="middle"><img
									src="../../assets/img/binaan.png" width="30"></td>
							<td class="border binaan2merah text-center" width="15%" valign="middle"><img
									src="../../assets/img/binaan.png" width="30"></td>
						</tr>
						<tr>
							<td class="border teguran1merah text-center" valign="middle"><img
									src="../../assets/img/teguran1.png" width="30"></td>
							<td class="border teguran2merah text-center" valign="middle"><img
									src="../../assets/img/teguran2.png" width="30"></td>
						</tr>
						<tr>
							<td class="border peringatan1merah text-center" valign="middle"><img
									src="../../assets/img/peringatan.png" width="30"></td>
							<td class="border peringatan2merah text-center" valign="middle"><img
									src="../../assets/img/peringatan.png" width="30"></td>
						</tr>
						<tr>
							<td class="border peringatan3merah text-center" colspan="2" valign="middle" align="center"><img
									src="../../assets/img/peringatan.png" width="30"></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="container-fluid mt-2">
			<div class="row">
				<div class="col-5">
					<table width="100%">
						<tr>
							<td class="text-center border text-uppercase p-1">
								<div class="d-grid gap-2">
									<button class="btn btn-outline-light text-dark border p-2 j-pBIRU1">
										<h4 class="fw-bold">J1</h4 class="fw-bold">
									</button>
								</div>
							</td>
							<td class="text-center border text-uppercase p-1">
								<div class="d-grid gap-2">
									<button class="btn btn-outline-light text-dark border p-2 j-pBIRU2">
										<h4 class="fw-bold">J2</h4 class="fw-bold">
									</button>
								</div>
							</td>
							<td class="text-center border text-uppercase p-1">
								<div class="d-grid gap-2">
									<button class="btn btn-outline-light text-dark border p-2 j-pBIRU3">
										<h4 class="fw-bold">J3</h4 class="fw-bold">
									</button>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="col-2 d-flex align-items-center justify-content-center"><img src="../../assets/img/pukulan1.png"
						class="img-fluid" width="14%"><img src="../../assets/img/pukulan2.png" class="img-fluid"
						width="15%"></div>
				<div class="col-5">
					<table width="100%">
						<tr>
							<td class="text-center border text-uppercase p-1">
								<div class="d-grid gap-2">
									<button class="btn btn-outline-light text-dark border p-2 j-pMERAH1">
										<h4 class="fw-bold">J1</h4 class="fw-bold">
									</button>
								</div>
							</td>
							<td class="text-center border text-uppercase p-1">
								<div class="d-grid gap-2">
									<button class="btn btn-outline-light text-dark border p-2 j-pMERAH2">
										<h4 class="fw-bold">J2</h4 class="fw-bold">
									</button>
								</div>
							</td>
							<td class="text-center border text-uppercase p-1">
								<div class="d-grid gap-2">
									<button class="btn btn-outline-light text-dark border p-2 j-pMERAH3">
										<h4 class="fw-bold">J3</h4 class="fw-bold">
									</button>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-5">
					<table width="100%">
						<tr>
							<td class="text-center border text-uppercase p-1">
								<div class="d-grid gap-2">
									<button class="btn btn-outline-light text-dark border p-2 j-kBIRU1">
										<h4 class="fw-bold">J1</h4 class="fw-bold">
									</button>
								</div>
							</td>
							<td class="text-center border text-uppercase p-1">
								<div class="d-grid gap-2">
									<button class="btn btn-outline-light text-dark border p-2 j-kBIRU2">
										<h4 class="fw-bold">J2</h4 class="fw-bold">
									</button>
								</div>
							</td>
							<td class="text-center border text-uppercase p-1">
								<div class="d-grid gap-2">
									<button class="btn btn-outline-light text-dark border p-2 j-kBIRU3">
										<h4 class="fw-bold">J3</h4 class="fw-bold">
									</button>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="col-2 text-center d-flex align-items-center justify-content-center"><img
						src="../../assets/img/tendangan (1).png" class="img-fluid" width="15%"></div>
				<div class="col-5">
					<table width="100%">
						<tr>
							<td class="text-center border text-uppercase p-1">
								<div class="d-grid gap-2">
									<button class="btn btn-outline-light text-dark border p-2 j-kMERAH1">
										<h4 class="fw-bold">J1</h4 class="fw-bold">
									</button>
								</div>
							</td>
							<td class="text-center border text-uppercase p-1">
								<div class="d-grid gap-2">
									<button class="btn btn-outline-light text-dark border p-2 j-kMERAH2">
										<h4 class="fw-bold">J2</h4 class="fw-bold">
									</button>
								</div>
							</td>
							<td class="text-center border text-uppercase p-1">
								<div class="d-grid gap-2">
									<button class="btn btn-outline-light text-dark border p-2 j-kMERAH3">
										<h4 class="fw-bold">J3</h4 class="fw-bold">
									</button>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<!-- Smooth Marquee Footer -->
		<div class="footer-marquee">
			<div class="marquee-content">
				APLIKASI DigitalScore Pencak Silat Kota Dumai &copy; <?= date('Y') ?> - IPSI Kota
				Dumai
			</div>
		</div>

		<!-- Modal -->
		<style>
			/* Modal body: Menjaga elemen terpusat */
			.verifikasi {
				display: flex;
				flex-direction: column;
				/* Vertikal stack elemen */
				justify-content: center;
				align-items: center;
				padding: 20px;
			}

			/* Menambahkan jarak antar elemen */
			.spinner-container {
				margin-top: 20px;
				/* Menambahkan jarak antara judul dan spinner */
			}

			.verifikasi,
			.diskualifikasi h1 {
				font-weight: normal;
				font-size: 10rem;
				/* ukuran default */
			}
		</style>
		<div class="modal fade" id="verifikasiModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-fullscreen">
				<div class="modal-content verifikasi-bg">
					<div class="modal-body verifikasi text-center text-dark text-uppercase">
						<!-- Judul -->
						<h1>VERIFIKASI <b class="jenis text-uppercase"></b></h1>

						<!-- Spinner loading di bawah judul -->
						<div class="spinner-container">
							<div class="d-flex justify-content-center">
								<div class="spinner-border" role="status">
									<span class="visually-hidden">Loading...</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="modal fade" id="diskualifikasiModal" tabindex="-1" aria-labelledby="exampleModalLabel"
			aria-hidden="true">
			<div class="modal-dialog modal-fullscreen">
				<div class="modal-content bg-dark bg-gradient">
					<div class="modal-body d-flex flex-column justify-content-center align-items-center vh-100">
						<h1 class="text-light display-1">DISKUALIFIKASI</h1>
						<h3 class="text-light mt-3 sudut" style="font-size: 5rem;"></h3>
					</div>

				</div>
			</div>
		</div>


		<div class="modal fade" id="winnerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-fullscreen">
				<div class="modal-content winner">
					<div class="modal-body winner text-center d-flex align-items-center justify-content-center">
						<div>
							<h1 class="winner-title fw-bold text-white" style="font-size: 100px;">WINNER</h1>
							<h1 class="winner-data text-uppercase p-2 text-white"></h1>
							<h1 class="poin fw-bold text-white border border-5 bordr-light" style="font-size: 200px;"></h1>
						</div>

					</div>
				</div>
			</div>

			<script type="text/javascript" src="../../assets/jquery/jquery.min.js"></script>
			<script src="../../assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

			<audio class="my_audio" controls preload="none" style="display:none;">
				<source src="button.mp3" type="audio/mpeg">
				<source src="button.ogg" type="audio/ogg">
			</audio>

			<audio class="gong" controls preload="none" style="display:none;">
				<source src="gong.mp3" type="audio/mpeg">
				<source src="gong.ogg" type="audio/ogg">
			</audio>

			<script type="text/javascript">
				$(document).ready(function() {
					var verifikasi = new bootstrap.Modal(document.getElementById('verifikasiModal'));
					var diskualifikasi = new bootstrap.Modal(document.getElementById('diskualifikasiModal'));
					var winner = new bootstrap.Modal(document.getElementById('winnerModal'));
					verifikasi.hide();
					diskualifikasi.hide();
					winner.hide();
					const socket = new WebSocket("ws://localhost:3000");

					let LastcombinedData = null;

					socket.onopen = () => {
						console.log("Server terhubung.");


						socket.send(JSON.stringify({
							type: 'get_nilai_monitor',
							partai: localStorage.getItem('partai'),
							babak: localStorage.getItem('babak'),
							bbk: localStorage.getItem('bbk'),
						}));
						renderTombolBabak();

						function babakUI(babak) {
							const jumlahBabak = parseInt(localStorage.getItem('jumlahBabak')) || 3;

							// Nonaktifkan semua tombol babak
							for (let i = 1; i <= jumlahBabak; i++) {
								$(".babak" + i).prop("disabled", true);
							}

							// Reset semua tombol babak ke style default
							for (let i = 1; i <= jumlahBabak; i++) {
								document.querySelectorAll(".babak" + i).forEach(function(element) {
									element.style.background = "black";
								});
							}

							// Aktifkan dan ubah warna tombol babak saat ini
							if (babak >= 1 && babak <= jumlahBabak) {
								document.querySelectorAll(".babak" + babak).forEach(function(element) {
									element.style.background = "linear-gradient(0deg, rgb(4, 175, 138) 0%, rgb(5, 184, 175) 100%)";
								});
							}
						}


						function renderTombolBabak() {
							const jumlahBabak = parseInt(localStorage.getItem("jumlahBabak")) || 3;
							const container = document.getElementById("babakContainer");

							container.innerHTML = ""; // Kosongkan dulu

							for (let i = jumlahBabak; i >= 1; i--) {
								container.innerHTML += `
            <tr>
                <td class="text-center">
                    <button class="p-3 text-white tombol-babak babak${i}" 
                            data-babak="${i}" 
                            style="background:black;">
                        <h2>${i}</h2>
                    </button>
                </td>
            </tr>
        `;
							}

							container.innerHTML += `
        <tr>
            <td class="fw-bold text-center">ROUND</td>
        </tr>
    `;
						}

						function renderPartaiFromStorage() {
							const raw = localStorage.getItem('currentPartai');
							if (!raw) return; // belum ada data tersimpan

							try {
								const p = JSON.parse(raw);

								// Render UI sama seperti di handler partai_data
								$('#kelas').text(p.kelas);
								// $('#bbk').text(p.bbk);
								$('#bbk').text(p.st ? p.st : p.bbk);

								$('.nm_biru').text(p.biru.nama);
								$('.kontingen_biru').text(p.biru.kontingen);
								$('.nm_merah').text(p.merah.nama);
								$('.kontingen_merah').text(p.merah.kontingen);

								$('#juri').text(localStorage.getItem('nama_juri'));
								$('#gelanggang').text(p.gelanggang + ' - ' + p.partai);

								// $('.gelanggang').text(p.gelanggang);

								if (p.partai === '?') {
									$('.nilai_biru').text(0);
									$('.button_nilai, .hapus-skor').prop('disabled', true);
								}

								babakUI(p.babak);
								console.log(p.babak);
							} catch (e) {
								console.warn('Gagal parse currentPartai di storage', e);
							}
						}

						// Panggil segera saat load halaman
						renderPartaiFromStorage();
						babakUI(localStorage.getItem('babak'));


						function formatTime(seconds) {
							const m = Math.floor(seconds / 60);
							const s = seconds % 60;
							return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
						}

						function resetHukumanBiru() {
							$('.binaan1').html('<img src="../../assets/img/binaan.png" width="50">');
							$('.binaan2').html('<img src="../../assets/img/binaan2.png" width="50">');
							$('.teguran1').html('<img src="../../assets/img/teguran1.png" width="50">');
							$('.teguran2').html('<img src="../../assets/img/teguran2.png" width="50">');
							$('.peringatan1').html('<img src="../../assets/img/peringatan.png" width="50">');
							$('.peringatan2').html('<img src="../../assets/img/peringatan.png" width="50">');
							$('.peringatan3').html('<img src="../../assets/img/peringatan.png" width="50">');
						}

						function resetHukumanMerah() {
							$('.binaan1merah').html('<img src="../../assets/img/binaan.png" width="50">');
							$('.binaan2merah').html('<img src="../../assets/img/binaan2.png" width="50">');
							$('.teguran1merah').html('<img src="../../assets/img/teguran1.png" width="50">');
							$('.teguran2merah').html('<img src="../../assets/img/teguran2.png" width="50">');
							$('.peringatan1merah').html('<img src="../../assets/img/peringatan.png" width="50">');
							$('.peringatan2merah').html('<img src="../../assets/img/peringatan.png" width="50">');
							$('.peringatan3merah').html('<img src="../../assets/img/peringatan.png" width="50">');
						}

						function updateHukumanUI(res) {
							const babakAktif = localStorage.getItem('babak');

							// Reset semua elemen yang terkait supaya tidak numpuk gambar
							resetHukumanBiru();
							resetHukumanMerah();

							// Fungsi internal untuk render sisi biru/merah
							function renderHukuman(hukumanData, prefix) {
								// Ambil data babak aktif dari parameter fungsi
								const babak = babakAktif;
								const grouped = hukumanData.grouped[babak] || [];
								const others = hukumanData.others || [];

								let countBinaan = 0;

								// Render grouped buttons
								grouped.forEach(buttonId => {
									console.log(`Render buttonId group: ${buttonId} (${prefix})`);

									if ([2, 3, 4].includes(buttonId)) {
										if (buttonId === 2) {
											countBinaan++;
											if (countBinaan === 1) {
												$(`.binaan1${prefix}`).html('<img src="../../assets/img/binaan1.png" width="50">');
											} else if (countBinaan === 2) {
												$(`.binaan2${prefix}`).html('<img src="../../assets/img/binaan2-1.png" width="50">');
											}
										} else if (buttonId === 3) {
											$(`.teguran1${prefix}`).html('<img src="../../assets/img/teguran1-1.png" width="50">');
										} else if (buttonId === 4) {
											$(`.teguran2${prefix}`).html('<img src="../../assets/img/teguran2-2.png" width="50">');
										}
									}
								});

								// Render others buttons
								others.forEach(buttonId => {
									console.log(`Render buttonId other: ${buttonId}`);

									if (buttonId === 5) {
										$(`.peringatan1${prefix}`).html('<img src="../../assets/img/peringatan1.png" width="50">');
									} else if (buttonId === 6) {
										$(`.peringatan2${prefix}`).html('<img src="../../assets/img/peringatan2.png" width="50">');
									} else if (buttonId === 7) {
										$(`.peringatan3${prefix}`).html('<img src="../../assets/img/peringatan3.png" width="50">');
									}
								});
							}

							// Render UI hukuman untuk kedua sudut
							renderHukuman(res.hukuman_biru, '');
							renderHukuman(res.hukuman_merah, 'merah');
						}

						socket.onmessage = (event) => {
							let res;
							try {
								res = JSON.parse(event.data);
								console.log(res);

								if (res.type === 'ip_server') {
									console.log(res.ip);
								}

								if (res.type === 'partai_data') {
									const p = res.data;
									LastcombinedData = null;
									// console.log("Data partai diterima:", p);
									localStorage.setItem('partai', p.partai);
									// localStorage.setItem('babak', 0);
									localStorage.setItem('currentPartai', JSON.stringify(p));
									// Tampilkan ke DOM
									$('#kelas').text(p.kelas);
									// $('#bbk').text(p.bbk);
									localStorage.setItem('bbk', p.bbk);
									$('#bbk').text(p.st ? p.st : p.bbk);

									$('.nm_biru').text(p.biru.nama);
									$('.kontingen_biru').text(p.biru.kontingen);
									$('.nm_merah').text(p.merah.nama);
									$('.kontingen_merah').text(p.merah.kontingen);

									$('#juri').text(localStorage.getItem('nama_juri'));
									$('#gelanggang').text(p.gelanggang + ' - ' + p.partai);

									// $('.gelanggang').text(p.gelanggang);

									if (p.biru.nilai === 0) {
										$('.nilai_biru').text(0);
										$('.nilai_merah').text(0);
										resetHukumanBiru();
										resetHukumanMerah();
									}
									babakUI(localStorage.getItem('babak'));
								}

								// Babak
								if (res.type === 'babak_data') {
									// console.log("Babak sekarang:", res.round);
									localStorage.setItem('babak', res.round);
									babakUI(res.round);

									console.log(LastcombinedData);

									if (LastcombinedData) {
										updateHukumanUI(LastcombinedData);
									} else {
										resetHukumanBiru();
										resetHukumanMerah();
									}
								}

								if (res.type === 'set_jumlah_babak') {
									console.log(res.jumlah);
									localStorage.setItem('jumlahBabak', res.jumlah);
									renderTombolBabak();
									babakUI(localStorage.getItem('babak'));
								}

								// Timer
								if (res.type === "tick") {
									const timerElement = document.getElementById("timer");
									if (timerElement) {
										timerElement.textContent = formatTime(res.remaining);
									}

									$('.button_nilai').prop('disabled', false);
									$('.hapus-skor').prop('disabled', false);
								}

								if (res.type === "stopped") {
									const timerElement = document.getElementById("timer");
									if (timerElement) {
										timerElement.textContent = '00:00';
									}
									$('.button_nilai').prop('disabled', true);
									$('.hapus-skor').prop('disabled', true);
									socket.send(JSON.stringify({
										type: 'set_status_stop',
										partai: localStorage.getItem('partai')
									}));
								}

								if (res.type === "keputusan_verifikasi") {
									verifikasi.show();
									let keterangan = '';
									let bgClass = '';

									if (res.data.sudut === 'MERAH') {
										keterangan = 'Sah untuk sudut MERAH';
										bgClass = 'bg-danger';
									} else if (res.data.sudut === 'BIRU') {
										keterangan = 'Sah untuk sudut BIRU';
										bgClass = 'bg-primary';
									} else {
										keterangan = 'Tidak Sah';
										bgClass = 'bg-warning';
									}

									$('.verifikasi-bg').removeClass('bg-danger bg-primary bg-warning bg-light').addClass(bgClass);
									$('.verifikasi').removeClass('text-dark').addClass('text-white');

									$('.verifikasi').html(
										'<h1 class="p-3">' +
										res.data.judul + '<br>' +
										keterangan + '</h1>'
									);

								}

								if (res.type === "verifikasi_masuk") {
									$('.verifikasi').html('<h1>VERIFIKASI <b class="jenis text-uppercase"></b></h1>');
									$('.verifikasi-bg').removeClass('bg-danger bg-primary bg-warning').addClass('bg-light bg-gradient');
									$('.verifikasi').removeClass('text-white').addClass('text-dark');
									console.log(res.data);
									verifikasi.show();
									winner.hide();
									diskualifikasi.hide();
									$('.jenis').html(res.data.jenis);
								}

								if (res.type === 'verifikasi_tutup') {
									verifikasi.hide();
								}

								if (res.type === "set_diskualifikasi") {
									console.log("DISK : " + res.sudut);
									$('.sudut').html('SUDUT ' + res.sudut);
									if (res.sudut === 'BIRU') {
										$('.sudut').removeClass('bg-danger').addClass('bg-primary');
									}

									if (res.sudut === 'MERAH') {
										$('.sudut').removeClass('bg-primary').addClass('bg-danger');
									}
									const warna = res.sudut === 'MERAH' ? 'primary' : 'danger';

									$('.winner')
										.removeClass('bg-danger bg-primary') // hapus class lama
										.addClass('bg-' + warna); // tambahkan class baru
									diskualifikasi.show();

									// winner.hide();
									setTimeout(function() {
										diskualifikasi.hide();
										// const pemenang = res.sudut === 'BIRU' ? 'MERAH' : 'BIRU';
										// $('.winner-bio').html('SUDUT ' + pemenang);
										// winner.show();
									}, 5000);

									// setTimeout(function() {
									// 	winner.hide();
									// }, 10000);
								}

								if (res.type === 'winner') {
									// Jika currentPartai masih string, parse lagi
									if (typeof res.data.currentPartai === 'string') {
										res.data.currentPartai = JSON.parse(res.data.currentPartai);
									}
									const sudut = res.data.sudut; // nilai: 'biru' atau 'merah'
									const nilai = res.data.nilai;
									console.log("Sudut: " + sudut + ", Nilai: " + nilai);
									const currentPartai = res.data.currentPartai;

									// Akses berdasarkan sudut dinamis
									const sudutData = currentPartai[sudut];
									const warna = sudut === 'merah' ? 'danger' : 'primary';
									console.log("Sudut : " + sudut);
									$('.winner')
										.removeClass('bg-danger bg-primary') // hapus class lama
										.addClass('bg-' + warna); // tambahkan class baru

									$('.winner-data').html('SUDUT ' + sudut);
									$('.poin').html('' + nilai);

									winner.show();

									setTimeout(function() {
										winner.hide();
									}, 3000);
								}

								// Optional: bisa juga tampilkan ketika mulai atau dihentikan
								if (res.type === "started" || res.type === "paused" || res.type === "resumed" || res.type === "stopped" || res.type === "ended") {
									console.log("Timer state:", res.type, "remaining:", res.remaining);
								}

							} catch (e) {
								console.error("Data WebSocket tidak valid JSON:", event.data);
								return;
							}

							if (res.type === 'monitor_data') {
								resetHukumanBiru();
								resetHukumanMerah();

								let countButton1 = 0;

								console.log('Hukuman Biru : ' + res.hukuman_biru);
								console.log('Hukuman Merah : ' + res.hukuman_merah);
								const combinedData = {
									hukuman_biru: res.hukuman_biru,
									hukuman_merah: res.hukuman_merah
								};

								updateHukumanUI(combinedData);
								LastcombinedData = combinedData;

								$('.nilai_biru').html(res.nilai_biru);
								$('.nilai_merah').html(res.nilai_merah);

								// Reset semua tombol
								$('.j-pBIRU1, .j-pBIRU2, .j-pBIRU3').removeClass('bg-warning');
								$('.j-kBIRU1, .j-kBIRU2, .j-kBIRU3').removeClass('bg-warning');
								$('.j-pMERAH1, .j-pMERAH2, .j-pMERAH3').removeClass('bg-warning');
								$('.j-kMERAH1, .j-kMERAH2, .j-kMERAH3').removeClass('bg-warning');

								(res.juri_biru || []).forEach(data => {
									// Reset semua tombol
									$('.j-pBIRU1, .j-pBIRU2, .j-pBIRU3').removeClass('bg-warning');
									$('.j-kBIRU1, .j-kBIRU2, .j-kBIRU3').removeClass('bg-warning');
									$('.j-pMERAH1, .j-pMERAH2, .j-pMERAH3').removeClass('bg-warning');
									$('.j-kMERAH1, .j-kMERAH2, .j-kMERAH3').removeClass('bg-warning');
									if (data.nilai == 1) {
										$('.j-pBIRU' + data.id_juri).addClass('bg-warning');
										setTimeout(() => {
											$('.j-pBIRU' + data.id_juri).removeClass('bg-warning');
										}, 1000);
									} else if (data.nilai == 2) {
										$('.j-kBIRU' + data.id_juri).addClass('bg-warning');
										setTimeout(() => {
											$('.j-kBIRU' + data.id_juri).removeClass('bg-warning');
										}, 1000);
									}
								});

								(res.juri_merah || []).forEach(data => {
									// Reset semua tombol
									$('.j-pBIRU1, .j-pBIRU2, .j-pBIRU3').removeClass('bg-warning');
									$('.j-kBIRU1, .j-kBIRU2, .j-kBIRU3').removeClass('bg-warning');
									$('.j-pMERAH1, .j-pMERAH2, .j-pMERAH3').removeClass('bg-warning');
									$('.j-kMERAH1, .j-kMERAH2, .j-kMERAH3').removeClass('bg-warning');
									if (data.nilai == 1) {
										$('.j-pMERAH' + data.id_juri).addClass('bg-warning');
										setTimeout(() => {
											$('.j-pMERAH' + data.id_juri).removeClass('bg-warning');
										}, 1000);
									} else if (data.nilai == 2) {
										$('.j-kMERAH' + data.id_juri).addClass('bg-warning');
										setTimeout(() => {
											$('.j-kMERAH' + data.id_juri).removeClass('bg-warning');
										}, 1000);
									}
								});
							}
						};
					};

					socket.onerror = (error) => {
						console.error("Server error:", error);
					};

					socket.onclose = () => {
						alert("Koneksi server terputus.");
					};


				});
			</script>
			<script>
				function cek_selesai() {
					if (confirm('Apakah Anda Yakin Pertandingan Sudah Berakhir?')) {
						return true;
					} else {
						return false;
					}
				}
			</script>

			<script>
				// Fungsi untuk membuat marquee yang halus
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

				// Fungsi untuk mode layar penuh
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

				// Inisialisasi saat halaman dimuat
				window.onload = function() {
					createSmoothMarquee();

					// Assign fungsi ke window untuk akses tombol
					window.openFullscreen = openFullscreen;
					window.closeFullscreen = closeFullscreen;
				};
			</script>
</body>

</html>