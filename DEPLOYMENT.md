# Smart Waste App - Deployment Guide

This guide provides step-by-step instructions for deploying the Smart Waste App in different environments.

## Prerequisites

- Docker and Docker Compose
- Node.js 18+ (for Flutter web)
- PHP 8.2+ and Composer (for local Laravel development)
- Python 3.11+ (for ML service development)
- MySQL 8.0+

## Quick Start with Docker

### 1. Clone and Setup

```bash
git clone <repository-url>
cd Smart-Waste-App
```

### 2. Environment Configuration

```bash
# Copy environment files
cp laravel_backend/.env.example laravel_backend/.env
cp ml_service/.env.example ml_service/.env

# Update Laravel .env
# Set database credentials to match docker-compose.yml
# Set ML_SERVICE_URL=http://ml_service:8001
```

### 3. Start Services

```bash
# Start all services
docker-compose up -d

# Check service status
docker-compose ps

# View logs
docker-compose logs -f
```

### 4. Initialize Database

```bash
# Run Laravel migrations
docker-compose exec laravel php artisan migrate

# Create storage link
docker-compose exec laravel php artisan storage:link

# Generate app key
docker-compose exec laravel php artisan key:generate
```

## Service URLs

- **Laravel API**: http://localhost:8000
- **ML Service**: http://localhost:8001
- **MySQL**: localhost:3306
- **Redis**: localhost:6379

## Flutter App Setup

### Development

```bash
cd flutter_app

# Install dependencies
flutter pub get

# Run on emulator/device
flutter run

# Run on web (development)
flutter run -d web-server --web-port 3000
```

### Production Build

```bash
# Android APK
flutter build apk --release

# iOS (requires macOS)
flutter build ios --release

# Web
flutter build web --release
```

## ML Model Training

### 1. Prepare Training Data

```bash
cd ml_training

# Install dependencies
pip install -r requirements.txt

# Prepare dataset structure
python data_preparation.py
```

### 2. Add Real Dataset

Replace dummy data with real waste images:

```
ml_training/data/
├── train/
│   ├── Organic/     # Food waste, biodegradable items
│   ├── Plastic/     # Bottles, containers, packaging
│   ├── Metal/       # Cans, foil, metal objects
│   ├── Glass/       # Bottles, jars, glass containers
│   └── Hazardous/   # Batteries, chemicals, e-waste
├── val/
└── test/
```

### 3. Train Model

```bash
# Start training
python train_model.py

# Monitor training progress
# Model will be saved to ../ml_service/models/waste_classifier.h5
```

## Production Deployment

### Option 1: Cloud VPS (Recommended)

1. **Server Setup**
   ```bash
   # Install Docker and Docker Compose
   curl -fsSL https://get.docker.com -o get-docker.sh
   sh get-docker.sh
   
   # Install Docker Compose
   sudo curl -L "https://github.com/docker/compose/releases/download/v2.20.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
   sudo chmod +x /usr/local/bin/docker-compose
   ```

2. **Deploy Application**
   ```bash
   # Clone repository
   git clone <repository-url>
   cd Smart-Waste-App
   
   # Set production environment
   cp laravel_backend/.env.example laravel_backend/.env
   # Update .env with production values
   
   # Start services
   docker-compose -f docker-compose.prod.yml up -d
   ```

3. **SSL Certificate (Let's Encrypt)**
   ```bash
   # Install Certbot
   sudo apt install certbot python3-certbot-nginx
   
   # Get certificate
   sudo certbot --nginx -d yourdomain.com
   ```

### Option 2: Cloud Platforms

#### AWS Deployment

1. **ECS with Fargate**
   - Create ECS cluster
   - Define task definitions for each service
   - Set up Application Load Balancer
   - Configure RDS for MySQL

2. **Elastic Beanstalk**
   - Deploy Laravel backend
   - Use separate EC2 for ML service
   - Configure RDS and ElastiCache

#### Google Cloud Platform

1. **Cloud Run**
   - Deploy each service as Cloud Run service
   - Use Cloud SQL for MySQL
   - Cloud Storage for file uploads

2. **GKE (Kubernetes)**
   - Create Kubernetes manifests
   - Deploy with Helm charts
   - Use Google Cloud SQL and Redis

## Environment Variables

### Laravel Backend (.env)

```env
APP_NAME="Smart Waste API"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=smart_waste_db
DB_USERNAME=smartwaste
DB_PASSWORD=your-secure-password

ML_SERVICE_URL=http://ml_service:8001
ML_SERVICE_API_KEY=your-ml-api-key
```

### ML Service (.env)

```env
PORT=8001
MODEL_PATH=/app/models/waste_classifier.h5
CONFIDENCE_THRESHOLD=0.6
API_KEY=your-ml-api-key
```

### Flutter App

Update `lib/services/api_service.dart`:

```dart
static const String baseUrl = 'https://yourdomain.com/api';
```

## Monitoring and Logging

### Health Checks

- **Laravel**: `GET /api/health`
- **ML Service**: `GET /health`

### Logging

```bash
# View application logs
docker-compose logs -f laravel
docker-compose logs -f ml_service

# Laravel logs
docker-compose exec laravel tail -f storage/logs/laravel.log
```

### Performance Monitoring

Consider integrating:
- **Sentry** for error tracking
- **New Relic** for APM
- **Prometheus + Grafana** for metrics

## Security Considerations

### API Security

1. **Rate Limiting**
   ```php
   // In Laravel routes/api.php
   Route::middleware(['throttle:60,1'])->group(function () {
       // API routes
   });
   ```

2. **CORS Configuration**
   ```php
   // config/cors.php
   'allowed_origins' => ['https://yourdomain.com'],
   ```

3. **API Keys**
   - Use environment variables
   - Rotate keys regularly
   - Implement API key middleware

### File Upload Security

1. **Validation**
   - File type validation
   - File size limits
   - Virus scanning

2. **Storage**
   - Use cloud storage (S3, GCS)
   - Implement signed URLs
   - Regular cleanup of temporary files

## Scaling Considerations

### Horizontal Scaling

1. **Load Balancing**
   - Use nginx or cloud load balancer
   - Session management with Redis

2. **Database**
   - Read replicas for scaling reads
   - Connection pooling
   - Query optimization

3. **ML Service**
   - Multiple ML service instances
   - GPU acceleration for inference
   - Model caching strategies

### Performance Optimization

1. **Caching**
   - Redis for session and cache
   - CDN for static assets
   - API response caching

2. **Database**
   - Index optimization
   - Query optimization
   - Connection pooling

## Troubleshooting

### Common Issues

1. **ML Service Not Responding**
   ```bash
   # Check service status
   docker-compose ps ml_service
   
   # Check logs
   docker-compose logs ml_service
   
   # Restart service
   docker-compose restart ml_service
   ```

2. **Database Connection Issues**
   ```bash
   # Check MySQL status
   docker-compose ps mysql
   
   # Test connection
   docker-compose exec mysql mysql -u smartwaste -p smart_waste_db
   ```

3. **File Upload Issues**
   ```bash
   # Check storage permissions
   docker-compose exec laravel ls -la storage/
   
   # Fix permissions
   docker-compose exec laravel chown -R www-data:www-data storage/
   ```

## Backup and Recovery

### Database Backup

```bash
# Create backup
docker-compose exec mysql mysqldump -u smartwaste -p smart_waste_db > backup.sql

# Restore backup
docker-compose exec -i mysql mysql -u smartwaste -p smart_waste_db < backup.sql
```

### File Backup

```bash
# Backup uploaded files
docker-compose exec laravel tar -czf /tmp/storage-backup.tar.gz storage/app/public/

# Copy to host
docker cp container_name:/tmp/storage-backup.tar.gz ./storage-backup.tar.gz
```

## Support

For issues and questions:
- Check logs first
- Review this deployment guide
- Create GitHub issues for bugs
- Contact support team

---

**Note**: This is a production-ready deployment guide. Always test in a staging environment before deploying to production.
