# Smart Waste App - API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication

The API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header:

```
Authorization: Bearer {token}
```

## Endpoints

### Authentication

#### Register User
```http
POST /register
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "+1234567890"
}
```

**Response:**
```json
{
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "+1234567890",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    },
    "access_token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

#### Login
```http
POST /login
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "access_token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

#### Logout
```http
POST /logout
```
*Requires authentication*

**Response:**
```json
{
  "message": "Logged out successfully"
}
```

#### Get Current User
```http
GET /user
```
*Requires authentication*

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

### Reports

#### Submit Report
```http
POST /reports
```
*Requires authentication*

**Request (multipart/form-data):**
- `image`: Image file (JPEG, PNG, JPG, max 5MB)
- `lat`: Latitude (-90 to 90)
- `lng`: Longitude (-180 to 180)
- `description`: Optional description (max 500 chars)
- `address`: Optional address (max 255 chars)

**Response:**
```json
{
  "message": "Report submitted successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "image_path": "reports/2024/01/01/image.jpg",
    "image_url": "http://localhost:8000/storage/reports/2024/01/01/image.jpg",
    "lat": "40.7128",
    "lng": "-74.0060",
    "predicted_label": "Plastic",
    "confidence": 0.85,
    "confidence_percentage": 85,
    "status": "pending",
    "description": "Plastic bottle found in park",
    "address": "Central Park, New York, NY",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

#### Get Reports
```http
GET /reports
```
*Requires authentication*

**Query Parameters:**
- `status`: Filter by status (pending, assigned, collected, rejected)
- `label`: Filter by waste type (Organic, Plastic, Metal, Glass, Hazardous)
- `recent_days`: Filter by recent days (e.g., 7, 30)
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15)

**Response:**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "image_url": "http://localhost:8000/storage/reports/image.jpg",
      "predicted_label": "Plastic",
      "confidence_percentage": 85,
      "status": "pending",
      "created_at": "2024-01-01T00:00:00.000000Z"
    }
  ],
  "total": 1,
  "per_page": 15,
  "last_page": 1
}
```

#### Get Single Report
```http
GET /reports/{id}
```
*Requires authentication*

**Response:**
```json
{
  "data": {
    "id": 1,
    "user_id": 1,
    "image_url": "http://localhost:8000/storage/reports/image.jpg",
    "lat": "40.7128",
    "lng": "-74.0060",
    "predicted_label": "Plastic",
    "confidence": 0.85,
    "status": "pending",
    "description": "Plastic bottle found in park",
    "address": "Central Park, New York, NY",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

#### Update Report Status (Admin Only)
```http
PUT /reports/{id}/status
```
*Requires authentication and admin privileges*

**Request Body:**
```json
{
  "status": "collected"
}
```

**Response:**
```json
{
  "message": "Report status updated successfully",
  "data": {
    "id": 1,
    "status": "collected",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

### Health Check

#### API Health
```http
GET /health
```

**Response:**
```json
{
  "status": "ok",
  "timestamp": "2024-01-01T00:00:00.000000Z",
  "service": "Smart Waste API"
}
```

## ML Service API

### Base URL
```
http://localhost:8001
```

#### Predict Waste Type
```http
POST /predict
```

**Request (multipart/form-data):**
- `file`: Image file

**Response:**
```json
{
  "label": "Plastic",
  "confidence": 0.85,
  "all_predictions": {
    "Organic": 0.05,
    "Plastic": 0.85,
    "Metal": 0.03,
    "Glass": 0.04,
    "Hazardous": 0.03
  },
  "is_uncertain": false,
  "message": "Prediction successful"
}
```

#### Batch Predict
```http
POST /batch_predict
```

**Request (multipart/form-data):**
- `files`: Multiple image files (max 10)

**Response:**
```json
{
  "results": [
    {
      "filename": "image1.jpg",
      "label": "Plastic",
      "confidence": 0.85,
      "is_uncertain": false
    },
    {
      "filename": "image2.jpg",
      "label": "Organic",
      "confidence": 0.92,
      "is_uncertain": false
    }
  ],
  "total_processed": 2
}
```

#### ML Service Health
```http
GET /health
```

**Response:**
```json
{
  "status": "healthy",
  "model_status": "loaded",
  "classes": ["Organic", "Plastic", "Metal", "Glass", "Hazardous"],
  "version": "1.0.0"
}
```

## Error Responses

### Validation Error (422)
```json
{
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

### Authentication Error (401)
```json
{
  "message": "Unauthenticated"
}
```

### Authorization Error (403)
```json
{
  "message": "Unauthorized"
}
```

### Not Found Error (404)
```json
{
  "message": "Resource not found"
}
```

### Server Error (500)
```json
{
  "message": "Internal server error"
}
```

## Rate Limiting

- **Authentication endpoints**: 5 requests per minute
- **Report submission**: 10 requests per minute
- **General API**: 60 requests per minute

## Waste Categories

| Category | Description | Examples |
|----------|-------------|----------|
| Organic | Biodegradable waste | Food scraps, leaves, paper |
| Plastic | Plastic materials | Bottles, containers, bags |
| Metal | Metal objects | Cans, foil, metal containers |
| Glass | Glass materials | Bottles, jars, glass containers |
| Hazardous | Dangerous waste | Batteries, chemicals, electronics |

## Status Codes

| Status | Description |
|--------|-------------|
| pending | Report submitted, awaiting assignment |
| assigned | Report assigned to collection team |
| collected | Waste has been collected |
| rejected | Report rejected (invalid/duplicate) |

## SDKs and Examples

### cURL Examples

**Register User:**
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Submit Report:**
```bash
curl -X POST http://localhost:8000/api/reports \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "image=@/path/to/image.jpg" \
  -F "lat=40.7128" \
  -F "lng=-74.0060" \
  -F "description=Plastic bottle in park"
```

### JavaScript Example

```javascript
// Register user
const response = await fetch('http://localhost:8000/api/register', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    name: 'John Doe',
    email: 'john@example.com',
    password: 'password123',
    password_confirmation: 'password123'
  })
});

const data = await response.json();
const token = data.data.access_token;

// Submit report
const formData = new FormData();
formData.append('image', imageFile);
formData.append('lat', '40.7128');
formData.append('lng', '-74.0060');
formData.append('description', 'Plastic bottle in park');

const reportResponse = await fetch('http://localhost:8000/api/reports', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`
  },
  body: formData
});
```
