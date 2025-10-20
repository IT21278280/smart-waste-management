<?php $__env->startSection('title', 'Users - Smart Waste Management'); ?>
<?php $__env->startSection('page-title', 'User Management'); ?>
<?php $__env->startSection('page-description', 'Manage registered users and view their activity statistics'); ?>

<?php $__env->startSection('content'); ?>
<!-- User Statistics Cards -->
<div class="row mb-4" data-aos="fade-down">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background-color: var(--primary-color); color: white; border: 2px solid var(--primary-color);">
                <i class="fas fa-users fa-2x mb-3"></i>
                <h3 class="fw-bold mb-1"><?php echo e($users->total()); ?></h3>
                <p class="mb-0 opacity-75">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background-color: var(--success-color); color: white; border: 2px solid var(--success-color);">
                <i class="fas fa-user-plus fa-2x mb-3"></i>
                <h3 class="fw-bold mb-1"><?php echo e($users->where('created_at', '>=', now()->subDays(7))->count()); ?></h3>
                <p class="mb-0 opacity-75">New This Week</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background-color: var(--danger-color); color: white; border: 2px solid var(--danger-color);">
                <i class="fas fa-file-alt fa-2x mb-3"></i>
                <h3 class="fw-bold mb-1"><?php echo e($users->sum('reports_count')); ?></h3>
                <p class="mb-0 opacity-75">Total Reports</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background-color: var(--warning-color); color: white; border: 2px solid var(--warning-color);">
                <i class="fas fa-chart-line fa-2x mb-3"></i>
                <h3 class="fw-bold mb-1"><?php echo e(number_format($users->avg('reports_count') ?? 0, 1)); ?></h3>
                <p class="mb-0 opacity-75">Avg Reports/User</p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: var(--primary-color); border-bottom: 1px solid var(--gray-200);">
                <div class="d-flex align-items-center">
                    <i class="fas fa-users me-2"></i>
                    <h5 class="mb-0 fw-semibold">Registered Users</h5>
                </div>
                <span class="badge bg-white px-3 py-2 fw-semibold" style="color: var(--primary-color) !important; border: 1px solid var(--primary-color);"><?php echo e($users->total()); ?> Total</span>
            </div>
            <div class="card-body p-0">
                <?php if($users->count() > 0): ?>
                    <div class="users-container">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="user-card p-4 border-bottom" data-aos="fade-up" data-aos-delay="<?php echo e($index * 50); ?>">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3">
                                            <div class="avatar text-white d-flex align-items-center justify-content-center position-relative" style="width: 60px; height: 60px; background-color: var(--primary-color); border: 2px solid var(--primary-color);">
                                                <span class="fw-bold fs-4"><?php echo e(substr($user->name, 0, 1)); ?></span>
                                                <?php if($user->reports_count > 0): ?>
                                                    <span class="position-absolute top-0 start-100 translate-middle badge bg-success" style="border: 1px solid var(--success-color);">
                                                        <?php echo e($user->reports_count); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-1">
                                                <h6 class="mb-0 fw-bold text-primary"><?php echo e($user->name); ?></h6>
                                                <span class="mx-2 text-muted">•</span>
                                                <small class="text-muted">#<?php echo e($user->id); ?></small>
                                            </div>
                                            <div class="mb-2">
                                                <i class="fas fa-envelope me-1 text-muted"></i>
                                                <span class="text-muted"><?php echo e($user->email); ?></span>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <?php if($user->phone): ?>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-phone me-1 text-muted"></i>
                                                        <small class="text-muted"><?php echo e($user->phone); ?></small>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar me-1 text-muted"></i>
                                                    <small class="text-muted">Joined <?php echo e($user->created_at->format('M d, Y')); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="mb-2">
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2 mb-2">
                                        <div class="text-center">
                                            <div class="fw-bold text-primary"><?php echo e($user->reports_count ?? 0); ?></div>
                                            <small class="text-muted">Reports</small>
                                        </div>
                                        <div class="text-center">
                                            <div class="fw-bold text-success"><?php echo e($user->created_at->diffForHumans()); ?></div>
                                            <small class="text-muted">Member since</small>
                                        </div>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-outline-primary btn-sm" onclick="viewUser(<?php echo e($user->id); ?>)" data-bs-toggle="tooltip" title="View Profile">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="viewReports(<?php echo e($user->id); ?>)" data-bs-toggle="tooltip" title="View Reports">
                                            <i class="fas fa-file-alt"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-sm" onclick="sendMessage(<?php echo e($user->id); ?>)" data-bs-toggle="tooltip" title="Send Message">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="p-3 d-flex justify-content-center">
                        <?php echo e($users->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No users found</h5>
                        <p class="text-muted">Users will appear here once they register through the API.</p>
                        <a href="/test" class="btn btn-primary btn-lg">
                            <i class="fas fa-flask me-2"></i>Test Registration
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4" data-aos="fade-left" data-aos-delay="200">
        <!-- User Activity Chart -->
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color: var(--success-color); border-bottom: 1px solid var(--gray-200);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-chart-bar me-2"></i>User Activity</h6>
            </div>
            <div class="card-body">
                <canvas id="userActivityChart" height="200"></canvas>
            </div>
        </div>

        <!-- Top Contributors -->
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color: var(--danger-color); border-bottom: 1px solid var(--gray-200);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-trophy me-2"></i>Top Contributors</h6>
            </div>
            <div class="card-body p-3">
                <?php
                    $topUsers = $users->sortByDesc('reports_count')->take(5);
                ?>
                <?php $__currentLoopData = $topUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $topUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center mb-3 p-2" style="background-color: var(--gray-100); border: 1px solid var(--gray-200);">
                    <div class="me-3">
                        <div class="badge bg-<?php echo e($index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'info')); ?>" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 1px solid transparent;">
                            <?php echo e($index + 1); ?>

                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold"><?php echo e($topUser->name); ?></div>
                        <small class="text-muted"><?php echo e($topUser->reports_count ?? 0); ?> reports</small>
                    </div>
                    <?php if($index === 0): ?>
                        <i class="fas fa-crown text-warning"></i>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header text-white" style="background-color: var(--primary-color); border-bottom: 1px solid var(--gray-200);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body p-4">
                <button class="btn btn-primary w-100 mb-3 btn-lg" onclick="refreshUsers()">
                    <i class="fas fa-sync me-2"></i>Refresh Users
                </button>
                <div class="row g-2">
                    <div class="col-6">
                        <button class="btn btn-outline-success w-100" onclick="exportUsers()">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-info w-100" onclick="sendBulkMessage()">
                            <i class="fas fa-bullhorn me-1"></i>Notify
                        </button>
                    </div>
                </div>
                <hr class="my-3">
                <a href="/test" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-flask me-2"></i>Test Registration
                </a>
                <a href="/api/users" target="_blank" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-code me-2"></i>View API
                </a>
            </div>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: var(--primary-color); border-bottom: 1px solid var(--gray-200);">
                <h5 class="modal-title fw-bold"><i class="fas fa-user me-2"></i>User Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="userDetails">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading user details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.user-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.user-card:hover {
    background-color: var(--gray-50);
    border-left-color: var(--primary-color);
    transform: translateX(2px);
}

.users-container {
    max-height: 600px;
    overflow-y: auto;
}

.users-container::-webkit-scrollbar {
    width: 6px;
}

.users-container::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.users-container::-webkit-scrollbar-thumb {
    background: var(--primary-color);
}

.user-avatar {
    position: relative;
}

.user-avatar::after {
    content: '';
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 12px;
    height: 12px;
    background: var(--success-color);
    border: 2px solid white;
}
</style>

<script>
function viewUser(userId) {
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
    
    // Simulate loading user details
    setTimeout(() => {
        document.getElementById('userDetails').innerHTML = `
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="avatar text-white mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; background-color: var(--primary-color); border: 2px solid var(--primary-color);">
                        <span class="fw-bold fs-2">U</span>
                    </div>
                    <h5 class="fw-bold">User #${userId}</h5>
                    <p class="text-muted">Active Member</p>
                </div>
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="text-muted small">Email</label>
                            <p class="fw-semibold">user${userId}@example.com</p>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small">Phone</label>
                            <p class="fw-semibold">+1 (555) 123-4567</p>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small">Total Reports</label>
                            <p class="fw-semibold text-primary">12 Reports</p>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small">Member Since</label>
                            <p class="fw-semibold">Jan 15, 2024</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

function viewReports(userId) {
    window.location.href = `/reports?user_id=${userId}`;
}

function sendMessage(userId) {
    alert(`Send message to user #${userId}`);
}

function refreshUsers() {
    location.reload();
}

function exportUsers() {
    alert('Export users functionality would be implemented here');
}

function sendBulkMessage() {
    alert('Bulk message functionality would be implemented here');
}

// Initialize user activity chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('userActivityChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New Users',
                data: [12, 19, 8, 15, 22, 18],
                borderColor: 'var(--primary-color)',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Smart Waste App\laravel_backend\resources\views/users.blade.php ENDPATH**/ ?>