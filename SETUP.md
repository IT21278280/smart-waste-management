# Smart Waste App - Setup Guide

Quick setup instructions for development and testing.

## Prerequisites

- Docker Desktop
- Git
- Code editor (VS Code recommended)

## Quick Start (5 minutes)

### 1. Clone and Start Services

```bash
# Clone the repository
git clone <your-repo-url>
cd Smart-Waste-App

# Start all services with Docker
docker-compose up -d

# Wait for services to start (about 2-3 minutes)
docker-compose ps
```

### 2. Initialize Database

```bash
# Run database migrations
docker-compose exec laravel php artisan migrate

# Create storage symlink
docker-compose exec laravel php artisan storage:link
```

### 3. Test the Services

- **API Health Check**: http://localhost:8000/api/health
- **ML Service**: http://localhost:8001/health
- **API Documentation**: http://localhost:8000/api (if implemented)

### 4. Flutter App Setup

```bash
cd flutter_app

# Install dependencies
flutter pub get

# Run on emulator or web
flutter run
# OR for web development
flutter run -d web-server --web-port 3000
```

## Service Overview

| Service | Port | Purpose |
|---------|------|---------|
| Laravel API | 8000 | Backend REST API |
| ML Service | 8001 | Image classification |
| MySQL | 3306 | Database |
| Redis | 6379 | Caching & sessions |

## Development Workflow

### 1. API Development (Laravel)

```bash
# Access Laravel container
docker-compose exec laravel bash

# Run artisan commands
php artisan make:controller NewController
php artisan migrate
php artisan tinker
```

### 2. ML Service Development

```bash
# Access ML container
docker-compose exec ml_service bash

# Test predictions
curl -X POST -F "file=@test_image.jpg" http://localhost:8001/predict
```

### 3. Database Access

```bash
# Access MySQL
docker-compose exec mysql mysql -u smartwaste -p smart_waste_db

# View tables
SHOW TABLES;
SELECT * FROM reports LIMIT 5;
```

## Testing the Complete Flow

### 1. Register a User

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Login and Get Token

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### 3. Submit a Report

```bash
# Use the token from login response
curl -X POST http://localhost:8000/api/reports \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "image=@path/to/waste_image.jpg" \
  -F "lat=40.7128" \
  -F "lng=-74.0060" \
  -F "description=Plastic bottle found in park"
```

## Common Commands

### Docker Management

```bash
# View logs
docker-compose logs -f [service_name]

# Restart a service
docker-compose restart [service_name]

# Rebuild and restart
docker-compose up -d --build [service_name]

# Stop all services
docker-compose down

# Clean up (removes volumes)
docker-compose down -v
```

### Laravel Commands

```bash
# Clear cache
docker-compose exec laravel php artisan cache:clear

# Generate app key
docker-compose exec laravel php artisan key:generate

# Run seeders
docker-compose exec laravel php artisan db:seed
```

## Troubleshooting

### Services Won't Start

1. Check Docker Desktop is running
2. Ensure ports 8000, 8001, 3306, 6379 are available
3. Check logs: `docker-compose logs`

### Database Connection Issues

```bash
# Check MySQL is ready
docker-compose exec mysql mysqladmin ping -h localhost

# Reset database
docker-compose down -v
docker-compose up -d mysql
# Wait for MySQL to be ready, then start other services
```

### ML Service Issues

```bash
# Check if model file exists
docker-compose exec ml_service ls -la models/

# Test ML service directly
curl http://localhost:8001/health
```

### Flutter Issues

```bash
# Clean and rebuild
flutter clean
flutter pub get
flutter run
```

## File Structure

```
Smart-Waste-App/
├── laravel_backend/          # Laravel API
│   ├── app/Http/Controllers/ # API controllers
│   ├── database/migrations/  # Database schema
│   └── routes/api.php       # API routes
├── ml_service/              # FastAPI ML service
│   ├── main.py             # ML API server
│   └── models/             # Trained models
├── flutter_app/            # Flutter mobile app
│   ├── lib/screens/        # App screens
│   ├── lib/services/       # API services
│   └── lib/models/         # Data models
├── ml_training/            # Model training scripts
└── docker-compose.yml     # Docker configuration
```

## Next Steps

1. **Add Real Data**: Replace dummy ML training data with real waste images
2. **Train Model**: Run the ML training pipeline
3. **Customize UI**: Modify Flutter app design and features
4. **Deploy**: Follow DEPLOYMENT.md for production setup

## Support

- Check logs first: `docker-compose logs`
- Review error messages carefully
- Ensure all services are running: `docker-compose ps`
- Restart problematic services: `docker-compose restart [service]`
