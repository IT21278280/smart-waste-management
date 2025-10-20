@extends('layouts.public')

@section('title', 'Features - Smart Waste Management')

@section('content')
<!-- Features Hero -->
<section class="hero-section" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center text-white">
                <h1 class="display-4 fw-bold mb-4">Powerful Features</h1>
                <p class="lead">Everything you need for intelligent waste management</p>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Features -->
<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <!-- AI Classification -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="feature-card p-5">
                    <div class="feature-icon mb-4" style="background: linear-gradient(45deg, #667eea, #764ba2); width: 100px; height: 100px; font-size: 2.5rem;">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3 class="fw-bold mb-3">AI-Powered Classification</h3>
                    <p class="text-muted mb-4">
                        Advanced machine learning algorithms analyze waste images and automatically classify them into categories:
                        Organic, Plastic, Metal, Glass, and Hazardous waste with high accuracy.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>99%+ accuracy rate</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Real-time processing</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Confidence scoring</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Continuous learning</li>
                    </ul>
                </div>
            </div>

            <!-- Mobile App -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="feature-card p-5">
                    <div class="feature-icon mb-4" style="background: linear-gradient(45deg, #f093fb, #f5576c); width: 100px; height: 100px; font-size: 2.5rem;">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Mobile-First Design</h3>
                    <p class="text-muted mb-4">
                        Cross-platform mobile application built with Flutter, providing a seamless experience
                        across iOS and Android devices with offline capabilities.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Native performance</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Offline support</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Push notifications</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Intuitive interface</li>
                    </ul>
                </div>
            </div>

            <!-- Location Services -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="feature-card p-5">
                    <div class="feature-icon mb-4" style="background: linear-gradient(45deg, #43e97b, #38f9d7); width: 100px; height: 100px; font-size: 2.5rem;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="fw-bold mb-3">GPS Location Tracking</h3>
                    <p class="text-muted mb-4">
                        Precise GPS coordinates for every waste report, enabling authorities to locate
                        and respond to issues quickly and efficiently.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>High accuracy GPS</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Address geocoding</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Map integration</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Route optimization</li>
                    </ul>
                </div>
            </div>

            <!-- Analytics Dashboard -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="feature-card p-5">
                    <div class="feature-icon mb-4" style="background: linear-gradient(45deg, #fa709a, #fee140); width: 100px; height: 100px; font-size: 2.5rem;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Analytics & Reporting</h3>
                    <p class="text-muted mb-4">
                        Comprehensive dashboard with real-time analytics, trends analysis, and detailed
                        reporting for waste management optimization.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Real-time metrics</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Trend analysis</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Export capabilities</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Custom reports</li>
                    </ul>
                </div>
            </div>

            <!-- Real-time Updates -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="feature-card p-5">
                    <div class="feature-icon mb-4" style="background: linear-gradient(45deg, #a8edea, #fed6e3); width: 100px; height: 100px; font-size: 2.5rem;">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Real-Time Notifications</h3>
                    <p class="text-muted mb-4">
                        Instant notifications for report status updates, new assignments, and important
                        announcements to keep everyone informed.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Push notifications</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Email alerts</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Status tracking</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Assignment alerts</li>
                    </ul>
                </div>
            </div>

            <!-- Security -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="feature-card p-5">
                    <div class="feature-icon mb-4" style="background: linear-gradient(45deg, #ffecd2, #fcb69f); width: 100px; height: 100px; font-size: 2.5rem;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Enterprise Security</h3>
                    <p class="text-muted mb-4">
                        Bank-grade security with end-to-end encryption, secure authentication,
                        and comprehensive data protection measures.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>SSL encryption</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Two-factor auth</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Data encryption</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>GDPR compliant</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Technical Specifications -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">Technical Excellence</h2>
                <p class="lead text-muted">Built with cutting-edge technology stack</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="p-4">
                    <i class="fas fa-code text-primary fa-3x mb-3"></i>
                    <h5 class="fw-bold">Laravel Backend</h5>
                    <p class="text-muted">Robust PHP framework with RESTful APIs</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="p-4">
                    <i class="fas fa-mobile text-success fa-3x mb-3"></i>
                    <h5 class="fw-bold">Flutter Mobile</h5>
                    <p class="text-muted">Cross-platform mobile apps with Dart</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="p-4">
                    <i class="fas fa-database text-info fa-3x mb-3"></i>
                    <h5 class="fw-bold">MySQL Database</h5>
                    <p class="text-muted">Reliable relational database management</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="p-4">
                    <i class="fas fa-brain text-warning fa-3x mb-3"></i>
                    <h5 class="fw-bold">TensorFlow ML</h5>
                    <p class="text-muted">Advanced machine learning models</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="p-4">
                    <i class="fas fa-docker text-secondary fa-3x mb-3"></i>
                    <h5 class="fw-bold">Docker Deployment</h5>
                    <p class="text-muted">Containerized deployment for scalability</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="p-4">
                    <i class="fas fa-cloud text-danger fa-3x mb-3"></i>
                    <h5 class="fw-bold">Cloud Ready</h5>
                    <p class="text-muted">Designed for cloud deployment and scaling</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
