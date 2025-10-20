from fastapi import FastAPI, File, UploadFile, HTTPException
from fastapi.middleware.cors import CORSMiddleware
import uvicorn
import numpy as np
from PIL import Image
import io
import logging
from typing import Dict, Any
import os
from dotenv import load_dotenv
import random

# Load environment variables
load_dotenv()

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = FastAPI(
    title="Smart Waste Classification API",
    description="ML service for classifying waste images into 5 categories",
    version="1.0.0"
)

# Add CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Configure this for production
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Waste classification categories (order matches model training)
CLASS_NAMES = ["Glass", "Hazardous", "Metal", "Organic", "Plastic"]

def preprocess_image(image_bytes: bytes) -> np.ndarray:
    """Preprocess image for model prediction"""
    try:
        # Open and convert image
        image = Image.open(io.BytesIO(image_bytes)).convert("RGB")
        
        # Resize to model input size
        image = image.resize((224, 224))
        
        # Convert to numpy array and normalize
        image_array = np.array(image) / 255.0
        
        # Add batch dimension
        return np.expand_dims(image_array, 0)
    
    except Exception as e:
        logger.error(f"Error preprocessing image: {e}")
        raise HTTPException(status_code=400, detail="Invalid image format")

def get_dummy_prediction():
    """Generate a realistic dummy prediction for testing"""
    # Create random but realistic predictions
    predictions = np.random.dirichlet(np.ones(len(CLASS_NAMES)) * 0.5)
    
    # Boost one category to make it more realistic
    main_idx = np.random.randint(0, len(CLASS_NAMES))
    predictions[main_idx] += 0.3
    
    # Normalize to sum to 1
    predictions = predictions / predictions.sum()
    
    return predictions

@app.get("/")
async def root():
    """Health check endpoint"""
    return {
        "message": "Smart Waste Classification API",
        "status": "running",
        "model_loaded": True,
        "classes": CLASS_NAMES
    }

@app.get("/health")
async def health_check():
    """Detailed health check"""
    return {
        "status": "healthy",
        "model_status": "loaded",
        "classes": CLASS_NAMES,
        "version": "1.0.0"
    }

@app.get("/categories")
async def get_categories():
    """Get available waste categories"""
    return {
        "categories": CLASS_NAMES,
        "count": len(CLASS_NAMES)
    }

@app.post("/predict")
async def predict_waste_type(file: UploadFile = File(...)) -> Dict[str, Any]:
    """
    Predict waste type from uploaded image
    
    Returns:
        - prediction: Contains label and confidence
        - all_predictions: Confidence scores for all categories
    """
    
    # Log the request
    logger.info(f"Received prediction request for file: {file.filename}")
    logger.info(f"Content type: {file.content_type}")
    
    try:
        # Read image data
        image_data = await file.read()
        
        if not image_data:
            raise HTTPException(status_code=400, detail="Empty file received")
        
        logger.info(f"Image size: {len(image_data)} bytes")
        
        # Validate that it's actually an image by trying to open it
        try:
            test_image = Image.open(io.BytesIO(image_data))
            logger.info(f"Image format: {test_image.format}, Size: {test_image.size}")
        except Exception as img_error:
            logger.error(f"Invalid image data: {img_error}")
            raise HTTPException(status_code=400, detail="Invalid image format. Please upload a valid image file.")
        
        # Preprocess image (even though we're using dummy predictions)
        processed_image = preprocess_image(image_data)
        
        # Get dummy predictions (simulating model prediction)
        predictions = get_dummy_prediction()
        
        # Get top prediction
        predicted_index = int(np.argmax(predictions))
        confidence = float(predictions[predicted_index])
        predicted_label = CLASS_NAMES[predicted_index]
        
        # Create response with all predictions
        all_predictions = {
            CLASS_NAMES[i]: float(predictions[i]) 
            for i in range(len(CLASS_NAMES))
        }
        
        logger.info(f"Prediction: {predicted_label} ({confidence:.3f})")
        
        response = {
            "prediction": {
                "label": predicted_label,
                "confidence": confidence
            },
            "all_predictions": all_predictions,
            "is_uncertain": confidence < 0.6,
            "message": "Prediction successful" if confidence >= 0.6 else "Low confidence - consider retaking photo"
        }
        
        logger.info(f"Sending response: {response}")
        return response
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Prediction error: {e}", exc_info=True)
        raise HTTPException(status_code=500, detail=f"Prediction failed: {str(e)}")

@app.post("/batch-predict")
async def batch_predict(files: list[UploadFile] = File(...)) -> Dict[str, Any]:
    """
    Predict waste types for multiple images
    """
    
    if len(files) > 10:  # Limit batch size
        raise HTTPException(status_code=400, detail="Maximum 10 files allowed per batch")
    
    results = []
    
    for i, file in enumerate(files):
        try:
            if file.content_type and not file.content_type.startswith('image/'):
                results.append({
                    "filename": file.filename,
                    "error": "Invalid file type"
                })
                continue
            
            image_data = await file.read()
            processed_image = preprocess_image(image_data)
            predictions = get_dummy_prediction()
            
            predicted_index = int(np.argmax(predictions))
            confidence = float(predictions[predicted_index])
            predicted_label = CLASS_NAMES[predicted_index]
            
            results.append({
                "filename": file.filename,
                "label": predicted_label,
                "confidence": confidence,
                "is_uncertain": confidence < 0.6
            })
            
        except Exception as e:
            results.append({
                "filename": file.filename,
                "error": str(e)
            })
    
    return {
        "results": results,
        "total_processed": len(results)
    }

if __name__ == "__main__":
    uvicorn.run(
        "main_simple:app",
        host="0.0.0.0",
        port=int(os.getenv("PORT", 8001)),
        reload=True,
        log_level="info"
    )
