@extends('layouts.app')

@section('title', 'API Testing - Smart Waste Management')
@section('page-title', 'API Testing Interface')
@section('page-description', 'Interactive testing environment for all Smart Waste Management API endpoints')

@section('content')
<!-- API Status Cards -->
<div class="row mb-4" data-aos="fade-down">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background-color: var(--success-color); color: white; border: 2px solid var(--success-color);">
                <i class="fas fa-check-circle fa-2x mb-3"></i>
                <h5 class="fw-bold mb-1">API Status</h5>
                <p class="mb-0 opacity-75" id="apiStatus">Checking...</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background-color: var(--primary-color); color: white; border: 2px solid var(--primary-color);">
                <i class="fas fa-clock fa-2x mb-3"></i>
                <h5 class="fw-bold mb-1">Response Time</h5>
                <p class="mb-0 opacity-75" id="responseTime">-</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background-color: var(--danger-color); color: white; border: 2px solid var(--danger-color);">
                <i class="fas fa-list fa-2x mb-3"></i>
                <h5 class="fw-bold mb-1">Tests Run</h5>
                <p class="mb-0 opacity-75" id="testsRun">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background-color: var(--warning-color); color: white; border: 2px solid var(--warning-color);">
                <i class="fas fa-percentage fa-2x mb-3"></i>
                <h5 class="fw-bold mb-1">Success Rate</h5>
                <p class="mb-0 opacity-75" id="successRate">-</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- API Endpoints Testing -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold"><i class="fas fa-code me-2"></i>API Endpoints</h5>
                    <button class="btn btn-light btn-sm" onclick="runAllTests()">
                        <i class="fas fa-play me-1"></i>Run All Tests
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Health Check -->
                <div class="endpoint-section border-bottom">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1 fw-bold text-success">Health Check</h6>
                                <code class="text-muted">GET /api/health</code>
                            </div>
                            <button class="btn btn-outline-success btn-sm" onclick="testHealthCheck()">
                                <i class="fas fa-play me-1"></i>Test
                            </button>
                        </div>
                        <div class="response-container" id="health-response" style="display: none;">
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0"><code id="health-result"></code></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Registration -->
                <div class="endpoint-section border-bottom">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1 fw-bold text-primary">User Registration</h6>
                                <code class="text-muted">POST /api/register</code>
                            </div>
                            <button class="btn btn-outline-primary btn-sm" onclick="testRegistration()">
                                <i class="fas fa-play me-1"></i>Test
                            </button>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Name</label>
                                <input type="text" class="form-control form-control-sm" id="reg-name" value="Test User">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email</label>
                                <input type="email" class="form-control form-control-sm" id="reg-email" value="test@example.com">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Password</label>
                                <input type="password" class="form-control form-control-sm" id="reg-password" value="password123">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Phone (Optional)</label>
                                <input type="text" class="form-control form-control-sm" id="reg-phone" value="+1234567890">
                            </div>
                        </div>
                        <div class="response-container" id="register-response" style="display: none;">
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0"><code id="register-result"></code></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Login -->
                <div class="endpoint-section border-bottom">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1 fw-bold text-info">User Login</h6>
                                <code class="text-muted">POST /api/login</code>
                            </div>
                            <button class="btn btn-outline-info btn-sm" onclick="testLogin()">
                                <i class="fas fa-play me-1"></i>Test
                            </button>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email</label>
                                <input type="email" class="form-control form-control-sm" id="login-email" value="test@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Password</label>
                                <input type="password" class="form-control form-control-sm" id="login-password" value="password123">
                            </div>
                        </div>
                        <div class="response-container" id="login-response" style="display: none;">
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0"><code id="login-result"></code></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create Report -->
                <div class="endpoint-section border-bottom">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1 fw-bold text-warning">Create Report</h6>
                                <code class="text-muted">POST /api/reports</code>
                            </div>
                            <button class="btn btn-outline-warning btn-sm" onclick="testCreateReport()">
                                <i class="fas fa-play me-1"></i>Test
                            </button>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Latitude</label>
                                <input type="number" class="form-control form-control-sm" id="report-lat" value="40.7128" step="any">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Longitude</label>
                                <input type="number" class="form-control form-control-sm" id="report-lng" value="-74.0060" step="any">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Description</label>
                            <textarea class="form-control form-control-sm" id="report-description" rows="2">Test waste report from API testing interface</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Image (Optional)</label>
                            <input type="file" class="form-control form-control-sm" id="report-image" accept="image/*">
                        </div>
                        <div class="response-container" id="report-response" style="display: none;">
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0"><code id="report-result"></code></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Get Reports -->
                <div class="endpoint-section">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1 fw-bold text-secondary">Get Reports</h6>
                                <code class="text-muted">GET /api/reports</code>
                            </div>
                            <button class="btn btn-outline-secondary btn-sm" onclick="testGetReports()">
                                <i class="fas fa-play me-1"></i>Test
                            </button>
                        </div>
                        <div class="response-container" id="reports-response" style="display: none;">
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0"><code id="reports-result"></code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Results & Tools -->
    <div class="col-lg-4" data-aos="fade-left" data-aos-delay="200">
        <!-- Test Results Summary -->
        <div class="card mb-4">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-chart-bar me-2"></i>Test Results</h6>
            </div>
            <div class="card-body">
                <div id="test-results">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-flask fa-2x mb-2"></i>
                        <p class="mb-0">No tests run yet</p>
                        <small>Click "Test" buttons to start</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Authentication Token -->
        <div class="card mb-4">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-key me-2"></i>Authentication</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Bearer Token</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" id="auth-token" placeholder="Login to get token" readonly>
                        <button class="btn btn-outline-secondary btn-sm" onclick="clearToken()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary btn-sm" onclick="copyToken()">
                        <i class="fas fa-copy me-1"></i>Copy Token
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-success btn-sm" onclick="runAllTests()">
                        <i class="fas fa-play me-2"></i>Run All Tests
                    </button>
                    <button class="btn btn-info btn-sm" onclick="clearAllResults()">
                        <i class="fas fa-broom me-2"></i>Clear Results
                    </button>
                    <button class="btn btn-warning btn-sm" onclick="exportResults()">
                        <i class="fas fa-download me-2"></i>Export Results
                    </button>
                    <hr class="my-2">
                    <a href="/api/documentation" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-book me-2"></i>API Docs
                    </a>
                    <a href="/dashboard" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.endpoint-section {
    transition: all 0.3s ease;
}

.endpoint-section:hover {
    background-color: #f8f9fa;
}

.response-container {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 500px;
    }
}

.test-result-item {
    padding: 8px 12px;
    margin-bottom: 8px;
    border-radius: 6px;
    border-left: 4px solid;
}

.test-success {
    background-color: #d4edda;
    border-left-color: #28a745;
    color: #155724;
}

.test-error {
    background-color: #f8d7da;
    border-left-color: #dc3545;
    color: #721c24;
}

.test-pending {
    background-color: #fff3cd;
    border-left-color: #ffc107;
    color: #856404;
}

pre code {
    font-size: 12px;
    max-height: 200px;
    overflow-y: auto;
}

.btn-sm {
    font-size: 0.8rem;
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}
</style>

<script>
let authToken = '';
let testResults = [];
let testsRun = 0;
let successfulTests = 0;

// Check API status on page load
document.addEventListener('DOMContentLoaded', function() {
    checkApiStatus();
    updateStats();
});

async function checkApiStatus() {
    const startTime = Date.now();
    try {
        const response = await fetch('/api/health');
        const endTime = Date.now();
        const responseTime = endTime - startTime;
        
        if (response.ok) {
            document.getElementById('apiStatus').textContent = 'Online';
            document.getElementById('responseTime').textContent = responseTime + 'ms';
        } else {
            document.getElementById('apiStatus').textContent = 'Error';
            document.getElementById('responseTime').textContent = 'N/A';
        }
    } catch (error) {
        document.getElementById('apiStatus').textContent = 'Offline';
        document.getElementById('responseTime').textContent = 'N/A';
    }
}

async function testHealthCheck() {
    const button = event.target.closest('button');
    setLoading(button, true);
    
    try {
        const response = await fetch('/api/health');
        const data = await response.json();
        
        showResponse('health', response, data);
        addTestResult('Health Check', response.ok, response.status);
    } catch (error) {
        showResponse('health', null, { error: error.message });
        addTestResult('Health Check', false, 'Network Error');
    }
    
    setLoading(button, false);
}

async function testRegistration() {
    const button = event.target.closest('button');
    setLoading(button, true);
    
    const data = {
        name: document.getElementById('reg-name').value,
        email: document.getElementById('reg-email').value,
        password: document.getElementById('reg-password').value,
        password_confirmation: document.getElementById('reg-password').value,
        phone: document.getElementById('reg-phone').value
    };
    
    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.ok && result.data && result.data.access_token) {
            authToken = result.data.access_token;
            document.getElementById('auth-token').value = authToken;
        }
        
        showResponse('register', response, result);
        addTestResult('User Registration', response.ok, response.status);
    } catch (error) {
        showResponse('register', null, { error: error.message });
        addTestResult('User Registration', false, 'Network Error');
    }
    
    setLoading(button, false);
}

async function testLogin() {
    const button = event.target.closest('button');
    setLoading(button, true);
    
    const data = {
        email: document.getElementById('login-email').value,
        password: document.getElementById('login-password').value
    };
    
    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.ok && result.data && result.data.access_token) {
            authToken = result.data.access_token;
            document.getElementById('auth-token').value = authToken;
        }
        
        showResponse('login', response, result);
        addTestResult('User Login', response.ok, response.status);
    } catch (error) {
        showResponse('login', null, { error: error.message });
        addTestResult('User Login', false, 'Network Error');
    }
    
    setLoading(button, false);
}

async function testCreateReport() {
    const button = event.target.closest('button');
    setLoading(button, true);
    
    if (!authToken) {
        alert('Please login first to get authentication token');
        setLoading(button, false);
        return;
    }
    
    const formData = new FormData();
    formData.append('lat', document.getElementById('report-lat').value);
    formData.append('lng', document.getElementById('report-lng').value);
    formData.append('description', document.getElementById('report-description').value);
    
    const imageFile = document.getElementById('report-image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    try {
        const response = await fetch('/api/reports', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        showResponse('report', response, result);
        addTestResult('Create Report', response.ok, response.status);
    } catch (error) {
        showResponse('report', null, { error: error.message });
        addTestResult('Create Report', false, 'Network Error');
    }
    
    setLoading(button, false);
}

async function testGetReports() {
    const button = event.target.closest('button');
    setLoading(button, true);
    
    try {
        const headers = {
            'Accept': 'application/json'
        };
        
        if (authToken) {
            headers['Authorization'] = `Bearer ${authToken}`;
        }
        
        const response = await fetch('/api/reports', {
            method: 'GET',
            headers: headers
        });
        
        const result = await response.json();
        
        showResponse('reports', response, result);
        addTestResult('Get Reports', response.ok, response.status);
    } catch (error) {
        showResponse('reports', null, { error: error.message });
        addTestResult('Get Reports', false, 'Network Error');
    }
    
    setLoading(button, false);
}

function showResponse(endpoint, response, data) {
    const container = document.getElementById(`${endpoint}-response`);
    const resultElement = document.getElementById(`${endpoint}-result`);
    
    const responseData = {
        status: response ? response.status : 'Error',
        statusText: response ? response.statusText : 'Network Error',
        data: data
    };
    
    resultElement.textContent = JSON.stringify(responseData, null, 2);
    container.style.display = 'block';
}

function addTestResult(testName, success, status) {
    testsRun++;
    if (success) successfulTests++;
    
    const result = {
        name: testName,
        success: success,
        status: status,
        timestamp: new Date().toLocaleTimeString()
    };
    
    testResults.unshift(result);
    updateTestResults();
    updateStats();
}

function updateTestResults() {
    const container = document.getElementById('test-results');
    
    if (testResults.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="fas fa-flask fa-2x mb-2"></i>
                <p class="mb-0">No tests run yet</p>
                <small>Click "Test" buttons to start</small>
            </div>
        `;
        return;
    }
    
    const html = testResults.slice(0, 5).map(result => `
        <div class="test-result-item test-${result.success ? 'success' : 'error'}">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>${result.name}</strong>
                    <br>
                    <small>Status: ${result.status}</small>
                </div>
                <div class="text-end">
                    <i class="fas fa-${result.success ? 'check' : 'times'}"></i>
                    <br>
                    <small>${result.timestamp}</small>
                </div>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = html;
}

function updateStats() {
    document.getElementById('testsRun').textContent = testsRun;
    const successRate = testsRun > 0 ? Math.round((successfulTests / testsRun) * 100) : 0;
    document.getElementById('successRate').textContent = successRate + '%';
}

function setLoading(button, loading) {
    if (loading) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Testing...';
    } else {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-play me-1"></i>Test';
    }
}

async function runAllTests() {
    await testHealthCheck();
    await new Promise(resolve => setTimeout(resolve, 500));
    await testRegistration();
    await new Promise(resolve => setTimeout(resolve, 500));
    await testLogin();
    await new Promise(resolve => setTimeout(resolve, 500));
    await testGetReports();
}

function clearAllResults() {
    testResults = [];
    testsRun = 0;
    successfulTests = 0;
    updateTestResults();
    updateStats();
    
    // Hide all response containers
    document.querySelectorAll('.response-container').forEach(container => {
        container.style.display = 'none';
    });
}

function clearToken() {
    authToken = '';
    document.getElementById('auth-token').value = '';
}

function copyToken() {
    const tokenInput = document.getElementById('auth-token');
    if (tokenInput.value) {
        navigator.clipboard.writeText(tokenInput.value).then(() => {
            alert('Token copied to clipboard!');
        });
    } else {
        alert('No token to copy. Please login first.');
    }
}

function exportResults() {
    if (testResults.length === 0) {
        alert('No test results to export');
        return;
    }
    
    const data = {
        timestamp: new Date().toISOString(),
        summary: {
            totalTests: testsRun,
            successfulTests: successfulTests,
            successRate: Math.round((successfulTests / testsRun) * 100)
        },
        results: testResults
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `api-test-results-${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}
</script>
@endsection
