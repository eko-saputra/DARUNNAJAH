<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Nebula Admin - Login</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <!-- Custom CSS -->
  <style>
    :root {
      --bg-dark-purple: #1a0a2e;
      --bg-medium-purple: #2a1b3d;
      --bg-light-purple: #3a2b4d;
      --primary-purple: #e22b77;
      --primary-light: #f755b4;
      --accent-warning: #ffc107;
      --accent-warning-light: #ffdb70;
      --text-light: #f8f9fa;
      --text-muted: #adb5bd;
    }

    body {
      background-color: var(--bg-dark-purple);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image:
        radial-gradient(circle at 10% 20%, rgba(138, 43, 226, 0.1) 0%, transparent 20%),
        radial-gradient(circle at 90% 80%, rgba(255, 193, 7, 0.08) 0%, transparent 20%);
      min-height: 100vh;
      overflow-x: hidden;
    }

    .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .login-card {
      background-color: var(--bg-medium-purple);
      border-radius: 20px;
      box-shadow:
        0 15px 35px rgba(0, 0, 0, 0.5),
        0 0 15px rgba(137, 43, 226, 0.15);
      border: 1px solid rgba(138, 43, 226, 0.2);
      overflow: hidden;
      width: 100%;
      max-width: 450px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-5px);
      box-shadow:
        0 20px 40px rgba(0, 0, 0, 0.6),
        0 0 20px rgba(138, 43, 226, 0.4);
    }

    .card-header {
      background: linear-gradient(135deg, var(--primary-purple), var(--bg-light-purple));
      padding: 30px 20px 20px;
      text-align: center;
      border-bottom: 3px solid var(--accent-warning);
      position: relative;
    }

    .card-header::after {
      content: '';
      position: absolute;
      bottom: -3px;
      left: 0;
      width: 100%;
      height: 3px;
      background: linear-gradient(90deg, transparent, var(--accent-warning), transparent);
    }

    .logo {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 15px;
    }

    .logo-text {
      font-weight: 700;
      font-size: 28px;
      background: linear-gradient(to right, var(--primary-light), var(--accent-warning-light));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      letter-spacing: 1px;
    }

    .tagline {
      color: var(--text-muted);
      font-size: 14px;
      letter-spacing: 0.5px;
    }

    .card-body {
      padding: 30px;
    }

    .form-label {
      color: var(--text-light);
      font-weight: 600;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
    }

    .form-label i {
      margin-right: 10px;
      color: var(--primary-light);
    }

    .form-control {
      background-color: var(--bg-light-purple);
      border: 1px solid rgba(138, 43, 226, 0.3);
      color: var(--text-light);
      padding: 12px 15px;
      border-radius: 10px;
      transition: all 0.3s;
    }

    .form-control:focus {
      background-color: var(--bg-light-purple);
      border-color: var(--primary-purple);
      box-shadow: 0 0 0 0.25rem rgba(138, 43, 226, 0.25);
      color: var(--text-light);
    }

    .form-control::placeholder {
      color: var(--text-muted);
    }

    .input-group-text {
      background-color: var(--bg-light-purple);
      border: 1px solid rgba(138, 43, 226, 0.3);
      color: var(--primary-light);
      border-right: none;
    }

    .password-toggle {
      background-color: var(--bg-light-purple);
      border: 1px solid rgba(138, 43, 226, 0.3);
      color: var(--primary-light);
      border-left: none;
      cursor: pointer;
    }

    .password-toggle:hover {
      background-color: rgba(138, 43, 226, 0.1);
    }

    .btn-login {
      background: linear-gradient(135deg, var(--primary-purple), var(--primary-light));
      border: none;
      color: white;
      font-weight: 600;
      padding: 12px;
      border-radius: 10px;
      transition: all 0.3s;
      width: 100%;
      margin-top: 10px;
      letter-spacing: 0.5px;
    }

    .btn-login:hover {
      background: linear-gradient(135deg, var(--primary-light), var(--primary-purple));
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(138, 43, 226, 0.4);
    }

    .divider {
      display: flex;
      align-items: center;
      margin: 25px 0;
      color: var(--text-muted);
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(138, 43, 226, 0.5), transparent);
    }

    .divider span {
      padding: 0 15px;
      font-size: 14px;
    }

    .is-invalid {
      border-color: #dc3545 !important;
    }

    .invalid-feedback {
      display: none;
      color: #dc3545;
      font-size: 0.875em;
      margin-top: 5px;
    }

    @media (max-width: 576px) {
      .login-card {
        max-width: 100%;
      }

      .card-body {
        padding: 20px;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-card">
      <div class="card-header">
        <div class="logo">
          <div class="logo-text">SKORDIGITAL</div>
        </div>
        <div class="tagline">PENCAKSILAT KOTA DUMAI</div>
      </div>

      <div class="card-body">
        <h3 class="text-center mb-4" style="color: var(--text-light);">Login to Your Account</h3>

        <form id="loginForm">
          <div class="mb-4">
            <label for="username" class="form-label">
              <i class="fas fa-user"></i> Username
            </label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="fas fa-at"></i>
              </span>
              <input type="text" class="form-control" id="username" name="username" placeholder="Enter username or email" required>
            </div>
            <div class="invalid-feedback" id="usernameError">
              Username tidak boleh kosong
            </div>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label">
              <i class="fas fa-key"></i> Password
            </label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="fas fa-lock"></i>
              </span>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
              <span class="input-group-text password-toggle" id="togglePassword">
                <i class="fas fa-eye"></i>
              </span>
            </div>
            <div class="invalid-feedback" id="passwordError">
              Password tidak boleh kosong
            </div>
          </div>

          <button type="submit" class="btn btn-login" id="loginButton">
            <i class="fas fa-sign-in-alt me-2"></i> Login to Dashboard
          </button>

          <div class="divider">
            <span>SKORDIGITAL</span>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->

  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">


  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="../assets/js/bootstrap.bundle.min.js"></script>

  <!-- jQuery (untuk AJAX) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const togglePassword = document.getElementById('togglePassword');
      const passwordInput = document.getElementById('password');
      const loginForm = document.getElementById('loginForm');
      const usernameInput = document.getElementById('username');
      const passwordError = document.getElementById('passwordError');
      const usernameError = document.getElementById('usernameError');

      // Toggle password visibility
      togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        const icon = this.querySelector('i');
        if (type === 'text') {
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        } else {
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        }
      });

      // Form validation and AJAX submission
      loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        let isValid = true;

        // Reset error states
        usernameInput.classList.remove('is-invalid');
        passwordInput.classList.remove('is-invalid');
        usernameError.style.display = 'none';
        passwordError.style.display = 'none';

        // Validate username
        if (!usernameInput.value.trim()) {
          usernameInput.classList.add('is-invalid');
          usernameError.style.display = 'block';
          isValid = false;
        }

        // Validate password
        if (!passwordInput.value.trim()) {
          passwordInput.classList.add('is-invalid');
          passwordError.style.display = 'block';
          isValid = false;
        }

        if (isValid) {
          // Show loading state
          const loginButton = document.getElementById('loginButton');
          const originalText = loginButton.innerHTML;
          loginButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Logging in...';
          loginButton.disabled = true;

          // Collect form data
          const formData = new FormData(loginForm);

          // Send AJAX request to verifylogin.php
          fetch('pages/proses/verifylogin.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.text())
            .then(data => {
              // Reset button state
              loginButton.innerHTML = originalText;
              loginButton.disabled = false;

              console.log(data);

              // Check response from verifylogin.php
              if (data === 'success') {
                Swal.fire({
                  title: 'Login Successful!',
                  text: 'Redirecting to dashboard...',
                  icon: 'success',
                  timer: 2000,
                  showConfirmButton: false,
                  willClose: () => {
                    window.location.href = 'index.php';
                  }
                });
              } else {
                Swal.fire({
                  title: 'Login Failed!',
                  text: 'Invalid username or password',
                  icon: 'error',
                  confirmButtonColor: '#e22b77',
                  confirmButtonText: 'Try Again'
                });
              }
            })
            .catch(error => {
              // Reset button state
              loginButton.innerHTML = originalText;
              loginButton.disabled = false;

              Swal.fire({
                title: 'Error!',
                text: 'An error occurred. Please try again.',
                icon: 'error',
                confirmButtonColor: '#e22b77'
              });
              console.error('Error:', error);
            });
        }
      });

      // Real-time validation
      usernameInput.addEventListener('input', function() {
        if (this.value.trim()) {
          this.classList.remove('is-invalid');
          usernameError.style.display = 'none';
        }
      });

      passwordInput.addEventListener('input', function() {
        if (this.value.trim()) {
          this.classList.remove('is-invalid');
          passwordError.style.display = 'none';
        }
      });
    });
  </script>
</body>

</html>