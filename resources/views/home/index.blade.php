@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="display-4 fw-bold mb-4 animate-fadeInUp" style="color: #0D5C63;">
                    Welcome to Graduate Library
                </h1>
                <p class="lead mb-4" style="color: #4A5568;">Your gateway to knowledge, imagination, and academic excellence. Access thousands of books, journals, and resources anytime, anywhere.</p>
                <div class="d-flex gap-3">
                    <a href="/student/login" class="btn-gradient btn-lg">
                        <i class="fas fa-rocket me-2"></i>Get Started
                    </a>
                   
                </div>
            </div>
          <div class="col-lg-6" data-aos="fade-left" style="position: relative; overflow: hidden; border-radius: 20px; height: 400px; background: transparent;">
    
    <!-- Images Container -->
    <div style="position: relative; width: 100%; height: 100%;">
        <img src="images/graduatecollege.jpeg" class="slide-img" style="position: absolute; width: 100%; height: 100%; object-fit: cover; transition: opacity 0.8s ease; opacity: 1;">
        <img src="images/boys.png" class="slide-img" style="position: absolute; width: 100%; height: 100%; object-fit: cover; transition: opacity 0.8s ease; opacity: 0;">
        <img src="images/main.jpeg" class="slide-img" style="position: absolute; width: 100%; height: 100%; object-fit: cover; transition: opacity 0.8s ease; opacity: 0;">
        <img src="images/girls.jpeg" class="slide-img" style="position: absolute; width: 100%; height: 100%; object-fit: cover; transition: opacity 0.8s ease; opacity: 0;">
    </div>
    
    <!-- Dots -->
    <div style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 10px; z-index: 20; background: rgba(0,0,0,0.4); padding: 6px 15px; border-radius: 30px;">
        <div class="dot" data-index="0" style="width: 10px; height: 10px; background: white; border-radius: 50%; cursor: pointer;"></div>
        <div class="dot" data-index="1" style="width: 10px; height: 10px; background: rgba(255,255,255,0.5); border-radius: 50%; cursor: pointer;"></div>
        <div class="dot" data-index="2" style="width: 10px; height: 10px; background: rgba(255,255,255,0.5); border-radius: 50%; cursor: pointer;"></div>
        <div class="dot" data-index="3" style="width: 10px; height: 10px; background: rgba(255,255,255,0.5); border-radius: 50%; cursor: pointer;"></div>
    </div>
    
    <!-- Arrows -->
    <button class="prev-btn" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; z-index: 20;">←</button>
    <button class="next-btn" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; z-index: 20;">→</button>
</div>

<script>
    // Simple working slider
    (function() {
        const images = document.querySelectorAll('.slide-img');
        const dots = document.querySelectorAll('.dot');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        
        let current = 0;
        let interval;
        
        function showImage(index) {
            // Hide all images
            images.forEach((img, i) => {
                img.style.opacity = '0';
            });
            // Show selected image
            images[index].style.opacity = '1';
            
            // Update dots
            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.style.background = 'white';
                    dot.style.transform = 'scale(1.2)';
                } else {
                    dot.style.background = 'rgba(255,255,255,0.5)';
                    dot.style.transform = 'scale(1)';
                }
            });
            
            current = index;
        }
        
        function nextImage() {
            let next = (current + 1) % images.length;
            showImage(next);
        }
        
        function prevImage() {
            let prev = (current - 1 + images.length) % images.length;
            showImage(prev);
        }
        
        // Auto slide - Change time here (3000 = 3 seconds)
        interval = setInterval(nextImage, 3000);
        
        // Event listeners
        nextBtn.addEventListener('click', function() {
            clearInterval(interval);
            nextImage();
            interval = setInterval(nextImage, 3000);
        });
        
        prevBtn.addEventListener('click', function() {
            clearInterval(interval);
            prevImage();
            interval = setInterval(nextImage, 3000);
        });
        
        dots.forEach((dot, i) => {
            dot.addEventListener('click', function() {
                clearInterval(interval);
                showImage(i);
                interval = setInterval(nextImage, 3000);
            });
        });
    })();
</script>

<style>
    /* No white background */
    .col-lg-6, .col-lg-6 > div, .slide-img {
        background: transparent !important;
    }
    
    /* Hover effects */
    .prev-btn:hover, .next-btn:hover {
        background: #0D5C63 !important;
        transform: translateY(-50%) scale(1.1) !important;
    }
</style>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold" style="color: #0D5C63;">
                Graduate Library Offering
            </h2>
            <p class="lead" style="color: #6B7280;">What makes us the best choice for your academic journey</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="flip-left">
                <div class="service-card text-center equal-card">
                    <i class="fas fa-book-open fa-3x mb-3 icon-animate" style="color: #0D5C63;"></i>
                    <h4 class="counter-service" style="color: #0D5C63;" data-target="10000">0</h4>
                    <h4 style="color: #0D5C63;">Books</h4>
                    <p style="color: #6B7280;">Wide collection of academic and reference books</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="flip-left" data-aos-delay="100">
                <div class="service-card text-center equal-card">
                    <i class="fas fa-users fa-3x mb-3 icon-animate" style="color: #0D5C63;"></i>
                    <h4 style="color: #0D5C63;">Expert Staff</h4>
                    <p style="color: #6B7280;">Professional librarians to assist you</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="flip-left" data-aos-delay="200">
                <div class="service-card text-center equal-card">
                    <i class="fas fa-laptop fa-3x mb-3 icon-animate" style="color: #0D5C63;"></i>
                    <h4 style="color: #0D5C63;">Digital Resources</h4>
                    <p style="color: #6B7280;">E-books and online journals access</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="flip-left" data-aos-delay="300">
                <div class="service-card text-center equal-card">
                    <i class="fas fa-clock fa-3x mb-3 icon-animate" style="color: #0D5C63;"></i>
                    <h4 style="color: #0D5C63;">24/7 Access</h4>
                    <p style="color: #6B7280;">Online resources available anytime</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: #0D5C63;">
    <div class="container text-center" data-aos="zoom-in">
        <h2 class="display-5 fw-bold mb-3" style="color: #FFFFFF;">Sign up now and enjoy a free library membership!</h2>
        <p class="lead mb-4" style="color: #D1D5DB;">Join thousands of students who are already benefiting from our resources</p>
        <a href="/student/register" class="btn btn-light btn-lg rounded-pill px-5" style="color: #0D5C63; background: #FFFFFF;">
            <i class="fas fa-user-plus me-2"></i>Sign Up Now
        </a>
    </div>
</section>

<!-- Books Gallery Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold" style="color: #0D5C63;">
                Featured Books Collection
            </h2>
            <p class="lead" style="color: #6B7280;">Explore our most popular and recommended books</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up">
                <div class="card book-card h-100">
                    <img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?w=400" class="card-img-top" alt="Book" height="250" style="object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title" style="color: #0D5C63;">Classic Literature</h5>
                        <p class="card-text" style="color: #6B7280;">Explore timeless classics from world-renowned authors.</p>
                        <a href="/student/login" class="btn-gradient btn-sm">Request Book</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card book-card h-100">
                    <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400" class="card-img-top" alt="Book" height="250" style="object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title" style="color: #0D5C63;">Science & Technology</h5>
                        <p class="card-text" style="color: #6B7280;">Stay updated with latest scientific discoveries.</p>
                        <a href="/student/login" class="btn-gradient btn-sm">Request Book</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card book-card h-100">
                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=400" class="card-img-top" alt="Book" height="250" style="object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title" style="color: #0D5C63;">Study Guides</h5>
                        <p class="card-text" style="color: #6B7280;">Comprehensive study materials for exams.</p>
                        <a href="/student/login" class="btn-gradient btn-sm">Request Book</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5" style="background: #F5F5F7;">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3" data-aos="fade-up">
                <div class="stat-card p-4 bg-white rounded-4 shadow-sm stat-item">
                    <i class="fas fa-book fa-3x mb-3" style="color: #0D5C63;"></i>
                    <h3 class="fw-bold counter" style="color: #0D5C63;" data-target="10000">0</h3>
                    <p style="color: #6B7280;">Books Available</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card p-4 bg-white rounded-4 shadow-sm stat-item">
                    <i class="fas fa-users fa-3x mb-3" style="color: #0D5C63;"></i>
                    <h3 class="fw-bold counter" style="color: #0D5C63;" data-target="5000">0</h3>
                    <p style="color: #6B7280;">Active Students</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card p-4 bg-white rounded-4 shadow-sm stat-item">
                    <i class="fas fa-calendar-alt fa-3x mb-3" style="color: #0D5C63;"></i>
                    <h3 class="fw-bold counter" style="color: #0D5C63;" data-target="10">0</h3>
                    <p style="color: #6B7280;">Years of Excellence</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card p-4 bg-white rounded-4 shadow-sm stat-item">
                    <i class="fas fa-star fa-3x mb-3" style="color: #0D5C63;"></i>
                    <h3 class="fw-bold counter" style="color: #0D5C63;" data-target="49">0</h3>
                    <p style="color: #6B7280;">Student Rating</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Counter Animation for Services Section (10,000+ Books)
    function animateServiceCounters() {
        const serviceCounters = document.querySelectorAll('.counter-service');
        
        serviceCounters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            if (!target || target === 0) return;
            
            let current = 0;
            const increment = target / 50;
            const duration = 2000;
            const stepTime = duration / 50;
            
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.innerText = Math.floor(current).toLocaleString();
                    setTimeout(updateCounter, stepTime);
                } else {
                    counter.innerText = target.toLocaleString();
                }
            };
            
            updateCounter();
        });
    }
    
    // Counter Animation for Statistics Section
    function animateStatsCounters() {
        const counters = document.querySelectorAll('.counter');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            let current = 0;
            const increment = target / 50;
            const duration = 2000;
            const stepTime = duration / 50;
            
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    if (target === 49) {
                        counter.innerText = (Math.floor(current) / 10).toFixed(1);
                    } else {
                        counter.innerText = Math.floor(current).toLocaleString() + '+';
                    }
                    setTimeout(updateCounter, stepTime);
                } else {
                    if (target === 49) {
                        counter.innerText = '4.9';
                    } else {
                        counter.innerText = target.toLocaleString() + '+';
                    }
                }
            };
            
            updateCounter();
        });
    }
    
    // Start counter animation when sections come into view
    const observerOptions = {
        threshold: 0.3,
        rootMargin: "0px"
    };
    
    // Observer for Services Section (10,000+ Books counter)
    const serviceObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateServiceCounters();
                serviceObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observer for Statistics Section
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStatsCounters();
                statsObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe the service card with counter
    const serviceCardWithCounter = document.querySelector('.counter-service');
    if (serviceCardWithCounter) {
        serviceObserver.observe(serviceCardWithCounter.closest('.service-card'));
    }
    
    // Observe the statistics section
    const statsSection = document.querySelector('.stat-card');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }
</script>
@endpush