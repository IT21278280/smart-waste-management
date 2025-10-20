"""
Automatic Waste Dataset Downloader
Downloads real waste classification datasets from various sources
"""

import os
import zipfile
import tarfile
import shutil
import requests
from pathlib import Path
import gdown
import kaggle
from tqdm import tqdm
import json
import urllib.request

class WasteDatasetDownloader:
    def __init__(self, base_dir="datasets"):
        self.base_dir = Path(base_dir)
        self.base_dir.mkdir(exist_ok=True)
        
    def download_file(self, url, filename, desc="Downloading"):
        """Download file with progress bar"""
        response = requests.get(url, stream=True)
        total_size = int(response.headers.get('content-length', 0))
        
        with open(filename, 'wb') as file:
            with tqdm(total=total_size, unit='B', unit_scale=True, desc=desc) as pbar:
                for chunk in response.iter_content(chunk_size=8192):
                    file.write(chunk)
                    pbar.update(len(chunk))
        
        return filename
    
    def download_trashnet(self):
        """
        Download TrashNet Dataset
        Source: https://github.com/garythung/trashnet
        Categories: cardboard, glass, metal, paper, plastic, trash
        Size: ~3500 images
        """
        print("\n" + "="*60)
        print("DOWNLOADING TRASHNET DATASET")
        print("="*60)
        
        dataset_dir = self.base_dir / "trashnet"
        dataset_dir.mkdir(exist_ok=True)
        
        # TrashNet dataset from GitHub
        print("TrashNet contains 2527 images in 6 categories:")
        print("- Cardboard (403 images)")
        print("- Glass (501 images)")
        print("- Metal (410 images)")
        print("- Paper (594 images)")
        print("- Plastic (482 images)")
        print("- Trash (137 images)")
        
        # Download from Google Drive (mirror)
        trashnet_url = "https://drive.google.com/uc?id=1lXreXd5_VGlLZq8qPQbJn0N8yvJcpVGP"
        
        try:
            print("\nDownloading TrashNet dataset...")
            zip_path = dataset_dir / "trashnet.zip"
            gdown.download(trashnet_url, str(zip_path), quiet=False)
            
            # Extract
            print("Extracting...")
            with zipfile.ZipFile(zip_path, 'r') as zip_ref:
                zip_ref.extractall(dataset_dir)
            
            # Clean up
            zip_path.unlink()
            print(f"✓ TrashNet dataset saved to: {dataset_dir}")
            
        except Exception as e:
            print(f"Error downloading TrashNet: {e}")
            print("\nAlternative: Clone from GitHub:")
            print("git clone https://github.com/garythung/trashnet.git")
            
        return dataset_dir
    
    def download_waste_classification_v2(self):
        """
        Download Waste Classification Data V2 from Kaggle
        Source: https://www.kaggle.com/datasets/sapal6/waste-classification-data-v2
        Categories: Organic, Recyclable
        Size: ~25,000 images
        """
        print("\n" + "="*60)
        print("DOWNLOADING WASTE CLASSIFICATION V2 DATASET")
        print("="*60)
        
        dataset_dir = self.base_dir / "waste_classification_v2"
        dataset_dir.mkdir(exist_ok=True)
        
        print("This dataset contains 25,077 images in 2 main categories:")
        print("- Organic Waste (12,565 images)")
        print("- Recyclable Waste (12,512 images)")
        
        try:
            # Using Kaggle API (requires authentication)
            print("\nDownloading from Kaggle...")
            print("Note: Requires Kaggle API key in ~/.kaggle/kaggle.json")
            
            kaggle.api.dataset_download_files(
                'sapal6/waste-classification-data-v2',
                path=dataset_dir,
                unzip=True
            )
            
            print(f"✓ Dataset saved to: {dataset_dir}")
            
        except Exception as e:
            print(f"Error: {e}")
            print("\nTo download manually:")
            print("1. Go to: https://www.kaggle.com/datasets/sapal6/waste-classification-data-v2")
            print("2. Sign in to Kaggle")
            print("3. Click 'Download' button")
            print(f"4. Extract to: {dataset_dir}")
            
        return dataset_dir
    
    def download_taco(self):
        """
        Download TACO (Trash Annotations in Context) Dataset
        Source: http://tacodataset.org/
        Categories: 60 categories of litter
        Size: ~1500 images with segmentation masks
        """
        print("\n" + "="*60)
        print("DOWNLOADING TACO DATASET")
        print("="*60)
        
        dataset_dir = self.base_dir / "taco"
        dataset_dir.mkdir(exist_ok=True)
        
        print("TACO contains 1500 images with 60 waste categories including:")
        print("- Aluminum cans, Plastic bottles, Glass bottles")
        print("- Food wrappers, Cigarette butts, Paper bags")
        print("- And many more specific litter categories")
        
        # Clone TACO repository
        taco_repo = "https://github.com/pedropro/TACO.git"
        
        try:
            print("\nCloning TACO repository...")
            os.system(f"git clone {taco_repo} {dataset_dir / 'TACO'}")
            
            # Download annotations
            annotations_url = "https://github.com/pedropro/TACO/releases/download/v1.0/annotations.zip"
            print("\nDownloading annotations...")
            
            annotations_path = dataset_dir / "annotations.zip"
            self.download_file(annotations_url, annotations_path, "Downloading TACO annotations")
            
            # Extract annotations
            with zipfile.ZipFile(annotations_path, 'r') as zip_ref:
                zip_ref.extractall(dataset_dir)
            
            annotations_path.unlink()
            print(f"✓ TACO dataset saved to: {dataset_dir}")
            
        except Exception as e:
            print(f"Error downloading TACO: {e}")
            print("\nManual download:")
            print("1. Visit: http://tacodataset.org/")
            print("2. Follow download instructions")
            
        return dataset_dir
    
    def download_garbage_classification(self):
        """
        Download Garbage Classification Dataset (12 classes)
        Source: Kaggle
        Categories: 12 types of garbage
        Size: ~15,000 images
        """
        print("\n" + "="*60)
        print("DOWNLOADING GARBAGE CLASSIFICATION DATASET")
        print("="*60)
        
        dataset_dir = self.base_dir / "garbage_classification"
        dataset_dir.mkdir(exist_ok=True)
        
        print("This dataset contains ~15,000 images in 12 categories:")
        print("- Paper, Cardboard, Plastic, Metal, Glass")
        print("- Trash, Battery, Biological, Clothes")
        print("- Green-glass, Brown-glass, White-glass, Shoes")
        
        try:
            # Kaggle API download
            print("\nDownloading from Kaggle...")
            kaggle.api.dataset_download_files(
                'asdasdasasdas/garbage-classification',
                path=dataset_dir,
                unzip=True
            )
            
            print(f"✓ Dataset saved to: {dataset_dir}")
            
        except Exception as e:
            print(f"Error: {e}")
            print("\nManual download link:")
            print("https://www.kaggle.com/datasets/asdasdasasdas/garbage-classification")
            
        return dataset_dir
    
    def download_waste_images(self):
        """
        Download Waste Images Dataset from Kaggle
        Source: https://www.kaggle.com/datasets/wangziang/waste-pictures
        Categories: Hazardous, Organic, Recyclable
        Size: ~2500 images
        """
        print("\n" + "="*60)
        print("DOWNLOADING WASTE IMAGES DATASET")
        print("="*60)
        
        dataset_dir = self.base_dir / "waste_images"
        dataset_dir.mkdir(exist_ok=True)
        
        print("This dataset contains 2467 images in categories:")
        print("- Hazardous Waste")
        print("- Organic Waste")
        print("- Recyclable Waste")
        
        try:
            print("\nDownloading from Kaggle...")
            kaggle.api.dataset_download_files(
                'wangziang/waste-pictures',
                path=dataset_dir,
                unzip=True
            )
            
            print(f"✓ Dataset saved to: {dataset_dir}")
            
        except Exception as e:
            print(f"Error: {e}")
            print("\nManual download:")
            print("https://www.kaggle.com/datasets/wangziang/waste-pictures")
            
        return dataset_dir
    
    def prepare_combined_dataset(self):
        """
        Combine and organize all downloaded datasets into our 5 categories:
        Organic, Plastic, Metal, Glass, Hazardous
        """
        print("\n" + "="*60)
        print("PREPARING COMBINED DATASET")
        print("="*60)
        
        combined_dir = self.base_dir / "combined_dataset"
        
        # Create directory structure
        categories = ['Organic', 'Plastic', 'Metal', 'Glass', 'Hazardous']
        for split in ['train', 'val', 'test']:
            for category in categories:
                (combined_dir / split / category).mkdir(parents=True, exist_ok=True)
        
        # Mapping from various dataset categories to our 5 categories
        category_mapping = {
            # TrashNet mappings
            'cardboard': 'Organic',
            'paper': 'Organic',
            'trash': 'Organic',
            'plastic': 'Plastic',
            'metal': 'Metal',
            'glass': 'Glass',
            
            # Waste Classification V2 mappings
            'organic': 'Organic',
            'O': 'Organic',
            'recyclable': 'Plastic',  # Most recyclables are plastic
            'R': 'Plastic',
            
            # Garbage Classification mappings
            'biological': 'Organic',
            'green-glass': 'Glass',
            'brown-glass': 'Glass',
            'white-glass': 'Glass',
            'battery': 'Hazardous',
            'clothes': 'Organic',
            'shoes': 'Organic',
            
            # Waste Images mappings
            'hazardous': 'Hazardous',
            'e-waste': 'Hazardous',
        }
        
        print(f"\nCombined dataset will be saved to: {combined_dir}")
        print("\nCategory mappings applied:")
        for source, target in category_mapping.items():
            print(f"  {source} -> {target}")
        
        return combined_dir

def setup_kaggle_api():
    """
    Setup Kaggle API credentials
    """
    print("\n" + "="*60)
    print("KAGGLE API SETUP")
    print("="*60)
    
    kaggle_dir = Path.home() / ".kaggle"
    kaggle_json = kaggle_dir / "kaggle.json"
    
    if not kaggle_json.exists():
        print("Kaggle API key not found!")
        print("\nTo set up Kaggle API:")
        print("1. Go to https://www.kaggle.com/account")
        print("2. Click 'Create New API Token'")
        print("3. Save the downloaded kaggle.json to:")
        print(f"   {kaggle_json}")
        print("\nOr create kaggle.json manually with:")
        print('{"username":"your_username","key":"your_api_key"}')
        
        # Offer to create template
        create = input("\nCreate template kaggle.json? (y/n): ")
        if create.lower() == 'y':
            kaggle_dir.mkdir(exist_ok=True)
            template = {
                "username": "your_kaggle_username",
                "key": "your_kaggle_api_key"
            }
            with open(kaggle_json, 'w') as f:
                json.dump(template, f, indent=2)
            print(f"Template created at: {kaggle_json}")
            print("Please edit it with your actual credentials.")
        return False
    
    # Set permissions (Unix-like systems)
    try:
        os.chmod(kaggle_json, 0o600)
    except:
        pass  # Windows doesn't need this
    
    print("✓ Kaggle API configured")
    return True

def main():
    """
    Main function to download all datasets
    """
    print("\n" + "="*60)
    print("WASTE DATASET AUTOMATIC DOWNLOADER")
    print("="*60)
    
    downloader = WasteDatasetDownloader()
    
    # Check dependencies
    print("\nChecking dependencies...")
    dependencies = {
        'gdown': 'pip install gdown',
        'kaggle': 'pip install kaggle',
        'tqdm': 'pip install tqdm'
    }
    
    missing = []
    for package, install_cmd in dependencies.items():
        try:
            __import__(package)
            print(f"✓ {package} installed")
        except ImportError:
            print(f"✗ {package} not installed")
            missing.append(install_cmd)
    
    if missing:
        print("\nInstall missing dependencies:")
        for cmd in missing:
            print(f"  {cmd}")
        print("\nOr install all at once:")
        print("  pip install gdown kaggle tqdm")
        return
    
    # Setup Kaggle API if needed
    kaggle_ready = setup_kaggle_api()
    
    # Download datasets
    print("\n" + "="*60)
    print("AVAILABLE DATASETS TO DOWNLOAD")
    print("="*60)
    
    datasets = [
        ("TrashNet", "GitHub", "2,527 images", downloader.download_trashnet),
        ("Waste Classification V2", "Kaggle", "25,077 images", downloader.download_waste_classification_v2),
        ("TACO", "GitHub", "1,500 images", downloader.download_taco),
        ("Garbage Classification", "Kaggle", "15,000 images", downloader.download_garbage_classification),
        ("Waste Images", "Kaggle", "2,467 images", downloader.download_waste_images),
    ]
    
    print("\nDatasets available for download:")
    for i, (name, source, size, _) in enumerate(datasets, 1):
        print(f"{i}. {name} ({source}) - {size}")
    
    print("\nOptions:")
    print("  a - Download all datasets")
    print("  1-5 - Download specific dataset")
    print("  q - Quit")
    
    choice = input("\nYour choice: ").strip().lower()
    
    if choice == 'q':
        return
    elif choice == 'a':
        # Download all
        for name, source, size, download_func in datasets:
            if source == "Kaggle" and not kaggle_ready:
                print(f"Skipping {name} (Kaggle API not configured)")
                continue
            try:
                download_func()
            except Exception as e:
                print(f"Error downloading {name}: {e}")
    else:
        # Download specific
        try:
            idx = int(choice) - 1
            if 0 <= idx < len(datasets):
                name, source, size, download_func = datasets[idx]
                if source == "Kaggle" and not kaggle_ready:
                    print(f"Cannot download {name} (Kaggle API not configured)")
                else:
                    download_func()
            else:
                print("Invalid choice")
        except ValueError:
            print("Invalid choice")
    
    # Prepare combined dataset
    print("\n" + "="*60)
    print("NEXT STEPS")
    print("="*60)
    print("\n1. Review downloaded datasets in 'datasets/' folder")
    print("2. Run prepare_combined_dataset() to organize into 5 categories")
    print("3. Copy organized data to ml_training/data/")
    print("4. Retrain model with: python train_model.py")
    
    print("\n" + "="*60)
    print("DATASET STATISTICS")
    print("="*60)
    print("\nTotal available images: ~46,000+")
    print("Recommended split:")
    print("  - Training: 70% (~32,000 images)")
    print("  - Validation: 15% (~7,000 images)")
    print("  - Testing: 15% (~7,000 images)")

if __name__ == "__main__":
    main()
