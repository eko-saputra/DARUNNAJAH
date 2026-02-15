<?php include('templates/header.php') ?>
<div class="container-scroller">
  <!-- partial:partials/_sidebar.html -->
  <?php include('templates/sidebar.php') ?>
  <!-- partial -->
  <div class="container-fluid page-body-wrapper">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar p-0 fixed-top d-flex flex-row">
      <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="mdi mdi-menu"></span>
        </button>
        <div class="container-fluid border border-1 border-muted bg-dark text-light my-3 d-flex justify-content-center align-items-center">
          <marquee>Selamat Datang di Panel Admin Skordigital - Versi 2.0 - Eko Saputra <?= date('Y'); ?></marquee>
        </div>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown">
            <b class="nav-link" id="profileDropdown" data-bs-toggle="dropdown">
              <style>
                /* Digital Clock Styles */
                .digital-clock {
                  background: linear-gradient(135deg, #1a0a2e, #3a2b4d);
                  padding: 8px 12px;
                  border-radius: 10px;
                  border: 1px solid rgba(138, 43, 226, 0.3);
                  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                  transition: all 0.3s ease;
                  min-width: 140px;
                }

                .digital-clock:hover {
                  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
                  border-color: #e22b77;
                }

                .clock-time {
                  font-family: 'Courier New', monospace;
                  font-weight: 700;
                  font-size: 16px;
                  letter-spacing: 1px;
                  color: #f8f9fa;
                  text-shadow: 0 0 5px rgba(226, 43, 119, 0.5);
                }

                .clock-date {
                  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                  font-size: 12px;
                  margin-top: 2px;
                  color: #adb5bd;
                }

                .clock-colon {
                  animation: blink 1s infinite;
                }

                @keyframes blink {

                  0%,
                  100% {
                    opacity: 1;
                  }

                  50% {
                    opacity: 0.3;
                  }
                }

                /* Profile Clock */
                .profile-time {
                  font-family: 'Courier New', monospace;
                  font-weight: 600;
                  font-size: 25px;
                  color: #f8f9fa;
                  background-color: #2a1b3d;
                  text-shadow: 0 0 3px rgba(226, 43, 119, 0.3);
                  letter-spacing: 0.5px;
                }

                /* Analog Clock Style (Alternative) */
                .analog-clock-container {
                  width: 40px;
                  height: 40px;
                  position: relative;
                  border-radius: 50%;
                  background: linear-gradient(135deg, #2a1b3d, #1a0a2e);
                  border: 2px solid #e22b77;
                  box-shadow: 0 0 10px rgba(226, 43, 119, 0.3);
                }

                .analog-clock-hour,
                .analog-clock-minute,
                .analog-clock-second {
                  position: absolute;
                  background-color: #f8f9fa;
                  border-radius: 2px;
                  transform-origin: bottom center;
                }

                .analog-clock-hour {
                  width: 3px;
                  height: 10px;
                  top: 10px;
                  left: 18.5px;
                }

                .analog-clock-minute {
                  width: 2px;
                  height: 15px;
                  top: 5px;
                  left: 19px;
                }

                .analog-clock-second {
                  width: 1px;
                  height: 18px;
                  top: 2px;
                  left: 19.5px;
                  background-color: #e22b77;
                }

                .analog-clock-center {
                  position: absolute;
                  width: 6px;
                  height: 6px;
                  background-color: #e22b77;
                  border-radius: 50%;
                  top: 17px;
                  left: 17px;
                }

                /* Responsive */
                @media (max-width: 768px) {
                  .digital-clock {
                    padding: 6px 8px;
                    min-width: 100px;
                  }

                  .clock-time {
                    font-size: 14px;
                  }

                  .clock-date {
                    font-size: 10px;
                  }
                }
              </style>
              <div id="profileClock" class="mb-0 d-none d-sm-block navbar-profile-name" style="font-size: 12px; min-width: 80px;">
                <div class="profile-time"></div>
              </div>
              <script>
                // Digital Clock Function
                function updateDigitalClock() {
                  const now = new Date();

                  // Format waktu: HH:MM:SS
                  const hours = now.getHours().toString().padStart(2, '0');
                  const minutes = now.getMinutes().toString().padStart(2, '0');
                  const seconds = now.getSeconds().toString().padStart(2, '0');

                  // Format tanggal: Day, DD Month YYYY
                  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                  const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                  ];

                  const dayName = days[now.getDay()];
                  const date = now.getDate().toString().padStart(2, '0');
                  const monthName = months[now.getMonth()];
                  const year = now.getFullYear();

                  // Update navbar clock
                  const clockTimeElement = document.querySelector('.clock-time');
                  const clockDateElement = document.querySelector('.clock-date');

                  if (clockTimeElement) {
                    // Membuat efek blink pada colon
                    const colonClass = (now.getSeconds() % 2 === 0) ? 'clock-colon' : '';
                    clockTimeElement.innerHTML = `
      <span>${hours}</span>
      <span class="${colonClass}">:</span>
      <span>${minutes}</span>
      <span class="${colonClass}">:</span>
      <span>${seconds}</span>
    `;
                  }

                  if (clockDateElement) {
                    clockDateElement.textContent = `${dayName}, ${date} ${monthName} ${year}`;
                  }

                  // Update profile clock (smaller version)
                  const profileClockElement = document.querySelector('.profile-time');
                  if (profileClockElement) {
                    const profileHours = now.getHours().toString().padStart(2, '0');
                    const profileMinutes = now.getMinutes().toString().padStart(2, '0');
                    profileClockElement.innerHTML = `${profileHours}:${profileMinutes}`;
                  }
                }

                // Initialize clock
                document.addEventListener('DOMContentLoaded', function() {
                  // Update clock immediately
                  updateDigitalClock();

                  // Update clock every second
                  setInterval(updateDigitalClock, 1000);

                  // Optional: Add analog clock functionality
                  function createAnalogClock() {
                    const analogClockHTML = `
      <div class="analog-clock-container">
        <div class="analog-clock-hour" id="analogHour"></div>
        <div class="analog-clock-minute" id="analogMinute"></div>
        <div class="analog-clock-second" id="analogSecond"></div>
        <div class="analog-clock-center"></div>
      </div>
    `;

                    // Uncomment to add analog clock somewhere
                    // document.getElementById('clockContainer').innerHTML = analogClockHTML;
                  }

                  function updateAnalogClock() {
                    const now = new Date();
                    const hours = now.getHours() % 12;
                    const minutes = now.getMinutes();
                    const seconds = now.getSeconds();

                    const hourDeg = (hours * 30) + (minutes * 0.5);
                    const minuteDeg = (minutes * 6) + (seconds * 0.1);
                    const secondDeg = seconds * 6;

                    const hourHand = document.getElementById('analogHour');
                    const minuteHand = document.getElementById('analogMinute');
                    const secondHand = document.getElementById('analogSecond');

                    if (hourHand) hourHand.style.transform = `rotate(${hourDeg}deg)`;
                    if (minuteHand) minuteHand.style.transform = `rotate(${minuteDeg}deg)`;
                    if (secondHand) secondHand.style.transform = `rotate(${secondDeg}deg)`;
                  }

                  // Initialize analog clock if needed
                  // createAnalogClock();
                  // setInterval(updateAnalogClock, 1000);
                });
              </script>
            </b>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
              <h6 class="p-3 mb-0">Profile</h6>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-settings text-success"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <p class="preview-subject mb-1">Settings</p>
                </div>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-logout text-danger"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <p class="preview-subject mb-1">Log out</p>
                </div>
              </a>
              <div class="dropdown-divider"></div>
              <p class="p-3 mb-0 text-center">Advanced settings</p>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-format-line-spacing"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-12 grid-margin stretch-card">
            <div class="card corona-gradient-card">
              <div class="card-body py-0 px-0 px-sm-3">
                <div class="row align-items-center">
                  <div class="col-4 col-sm-3 col-xl-2">
                    <img src="assets/images/dashboard/Group126@2x.png" class="gradient-corona-img img-fluid" alt="">
                  </div>
                  <div class="col-5 col-sm-7 col-xl-8 p-0">
                    <h4 class="mb-1 mb-sm-0">SKORDIGITAL PENCAKSILAT</h4>
                    <p class="mb-0 font-weight-normal d-none d-sm-block">Lebih mudah, efisien dan transparan.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
        $page = @$_GET['page'];

        if ($page) {
          include('pages/' . $page . '.php');
        } else {
          include('pages/dashboard.php');
        }

        ?>
        <!-- content-wrapper ends -->
        <?php include('templates/footer.php') ?>