from analyzer.website_analyzer import WebsiteAnalyzer
from analyzer.smart_crawler import SmartCrawler
import json

# Analyze the website
w = WebsiteAnalyzer()
crawler = SmartCrawler()

print("Analyzing Robert Lee Law Offices...")
data = w.analyze('https://www.robertleelawoffices.com')

print(f"\n1. Services found by analyzer: {len(data.get('services', []))}")
for s in data.get('services', []):
    print(f"   - {s.get('title', 'Unknown')}: {s.get('description', '')[:50]}...")

print(f"\n2. Service pages discovered: {len(data.get('discovered_pages', {}).get('services', []))}")
for url in data.get('discovered_pages', {}).get('services', [])[:10]:
    print(f"   - {url}")

print(f"\n3. Navigation items: {len(data.get('navigation', []))}")
for nav in data.get('navigation', []):
    print(f"   - {nav.get('label', '')}: {nav.get('url', '')}")

# Let's check the actual website more carefully
print("\n4. Checking for practice areas on main page...")
import requests
from bs4 import BeautifulSoup

response = requests.get('https://www.robertleelawoffices.com')
soup = BeautifulSoup(response.text, 'html.parser')

# Look for practice areas or services
practice_areas = []

# Check for common patterns
patterns = [
    ('h2', 'practice'),
    ('h3', 'practice'),
    ('div', 'practice'),
    ('section', 'practice'),
    ('h2', 'service'),
    ('div', 'service'),
    ('li', 'practice'),
]

for tag, keyword in patterns:
    elements = soup.find_all(tag, text=lambda x: x and keyword in x.lower())
    for elem in elements:
        print(f"   Found {tag} with '{keyword}': {elem.get_text()[:100]}")

# Look for lists that might contain services
print("\n5. Looking for service lists...")
all_lists = soup.find_all('ul')
for ul in all_lists:
    text = ul.get_text().lower()
    if any(word in text for word in ['practice', 'service', 'law', 'legal', 'defense', 'litigation']):
        items = ul.find_all('li')
        if items and len(items) < 20:  # Reasonable service list size
            print(f"   Found potential service list with {len(items)} items:")
            for item in items[:10]:
                print(f"      - {item.get_text().strip()[:50]}")

# Check if it's a single-page site with sections
print("\n6. Checking page structure...")
sections = soup.find_all(['section', 'div'], class_=lambda x: x and 'practice' in str(x).lower())
print(f"   Found {len(sections)} sections with 'practice' in class")

# Check the practices page specifically
print("\n7. Checking /practices page...")
try:
    practices_response = requests.get('https://www.robertleelawoffices.com/practices')
    if practices_response.status_code == 200:
        practices_soup = BeautifulSoup(practices_response.text, 'html.parser')

        # Look for practice area headings
        headings = practices_soup.find_all(['h2', 'h3', 'h4'])
        practice_headings = [h for h in headings if 'practice' not in h.get_text().lower()]

        print(f"   Found {len(practice_headings)} potential practice areas:")
        for h in practice_headings[:15]:
            text = h.get_text().strip()
            if text and len(text) < 50:
                print(f"      - {text}")
except:
    print("   Could not access /practices page")