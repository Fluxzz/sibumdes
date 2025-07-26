<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login - SIBUMDES WUNUT</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon" />
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #334155;
            line-height: 1.6;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .login-branding {
            flex: 1;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-branding::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .branding-content {
            position: relative;
            z-index: 1;
            max-width: 500px;
        }

        .logo-icon {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .logo-icon:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .logo-icon i {
            font-size: 4rem;
            color: white;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .login-branding h1 {
            font-weight: 800;
            font-size: 3rem;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            letter-spacing: -0.02em;
        }

        .login-branding p {
            font-size: 1.2rem;
            opacity: 0.9;
            line-height: 1.8;
            font-weight: 300;
        }

        .login-form-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            padding: 40px;
            position: relative;
        }

        .login-form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 20%, rgba(59, 130, 246, 0.03) 0%, transparent 50%);
        }

        .login-form {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            position: relative;
            z-index: 1;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 40px;
        }

        .welcome-text h2 {
            font-weight: 700;
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .welcome-text p {
            color: #64748b;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            height: 56px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0 20px 0 50px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #ffffff;
            color: #1e293b;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }

        .form-control:hover {
            border-color: #cbd5e1;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.1rem;
            transition: color 0.3s ease;
            z-index: 2;
        }

        .form-control:focus + .input-icon {
            color: #3b82f6;
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.2s ease;
            z-index: 2;
            padding: 4px;
            border-radius: 6px;
        }

        .password-toggle:hover {
            color: #3b82f6;
            background: rgba(59, 130, 246, 0.1);
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            cursor: pointer;
        }

        .forgot-password {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .forgot-password:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            height: 56px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
        }

        .btn-login:active {
            transform: translateY(0px);
        }

        .btn-login:disabled {
            opacity: 0.7;
            transform: none;
            cursor: not-allowed;
        }

        .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-loading {
            display: none;
        }

        .btn-loading.active {
            display: flex;
        }

        .btn-text.loading {
            display: none;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid transparent;
            font-weight: 500;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fde8e8 100%);
            color: #dc2626;
            border-color: #fecaca;
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            background: rgba(59, 130, 246, 0.03);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            
            .login-branding {
                flex: none;
                min-height: 40vh;
                padding: 40px 20px;
            }
            
            .login-branding h1 {
                font-size: 2.2rem;
            }
            
            .login-branding p {
                font-size: 1rem;
            }
            
            .login-form-container {
                flex: 1;
                padding: 20px;
            }
            
            .login-form {
                padding: 40px 30px;
                border-radius: 20px;
            }
            
            .welcome-text h2 {
                font-size: 1.6rem;
            }
        }

        @media (max-width: 480px) {
            .login-form {
                padding: 30px 20px;
            }
            
            .form-control {
                height: 50px;
                padding-left: 45px;
            }
            
            .btn-login {
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-branding">
            <div class="branding-content">
                <div class="logo-icon">
                    <i class="fas fa-landmark"></i>
                </div>
                <h1>SIBUMDES WUNUT</h1>
                <p>Sistem Informasi Badan Usaha Milik Desa untuk pengelolaan yang lebih efisien, transparan, dan modern.</p>
            </div>
        </div>
        
        <div class="login-form-container">
            <div class="floating-shapes">
                <div class="shape"></div>
                <div class="shape"></div>
                <div class="shape"></div>
            </div>
            
            <div class="login-form">
                <div class="welcome-text">
                    <h2>Selamat Datang</h2>
                    <p>Silakan masuk untuk mengakses dashboard</p>
                </div>
                
                <!-- Error Alert (PHP will be processed on server) -->
                <div id="errorAlert" class="alert alert-danger" style="display: none;">
                    <i class="fas fa-exclamation-circle"></i>
                    Username atau password salah.
                </div>

                <form id="loginForm" action="auth/proses_login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-wrapper">
                            <input 
                                id="username" 
                                name="username_admin" 
                                type="text" 
                                class="form-control" 
                                placeholder="Masukkan username Anda"
                                required 
                                autocomplete="username"
                            />
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                class="form-control" 
                                placeholder="Masukkan password Anda"
                                required
                                autocomplete="current-password"
                            />
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye password-toggle" id="passwordToggle"></i>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <div class="remember-me">
                            <input type="checkbox" id="rememberMe" name="remember_me">
                            <label for="rememberMe">Ingat Saya</label>
                        </div>
                        <a href="#" class="forgot-password">Lupa Password?</a>
                    </div>
                    
                    <button type="submit" class="btn-login" id="loginButton">
                        <div class="btn-content">
                            <span class="btn-text">
                                <i class="fas fa-sign-in-alt"></i>
                                Masuk
                            </span>
                            <span class="btn-loading">
                                <div class="spinner"></div>
                                Memproses...
                            </span>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.getElementById('passwordToggle').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });

        // Enhanced form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const loginButton = document.getElementById('loginButton');
            const btnText = loginButton.querySelector('.btn-text');
            const btnLoading = loginButton.querySelector('.btn-loading');

            // Disable button and show loading state
            loginButton.disabled = true;
            btnText.classList.add('loading');
            btnLoading.classList.add('active');

            // Basic form validation
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            if (!username || !password) {
                e.preventDefault();
                showError('Mohon lengkapi username dan password');
                resetButton();
                return;
            }

            // Simulate processing delay for better UX
            setTimeout(() => {
                // Form will submit normally after this delay
            }, 500);
        });

        // Function to reset button state
        function resetButton() {
            const loginButton = document.getElementById('loginButton');
            const btnText = loginButton.querySelector('.btn-text');
            const btnLoading = loginButton.querySelector('.btn-loading');

            loginButton.disabled = false;
            btnText.classList.remove('loading');
            btnLoading.classList.remove('active');
        }

        // Function to show error message
        function showError(message) {
            const errorAlert = document.getElementById('errorAlert');
            errorAlert.textContent = message;
            errorAlert.style.display = 'block';
            
            // Hide error after 5 seconds
            setTimeout(() => {
                errorAlert.style.display = 'none';
            }, 5000);
        }

        // Enhanced input focus effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });

        // Check for error parameter in URL (from PHP)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error')) {
            showError('Username atau password salah');
        }

        // Auto-focus username field on page load
        window.addEventListener('load', function() {
            document.getElementById('username').focus();
        });
    </script>
</body>
</html>