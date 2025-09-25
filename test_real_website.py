#!/usr/bin/env python3
"""
Test the Website Rebuilder with a real website
This script demonstrates how the system works with actual URLs
"""

import sys
import logging
from pathlib import Path
from datetime import datetime

# Add parent directory to path
sys.path.insert(0, str(Path(__file__).parent))

from main import WebsiteRebuilder

def test_with_real_website():
    """Test the system with a real website"""

    # Configure logging
    logging.basicConfig(
        level=logging.INFO,
        format='%(message)s'
    )

    # Test URLs (these are examples - replace with actual URLs)
    test_cases = [
        {
            'name': 'Technology Company Example',
            'url': 'https://www.example.com',  # Replace with real URL
            'keywords': 'web development,mobile apps,cloud services,IT consulting,software solutions'
        },
        {
            'name': 'Restaurant Example',
            'url': 'https://www.example-restaurant.com',  # Replace with real URL
            'keywords': 'italian cuisine,pizza delivery,catering,private events,wine bar'
        },
        {
            'name': 'Medical Practice Example',
            'url': 'https://www.example-clinic.com',  # Replace with real URL
            'keywords': 'family medicine,pediatrics,urgent care,health screening,vaccinations'
        }
    ]

    print("="*60)
    print("WEBSITE REBUILDER - REAL WEBSITE TEST")
    print("="*60)
    print("\nThis script demonstrates how to use the system with real websites.")
    print("Replace the example URLs with actual website URLs to test.\n")

    # Initialize the rebuilder
    rebuilder = WebsiteRebuilder()

    # Show how to use it
    print("Example usage:")
    print("-"*60)

    for test in test_cases:
        print(f"\n{test['name']}:")
        print(f"  URL: {test['url']}")
        print(f"  Keywords: {test['keywords']}")
        print(f"\nCommand to run:")
        print(f'  python main.py --url "{test["url"]}" --keywords "{test["keywords"]}"')

    print("\n" + "="*60)
    print("To test with a real website, run one of the commands above")
    print("or use the interactive batch file: run_website_rebuild.bat")
    print("="*60)

    # Demonstrate with demo mode
    print("\nRunning demo mode to show the system works...")
    print("-"*60)

    try:
        # Run demo
        success = rebuilder.run_demo()

        if success:
            print("\n[SUCCESS] Demo completed successfully!")
            print("The system is ready to process real websites.")
        else:
            print("\n[ERROR] Demo failed. Check the logs for details.")

    except Exception as e:
        print(f"\n[ERROR] An error occurred: {e}")
        logging.exception("Test failed")

    print("\n" + "="*60)
    print("TEST COMPLETE")
    print("="*60)

if __name__ == "__main__":
    test_with_real_website()