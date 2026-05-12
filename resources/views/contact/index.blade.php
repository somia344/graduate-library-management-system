@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="container py-5 mt-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="display-4 fw-bold" style="color: #0D5C63;">Help Center</h1>
        <p class="text-muted" style="color: #6B7280;">If you are already registered, it is advised to contact through dashboard</p>
    </div>
    
    <!-- Contact Card -->
    <div class="row justify-content-center" data-aos="fade-up">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="row g-0">
                    <!-- Left Side - Contact Information -->
                    <div class="col-md-5" style="background: #0D5C63;">
                        <div class="p-4 p-lg-5 text-white h-100">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-4">
                                    <div class="mb-3">
                                        <i class="" style="color: #FFFFFF;"></i>
                                    </div>
                                    <h3 class="">Get in Touch</h3>
                                    <p style="color: rgba(255,255,255,0.85); line-height: 1.6;">Have a question or need assistance? Reach out to us, and we'll be happy to help with your inquiry or any other requests.</p>
                                </div>
                                
                                <div class="mt-auto">
                                    <div class="mb-4">
                                        <!-- Email -->
                                        <div class="contact-info-item">
                                            <div class="contact-icon">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <div class="contact-details">
                                                <h6>Email Us</h6>
                                                <p>library@gcbskp.edu.pk</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Phone -->
                                        <div class="contact-info-item">
                                            <div class="contact-icon">
                                                <i class="fas fa-phone-alt"></i>
                                            </div>
                                            <div class="contact-details">
                                                <h6>Call Us</h6>
                                                <p>+92-42-1234567</p>
                                                <p>+92-300-1234567</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Location -->
                                        <div class="contact-info-item">
                                            <div class="contact-icon">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div class="contact-details">
                                                <h6>Visit Us</h6>
                                                <p>Government Graduate College,<br>Sargodha Road, Sheikhupura</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 pt-3">
                                        <div class="d-flex gap-3">
                                            <a href="https://www.facebook.com/GCbSkp/" target="_blank" class="social-icon">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                            <a href="https://x.com/GCbSkp/" target="_blank" class="social-icon">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                            <a href="https://www.instagram.com/GCbSkp/" target="_blank" class="social-icon">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                            <a href="https://www.linkedin.com/company/govt-college-sheikhupura/" target="_blank" class="social-icon">
                                                <i class="fab fa-linkedin-in"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Side - Contact Form -->
                    <div class="col-md-7">
                        <div class="p-4 p-lg-5">
                            <h3 class="fw-bold mb-4" style="color: #0D5C63;">Send us a Message</h3>
                            
                            @if(session('success'))
                                <div class="alert alert-success mb-4">{{ session('success') }}</div>
                            @endif
                            
                            @if($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <form action="{{ route('contact.send') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Your Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" required placeholder="Enter your full name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" required placeholder="your@email.com">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-medium">Subject <span class="text-danger">*</span></label>
                                        <input type="text" name="subject" class="form-control" required placeholder="What is this regarding?">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-medium">Message <span class="text-danger">*</span></label>
                                        <textarea name="message" rows="5" class="form-control" required placeholder="Please describe your query in detail..."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn-primary-custom w-100 py-3">
                                            <i class="fas fa-paper-plane me-2"></i>Send Message
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-primary-custom {
    background: #0D5C63;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    color: white;
    padding: 12px;
    transition: all 0.3s ease;
}
.btn-primary-custom:hover {
    background: #084C52;
    transform: translateY(-2px);
    color: white;
}
.form-control {
    border-radius: 10px;
    border: 1px solid #E2E8F0;
    padding: 12px 15px;
    transition: all 0.3s ease;
}
.form-control:focus {
    border-color: #0D5C63;
    box-shadow: 0 0 0 3px rgba(13, 92, 99, 0.1);
    outline: none;
}
.form-label {
    color: #2D3748;
    margin-bottom: 8px;
    font-weight: 500;
}
.alert-success {
    background: #D4EDDA;
    color: #155724;
    border: none;
    border-radius: 10px;
    padding: 15px;
}
.alert-danger {
    background: #F8D7DA;
    color: #721C24;
    border: none;
    border-radius: 10px;
    padding: 15px;
}
.divider {
    width: 80px;
    height: 3px;
    background: #0D5C63;
    margin: 10px auto;
}
.text-muted {
    color: #6B7280 !important;
}

/* Contact Info Styles */
.contact-info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 25px;
}

.contact-icon {
    width: 35px;
    height: 35px;
    background: #FFFFFF;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
}

.contact-icon i {
    color: #0D5C63;
    font-size: 20px;
}

.contact-details h6 {
    color: #FFFFFF;
    font-weight: 700;
    margin-bottom: 5px;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
}

.contact-details p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 0;
    font-size: 0.9rem;
    line-height: 1.4;
}

.contact-details p:last-child {
    margin-top: 3px;
}

/* Social Icons */
.social-icon {
    background: rgba(255, 255, 255, 0.15);
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
    text-decoration: none;
}

.social-icon:hover {
    background: #FFFFFF !important;
    color: #0D5C63 !important;
    transform: translateY(-3px);
}

/* Responsive */
@media (max-width: 768px) {
    .contact-info-item {
        margin-bottom: 20px;
    }
    
    .contact-icon {
        width: 40px;
        height: 40px;
    }
    
    .contact-icon i {
        font-size: 18px;
    }
}
</style>
@endsection