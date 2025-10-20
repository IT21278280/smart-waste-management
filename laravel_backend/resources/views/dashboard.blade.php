@extends('layouts.app')

@section('title', 'Dashboard - Smart Waste Management')
@section('page-title', 'Dashboard')
@section('page-description', 'Monitor your smart waste management system performance and insights')

@section('content')
<style>
.gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.gradient-bg-2 {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.gradient-bg-3 {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.gradient-bg-4 {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stat-card {
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    position: relative;
}

.icon-circle::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 50%;
    z-index: -1;
    opacity: 0.3;
}

.status-item {
    border-radius: 15px;
    transition: all 0.3s ease;
}

.status-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.quick-action-btn {
    border-radius: 15px;
    padding: 20px;
    transition: all 0.3s ease;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.quick-action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.api-table {
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.modern-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.2);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.pulse-animation {
    animation: pulse 2s infinite;
}
</style>

<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="modern-card p-5 gradient-bg">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="text-white mb-3 fw-bold">
                            <i class="fas fa-leaf me-3"></i>Smart Waste Management
                        </h1>
                        <p class="text-white-50 fs-5 mb-4">
                            Monitor and manage waste collection with AI-powered classification and real-time insights
                        </p>
                        <div class="d-flex gap-3">
                            <a href="/ml-test" class="btn btn-light btn-lg px-4">
                                <i class="fas fa-brain me-2"></i>Test ML Service
                            </a>
                            <a href="/reports" class="btn btn-outline-light btn-lg px-4">
                                <i class="fas fa-chart-line me-2"></i>View Analytics
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="pulse-animation">
                            <i class="fas fa-recycle text-white" style="font-size: 120px; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Cards -->
    <div class="row mb-5">
        <div class="col-xl-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="100">
            <div class="stat-card gradient-bg-4">
                <div class="card-body text-center p-4">
                    <div class="icon-circle mb-4" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                        <i class="fas fa-users fa-3x text-white"></i>
                    </div>
                    <h2 class="fw-bold mb-2 text-white">{{ number_format($stats['users'] ?? 0) }}</h2>
                    <p class="mb-3 text-white-50 fw-medium">Total Users</p>
                    <div class="progress mb-3" style="height: 8px; background-color: rgba(255,255,255,0.3); border-radius: 10px;">
                        <div class="progress-bar bg-white" style="width: 85%; border-radius: 10px;"></div>
                    </div>
                    <small class="text-white-50">
                        <i class="fas fa-arrow-up me-1 text-success"></i>+12% from last month
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="stat-card gradient-bg-3">
                <div class="card-body text-center p-4">
                    <div class="icon-circle mb-4" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                        <i class="fas fa-file-alt fa-3x text-white"></i>
                    </div>
                    <h2 class="fw-bold mb-2 text-white">{{ number_format($stats['reports'] ?? 0) }}</h2>
                    <p class="mb-3 text-white-50 fw-medium">Total Reports</p>
                    <div class="progress mb-3" style="height: 8px; background-color: rgba(255,255,255,0.3); border-radius: 10px;">
                        <div class="progress-bar bg-white" style="width: 92%; border-radius: 10px;"></div>
                    </div>
                    <small class="text-white-50">
                        <i class="fas fa-arrow-up me-1 text-success"></i>+8% from last week
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="300">
            <div class="stat-card gradient-bg-2">
                <div class="card-body text-center p-4">
                    <div class="icon-circle mb-4" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                        <i class="fas fa-clock fa-3x text-white"></i>
                    </div>
                    <h2 class="fw-bold mb-2 text-white">{{ number_format($stats['pending'] ?? 0) }}</h2>
                    <p class="mb-3 text-white-50 fw-medium">Pending Reports</p>
                    <div class="progress mb-3" style="height: 8px; background-color: rgba(255,255,255,0.3); border-radius: 10px;">
                        <div class="progress-bar bg-white" style="width: 65%; border-radius: 10px;"></div>
                    </div>
                    <small class="text-white-50">
                        <i class="fas fa-arrow-down me-1 text-warning"></i>-5% from yesterday
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="400">
            <div class="stat-card gradient-bg">
                <div class="card-body text-center p-4">
                    <div class="icon-circle mb-4" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                        <i class="fas fa-check-circle fa-3x text-white"></i>
                    </div>
                    <h2 class="fw-bold mb-2 text-white">{{ number_format($stats['resolved'] ?? 0) }}</h2>
                    <p class="mb-3 text-white-50 fw-medium">Resolved Reports</p>
                    <div class="progress mb-3" style="height: 8px; background-color: rgba(255,255,255,0.3); border-radius: 10px;">
                        <div class="progress-bar bg-white" style="width: 78%; border-radius: 10px;"></div>
                    </div>
                    <small class="text-white-50">
                        <i class="fas fa-arrow-up me-1 text-success"></i>+15% efficiency
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health Monitor -->
    <div class="row mb-5">
        <div class="col-lg-8" data-aos="fade-up" data-aos-delay="500">
            <div class="modern-card">
                <div class="card-header gradient-bg text-white d-flex align-items-center" style="border-radius: 20px 20px 0 0;">
                    <i class="fas fa-server me-3"></i>
                    <h5 class="mb-0 fw-semibold">System Health Monitor</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="status-item p-4 bg-gradient-primary bg-opacity-10" style="border: 2px solid #3b82f6; border-radius: 15px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="status-icon me-4">
                                            <i class="fas fa-code text-primary fa-2x"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark">Laravel API</h6>
                                            <small class="text-muted">Backend Service</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success px-3 py-2 fs-6">
                                        <i class="fas fa-check-circle me-2"></i>Online
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="status-item p-4 bg-gradient-info bg-opacity-10" style="border: 2px solid #0ea5e9; border-radius: 15px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="status-icon me-4">
                                            <i class="fas fa-database text-info fa-2x"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark">Database</h6>
                                            <small class="text-muted">MySQL Connection</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success px-3 py-2 fs-6">
                                        <i class="fas fa-link me-2"></i>Connected
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="status-item p-4 bg-gradient-warning bg-opacity-10" style="border: 2px solid #f59e0b; border-radius: 15px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="status-icon me-4">
                                            <i class="fas fa-brain text-warning fa-2x"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark">ML Service</h6>
                                            <small class="text-muted">AI Classification</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning px-3 py-2 fs-6">
                                        <i class="fas fa-spinner fa-spin me-2"></i>Checking...
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="status-item p-4 bg-gradient-success bg-opacity-10" style="border: 2px solid #10b981; border-radius: 15px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="status-icon me-4">
                                            <i class="fas fa-memory text-success fa-2x"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark">Redis Cache</h6>
                                            <small class="text-muted">Memory Store</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success px-3 py-2 fs-6">
                                        <i class="fas fa-bolt me-2"></i>Active
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="600">
            <div class="modern-card h-100">
                <div class="card-header gradient-bg-3 text-white" style="border-radius: 20px 20px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body p-4">
                    @if(isset($recentReports) && count($recentReports) > 0)
                        @foreach($recentReports as $report)
                        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                            <div class="me-4">
                                <div class="bg-primary rounded-circle p-2">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark">New Report #{{ $report->id }}</div>
                                <small class="text-muted">{{ $report->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="badge bg-{{ $report->status === 'pending' ? 'warning' : 'success' }} px-2 py-1">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                            <p class="mb-0">No recent activity</p>
                            <small class="text-muted">Reports will appear here when submitted</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-header gradient-bg text-white" style="border-radius: 20px 20px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="/test" class="quick-action-btn btn-primary w-100 py-3 fs-6">
                                <i class="fas fa-flask me-2"></i>Test API
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/reports" class="quick-action-btn btn-success w-100 py-3 fs-6">
                                <i class="fas fa-file-alt me-2"></i>View Reports
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/users" class="quick-action-btn btn-info w-100 py-3 fs-6">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/analytics" class="quick-action-btn btn-warning w-100 py-3 fs-6">
                                <i class="fas fa-chart-bar me-2"></i>Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Endpoints -->
    <div class="row">
        <div class="col-12">
            <div class="api-table">
                <div class="card-header gradient-bg text-white">
                    <h5 class="mb-0"><i class="fas fa-code me-2"></i>API Endpoints</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Method</th>
                                    <th class="border-0">Endpoint</th>
                                    <th class="border-0">Description</th>
                                    <th class="border-0">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-success px-3 py-2">GET</span></td>
                                    <td class="fw-semibold">/api/health</td>
                                    <td>Health check</td>
                                    <td><span class="badge bg-success px-3 py-2">Active</span></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary px-3 py-2">POST</span></td>
                                    <td class="fw-semibold">/api/register</td>
                                    <td>User registration</td>
                                    <td><span class="badge bg-success px-3 py-2">Active</span></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary px-3 py-2">POST</span></td>
                                    <td class="fw-semibold">/api/login</td>
                                    <td>User authentication</td>
                                    <td><span class="badge bg-success px-3 py-2">Active</span></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success px-3 py-2">GET</span></td>
                                    <td class="fw-semibold">/api/user</td>
                                    <td>Get current user</td>
                                    <td><span class="badge bg-success px-3 py-2">Active</span></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary px-3 py-2">POST</span></td>
                                    <td class="fw-semibold">/api/logout</td>
                                    <td>User logout</td>
                                    <td><span class="badge bg-success px-3 py-2">Active</span></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success px-3 py-2">GET</span></td>
                                    <td class="fw-semibold">/api/reports</td>
                                    <td>Get reports</td>
                                    <td><span class="badge bg-success px-3 py-2">Active</span></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary px-3 py-2">POST</span></td>
                                    <td class="fw-semibold">/api/reports</td>
                                    <td>Create report</td>
                                    <td><span class="badge bg-success px-3 py-2">Active</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced animations and interactions
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true
    });

    // Check ML Service status
    fetch('/api/health')
        .then(response => response.json())
        .then(data => {
            console.log('API Health:', data);
        })
        .catch(error => {
            console.error('API Health Check Failed:', error);
        });
});
</script>
@endsection
