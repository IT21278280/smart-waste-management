@extends('layouts.app')

@section('title', 'Users - Smart Waste Management')
@section('page-title', 'User Management')
@section('page-description', 'Manage registered users and view their activity statistics')

@section('content')
<!-- User Statistics Cards -->
<div class="row mb-4" data-aos="fade-down">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background-color: var(--primary-color); color: white; border: 2px solid var(--primary-color);">
                <i class="fas fa-users fa-2x mb-3"></i>
                <h3 class="fw-bold mb-1">{{ $users->total() }}</h3>
                <p class="mb-0 opacity-75">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background-color: var(--success-color); color: white; border: 2px solid var(--success-color);">
                <i class="fas fa-user-plus fa-2x mb-3"></i>
                <h3 class="fw-bold mb-1">{{ $users->where('created_at', '>=', now()->subDays(7))->count() }}</h3>
                <p class="mb-0 opacity-75">New This Week</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background-color: var(--danger-color); color: white; border: 2px solid var(--danger-color);">
                <i class="fas fa-file-alt fa-2x mb-3"></i>
                <h3 class="fw-bold mb-1">{{ $users->sum('reports_count') }}</h3>
                <p class="mb-0 opacity-75">Total Reports</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background-color: var(--warning-color); color: white; border: 2px solid var(--warning-color);">
                <i class="fas fa-chart-line fa-2x mb-3"></i>
                <h3 class="fw-bold mb-1">{{ number_format($users->avg('reports_count') ?? 0, 1) }}</h3>
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
                <span class="badge bg-white px-3 py-2 fw-semibold" style="color: var(--primary-color) !important; border: 1px solid var(--primary-color);">{{ $users->total() }} Total</span>
            </div>
            <div class="card-body p-0">
                @if($users->count() > 0)
                    <div class="users-container">
                        @foreach($users as $index => $user)
                        <div class="user-card p-4 border-bottom" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3">
                                            <div class="avatar text-white d-flex align-items-center justify-content-center position-relative" style="width: 60px; height: 60px; background-color: var(--primary-color); border: 2px solid var(--primary-color);">
                                                <span class="fw-bold fs-2">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-1">
                                                <h6 class="mb-0 fw-bold text-primary">{{ $user->name }}</h6>
                                                <span class="mx-2 text-muted">•</span>
                                                <small class="text-muted">#{{ $user->id }}</small>
                                            </div>
                                            <div class="mb-2">
                                                <i class="fas fa-envelope me-1 text-muted"></i>
                                                <span class="text-muted">{{ $user->email }}</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                @if($user->phone)
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-phone me-1 text-muted"></i>
                                                        <small class="text-muted">{{ $user->phone }}</small>
                                                    </div>
                                                @endif
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar me-1 text-muted"></i>
                                                    <small class="text-muted">Joined {{ $user->created_at->format('M d, Y') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="mb-2">
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success mb-1">Verified</span>
                                        @else
                                            <span class="badge bg-warning mb-1">Unverified</span>
                                        @endif
                                        @if($user->is_admin)
                                            <span class="badge bg-primary mb-1">Admin</span>
                                        @endif
                                    </div>
                                    <div class="btn-group" role="group">
                                        <input type="checkbox" class="form-check-input me-2 user-checkbox" value="{{ $user->id }}" onchange="toggleBulkActions()">
                                        <button class="btn btn-outline-primary btn-sm" onclick="viewUser({{ $user->id }})" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-sm" onclick="editUser({{ $user->id }})" data-bs-toggle="tooltip" title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm" onclick="toggleUserStatus({{ $user->id }})" data-bs-toggle="tooltip" title="Toggle Status">
                                            <i class="fas fa-{{ $user->email_verified_at ? 'user-slash' : 'user-check' }}"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteUser({{ $user->id }})" data-bs-toggle="tooltip" title="Delete User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="p-3 d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No users found</h5>
                        <p class="text-muted">Users will appear here once they register through the API.</p>
                        <a href="/test" class="btn btn-primary btn-lg">
                            <i class="fas fa-flask me-2"></i>Test Registration
                        </a>
                    </div>
                @endif
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
                @php
                    $topUsers = $users->sortByDesc('reports_count')->take(5);
                @endphp
                @foreach($topUsers as $index => $topUser)
                <div class="d-flex align-items-center mb-3 p-2" style="background-color: var(--gray-100); border: 1px solid var(--gray-200);">
                    <div class="me-3">
                        <div class="badge bg-{{ $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'info') }}" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 1px solid transparent;">
                            {{ $index + 1 }}
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $topUser->name }}</div>
                        <small class="text-muted">{{ $topUser->reports_count ?? 0 }} reports</small>
                    </div>
                    @if($index === 0)
                        <i class="fas fa-crown text-warning"></i>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header text-white" style="background-color: var(--primary-color); border-bottom: 1px solid var(--gray-200);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body p-4">
                <button class="btn btn-primary w-100 mb-3" onclick="createUser()">
                    <i class="fas fa-plus me-2"></i>Create New User
                </button>
                <button class="btn btn-outline-primary w-100 mb-3" onclick="refreshUsers()">
                    <i class="fas fa-sync me-2"></i>Refresh Users
                </button>
                <button class="btn btn-outline-success w-100 mb-3" onclick="exportUsers()">
                    <i class="fas fa-download me-2"></i>Export CSV
                </button>
                <button class="btn btn-outline-danger w-100" onclick="bulkUserAction()" id="bulkUserBtn" style="display: none;">
                    <i class="fas fa-users-cog me-2"></i>Bulk Actions
                </button>
                <hr class="my-3">
                <div class="row g-2">
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
function createUser() {
    window.location.href = '/users/create';
}

function editUser(userId) {
    window.location.href = `/users/${userId}/edit`;
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch(`/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error deleting user');
            }
        });
    }
}

function toggleUserStatus(userId) {
    const action = confirm('Activate or deactivate this user?') ? 'activate' : 'deactivate';
    
    fetch(`/users/${userId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: action })
    }).then(response => {
        if (response.ok) {
            location.reload();
        } else {
            alert('Error updating user status');
        }
    });
}

function toggleBulkActions() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkBtn = document.getElementById('bulkUserBtn');
    bulkBtn.style.display = checkboxes.length > 0 ? 'block' : 'none';
}

function bulkUserAction() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const userIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (userIds.length === 0) {
        alert('Please select users first');
        return;
    }
    
    const action = prompt('Enter action: activate, deactivate, or delete');
    if (!action || !['activate', 'deactivate', 'delete'].includes(action)) {
        alert('Invalid action');
        return;
    }
    
    if (confirm(`Are you sure you want to ${action} ${userIds.length} users?`)) {
        fetch('/users/bulk-action', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ user_ids: userIds, action: action })
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error performing bulk action');
            }
        });
    }
}

function exportUsers() {
    window.location.href = '/users/export/csv';
}

function refreshUsers() {
    location.reload();
}

function viewUser(userId) {
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
    
    fetch(`/users/${userId}`)
        .then(response => response.json())
        .then(data => {
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
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js not loaded yet, retrying...');
        setTimeout(() => {
            if (typeof Chart !== 'undefined') {
                initializeChart();
            } else {
                console.error('Chart.js failed to load');
            }
        }, 1000);
        return;
    }
    initializeChart();
});

function initializeChart() {
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
}

</script>
@endsection
