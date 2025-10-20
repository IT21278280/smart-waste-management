@extends('layouts.public')

@section('title', 'Smart Waste Management - AI-Powered Waste Classification')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="display-4 fw-bold mb-4">
                    Smart Waste Management with
                    <span class="text-warning">AI Power</span>
                </h1>
                <p class="lead mb-4 text-white-50">
                    Revolutionize waste collection with intelligent classification,
                    real-time reporting, and smart analytics. Join thousands of users
                    making their communities cleaner and greener.
                </p>
                <div class="d-flex gap-3">
                    <a href="/ml" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-brain me-2"></i>Try AI Classification
                    </a>
                    <a href="#features" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-info-circle me-2"></i>Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center" data-aos="fade-left">
                <div class="floating-animation">
                    <i class="fas fa-recycle text-white" style="font-size: 200px; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="stats-counter" data-target="{{ $stats['total_users'] ?? 0 }}">0</div>
                <p class="text-muted mb-0">Active Users</p>
            </div>
            <div class="col-md-3 mb-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="stats-counter" data-target="{{ $stats['total_reports'] ?? 0 }}">0</div>
                <p class="text-muted mb-0">Reports Submitted</p>
            </div>
            <div class="col-md-3 mb-4" data-aos="zoom-in" data-aos-delay="300">
                <div class="stats-counter" data-target="{{ $stats['pending_reports'] ?? 0 }}">0</div>
                <p class="text-muted mb-0">Pending Reviews</p>
            </div>
            <div class="col-md-3 mb-4" data-aos="zoom-in" data-aos-delay="400">
                <div class="stats-counter" data-target="{{ $stats['resolved_reports'] ?? 0 }}">0</div>
                <p class="text-muted mb-0">Issues Resolved</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="section-padding">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center" data-aos="fade-up">
                <h2 class="display-5 fw-bold mb-3">Powerful Features</h2>
                <p class="lead text-muted">Everything you need for smart waste management</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h5 class="fw-bold mb-3">AI-Powered Classification</h5>
                    <p class="text-muted">
                        Advanced machine learning algorithms automatically classify waste types
                        with high accuracy using computer vision technology.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(45deg, #f093fb, #f5576c);">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Mobile-First Design</h5>
                    <p class="text-muted">
                        Responsive design that works perfectly on all devices,
                        making waste reporting accessible everywhere.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(45deg, #4facfe, #00f2fe);">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Location Tracking</h5>
                    <p class="text-muted">
                        GPS integration for precise location tracking of waste reports,
                        helping authorities respond efficiently.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(45deg, #43e97b, #38f9d7);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Analytics Dashboard</h5>
                    <p class="text-muted">
                        Comprehensive analytics and reporting tools for tracking
                        waste management performance and trends.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(45deg, #fa709a, #fee140);">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Real-Time Notifications</h5>
                    <p class="text-muted">
                        Instant notifications for report updates and status changes,
                        keeping everyone informed.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(45deg, #a8edea, #fed6e3);">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Secure & Private</h5>
                    <p class="text-muted">
                        Enterprise-grade security with data encryption and privacy
                        protection for all users.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center" data-aos="fade-up">
                <h2 class="display-5 fw-bold mb-3">How It Works</h2>
                <p class="lead text-muted">Simple steps to make a difference</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 text-center mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="mb-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <span class="text-white fw-bold fs-4">1</span>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Take a Photo</h5>
                <p class="text-muted">
                    Capture an image of waste using our mobile app with GPS location tracking.
                </p>
            </div>

            <div class="col-md-4 text-center mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="mb-4">
                    <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <span class="text-white fw-bold fs-4">2</span>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">AI Classification</h5>
                <p class="text-muted">
                    Our advanced AI analyzes the image and automatically classifies the waste type.
                </p>
            </div>

            <div class="col-md-4 text-center mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="mb-4">
                    <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <span class="text-white fw-bold fs-4">3</span>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Report & Track</h5>
                <p class="text-muted">
                    Submit the report and track its progress until resolution by authorities.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section-padding" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 text-white" data-aos="fade-right">
                <h2 class="display-5 fw-bold mb-3">Ready to Make a Difference?</h2>
                <p class="lead mb-4">
                    Join thousands of users already using Smart Waste Management to keep their communities clean.
                </p>
            </div>
            <div class="col-lg-4 text-center" data-aos="fade-left">
                <a href="/ml" class="btn btn-light btn-lg px-5 py-3 me-3">
                    <i class="fas fa-rocket me-2"></i>Get Started
                </a>
                <a href="/admin/login" class="btn btn-outline-light btn-lg px-5 py-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Admin Login
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
