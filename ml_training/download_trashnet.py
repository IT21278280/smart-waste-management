"""
Simple script to download TrashNet dataset
"""

import os
import zipfile
import gdown
from pathlib import Path

def download_trashnet():
    """Download TrashNet dataset from Google Drive mirror"""
    
    print("="*60)
    print("DOWNLOADING TRASHNET DATASET")
    print("="*60)
    print("\nTrashNet contains 2527 images in 6 categories:")
    print("- Cardboard (403 images)")
    print("- Glass (501 images)")
    print("- Metal (410 images)")
    print("- Paper (594 images)")
    print("- Plastic (482 images)")
    print("- Trash (137 images)")
    
    # Create datasets directory
    dataset_dir = Path("datasets/trashnet")
    dataset_dir.mkdir(parents=True, exist_ok=True)
    
    # Google Drive URL for TrashNet dataset
    # This is a mirror of the original dataset
    url = "https://drive.google.com/uc?id=1lXreXd5_VGlLZq8qPQbJn0N8yvJcpVGP"
    
    try:
        print("\nDownloading TrashNet dataset (may take a few minutes)...")
        zip_path = dataset_dir / "trashnet.zip"
        
        # Download using gdown
        gdown.download(url, str(zip_path), quiet=False)
        
        print("\nExtracting dataset...")
        with zipfile.ZipFile(zip_path, 'r') as zip_ref:
            zip_ref.extractall(dataset_dir)
        
        # Clean up zip file
        zip_path.unlink()
        
        print(f"\n[SUCCESS] TrashNet dataset downloaded to: {dataset_dir}")
        
        # List what was downloaded
        for item in dataset_dir.iterdir():
            if item.is_dir():
                count = len(list(item.glob('**/*.jpg'))) + len(list(item.glob('**/*.png')))
                print(f"  - {item.name}: {count} images")
        
        return True
        
    except Exception as e:
        print(f"\n[ERROR] Failed to download TrashNet: {e}")
        print("\nAlternative method:")
        print("1. Visit: https://github.com/garythung/trashnet")
        print("2. Download the dataset manually")
        print(f"3. Extract to: {dataset_dir}")
        return False

if __name__ == "__main__":
    success = download_trashnet()
    if success:
        print("\nNext step: Run organize_trashnet.py to prepare the data")
    else:
        print("\nPlease try the manual download method")
