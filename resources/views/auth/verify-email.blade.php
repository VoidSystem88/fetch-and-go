<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fetch and Go') }} - Verify Email</title>
    
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
        
        .verify-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        /* Transparent Card - Walang Blur */
        .verify-card {
            background: rgba(0, 0, 0, 0.55);
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 45px 40px;
            width: 100%;
            max-width: 500px;
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
            margin-bottom: 20px;
        }
        
        .logo-image {
            width: 200px;
            max-width: 70%;
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
        
        .info-text {
            text-align: center;
            color: rgba(255,255,255,0.7);
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .info-text i {
            color: #38bd55;
            font-size: 48px;
            margin-bottom: 15px;
            display: inline-block;
        }
        
        .success-message {
            background: rgba(56,189,85,0.15);
            border: 1px solid rgba(56,189,85,0.3);
            color: #4ade80;
            padding: 12px 16px;
            border-radius: 14px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-resend {
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
        }
        
        .btn-resend:hover {
            transform: translateY(-2px);
            opacity: 0.9;
            box-shadow: 0 8px 20px rgba(56,189,85,0.3);
        }
        
        .action-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        
        .action-links a, 
        .action-links button {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'Figtree', sans-serif;
        }
        
        .action-links a:hover, 
        .action-links button:hover {
            color: #38bd55;
        }
        
        @media (max-width: 768px) {
            .verify-card {
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
            
            .btn-resend {
                padding: 11px;
                font-size: 15px;
            }
            
            .info-text {
                font-size: 13px;
            }
            
            .action-links {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-card">
            <div class="logo-wrapper">
                <img src="{{ asset('images/fetchlogo.png') }}" alt="Fetch and Go Logo" class="logo-image">
            </div>
            <div class="logo">
                <h1>Verify Your Email</h1>
                <p>Confirm your email address</p>
            </div>
            
            <div class="info-text">
                <i class="fas fa-envelope"></i>
                <p>Before continuing, could you verify your email address by clicking on the link we just emailed to you?</p>
                <p style="margin-top: 12px; font-size: 12px; color: rgba(255,255,255,0.5);">If you didn't receive the email, we will gladly send you another.</p>
            </div>
            
            @if (session('status') == 'verification-link-sent')
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    A new verification link has been sent to your email address.
                </div>
            @endif
            
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-resend">
                    <i class="fas fa-paper-plane"></i> Resend Verification Email
                </button>
            </form>
            
            <div class="action-links">
                <a href="{{ route('profile.show') }}">
                    <i class="fas fa-user-edit"></i> Edit Profile
                </a>
                
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>