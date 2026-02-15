<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pencak Silat Digital Scoring System - Admin Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
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
      font-family: 'Poppins', sans-serif;
      background-color: var(--light);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS85wTg8pxKlildgzotDZhhXVmBMx032P3yMw&s');
      background-size: cover;
      background-position: center;
      position: relative;
    }
    
    body::before {
      content: '';
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
      max-width: 420px;
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
      padding: 2rem 2rem 1.5rem;
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
      padding: 0 2rem 2rem;
    }
    
    .alert {
      padding: 0.75rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      color: white;
      font-size: 0.875rem;
    }
    
    .alert-error {
      background-color: var(--error);
    }
    
    .form-group {
      margin-bottom: 1.5rem;
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
      font-size: 0.75rem;
      color: #64748b;
      border-top: 1px solid #e2e8f0;
    }
    
    .silat-decoration {
      position: absolute;
      bottom: -40px;
      right: -40px;
      width: 200px;
      height: 200px;
      background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNTYgMjU2IiBmaWxsPSJub25lIj48cGF0aCBkPSJNMTk2LjUgMTQ4QzIyNC41IDk1IDIwMCA1NS41IDE3NS41IDQwLjVDMjEwLjUgNzYuNSAxODUgMTI0IDE2NS41IDE0NEMxNDYgMTY0IDk4LjUgMTgzIDE0OS41IDE1Mi41QzI2OSA3NyAxNDYuNSAyMzguNSAxMzEgMjE1QzExNS41IDE5MS41IDE3NCA5MiAxMzcuNSAxMjEuNUMxMDEgMTUxIDgyLjUgMTEzLjUgOTUuNSA5MC41QzEwOC41IDY3LjUgODYuNSA5MC41IDYzLjUgMTAwQzQwLjUgMTA5LjUgNDQgMTQwLjUgNDQgMTYyQzQ0IDE4My41IDc2IDE3MC41IDc2IDE3MC41QzUwLjUgMTcwLjUgNjAgMjE2IDExNC41IDIxNkMxNjkgMjE2IDIwNC41IDIxNC41IDIxNiAxNjIuNUMyMjcuNSAxMTAuNSAyMDQgMTI2LjUgMTk2LjUgMTQ4WiIgZmlsbD0icmdiYSgzMCwgNjQsIDE3NSwgMC4xKSIvPjwvc3ZnPg==');
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
      background: linear-gradient(90deg, var(--primary), var(--secondary), var(--accent));
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
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/35/LogoIPSI_%281%29.png/1200px-LogoIPSI_%281%29.png" alt="Pencak Silat Logo" class="login-logo">
        <h1 class="login-title">PENCAK SILAT</h1>
        <p class="login-subtitle">Digital Scoring System</p>
      </div>
      <div class="login-body">
        <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-error">
          <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['require'])): ?>
        <div class="alert alert-error">
          <?php echo $_SESSION['require']; unset($_SESSION['require']); ?>
        </div>
        <?php endif; ?>
        
        <form action="verifylogin.php" method="post">
          <div class="form-group">
            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
          </div>
          
          <div class="form-group">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
          </div>
          
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt mr-2"></i> Masuk
          </button>
        </form>
      </div>
      <div class="login-footer">
        &copy; <?php echo date('Y'); ?> Pencak Silat Digital Scoring System
      </div>
      <div class="silat-decoration"></div>
    </div>
  </div>

  <script>
    // Simple animation for login form
    document.addEventListener('DOMContentLoaded', function() {
      const formElements = document.querySelectorAll('.form-control, .btn');
      
      formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'all 0.4s ease';
        
        setTimeout(() => {
          element.style.opacity = '1';
          element.style.transform = 'translateY(0)';
        }, 300 + (index * 100));
      });
    });
  </script>
</body>
</html>