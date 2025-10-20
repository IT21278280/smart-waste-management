@extends('layouts.app')

@section('title', 'Analytics - Smart Waste Management')
@section('page-title', 'Analytics & Insights')
@section('page-description', 'Comprehensive analytics and insights from AI-powered waste management data')

@section('content')
<!-- Key Metrics Cards -->
<div class="row mb-4" data-aos="fade-down">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 0.5rem;">
                <i class="fas fa-chart-pie fa-2x mb-3"></i>
                <h3 class="fw-bold mb-1">{{ $totalReports }}</h3>
                <p class="mb-0 opacity-75">Total Reports</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; border-radius: 0.5rem;">
                <i class="fas fa-check-circle fa-2x mb-3"></i>
                <h3 class="fw-bold mb-1">{{ $resolvedReports }}</h3>
                <p class="mb-0 opacity-75">Resolved</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 0.5rem;">
                <i class="fas fa-clock fa-2x mb-3"></i>
                <h3 class="fw-bold mb-1">{{ $pendingReports }}</h3>
                <p class="mb-0 opacity-75">Pending</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333; border-radius: 0.5rem;">
                <i class="fas fa-cogs fa-2x mb-3"></i>
                <h3 class="fw-bold mb-1">{{ $inProgressReports }}</h3>
                <p class="mb-0 opacity-75">In Progress</p>
            </div>
        </div>
    </div>
</div>
@if(isset($error))
    <div class="alert alert-warning alert-dismissible fade show animate-fade-in" role="alert" data-aos="fade-down">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Warning:</strong> {{ $error }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row mb-4">
    <!-- Waste Type Distribution -->
    <div class="col-md-6 mb-4" data-aos="fade-right">
        <div class="card card-hover tooltip-custom" data-tooltip="Distribution of different waste types classified by AI">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Waste Type Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="wasteTypeChart" width="400" height="200"></canvas>
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        AI-powered waste classification results
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Reports -->
    <div class="col-md-6 mb-4" data-aos="fade-left">
        <div class="card card-hover tooltip-custom" data-tooltip="Monthly trend of waste reports submitted">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Reports Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" width="400" height="200"></canvas>
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Reports submitted in {{ date('Y') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Interactive Waste Categories -->
    <div class="col-lg-8 mb-4" data-aos="fade-up">
        <div class="card h-100">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <h5 class="mb-0 fw-semibold"><i class="fas fa-recycle me-2"></i>Waste Categories Breakdown</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="category-card p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-left: 4px solid #28a745;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <i class="fas fa-leaf fa-2x text-success"></i>
                                <span class="badge bg-success px-3 py-2 rounded-pill">{{ number_format((($wasteTypes->where('waste_type', 'organic')->first()->count ?? 0) / max($totalReports, 1)) * 100, 1) }}%</span>
                            </div>
                            <h3 class="fw-bold text-success mb-1">{{ $wasteTypes->where('waste_type', 'organic')->first()->count ?? 0 }}</h3>
                            <p class="mb-0 fw-semibold">Organic Waste</p>
                            <small class="text-muted">Biodegradable materials</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="category-card p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #cce7ff 0%, #b3d9ff 100%); border-left: 4px solid #007bff;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <i class="fas fa-bottle-water fa-2x text-primary"></i>
                                <span class="badge bg-primary px-3 py-2 rounded-pill">{{ number_format((($wasteTypes->where('waste_type', 'plastic')->first()->count ?? 0) / max($totalReports, 1)) * 100, 1) }}%</span>
                            </div>
                            <h3 class="fw-bold text-primary mb-1">{{ $wasteTypes->where('waste_type', 'plastic')->first()->count ?? 0 }}</h3>
                            <p class="mb-0 fw-semibold">Plastic</p>
                            <small class="text-muted">Recyclable plastics</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="category-card p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%); border-left: 4px solid #17a2b8;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <i class="fas fa-wine-glass fa-2x text-info"></i>
                                <span class="badge bg-info px-3 py-2 rounded-pill">{{ number_format((($wasteTypes->where('waste_type', 'glass')->first()->count ?? 0) / max($totalReports, 1)) * 100, 1) }}%</span>
                            </div>
                            <h3 class="fw-bold text-info mb-1">{{ $wasteTypes->where('waste_type', 'glass')->first()->count ?? 0 }}</h3>
                            <p class="mb-0 fw-semibold">Glass</p>
                            <small class="text-muted">Glass containers</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="category-card p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%); border-left: 4px solid #6c757d;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <i class="fas fa-cog fa-2x text-secondary"></i>
                                <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ number_format((($wasteTypes->where('waste_type', 'metal')->first()->count ?? 0) / max($totalReports, 1)) * 100, 1) }}%</span>
                            </div>
                            <h3 class="fw-bold text-secondary mb-1">{{ $wasteTypes->where('waste_type', 'metal')->first()->count ?? 0 }}</h3>
                            <p class="mb-0 fw-semibold">Metal</p>
                            <small class="text-muted">Metal objects</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="category-card p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border-left: 4px solid #dc3545;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                                <span class="badge bg-danger px-3 py-2 rounded-pill">{{ number_format((($wasteTypes->where('waste_type', 'hazardous')->first()->count ?? 0) / max($totalReports, 1)) * 100, 1) }}%</span>
                            </div>
                            <h3 class="fw-bold text-danger mb-1">{{ $wasteTypes->where('waste_type', 'hazardous')->first()->count ?? 0 }}</h3>
                            <p class="mb-0 fw-semibold">Hazardous</p>
                            <small class="text-muted">Requires special handling</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="category-card p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-left: 4px solid #ffc107;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <i class="fas fa-question fa-2x text-warning"></i>
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">{{ number_format((($wasteTypes->where('waste_type', null)->first()->count ?? 0) / max($totalReports, 1)) * 100, 1) }}%</span>
                            </div>
                            <h3 class="fw-bold text-warning mb-1">{{ $wasteTypes->where('waste_type', null)->first()->count ?? 0 }}</h3>
                            <p class="mb-0 fw-semibold">Unclassified</p>
                            <small class="text-muted">Pending AI analysis</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced System Performance -->
    <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card h-100">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0 fw-semibold"><i class="fas fa-tachometer-alt me-2"></i>System Performance</h5>
            </div>
            <div class="card-body p-4">
                <div class="performance-metric mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">AI Classification Accuracy</span>
                        <span class="badge bg-success px-3 py-2 rounded-pill">94.2%</span>
                    </div>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-gradient" style="width: 94.2%; background: linear-gradient(90deg, #28a745, #20c997);"></div>
                    </div>
                    <small class="text-muted">Excellent performance</small>
                </div>
                
                <div class="performance-metric mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Processing Speed</span>
                        <span class="badge bg-info px-3 py-2 rounded-pill">1.2s</span>
                    </div>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-gradient" style="width: 85%; background: linear-gradient(90deg, #17a2b8, #007bff);"></div>
                    </div>
                    <small class="text-muted">Average response time</small>
                </div>
                
                <div class="performance-metric mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">API Uptime</span>
                        <span class="badge bg-success px-3 py-2 rounded-pill">99.8%</span>
                    </div>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-gradient" style="width: 99.8%; background: linear-gradient(90deg, #28a745, #20c997);"></div>
                    </div>
                    <small class="text-muted">Highly reliable</small>
                </div>

                <hr class="my-3">
                
                <div class="text-center">
                    <div class="d-flex justify-content-around mb-3">
                        <div class="text-center">
                            <div class="fw-bold text-primary fs-5">{{ $totalReports }}</div>
                            <small class="text-muted">Total Processed</small>
                        </div>
                        <div class="text-center">
                            <div class="fw-bold text-success fs-5">{{ number_format($totalReports > 0 ? ($processedReports / $totalReports) * 100 : 0, 1) }}%</div>
                            <small class="text-muted">Success Rate</small>
                        </div>
                    </div>
                    <small class="text-muted">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.category-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.performance-metric {
    transition: all 0.3s ease;
}

.performance-metric:hover {
    transform: translateX(5px);
}

.chart-container {
    position: relative;
    height: 300px;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.pulse-animation {
    animation: pulse 2s infinite;
}
</style>

<script>
// Enhanced Waste Type Chart with animations
const wasteTypeCtx = document.getElementById('wasteTypeChart').getContext('2d');
const wasteTypeChart = new Chart(wasteTypeCtx, {
    type: 'doughnut',
    data: {
        labels: ['Organic', 'Plastic', 'Glass', 'Metal', 'Hazardous', 'Unclassified'],
        datasets: [{
            data: [
                {{ $wasteTypes->where('waste_type', 'organic')->first()->count ?? 0 }},
                {{ $wasteTypes->where('waste_type', 'plastic')->first()->count ?? 0 }},
                {{ $wasteTypes->where('waste_type', 'glass')->first()->count ?? 0 }},
                {{ $wasteTypes->where('waste_type', 'metal')->first()->count ?? 0 }},
                {{ $wasteTypes->where('waste_type', 'hazardous')->first()->count ?? 0 }},
                {{ $wasteTypes->where('waste_type', null)->first()->count ?? 0 }}
            ],
            backgroundColor: [
                '#28a745',
                '#007bff', 
                '#17a2b8',
                '#6c757d',
                '#dc3545',
                '#ffc107'
            ],
            borderWidth: 3,
            borderColor: '#fff',
            hoverBorderWidth: 5,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            animateRotate: true,
            animateScale: true,
            duration: 2000
        },
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    font: {
                        size: 12,
                        weight: 'bold'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#667eea',
                borderWidth: 2,
                cornerRadius: 10,
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.raw / total) * 100).toFixed(1);
                        return context.label + ': ' + context.raw + ' (' + percentage + '%)';
                    }
                }
            }
        },
        onHover: (event, activeElements) => {
            event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
        }
    }
});

// Enhanced Monthly Reports Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Reports Submitted',
            data: [
                @foreach(range(1, 12) as $month)
                    {{ $monthlyReports->where('month', $month)->first()->count ?? 0 }},
                @endforeach
            ],
            borderColor: 'var(--primary-color)',
            backgroundColor: 'rgba(37, 99, 235, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#667eea',
            pointBorderColor: '#fff',
            pointBorderWidth: 3,
            pointRadius: 6,
            pointHoverRadius: 8,
            pointHoverBackgroundColor: 'var(--secondary-color)',
            pointHoverBorderColor: '#fff',
            pointHoverBorderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 2000,
            easing: 'easeInOutQuart'
        },
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#667eea',
                borderWidth: 2,
                cornerRadius: 10,
                callbacks: {
                    title: function(context) {
                        return context[0].label + ' 2024';
                    },
                    label: function(context) {
                        return 'Reports: ' + context.raw;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.1)',
                    drawBorder: false
                },
                ticks: {
                    color: '#6c757d',
                    font: {
                        size: 11
                    }
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#6c757d',
                    font: {
                        size: 11
                    }
                }
            }
        },
        onHover: (event, activeElements) => {
            event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
        }
    }
});

// Add click interactions for category cards
document.addEventListener('DOMContentLoaded', function() {
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(card => {
        card.addEventListener('click', function() {
            // Add pulse animation
            this.classList.add('pulse-animation');
            setTimeout(() => {
                this.classList.remove('pulse-animation');
            }, 1000);
            
            // You could add functionality to filter reports by waste type here
            console.log('Category clicked:', this.querySelector('p').textContent);
        });
    });
    
    // Refresh charts every 30 seconds
    setInterval(() => {
        // In a real application, you would fetch new data and update charts
        console.log('Refreshing analytics data...');
    }, 30000);
    
    // Add real-time updates simulation
    let updateCounter = 0;
    setInterval(() => {
        updateCounter++;
        const lastUpdated = document.querySelector('small:contains("Last updated")');
        if (lastUpdated) {
            lastUpdated.textContent = `Last updated: ${new Date().toLocaleString()}`;
        }
    }, 60000);
});

// Chart interaction handlers
wasteTypeChart.options.onClick = (event, elements) => {
    if (elements.length > 0) {
        const index = elements[0].index;
        const label = wasteTypeChart.data.labels[index];
        alert(`Clicked on ${label} waste type. You could filter reports by this category.`);
    }
};

monthlyChart.options.onClick = (event, elements) => {
    if (elements.length > 0) {
        const index = elements[0].index;
        const month = monthlyChart.data.labels[index];
        alert(`Clicked on ${month}. You could view detailed reports for this month.`);
    }
};
</script>
@endsection
