"""
Smart Website Crawler
Intelligently discovers all relevant pages on a website
"""

import re
import json
import logging
import requests
import xml.etree.ElementTree as ET
from urllib.parse import urljoin, urlparse
from typing import List, Dict, Set, Optional
from bs4 import BeautifulSoup

logger = logging.getLogger(__name__)


class SmartCrawler:
    """Intelligent website crawler that discovers all relevant content"""

    def __init__(self, max_pages: int = 50, timeout: int = 10):
        self.max_pages = max_pages
        self.timeout = timeout
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        })

    def discover_all_pages(self, url: str) -> Dict:
        """Discover all relevant pages on a website"""
        base_url = self._get_base_url(url)
        discovered_pages = {
            'services': [],
            'about': [],
            'team': [],
            'contact': [],
            'other': []
        }

        # Try multiple discovery methods
        all_urls = set()

        # 1. Check sitemap.xml
        logger.info("Checking for sitemap.xml...")
        sitemap_urls = self._parse_sitemap(base_url)
        if sitemap_urls:
            logger.info(f"Found {len(sitemap_urls)} URLs in sitemap")
            all_urls.update(sitemap_urls)

        # 2. Check if WordPress and use REST API
        logger.info("Checking for WordPress...")
        if self._is_wordpress(base_url):
            wp_pages = self._get_wordpress_pages(base_url)
            logger.info(f"Found {len(wp_pages)} WordPress pages")
            all_urls.update(wp_pages)

        # 3. Check common page patterns
        logger.info("Checking common page patterns...")
        common_pages = self._check_common_pages(base_url)
        all_urls.update(common_pages)

        # 4. Parse navigation menu from homepage
        logger.info("Extracting navigation links...")
        nav_links = self._extract_navigation_links(base_url)
        all_urls.update(nav_links)

        # 5. Look for practice/service pages specifically
        logger.info("Looking for service pages...")
        service_pages = self._find_service_pages(base_url, all_urls)
        all_urls.update(service_pages)

        # Categorize discovered URLs
        for url in all_urls:
            category = self._categorize_url(url)
            if category != 'skip':
                discovered_pages[category].append(url)

        # Limit pages per category
        for category in discovered_pages:
            discovered_pages[category] = discovered_pages[category][:20]

        return discovered_pages

    def _get_base_url(self, url: str) -> str:
        """Get the base URL from any page URL"""
        parsed = urlparse(url)
        return f"{parsed.scheme}://{parsed.netloc}"

    def _parse_sitemap(self, base_url: str) -> Set[str]:
        """Parse sitemap.xml if it exists"""
        urls = set()
        sitemap_locations = [
            '/sitemap.xml',
            '/sitemap_index.xml',
            '/wp-sitemap.xml',
            '/sitemap.xml.gz'
        ]

        for location in sitemap_locations:
            sitemap_url = urljoin(base_url, location)
            try:
                response = self.session.get(sitemap_url, timeout=self.timeout)
                if response.status_code == 200:
                    # Parse XML
                    root = ET.fromstring(response.content)

                    # Handle sitemap index
                    if 'sitemapindex' in root.tag:
                        for sitemap in root.findall('.//{http://www.sitemaps.org/schemas/sitemap/0.9}loc'):
                            sub_urls = self._parse_single_sitemap(sitemap.text)
                            urls.update(sub_urls)
                    else:
                        # Regular sitemap
                        for url in root.findall('.//{http://www.sitemaps.org/schemas/sitemap/0.9}loc'):
                            urls.add(url.text)

                    if urls:
                        break
            except Exception as e:
                logger.debug(f"Sitemap not found at {sitemap_url}: {e}")
                continue

        return urls

    def _parse_single_sitemap(self, sitemap_url: str) -> Set[str]:
        """Parse a single sitemap file"""
        urls = set()
        try:
            response = self.session.get(sitemap_url, timeout=self.timeout)
            if response.status_code == 200:
                root = ET.fromstring(response.content)
                for url in root.findall('.//{http://www.sitemaps.org/schemas/sitemap/0.9}loc'):
                    urls.add(url.text)
        except Exception as e:
            logger.debug(f"Error parsing sitemap {sitemap_url}: {e}")

        return urls

    def _is_wordpress(self, base_url: str) -> bool:
        """Check if the site is WordPress"""
        try:
            response = self.session.get(base_url, timeout=self.timeout)
            if response.status_code == 200:
                soup = BeautifulSoup(response.text, 'html.parser')

                # Check for WordPress indicators
                wordpress_indicators = [
                    soup.find('meta', {'name': 'generator', 'content': re.compile(r'WordPress', re.I)}),
                    soup.find('link', {'rel': 'https://api.w.org/'}),
                    'wp-content' in response.text,
                    'wp-includes' in response.text
                ]

                return any(wordpress_indicators)
        except Exception as e:
            logger.debug(f"Error checking WordPress: {e}")

        return False

    def _get_wordpress_pages(self, base_url: str) -> Set[str]:
        """Get pages from WordPress REST API"""
        urls = set()

        try:
            # Try WordPress REST API
            api_endpoints = [
                '/wp-json/wp/v2/pages',
                '/wp-json/wp/v2/posts?categories_exclude=1',  # Exclude uncategorized
                '/?rest_route=/wp/v2/pages'
            ]

            for endpoint in api_endpoints:
                api_url = urljoin(base_url, endpoint)
                response = self.session.get(api_url, timeout=self.timeout)

                if response.status_code == 200:
                    try:
                        pages = response.json()
                        for page in pages[:30]:  # Limit to 30 pages
                            if 'link' in page:
                                urls.add(page['link'])
                        if urls:
                            break
                    except json.JSONDecodeError:
                        continue
        except Exception as e:
            logger.debug(f"Error getting WordPress pages: {e}")

        return urls

    def _check_common_pages(self, base_url: str) -> Set[str]:
        """Check for common page patterns"""
        urls = set()
        common_paths = [
            '/about', '/about-us', '/about-team',
            '/services', '/our-services', '/what-we-do',
            '/practices', '/practice-areas', '/areas-of-practice',
            '/team', '/our-team', '/attorneys', '/lawyers', '/staff',
            '/contact', '/contact-us', '/get-in-touch',
            '/testimonials', '/reviews', '/case-studies',
            '/faq', '/faqs', '/frequently-asked-questions'
        ]

        for path in common_paths:
            test_url = urljoin(base_url, path)
            try:
                response = self.session.head(test_url, timeout=3, allow_redirects=True)
                if response.status_code == 200:
                    urls.add(test_url)
            except:
                continue

        return urls

    def _extract_navigation_links(self, base_url: str) -> Set[str]:
        """Extract all navigation links from the homepage"""
        urls = set()

        try:
            response = self.session.get(base_url, timeout=self.timeout)
            if response.status_code == 200:
                soup = BeautifulSoup(response.text, 'html.parser')

                # Find navigation elements
                nav_selectors = [
                    'nav a',
                    '.nav a', '.navbar a', '.navigation a',
                    '.menu a', '.main-menu a',
                    'header a',
                    '[role="navigation"] a'
                ]

                for selector in nav_selectors:
                    links = soup.select(selector)
                    for link in links:
                        href = link.get('href')
                        if href:
                            full_url = urljoin(base_url, href)
                            # Only include same-domain links
                            if urlparse(full_url).netloc == urlparse(base_url).netloc:
                                urls.add(full_url)
        except Exception as e:
            logger.debug(f"Error extracting navigation: {e}")

        return urls

    def _find_service_pages(self, base_url: str, existing_urls: Set[str]) -> Set[str]:
        """Find service/practice pages by analyzing content"""
        service_urls = set()

        # Look for pages with service-related URLs
        service_patterns = [
            r'/practice[s]?/',
            r'/service[s]?/',
            r'/area[s]?-of-practice/',
            r'/what-we-do/',
            r'/expertise/',
            r'/specialt(y|ies)/'
        ]

        for url in existing_urls:
            for pattern in service_patterns:
                if re.search(pattern, url, re.I):
                    # This might be a service listing page - crawl it for individual services
                    service_urls.update(self._extract_service_links(url))
                    break

        return service_urls

    def _extract_service_links(self, url: str) -> Set[str]:
        """Extract individual service page links from a service listing page"""
        urls = set()

        try:
            response = self.session.get(url, timeout=self.timeout)
            if response.status_code == 200:
                soup = BeautifulSoup(response.text, 'html.parser')

                # Look for service cards/links
                service_selectors = [
                    '.service-item a', '.practice-area a',
                    '.service-card a', '.practice-card a',
                    'article a', '.services-list a',
                    'h2 a', 'h3 a'  # Often service titles are linked
                ]

                base_url = self._get_base_url(url)

                for selector in service_selectors:
                    links = soup.select(selector)
                    for link in links[:30]:  # Limit to 30 services
                        href = link.get('href')
                        if href:
                            full_url = urljoin(base_url, href)
                            if urlparse(full_url).netloc == urlparse(base_url).netloc:
                                urls.add(full_url)
        except Exception as e:
            logger.debug(f"Error extracting service links: {e}")

        return urls

    def _categorize_url(self, url: str) -> str:
        """Categorize a URL based on its path and content"""

        # Skip blog posts, news, articles
        skip_patterns = [
            r'/blog/', r'/news/', r'/article[s]?/', r'/post[s]?/',
            r'/\d{4}/\d{2}/',  # Date-based URLs (typically blog posts)
            r'/category/', r'/tag/', r'/author/',
            r'\.pdf$', r'\.doc', r'\.zip',  # Skip downloads
            r'#', r'javascript:', r'mailto:'  # Skip anchors and special links
        ]

        for pattern in skip_patterns:
            if re.search(pattern, url, re.I):
                return 'skip'

        # Categorize based on URL patterns
        if re.search(r'/about|/team|/attorney|/lawyer|/staff', url, re.I):
            return 'about'
        elif re.search(r'/contact|/get-in-touch|/location', url, re.I):
            return 'contact'
        elif re.search(r'/service|/practice|/area|/expertise|/what-we-do', url, re.I):
            return 'services'
        elif re.search(r'/testimonial|/review|/case-stud', url, re.I):
            return 'other'
        else:
            return 'other'

    def extract_page_content(self, url: str) -> Dict:
        """Extract detailed content from a specific page"""
        content = {
            'url': url,
            'title': '',
            'description': '',
            'main_content': '',
            'sections': []
        }

        try:
            response = self.session.get(url, timeout=self.timeout)
            if response.status_code == 200:
                soup = BeautifulSoup(response.text, 'html.parser')

                # Get title
                title = soup.find('title')
                if title:
                    content['title'] = title.get_text(strip=True)

                # Get meta description
                meta_desc = soup.find('meta', {'name': 'description'})
                if meta_desc:
                    content['description'] = meta_desc.get('content', '')

                # Get main content
                main_selectors = [
                    'main', 'article', '.content', '#content',
                    '.main-content', '.page-content'
                ]

                for selector in main_selectors:
                    main = soup.select_one(selector)
                    if main:
                        # Extract text sections
                        sections = main.find_all(['h1', 'h2', 'h3', 'p', 'ul', 'ol'])

                        for section in sections[:50]:  # Limit sections
                            if section.name in ['h1', 'h2', 'h3']:
                                content['sections'].append({
                                    'type': 'heading',
                                    'level': section.name,
                                    'text': section.get_text(strip=True)
                                })
                            elif section.name == 'p':
                                text = section.get_text(strip=True)
                                if len(text) > 50:  # Skip very short paragraphs
                                    content['sections'].append({
                                        'type': 'paragraph',
                                        'text': text
                                    })
                            elif section.name in ['ul', 'ol']:
                                items = [li.get_text(strip=True) for li in section.find_all('li')]
                                if items:
                                    content['sections'].append({
                                        'type': 'list',
                                        'items': items
                                    })

                        # Get full text for main_content
                        content['main_content'] = main.get_text(strip=True)[:3000]
                        break

        except Exception as e:
            logger.error(f"Error extracting content from {url}: {e}")

        return content