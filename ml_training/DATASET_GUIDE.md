# Complete Guide: ML Model Training with Real Waste Datasets

## Overview
This guide explains how the ML model for waste classification was trained and how to improve it with real datasets.

## Current Model Status
- **Training Data**: Synthetic/dummy images (700 total)
- **Accuracy**: 69% (limited due to synthetic data)
- **Categories**: 5 (Organic, Plastic, Metal, Glass, Hazardous)
- **Architecture**: MobileNetV2 with transfer learning

## Available Real Datasets

### 1. TrashNet Dataset
- **Source**: Stanford University / GitHub
- **Size**: 2,527 images
- **Categories**: 6 (cardboard, glass, metal, paper, plastic, trash)
- **URL**: https://github.com/garythung/trashnet
- **Quality**: High-quality, manually sorted images
- **Best for**: General waste classification

### 2. Waste Classification Data V2
- **Source**: Kaggle
- **Size**: 25,077 images
- **Categories**: 2 main (Organic, Recyclable)
- **URL**: https://www.kaggle.com/datasets/sapal6/waste-classification-data-v2
- **Quality**: Large dataset with diverse images
- **Best for**: Binary classification (organic vs recyclable)

### 3. TACO (Trash Annotations in Context)
- **Source**: TACO Project
- **Size**: 1,500 images with segmentation
- **Categories**: 60 detailed litter categories
- **URL**: http://tacodataset.org/
- **Quality**: Real-world litter images with annotations
- **Best for**: Detailed waste classification and detection

### 4. Garbage Classification Dataset
- **Source**: Kaggle
- **Size**: ~15,000 images
- **Categories**: 12 (including battery, biological, clothes)
- **URL**: https://www.kaggle.com/datasets/asdasdasasdas/garbage-classification
- **Quality**: Diverse waste types
- **Best for**: Multi-class classification

### 5. Waste Images Dataset
- **Source**: Kaggle
- **Size**: 2,467 images
- **Categories**: 3 (Hazardous, Organic, Recyclable)
- **URL**: https://www.kaggle.com/datasets/wangziang/waste-pictures
- **Quality**: Focused on hazardous waste
- **Best for**: Hazardous waste detection

## How to Download and Prepare Datasets

### Step 1: Install Requirements
```bash
cd ml_training
pip install -r dataset_requirements.txt
```

### Step 2: Setup Kaggle API (Optional, for Kaggle datasets)
1. Go to https://www.kaggle.com/account
2. Click "Create New API Token"
3. Save `kaggle.json` to `~/.kaggle/kaggle.json`
4. Set permissions: `chmod 600 ~/.kaggle/kaggle.json` (Linux/Mac)

### Step 3: Download Datasets
```bash
python download_real_datasets.py
```
Choose option:
- `a` - Download all datasets (~46,000 images)
- `1-5` - Download specific dataset
- `q` - Quit

### Step 4: Organize Datasets
```bash
python organize_datasets.py
```
This will:
- Map various categories to our 5 target categories
- Split data into train/val/test (70/15/15)
- Balance classes if needed

### Step 5: Data Augmentation (Optional)
```bash
python augment_data.py
```
This increases dataset size by applying:
- Random rotations (-30° to +30°)
- Horizontal flips
- Brightness adjustments
- Contrast adjustments
- Gaussian blur

### Step 6: Train Model with Real Data
```bash
python train_model.py
```

## Category Mapping Strategy

Our 5 target categories are mapped from various source categories:

### Organic
- Paper, cardboard, food waste, compost
- Biodegradable materials, yard waste
- Textiles, clothes, leather, shoes
- General trash (non-recyclable)

### Plastic
- All plastic types (PET, HDPE, PVC, LDPE, PP, PS)
- Plastic bottles, bags, containers
- Most "recyclable" items (when not specified)

### Metal
- Aluminum, steel, iron, tin
- Cans, scrap metal
- Metal containers and packaging

### Glass
- All glass colors (green, brown, clear)
- Glass bottles and containers
- Broken glass

### Hazardous
- E-waste, batteries, electronics
- Medical waste
- Chemical waste, paint, oil
- Fluorescent bulbs

## Training Process Explained

### 1. Data Preparation (Current - Synthetic)
```python
# Created dummy images with colors representing categories
colors = {
    'Organic': (139, 195, 74),    # Green
    'Plastic': (33, 150, 243),    # Blue
    'Metal': (158, 158, 158),     # Grey
    'Glass': (0, 188, 212),       # Cyan
    'Hazardous': (255, 87, 34)    # Red
}
```

### 2. Model Architecture
```python
# Transfer learning with MobileNetV2
base_model = MobileNetV2(weights='imagenet', include_top=False)
# Custom classification head
x = GlobalAveragePooling2D()(base_model.output)
x = Dense(128, activation='relu')(x)
x = Dropout(0.5)(x)
predictions = Dense(5, activation='softmax')(x)
```

### 3. Training Strategy
- **Phase 1**: Train only the custom head (20 epochs)
- **Phase 2**: Fine-tune top 100 layers (30 epochs)
- **Callbacks**: Early stopping, learning rate reduction, model checkpointing

### 4. Current Results
```
Classification Report:
              precision    recall  f1-score
Organic       0.55      0.85      0.67
Plastic       0.71      0.50      0.59
Metal         0.67      0.40      0.50
Glass         0.64      0.80      0.71
Hazardous     1.00      0.90      0.95
```

## Expected Improvements with Real Data

### With Real Datasets:
- **Accuracy**: 85-95% (vs current 69%)
- **Robustness**: Better generalization to real-world images
- **Confidence**: Higher confidence scores on predictions
- **Edge Cases**: Better handling of mixed/unclear waste

### Dataset Size Recommendations:
- **Minimum**: 500 images per category (2,500 total)
- **Good**: 2,000 images per category (10,000 total)
- **Excellent**: 5,000+ images per category (25,000+ total)

## Tips for Best Results

1. **Use Multiple Datasets**: Combine TrashNet + TACO + others for diversity
2. **Balance Classes**: Ensure equal representation of all categories
3. **Augment Data**: Use augmentation to increase dataset size 3-5x
4. **Clean Data**: Remove corrupted/irrelevant images
5. **Validate Manually**: Check category mappings are correct
6. **Progressive Training**: Start with less data, gradually add more
7. **Cross-Validation**: Use k-fold validation for robust evaluation

## Troubleshooting

### Low Accuracy
- Increase dataset size
- Add more augmentation
- Adjust learning rate
- Train for more epochs

### Class Imbalance
- Use class weights in training
- Oversample minority classes
- Undersample majority classes

### Overfitting
- Add more dropout
- Reduce model complexity
- Increase data augmentation
- Use early stopping

## Production Deployment

Once trained with real data:

1. **Save Best Model**: Keep the model with highest validation accuracy
2. **Version Control**: Tag model versions (e.g., v2.0-trashnet)
3. **Document Performance**: Record accuracy metrics for each version
4. **A/B Testing**: Compare new model against current in production
5. **Monitor Predictions**: Log low-confidence predictions for review

## Resources

- [TrashNet Paper](https://cs229.stanford.edu/proj2016/report/ThungYang-ClassificationOfTrashForRecyclabilityStatus-report.pdf)
- [TACO Paper](https://arxiv.org/abs/2003.06975)
- [Waste Classification Research](https://www.sciencedirect.com/science/article/pii/S0956053X20302354)
- [MobileNetV2 Paper](https://arxiv.org/abs/1801.04381)

## Contact & Support

For issues or questions about the datasets or training process:
1. Check the error logs in `ml_training/logs/`
2. Review the model summary in `model_summary.txt`
3. Inspect training curves in `training_history.png`
