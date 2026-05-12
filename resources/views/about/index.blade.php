@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container py-5 mt-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="display-4 fw-bold" style="color: #0D5C63;">
            About Graduate Library
        </h1>
    </div>
    
    <!-- Section 1: College Campus Image -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6" data-aos="fade-right">
            <img src="images/graduatecollege.jpeg" alt="College Campus" class="img-fluid rounded-4 shadow-lg" style="transition: transform 0.3s ease; width: 100%; height: 350px; object-fit: cover;">
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <h2 class="mb-4" style="color: #0D5C63;">Welcome to Graduate Library</h2>
            <p style="color: #6B7280;">The Graduate Library is a modern, well-equipped resource center established to support the academic and research needs of students, faculty, and researchers. Situated in the beautiful campus of Government Graduate College Sheikhupura, our library serves as a hub of knowledge and learning.</p>
            <div class="mt-4">
                <div class="d-flex mb-3">
                    <i class="fas fa-calendar-alt fa-2x me-3" style="color: #0D5C63;"></i>
                    <div>
                        <h5 style="color: #2D3748;">Established</h5>
                        <p style="color: #6B7280;">Since 1959 | Modernized in 2020</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <i class="fas fa-map-marker-alt fa-2x me-3" style="color: #0D5C63;"></i>
                    <div>
                        <h5 style="color: #2D3748;">Location</h5>
                        <p style="color: #6B7280;">Main Campus, Government Graduate College Sheikhupura</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Section 2: Library Interior Image -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 order-lg-2" data-aos="fade-left">
            <img src="images/main.jpeg" alt="Library Interior" class="img-fluid rounded-4 shadow-lg" style="transition: transform 0.3s ease; width: 100%; height: 350px; object-fit: cover;">
        </div>
        <div class="col-lg-6 order-lg-1" data-aos="fade-right">
            <h2 class="mb-4" style="color: #0D5C63;">Library Resources & Facilities</h2>
            <p style="color: #6B7280;">Step into our spacious library where knowledge meets comfort. We provide a wide range of resources and facilities to support teaching, learning, and research activities across all disciplines.</p>
            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="p-3 bg-white rounded-3 shadow-sm h-100">
                        <i class="fas fa-book fa-2x mb-2" style="color: #0D5C63;"></i>
                        <h6 style="color: #2D3748;">Textbooks Collection</h6>
                        <small style="color: #6B7280;">All course-related books available</small>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="p-3 bg-white rounded-3 shadow-sm h-100">
                        <i class="fas fa-laptop fa-2x mb-2" style="color: #0D5C63;"></i>
                        <h6 style="color: #2D3748;">Digital Resources</h6>
                        <small style="color: #6B7280;">Online databases & e-books access</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Section 3: Study Area Image -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6" data-aos="fade-right">
            <img src="images/girls.jpeg" alt="Study Area" class="img-fluid rounded-4 shadow-lg" style="transition: transform 0.3s ease; width: 100%; height: 350px; object-fit: cover;">
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <h2 class="mb-4" style="color: #0D5C63;">Library Services for Students</h2>
            <p style="color: #6B7280;">We offer comprehensive library services to ensure students and faculty get the most out of their library experience in a peaceful study environment.</p>
            <div class="mt-4">
                <div class="d-flex mb-3">
                    <i class="fas fa-id-card fa-2x me-3" style="color: #0D5C63;"></i>
                    <div>
                        <h5 style="color: #2D3748;">Free Library Membership</h5>
                        <p style="color: #6B7280;">All enrolled students and faculty get free membership</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <i class="fas fa-book-reader fa-2x me-3" style="color: #0D5C63;"></i>
                    <div>
                        <h5 style="color: #2D3748;">Easy Book Issuance</h5>
                        <p style="color: #6B7280;">Simple borrowing system with flexible return policies</p>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <i class="fas fa-headset fa-2x me-3" style="color: #0D5C63;"></i>
                    <div>
                        <h5 style="color: #2D3748;">Research Support</h5>
                        <p style="color: #6B7280;">Expert librarians available for research assistance</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Section with Counter Animation -->
    <div class="row g-4 mt-3">
        <div class="col-md-3" data-aos="fade-up">
            <div class="stat-card p-4 bg-white rounded-4 shadow-sm text-center h-100">
                <i class="fas fa-book fa-3x mb-3" style="color: #0D5C63;"></i>
                <h3 class="fw-bold counter-stat" style="color: #0D5C63; font-size: 2.5rem;" data-target="10000">0</h3>
                <p style="color: #6B7280;">Books Available</p>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card p-4 bg-white rounded-4 shadow-sm text-center h-100">
                <i class="fas fa-users fa-3x mb-3" style="color: #0D5C63;"></i>
                <h3 class="fw-bold counter-stat" style="color: #0D5C63; font-size: 2.5rem;" data-target="5000">0</h3>
                <p style="color: #6B7280;">Active Members</p>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card p-4 bg-white rounded-4 shadow-sm text-center h-100">
                <i class="fas fa-chair fa-3x mb-3" style="color: #0D5C63;"></i>
                <h3 class="fw-bold counter-stat" style="color: #0D5C63; font-size: 2.5rem;" data-target="200">0</h3>
                <p style="color: #6B7280;">Reading Seats</p>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card p-4 bg-white rounded-4 shadow-sm text-center h-100">
                <i class="fas fa-clock fa-3x mb-3" style="color: #0D5C63;"></i>
                <h3 class="fw-bold counter-stat" style="color: #0D5C63; font-size: 2.5rem;" data-target="24">0</h3>
                <p style="color: #6B7280;">Digital Access</p>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-5 pt-4" data-aos="fade-up">
        <p class="lead" style="color: #4A5568;">"A library is not just a collection of books, it is a gateway to knowledge, imagination, and endless possibilities."</p>
        <div class="mt-4">
            <a href="/student/register" class="btn-gradient btn-lg">
                <i class="fas fa-user-plus me-2"></i>Become a Member Today
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Counter Animation for Statistics Section
    function animateStatsCounters() {
        const counters = document.querySelectorAll('.counter-stat');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            let current = 0;
            const increment = target / 50;
            const stepTime = 2000 / 50;
            
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    if (target === 24) {
                        counter.innerText = Math.floor(current);
                    } else {
                        counter.innerText = Math.floor(current).toLocaleString();
                    }
                    setTimeout(updateCounter, stepTime);
                } else {
                    if (target === 24) {
                        counter.innerText = '24/7';
                    } else {
                        counter.innerText = target.toLocaleString() + '+';
                    }
                }
            };
            
            updateCounter();
        });
    }
    
    // Intersection Observer for counters
    const observerOptions = { threshold: 0.3, rootMargin: "0px" };
    
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStatsCounters();
                statsObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe the statistics section
    const statsSection = document.querySelector('.stat-card');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }
</script>
@endpush

@endsection