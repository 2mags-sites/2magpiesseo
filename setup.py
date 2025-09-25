#!/usr/bin/env python3
"""
Setup script for Website Rebuilder
"""

import os
import sys
import subprocess
from pathlib import Path


def check_python_version():
    """Check if Python version is 3.8+"""
    if sys.version_info < (3, 8):
        print("Error: Python 3.8 or higher is required")
        print(f"Current version: {sys.version}")
        return False
    print(f"[OK] Python {sys.version.split()[0]} detected")
    return True


def install_dependencies():
    """Install required Python packages"""
    print("\nInstalling dependencies...")
    try:
        subprocess.check_call([sys.executable, "-m", "pip", "install", "-r", "requirements.txt"])
        print("[OK] Dependencies installed successfully")
        return True
    except subprocess.CalledProcessError:
        print("[ERROR] Failed to install dependencies")
        print("Please run manually: pip install -r requirements.txt")
        return False


def check_php():
    """Check if PHP is installed (optional for local testing)"""
    try:
        result = subprocess.run(["php", "-v"], capture_output=True, text=True)
        if result.returncode == 0:
            php_version = result.stdout.split('\n')[0]
            print(f"[OK] PHP detected: {php_version}")
            return True
    except FileNotFoundError:
        pass

    print("[WARNING] PHP not found (optional - only needed for local testing)")
    print("  To test generated websites locally, install PHP 7.4+")
    return True  # PHP is optional


def create_directories():
    """Create necessary directories"""
    directories = [
        "output",
        "logs",
        "config"
    ]

    for directory in directories:
        Path(directory).mkdir(exist_ok=True)

    print("[OK] Directory structure created")
    return True


def run_tests():
    """Run basic tests to ensure system works"""
    print("\nRunning system tests...")

    # Test imports
    try:
        from analyzer.website_analyzer import WebsiteAnalyzer
        from analyzer.business_detector import BusinessDetector
        from generator.json_generator import JSONGenerator
        from generator.php_generator import PHPGenerator
        from enhancer.seo_optimizer import SEOOptimizer
        from enhancer.content_enhancer import ContentEnhancer
        print("[OK] All modules imported successfully")
        return True
    except ImportError as e:
        print(f"[ERROR] Import error: {e}")
        return False


def main():
    """Main setup function"""
    print("="*60)
    print("WEBSITE REBUILDER - SETUP")
    print("="*60)

    # Check Python version
    if not check_python_version():
        return False

    # Install dependencies
    if not install_dependencies():
        return False

    # Check PHP (optional)
    check_php()

    # Create directories
    if not create_directories():
        return False

    # Run tests
    if not run_tests():
        return False

    print("\n" + "="*60)
    print("SETUP COMPLETED SUCCESSFULLY!")
    print("="*60)
    print("\nYou can now use the Website Rebuilder:")
    print("\n1. Run demo:")
    print("   python main.py --demo")
    print("\n2. Rebuild a website:")
    print("   python main.py --url https://example.com")
    print("\n3. With specific keywords:")
    print("   python main.py --url https://example.com --keywords \"keyword1,keyword2\"")
    print("\nOr use the batch files:")
    print("   run_demo.bat - Run demo with example data")
    print("   run_website_rebuild.bat - Interactive website rebuild")
    print("="*60)

    return True


if __name__ == "__main__":
    success = main()
    sys.exit(0 if success else 1)