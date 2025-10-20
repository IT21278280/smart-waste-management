"""
Dataset Organizer for Smart Waste Management
Organizes various waste datasets into our 5 categories:
Organic, Plastic, Metal, Glass, Hazardous
"""

import os
import shutil
import random
from pathlib import Path
from PIL import Image
import json
from collections import defaultdict
from tqdm import tqdm

class DatasetOrganizer:
    def __init__(self, source_dir="datasets", output_dir="data"):
        self.source_dir = Path(source_dir)
        self.output_dir = Path(output_dir)
        
        # Our 5 target categories
        self.target_categories = ['Organic', 'Plastic', 'Metal', 'Glass', 'Hazardous']
        
        # Comprehensive mapping from source categories to target categories
        self.category_mapping = {
            # TrashNet categories
            'cardboard': 'Organic',
            'paper': 'Organic',
            'trash': 'Organic',
            'plastic': 'Plastic',
            'metal': 'Metal',
            'glass': 'Glass',
            
            # Common waste categories
            'organic': 'Organic',
            'organic_waste': 'Organic',
            'food': 'Organic',
            'food_waste': 'Organic',
            'compost': 'Organic',
            'biodegradable': 'Organic',
            'green_waste': 'Organic',
            'yard_waste': 'Organic',
            'wood': 'Organic',
            
            # Plastic variations
            'plastic_waste': 'Plastic',
            'recyclable_plastic': 'Plastic',
            'plastic_bottles': 'Plastic',
            'plastic_bags': 'Plastic',
            'pet': 'Plastic',
            'hdpe': 'Plastic',
            'pvc': 'Plastic',
            'ldpe': 'Plastic',
            'pp': 'Plastic',
            'ps': 'Plastic',
            'polystyrene': 'Plastic',
            
            # Metal variations
            'metal_waste': 'Metal',
            'aluminum': 'Metal',
            'aluminium': 'Metal',
            'cans': 'Metal',
            'tin': 'Metal',
            'steel': 'Metal',
            'iron': 'Metal',
            'scrap_metal': 'Metal',
            
            # Glass variations
            'glass_waste': 'Glass',
            'glass_bottles': 'Glass',
            'green_glass': 'Glass',
            'brown_glass': 'Glass',
            'white_glass': 'Glass',
            'clear_glass': 'Glass',
            'broken_glass': 'Glass',
            
            # Hazardous materials
            'hazardous': 'Hazardous',
            'hazardous_waste': 'Hazardous',
            'e_waste': 'Hazardous',
            'e-waste': 'Hazardous',
            'electronic': 'Hazardous',
            'battery': 'Hazardous',
            'batteries': 'Hazardous',
            'medical': 'Hazardous',
            'medical_waste': 'Hazardous',
            'chemical': 'Hazardous',
            'toxic': 'Hazardous',
            'paint': 'Hazardous',
            'oil': 'Hazardous',
            'fluorescent': 'Hazardous',
            
            # Mixed/Recyclable (need intelligent mapping)
            'recyclable': 'Plastic',  # Most common recyclable
            'non_recyclable': 'Organic',  # Usually goes to landfill
            'mixed_waste': 'Organic',
            
            # Textile and others
            'textile': 'Organic',
            'clothes': 'Organic',
            'fabric': 'Organic',
            'shoes': 'Organic',
            'leather': 'Organic',
            
            # Biological
            'biological': 'Organic',
            'bio': 'Organic',
        }
    
    def find_images(self, directory):
        """Find all image files in directory recursively"""
        image_extensions = {'.jpg', '.jpeg', '.png', '.gif', '.bmp', '.tiff', '.webp'}
        images = []
        
        for path in Path(directory).rglob('*'):
            if path.is_file() and path.suffix.lower() in image_extensions:
                images.append(path)
        
        return images
    
    def detect_category(self, file_path):
        """Detect category from file path and parent directories"""
        path_str = str(file_path).lower()
        
        # Check each part of the path for category keywords
        for source_cat, target_cat in self.category_mapping.items():
            if source_cat in path_str:
                return target_cat
        
        # Check parent directory names
        for parent in file_path.parents:
            parent_name = parent.name.lower()
            for source_cat, target_cat in self.category_mapping.items():
                if source_cat in parent_name:
                    return target_cat
        
        return None
    
    def validate_image(self, image_path):
        """Validate if image is valid and can be opened"""
        try:
            with Image.open(image_path) as img:
                # Check if image is valid
                img.verify()
                return True
        except:
            return False
    
    def organize_dataset(self, dataset_name, split_ratios=(0.7, 0.15, 0.15)):
        """
        Organize a specific dataset into train/val/test splits
        
        Args:
            dataset_name: Name of dataset folder in source_dir
            split_ratios: (train, val, test) ratios
        """
        dataset_path = self.source_dir / dataset_name
        
        if not dataset_path.exists():
            print(f"Dataset {dataset_name} not found at {dataset_path}")
            return
        
        print(f"\nOrganizing {dataset_name}...")
        
        # Find all images
        all_images = self.find_images(dataset_path)
        print(f"Found {len(all_images)} total images")
        
        # Categorize images
        categorized = defaultdict(list)
        uncategorized = []
        
        for img_path in tqdm(all_images, desc="Categorizing"):
            if not self.validate_image(img_path):
                continue
                
            category = self.detect_category(img_path)
            if category:
                categorized[category].append(img_path)
            else:
                uncategorized.append(img_path)
        
        # Print statistics
        print("\nCategorization results:")
        for cat in self.target_categories:
            count = len(categorized[cat])
            print(f"  {cat}: {count} images")
        print(f"  Uncategorized: {len(uncategorized)} images")
        
        # Split and copy images
        train_ratio, val_ratio, test_ratio = split_ratios
        
        for category, images in categorized.items():
            if not images:
                continue
                
            # Shuffle images
            random.shuffle(images)
            
            # Calculate split points
            n_total = len(images)
            n_train = int(n_total * train_ratio)
            n_val = int(n_total * val_ratio)
            
            # Split images
            train_images = images[:n_train]
            val_images = images[n_train:n_train + n_val]
            test_images = images[n_train + n_val:]
            
            # Copy to output directories
            for split_name, split_images in [
                ('train', train_images),
                ('val', val_images),
                ('test', test_images)
            ]:
                output_path = self.output_dir / split_name / category
                output_path.mkdir(parents=True, exist_ok=True)
                
                for img_path in split_images:
                    # Create unique filename
                    filename = f"{dataset_name}_{img_path.stem}{img_path.suffix}"
                    dest_path = output_path / filename
                    
                    # Copy image
                    shutil.copy2(img_path, dest_path)
        
        return categorized, uncategorized
    
    def organize_all_datasets(self):
        """Organize all downloaded datasets"""
        print("="*60)
        print("ORGANIZING ALL DATASETS")
        print("="*60)
        
        # List all dataset directories
        if not self.source_dir.exists():
            print(f"Source directory {self.source_dir} not found!")
            print("Please run download_real_datasets.py first")
            return
        
        datasets = [d for d in self.source_dir.iterdir() if d.is_dir()]
        
        if not datasets:
            print("No datasets found in source directory")
            return
        
        print(f"Found {len(datasets)} datasets:")
        for dataset in datasets:
            print(f"  - {dataset.name}")
        
        # Organize each dataset
        all_stats = defaultdict(int)
        
        for dataset in datasets:
            categorized, uncategorized = self.organize_dataset(dataset.name)
            
            # Update statistics
            for cat, images in categorized.items():
                all_stats[cat] += len(images)
        
        # Print final statistics
        print("\n" + "="*60)
        print("FINAL DATASET STATISTICS")
        print("="*60)
        
        total_images = sum(all_stats.values())
        print(f"\nTotal organized images: {total_images}")
        print("\nPer category:")
        for cat in self.target_categories:
            count = all_stats[cat]
            percentage = (count / total_images * 100) if total_images > 0 else 0
            print(f"  {cat}: {count} images ({percentage:.1f}%)")
        
        # Check data directory
        print("\n" + "="*60)
        print("OUTPUT STRUCTURE")
        print("="*60)
        
        for split in ['train', 'val', 'test']:
            split_path = self.output_dir / split
            if split_path.exists():
                print(f"\n{split.upper()}:")
                for cat_dir in split_path.iterdir():
                    if cat_dir.is_dir():
                        count = len(list(cat_dir.glob('*')))
                        print(f"  {cat_dir.name}: {count} images")
        
        return all_stats
    
    def balance_dataset(self, max_per_category=None):
        """
        Balance the dataset by undersampling or oversampling
        
        Args:
            max_per_category: Maximum images per category (None for auto-balance)
        """
        print("\n" + "="*60)
        print("BALANCING DATASET")
        print("="*60)
        
        # Count images per category in training set
        train_dir = self.output_dir / 'train'
        category_counts = {}
        
        for category in self.target_categories:
            cat_dir = train_dir / category
            if cat_dir.exists():
                count = len(list(cat_dir.glob('*')))
                category_counts[category] = count
        
        if not category_counts:
            print("No training data found!")
            return
        
        # Determine target count
        min_count = min(category_counts.values())
        max_count = max(category_counts.values())
        avg_count = sum(category_counts.values()) // len(category_counts)
        
        print(f"Current distribution:")
        print(f"  Min: {min_count}, Max: {max_count}, Avg: {avg_count}")
        
        if max_per_category is None:
            # Use average as target
            target_count = avg_count
        else:
            target_count = min(max_per_category, max_count)
        
        print(f"\nBalancing to {target_count} images per category...")
        
        # Balance each category
        for category in self.target_categories:
            cat_dir = train_dir / category
            if not cat_dir.exists():
                continue
            
            images = list(cat_dir.glob('*'))
            current_count = len(images)
            
            if current_count > target_count:
                # Undersample
                random.shuffle(images)
                to_remove = images[target_count:]
                for img in to_remove:
                    img.unlink()
                print(f"  {category}: Removed {len(to_remove)} images")
                
            elif current_count < target_count:
                # Oversample by duplication
                needed = target_count - current_count
                duplicates = random.choices(images, k=needed)
                
                for i, src_img in enumerate(duplicates):
                    # Create duplicate with new name
                    dest_name = f"{src_img.stem}_dup{i}{src_img.suffix}"
                    dest_path = cat_dir / dest_name
                    shutil.copy2(src_img, dest_path)
                
                print(f"  {category}: Added {needed} duplicates")
            else:
                print(f"  {category}: Already balanced")

def create_augmentation_script():
    """Create a data augmentation script for better training"""
    augmentation_code = '''"""
Data Augmentation for Waste Classification
Applies various augmentations to increase dataset size and diversity
"""

import os
from pathlib import Path
import numpy as np
from PIL import Image, ImageEnhance, ImageFilter
import random
from tqdm import tqdm

def augment_image(image_path, output_dir, num_augmentations=5):
    """Apply random augmentations to an image"""
    
    img = Image.open(image_path)
    base_name = Path(image_path).stem
    ext = Path(image_path).suffix
    
    augmentations = []
    
    for i in range(num_augmentations):
        aug_img = img.copy()
        
        # Random rotation
        if random.random() > 0.5:
            angle = random.randint(-30, 30)
            aug_img = aug_img.rotate(angle, fillcolor=(255, 255, 255))
        
        # Random flip
        if random.random() > 0.5:
            aug_img = aug_img.transpose(Image.FLIP_LEFT_RIGHT)
        
        # Random brightness
        if random.random() > 0.5:
            enhancer = ImageEnhance.Brightness(aug_img)
            factor = random.uniform(0.7, 1.3)
            aug_img = enhancer.enhance(factor)
        
        # Random contrast
        if random.random() > 0.5:
            enhancer = ImageEnhance.Contrast(aug_img)
            factor = random.uniform(0.7, 1.3)
            aug_img = enhancer.enhance(factor)
        
        # Random blur
        if random.random() > 0.3:
            aug_img = aug_img.filter(ImageFilter.GaussianBlur(radius=random.uniform(0, 2)))
        
        # Save augmented image
        output_path = output_dir / f"{base_name}_aug{i}{ext}"
        aug_img.save(output_path)
        augmentations.append(output_path)
    
    return augmentations

def augment_dataset(data_dir="data/train", augmentations_per_image=3):
    """Augment entire training dataset"""
    
    data_path = Path(data_dir)
    
    for category_dir in data_path.iterdir():
        if not category_dir.is_dir():
            continue
        
        print(f"Augmenting {category_dir.name}...")
        images = list(category_dir.glob("*"))
        
        for img_path in tqdm(images):
            if "_aug" in img_path.stem:
                continue  # Skip already augmented images
            
            augment_image(img_path, category_dir, augmentations_per_image)
    
    print("Augmentation complete!")

if __name__ == "__main__":
    augment_dataset()
'''
    
    with open("augment_data.py", "w") as f:
        f.write(augmentation_code)
    
    print("Created augment_data.py for data augmentation")

def main():
    """Main function to organize datasets"""
    print("\n" + "="*60)
    print("WASTE DATASET ORGANIZER")
    print("="*60)
    
    organizer = DatasetOrganizer()
    
    print("\nOptions:")
    print("1. Organize all downloaded datasets")
    print("2. Organize specific dataset")
    print("3. Balance training data")
    print("4. Create augmentation script")
    print("5. Show current statistics")
    
    choice = input("\nYour choice (1-5): ").strip()
    
    if choice == '1':
        organizer.organize_all_datasets()
        
    elif choice == '2':
        # List available datasets
        if organizer.source_dir.exists():
            datasets = [d.name for d in organizer.source_dir.iterdir() if d.is_dir()]
            print("\nAvailable datasets:")
            for i, name in enumerate(datasets, 1):
                print(f"{i}. {name}")
            
            idx = int(input("Select dataset number: ")) - 1
            if 0 <= idx < len(datasets):
                organizer.organize_dataset(datasets[idx])
        else:
            print("No datasets found. Run download_real_datasets.py first")
    
    elif choice == '3':
        organizer.balance_dataset()
    
    elif choice == '4':
        create_augmentation_script()
    
    elif choice == '5':
        # Show current statistics
        data_dir = Path("data")
        if data_dir.exists():
            for split in ['train', 'val', 'test']:
                split_path = data_dir / split
                if split_path.exists():
                    print(f"\n{split.upper()}:")
                    total = 0
                    for cat_dir in split_path.iterdir():
                        if cat_dir.is_dir():
                            count = len(list(cat_dir.glob('*')))
                            print(f"  {cat_dir.name}: {count} images")
                            total += count
                    print(f"  Total: {total} images")
        else:
            print("No organized data found")
    
    print("\n" + "="*60)
    print("NEXT STEPS")
    print("="*60)
    print("\n1. Review organized data in 'data/' folder")
    print("2. Run balance_dataset() if needed")
    print("3. Run augment_data.py for data augmentation")
    print("4. Train model with: python train_model.py")
    print("\nExpected accuracy with real data: 85-95%")

if __name__ == "__main__":
    main()
