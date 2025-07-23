<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIBUMDES WUNUT - Login</title>
  <link rel="shortcut icon" href="../../img/icon.ico">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      background: linear-gradient(0deg, #3d85d1 7%, #ffffff 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      overflow-x: hidden;
    }

    .login-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(226, 232, 240, 0.8);
      border-radius: 24px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.08);
      width: 100%;
      max-width: 440px;
      padding: 48px 40px;
      position: relative;
      overflow: hidden;
      animation: slideUp 0.6s ease-out;
    }

    .login-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #64748b, #475569);
      border-radius: 24px 24px 0 0;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .logo-section {
      text-align: center;
      margin-bottom: 40px;
    }

    .logo-icon {
      width: 72px;
      height: 72px;
      background: linear-gradient(135deg, #64748b, #475569);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      box-shadow: 0 10px 25px rgba(100, 116, 139, 0.2);
    }

    .logo-icon i {
      font-size: 32px;
      color: white;
    }

    .logo-title {
      font-size: 28px;
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }

    .logo-subtitle {
      font-size: 15px;
      color: #718096;
      font-weight: 400;
    }

    .form-group {
      position: relative;
      margin-bottom: 24px;
    }

    .form-group label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      color: #4a5568;
      margin-bottom: 8px;
    }

    .input-wrapper {
      position: relative;
    }

    .form-control {
      width: 100%;
      padding: 16px 20px 16px 52px;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 400;
      background: #ffffff;
      transition: all 0.3s ease;
      outline: none;
    }

    .form-control:focus {
      border-color: #64748b;
      box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
      transform: translateY(-1px);
    }

    .form-control::placeholder {
      color: #a0aec0;
    }

    .input-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #a0aec0;
      font-size: 18px;
      transition: color 0.3s ease;
    }

    .form-control:focus+.input-icon {
      color: #64748b;
    }

    .password-toggle {
      position: absolute;
      right: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #a0aec0;
      cursor: pointer;
      font-size: 18px;
      transition: color 0.3s ease;
    }

    .password-toggle:hover {
      color: #64748b;
    }

    .login-btn {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, #374151, #1f2937);
      border: none;
      border-radius: 12px;
      color: white;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(55, 65, 81, 0.3);
      margin-top: 8px;
    }

    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(55, 65, 81, 0.4);
      background: linear-gradient(135deg, #4b5563, #374151);
    }

    .login-btn:active {
      transform: translateY(0);
    }

    .footer-section {
      text-align: center;
      margin-top: 40px;
      padding-top: 32px;
      border-top: 1px solid #e2e8f0;
    }

    .system-title {
      font-size: 18px;
      font-weight: 700;
      color: #4a5568;
      margin-bottom: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .copyright {
      font-size: 13px;
      color: #a0aec0;
      font-weight: 400;
    }

    .error-message {
      background: #fed7d7;
      border: 1px solid #feb2b2;
      color: #c53030;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 14px;
      margin-bottom: 24px;
      display: none;
    }

    .loading {
      display: none;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .loading-spinner {
      width: 20px;
      height: 20px;
      border: 2px solid transparent;
      border-top: 2px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    /* Responsive Design */
    @media (max-width: 480px) {
      .login-container {
        padding: 32px 24px;
        margin: 16px;
        border-radius: 20px;
      }

      .logo-title {
        font-size: 24px;
      }

      .form-control {
        padding: 14px 16px 14px 48px;
        font-size: 16px;
      }

      .input-icon {
        left: 16px;
        font-size: 16px;
      }

      .password-toggle {
        right: 16px;
        font-size: 16px;
      }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
      .login-container {
        background: rgba(26, 32, 44, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.1);
      }

      .logo-title {
        color: #f7fafc;
      }

      .logo-subtitle {
        color: #a0aec0;
      }

      .form-group label {
        color: #e2e8f0;
      }

      .form-control {
        background: #2d3748;
        border-color: #4a5568;
        color: #f7fafc;
      }

      .form-control:focus {
        border-color: #64748b;
      }

      .system-title {
        color: #e2e8f0;
      }

      .footer-section {
        border-top-color: #4a5568;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="logo-section">
      <div class="logo-icon">
        <i class="fas fa-building"></i>
      </div>
      <h1 class="logo-title">SIDESA</h1>
      <p class="logo-subtitle">Sistem Informasi Desa Wunut</p>
    </div>

    <div class="error-message" id="errorMessage">
      Username atau password yang Anda masukkan salah.
    </div>

    <form action="proses_login.php" id="loginForm" method="post">
      <div class="form-group">
        <label for="username">Username</label>
        <div class="input-wrapper">
          <input
            type="text"
            id="username"
            name="username_admin"
            class="form-control"
            placeholder="Masukkan username Anda"
            autocomplete="username"
            maxlength="50"
            required />
          <i class="fas fa-user input-icon"></i>
        </div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-wrapper">
          <input
            type="password"
            id="password"
            name="password"
            class="form-control"
            placeholder="Masukkan password Anda"
            autocomplete="current-password"
            maxlength="50"
            required />
          <i class="fas fa-lock input-icon"></i>
          <i class="fas fa-eye password-toggle" id="togglePassword"></i>
        </div>
      </div>

      <button type="submit" class="login-btn" id="loginBtn">
        <span class="btn-text">Masuk ke Sistem</span>
        <div class="loading" id="loadingSpinner">
          <div class="loading-spinner"></div>
          <span>Memproses...</span>
        </div>
      </button>
    </form>

    <div class="footer-section">
      <div class="system-title">
        <i class="fas fa-building"></i>
        SIBUMDES WUNUT
      </div>
      <p class="copyright">© 2024 VURIKO DEV STUDIO. All Rights Reserved</p>
    </div>
  </div>

  <script>
    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    // Form submission with loading state
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const btnText = document.querySelector('.btn-text');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const errorMessage = document.getElementById('errorMessage');

    loginForm.addEventListener('submit', function(e) {
      // Hide error message if visible
      errorMessage.style.display = 'none';

      // Show loading state
      btnText.style.display = 'none';
      loadingSpinner.style.display = 'flex';
      loginBtn.disabled = true;

      // Note: In a real implementation, you might want to handle the response
      // and show/hide loading state accordingly
    });

    // Input focus animations
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
      });

      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
      });
    });

    // Auto-hide error message after 5 seconds
    function showError(message) {
      const errorDiv = document.getElementById('errorMessage');
      errorDiv.textContent = message;
      errorDiv.style.display = 'block';

      setTimeout(() => {
        errorDiv.style.display = 'none';
      }, 5000);
    }

    // Example: Show error if URL contains error parameter
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
      showError('Username atau password yang Anda masukkan salah.');
    }
  </script>
</body>

</html>