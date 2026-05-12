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
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background: #F5F5F7;
            color: #2D3748;
        }
        
       
        .dashboard-sidebar {
            background: white;
            min-height: calc(100vh - 70px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 10px;
        }
        
        .sidebar-menu li a {
            padding: 12px 20px;
            display: block;
            color: #4A5568;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 10px;
            margin: 0 10px;
        }
        
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: #0D5C63;
            color: white;
        }
        
        .sidebar-menu li a i {
            width: 25px;
            margin-right: 10px;
        }
        
        .main-content {
            padding: 30px;
            min-height: calc(100vh - 70px);
        }
        
        .logout-btn {
            color: #DC2626 !important;
        }
        
        .logout-btn:hover {
            background: #DC2626 !important;
            color: white !important;
        }
        
        @media (max-width: 768px) {
            .dashboard-sidebar {
                position: fixed;
                left: -250px;
                top: 70px;
                width: 250px;
                height: 100%;
                z-index: 1000;
                transition: left 0.3s ease;
            }
            
            .dashboard-sidebar.show {
                left: 0;
            }
            
            .main-content {
                padding: 20px;
            }
        }
    </style>
    <script>
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
</script>
    
    @stack('styles')
</head>
<body>
   
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 dashboard-sidebar" id="sidebar">
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.my-issued-books') }}" class="{{ request()->routeIs('student.my-issued-books') ? 'active' : '' }}">
                            <i class="fas fa-book-reader"></i> My Issued Books
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.search-books') }}" class="{{ request()->routeIs('student.search-books') ? 'active' : '' }}">
                            <i class="fas fa-search"></i> Search Books
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.request-books.index') }}" class="{{ request()->routeIs('student.request-books.index') ? 'active' : '' }}">
                            <i class="fas fa-hand-paper"></i> Book Requests
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.contact-reply') }}" class="{{ request()->routeIs('student.contact-reply') ? 'active' : '' }}">
                            <i class="fas fa-envelope"></i> Messages
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.profile') }}" class="{{ request()->routeIs('student.profile') ? 'active' : '' }}">
                            <i class="fas fa-user-circle"></i> Profile
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('student.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn w-100 text-start logout-btn" style="padding: 12px 20px; margin: 0 10px; border-radius: 10px;">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
        
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
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
    <script>
    window.onload = () => {
        window.scrollTo(0, 0);
    };
</script>
    
    @stack('scripts')
</body>
</html>