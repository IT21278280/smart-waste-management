import os
import shutil
import random
from pathlib import Path
import requests
from PIL import Image
import zipfile

def create_directory_structure():
    """Create the required directory structure for training data"""
    
    directories = [
        'data/train/Organic',
        'data/train/Plastic', 
        'data/train/Metal',
        'data/train/Glass',
        'data/train/Hazardous',
        'data/val/Organic',
        'data/val/Plastic',
        'data/val/Metal', 
        'data/val/Glass',
        'data/val/Hazardous',
        'data/test/Organic',
        'data/test/Plastic',
        'data/test/Metal',
        'data/test/Glass', 
        'data/test/Hazardous'
    ]
    
    for directory in directories:
        os.makedirs(directory, exist_ok=True)
        print(f"Created directory: {directory}")

def download_sample_dataset():
    """Download and extract sample waste classification dataset"""
    
    # This is a placeholder function - you would need to implement
    # downloading from actual datasets like TrashNet, TACO, etc.
    
    print("Sample dataset download function")
    print("To use real data, implement downloads from:")
    print("- TrashNet: https://github.com/garythung/trashnet")
    print("- TACO: http://tacodataset.org/")
    print("- Waste Classification Data: https://www.kaggle.com/datasets/...")
    
    # Create some dummy files for demonstration
    create_dummy_dataset()

def create_dummy_dataset():
    """Create dummy dataset for testing purposes"""
    
    from PIL import Image, ImageDraw
    import numpy as np
    
    categories = ['Organic', 'Plastic', 'Metal', 'Glass', 'Hazardous']
    colors = {
        'Organic': (139, 195, 74),    # Green
        'Plastic': (33, 150, 243),    # Blue  
        'Metal': (158, 158, 158),     # Grey
        'Glass': (0, 188, 212),       # Cyan
        'Hazardous': (255, 87, 34)    # Red
    }
    
    # Create images for each split
    splits = {'train': 100, 'val': 20, 'test': 20}
    
    for split, count in splits.items():
        for category in categories:
            for i in range(count):
                # Create a simple colored image with some noise
                img = Image.new('RGB', (224, 224), colors[category])
                draw = ImageDraw.Draw(img)
                
                # Add some random shapes to make images unique
                for _ in range(random.randint(3, 8)):
                    x1, y1 = random.randint(0, 200), random.randint(0, 200)
                    x2, y2 = x1 + random.randint(10, 50), y1 + random.randint(10, 50)
                    color = tuple(random.randint(0, 255) for _ in range(3))
                    draw.rectangle([x1, y1, x2, y2], fill=color)
                
                # Save image
                filename = f"data/{split}/{category}/{category.lower()}_{i:03d}.jpg"
                img.save(filename)
                
        print(f"Created {count} images per category for {split} set")

def split_dataset(source_dir, train_ratio=0.7, val_ratio=0.15, test_ratio=0.15):
    """Split existing dataset into train/val/test sets"""
    
    if not os.path.exists(source_dir):
        print(f"Source directory {source_dir} does not exist")
        return
    
    categories = [d for d in os.listdir(source_dir) 
                 if os.path.isdir(os.path.join(source_dir, d))]
    
    for category in categories:
        category_path = os.path.join(source_dir, category)
        images = [f for f in os.listdir(category_path) 
                 if f.lower().endswith(('.jpg', '.jpeg', '.png'))]
        
        # Shuffle images
        random.shuffle(images)
        
        # Calculate split indices
        total = len(images)
        train_end = int(total * train_ratio)
        val_end = train_end + int(total * val_ratio)
        
        # Split images
        train_images = images[:train_end]
        val_images = images[train_end:val_end]
        test_images = images[val_end:]
        
        # Copy images to respective directories
        for split, image_list in [('train', train_images), 
                                ('val', val_images), 
                                ('test', test_images)]:
            dest_dir = f"data/{split}/{category}"
            os.makedirs(dest_dir, exist_ok=True)
            
            for image in image_list:
                src_path = os.path.join(category_path, image)
                dest_path = os.path.join(dest_dir, image)
                shutil.copy2(src_path, dest_path)
        
        print(f"{category}: {len(train_images)} train, {len(val_images)} val, {len(test_images)} test")

def validate_dataset():
    """Validate the dataset structure and count images"""
    
    categories = ['Organic', 'Plastic', 'Metal', 'Glass', 'Hazardous']
    splits = ['train', 'val', 'test']
    
    print("\nDataset Validation:")
    print("-" * 50)
    
    total_images = 0
    
    for split in splits:
        split_total = 0
        print(f"\n{split.upper()} SET:")
        
        for category in categories:
            path = f"data/{split}/{category}"
            if os.path.exists(path):
                count = len([f for f in os.listdir(path) 
                           if f.lower().endswith(('.jpg', '.jpeg', '.png'))])
                print(f"  {category}: {count} images")
                split_total += count
            else:
                print(f"  {category}: Directory not found")
        
        print(f"  Total: {split_total} images")
        total_images += split_total
    
    print(f"\nGRAND TOTAL: {total_images} images")
    
    # Check for class imbalance
    print("\nClass Distribution Analysis:")
    for category in categories:
        category_total = 0
        for split in splits:
            path = f"data/{split}/{category}"
            if os.path.exists(path):
                count = len([f for f in os.listdir(path) 
                           if f.lower().endswith(('.jpg', '.jpeg', '.png'))])
                category_total += count
        
        percentage = (category_total / total_images) * 100 if total_images > 0 else 0
        print(f"  {category}: {category_total} images ({percentage:.1f}%)")

def main():
    """Main data preparation function"""
    
    print("Smart Waste Dataset Preparation")
    print("=" * 40)
    
    # Create directory structure
    print("\n1. Creating directory structure...")
    create_directory_structure()
    
    # Download or create sample dataset
    print("\n2. Preparing sample dataset...")
    download_sample_dataset()
    
    # Validate dataset
    print("\n3. Validating dataset...")
    validate_dataset()
    
    print("\nDataset preparation completed!")
    print("\nNext steps:")
    print("1. Replace dummy data with real waste images")
    print("2. Ensure balanced class distribution")
    print("3. Run train_model.py to start training")

if __name__ == "__main__":
    # Set random seed for reproducibility
    random.seed(42)
    
    # Run data preparation
    main()
