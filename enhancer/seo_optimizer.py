"""
SEO Optimizer - Enhances content for search engine optimization
"""

import re
import logging
from typing import Dict, List, Optional

logger = logging.getLogger(__name__)


class SEOOptimizer:
    """Optimizes content for SEO"""

    def __init__(self):
        self.keyword_density_target = 0.015  # 1.5% target density
        self.min_word_count = {
            'homepage': 500,
            'service_page': 800,
            'about_page': 400,
            'contact_page': 200
        }

    def suggest_keywords(self, analysis_data: Dict) -> List[str]:
        """
        Suggest SEO keywords based on analyzed website

        Args:
            analysis_data: Website analysis data

        Returns:
            List of suggested keywords
        """
        keywords = []

        # Extract from existing SEO data
        seo_data = analysis_data.get('seo_data', {})
        if seo_data.get('keywords'):
            existing = seo_data['keywords'].split(',')
            keywords.extend([k.strip() for k in existing[:5]])

        # Extract from services
        services = analysis_data.get('services', [])
        for service in services[:10]:
            title = service.get('title', '')
            if title:
                keywords.append(title.lower())

        # Extract from business type
        business_type = analysis_data.get('business_type', '')
        if business_type and business_type != 'general_service':
            type_keywords = self._get_business_type_keywords(business_type)
            keywords.extend(type_keywords)

        # Add location-based keywords if available
        contact = analysis_data.get('contact', {})
        if contact.get('address'):
            location = self._extract_location(contact['address'])
            if location:
                # Add location to service keywords
                for service in keywords[:5]:
                    keywords.append(f"{service} {location}")

        # Remove duplicates and return top keywords
        seen = set()
        unique_keywords = []
        for keyword in keywords:
            if keyword and keyword not in seen:
                seen.add(keyword)
                unique_keywords.append(keyword)

        return unique_keywords[:15]

    def optimize_meta_title(self, title: str, keyword: str = None, max_length: int = 60) -> str:
        """
        Optimize meta title for SEO

        Args:
            title: Original title
            keyword: Target keyword
            max_length: Maximum title length

        Returns:
            Optimized title
        """
        if not title:
            title = "Professional Services"

        # Include keyword if provided
        if keyword and keyword.lower() not in title.lower():
            title = f"{keyword.title()} - {title}"

        # Truncate if too long
        if len(title) > max_length:
            title = title[:max_length-3] + "..."

        return title

    def optimize_meta_description(self, description: str, keyword: str = None, max_length: int = 160) -> str:
        """
        Optimize meta description for SEO

        Args:
            description: Original description
            keyword: Target keyword
            max_length: Maximum description length

        Returns:
            Optimized description
        """
        if not description:
            description = "Professional services you can trust. Contact us today for expert solutions."

        # Include keyword if provided
        if keyword and keyword.lower() not in description.lower():
            description = f"{keyword.title()} services. {description}"

        # Add call to action if missing
        cta_phrases = ['contact us', 'call today', 'get started', 'learn more']
        has_cta = any(phrase in description.lower() for phrase in cta_phrases)
        if not has_cta and len(description) < max_length - 20:
            description += " Contact us today."

        # Truncate if too long
        if len(description) > max_length:
            description = description[:max_length-3] + "..."

        return description

    def optimize_content_for_keyword(self, content: str, keyword: str, target_density: float = None) -> str:
        """
        Optimize content for keyword density

        Args:
            content: Original content
            keyword: Target keyword
            target_density: Target keyword density

        Returns:
            Optimized content
        """
        if not content or not keyword:
            return content

        if target_density is None:
            target_density = self.keyword_density_target

        # Calculate current density
        word_count = len(content.split())
        keyword_count = content.lower().count(keyword.lower())
        current_density = keyword_count / word_count if word_count > 0 else 0

        # If density is too low, add keyword mentions
        if current_density < target_density:
            target_count = int(word_count * target_density)
            additions_needed = target_count - keyword_count

            if additions_needed > 0:
                # Add keyword variations naturally
                variations = [
                    f"Our {keyword} services",
                    f"Professional {keyword} solutions",
                    f"Expert {keyword} assistance",
                    f"Quality {keyword} support"
                ]

                for i in range(min(additions_needed, len(variations))):
                    # Add at natural points
                    content += f" {variations[i]}."

        return content

    def generate_schema_markup(self, page_type: str, data: Dict) -> Dict:
        """
        Generate schema.org markup for a page

        Args:
            page_type: Type of page
            data: Page data

        Returns:
            Schema markup dictionary
        """
        if page_type == 'service':
            return self._generate_service_schema(data)
        elif page_type == 'faq':
            return self._generate_faq_schema(data)
        elif page_type == 'local_business':
            return self._generate_local_business_schema(data)
        else:
            return self._generate_default_schema(data)

    def optimize_url_slug(self, text: str) -> str:
        """
        Create SEO-friendly URL slug

        Args:
            text: Text to convert

        Returns:
            URL slug
        """
        # Convert to lowercase
        slug = text.lower()

        # Replace spaces with hyphens
        slug = re.sub(r'\s+', '-', slug)

        # Remove special characters
        slug = re.sub(r'[^a-z0-9-]', '', slug)

        # Remove multiple hyphens
        slug = re.sub(r'-+', '-', slug)

        # Remove leading/trailing hyphens
        slug = slug.strip('-')

        return slug

    def check_content_quality(self, content: Dict, page_type: str = 'service') -> Dict:
        """
        Check content quality for SEO

        Args:
            content: Content to check
            page_type: Type of page

        Returns:
            Quality report
        """
        report = {
            'score': 0,
            'issues': [],
            'suggestions': []
        }

        # Check word count
        min_words = self.min_word_count.get(page_type, 500)
        word_count = 0

        if isinstance(content, dict):
            # Count words in all text fields
            for key, value in content.items():
                if isinstance(value, str):
                    word_count += len(value.split())

        if word_count < min_words:
            report['issues'].append(f"Content is too short ({word_count} words, minimum {min_words})")
            report['suggestions'].append(f"Add more detailed content to reach {min_words} words")
        else:
            report['score'] += 20

        # Check for meta data
        if 'meta' in content:
            meta = content['meta']

            # Check title
            if 'title' in meta:
                title_length = len(meta['title'])
                if 30 <= title_length <= 60:
                    report['score'] += 20
                else:
                    report['issues'].append(f"Title length is {title_length} (should be 30-60)")

            # Check description
            if 'description' in meta:
                desc_length = len(meta['description'])
                if 120 <= desc_length <= 160:
                    report['score'] += 20
                else:
                    report['issues'].append(f"Description length is {desc_length} (should be 120-160)")

            # Check keywords
            if 'keywords' in meta and len(meta['keywords']) > 0:
                report['score'] += 10

        # Check for FAQ section
        if 'faq' in content and len(content['faq']) >= 5:
            report['score'] += 15

        # Check for structure
        if 'content' in content:
            page_content = content['content']
            if 'hero' in page_content:
                report['score'] += 5
            if 'services' in page_content or 'features' in page_content:
                report['score'] += 5
            if 'process' in page_content or 'why_choose' in page_content:
                report['score'] += 5

        # Cap score at 100
        report['score'] = min(report['score'], 100)

        # Add general suggestions
        if report['score'] < 80:
            report['suggestions'].append("Consider adding more comprehensive content")
            report['suggestions'].append("Include relevant keywords naturally throughout the content")

        return report

    def _get_business_type_keywords(self, business_type: str) -> List[str]:
        """Get keywords for specific business type"""
        keywords_map = {
            'law_firm': ['lawyer', 'attorney', 'legal services', 'law firm'],
            'medical_practice': ['doctor', 'medical', 'healthcare', 'clinic'],
            'dental_practice': ['dentist', 'dental', 'teeth', 'oral health'],
            'restaurant': ['restaurant', 'dining', 'food', 'cuisine'],
            'consulting': ['consulting', 'consultant', 'advisory', 'business solutions'],
            'real_estate': ['real estate', 'property', 'homes for sale', 'realtor'],
            'accounting_firm': ['accountant', 'cpa', 'tax services', 'bookkeeping'],
            'fitness': ['gym', 'fitness', 'personal training', 'workout'],
            'salon_spa': ['salon', 'spa', 'beauty', 'hair styling'],
            'auto_service': ['auto repair', 'car service', 'mechanic', 'automotive']
        }

        return keywords_map.get(business_type, [])

    def _extract_location(self, address: str) -> str:
        """Extract location from address"""
        if not address:
            return ''

        # Simple extraction - get city or region
        parts = address.split(',')
        if len(parts) >= 2:
            return parts[1].strip()
        return ''

    def _generate_service_schema(self, data: Dict) -> Dict:
        """Generate service schema markup"""
        return {
            "@context": "https://schema.org",
            "@type": "Service",
            "serviceType": data.get('service_type', 'Professional Service'),
            "provider": {
                "@type": "Organization",
                "name": data.get('provider', '')
            },
            "areaServed": data.get('area_served', []),
            "description": data.get('description', '')
        }

    def _generate_faq_schema(self, faq_data: List[Dict]) -> Dict:
        """Generate FAQ schema markup"""
        questions = []
        for item in faq_data:
            questions.append({
                "@type": "Question",
                "name": item.get('question', ''),
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": item.get('answer', '')
                }
            })

        return {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": questions
        }

    def _generate_local_business_schema(self, data: Dict) -> Dict:
        """Generate local business schema markup"""
        return {
            "@context": "https://schema.org",
            "@type": "LocalBusiness",
            "name": data.get('name', ''),
            "description": data.get('description', ''),
            "telephone": data.get('phone', ''),
            "email": data.get('email', ''),
            "address": {
                "@type": "PostalAddress",
                "streetAddress": data.get('street', ''),
                "addressLocality": data.get('city', ''),
                "addressRegion": data.get('region', ''),
                "postalCode": data.get('postal_code', '')
            }
        }

    def _generate_default_schema(self, data: Dict) -> Dict:
        """Generate default schema markup"""
        return {
            "@context": "https://schema.org",
            "@type": "WebPage",
            "name": data.get('title', ''),
            "description": data.get('description', '')
        }