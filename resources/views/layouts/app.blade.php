<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Graduate Library - @yield('title')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background: var(--bg);
            color: var(--text);
        }
        
        /* Custom Color Variables */
        :root {
            --primary: #0D5C63;
            --primary-hover: #084C52;
            --danger: #F24B4B;
            --danger-hover: #D93636;
            --bg: #F5F5F7;
            --navbar: #F1F1F3;
            --text: #2D3748;
            --text-light: #4A5568;
            --text-muted: #6B7280;
            --white: #FFFFFF;
        }
        
        /* Navbar Styles */
        .navbar {
            background: var(--navbar);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
        }
        
        /* Updated Navbar Links with Hover Effect */
        .nav-link {
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
            position: relative;
            color: var(--text) !important;
            padding: 8px 16px !important;
            border-radius: 8px;
        }
        
        .nav-link:hover {
            background: var(--primary) !important;
            color: var(--white) !important;
            transform: translateY(-2px);
        }
        
        .nav-link:active {
            transform: scale(0.95);
        }
        
        /* Dropdown Menu Styles */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 10px;
            margin-top: 10px;
        }
        
        .dropdown-item {
            border-radius: 8px;
            transition: all 0.3s ease;
            padding: 8px 20px;
        }
        
        .dropdown-item:hover {
            background: var(--primary);
            color: var(--white);
            transform: translateX(5px);
        }
        
        /* Register Button */
        .btn-register {
            background: var(--primary);
            color: var(--white);
            padding: 8px 24px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-left: 10px;
            border: none;
        }
        
        .btn-register:hover {
            background: var(--primary-hover);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 92, 99, 0.3);
        }
        
        .btn-register:active {
            transform: scale(0.95);
        }
        
        /* Button Styles */
        .btn-gradient {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: var(--primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: var(--white);
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: var(--white);
        }
        
        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: var(--bg);
            position: relative;
            overflow: hidden;
        }
        
        .hero-section h1 {
            color: var(--primary);
        }
        
        .hero-section .lead {
            color: var(--text-muted);
        }
        
        .hero-image {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        /* Services Section */
        .service-card {
            background: var(--white);
            padding: 40px 20px;
            text-align: center;
            border-radius: 20px;
            transition: all 0.3s ease;
            color: var(--text);
        }
        
        .service-card i {
            color: var(--primary);
            transition: all 0.3s ease;
        }
        
        .service-card h3 {
            color: var(--primary);
            margin-top: 20px;
        }
        
        .service-card p {
            color: var(--text-light);
        }
        
        .service-card:hover {
            background: var(--primary);
            color: var(--white);
        }
        
        .service-card:hover i,
        .service-card:hover h3,
        .service-card:hover p {
            color: var(--white);
        }
        
        /* Featured Books Section */
        .section-title {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
        }
        
        /* Footer */
        .footer {
            background: var(--primary);
            color: var(--white);
            padding: 50px 0 20px;
        }
        
        .footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--white);
        }
        
        /* Login Form Styles */
        .login-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 0;
        }
        
        .login-card {
            background: var(--white);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            max-width: 450px;
            width: 100%;
        }
        
        .login-card h2 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-card .subtitle {
            color: var(--text-light);
            margin-bottom: 30px;
        }
        
        .form-label {
            color: var(--text);
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13, 92, 99, 0.1);
        }
        
        /* Student Dashboard Styles */
        .dashboard-card {
            background: var(--white);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .dashboard-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        
        .dashboard-card h5 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 16px;
        }
        
        .dashboard-card p {
            color: var(--text-light);
            line-height: 1.6;
        }
        
        /* Table Styles */
        .table-container {
            background: var(--white);
            border-radius: 16px;
            padding: 20px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead th {
            background: var(--bg);
            color: var(--text);
            font-weight: 600;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid var(--primary);
        }
        
        tbody td {
            color: var(--text-light);
            padding: 12px;
            border-bottom: 1px solid #E2E8F0;
        }
        
        /* ========== NEW CSS FOR CARDS & COUNTERS ========== */
        
        /* Card equal size and hover effect */
        .book-card {
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .book-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            background: #f8f9fa !important;
        }
        
        .book-card .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .book-card .btn-gradient {
            margin-top: auto;
            align-self: center;
        }
        
        /* Card hover effect - light gray instead of green */
        .book-card:hover .card-title {
            color: #0D5C63 !important;
        }
        
        .book-card:hover .card-text {
            color: #4A5568 !important;
        }
        
        /* Stat card hover effect */
        .stat-card {
            transition: all 0.3s ease;
            cursor: pointer;
            background: var(--white);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            background: #f8f9fa;
        }
        
        /* Card text animation on hover */
        .book-card,
        .stat-card {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        /* Counter number styling */
        .counter {
            font-size: 2.5rem;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        
        /* Statistics section counter animation trigger */
        .stat-item {
            transition: all 0.3s ease;
        }
        
        /* Responsive counter font size */
        @media (max-width: 768px) {
            .counter {
                font-size: 1.8rem;
            }
        }
        
        /* Animations */
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
        
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                text-align: center;
            }
            
            .navbar-brand {
                font-size: 1.4rem;
            }
            
            .btn-gradient {
                padding: 8px 20px;
            }
            
            .login-card {
                padding: 25px;
                margin: 20px;
            }
        }

        /* ========== SERVICE CARDS EQUAL SIZE & ANIMATIONS ========== */

        /* Equal card size */
        .equal-card {
            height: 100%;
            min-height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: all 0.3s ease;
        }

        /* Text move animation on hover */
        .equal-card:hover h4,
        .equal-card:hover p,
        .equal-card:hover i {
            animation: textMove 0.3s ease-in-out;
        }

        @keyframes textMove {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
            100% {
                transform: translateY(0);
            }
        }

        /* Icon animation on hover */
        .icon-animate {
            transition: all 0.3s ease;
        }

        .equal-card:hover .icon-animate {
            transform: scale(1.1) rotate(5deg);
        }

        /* Light gray hover effect for service cards */
        .equal-card:hover {
            background: #f8f9fa !important;
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .equal-card:hover h4,
        .equal-card:hover p {
            color: #0D5C63 !important;
        }

        .equal-card:hover i {
            color: #0D5C63 !important;
        }

        /* Counter service number styling */
        .counter-service {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 0;
            line-height: 1.2;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .counter-service {
                font-size: 2rem;
            }
            
            .equal-card {
                min-height: 240px;
                padding: 30px 15px !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Updated Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-graduation-cap me-2" style="color: #0D5C63;"></i>
                <span style="color: #0D5C63;">Graduate Library</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
                    </li>
                    
                    <!-- Login Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Login
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="loginDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ url('/librarian/login') }}">
                                    <i class="fas fa-user-shield me-2"></i>Librarian Login
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/student/login') }}">
                                    <i class="fas fa-user-graduate me-2"></i>Student Login
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Register Button -->
                    <li class="nav-item">
                        <a class="btn-register" href="{{ url('/student/register') }}">
                            Register
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <main>
        @yield('content')
    </main>
    
    @include('layouts.footer')
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').style.background = 'var(--white)';
                document.querySelector('.navbar').style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            } else {
                document.querySelector('.navbar').style.background = 'var(--navbar)';
            }
        });
        
        // Show success/error messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                showConfirmButton: true
            });
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>