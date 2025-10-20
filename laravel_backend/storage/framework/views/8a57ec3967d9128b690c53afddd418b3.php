<?php $__env->startSection('title', 'Dashboard - Smart Waste Management'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-description', 'Monitor your smart waste management system performance and insights'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="100">
        <div class="card border-0 shadow-sm h-100 dashboard-card-users">
            <div class="card-body text-center p-4">
                <div class="icon-circle mb-3">
                    <i class="fas fa-users fa-2x text-white"></i>
                </div>
                <h2 class="fw-bold mb-1 text-dark"><?php echo e($stats['users'] ?? 0); ?></h2>
                <p class="mb-0 text-muted fw-medium">Total Users</p>
                <div class="progress mt-3" style="height: 6px; background-color: #e9ecef;">
                    <div class="progress-bar" style="width: 85%; background-color: #3b82f6;"></div>
                </div>
                <small class="text-success mt-2 d-block"><i class="fas fa-arrow-up me-1"></i>+12% from last month</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="200">
        <div class="card border-0 shadow-sm h-100 dashboard-card-reports">
            <div class="card-body text-center p-4">
                <div class="icon-circle mb-3">
                    <i class="fas fa-file-alt fa-2x text-white"></i>
                </div>
                <h2 class="fw-bold mb-1 text-dark"><?php echo e($stats['reports'] ?? 0); ?></h2>
                <p class="mb-0 text-muted fw-medium">Total Reports</p>
                <div class="progress mt-3" style="height: 6px; background-color: #e9ecef;">
                    <div class="progress-bar" style="width: 92%; background-color: #10b981;"></div>
                </div>
                <small class="text-success mt-2 d-block"><i class="fas fa-arrow-up me-1"></i>+8% from last week</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="300">
        <div class="card border-0 shadow-sm h-100 dashboard-card-pending">
            <div class="card-body text-center p-4">
                <div class="icon-circle mb-3">
                    <i class="fas fa-clock fa-2x text-white"></i>
                </div>
                <h2 class="fw-bold mb-1 text-dark"><?php echo e($stats['pending'] ?? 0); ?></h2>
                <p class="mb-0 text-muted fw-medium">Pending Reports</p>
                <div class="progress mt-3" style="height: 6px; background-color: #e9ecef;">
                    <div class="progress-bar" style="width: 65%; background-color: #f59e0b;"></div>
                </div>
                <small class="text-warning mt-2 d-block"><i class="fas fa-arrow-down me-1"></i>-5% from yesterday</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="400">
        <div class="card border-0 shadow-sm h-100 dashboard-card-processed">
            <div class="card-body text-center p-4">
                <div class="icon-circle mb-3">
                    <i class="fas fa-check-circle fa-2x text-white"></i>
                </div>
                <h2 class="fw-bold mb-1 text-dark"><?php echo e($stats['processed'] ?? 0); ?></h2>
                <p class="mb-0 text-muted fw-medium">Processed Reports</p>
                <div class="progress mt-3" style="height: 6px; background-color: #e9ecef;">
                    <div class="progress-bar" style="width: 78%; background-color: #8b5cf6;"></div>
                </div>
                <small class="text-success mt-2 d-block"><i class="fas fa-arrow-up me-1"></i>+15% efficiency</small>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="col-lg-8 mb-4" data-aos="fade-up" data-aos-delay="500">
        <div class="card h-100">
            <div class="card-header text-white d-flex align-items-center" style="background-color: var(--primary-color); border-bottom: 1px solid var(--gray-200);">
                <i class="fas fa-server me-2"></i>
                <h5 class="mb-0 fw-semibold">System Health Monitor</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="status-item p-3 bg-light" style="border: 1px solid var(--gray-200);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="status-icon me-3">
                                        <i class="fas fa-code text-success fa-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">Laravel API</h6>
                                        <small class="text-muted">Backend Service</small>
                                    </div>
                                </div>
                                <span class="badge bg-success px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i>Online
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="status-item p-3 bg-light" style="border: 1px solid var(--gray-200);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="status-icon me-3">
                                        <i class="fas fa-database text-info fa-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">Database</h6>
                                        <small class="text-muted">MySQL Connection</small>
                                    </div>
                                </div>
                                <span class="badge bg-success px-3 py-2">
                                    <i class="fas fa-link me-1"></i>Connected
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="status-item p-3 bg-light" style="border: 1px solid var(--gray-200);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="status-icon me-3">
                                        <i class="fas fa-brain text-warning fa-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">ML Service</h6>
                                        <small class="text-muted">AI Classification</small>
                                    </div>
                                </div>
                                <span class="badge bg-warning px-3 py-2">
                                    <i class="fas fa-spinner fa-spin me-1"></i>Checking...
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="status-item p-3 bg-light" style="border: 1px solid var(--gray-200);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="status-icon me-3">
                                        <i class="fas fa-memory text-danger fa-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">Redis Cache</h6>
                                        <small class="text-muted">Memory Store</small>
                                    </div>
                                </div>
                                <span class="badge bg-success px-3 py-2">
                                    <i class="fas fa-bolt me-1"></i>Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header text-white" style="background-color: var(--info-color); border-bottom: 1px solid var(--gray-200);">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h5>
            </div>
            <div class="card-body">
                <?php if(isset($recentReports) && count($recentReports) > 0): ?>
                    <?php $__currentLoopData = $recentReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="fas fa-file-alt text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">New Report #<?php echo e($report->id); ?></div>
                            <small class="text-muted"><?php echo e($report->created_at->diffForHumans()); ?></small>
                        </div>
                        <span class="badge bg-<?php echo e($report->status === 'pending' ? 'warning' : 'success'); ?>">
                            <?php echo e(ucfirst($report->status)); ?>

                        </span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p>No recent activity</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header text-white" style="background-color: var(--dark-color); border-bottom: 1px solid var(--gray-200);">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="/test" class="btn btn-primary w-100">
                            <i class="fas fa-flask me-2"></i>Test API
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="/reports" class="btn btn-success w-100">
                            <i class="fas fa-file-alt me-2"></i>View Reports
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="/users" class="btn btn-info w-100">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="/analytics" class="btn btn-warning w-100">
                            <i class="fas fa-chart-bar me-2"></i>Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- API Endpoints -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-code me-2"></i>API Endpoints</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Method</th>
                                <th>Endpoint</th>
                                <th>Description</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-success">GET</span></td>
                                <td>/api/health</td>
                                <td>Health check</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">POST</span></td>
                                <td>/api/register</td>
                                <td>User registration</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">POST</span></td>
                                <td>/api/login</td>
                                <td>User authentication</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">GET</span></td>
                                <td>/api/user</td>
                                <td>Get current user</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">POST</span></td>
                                <td>/api/logout</td>
                                <td>User logout</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">GET</span></td>
                                <td>/api/reports</td>
                                <td>Get reports</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">POST</span></td>
                                <td>/api/reports</td>
                                <td>Create report</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Check ML Service status
fetch('/api/health')
    .then(response => response.json())
    .then(data => {
        console.log('API Health:', data);
    })
    .catch(error => {
        console.error('API Health Check Failed:', error);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Smart Waste App\laravel_backend\resources\views/dashboard.blade.php ENDPATH**/ ?>