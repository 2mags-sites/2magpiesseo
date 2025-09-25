#!/usr/bin/env python3
"""
Website Rebuilder - Main Controller
Analyzes any website and generates a complete PHP-based website with SEO optimization
"""

import os
import sys
import argparse
import json
import logging
from datetime import datetime
from pathlib import Path

# Add project root to path
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from analyzer.website_analyzer import WebsiteAnalyzer
from analyzer.business_detector import BusinessDetector
from generator.json_generator import JSONGenerator
from generator.php_generator import PHPGenerator
from enhancer.seo_optimizer import SEOOptimizer
from enhancer.content_enhancer import ContentEnhancer

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('website_rebuilder.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)


class WebsiteRebuilder:
    """Main orchestrator for the website rebuilding process"""

    def __init__(self, output_dir="output"):
        self.output_dir = Path(output_dir)
        self.analyzer = WebsiteAnalyzer()
        self.detector = BusinessDetector()
        self.json_generator = JSONGenerator()
        self.php_generator = PHPGenerator()
        self.seo_optimizer = SEOOptimizer()
        self.content_enhancer = ContentEnhancer()

    def rebuild_website(self, url, keywords=None, business_name=None):
        """
        Main workflow to rebuild a website

        Args:
            url: Website URL to analyze
            keywords: Target SEO keywords (optional)
            business_name: Override business name (optional)

        Returns:
            Path to generated website
        """
        logger.info(f"Starting website rebuild for: {url}")

        # Create project folder
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        project_name = business_name or url.replace("https://", "").replace("http://", "").replace("/", "_")
        project_dir = self.output_dir / f"{project_name}_{timestamp}"
        project_dir.mkdir(parents=True, exist_ok=True)

        try:
            # Step 1: Analyze website
            logger.info("Step 1: Analyzing website...")
            analysis_data = self.analyzer.analyze(url)

            # Step 2: Detect business type
            logger.info("Step 2: Detecting business type...")
            business_type = self.detector.detect(analysis_data)
            analysis_data['business_type'] = business_type

            # Step 3: Generate keywords if not provided
            if not keywords:
                logger.info("Generating suggested keywords...")
                keywords = self.seo_optimizer.suggest_keywords(analysis_data)

            # Step 3.5: Discover all services from the website
            discovered_services = []
            if analysis_data.get('discovered_pages', {}).get('services'):
                logger.info(f"Found {len(analysis_data['discovered_pages']['services'])} service pages")
                # Extract service names from URLs and titles
                for service_data in analysis_data.get('services', []):
                    if service_data.get('title'):
                        discovered_services.append(service_data['title'])

            # Combine discovered services with keywords
            all_services = list(set(discovered_services + (keywords or [])))
            logger.info(f"Total services to generate: {len(all_services)}")

            # Step 4: Generate JSON data files
            logger.info("Step 4: Generating JSON data files with AI-enhanced content...")
            json_data = self.json_generator.generate(
                analysis_data,
                keywords=all_services,  # Use all discovered services
                output_dir=project_dir / "data"
            )

            # Step 5: Enhance content for SEO
            logger.info("Step 5: Enhancing content for SEO...")
            enhanced_data = self.content_enhancer.enhance(
                json_data,
                business_type=business_type,
                keywords=all_services
            )

            # Step 6: Generate PHP website
            logger.info("Step 5: Generating PHP website...")
            website_path = self.php_generator.generate(
                enhanced_data,
                output_dir=project_dir,
                business_type=business_type
            )

            # Step 7: Generate additional SEO files
            logger.info("Step 6: Generating SEO files...")
            self._generate_seo_files(project_dir, enhanced_data, url)

            # Step 8: Create deployment package
            logger.info("Step 7: Creating deployment package...")
            self._create_deployment_package(project_dir)

            logger.info(f"[SUCCESS] Website successfully generated at: {project_dir}")
            return project_dir

        except Exception as e:
            logger.error(f"Error during website rebuild: {str(e)}")
            raise

    def _generate_seo_files(self, output_dir, data, original_url):
        """Generate robots.txt, sitemap.xml, and .htaccess"""

        # Generate robots.txt
        robots_content = """User-agent: *
Allow: /
Sitemap: /sitemap.xml

User-agent: Googlebot
Allow: /

User-agent: Bingbot
Allow: /
"""
        (output_dir / "robots.txt").write_text(robots_content)

        # Generate sitemap.xml
        pages = data.get('pages', {})
        sitemap_content = '<?xml version="1.0" encoding="UTF-8"?>\n'
        sitemap_content += '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n'

        # Add homepage
        sitemap_content += '  <url>\n'
        sitemap_content += '    <loc>/</loc>\n'
        sitemap_content += '    <changefreq>weekly</changefreq>\n'
        sitemap_content += '    <priority>1.0</priority>\n'
        sitemap_content += '  </url>\n'

        # Add all pages
        for page_name in pages.keys():
            sitemap_content += '  <url>\n'
            sitemap_content += f'    <loc>/{page_name}.php</loc>\n'
            sitemap_content += '    <changefreq>weekly</changefreq>\n'
            sitemap_content += '    <priority>0.8</priority>\n'
            sitemap_content += '  </url>\n'

        sitemap_content += '</urlset>'
        (output_dir / "sitemap.xml").write_text(sitemap_content)

        # Generate router.php for PHP built-in server
        router_content = r'''<?php
/**
 * Router for PHP Built-in Server
 * Handles clean URLs without .php extension
 * Usage: php -S localhost:8000 router.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = ltrim($uri, '/');

// If no URI, serve index
if (empty($uri)) {
    $uri = 'index';
}

// Check for static files
if (file_exists($uri) && !is_dir($uri) && preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|xml|txt|woff|woff2|ttf|svg)$/i', $uri)) {
    return false; // Let PHP handle static files
}

// For PHP pages without extension
if (!preg_match('/\.(php|html|css|js|jpg|jpeg|png|gif|ico|xml|txt)$/i', $uri)) {
    if (file_exists($uri . '.php')) {
        // Set the correct script name for page detection
        $_SERVER['SCRIPT_NAME'] = '/' . $uri . '.php';
        $_SERVER['PHP_SELF'] = '/' . $uri . '.php';
        $_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/' . $uri . '.php';
        chdir(dirname($_SERVER['SCRIPT_FILENAME']));
        require $uri . '.php';
        return;
    }
}

// Default handling
if (file_exists($uri)) {
    return false;
}

// 404 page
if (file_exists('404.php')) {
    require '404.php';
} else {
    http_response_code(404);
    echo '404 Not Found';
}
?>'''
        (output_dir / "router.php").write_text(router_content)

        # Copy .htaccess template if it exists, otherwise use basic version
        htaccess_template = Path(__file__).parent / "templates" / ".htaccess.template"
        if htaccess_template.exists():
            htaccess_content = htaccess_template.read_text()
        else:
            # Fallback basic .htaccess if template not found
            htaccess_content = r"""# Basic .htaccess for PHP site
RewriteEngine On

# Remove .php extension
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME}\.php -f
RewriteRule ^(.*)$ $1.php [L]

# CRITICAL: Don't redirect POST requests (they lose data)
RewriteCond %{REQUEST_METHOD} !POST
RewriteCond %{THE_REQUEST} /([^.]+)\.php [NC]
RewriteRule ^ /%1 [R=301,L]

# Security headers
<IfModule mod_headers.c>
  Header set X-Content-Type-Options "nosniff"
  Header set X-Frame-Options "SAMEORIGIN"
  Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Error pages
ErrorDocument 404 /404.php
ErrorDocument 500 /500.php
"""
        (output_dir / ".htaccess").write_text(htaccess_content)

    def _create_deployment_package(self, output_dir):
        """Create deployment instructions and package info"""

        readme_content = f"""# Website Deployment Instructions

## Generated Website Package
Generated on: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}

## Files Included:
- PHP pages (index.php, service pages, etc.)
- Data files (JSON data in /data folder)
- Assets (CSS, JS, images in /assets folder)
- Configuration files (.htaccess, robots.txt, sitemap.xml)

## Deployment Steps:

1. **Upload all files** to your web hosting root directory
2. **Ensure PHP 7.4+** is enabled on your hosting
3. **Set proper permissions**:
   - Files: 644
   - Directories: 755
   - Data folder: 755 (read/write)

4. **Test the website**:
   - Visit your domain
   - Check all pages load correctly
   - Test contact form
   - Verify mobile responsiveness

5. **Submit to search engines**:
   - Submit sitemap.xml to Google Search Console
   - Submit to Bing Webmaster Tools
   - Monitor indexing progress

## Features:
- [OK] SEO optimized pages
- [OK] Mobile responsive design
- [OK] Fast loading (no database)
- [OK] Schema markup included
- [OK] Clean URLs enabled
- [OK] Security headers configured

## Support:
For issues or modifications, refer to the documentation or regenerate with updated content.
"""
        (output_dir / "README.txt").write_text(readme_content, encoding='utf-8')


def main():
    """Main entry point"""
    parser = argparse.ArgumentParser(description='Website Rebuilder - Generate PHP websites from any URL')
    parser.add_argument('--url', type=str, help='Website URL to analyze and rebuild')
    parser.add_argument('--keywords', type=str, help='Comma-separated target keywords for SEO')
    parser.add_argument('--name', type=str, help='Business name (optional override)')
    parser.add_argument('--output', type=str, default='output', help='Output directory')
    parser.add_argument('--demo', action='store_true', help='Run demo with example data')

    args = parser.parse_args()

    rebuilder = WebsiteRebuilder(output_dir=args.output)

    if args.demo:
        # Run demo mode with example data
        logger.info("Running in DEMO mode with example data...")
        from examples.demo_runner import run_demo
        run_demo()
    elif args.url:
        # Process real website
        keywords = args.keywords.split(',') if args.keywords else None
        rebuilder.rebuild_website(
            url=args.url,
            keywords=keywords,
            business_name=args.name
        )
    else:
        parser.print_help()


if __name__ == "__main__":
    main()