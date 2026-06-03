<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fetch and Go') }} - Register</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            position: relative;
        }
        
        /* Background Image */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/loginwel.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }
        
        /* Dark overlay para mabasa ang text */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.65);
            z-index: -1;
        }
        
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        /* Transparent Card - Walang Blur */
        .register-card {
            background: rgba(0, 0, 0, 0.55);
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 45px 40px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeInUp 0.6s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Logo Image */
        .logo-wrapper {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .logo-image {
            width: 200px;
            max-width: 100%;
            height: auto;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.3));
        }
        
        .logo h1 {
            font-size: 28px;
            font-weight: bold;
            margin-top: 10px;
            color: white;
        }
        
        .logo p {
            color: rgba(255,255,255,0.6);
            font-size: 14px;
            margin-top: 5px;
        }
        
        .form-group {
            margin-bottom: 18px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: rgba(255,255,255,0.8);
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 14px;
            font-size: 14px;
            color: white;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #38bd55;
            background: rgba(255,255,255,0.12);
        }
        
        .form-group input::placeholder {
            color: rgba(255,255,255,0.4);
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.45);
            font-size: 14px;
        }
        
        .input-icon input {
            padding-left: 42px;
        }
        
        .btn-register {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #38bd55, #2a9e46);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            opacity: 0.9;
            box-shadow: 0 8px 20px rgba(56,189,85,0.3);
        }
        
        .login-link {
            text-align: center;
            margin-top: 22px;
            color: rgba(255,255,255,0.5);
            font-size: 14px;
        }
        
        .login-link a {
            color: #38bd55;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            background: rgba(239,68,68,0.15);
            border: 1px solid rgba(239,68,68,0.3);
            color: #f87171;
            padding: 12px 16px;
            border-radius: 14px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .error-message i {
            font-size: 16px;
        }
        
        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        @media (max-width: 768px) {
            .register-card {
                padding: 30px 24px;
                margin: 0 15px;
                border-radius: 32px;
            }
            
            .logo-image {
                width: 150px;
            }
            
            .logo h1 {
                font-size: 24px;
            }
            
            .row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .btn-register {
                padding: 11px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="logo-wrapper">
                <img src="{{ asset('images/fetchlogo.png') }}" alt="Fetch and Go Logo" class="logo-image">
            </div>
            <div class="logo">
                <h1>Create Account</h1>
                <p>Join Fetch and Go today!</p>
            </div>
            
            @if ($errors->any())
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group">
                    <label>Full Name</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required autofocus>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Phone Number</label>
                    <div class="input-icon">
                        <i class="fas fa-phone"></i>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="09XX XXX XXXX">
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password_confirmation" placeholder="••••••••" required>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Register
                </button>
                
                <div class="login-link">
                    Already have an account? <a href="{{ route('login') }}">Login here</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>