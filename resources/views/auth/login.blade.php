<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - K-JOBS</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    
    <style>
        :root {
            --primary-color: #3498db;
            --gradient-primary: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            border: none;
            padding: 1.5rem;
        }
        
        .form-control-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
        
        .input-group-text {
            background-color: white;
        }
        
        .toggle-password {
            border-left: 0;
        }
        
        .toggle-password:hover {
            background-color: #e9ecef;
        }
        
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .rounded-pill {
            border-radius: 50rem !important;
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-md-8 col-lg-6">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header py-4" style="background: var(--gradient-primary);">
                            <h3 class="text-center text-white fw-bold fs-4 mb-0">{{ __('Login to K-JOBS') }}</h3>
                        </div>

                        <div class="card-body p-5">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="username" class="form-label">{{ __('Username') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user text-primary"></i>
                                        </span>
                                        <input id="username" type="text" 
                                            class="form-control form-control-lg @error('username') is-invalid @enderror" 
                                            name="username" value="{{ old('username') }}" 
                                            required autocomplete="username" autofocus
                                            placeholder="Masukkan username Anda">
                                    </div>
                                    @error('username')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                        <input id="password" type="password" 
                                            class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                            name="password" required autocomplete="current-password"
                                            placeholder="Masukkan password Anda">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3" style="background: var(--gradient-primary); border: none;">
                                        {{ __('Login') }} <i class="fas fa-sign-in-alt ms-2"></i>
                                    </button>
                                </div>

                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a class="text-decoration-none" href="{{ route('password.request') }}" style="color: var(--primary-color);">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.toggle-password');
            const password = document.querySelector('#password');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>