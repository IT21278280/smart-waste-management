from fastapi import FastAPI, File, UploadFile, HTTPException
from fastapi.middleware.cors import CORSMiddleware
import uvicorn
import numpy as np
from PIL import Image
import io
import tensorflow as tf
import logging
from typing import Dict, Any
import os
from dotenv import load_dotenv

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

# Global model variable
model = None

def load_model():
    """Load the trained model"""
    global model
    model_path = os.getenv("MODEL_PATH", "models/waste_classifier.h5")
    
    try:
        if os.path.exists(model_path):
            # Try to load with compile=False to avoid layer compatibility issues
            try:
                model = tf.keras.models.load_model(model_path, compile=False)
                # Recompile the model
                model.compile(
                    optimizer='adam',
                    loss='categorical_crossentropy',
                    metrics=['accuracy']
                )
                logger.info(f"Model loaded successfully from {model_path}")
            except Exception as load_error:
                logger.warning(f"Could not load saved model: {load_error}")
                # Try loading just the weights
                model = create_model_architecture()
                try:
                    model.load_weights(model_path)
                    logger.info(f"Model weights loaded successfully from {model_path}")
                except:
                    logger.warning("Could not load weights, using new model")
                    model = create_dummy_model()
        else:
            # Create a dummy model for demonstration
            logger.warning(f"Model file not found at {model_path}. Creating dummy model.")
            model = create_dummy_model()
            logger.info("Dummy model created for demonstration")
    except Exception as e:
        logger.error(f"Error loading model: {e}")
        model = create_dummy_model()
        logger.info("Fallback to dummy model")

def create_model_architecture():
    """Create the model architecture matching the trained model"""
    from tensorflow.keras.applications import MobileNetV2
    from tensorflow.keras.layers import GlobalAveragePooling2D, Dense, Dropout, Input
    from tensorflow.keras.models import Model
    
    # Create input layer
    inputs = Input(shape=(224, 224, 3))
    
    # Load base model
    base_model = MobileNetV2(
        weights=None,
        include_top=False,
        input_tensor=inputs
    )
    
    # Add custom layers
    x = GlobalAveragePooling2D()(base_model.output)
    x = Dense(128, activation='relu')(x)
    x = Dropout(0.5)(x)
    predictions = Dense(len(CLASS_NAMES), activation='softmax')(x)
    
    model = Model(inputs=inputs, outputs=predictions)
    return model

def create_dummy_model():
    """Create a dummy model for demonstration purposes"""
    from tensorflow.keras.applications import MobileNetV2
    from tensorflow.keras.layers import GlobalAveragePooling2D, Dense
    from tensorflow.keras.models import Model
    
    base_model = MobileNetV2(
        weights='imagenet',
        include_top=False,
        input_shape=(224, 224, 3)
    )
    
    x = GlobalAveragePooling2D()(base_model.output)
    x = Dense(128, activation='relu')(x)
    predictions = Dense(len(CLASS_NAMES), activation='softmax')(x)
    
    model = Model(inputs=base_model.input, outputs=predictions)
    return model

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

@app.on_event("startup")
async def startup_event():
    """Load model on startup"""
    load_model()

@app.get("/")
async def root():
    """Health check endpoint"""
    return {
        "message": "Smart Waste Classification API",
        "status": "running",
        "model_loaded": model is not None,
        "classes": CLASS_NAMES
    }

@app.get("/health")
async def health_check():
    """Detailed health check"""
    return {
        "status": "healthy",
        "model_status": "loaded" if model is not None else "not_loaded",
        "classes": CLASS_NAMES,
        "version": "1.0.0"
    }

@app.post("/predict")
async def predict_waste_type(file: UploadFile = File(...)) -> Dict[str, Any]:
    """
    Predict waste type from uploaded image
    
    Returns:
        - prediction: Contains label and confidence
        - all_predictions: Confidence scores for all categories
    """
    
    if model is None:
        raise HTTPException(status_code=503, detail="Model not loaded")
    
    # Validate file type (more flexible)
    content_type = file.content_type or "image/jpeg"  # Default if not provided
    if not (content_type.startswith('image/') or content_type == 'application/octet-stream'):
        # Allow application/octet-stream as it's common for blob uploads
        logger.warning(f"Unexpected content type: {content_type}, attempting to process anyway")
    
    try:
        # Read and preprocess image
        image_data = await file.read()
        
        # Validate that it's actually an image by trying to open it
        try:
            test_image = Image.open(io.BytesIO(image_data))
            test_image.verify()
        except Exception as img_error:
            logger.error(f"Invalid image data: {img_error}")
            raise HTTPException(status_code=400, detail="Invalid image format. Please upload a valid image file.")
        
        processed_image = preprocess_image(image_data)
        
        # Make prediction
        predictions = model.predict(processed_image, verbose=0)[0]
        
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
        
        return {
            "prediction": {
                "label": predicted_label,
                "confidence": confidence
            },
            "all_predictions": all_predictions,
            "is_uncertain": confidence < 0.6,
            "message": "Low confidence - consider retaking photo" if confidence < 0.6 else "Prediction successful"
        }
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Prediction error: {e}")
        raise HTTPException(status_code=500, detail=f"Prediction failed: {str(e)}")

@app.post("/batch_predict")
async def batch_predict(files: list[UploadFile] = File(...)) -> Dict[str, Any]:
    """
    Predict waste types for multiple images
    """
    if model is None:
        raise HTTPException(status_code=503, detail="Model not loaded")
    
    if len(files) > 10:  # Limit batch size
        raise HTTPException(status_code=400, detail="Maximum 10 files allowed per batch")
    
    results = []
    
    for i, file in enumerate(files):
        try:
            if not file.content_type.startswith('image/'):
                results.append({
                    "filename": file.filename,
                    "error": "Invalid file type"
                })
                continue
            
            image_data = await file.read()
            processed_image = preprocess_image(image_data)
            predictions = model.predict(processed_image)[0]
            
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
        "main:app",
        host="0.0.0.0",
        port=int(os.getenv("PORT", 8001)),
        reload=True,
        log_level="info"
    )
