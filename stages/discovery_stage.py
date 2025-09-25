"""
Discovery Stage - Comprehensive website analysis and content extraction
"""

import json
import logging
from pathlib import Path
from typing import Dict, List, Optional
import re
from urllib.parse import urlparse

logger = logging.getLogger(__name__)


class DiscoveryStage:
    """Handles the discovery and analysis phase of website building"""

    def __init__(self, project_dir: Path):
        self.project_dir = project_dir
        self.output_dir = project_dir / 'discovery'
        self.output_dir.mkdir(exist_ok=True)

    def run(self, input_data: Dict) -> Dict:
        """Run the discovery stage"""
        url = input_data.get('url')
        if not url:
            raise ValueError("URL is required for discovery stage")

        logger.info(f"Starting discovery for: {url}")

        # Import analyzers
        from analyzer.website_analyzer import WebsiteAnalyzer
        from analyzer.smart_crawler import SmartCrawler
        from analyzer.business_detector import BusinessDetector

        # Initialize analyzers
        analyzer = WebsiteAnalyzer()
        crawler = SmartCrawler()
        detector = BusinessDetector()

        # Perform comprehensive analysis
        analysis_data = analyzer.analyze(url)
        business_type = detector.detect(analysis_data)

        # Enhanced service discovery
        services = self._enhance_service_discovery(analysis_data, crawler)

        # Categorize services
        service_taxonomy = self._categorize_services(services, business_type)

        # Extract content depth
        content_analysis = self._analyze_content_depth(analysis_data)

        # Identify content patterns
        content_patterns = self._identify_content_patterns(analysis_data)

        # Compile discovery output
        discovery_output = {
            'url': url,
            'business_info': analysis_data.get('business_info', {}),
            'business_type': business_type,
            'contact': analysis_data.get('contact', {}),
            'services': services,
            'service_taxonomy': service_taxonomy,
            'pages': analysis_data.get('pages', {}),
            'discovered_pages': analysis_data.get('discovered_pages', {}),
            'navigation': analysis_data.get('navigation', []),
            'seo_data': analysis_data.get('seo_data', {}),
            'content_analysis': content_analysis,
            'content_patterns': content_patterns,
            'team': analysis_data.get('team', []),
            'social_media': analysis_data.get('social_media', {})
        }

        # Generate discovery report
        self._generate_discovery_report(discovery_output)

        # Save output
        output_file = self.output_dir / 'discovery_output.json'
        with open(output_file, 'w') as f:
            json.dump(discovery_output, f, indent=2)

        return discovery_output

    def _enhance_service_discovery(self, analysis_data: Dict, crawler) -> List[Dict]:
        """Enhanced service discovery with detailed extraction"""
        services = []
        seen_services = set()

        # 1. Get services from initial analysis
        for service in analysis_data.get('services', []):
            service_name = service.get('title', '').strip()
            if service_name and service_name not in seen_services:
                services.append({
                    'name': service_name,
                    'description': service.get('description', ''),
                    'url': service.get('url', ''),
                    'source': 'initial_analysis',
                    'content': service.get('content', ''),
                    'sections': service.get('sections', [])
                })
                seen_services.add(service_name)

        # 2. Extract from discovered service pages
        discovered_pages = analysis_data.get('discovered_pages', {})
        if discovered_pages.get('services'):
            for service_url in discovered_pages['services']:
                try:
                    # Extract detailed content from each service page
                    page_content = crawler.extract_page_content(service_url)
                    if page_content.get('title'):
                        service_name = self._clean_service_name(page_content['title'])
                        if service_name not in seen_services:
                            services.append({
                                'name': service_name,
                                'description': page_content.get('description', ''),
                                'url': service_url,
                                'source': 'crawled_page',
                                'content': page_content.get('main_content', ''),
                                'sections': page_content.get('sections', [])
                            })
                            seen_services.add(service_name)
                except Exception as e:
                    logger.debug(f"Error extracting service from {service_url}: {e}")

        # 3. Parse navigation for service mentions
        for nav_item in analysis_data.get('navigation', []):
            label = nav_item.get('label', '').strip()
            if self._is_service_related(label) and label not in seen_services:
                services.append({
                    'name': label,
                    'description': '',
                    'url': nav_item.get('url', ''),
                    'source': 'navigation',
                    'content': '',
                    'sections': []
                })
                seen_services.add(label)

        # 4. Extract from page content
        for page_key, page_data in analysis_data.get('pages', {}).items():
            content = page_data.get('content', '')
            # Look for service lists in content
            service_mentions = self._extract_service_mentions(content)
            for service_name in service_mentions:
                if service_name not in seen_services:
                    services.append({
                        'name': service_name,
                        'description': '',
                        'url': '',
                        'source': 'content_extraction',
                        'content': '',
                        'sections': []
                    })
                    seen_services.add(service_name)

        logger.info(f"Enhanced service discovery found {len(services)} services")
        return services

    def _categorize_services(self, services: List[Dict], business_type: str) -> Dict:
        """Categorize services into a hierarchical taxonomy"""
        taxonomy = {}

        if business_type == 'law_firm':
            # Law firm categories
            categories = {
                'Criminal Law': ['criminal', 'dui', 'drug', 'assault', 'theft', 'felony', 'misdemeanor'],
                'Family Law': ['divorce', 'custody', 'child', 'adoption', 'alimony', 'family'],
                'Personal Injury': ['injury', 'accident', 'medical malpractice', 'slip', 'fall', 'negligence'],
                'Estate Planning': ['estate', 'will', 'trust', 'probate', 'inheritance'],
                'Business Law': ['business', 'corporate', 'contract', 'partnership', 'llc', 'incorporation'],
                'Real Estate': ['real estate', 'property', 'landlord', 'tenant', 'closing'],
                'Immigration': ['immigration', 'visa', 'citizenship', 'deportation', 'asylum']
            }
        elif business_type == 'medical_practice':
            categories = {
                'Primary Care': ['general', 'checkup', 'physical', 'wellness', 'preventive'],
                'Specialized Care': ['cardiology', 'dermatology', 'orthopedic', 'neurology'],
                'Diagnostic Services': ['x-ray', 'mri', 'blood test', 'screening', 'diagnostic'],
                'Treatment Services': ['surgery', 'therapy', 'rehabilitation', 'treatment']
            }
        else:
            # Generic categories
            categories = {
                'Core Services': [],
                'Additional Services': [],
                'Support Services': []
            }

        # Categorize each service
        for service in services:
            service_name_lower = service['name'].lower()
            categorized = False

            for category, keywords in categories.items():
                if any(keyword in service_name_lower for keyword in keywords):
                    if category not in taxonomy:
                        taxonomy[category] = []
                    taxonomy[category].append(service)
                    categorized = True
                    break

            # If not categorized, put in general category
            if not categorized:
                if 'General Services' not in taxonomy:
                    taxonomy['General Services'] = []
                taxonomy['General Services'].append(service)

        return taxonomy

    def _analyze_content_depth(self, analysis_data: Dict) -> Dict:
        """Analyze the depth and quality of existing content"""
        content_analysis = {
            'total_pages': 0,
            'pages_with_content': 0,
            'average_content_length': 0,
            'content_quality_scores': {},
            'missing_content': []
        }

        total_content_length = 0
        pages_analyzed = 0

        # Analyze main pages
        for page_key, page_data in analysis_data.get('pages', {}).items():
            content = page_data.get('content', '')
            content_analysis['total_pages'] += 1

            if content:
                content_analysis['pages_with_content'] += 1
                content_length = len(content)
                total_content_length += content_length
                pages_analyzed += 1

                # Score content quality
                quality_score = self._score_content_quality(content)
                content_analysis['content_quality_scores'][page_key] = quality_score

                # Check for missing elements
                if 'testimonial' not in content.lower():
                    content_analysis['missing_content'].append(f"{page_key}: No testimonials")
                if 'faq' not in content.lower() and 'frequently' not in content.lower():
                    content_analysis['missing_content'].append(f"{page_key}: No FAQs")

        # Calculate average
        if pages_analyzed > 0:
            content_analysis['average_content_length'] = total_content_length // pages_analyzed

        return content_analysis

    def _identify_content_patterns(self, analysis_data: Dict) -> Dict:
        """Identify content patterns and structure"""
        patterns = {
            'has_blog': False,
            'has_case_studies': False,
            'has_testimonials': False,
            'has_team_profiles': bool(analysis_data.get('team')),
            'has_service_pages': False,
            'has_location_pages': False,
            'content_style': 'unknown',
            'common_sections': []
        }

        # Check discovered pages
        discovered = analysis_data.get('discovered_pages', {})
        all_urls = []
        for category_urls in discovered.values():
            all_urls.extend(category_urls)

        # Analyze URL patterns
        for url in all_urls:
            url_lower = url.lower()
            if 'blog' in url_lower or 'news' in url_lower or 'article' in url_lower:
                patterns['has_blog'] = True
            if 'case-study' in url_lower or 'portfolio' in url_lower or 'project' in url_lower:
                patterns['has_case_studies'] = True
            if 'testimonial' in url_lower or 'review' in url_lower:
                patterns['has_testimonials'] = True
            if 'location' in url_lower or 'office' in url_lower:
                patterns['has_location_pages'] = True

        patterns['has_service_pages'] = bool(discovered.get('services'))

        # Analyze content style
        sample_content = ''
        for page_data in list(analysis_data.get('pages', {}).values())[:3]:
            sample_content += page_data.get('content', '')

        if sample_content:
            if len(sample_content) > 5000:
                patterns['content_style'] = 'detailed'
            elif len(sample_content) > 2000:
                patterns['content_style'] = 'moderate'
            else:
                patterns['content_style'] = 'concise'

        return patterns

    def _clean_service_name(self, title: str) -> str:
        """Clean and normalize service name"""
        # Remove common suffixes
        title = re.sub(r'\s*[\||\-|•|–]\s*.*$', '', title)
        # Remove "Services" or "Service" at the end
        title = re.sub(r'\s+[Ss]ervices?$', '', title)
        # Clean up
        title = re.sub(r'\s+', ' ', title)
        return title.strip()

    def _is_service_related(self, text: str) -> bool:
        """Check if text is likely service-related"""
        service_keywords = [
            'service', 'practice', 'solution', 'consulting',
            'treatment', 'therapy', 'program', 'assistance'
        ]
        text_lower = text.lower()
        return any(keyword in text_lower for keyword in service_keywords)

    def _extract_service_mentions(self, content: str) -> List[str]:
        """Extract service mentions from content"""
        services = []

        # Look for common patterns like "Our services include:"
        patterns = [
            r'services include[:\s]+([^.]+)',
            r'we offer[:\s]+([^.]+)',
            r'our practice areas[:\s]+([^.]+)',
            r'specializing in[:\s]+([^.]+)'
        ]

        for pattern in patterns:
            matches = re.findall(pattern, content, re.IGNORECASE)
            for match in matches:
                # Split by commas or "and"
                items = re.split(r',|and', match)
                for item in items:
                    service = item.strip()
                    if service and len(service) < 50:  # Reasonable length
                        services.append(service)

        return services

    def _score_content_quality(self, content: str) -> int:
        """Score content quality from 0-100"""
        score = 0

        # Length score (max 30 points)
        length = len(content)
        if length > 3000:
            score += 30
        elif length > 1500:
            score += 20
        elif length > 500:
            score += 10

        # Structure score (max 30 points)
        if '<h2>' in content or '##' in content:
            score += 10
        if '<h3>' in content or '###' in content:
            score += 10
        if '<ul>' in content or '<ol>' in content:
            score += 10

        # Content elements (max 40 points)
        if 'testimonial' in content.lower() or 'review' in content.lower():
            score += 10
        if 'faq' in content.lower() or 'question' in content.lower():
            score += 10
        if 'contact' in content.lower() or 'call' in content.lower():
            score += 10
        if 'about' in content.lower() or 'experience' in content.lower():
            score += 10

        return min(score, 100)

    def _generate_discovery_report(self, discovery_output: Dict):
        """Generate a human-readable discovery report"""
        report_file = self.output_dir / 'discovery_report.md'

        report = []
        report.append("# Discovery Report")
        report.append(f"\n**URL:** {discovery_output['url']}")
        report.append(f"**Business Name:** {discovery_output['business_info'].get('name', 'Unknown')}")
        report.append(f"**Business Type:** {discovery_output['business_type']}")

        report.append("\n## Services Found")
        report.append(f"Total services discovered: {len(discovery_output['services'])}")

        if discovery_output['service_taxonomy']:
            report.append("\n### Service Categories")
            for category, services in discovery_output['service_taxonomy'].items():
                report.append(f"\n**{category}** ({len(services)} services)")
                for service in services[:5]:  # Show first 5
                    report.append(f"- {service['name']}")
                if len(services) > 5:
                    report.append(f"  ... and {len(services) - 5} more")

        report.append("\n## Content Analysis")
        analysis = discovery_output['content_analysis']
        report.append(f"- Total pages: {analysis['total_pages']}")
        report.append(f"- Pages with content: {analysis['pages_with_content']}")
        report.append(f"- Average content length: {analysis['average_content_length']} characters")

        if analysis['missing_content']:
            report.append("\n### Missing Content")
            for missing in analysis['missing_content'][:10]:
                report.append(f"- {missing}")

        report.append("\n## Content Patterns")
        patterns = discovery_output['content_patterns']
        report.append(f"- Has blog: {patterns['has_blog']}")
        report.append(f"- Has testimonials: {patterns['has_testimonials']}")
        report.append(f"- Has team profiles: {patterns['has_team_profiles']}")
        report.append(f"- Content style: {patterns['content_style']}")

        report.append("\n## Contact Information")
        contact = discovery_output['contact']
        report.append(f"- Phone: {contact.get('phone', 'Not found')}")
        report.append(f"- Email: {contact.get('email', 'Not found')}")
        report.append(f"- Address: {contact.get('address', 'Not found')}")

        with open(report_file, 'w') as f:
            f.write('\n'.join(report))

        logger.info(f"Discovery report generated: {report_file}")