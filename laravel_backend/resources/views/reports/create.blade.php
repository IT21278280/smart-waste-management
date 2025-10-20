@extends('layouts.app')

@section('title', 'Create Report - Smart Waste Management')
@section('page-title', 'Create New Report')
@section('page-description', 'Add a new waste report to the system')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header text-white" style="background-color: var(--primary-color); border-bottom: 1px solid var(--gray-200);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-plus-circle me-2"></i>Create New Report</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- User Selection -->
                    <div class="mb-4">
                        <label for="user_id" class="form-label fw-semibold">Select User</label>
                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                            <option value="">Choose a user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Describe the waste issue or report details...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="lat" class="form-label fw-semibold">Latitude</label>
                            <input type="number" 
                                   class="form-control @error('lat') is-invalid @enderror" 
                                   id="lat" 
                                   name="lat" 
                                   step="any"
                                   value="{{ old('lat') }}" 
                                   placeholder="e.g., 40.7128"
                                   required>
                            @error('lat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="lng" class="form-label fw-semibold">Longitude</label>
                            <input type="number" 
                                   class="form-control @error('lng') is-invalid @enderror" 
                                   id="lng" 
                                   name="lng" 
                                   step="any"
                                   value="{{ old('lng') }}" 
                                   placeholder="e.g., -74.0060"
                                   required>
                            @error('lng')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <input type="text" 
                               class="form-control @error('address') is-invalid @enderror" 
                               id="address" 
                               name="address" 
                               value="{{ old('address') }}" 
                               placeholder="Enter the address or location description">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Waste Type -->
                    <div class="mb-4">
                        <label for="waste_type" class="form-label fw-semibold">Waste Type</label>
                        <select class="form-select @error('waste_type') is-invalid @enderror" id="waste_type" name="waste_type">
                            <option value="">Select waste type...</option>
                            <option value="organic" {{ old('waste_type') == 'organic' ? 'selected' : '' }}>
                                <i class="fas fa-leaf"></i> Organic
                            </option>
                            <option value="plastic" {{ old('waste_type') == 'plastic' ? 'selected' : '' }}>
                                <i class="fas fa-bottle-water"></i> Plastic
                            </option>
                            <option value="metal" {{ old('waste_type') == 'metal' ? 'selected' : '' }}>
                                <i class="fas fa-can-food"></i> Metal
                            </option>
                            <option value="glass" {{ old('waste_type') == 'glass' ? 'selected' : '' }}>
                                <i class="fas fa-wine-bottle"></i> Glass
                            </option>
                            <option value="hazardous" {{ old('waste_type') == 'hazardous' ? 'selected' : '' }}>
                                <i class="fas fa-exclamation-triangle"></i> Hazardous
                            </option>
                        </select>
                        @error('waste_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-4">
                        <label for="image" class="form-label fw-semibold">Upload Image</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF (Max: 5MB)</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Preview -->
                    <div id="imagePreview" class="mb-4" style="display: none;">
                        <label class="form-label fw-semibold">Image Preview:</label>
                        <div class="border rounded p-3" style="background-color: var(--gray-50);">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                <i class="fas fa-clock"></i> Pending
                            </option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>
                                <i class="fas fa-spinner"></i> In Progress
                            </option>
                            <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>
                                <i class="fas fa-check-circle"></i> Resolved
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Report
                        </button>
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="button" class="btn btn-outline-info" onclick="getCurrentLocation()">
                            <i class="fas fa-map-marker-alt me-2"></i>Use Current Location
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview functionality
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
});

// Get current location
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('lat').value = position.coords.latitude.toFixed(6);
            document.getElementById('lng').value = position.coords.longitude.toFixed(6);
            
            // Optional: Get address from coordinates using reverse geocoding
            reverseGeocode(position.coords.latitude, position.coords.longitude);
        }, function(error) {
            alert('Error getting location: ' + error.message);
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

// Reverse geocoding (optional - requires API key)
function reverseGeocode(lat, lng) {
    // This is a basic example - you might want to use a proper geocoding service
    const address = `Location: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
    document.getElementById('address').value = address;
}
</script>
@endsection
