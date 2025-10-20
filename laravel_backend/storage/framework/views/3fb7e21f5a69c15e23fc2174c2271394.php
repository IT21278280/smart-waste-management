<?php $__env->startSection('title', 'ML Service Test - Smart Waste Management'); ?>
<?php $__env->startSection('page-title', 'ML Service Integration Test'); ?>
<?php $__env->startSection('page-description', 'Test AI-powered waste classification with image uploads'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- ML Service Status -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header text-white" style="background-color: var(--success-color); border-bottom: 1px solid var(--gray-200);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-robot me-2"></i>ML Service Status</h6>
            </div>
            <div class="card-body p-4">
                <div id="mlStatus">
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Checking...</span>
                        </div>
                        <p class="mt-2 text-muted">Checking ML service...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Upload Test -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header text-white" style="background-color: var(--primary-color); border-bottom: 1px solid var(--gray-200);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-camera me-2"></i>Waste Classification Test</h6>
            </div>
            <div class="card-body p-4">
                <form id="predictionForm" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label for="imageFile" class="form-label fw-semibold">Upload Waste Image</label>
                        <input type="file" class="form-control" id="imageFile" name="image" accept="image/*" required>
                        <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF (Max: 5MB)</div>
                    </div>
                    
                    <div class="d-flex gap-2 mb-4">
                        <button type="submit" class="btn btn-primary" id="predictBtn">
                            <i class="fas fa-magic me-2"></i>Classify Waste
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearResults()">
                            <i class="fas fa-eraser me-2"></i>Clear
                        </button>
                    </div>
                </form>

                <!-- Image Preview -->
                <div id="imagePreview" class="mb-4" style="display: none;">
                    <h6 class="fw-semibold mb-3">Image Preview:</h6>
                    <img id="previewImg" src="" alt="Preview" class="img-fluid" style="max-height: 200px; border: 2px solid var(--gray-300);">
                </div>

                <!-- Prediction Results -->
                <div id="predictionResults" style="display: none;">
                    <h6 class="fw-semibold mb-3">Classification Results:</h6>
                    <div id="resultsContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Batch Upload Test -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header text-white" style="background-color: var(--warning-color); border-bottom: 1px solid var(--gray-200);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-images me-2"></i>Batch Classification Test</h6>
            </div>
            <div class="card-body p-4">
                <form id="batchForm" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label for="batchFiles" class="form-label fw-semibold">Upload Multiple Images</label>
                        <input type="file" class="form-control" id="batchFiles" name="images[]" accept="image/*" multiple>
                        <div class="form-text">Select up to 10 images for batch processing</div>
                    </div>
                    
                    <button type="submit" class="btn btn-warning" id="batchBtn">
                        <i class="fas fa-layer-group me-2"></i>Batch Classify
                    </button>
                </form>

                <!-- Batch Results -->
                <div id="batchResults" style="display: none;">
                    <h6 class="fw-semibold mb-3 mt-4">Batch Results:</h6>
                    <div id="batchContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.confidence-bar {
    height: 20px;
    background-color: var(--gray-200);
    border: 1px solid var(--gray-300);
    overflow: hidden;
}

.confidence-fill {
    height: 100%;
    transition: width 0.3s ease;
}

.prediction-card {
    border: 2px solid var(--gray-200);
    transition: all 0.3s ease;
}

.prediction-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-2px);
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 8px;
}

.status-online { background-color: var(--success-color); }
.status-offline { background-color: var(--danger-color); }
.status-loading { background-color: var(--warning-color); }
</style>

<script>
// Check ML service status on page load
document.addEventListener('DOMContentLoaded', function() {
    checkMLStatus();
    
    // Setup file preview
    document.getElementById('imageFile').addEventListener('change', previewImage);
});

async function checkMLStatus() {
    try {
        const response = await fetch('/ml/status');
        const data = await response.json();
        
        const statusHtml = `
            <div class="d-flex align-items-center mb-3">
                <span class="status-indicator ${data.available ? 'status-online' : 'status-offline'}"></span>
                <strong>${data.available ? 'Online' : 'Offline'}</strong>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Service Status:</label>
                <p class="mb-0">${data.ml_service.status || 'Unknown'}</p>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Model Status:</label>
                <p class="mb-0">${data.ml_service.model_status || 'Unknown'}</p>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Categories:</label>
                <div class="d-flex flex-wrap gap-1">
                    ${data.categories.map(cat => `<span class="badge bg-primary">${cat}</span>`).join('')}
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Version:</label>
                <p class="mb-0">${data.ml_service.version || 'Unknown'}</p>
            </div>
        `;
        
        document.getElementById('mlStatus').innerHTML = statusHtml;
        
    } catch (error) {
        document.getElementById('mlStatus').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Failed to connect to ML service
            </div>
        `;
    }
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

// Handle single image prediction
document.getElementById('predictionForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('predictBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Classifying...';
    btn.disabled = true;
    
    try {
        const formData = new FormData(this);
        
        // Check if file is selected
        const fileInput = document.getElementById('imageFile');
        if (!fileInput.files.length) {
            showError('Please select an image file first');
            return;
        }
        
        console.log('Sending prediction request...');
        const response = await fetch('/ml/predict', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.success) {
            displayPredictionResults(data.prediction);
        } else {
            showError(data.error || data.errors || 'Prediction failed');
        }
        
    } catch (error) {
        console.error('Prediction error:', error);
        showError('Network error: ' + error.message);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});

function displayPredictionResults(prediction) {
    const confidenceColor = getConfidenceColor(prediction.confidence);
    
    const resultsHtml = `
        <div class="prediction-card p-4 mb-3">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-tag me-2"></i>${prediction.label}
                    </h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confidence:</label>
                        <div class="d-flex align-items-center">
                            <div class="confidence-bar flex-grow-1 me-3">
                                <div class="confidence-fill" style="width: ${prediction.confidence * 100}%; background-color: ${confidenceColor};"></div>
                            </div>
                            <span class="fw-bold">${prediction.confidence_formatted}</span>
                        </div>
                        <small class="text-muted">Level: ${prediction.confidence_level}</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Recommendation:</label>
                        <p class="mb-0 text-muted">${prediction.recommendation}</p>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-semibold">All Predictions:</label>
                    <div class="predictions-list">
                        ${Object.entries(prediction.all_predictions)
                            .sort(([,a], [,b]) => b - a)
                            .map(([category, confidence]) => `
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>${category}</span>
                                    <div class="d-flex align-items-center">
                                        <div class="confidence-bar me-2" style="width: 100px;">
                                            <div class="confidence-fill" style="width: ${confidence * 100}%; background-color: var(--primary-color);"></div>
                                        </div>
                                        <span class="small">${(confidence * 100).toFixed(1)}%</span>
                                    </div>
                                </div>
                            `).join('')}
                    </div>
                </div>
            </div>
            
            ${prediction.is_uncertain ? `
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Low confidence prediction - consider retaking the photo
                </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('resultsContent').innerHTML = resultsHtml;
    document.getElementById('predictionResults').style.display = 'block';
}

function getConfidenceColor(confidence) {
    if (confidence >= 0.8) return 'var(--success-color)';
    if (confidence >= 0.6) return 'var(--primary-color)';
    if (confidence >= 0.4) return 'var(--warning-color)';
    return 'var(--danger-color)';
}

function showError(message) {
    document.getElementById('resultsContent').innerHTML = `
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${message}
        </div>
    `;
    document.getElementById('predictionResults').style.display = 'block';
}

function clearResults() {
    document.getElementById('predictionResults').style.display = 'none';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('batchResults').style.display = 'none';
    document.getElementById('imageFile').value = '';
    document.getElementById('batchFiles').value = '';
}

// Handle batch prediction
document.getElementById('batchForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('batchBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    btn.disabled = true;
    
    try {
        const formData = new FormData(this);
        const response = await fetch('/ml/batch-predict', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayBatchResults(data.results);
        } else {
            showBatchError(data.error || 'Batch prediction failed');
        }
        
    } catch (error) {
        showBatchError('Network error: ' + error.message);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});

function displayBatchResults(results) {
    const resultsHtml = `
        <div class="row">
            ${results.results.map((result, index) => `
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">${result.filename}</h6>
                            ${result.error ? `
                                <div class="text-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    ${result.error}
                                </div>
                            ` : `
                                <div class="mb-2">
                                    <strong class="text-primary">${result.label}</strong>
                                    <span class="badge bg-${result.is_uncertain ? 'warning' : 'success'} ms-2">
                                        ${(result.confidence * 100).toFixed(1)}%
                                    </span>
                                </div>
                                ${result.is_uncertain ? '<small class="text-warning">Low confidence</small>' : ''}
                            `}
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
        
        <div class="mt-3">
            <strong>Summary:</strong> ${results.total_processed} files processed
        </div>
    `;
    
    document.getElementById('batchContent').innerHTML = resultsHtml;
    document.getElementById('batchResults').style.display = 'block';
}

function showBatchError(message) {
    document.getElementById('batchContent').innerHTML = `
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${message}
        </div>
    `;
    document.getElementById('batchResults').style.display = 'block';
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Smart Waste App\laravel_backend\resources\views/ml-test.blade.php ENDPATH**/ ?>