<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apply - Fetch and Go</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        
        .apply-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        /* Transparent Card - Walang Blur */
        .apply-card {
            background: rgba(0, 0, 0, 0.55);
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 45px 40px;
            width: 100%;
            max-width: 550px;
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
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 14px;
            font-size: 14px;
            color: white;
            transition: all 0.3s;
            font-family: 'Figtree', sans-serif;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #38bd55;
            background: rgba(255,255,255,0.12);
        }
        
        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: rgba(255,255,255,0.4);
        }
        
        .form-group select option {
            background: #1e1e1e;
            color: white;
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
        
        .input-icon input,
        .input-icon select {
            padding-left: 42px;
        }
        
        .textarea-icon {
            position: relative;
        }
        
        .textarea-icon i {
            position: absolute;
            left: 14px;
            top: 16px;
            color: rgba(255,255,255,0.45);
            font-size: 14px;
        }
        
        .textarea-icon textarea {
            padding-left: 42px;
        }
        
        .btn-submit {
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
        
        .btn-submit:hover {
            transform: translateY(-2px);
            opacity: 0.9;
            box-shadow: 0 8px 20px rgba(56,189,85,0.3);
        }
        
        .back-link {
            text-align: center;
            margin-top: 22px;
        }
        
        .back-link a {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }
        
        .back-link a:hover {
            color: #38bd55;
        }
        
        .success-message {
            background: rgba(56,189,85,0.15);
            border: 1px solid rgba(56,189,85,0.3);
            color: #4ade80;
            padding: 16px;
            border-radius: 14px;
            margin-bottom: 20px;
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
        
        .info-text {
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            margin-top: 6px;
        }
        
        @media (max-width: 768px) {
            .apply-card {
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
            
            .btn-submit {
                padding: 11px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="apply-container">
        <div class="apply-card">
            <div class="logo-wrapper">
                <img src="{{ asset('images/fetchlogo.png') }}" alt="Fetch and Go Logo" class="logo-image">
            </div>
            <div class="logo">
                <h1>Join Fetch and Go</h1>
                <p>Apply as a delivery rider or staff</p>
            </div>

            @if(session('success'))
                <div class="success-message">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-xl mt-0.5"></i>
                        <div>
                            <p class="font-semibold">Application Submitted! 🎉</p>
                            <p class="text-sm mt-1">{{ session('success') }}</p>
                            <div class="flex items-center gap-2 text-xs mt-2" style="color: #4ade80;">
                                <i class="fas fa-envelope"></i>
                                <span>Check your email for updates</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('apply.submit') }}">
                @csrf
                
                <div class="form-group">
                    <label>Full Name</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" required placeholder="Enter your full name">
                    </div>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" required placeholder="example@email.com">
                    </div>
                    <p class="info-text">We'll send updates to this email</p>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <div class="input-icon">
                        <i class="fas fa-phone"></i>
                        <input type="tel" name="phone" required placeholder="09XX XXX XXXX">
                    </div>
                </div>

                <div class="form-group">
                    <label>Position Applying For</label>
                    <div class="input-icon">
                        <i class="fas fa-briefcase"></i>
                        <select name="position" required>
                            <option value="">Select Position</option>
                            <option value="rider">🛵 Delivery Rider</option>
                            <option value="staff">📋 Staff</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Why do you want to join?</label>
                    <div class="textarea-icon">
                        <i class="fas fa-comment"></i>
                        <textarea name="message" rows="4" placeholder="Tell us about yourself..."></textarea>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Application
                </button>

                <div class="back-link">
                    <a href="{{ route('login') }}">
                        ← Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>