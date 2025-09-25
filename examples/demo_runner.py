"""
Demo Runner - Runs a demonstration with example data
"""

import os
import json
import logging
from pathlib import Path
from datetime import datetime
from typing import Dict, List

logger = logging.getLogger(__name__)


def run_demo():
    """Run demonstration with example data"""
    logger.info("="*60)
    logger.info("RUNNING DEMO MODE - WEBSITE REBUILDER")
    logger.info("="*60)

    # Create demo analysis data (simulating a law firm website)
    demo_data = create_demo_analysis_data()

    # Import necessary modules
    from analyzer.business_detector import BusinessDetector
    from generator.json_generator import JSONGenerator
    from generator.php_generator import PHPGenerator
    from enhancer.seo_optimizer import SEOOptimizer
    from enhancer.content_enhancer import ContentEnhancer

    # Initialize components
    detector = BusinessDetector()
    json_gen = JSONGenerator()
    php_gen = PHPGenerator()
    seo_opt = SEOOptimizer()
    enhancer = ContentEnhancer()

    # Create output directory
    output_dir = Path('output') / f'demo_{datetime.now().strftime("%Y%m%d_%H%M%S")}'
    output_dir.mkdir(parents=True, exist_ok=True)

    logger.info(f"Output directory: {output_dir}")

    # Step 1: Detect business type
    logger.info("\nStep 1: Detecting business type...")
    business_type = detector.detect(demo_data)
    logger.info(f"Detected business type: {business_type}")

    # Step 2: Generate keywords
    logger.info("\nStep 2: Generating SEO keywords...")
    keywords = seo_opt.suggest_keywords(demo_data)
    if not keywords:
        keywords = ['family law hong kong', 'divorce lawyer hong kong', 'child custody lawyer']
    logger.info(f"Target keywords: {keywords[:5]}")

    # Step 3: Generate JSON data
    logger.info("\nStep 3: Generating JSON data files...")
    json_data = json_gen.generate(
        demo_data,
        keywords=keywords,
        output_dir=output_dir / 'data'
    )
    logger.info(f"Generated {len(json_data['universal'])} universal files and {len(json_data['pages'])} page files")

    # Step 4: Enhance content
    logger.info("\nStep 4: Enhancing content for SEO...")
    enhanced_data = enhancer.enhance(
        json_data,
        business_type=business_type,
        keywords=keywords
    )

    # Step 5: Generate PHP website
    logger.info("\nStep 5: Generating PHP website...")
    website_path = php_gen.generate(
        enhanced_data,
        output_dir=output_dir,
        business_type=business_type
    )

    # Step 6: Generate additional files
    logger.info("\nStep 6: Generating additional files...")
    generate_demo_extras(output_dir, enhanced_data)

    # Step 7: Create summary report
    logger.info("\nStep 7: Creating summary report...")
    create_demo_report(output_dir, business_type, keywords)

    logger.info("\n" + "="*60)
    logger.info("DEMO COMPLETED SUCCESSFULLY!")
    logger.info(f"Website generated at: {output_dir}")
    logger.info("="*60)
    logger.info("\nTo view the website:")
    logger.info("1. Upload all files to a PHP-enabled web server")
    logger.info("2. Or run locally with: php -S localhost:8000")
    logger.info(f"   from directory: {output_dir}")
    logger.info("="*60)


def create_demo_analysis_data():
    """Create demo analysis data simulating a real website"""
    return {
        'url': 'https://demo-lawfirm.example.com',
        'domain': 'demo-lawfirm.example.com',
        'business_info': {
            'name': 'Smith & Associates Law Firm',
            'tagline': 'Your Trusted Legal Partners in Hong Kong',
            'about_summary': 'Smith & Associates is a leading law firm in Hong Kong with over 20 years of experience in family law, commercial litigation, and corporate law. Our team of dedicated attorneys provides personalized legal solutions.',
            'brand_text': 'Smith & Associates',
            'hours_raw': 'Monday-Friday 9AM-6PM, Saturday 10AM-2PM'
        },
        'contact': {
            'phone': '+852 2234 5678',
            'email': 'info@smithlaw.hk',
            'address': '123 Central Plaza, Central District, Hong Kong'
        },
        'services': [
            {
                'title': 'Family Law',
                'description': 'Comprehensive family law services including divorce, child custody, and matrimonial property division.'
            },
            {
                'title': 'Commercial Litigation',
                'description': 'Expert representation in commercial disputes, contract breaches, and business litigation.'
            },
            {
                'title': 'Corporate Law',
                'description': 'Full-service corporate legal support including company formation, mergers, and acquisitions.'
            },
            {
                'title': 'Immigration Law',
                'description': 'Immigration and visa services for individuals and businesses.'
            },
            {
                'title': 'Real Estate Law',
                'description': 'Property transactions, conveyancing, and real estate litigation.'
            },
            {
                'title': 'Criminal Defense',
                'description': 'Experienced criminal defense representation for all types of charges.'
            }
        ],
        'team': [
            {
                'name': 'John Smith',
                'title': 'Senior Partner',
                'bio': 'John Smith is the founding partner with over 25 years of experience in Hong Kong law. He specializes in complex commercial litigation and has represented numerous Fortune 500 companies.'
            },
            {
                'name': 'Sarah Chen',
                'title': 'Partner - Family Law',
                'bio': 'Sarah Chen leads our family law practice with expertise in high-net-worth divorces and international custody disputes.'
            },
            {
                'name': 'Michael Wong',
                'title': 'Associate',
                'bio': 'Michael Wong focuses on corporate law and cross-border transactions.'
            }
        ],
        'navigation': [
            {'label': 'Home', 'url': '/', 'relative_url': '/'},
            {'label': 'Services', 'url': '/services', 'relative_url': '/services'},
            {'label': 'About', 'url': '/about', 'relative_url': '/about'},
            {'label': 'Our Team', 'url': '/team', 'relative_url': '/team'},
            {'label': 'Contact', 'url': '/contact', 'relative_url': '/contact'}
        ],
        'seo_data': {
            'title': 'Smith & Associates Law Firm | Hong Kong Legal Services',
            'description': 'Leading Hong Kong law firm specializing in family law, commercial litigation, and corporate law. Free consultation available.',
            'keywords': 'hong kong lawyer, family law, divorce attorney, commercial litigation',
            'h1_tags': ['Hong Kong Law Firm', 'Expert Legal Services'],
            'heading_structure': {'h1': 2, 'h2': 8, 'h3': 12}
        },
        'social_media': {
            'facebook': 'https://facebook.com/smithlawHK',
            'linkedin': 'https://linkedin.com/company/smith-associates-hk',
            'twitter': 'https://twitter.com/smithlaw_hk'
        },
        'pages': {
            'about': {
                'url': '/about',
                'title': 'About Smith & Associates',
                'content': 'Founded in 2000, Smith & Associates has grown to become one of Hong Kong\'s most respected law firms. Our commitment to excellence and client satisfaction has earned us recognition from Legal 500 Asia Pacific.'
            },
            'services': {
                'url': '/services',
                'title': 'Our Legal Services',
                'content': 'We offer comprehensive legal services across multiple practice areas. Our team of experienced attorneys provides personalized solutions tailored to each client\'s unique needs.'
            }
        },
        'business_type': 'law_firm'  # This would normally be detected
    }


def generate_demo_extras(output_dir: Path, data: Dict):
    """Generate additional files for the demo"""

    # Generate robots.txt
    robots_content = """User-agent: *
Allow: /
Sitemap: /sitemap.xml

User-agent: Googlebot
Allow: /

User-agent: Bingbot
Allow: /
"""
    (output_dir / 'robots.txt').write_text(robots_content)

    # Generate sitemap.xml
    sitemap_content = '''<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://example.com/</loc>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://example.com/about</loc>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>https://example.com/services</loc>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc>https://example.com/contact</loc>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
</urlset>'''
    (output_dir / 'sitemap.xml').write_text(sitemap_content)

    # Generate .htaccess
    htaccess_content = r"""# Enable rewrite engine
RewriteEngine On

# Remove .php extension
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^\.]+)$ $1.php [NC,L]

# Force HTTPS (uncomment in production)
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]

# Compress text files
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>

# Browser caching
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType application/javascript "access plus 1 month"
</IfModule>

# Security headers
<IfModule mod_headers.c>
  Header set X-Content-Type-Options "nosniff"
  Header set X-Frame-Options "SAMEORIGIN"
  Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Custom error pages
ErrorDocument 404 /404.php
"""
    (output_dir / '.htaccess').write_text(htaccess_content)

    # Generate favicon.ico placeholder
    (output_dir / 'favicon.ico').write_text('')

    logger.info("Generated robots.txt, sitemap.xml, .htaccess, and favicon.ico")


def create_demo_report(output_dir: Path, business_type: str, keywords: List[str]):
    """Create a summary report for the demo"""

    report_content = f"""
================================================================================
WEBSITE REBUILDER - DEMO REPORT
================================================================================

Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}
Output Directory: {output_dir}

BUSINESS INFORMATION:
--------------------
Name: Smith & Associates Law Firm
Type: {business_type}
Industry: Legal Services

TARGET KEYWORDS:
---------------
{chr(10).join(f'- {kw}' for kw in keywords[:10])}

GENERATED FILES:
---------------
[OK] PHP Pages:
   - index.php (Homepage)
   - about.php (About Page)
   - contact.php (Contact Page)
   - Service pages for each keyword

[OK] Data Files:
   - data/universal/business.json
   - data/universal/navigation.json
   - data/universal/team.json
   - data/universal/contact.json
   - data/universal/social.json
   - data/pages/*.json (Page-specific data)

[OK] Assets:
   - assets/css/main.css (Main stylesheet)
   - assets/css/normalize.css (CSS reset)
   - assets/js/main.js (JavaScript functionality)

[OK] Components:
   - components/header.php
   - components/footer.php

[OK] Configuration:
   - config.php (Site configuration)
   - functions.php (Helper functions)
   - .htaccess (URL rewriting)
   - robots.txt (Search engine directives)
   - sitemap.xml (XML sitemap)

SEO FEATURES:
------------
[OK] Optimized meta tags for all pages
[OK] Schema.org markup (LocalBusiness, FAQPage)
[OK] Clean URL structure
[OK] Mobile-responsive design
[OK] Fast loading (no database queries)
[OK] Semantic HTML5 structure
[OK] Comprehensive FAQ sections

DEPLOYMENT INSTRUCTIONS:
-----------------------
1. Upload all files to your PHP web hosting
2. Ensure PHP 7.4+ is enabled
3. Update config.php with your email for contact form
4. Test all pages and functionality
5. Submit sitemap.xml to search engines

LOCAL TESTING:
-------------
To test locally, navigate to the output directory and run:
   php -S localhost:8000

Then open your browser to:
   http://localhost:8000

================================================================================
"""

    report_file = output_dir / 'DEMO_REPORT.txt'
    report_file.write_text(report_content)
    logger.info(f"Demo report created: {report_file}")


if __name__ == "__main__":
    logging.basicConfig(level=logging.INFO)
    run_demo()