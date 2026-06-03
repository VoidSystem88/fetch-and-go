<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fetch and Go - Fast & Reliable Delivery</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
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
            overflow-x: hidden;
        }
        
        /* Desktop Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/deskwel.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }
        
        /* Mobile Background - iba ang image */
        @media (max-width: 768px) {
            body::before {
                background-image: url('{{ asset("images/loginwel.png") }}');
                background-size: cover;
                background-position: center;
            }
        }
        
        /* Dark overlay para mabasa ang text */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.55);
            z-index: -1;
        }
        
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 40px 80px;
            position: relative;
        }
        
        /* Left Content */
        .hero-left {
            flex: 1;
            max-width: 600px;
            animation: fadeInLeft 0.8s ease;
        }
        
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Right Content - Logo */
        .hero-right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeInRight 0.8s ease;
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .logo-large {
            max-width: 450px;
            width: 100%;
            height: auto;
            filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.3));
            transition: transform 0.3s;
        }
        
        .logo-large:hover {
            transform: scale(1.02);
        }
        
        .badge {
            display: inline-block;
            background: rgba(56, 189, 85, 0.2);
            border: 1px solid rgba(56, 189, 85, 0.4);
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 500;
            color: #38bd55;
            margin-bottom: 20px;
        }
        
        h1 {
            font-size: 56px;
            font-weight: 800;
            color: white;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        
        .highlight {
            background: linear-gradient(135deg, #38bd55, #2a9e46);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .tagline {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 50px;
            padding: 8px 20px;
            backdrop-filter: blur(5px);
        }
        
        .feature-item i {
            font-size: 18px;
            color: #38bd55;
        }
        
        .feature-item span {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 14px 32px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #38bd55, #2a9e46);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(56, 189, 85, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(56, 189, 85, 0.4);
        }
        
        .btn-outline {
            border: 2px solid #38bd55;
            color: white;
            background: transparent;
        }
        
        .btn-outline:hover {
            background: #38bd55;
            color: white;
            transform: translateY(-3px);
        }
        
        .apply-link {
            margin-top: 30px;
        }
        
        .apply-link a {
            color: rgba(255, 255, 255, 0.45);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .apply-link a:hover {
            color: #38bd55;
        }
        
        /* Mobile Responsive */
        @media (max-width: 992px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding: 40px 30px;
                justify-content: center;
                gap: 40px;
            }
            
            .hero-left {
                max-width: 100%;
                text-align: center;
            }
            
            .features {
                justify-content: center;
            }
            
            .buttons {
                justify-content: center;
            }
            
            h1 {
                font-size: 42px;
            }
            
            .tagline {
                font-size: 16px;
            }
            
            .logo-large {
                max-width: 280px;
            }
        }
        
        @media (max-width: 768px) {
            .hero {
                padding: 30px 20px;
                gap: 30px;
            }
            
            h1 {
                font-size: 32px;
            }
            
            .badge {
                font-size: 11px;
            }
            
            .feature-item {
                padding: 6px 14px;
            }
            
            .feature-item i {
                font-size: 14px;
            }
            
            .feature-item span {
                font-size: 12px;
            }
            
            .btn {
                padding: 10px 24px;
                font-size: 14px;
            }
            
            .logo-large {
                max-width: 220px;
            }
        }
        
        @media (min-width: 1400px) {
            .hero {
                padding: 40px 120px;
            }
            
            h1 {
                font-size: 68px;
            }
            
            .logo-large {
                max-width: 550px;
            }
        }
    </style>
</head>
<body>
    <div class="hero">
        <!-- Left Side - Content -->
        <div class="hero-left">
            <div class="badge">
                <i class="fas fa-truck-fast"></i> Premium Delivery Service
            </div>
            
            <h1>
                Fast & Reliable<br>
                <span class="highlight">Delivery Service</span>
            </h1>
            
            <p class="tagline">
                Get your packages delivered quickly and safely with Fetch and Go. 
                Real-time tracking, competitive rates, and 24/7 customer support.
            </p>
            
            <div class="features">
                <div class="feature-item">
                    <i class="fas fa-bolt"></i>
                    <span>Fast Delivery</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Live Tracking</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>100% Safe</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-headset"></i>
                    <span>24/7 Support</span>
                </div>
            </div>
            
            <div class="buttons">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            </div>
            
            <div class="apply-link">
                <a href="{{ route('apply.form') }}">
                    <i class="fas fa-file-alt"></i> Apply as Rider or Staff
                </a>
            </div>
        </div>
        
        <!-- Right Side - Large Logo -->
        <div class="hero-right">
            <img src="{{ asset('images/fetchlogo.png') }}" alt="Fetch and Go Logo" class="logo-large">
        </div>
    </div>
</body>
</html>