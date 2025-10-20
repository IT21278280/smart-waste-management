<?php $__env->startSection('title', 'Reports - Smart Waste Management'); ?>
<?php $__env->startSection('page-title', 'Waste Reports'); ?>
<?php $__env->startSection('page-description', 'Monitor and manage all waste collection reports with AI-powered insights'); ?>

<?php $__env->startSection('content'); ?>
<!-- Filter Bar -->
<div class="row mb-4" data-aos="fade-down">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status Filter</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="processed">Processed</option>
                            <option value="assigned">Assigned</option>
                            <option value="collected">Collected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Waste Type</label>
                        <select class="form-select" id="wasteTypeFilter">
                            <option value="">All Types</option>
                            <option value="organic">Organic</option>
                            <option value="plastic">Plastic</option>
                            <option value="metal">Metal</option>
                            <option value="glass">Glass</option>
                            <option value="hazardous">Hazardous</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Date Range</label>
                        <input type="date" class="form-control" id="dateFilter">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100" onclick="applyFilters()">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: var(--primary-color); border-bottom: 1px solid var(--gray-200);">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-alt me-2"></i>
                    <h5 class="mb-0 fw-semibold">Waste Reports</h5>
                </div>
                <span class="badge bg-white text-primary px-3 py-2 fw-semibold" style="color: var(--primary-color) !important; border: 1px solid var(--primary-color);"><?php echo e($reports->total()); ?> Total</span>
            </div>
            <div class="card-body p-0">
                <?php if($reports->count() > 0): ?>
                    <div class="reports-container">
                        <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="report-card p-4 border-bottom" data-aos="fade-up" data-aos-delay="<?php echo e($index * 50); ?>">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start">
                                        <div class="report-avatar me-3">
                                            <div class="avatar text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: var(--primary-color); border: 2px solid var(--primary-color);">
                                                <?php echo e(substr($report->user->name ?? 'U', 0, 1)); ?>

                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="mb-0 fw-bold text-primary">#<?php echo e($report->id); ?></h6>
                                                <span class="mx-2 text-muted">•</span>
                                                <span class="text-muted"><?php echo e($report->user->name ?? 'Unknown User'); ?></span>
                                            </div>
                                            <div class="mb-2">
                                                <?php if($report->waste_type): ?>
                                                    <span class="badge bg-<?php echo e($report->waste_type === 'organic' ? 'success' : ($report->waste_type === 'plastic' ? 'info' : ($report->waste_type === 'metal' ? 'secondary' : ($report->waste_type === 'glass' ? 'primary' : 'danger')))); ?> me-2">
                                                        <i class="fas fa-<?php echo e($report->waste_type === 'organic' ? 'leaf' : ($report->waste_type === 'plastic' ? 'bottle-water' : ($report->waste_type === 'metal' ? 'cog' : ($report->waste_type === 'glass' ? 'wine-glass' : 'exclamation-triangle')))); ?> me-1"></i>
                                                        <?php echo e(ucfirst($report->waste_type)); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-dark me-2">
                                                        <i class="fas fa-clock me-1"></i>Pending Classification
                                                    </span>
                                                <?php endif; ?>
                                                <span class="badge bg-<?php echo e($report->status === 'pending' ? 'warning' : ($report->status === 'processing' ? 'info' : ($report->status === 'processed' ? 'success' : 'secondary'))); ?>">
                                                    <?php echo e(ucfirst($report->status)); ?>

                                                </span>
                                            </div>
                                            <div class="text-muted small">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                <?php if($report->address): ?>
                                                    <?php echo e(Str::limit($report->address, 50)); ?>

                                                <?php else: ?>
                                                    Lat: <?php echo e($report->lat); ?>, Lng: <?php echo e($report->lng); ?>

                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="mb-2">
                                        <small class="text-muted d-block"><?php echo e($report->created_at->format('M d, Y')); ?></small>
                                        <small class="text-muted"><?php echo e($report->created_at->format('H:i A')); ?></small>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <input type="checkbox" class="form-check-input me-2 report-checkbox" value="<?php echo e($report->id); ?>" onchange="toggleBulkActions()">
                                        <button class="btn btn-outline-primary btn-sm" onclick="viewReport(<?php echo e($report->id); ?>)" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if($report->image_path): ?>
                                            <a href="<?php echo e(Storage::url($report->image_path)); ?>" target="_blank" class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" title="View Image">
                                                <i class="fas fa-image"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button class="btn btn-outline-success btn-sm" onclick="editReport(<?php echo e($report->id); ?>)" data-bs-toggle="tooltip" title="Edit Report">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm" onclick="updateStatus(<?php echo e($report->id); ?>)" data-bs-toggle="tooltip" title="Update Status">
                                            <i class="fas fa-tasks"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteReport(<?php echo e($report->id); ?>)" data-bs-toggle="tooltip" title="Delete Report">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($reports->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No reports found</h5>
                        <p class="text-muted">Reports will appear here once users start submitting them.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4" data-aos="fade-left" data-aos-delay="200">
        <!-- Report Statistics -->
        <div class="card mb-4 h-auto">
            <div class="card-header text-white" style="background-color: var(--success-color); border-bottom: 1px solid var(--gray-200);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-chart-pie me-2"></i>Report Analytics</h6>
            </div>
            <div class="card-body p-4">
                <div class="stats-item mb-3 p-3" style="background-color: var(--gray-100); border: 1px solid var(--gray-200);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-clock text-warning fa-lg"></i>
                            <span class="ms-2 fw-semibold">Pending</span>
                        </div>
                        <span class="badge bg-warning px-3 py-2"><?php echo e($reports->where('status', 'pending')->count()); ?></span>
                    </div>
                </div>
                <div class="stats-item mb-3 p-3" style="background-color: var(--gray-100); border: 1px solid var(--gray-200);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-cogs text-info fa-lg"></i>
                            <span class="ms-2 fw-semibold">Processing</span>
                        </div>
                        <span class="badge bg-info px-3 py-2"><?php echo e($reports->where('status', 'processing')->count()); ?></span>
                    </div>
                </div>
                <div class="stats-item mb-3 p-3" style="background-color: var(--gray-100); border: 1px solid var(--gray-200);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                            <span class="ms-2 fw-semibold">Processed</span>
                        </div>
                        <span class="badge bg-success px-3 py-2"><?php echo e($reports->where('status', 'processed')->count()); ?></span>
                    </div>
                </div>
                <div class="stats-item p-3" style="background-color: var(--gray-100); border: 1px solid var(--gray-200);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-truck text-primary fa-lg"></i>
                            <span class="ms-2 fw-semibold">Collected</span>
                        </div>
                        <span class="badge bg-primary px-3 py-2"><?php echo e($reports->where('status', 'collected')->count()); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header text-white" style="background-color: var(--primary-color); border-bottom: 1px solid var(--gray-200);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body p-4">
                <button class="btn btn-primary w-100 mb-3" onclick="createReport()">
                    <i class="fas fa-plus me-2"></i>Create New Report
                </button>
                <button class="btn btn-outline-primary w-100 mb-3" onclick="refreshReports()">
                    <i class="fas fa-sync me-2"></i>Refresh Reports
                </button>
                <button class="btn btn-outline-success w-100 mb-3" onclick="exportReports()">
                    <i class="fas fa-download me-2"></i>Export CSV
                </button>
                <button class="btn btn-outline-danger w-100" onclick="bulkDelete()" id="bulkDeleteBtn" style="display: none;">
                    <i class="fas fa-trash me-2"></i>Delete Selected
                </button>
                <div class="row g-2">
                    <div class="col-6">
                        <a href="/api/reports" target="_blank" class="btn btn-outline-info w-100">
                            <i class="fas fa-code me-1"></i>API
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="/test" class="btn btn-outline-success w-100">
                            <i class="fas fa-flask me-1"></i>Test
                        </a>
                    </div>
                </div>
                <hr class="my-3">
                <button class="btn btn-outline-primary w-100 mb-2" onclick="exportReports()">
                    <i class="fas fa-download me-2"></i>Export Data
                </button>
                <button class="btn btn-outline-secondary w-100" onclick="generateReport()">
                    <i class="fas fa-file-pdf me-2"></i>Generate PDF
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Report Details Modal -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: var(--primary-color); border-bottom: 1px solid var(--gray-200);">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-alt me-2"></i>Report Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="reportDetails">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading report details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: var(--success-color); border-bottom: 1px solid var(--gray-200);">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Update Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Report Status</label>
                        <select class="form-select" id="newStatus" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="processed">Processed</option>
                            <option value="assigned">Assigned</option>
                            <option value="collected">Collected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes (Optional)</label>
                        <textarea class="form-control" id="statusNotes" rows="3" placeholder="Add any notes about this status change..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveStatus()">
                    <i class="fas fa-save me-2"></i>Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.report-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.report-card:hover {
    background-color: #f8f9fa;
    border-left-color: #667eea;
    transform: translateX(5px);
}

.stats-item {
    transition: transform 0.3s ease;
}

.stats-item:hover {
    transform: translateY(-2px);
}

.reports-container {
    max-height: 600px;
    overflow-y: auto;
}

.reports-container::-webkit-scrollbar {
    width: 6px;
}

.reports-container::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.reports-container::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 3px;
}

.btn[data-loading="true"]:active {
    position: relative;
}

.btn[data-loading="true"]:active::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    margin: auto;
    border: 2px solid transparent;
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
let currentReportId = null;

function viewReport(reportId) {
    currentReportId = reportId;
    
    // Show modal with loading state
    const modal = new bootstrap.Modal(document.getElementById('reportModal'));
    modal.show();
    
    // Load report details via AJAX
    fetch(`/api/reports/${reportId}`, {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const wasteTypeColors = {
            'organic': 'success',
            'plastic': 'info', 
            'metal': 'secondary',
            'glass': 'primary',
            'hazardous': 'danger'
        };
        
        const statusColors = {
            'pending': 'warning',
            'processing': 'info',
            'processed': 'success',
            'assigned': 'primary',
            'collected': 'secondary'
        };
        
        document.getElementById('reportDetails').innerHTML = `
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold text-primary">Report Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Report ID</label>
                                        <p class="fw-bold text-primary mb-2">#${data.id}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Reported By</label>
                                        <p class="fw-semibold mb-2">${data.user?.name || 'Unknown User'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Status</label>
                                        <p><span class="badge bg-${statusColors[data.status] || 'secondary'} px-3 py-2">${data.status?.charAt(0).toUpperCase() + data.status?.slice(1)}</span></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Date Reported</label>
                                        <p class="mb-2">${new Date(data.created_at).toLocaleDateString('en-US', { 
                                            year: 'numeric', 
                                            month: 'long', 
                                            day: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        })}</p>
                                    </div>
                                </div>
                                ${data.waste_type ? `
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">Waste Type</label>
                                        <p><span class="badge bg-${wasteTypeColors[data.waste_type] || 'secondary'} px-3 py-2">${data.waste_type?.charAt(0).toUpperCase() + data.waste_type?.slice(1)}</span></p>
                                    </div>
                                </div>
                                ` : ''}
                                ${data.confidence ? `
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="text-muted small">AI Confidence</label>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <div class="progress-bar bg-${data.confidence > 0.8 ? 'success' : data.confidence > 0.6 ? 'warning' : 'danger'}" 
                                                     style="width: ${(data.confidence * 100)}%"></div>
                                            </div>
                                            <span class="small fw-bold">${(data.confidence * 100).toFixed(1)}%</span>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                                <div class="col-12">
                                    <div class="info-item">
                                        <label class="text-muted small">Description</label>
                                        <p class="mb-0">${data.description || 'No description provided'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold text-primary">Location & Media</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted small">Address</label>
                                <p class="mb-2">${data.address || 'Address not available'}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Coordinates</label>
                                <p class="mb-2 font-monospace small">${data.lat}, ${data.lng}</p>
                            </div>
                            ${data.image_path ? `
                            <div class="mb-3">
                                <label class="text-muted small">Uploaded Image</label>
                                <div class="mt-2">
                                    <img src="/storage/${data.image_path}" class="img-fluid rounded shadow-sm" alt="Report Image" style="max-height: 200px; width: 100%; object-fit: cover;">
                                </div>
                            </div>
                            ` : '<p class="text-muted text-center py-4"><i class="fas fa-image fa-2x mb-2 d-block"></i>No image available</p>'}
                        </div>
                    </div>
                </div>
            </div>
        `;
    })
    .catch(error => {
        console.error('Error loading report details:', error);
        document.getElementById('reportDetails').innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                <h6>Failed to load report details</h6>
                <p class="mb-0">Please try again later.</p>
            </div>
        `;
    });
}

function updateStatus(reportId) {
    currentReportId = reportId;
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

function saveStatus() {
    const newStatus = document.getElementById('newStatus').value;
    const notes = document.getElementById('statusNotes').value;
    
    if (!newStatus) {
        alert('Please select a status');
        return;
    }
    
    // Here you would typically make an API call to update the status
    // For now, we'll just show a success message and reload
    alert(`Status updated to: ${newStatus}`);
    bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
    refreshReports();
}

function refreshReports() {
    const btn = event?.target;
    if (btn) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...';
        btn.disabled = true;
    }
    
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const wasteType = document.getElementById('wasteTypeFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    let url = new URL(window.location);
    
    if (status) url.searchParams.set('status', status);
    else url.searchParams.delete('status');
    
    if (wasteType) url.searchParams.set('waste_type', wasteType);
    else url.searchParams.delete('waste_type');
    
    if (date) url.searchParams.set('date', date);
    else url.searchParams.delete('date');
    
    window.location = url;
}

function createReport() {
    window.location.href = '/reports/create';
}

function editReport(reportId) {
    window.location.href = `/reports/${reportId}/edit`;
}

function deleteReport(reportId) {
    if (confirm('Are you sure you want to delete this report?')) {
        fetch(`/reports/${reportId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error deleting report');
            }
        });
    }
}

function toggleBulkActions() {
    const checkboxes = document.querySelectorAll('.report-checkbox:checked');
    const bulkBtn = document.getElementById('bulkDeleteBtn');
    bulkBtn.style.display = checkboxes.length > 0 ? 'block' : 'none';
}

function bulkDelete() {
    const checkboxes = document.querySelectorAll('.report-checkbox:checked');
    const reportIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (reportIds.length === 0) {
        alert('Please select reports to delete');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${reportIds.length} reports?`)) {
        fetch('/reports/bulk-delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ report_ids: reportIds })
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error deleting reports');
            }
        });
    }
}

function exportReports() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '/reports/export/csv?' + params.toString();
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Smart Waste App\laravel_backend\resources\views/reports.blade.php ENDPATH**/ ?>