"""
Website Analyzer - Extracts all business information from any website
"""

import re
import json
import logging
from urllib.parse import urlparse, urljoin
from bs4 import BeautifulSoup
import requests
from typing import Dict, List, Optional
from .smart_crawler import SmartCrawler

logger = logging.getLogger(__name__)


class WebsiteAnalyzer:
    """Analyzes websites and extracts business information"""

    def __init__(self):
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        })
        self.smart_crawler = SmartCrawler()

    def analyze(self, url: str) -> Dict:
        """
        Analyze a website and extract all relevant information

        Args:
            url: Website URL to analyze

        Returns:
            Dictionary containing all extracted information
        """
        logger.info(f"Analyzing website: {url}")

        # Normalize URL
        if not url.startswith(('http://', 'https://')):
            url = 'https://' + url

        analysis_data = {
            'url': url,
            'domain': urlparse(url).netloc,
            'pages': {},
            'business_info': {},
            'services': [],
            'team': [],
            'contact': {},
            'social_media': {},
            'content': {},
            'seo_data': {},
            'navigation': []
        }

        try:
            # Get homepage
            response = self.session.get(url, timeout=10)
            response.raise_for_status()
            soup = BeautifulSoup(response.content, 'html.parser')

            # Extract business information
            analysis_data['business_info'] = self._extract_business_info(soup, url)

            # Extract navigation structure
            analysis_data['navigation'] = self._extract_navigation(soup, url)

            # Extract contact information
            analysis_data['contact'] = self._extract_contact_info(soup)

            # Extract services
            analysis_data['services'] = self._extract_services(soup)

            # Extract team members
            analysis_data['team'] = self._extract_team_members(soup)

            # Extract SEO data
            analysis_data['seo_data'] = self._extract_seo_data(soup)

            # Extract social media links
            analysis_data['social_media'] = self._extract_social_links(soup)

            # Crawl important pages
            analysis_data['pages'] = self._crawl_pages(url, analysis_data['navigation'])

            # Use smart crawler to discover all pages and content
            logger.info("Starting smart crawl to discover all content...")
            try:
                discovered_pages = self.smart_crawler.discover_all_pages(url)

                # Extract detailed content from service pages
                if discovered_pages.get('services'):
                    logger.info(f"Analyzing {len(discovered_pages['services'])} service pages...")
                    enhanced_services = []
                    for service_url in discovered_pages['services'][:30]:  # Limit to 30 services
                        try:
                            service_content = self.smart_crawler.extract_page_content(service_url)
                            if service_content.get('title'):
                                enhanced_services.append({
                                    'title': service_content['title'],
                                    'url': service_url,
                                    'description': service_content.get('main_content', '')[:500],
                                    'sections': service_content.get('sections', [])[:5]
                                })
                        except Exception as e:
                            logger.debug(f"Error extracting service {service_url}: {e}")

                    # Replace or enhance existing services
                    if enhanced_services:
                        analysis_data['services'] = enhanced_services
                        logger.info(f"Found {len(enhanced_services)} services")

                # Extract detailed about content
                if discovered_pages.get('about'):
                    logger.info(f"Analyzing {len(discovered_pages['about'])} about pages...")
                    about_content = {}
                    for about_url in discovered_pages['about'][:5]:  # Analyze up to 5 about pages
                        try:
                            page_content = self.smart_crawler.extract_page_content(about_url)
                            if page_content.get('main_content'):
                                about_content[about_url] = page_content
                        except Exception as e:
                            logger.debug(f"Error extracting about page {about_url}: {e}")

                    if about_content:
                        analysis_data['content']['about_pages'] = about_content

                # Store all discovered pages for reference
                analysis_data['discovered_pages'] = discovered_pages
                logger.info(f"Total pages discovered: {sum(len(v) for v in discovered_pages.values())}")

            except Exception as e:
                logger.warning(f"Smart crawl failed, using basic extraction: {e}")

        except Exception as e:
            logger.error(f"Error analyzing website: {str(e)}")
            analysis_data['error'] = str(e)

        return analysis_data

    def _extract_business_info(self, soup: BeautifulSoup, url: str) -> Dict:
        """Extract business name, tagline, and basic info"""
        business_info = {}
        potential_names = []

        # Try to find business name from multiple sources
        # 1. Check site title
        title_tag = soup.find('title')
        if title_tag:
            cleaned_name = self._clean_business_name(title_tag.text)
            if cleaned_name:
                potential_names.append(cleaned_name)
                business_info['name'] = cleaned_name

        # 2. Check logo alt text
        logo = soup.find('img', {'class': re.compile(r'logo|brand', re.I)})
        if not logo:
            logo = soup.find('img', {'id': re.compile(r'logo|brand', re.I)})
        if logo and logo.get('alt'):
            business_info['logo_alt'] = logo['alt']
            # If alt text looks like a business name, prefer it
            if logo['alt'] and len(logo['alt']) > 3 and not logo['alt'].lower().startswith('logo'):
                potential_names.insert(0, logo['alt'])

        # 3. Check header/brand text
        brand_elem = soup.find(['div', 'span', 'a', 'h1'], {'class': re.compile(r'brand|logo|site-title|company-name', re.I)})
        if brand_elem:
            brand_text = brand_elem.get_text(strip=True)
            business_info['brand_text'] = brand_text
            if brand_text and len(brand_text) > 3:
                potential_names.insert(0, brand_text)

        # 4. Check Open Graph site_name
        og_site_name = soup.find('meta', {'property': 'og:site_name'})
        if og_site_name and og_site_name.get('content'):
            potential_names.insert(0, og_site_name['content'])

        # 5. Check schema.org data
        schema_script = soup.find('script', type='application/ld+json')
        if schema_script:
            try:
                schema_data = json.loads(schema_script.string)
                if isinstance(schema_data, dict) and schema_data.get('name'):
                    potential_names.insert(0, schema_data['name'])
            except:
                pass

        # Use the best name found
        if potential_names:
            business_info['name'] = potential_names[0]

        # Extract tagline
        tagline_elem = soup.find(['p', 'div', 'span'], {'class': re.compile(r'tagline|slogan|subtitle', re.I)})
        if tagline_elem:
            business_info['tagline'] = tagline_elem.get_text(strip=True)

        # Extract about text
        about_section = soup.find(['section', 'div'], {'class': re.compile(r'about', re.I)})
        if not about_section:
            about_section = soup.find(['section', 'div'], {'id': re.compile(r'about', re.I)})
        if about_section:
            about_text = about_section.get_text(strip=True)[:500]  # First 500 chars
            business_info['about_summary'] = about_text

        # Extract business hours if present
        hours_elem = soup.find(['div', 'section'], text=re.compile(r'hours|opening|open', re.I))
        if hours_elem:
            parent = hours_elem.parent
            if parent:
                hours_text = parent.get_text(strip=True)
                business_info['hours_raw'] = hours_text

        return business_info

    def _extract_navigation(self, soup: BeautifulSoup, base_url: str) -> List[Dict]:
        """Extract navigation structure"""
        nav_items = []

        # Find main navigation
        nav = soup.find('nav')
        if not nav:
            nav = soup.find(['div', 'ul'], {'class': re.compile(r'nav|menu', re.I)})

        if nav:
            links = nav.find_all('a')
            for link in links:
                href = link.get('href', '')
                if href and not href.startswith('#'):
                    nav_items.append({
                        'label': link.get_text(strip=True),
                        'url': urljoin(base_url, href),
                        'relative_url': href
                    })

        return nav_items

    def _extract_contact_info(self, soup: BeautifulSoup) -> Dict:
        """Extract contact information"""
        contact = {}

        # Phone numbers
        phone_patterns = [
            r'\+?\d{1,4}[\s.-]?\(?\d{1,4}\)?[\s.-]?\d{1,4}[\s.-]?\d{1,4}',
            r'\(\d{3}\)\s*\d{3}-\d{4}',
            r'\d{3}[-.\s]?\d{3}[-.\s]?\d{4}'
        ]

        for pattern in phone_patterns:
            phone_match = soup.find(text=re.compile(pattern))
            if phone_match:
                phone = re.search(pattern, phone_match).group()
                contact['phone'] = phone
                break

        # Email addresses
        email_pattern = r'\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b'
        email_match = soup.find(text=re.compile(email_pattern))
        if email_match:
            email = re.search(email_pattern, email_match).group()
            contact['email'] = email

        # Try mailto links
        mailto = soup.find('a', href=re.compile(r'^mailto:'))
        if mailto:
            contact['email'] = mailto['href'].replace('mailto:', '')

        # Address
        address_elem = soup.find(['address', 'div', 'p'], {'class': re.compile(r'address', re.I)})
        if address_elem:
            contact['address'] = address_elem.get_text(strip=True)

        # Try schema.org markup
        schema_script = soup.find('script', type='application/ld+json')
        if schema_script:
            try:
                schema_data = json.loads(schema_script.string)
                if isinstance(schema_data, dict):
                    if 'address' in schema_data:
                        contact['address_structured'] = schema_data['address']
                    if 'telephone' in schema_data:
                        contact['phone'] = schema_data['telephone']
                    if 'email' in schema_data:
                        contact['email'] = schema_data['email']
            except:
                pass

        return contact

    def _extract_services(self, soup: BeautifulSoup) -> List[Dict]:
        """Extract services or products offered"""
        services = []

        # Look for services section
        services_section = soup.find(['section', 'div'], {'class': re.compile(r'service', re.I)})
        if not services_section:
            services_section = soup.find(['section', 'div'], {'id': re.compile(r'service', re.I)})

        if services_section:
            # Find service items
            service_items = services_section.find_all(['div', 'article'], {'class': re.compile(r'service|card|item', re.I)})

            for item in service_items[:10]:  # Limit to 10 services
                service = {}

                # Get title
                title = item.find(['h2', 'h3', 'h4'])
                if title:
                    service['title'] = title.get_text(strip=True)

                # Get description
                desc = item.find(['p', 'div'], {'class': re.compile(r'desc|text|content', re.I)})
                if desc:
                    service['description'] = desc.get_text(strip=True)[:200]

                if service.get('title'):
                    services.append(service)

        # Fallback: look for list items with service-like content
        if not services:
            lists = soup.find_all('ul')
            for ul in lists:
                parent_text = ''
                if ul.parent:
                    prev = ul.find_previous_sibling(['h2', 'h3', 'h4'])
                    if prev:
                        parent_text = prev.get_text(strip=True).lower()

                if 'service' in parent_text or 'offer' in parent_text or 'practice' in parent_text:
                    items = ul.find_all('li')
                    for item in items[:10]:
                        services.append({
                            'title': item.get_text(strip=True),
                            'description': ''
                        })
                    break

        return services

    def _extract_team_members(self, soup: BeautifulSoup) -> List[Dict]:
        """Extract team member information"""
        team = []

        # Look for team section
        team_section = soup.find(['section', 'div'], {'class': re.compile(r'team|staff|people|attorney|lawyer', re.I)})
        if not team_section:
            team_section = soup.find(['section', 'div'], {'id': re.compile(r'team|staff|people', re.I)})

        if team_section:
            # Find team member cards
            member_items = team_section.find_all(['div', 'article'], {'class': re.compile(r'member|person|profile|card', re.I)})

            for item in member_items[:20]:  # Limit to 20 members
                member = {}

                # Get name
                name = item.find(['h2', 'h3', 'h4'])
                if name:
                    member['name'] = name.get_text(strip=True)

                # Get title/position
                title = item.find(['p', 'span', 'div'], {'class': re.compile(r'title|position|role', re.I)})
                if title:
                    member['title'] = title.get_text(strip=True)

                # Get bio
                bio = item.find(['p', 'div'], {'class': re.compile(r'bio|desc|text', re.I)})
                if bio:
                    member['bio'] = bio.get_text(strip=True)[:300]

                if member.get('name'):
                    team.append(member)

        return team

    def _extract_seo_data(self, soup: BeautifulSoup) -> Dict:
        """Extract SEO-related data"""
        seo_data = {}

        # Title
        title = soup.find('title')
        if title:
            seo_data['title'] = title.text.strip()

        # Meta description
        meta_desc = soup.find('meta', {'name': 'description'})
        if meta_desc:
            seo_data['description'] = meta_desc.get('content', '')

        # Meta keywords
        meta_keywords = soup.find('meta', {'name': 'keywords'})
        if meta_keywords:
            seo_data['keywords'] = meta_keywords.get('content', '')

        # Open Graph data
        og_data = {}
        og_tags = soup.find_all('meta', {'property': re.compile(r'^og:')})
        for tag in og_tags:
            prop = tag.get('property', '').replace('og:', '')
            og_data[prop] = tag.get('content', '')
        if og_data:
            seo_data['open_graph'] = og_data

        # H1 tags
        h1_tags = soup.find_all('h1')
        seo_data['h1_tags'] = [h1.get_text(strip=True) for h1 in h1_tags[:3]]

        # Count headings
        seo_data['heading_structure'] = {
            'h1': len(soup.find_all('h1')),
            'h2': len(soup.find_all('h2')),
            'h3': len(soup.find_all('h3'))
        }

        return seo_data

    def _extract_social_links(self, soup: BeautifulSoup) -> Dict:
        """Extract social media links"""
        social = {}

        social_patterns = {
            'facebook': r'facebook\.com',
            'twitter': r'twitter\.com|x\.com',
            'linkedin': r'linkedin\.com',
            'instagram': r'instagram\.com',
            'youtube': r'youtube\.com',
            'pinterest': r'pinterest\.com'
        }

        for platform, pattern in social_patterns.items():
            link = soup.find('a', href=re.compile(pattern, re.I))
            if link:
                social[platform] = link.get('href')

        return social

    def _crawl_pages(self, base_url: str, navigation: List[Dict]) -> Dict:
        """Crawl important pages from navigation"""
        pages = {}

        # Limit crawling to most important pages
        important_keywords = ['about', 'service', 'contact', 'team', 'practice', 'product']

        for nav_item in navigation[:10]:  # Limit to 10 pages
            url = nav_item['url']
            label = nav_item['label'].lower()

            # Check if this is an important page
            if any(keyword in label for keyword in important_keywords):
                try:
                    response = self.session.get(url, timeout=10)
                    if response.status_code == 200:
                        page_soup = BeautifulSoup(response.content, 'html.parser')
                        pages[label] = {
                            'url': url,
                            'title': page_soup.find('title').text if page_soup.find('title') else '',
                            'content': self._extract_main_content(page_soup)
                        }
                except Exception as e:
                    logger.warning(f"Could not crawl page {url}: {str(e)}")

        return pages

    def _extract_main_content(self, soup: BeautifulSoup) -> str:
        """Extract main content from a page"""
        # Remove script and style elements
        for script in soup(['script', 'style']):
            script.decompose()

        # Try to find main content area
        main = soup.find('main')
        if not main:
            main = soup.find(['div', 'section'], {'class': re.compile(r'content|main', re.I)})
        if not main:
            main = soup.find('article')
        if not main:
            main = soup.body

        if main:
            text = main.get_text(separator=' ', strip=True)
            # Limit to reasonable length
            return text[:3000]

        return ''

    def _clean_business_name(self, title: str) -> str:
        """Clean business name from title tag"""
        # Try to extract business name from common patterns
        # Pattern 1: "Description | Business Name"
        if '|' in title:
            parts = title.split('|')
            # Often the business name is the last part
            if len(parts) >= 2:
                # Check if last part looks like a business name (has proper capitalization)
                last_part = parts[-1].strip()
                if last_part and not last_part.islower():
                    return last_part
                # Otherwise try the first part
                return parts[0].strip()

        # Pattern 2: "Business Name - Description"
        elif '-' in title:
            parts = title.split('-')
            # Usually business name is first
            return parts[0].strip()

        # Pattern 3: "Business Name • Description"
        elif '•' in title or '–' in title:
            title = re.sub(r'\s*[•–]\s*.*$', '', title)

        title = re.sub(r'\s+', ' ', title)
        return title.strip()