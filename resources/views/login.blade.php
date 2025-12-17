<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - M-Coffee</title>

    <!-- CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --blue: #2a2185;
            --white: #fff;
            --gray: #f5f5f5;
            --black1: #222;
            --black2: #999;
            --light-blue: #3a31a5;
        }

        * {
            font-family: 'Ubuntu', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--blue) 0%, #4a41b5 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .login-left {
            flex: 1;
            background: var(--blue);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            background: var(--white);
            border-radius: 50%;
            opacity: 0.1;
        }

        .login-left::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 150px;
            height: 150px;
            background: var(--white);
            border-radius: 50%;
            opacity: 0.1;
        }

        .brand-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .brand-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--white);
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .brand-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
        }

        .features {
            margin-top: 40px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .feature-icon {
            margin-right: 15px;
            font-size: 1.5rem;
        }

        .feature-text {
            font-size: 1rem;
        }

        .login-right {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-title {
            color: var(--blue);
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-subtitle {
            color: var(--black2);
            font-size: 1rem;
        }

        .form-label {
            color: var(--black1);
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 0.2rem rgba(42, 33, 133, 0.25);
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--black2);
            font-size: 1.2rem;
        }

        .btn-login {
            background: var(--blue);
            color: var(--white);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-login:hover {
            background: var(--light-blue);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(42, 33, 133, 0.3);
        }

        .alert-danger {
            background: #fee;
            border: 1px solid #fcc;
            color: #c00;
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }

        .login-footer {
            text-align: center;
            margin-top: 30px;
            color: var(--black2);
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .login-container {
                flex-direction: column;
                max-width: 500px;
            }
            
            .login-left {
                padding: 40px 20px;
            }
            
            .login-right {
                padding: 40px 20px;
            }
            
            .brand-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .login-container {
                border-radius: 15px;
            }
            
            .login-title {
                font-size: 1.8rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-right {
            animation: fadeIn 0.6s ease-out;
        }

        .login-left {
            animation: fadeIn 0.6s ease-out 0.2s backwards;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Left Side - Brand & Info -->
        <div class="login-left">
            <div class="brand-section">
                <ion-icon name="business-outline" class="brand-icon"></ion-icon>
                <h1 class="brand-title">M-Coffee</h1>
                <p class="brand-subtitle">Sistem Seleksi Karyawan</p>
            </div>
            
            <div class="features">
                <div class="feature-item">
                    <ion-icon name="shield-checkmark-outline" class="feature-icon"></ion-icon>
                    <span class="feature-text">Sistem login yang aman</span>
                </div>
                <div class="feature-item">
                    <ion-icon name="bar-chart-outline" class="feature-icon"></ion-icon>
                    <span class="feature-text">Dashboard analytics lengkap</span>
                </div>
                <div class="feature-item">
                    <ion-icon name="people-outline" class="feature-icon"></ion-icon>
                    <span class="feature-text">Manajemen data kandidat</span>
                </div>
                <div class="feature-item">
                    <ion-icon name="calculator-outline" class="feature-icon"></ion-icon>
                    <span class="feature-text">Perhitungan seleksi otomatis</span>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-header">
                <h2 class="login-title">Welcome Back</h2>
                <p class="login-subtitle">Silakan masuk ke akun Anda</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    <ion-icon name="alert-circle-outline" style="vertical-align: middle; margin-right: 5px;"></ion-icon>
                    {{ session('error') }}
                </div>
            @endif

            <form action="/login" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <input type="text" name="email" class="form-control" placeholder="Masukkan email Anda" required>
                        <ion-icon name="mail-outline" class="input-icon"></ion-icon>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        <ion-icon name="lock-closed-outline" class="input-icon"></ion-icon>
                    </div>
                </div>

                <button type="submit" class="btn-login mb-3">
                    <ion-icon name="log-in-outline" style="vertical-align: middle; margin-right: 8px;"></ion-icon>
                    Login
                </button>

                <div class="login-footer">
                    <p>© 2024 M-Coffee System. All rights reserved.</p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');
            
            inputs.forEach(input => {
                // Add focus effect
                input.addEventListener('focus', function() {
                    this.parentElement.querySelector('.input-icon').style.color = 'var(--blue)';
                });
                
                // Remove focus effect
                input.addEventListener('blur', function() {
                    this.parentElement.querySelector('.input-icon').style.color = 'var(--black2)';
                });
            });
            
            // Form submission animation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const btn = this.querySelector('.btn-login');
                btn.innerHTML = '<ion-icon name="hourglass-outline" style="vertical-align: middle; margin-right: 8px;"></ion-icon> Processing...';
                btn.disabled = true;
            });
        });
    </script>

</body>
</html>