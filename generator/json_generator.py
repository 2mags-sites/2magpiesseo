"""
JSON Generator - Creates all required JSON data files from analyzed website data
"""

import json
import os
import re
import logging
from pathlib import Path
from typing import Dict, List, Optional
from datetime import datetime
import sys
sys.path.append(str(Path(__file__).parent.parent))
from enhancer.ai_content_generator import AIContentGenerator

logger = logging.getLogger(__name__)


class JSONGenerator:
    """Generates JSON data files for the PHP website builder"""

    def __init__(self):
        self.generated_files = []
        self.ai_generator = AIContentGenerator()

    def generate(self, analysis_data: Dict, keywords: List[str] = None, output_dir: Path = None) -> Dict:
        """
        Generate all JSON files from analysis data

        Args:
            analysis_data: Analyzed website data
            keywords: Target SEO keywords
            output_dir: Output directory for JSON files

        Returns:
            Dictionary containing all generated JSON data
        """
        logger.info("Generating JSON data files...")

        if output_dir is None:
            output_dir = Path('output/data')

        # Create directories
        universal_dir = output_dir / 'universal'
        pages_dir = output_dir / 'pages'
        universal_dir.mkdir(parents=True, exist_ok=True)
        pages_dir.mkdir(parents=True, exist_ok=True)

        # Generate universal data files
        universal_data = {
            'business': self._generate_business_json(analysis_data),
            'navigation': self._generate_navigation_json(analysis_data),
            'team': self._generate_team_json(analysis_data),
            'contact': self._generate_contact_json(analysis_data),
            'social': self._generate_social_json(analysis_data)
        }

        # Save universal files
        for filename, data in universal_data.items():
            filepath = universal_dir / f"{filename}.json"
            with open(filepath, 'w', encoding='utf-8') as f:
                json.dump(data, f, indent=2, ensure_ascii=False)
            self.generated_files.append(filepath)
            logger.info(f"Generated: {filepath}")

        # Generate page-specific data
        pages_data = {}

        # Always generate homepage
        homepage_data = self._generate_homepage_json(analysis_data)
        pages_data['index'] = homepage_data
        self._save_page_json(pages_dir / 'index.json', homepage_data)

        # Generate about page
        about_data = self._generate_about_json(analysis_data)
        pages_data['about'] = about_data
        self._save_page_json(pages_dir / 'about.json', about_data)

        # Generate contact page
        contact_page_data = self._generate_contact_page_json(analysis_data)
        pages_data['contact'] = contact_page_data
        self._save_page_json(pages_dir / 'contact.json', contact_page_data)

        # Generate service pages based on keywords or detected services
        if keywords:
            for keyword in keywords[:15]:  # Limit to 15 service pages
                page_name = self._slugify(keyword)
                service_data = self._generate_service_page_json(
                    analysis_data,
                    keyword,
                    page_name
                )
                pages_data[page_name] = service_data
                self._save_page_json(pages_dir / f"{page_name}.json", service_data)
        else:
            # Generate based on detected services
            for i, service in enumerate(analysis_data.get('services', [])[:10]):
                page_name = self._slugify(service.get('title', f'service-{i+1}'))
                service_data = self._generate_service_page_json(
                    analysis_data,
                    service.get('title', ''),
                    page_name
                )
                pages_data[page_name] = service_data
                self._save_page_json(pages_dir / f"{page_name}.json", service_data)

        return {
            'universal': universal_data,
            'pages': pages_data
        }

    def _generate_business_json(self, analysis_data: Dict) -> Dict:
        """Generate business.json file"""
        business_info = analysis_data.get('business_info', {})
        contact = analysis_data.get('contact', {})

        # Extract business name
        name = (business_info.get('name') or
                business_info.get('brand_text') or
                analysis_data.get('domain', 'Business Name'))

        # Parse address if available
        address = self._parse_address(contact.get('address', ''))

        return {
            "name": name,
            "tagline": business_info.get('tagline', 'Professional Services You Can Trust'),
            "founded": None,  # Could be extracted with more sophisticated parsing
            "contact": {
                "phone": contact.get('phone', ''),
                "email": contact.get('email', ''),
                "address": address
            },
            "hours": self._parse_hours(business_info.get('hours_raw', '')),
            "languages": ["English"],  # Default, could be detected
            "certifications": [],  # Would need specific extraction
            "service_areas": self._extract_service_areas(analysis_data),
            "description": business_info.get('about_summary', '')
        }

    def _generate_navigation_json(self, analysis_data: Dict) -> Dict:
        """Generate navigation.json file"""
        # Start with essential pages that we always generate
        primary_nav = [
            {
                "label": "Home",
                "url": "/",
                "active_pages": ["home", "index"]
            },
            {
                "label": "About",
                "url": "/about",
                "active_pages": ["about"]
            }
        ]

        # Add services if we have any
        services = analysis_data.get('services', [])
        if services:
            # Create a services dropdown with actual generated service pages
            dropdown_items = []
            for service in services[:8]:  # Limit dropdown items
                service_slug = self._slugify(service.get('title', 'service'))
                dropdown_items.append({
                    "label": service.get('title', 'Service'),
                    "url": f"/{service_slug}"
                })

            primary_nav.append({
                "label": "Services",
                "url": f"/{self._slugify(services[0].get('title', 'services'))}",
                "active_pages": ["services"],
                "dropdown": dropdown_items if len(dropdown_items) > 1 else None
            })

        # Always add contact
        primary_nav.append({
            "label": "Contact",
            "url": "/contact",
            "active_pages": ["contact"]
        })

        return {
            "primary_nav": primary_nav,
            "cta_button": {
                "label": "Get Started",
                "url": "/contact",
                "class": "btn-primary"
            }
        }

    def _generate_team_json(self, analysis_data: Dict) -> Dict:
        """Generate team.json file"""
        team_members = analysis_data.get('team', [])

        members = []
        for member in team_members:
            member_data = {
                "id": self._slugify(member.get('name', 'team-member')),
                "name": member.get('name', 'Team Member'),
                "title": member.get('title', 'Professional'),
                "bio": member.get('bio', ''),
                "image": f"assets/images/team/{self._slugify(member.get('name', 'placeholder'))}.jpg",
                "experience_years": None,
                "education": [],
                "languages": ["English"],
                "specialties": self._extract_specialties(member.get('bio', '')),
            }
            members.append(member_data)

        return {"members": members}

    def _generate_contact_json(self, analysis_data: Dict) -> Dict:
        """Generate contact.json file"""
        contact = analysis_data.get('contact', {})

        return {
            "phone": contact.get('phone', ''),
            "email": contact.get('email', ''),
            "address": self._parse_address(contact.get('address', '')),
            "hours": self._parse_hours(analysis_data.get('business_info', {}).get('hours_raw', '')),
            "response_time": "24 hours",
            "emergency_available": False
        }

    def _generate_social_json(self, analysis_data: Dict) -> Dict:
        """Generate social.json file"""
        social = analysis_data.get('social_media', {})

        return {
            "facebook": social.get('facebook', ''),
            "twitter": social.get('twitter', ''),
            "linkedin": social.get('linkedin', ''),
            "instagram": social.get('instagram', ''),
            "youtube": social.get('youtube', ''),
            "pinterest": social.get('pinterest', '')
        }

    def _generate_homepage_json(self, analysis_data: Dict) -> Dict:
        """Generate homepage JSON data"""
        business_info = analysis_data.get('business_info', {})
        seo_data = analysis_data.get('seo_data', {})

        return {
            "meta": {
                "title": seo_data.get('title', business_info.get('name', 'Welcome')),
                "description": seo_data.get('description', business_info.get('tagline', '')),
                "keywords": self._extract_keywords(seo_data),
                "canonical_url": "/"
            },
            "content": {
                "hero": {
                    "title": business_info.get('name', 'Welcome'),
                    "subtitle": business_info.get('tagline', 'Professional Services You Can Trust'),
                    "cta_primary": "Get Started",
                    "cta_secondary": "Learn More"
                },
                "features": self._generate_features(analysis_data),
                "services_overview": self._generate_services_overview(analysis_data),
                "why_choose": self._generate_why_choose(analysis_data),
                "testimonials": []  # Would need specific extraction
            }
        }

    def _generate_about_json(self, analysis_data: Dict) -> Dict:
        """Generate about page JSON data using AI content generator"""
        business_info = analysis_data.get('business_info', {})
        business_type = self._determine_business_type(analysis_data)

        # Use AI to generate comprehensive about page content
        ai_content = self.ai_generator.generate_about_content(
            business_info=business_info,
            team_members=analysis_data.get('team', []),
            business_type=business_type
        )

        return {
            "meta": {
                "title": f"About {business_info.get('name', 'Us')}",
                "description": ai_content.get('overview', {}).get('description',
                                                                   f"Learn about {business_info.get('name', 'our company')} and our commitment to excellence.")[:160],
                "keywords": ["about", business_info.get('name', ''), "company", "team"],
                "canonical_url": "/about"
            },
            "content": ai_content
        }

    def _generate_contact_page_json(self, analysis_data: Dict) -> Dict:
        """Generate contact page JSON data"""
        contact = analysis_data.get('contact', {})
        business_info = analysis_data.get('business_info', {})

        return {
            "meta": {
                "title": f"Contact {business_info.get('name', 'Us')}",
                "description": f"Get in touch with {business_info.get('name', 'us')}. Call {contact.get('phone', '')} or email us.",
                "keywords": ["contact", business_info.get('name', ''), "phone", "email", "location"],
                "canonical_url": "/contact"
            },
            "content": {
                "hero": {
                    "title": "Contact Us",
                    "subtitle": "We're here to help"
                },
                "contact_info": {
                    "phone": contact.get('phone', ''),
                    "email": contact.get('email', ''),
                    "address": contact.get('address', ''),
                    "hours": analysis_data.get('business_info', {}).get('hours_raw', 'Monday-Friday 9AM-5PM')
                },
                "contact_form": {
                    "enabled": True,
                    "fields": ["name", "email", "phone", "message"],
                    "submit_text": "Send Message"
                },
                "map": {
                    "enabled": False,  # Would need API key
                    "latitude": None,
                    "longitude": None
                }
            }
        }

    def _generate_service_page_json(self, analysis_data: Dict, keyword: str, page_name: str) -> Dict:
        """Generate service page JSON data using AI content generator"""
        business_info = analysis_data.get('business_info', {})
        business_type = self._determine_business_type(analysis_data)

        # Use AI to generate comprehensive service content
        ai_content = self.ai_generator.generate_service_content(
            service_name=keyword,
            business_info=business_info,
            business_type=business_type
        )

        # Generate title and description based on keyword
        title = f"{keyword.title()} | {business_info.get('name', 'Professional Services')}"
        description = ai_content.get('overview', {}).get('main_description',
                                                         f"Expert {keyword} services. Contact us today.")[:160]

        return {
            "meta": {
                "title": title,
                "description": description,
                "keywords": self._generate_related_keywords(keyword),
                "canonical_url": f"/{page_name}"
            },
            "schema": {
                "type": "Service",
                "service_type": keyword.title(),
                "provider": business_info.get('name', ''),
                "area_served": self._extract_service_areas(analysis_data)
            },
            "content": ai_content,
            "faq": ai_content.get('faqs', [])
        }

    def _generate_service_details(self, keyword: str, business_type: str) -> List[Dict]:
        """Generate service details for a service page"""
        return [
            {
                "title": f"Comprehensive {keyword.title()}",
                "description": f"Our {keyword} service provides complete solutions tailored to your specific needs.",
                "features": [
                    "Personalized approach",
                    "Expert consultation",
                    "Proven methodology",
                    "Ongoing support"
                ]
            },
            {
                "title": f"{keyword.title()} Consultation",
                "description": "Get expert advice and guidance from our experienced professionals.",
                "features": [
                    "Initial assessment",
                    "Strategy development",
                    "Implementation planning",
                    "Follow-up support"
                ]
            }
        ]

    def _generate_service_why_choose(self, keyword: str, business_info: Dict) -> Dict:
        """Generate why choose section for service page"""
        return {
            "title": f"Why Choose {business_info.get('name', 'Us')} for {keyword.title()}",
            "points": [
                {
                    "title": "Experienced Professionals",
                    "description": f"Our team has extensive experience in {keyword} services."
                },
                {
                    "title": "Proven Results",
                    "description": "We have a track record of delivering successful outcomes."
                },
                {
                    "title": "Personalized Service",
                    "description": "We tailor our approach to meet your specific needs."
                },
                {
                    "title": "Competitive Pricing",
                    "description": "Quality service at fair and transparent prices."
                }
            ]
        }

    def _generate_service_process(self, keyword: str, business_type: str) -> Dict:
        """Generate process steps for service page"""
        return {
            "title": f"Our {keyword.title()} Process",
            "steps": [
                {
                    "step": 1,
                    "title": "Initial Consultation",
                    "description": "We begin with a thorough understanding of your needs.",
                    "duration": "30-60 minutes"
                },
                {
                    "step": 2,
                    "title": "Assessment & Planning",
                    "description": "We develop a customized plan based on your requirements.",
                    "duration": "1-2 days"
                },
                {
                    "step": 3,
                    "title": "Implementation",
                    "description": "We execute the plan with precision and care.",
                    "duration": "Varies"
                },
                {
                    "step": 4,
                    "title": "Follow-up & Support",
                    "description": "We ensure your satisfaction and provide ongoing support.",
                    "duration": "Ongoing"
                }
            ]
        }

    def _generate_service_benefits(self, keyword: str) -> List[Dict]:
        """Generate benefits for service page"""
        return [
            {"title": "Save Time", "icon": "clock"},
            {"title": "Reduce Costs", "icon": "dollar"},
            {"title": "Expert Guidance", "icon": "star"},
            {"title": "Peace of Mind", "icon": "shield"}
        ]

    def _generate_service_faq(self, keyword: str, business_type: str) -> List[Dict]:
        """Generate FAQ questions for service page"""
        faqs = [
            {
                "question": f"What is included in your {keyword} service?",
                "answer": f"Our {keyword} service includes comprehensive consultation, planning, implementation, and ongoing support to ensure your needs are fully met."
            },
            {
                "question": f"How much does {keyword} service cost?",
                "answer": f"The cost of our {keyword} service varies based on your specific needs. We offer free consultations to provide accurate pricing."
            },
            {
                "question": f"How long does the {keyword} process take?",
                "answer": f"The timeline for {keyword} services depends on the complexity of your requirements. Most projects are completed within 2-4 weeks."
            },
            {
                "question": f"Do you offer emergency {keyword} services?",
                "answer": "We understand that urgent needs arise. Contact us to discuss expedited service options."
            },
            {
                "question": f"What makes your {keyword} service different?",
                "answer": "Our combination of experience, personalized approach, and commitment to excellence sets us apart."
            },
            {
                "question": "What areas do you serve?",
                "answer": "We serve clients throughout the region. Contact us to confirm service availability in your area."
            },
            {
                "question": "Do you offer consultations?",
                "answer": "Yes, we offer initial consultations to understand your needs and provide recommendations."
            },
            {
                "question": "What payment methods do you accept?",
                "answer": "We accept various payment methods including cash, check, and major credit cards."
            }
        ]

        return faqs

    # Helper methods
    def _slugify(self, text: str) -> str:
        """Convert text to URL-friendly slug"""
        text = re.sub(r'[^\w\s-]', '', text.lower())
        text = re.sub(r'[-\s]+', '-', text)
        return text.strip('-')

    def _clean_url(self, url: str) -> str:
        """Clean and normalize URL"""
        if not url:
            return '/'

        # Remove any full URLs (http/https)
        if url.startswith('http://') or url.startswith('https://'):
            # Extract just the path from the URL
            from urllib.parse import urlparse
            parsed = urlparse(url)
            url = parsed.path
            if not url:
                url = '/'

        # Ensure it starts with /
        if not url.startswith('/'):
            url = '/' + url

        # Remove file extensions
        url = url.replace('.html', '').replace('.php', '')

        # Clean up double slashes
        url = url.replace('//', '/')

        return url

    def _parse_address(self, address_text: str) -> Dict:
        """Parse address text into structured format"""
        if not address_text:
            return {
                "street": "",
                "city": "",
                "region": "",
                "postal_code": "",
                "country": ""
            }

        # Simple parsing - could be enhanced
        parts = address_text.split(',')
        return {
            "street": parts[0].strip() if len(parts) > 0 else "",
            "city": parts[1].strip() if len(parts) > 1 else "",
            "region": parts[2].strip() if len(parts) > 2 else "",
            "postal_code": "",
            "country": parts[-1].strip() if len(parts) > 3 else ""
        }

    def _parse_hours(self, hours_text: str) -> Dict:
        """Parse business hours text"""
        default_hours = {
            "monday": "9:00 AM - 5:00 PM",
            "tuesday": "9:00 AM - 5:00 PM",
            "wednesday": "9:00 AM - 5:00 PM",
            "thursday": "9:00 AM - 5:00 PM",
            "friday": "9:00 AM - 5:00 PM",
            "saturday": "Closed",
            "sunday": "Closed"
        }

        if not hours_text:
            return default_hours

        # Could implement more sophisticated parsing
        return default_hours

    def _extract_service_areas(self, analysis_data: Dict) -> List[str]:
        """Extract service areas from analysis data"""
        # Could be enhanced to extract from content
        return ["Local Area", "Surrounding Region"]

    def _extract_keywords(self, seo_data: Dict) -> List[str]:
        """Extract keywords from SEO data"""
        keywords = []
        if seo_data.get('keywords'):
            keywords = [k.strip() for k in seo_data['keywords'].split(',')][:10]
        return keywords

    def _generate_features(self, analysis_data: Dict) -> List[Dict]:
        """Generate feature list for homepage"""
        return [
            {"title": "Professional Service", "description": "Expert solutions tailored to your needs"},
            {"title": "Experienced Team", "description": "Years of industry experience"},
            {"title": "Customer Focused", "description": "Your satisfaction is our priority"},
            {"title": "Quality Guaranteed", "description": "We stand behind our work"}
        ]

    def _generate_services_overview(self, analysis_data: Dict) -> List[Dict]:
        """Generate services overview for homepage"""
        services = []
        for service in analysis_data.get('services', [])[:6]:
            services.append({
                "title": service.get('title', 'Service'),
                "description": service.get('description', 'Professional service solutions'),
                "link": f"/{self._slugify(service.get('title', 'service'))}"
            })
        return services

    def _generate_why_choose(self, analysis_data: Dict) -> Dict:
        """Generate why choose us section"""
        business_info = analysis_data.get('business_info', {})
        return {
            "title": f"Why Choose {business_info.get('name', 'Us')}",
            "points": [
                "Proven track record of success",
                "Dedicated professional team",
                "Competitive pricing",
                "Excellent customer service"
            ]
        }

    def _generate_service_dropdown(self, analysis_data: Dict) -> List[Dict]:
        """Generate dropdown menu for services"""
        dropdown = []
        for service in analysis_data.get('services', [])[:8]:
            dropdown.append({
                "label": service.get('title', 'Service'),
                "url": f"/{self._slugify(service.get('title', 'service'))}",
                "active_pages": [self._slugify(service.get('title', 'service'))]
            })
        return dropdown

    def _determine_business_type(self, analysis_data: Dict) -> str:
        """Determine the business type from analysis data"""
        domain = analysis_data.get('domain', '').lower()
        business_name = analysis_data.get('business_info', {}).get('name', '').lower()
        services_text = ' '.join([s.get('title', '') for s in analysis_data.get('services', [])]).lower()

        # Check for law firm indicators
        law_indicators = ['law', 'legal', 'attorney', 'lawyer', 'counsel', 'esq', 'litigation', 'court']
        if any(indicator in domain or indicator in business_name or indicator in services_text
               for indicator in law_indicators):
            return 'law_firm'

        # Check for medical practice
        medical_indicators = ['medical', 'doctor', 'physician', 'clinic', 'hospital', 'health']
        if any(indicator in domain or indicator in business_name or indicator in services_text
               for indicator in medical_indicators):
            return 'medical_practice'

        # Check for dental practice
        dental_indicators = ['dental', 'dentist', 'orthodont']
        if any(indicator in domain or indicator in business_name or indicator in services_text
               for indicator in dental_indicators):
            return 'dental_practice'

        # Check for accounting firm
        accounting_indicators = ['account', 'cpa', 'tax', 'bookkeep', 'audit']
        if any(indicator in domain or indicator in business_name or indicator in services_text
               for indicator in accounting_indicators):
            return 'accounting_firm'

        # Check for real estate
        realty_indicators = ['real estate', 'realty', 'property', 'homes']
        if any(indicator in domain or indicator in business_name or indicator in services_text
               for indicator in realty_indicators):
            return 'real_estate'

        # Default to general service
        return 'general_service'

    def _extract_specialties(self, bio: str) -> List[str]:
        """Extract specialties from bio text"""
        # Simple extraction - could be enhanced
        specialties = []
        keywords = ['specialized', 'expertise', 'focus', 'experience in']
        for keyword in keywords:
            if keyword in bio.lower():
                # Extract following words
                parts = bio.lower().split(keyword)
                if len(parts) > 1:
                    specialty = parts[1].split('.')[0].strip()
                    if specialty:
                        specialties.append(specialty.title())
        return specialties[:5]

    def _generate_related_keywords(self, keyword: str) -> List[str]:
        """Generate related keywords for SEO"""
        related = [keyword]
        variations = [
            f"{keyword} services",
            f"{keyword} solutions",
            f"professional {keyword}",
            f"{keyword} experts",
            f"best {keyword}",
            f"{keyword} near me"
        ]
        related.extend(variations)
        return related[:8]

    def _save_page_json(self, filepath: Path, data: Dict):
        """Save page JSON file"""
        with open(filepath, 'w', encoding='utf-8') as f:
            json.dump(data, f, indent=2, ensure_ascii=False)
        self.generated_files.append(filepath)
        logger.info(f"Generated: {filepath}")