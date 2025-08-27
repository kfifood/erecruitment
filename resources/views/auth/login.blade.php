<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login KFI-Vote</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;

            /* Animasi background */
            background: linear-gradient(-45deg, #6a11cb, #2575fc, #20c997, #007bff);
            background-size: 400% 400%;
            animation: gradientBG 12s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            text-align: center;
        }

        .login-card img {
            max-width: 120px;
            margin-bottom: 1rem;
        }

        .login-card h4 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
        }

        .btn-login {
            background: linear-gradient(135deg, #004e92, #000428);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            color: #fff;
            transition: 0.3s;
        }

        .btn-login:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <!-- Logo -->
        <img src="{{ asset('fevicon.png') }}" alt="Logo">
        <h4>Login K-CRAB</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input id="username" type="text" 
                        class="form-control @error('username') is-invalid @enderror" 
                        name="username" value="{{ old('username') }}" required autocomplete="username" autofocus
                        placeholder="Username">
                </div>
                @error('username')
                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input id="password" type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        name="password" required autocomplete="current-password"
                        placeholder="Password">
                </div>
                @error('password')
                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="d-flex justify-content-start mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100">LOGIN</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
