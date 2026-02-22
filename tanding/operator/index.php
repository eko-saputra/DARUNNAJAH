<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="format-detection" content="telephone=no" />
  <meta name="msapplication-tap-highlight" content="no" />
  <meta name="robots" content="noindex" />
  <meta http-equiv="refresh" content="30" />
  <title>OPERATOR - IPSI KOTA DUMAI</title>

  <link rel="shortcut icon" href="../../assets/img/LogoIPSI.png" />
  <link rel="stylesheet" href="../../assets/login/style.css" />
  <!-- Tambahan CSS untuk background hitam dan penyesuaian -->
  <style>
    /* Background hitam dengan overlay gelap */
    body {
      background-color: #2d2d2d;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      margin: 0;
      font-family: "Poppins", sans-serif;
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
      position: relative;
      z-index: 2;
    }

    /* Pastikan card login tetap menarik */
    .login-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 16px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      overflow: hidden;
    }

    .decorative-line {
      height: 6px;
      background: linear-gradient(90deg, #1e40af, #c2410c, #f97316);
    }

    .login-header {
      padding: 2rem 2rem 1rem;
      text-align: center;
    }

    .login-logo {
      width: 80px;
      height: 80px;
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
      color: #1e293b;
    }

    .login-subtitle {
      font-size: 0.875rem;
      color: #64748b;
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
      background: linear-gradient(135deg, #ef4444, #b91c1c);
      border-radius: 8px;
      margin-bottom: 1.5rem;
      border: none;
      transition: all 0.3s ease;
    }

    .reload-btn:hover {
      background: linear-gradient(135deg, #b91c1c, #991b1b);
      transform: translateY(-2px);
      box-shadow: 0 8px 15px -3px rgba(185, 28, 28, 0.4);
    }

    .form-group {
      margin-bottom: 1.25rem;
      position: relative;
    }

    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      font-size: 0.9rem;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      background-color: #fff;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: #1e40af;
      box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.2);
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
      background: linear-gradient(135deg, #1e40af, #2563eb);
      color: white;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #1e3a8a, #1e40af);
      transform: translateY(-2px);
      box-shadow: 0 8px 15px -3px rgba(30, 64, 175, 0.4);
    }

    .login-footer {
      padding: 1.5rem 2rem;
      background-color: #f1f5f9;
      text-align: center;
      font-size: 0.875rem;
      color: #64748b;
      border-top: 1px solid #e2e8f0;
    }

    /* Responsive */
    @media (max-width: 480px) {
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
        <h1 class="login-title">OPERATOR</h1>
        <p class="login-subtitle">Aplikasi Skor Digital Pencak Silat</p>
      </div>
      <div class="login-body">
        <form id="loginForm">
          <a href="index.html" class="reload-btn">
            <i class="fas fa-sync-alt mr-2"></i> RELOAD DATA PARTAI
          </a>

          <div class="form-group">
            <select class="form-control" id="juri" name="juri">
              <option value="">Loading...</option>
            </select>
          </div>

          <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password"
              value="operator" required />
          </div>

          <button type="submit" class="btn btn-primary" id="btn-login">
            <i class="fas fa-sign-in-alt mr-2"></i> Login
          </button>
        </form>
      </div>
      <div class="login-footer">
        &copy; <?php echo date('Y'); ?> IPSI KOTA DUMAI
      </div>
      <div class="silat-decoration"></div>
    </div>
  </div>

  <!-- Gunakan satu jQuery saja -->
  <script type="text/javascript" src="../../assets/jquery/jquery.min.js"></script>
  <script type="text/javascript">
    const hostname = window.location.hostname;
    var url_api = "http://" + hostname + "/DARUNNAJAH/api/api.php";

    $(function() {
      // Animasi elemen form
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

      // Muat daftar operator
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
            var html = '<option value=""> -- Pilih Operator -- </option>';

            $.each(obj, function(key, value) {
              if (value.name == "OPERATOR") {
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

        console.log(url_api);
      }, 1000);

      // Tangani submit form (klik tombol atau tekan Enter)
      $("#loginForm").on("submit", function(e) {
        e.preventDefault(); // Mencegah reload halaman

        var id_wasit = $("#juri").val();
        var nama_wasit = $("select#juri :selected").text();
        var pass = $("#password").val();

        if (id_wasit == "") {
          alert("Operator Harus dipilih");
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
              window.localStorage.setItem("operator", id_wasit);
              window.localStorage.setItem("nama_operator", nama_wasit);

              window.location.replace("daftar.php?status=");
            }
          },
        });
      });
    });

    // Efek hover tambahan untuk tombol
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