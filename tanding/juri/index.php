<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="format-detection" content="telephone=no" />
  <meta name="msapplication-tap-highlight" content="no" />
  <meta name="robots" content="noindex" />
  <meta http-equiv="refresh" content="30" />
  <title>Aplikasi Wasit Juri - Kota Dumai</title>
  <style>
    :root {
      --primary: #1e40af;
      --secondary: #c2410c;
      --accent: #f97316;
      --dark: #1e293b;
      --light: #f8fafc;
      --success: #10b981;
      --error: #ef4444;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Poppins", sans-serif;
      background-color: #2d2d2d;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-size: cover;
      background-position: center;
      position: relative;
    }

    body::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1;
    }

    .container {
      width: 100%;
      max-width: 480px;
      padding: 0 20px;
      position: relative;
      z-index: 2;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 16px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      overflow: hidden;
      position: relative;
    }

    .login-header {
      padding: 2rem 2rem 1rem;
      text-align: center;
    }

    .login-logo {
      width: 80px;
      height: 80px;
      margin-bottom: 1rem;
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

    .login-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 0.5rem;
    }

    .login-subtitle {
      font-size: 0.875rem;
      color: #64748b;
      margin-bottom: 0.5rem;
    }

    .login-body {
      padding: 0 2rem 1.5rem;
    }

    .reload-btn {
      display: block;
      width: 100%;
      padding: 0.75rem;
      text-align: center;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 600;
      color: white;
      background: linear-gradient(135deg, var(--error), #b91c1c);
      border-radius: 8px;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(185, 28, 28, 0.3);
      transition: all 0.3s ease;
    }

    .reload-btn:hover {
      background: linear-gradient(135deg, #b91c1c, #991b1b);
      transform: translateY(-2px);
      box-shadow: 0 8px 15px -3px rgba(185, 28, 28, 0.4);
    }

    .reload-btn:active {
      transform: translateY(0);
      box-shadow: 0 4px 6px -1px rgba(185, 28, 28, 0.3);
    }

    .form-group {
      margin-bottom: 1.25rem;
      position: relative;
    }

    .form-group i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
    }

    .form-control {
      width: 100%;
      padding: 0.75rem 1rem 0.75rem 2.5rem;
      font-size: 0.9rem;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      background-color: #fff;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.2);
    }

    .form-control::placeholder {
      color: #94a3b8;
    }

    select.form-control {
      appearance: none;
      background-repeat: no-repeat;
      background-position: right 1rem center;
      background-size: 16px 12px;
    }

    .btn {
      display: block;
      width: 100%;
      padding: 0.75rem;
      font-size: 1rem;
      font-weight: 600;
      text-align: center;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary), #2563eb);
      color: white;
      box-shadow: 0 4px 6px -1px rgba(30, 64, 175, 0.3);
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #1e3a8a, #1e40af);
      transform: translateY(-2px);
      box-shadow: 0 8px 15px -3px rgba(30, 64, 175, 0.4);
    }

    .btn-primary:active {
      transform: translateY(0);
      box-shadow: 0 4px 6px -1px rgba(30, 64, 175, 0.3);
    }

    .login-footer {
      padding: 1.5rem 2rem;
      background-color: #f1f5f9;
      text-align: center;
      font-size: 0.875rem;
      color: #64748b;
      border-top: 1px solid #e2e8f0;
    }

    .silat-decoration {
      position: absolute;
      bottom: -40px;
      right: -40px;
      width: 200px;
      height: 200px;
      background-size: contain;
      background-repeat: no-repeat;
      opacity: 0.8;
      z-index: 0;
      pointer-events: none;
    }

    .decorative-line {
      position: absolute;
      top: 0;
      left: 0;
      height: 6px;
      width: 100%;
      background: linear-gradient(90deg,
          var(--primary),
          var(--secondary),
          var(--accent));
    }

    /* Responsive adjustments */
    @media (max-width: 480px) {
      .login-card {
        border-radius: 12px;
      }

      .login-header {
        padding: 1.5rem 1.5rem 1rem;
      }

      .login-logo {
        width: 60px;
        height: 60px;
      }

      .login-title {
        font-size: 1.25rem;
      }

      .login-body {
        padding: 0 1.5rem 1.5rem;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="login-card">
      <div class="decorative-line"></div>
      <div class="login-header">
        <img src="../../assets/img/LogoIPSI.png" alt="Pencak Silat Logo" class="login-logo" />
        <h1 class="login-title">JURI</h1>
        <p class="login-subtitle">Aplikasi Skor Digital Pencak Silat</p>
      </div>
      <div class="login-body">
        <form id="loginForm">
          <a href="index.html" class="reload-btn">
            <i class="fas fa-sync-alt mr-2"></i> RELOAD DATA PARTAI
          </a>
          <div class="form-group">
            <select class="form-control" id="juri" name="juri">
              <option value="">-- Pilih Juri --</option>
            </select>
          </div>

          <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password"
              value="juri" required />
          </div>

          <button type="submit" class="btn btn-primary" id="btn-login">
            <i class="fas fa-sign-in-alt mr-2"></i> Login
          </button>
        </form>
      </div>
      <div class="login-footer">
        &copy;
        <?php echo date('Y'); ?>
        &copy; Eko Saputra - <?= date('Y') ?>. All rights reserved.
      </div>
      <div class="silat-decoration"></div>
    </div>
  </div>

  <!-- Gunakan satu jQuery saja -->
  <script type="text/javascript" src="../../assets/jquery/jquery.min.js"></script>
  <script type="text/javascript">
    // Ambil hostname untuk membangun URL API
    const hostname = window.location.hostname;
    const url_api = "http://" + hostname + "/DARUNNAJAH/api/api.php";

    $(function() {
      // Animasi awal untuk elemen form
      const formElements = document.querySelectorAll(
        ".form-control, .btn, .reload-btn"
      );

      formElements.forEach((element, index) => {
        element.style.opacity = "0";
        element.style.transform = "translateY(20px)";
        element.style.transition = "all 0.4s ease";

        setTimeout(() => {
          element.style.opacity = "1";
          element.style.transform = "translateY(0)";
        }, 300 + index * 100);
      });

      // Muat daftar juri dari API
      setTimeout(function() {
        $.ajax({
          url: url_api,
          data: {
            a: "juri"
          },
          type: "GET",
          dataType: "json",
          crossDomain: true,
          success: function(obj) {
            var html = '<option value=""> -- Pilih Juri -- </option>';

            $.each(obj, function(key, value) {
              if (value.name != "Operator" && value.name != "Dewan") {
                html +=
                  '<option value="' +
                  value.id +
                  '">' +
                  value.name +
                  "</option>";
              }
            });

            $("#juri").html(html);
          },
        });
      }, 1000);

      // Tangani submit form (baik via klik tombol maupun tekan Enter)
      $("#loginForm").on("submit", function(e) {
        e.preventDefault(); // Mencegah reload halaman

        var id_wasit = $("#juri").val();
        var nama_wasit = $("select#juri :selected").text();
        var pass = $("#password").val();

        if (id_wasit == "") {
          alert("Juri Harus dipilih");
          return false;
        }

        if (pass == "") {
          alert("Password Harus diisi");
          return false;
        }

        // Kirim request login
        $.ajax({
          url: url_api,
          data: {
            a: "login",
            id: id_wasit,
            password: pass
          },
          type: "GET",
          dataType: "json",
          crossDomain: true,
          success: function(obj) {
            if (obj.status == "error") {
              alert("Password anda salah");
            } else {
              window.localStorage.setItem("is_login", 1);
              window.localStorage.setItem("juri", id_wasit);
              window.localStorage.setItem("nama_juri", nama_wasit);

              window.location.replace("penilaian.html");
            }
          },
        });
      });
    });

    // Efek hover tambahan untuk tombol (opsional)
    document.addEventListener("DOMContentLoaded", function() {
      const btnLogin = document.getElementById("btn-login");
      const reloadBtn = document.querySelector(".reload-btn");

      if (btnLogin) {
        btnLogin.addEventListener("mouseover", function() {
          this.style.transform = "translateY(-2px)";
        });

        btnLogin.addEventListener("mouseout", function() {
          this.style.transform = "translateY(0)";
        });
      }

      if (reloadBtn) {
        reloadBtn.addEventListener("mouseover", function() {
          this.style.transform = "translateY(-2px)";
        });

        reloadBtn.addEventListener("mouseout", function() {
          this.style.transform = "translateY(0)";
        });
      }
    });
  </script>
</body>

</html>