import tensorflow as tf
from tensorflow.keras.applications import MobileNetV2
from tensorflow.keras.layers import GlobalAveragePooling2D, Dense, Dropout, Input
from tensorflow.keras.models import Model
import numpy as np
import json
import os

# Set up paths
model_dir = "ml_service/models"
os.makedirs(model_dir, exist_ok=True)

# Define categories
CLASS_NAMES = ["glass", "hazardous", "metal", "organic", "plastic"]

print("Creating compatible model architecture...")

# Create model with explicit Input layer
inputs = Input(shape=(224, 224, 3), name='image_input')

# Load MobileNetV2 base model
base_model = MobileNetV2(
    weights='imagenet',
    include_top=False,
    input_tensor=inputs
)

# Freeze base model layers
base_model.trainable = False

# Add custom layers
x = base_model.output
x = GlobalAveragePooling2D()(x)
x = Dense(128, activation='relu')(x)
x = Dropout(0.5)(x)
outputs = Dense(len(CLASS_NAMES), activation='softmax', name='predictions')(x)

# Create model
model = Model(inputs=inputs, outputs=outputs)

# Compile model
model.compile(
    optimizer='adam',
    loss='categorical_crossentropy',
    metrics=['accuracy']
)

print("Model architecture created successfully!")
print(f"Input shape: {model.input_shape}")
print(f"Output shape: {model.output_shape}")

# Save the model in the new format
model_path = os.path.join(model_dir, "waste_classifier.h5")
print(f"\nSaving model to {model_path}...")

# Save in H5 format
model.save(model_path)
print("Model saved successfully!")

# Also save in Keras format as backup
keras_path = os.path.join(model_dir, "waste_classifier.keras")
model.save(keras_path)
print(f"Also saved in Keras format at {keras_path}")

# Save class indices
class_indices = {name: i for i, name in enumerate(CLASS_NAMES)}
with open(os.path.join(model_dir, "class_indices.json"), "w") as f:
    json.dump(class_indices, f, indent=2)
print("\nClass indices saved!")

# Save model info
model_info = {
    "version": "4.0",
    "framework": f"TensorFlow {tf.__version__}",
    "architecture": "MobileNetV2",
    "categories": CLASS_NAMES,
    "num_categories": len(CLASS_NAMES),
    "input_size": [224, 224, 3],
    "total_parameters": model.count_params(),
    "notes": "Compatible model for current TensorFlow version"
}

with open(os.path.join(model_dir, "model_info.json"), "w") as f:
    json.dump(model_info, f, indent=2)
print("Model info saved!")

print("\n✅ Model creation complete!")
print("The model is now compatible with the current TensorFlow version.")
print("The ML service should be able to load this model without errors.")
