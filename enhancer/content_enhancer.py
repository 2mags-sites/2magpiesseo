"""
Content Enhancer - Improves and expands content for better quality and SEO
"""

import re
import logging
from typing import Dict, List, Optional

logger = logging.getLogger(__name__)


class ContentEnhancer:
    """Enhances content quality and depth"""

    def __init__(self):
        self.min_faq_count = 8
        self.min_service_description_length = 150

    def enhance(self, data: Dict, business_type: str = 'general_service', keywords: List[str] = None) -> Dict:
        """
        Enhance all content in the data

        Args:
            data: JSON data to enhance
            business_type: Type of business
            keywords: Target keywords

        Returns:
            Enhanced data
        """
        logger.info(f"Enhancing content for {business_type} business")

        enhanced_data = data.copy()

        # Enhance universal data
        if 'universal' in enhanced_data:
            enhanced_data['universal'] = self._enhance_universal_data(
                enhanced_data['universal'],
                business_type
            )

        # Enhance page data
        if 'pages' in enhanced_data:
            for page_name, page_data in enhanced_data['pages'].items():
                enhanced_data['pages'][page_name] = self._enhance_page_data(
                    page_data,
                    page_name,
                    business_type,
                    keywords
                )

        return enhanced_data

    def _enhance_universal_data(self, universal_data: Dict, business_type: str) -> Dict:
        """Enhance universal data files"""

        # Enhance business data
        if 'business' in universal_data:
            business = universal_data['business']

            # Add default tagline if missing
            if not business.get('tagline'):
                business['tagline'] = self._generate_tagline(business_type)

            # Add description if missing
            if not business.get('description'):
                business['description'] = self._generate_business_description(business_type)

            # Ensure service areas are populated
            if not business.get('service_areas') or len(business['service_areas']) < 2:
                business['service_areas'] = self._generate_service_areas(business_type)

        # Enhance navigation
        if 'navigation' in universal_data:
            nav = universal_data['navigation']
            if 'cta_button' in nav and not nav['cta_button'].get('label'):
                nav['cta_button']['label'] = self._get_cta_text(business_type)

        # Enhance team data
        if 'team' in universal_data:
            team = universal_data['team']
            for member in team.get('members', []):
                # Add default title if missing
                if not member.get('title'):
                    member['title'] = self._get_default_title(business_type)

                # Enhance bio if too short
                if len(member.get('bio', '')) < 50:
                    member['bio'] = self._enhance_bio(member, business_type)

        return universal_data

    def _enhance_page_data(self, page_data: Dict, page_name: str, business_type: str, keywords: List[str]) -> Dict:
        """Enhance individual page data"""

        # Enhance meta data
        if 'meta' in page_data:
            page_data['meta'] = self._enhance_meta_data(page_data['meta'], page_name, keywords)

        # Enhance content sections
        if 'content' in page_data:
            content = page_data['content']

            # Enhance hero section
            if 'hero' in content:
                content['hero'] = self._enhance_hero_section(content['hero'], business_type)

            # Enhance services
            if 'services' in content:
                content['services'] = self._enhance_services(content['services'], business_type)

            # Enhance why choose section
            if 'why_choose' in content:
                content['why_choose'] = self._enhance_why_choose(content['why_choose'], business_type)

            # Enhance process section
            if 'process' in content:
                content['process'] = self._enhance_process(content['process'], business_type)

        # Enhance FAQ section
        if 'faq' in page_data:
            page_data['faq'] = self._enhance_faq(page_data['faq'], page_name, business_type)

        return page_data

    def _enhance_meta_data(self, meta: Dict, page_name: str, keywords: List[str]) -> Dict:
        """Enhance meta tags for SEO"""

        # Ensure title is optimal length
        if 'title' in meta:
            title = meta['title']
            if len(title) < 30:
                # Too short - add more context
                if keywords and len(keywords) > 0:
                    title = f"{title} | {keywords[0].title()} Services"
                meta['title'] = title
            elif len(title) > 60:
                # Too long - truncate
                meta['title'] = title[:57] + "..."

        # Ensure description is optimal length
        if 'description' in meta:
            description = meta['description']
            if len(description) < 120:
                # Too short - add more content
                description += " Contact us today for professional service and expert solutions."
                meta['description'] = description
            elif len(description) > 160:
                # Too long - truncate
                meta['description'] = description[:157] + "..."

        # Add keywords if missing
        if 'keywords' not in meta or not meta['keywords']:
            if keywords:
                meta['keywords'] = keywords[:8]
            else:
                meta['keywords'] = self._generate_default_keywords(page_name)

        return meta

    def _enhance_hero_section(self, hero: Dict, business_type: str) -> Dict:
        """Enhance hero section content"""

        # Ensure CTAs are present
        if not hero.get('cta_primary'):
            hero['cta_primary'] = self._get_cta_text(business_type)

        if not hero.get('cta_secondary'):
            hero['cta_secondary'] = "Learn More"

        # Enhance subtitle if too short
        if len(hero.get('subtitle', '')) < 30:
            hero['subtitle'] = self._generate_hero_subtitle(business_type)

        return hero

    def _enhance_services(self, services: List[Dict], business_type: str) -> List[Dict]:
        """Enhance service descriptions"""

        for service in services:
            # Ensure description is long enough
            if len(service.get('description', '')) < self.min_service_description_length:
                service['description'] = self._expand_service_description(
                    service.get('title', 'Service'),
                    service.get('description', ''),
                    business_type
                )

            # Add features if missing
            if 'features' not in service or len(service['features']) < 3:
                service['features'] = self._generate_service_features(
                    service.get('title', 'Service'),
                    business_type
                )

        return services

    def _enhance_why_choose(self, why_choose: Dict, business_type: str) -> Dict:
        """Enhance why choose section"""

        # Ensure we have enough points
        if 'points' in why_choose:
            points = why_choose['points']
            if len(points) < 4:
                # Add more points
                additional_points = self._generate_why_choose_points(business_type)
                for point in additional_points:
                    if len(points) < 6:
                        points.append(point)

        return why_choose

    def _enhance_process(self, process: Dict, business_type: str) -> Dict:
        """Enhance process section"""

        # Ensure process has enough steps
        if 'steps' in process:
            steps = process['steps']
            if len(steps) < 3:
                # Add more steps
                additional_steps = self._generate_process_steps(business_type)
                for i, step in enumerate(additional_steps):
                    if len(steps) < 5:
                        step['step'] = len(steps) + 1
                        steps.append(step)

        return process

    def _enhance_faq(self, faq: List[Dict], page_name: str, business_type: str) -> List[Dict]:
        """Enhance FAQ section"""

        # Ensure we have enough questions
        if len(faq) < self.min_faq_count:
            # Generate additional questions
            additional_faqs = self._generate_additional_faqs(
                page_name,
                business_type,
                self.min_faq_count - len(faq)
            )
            faq.extend(additional_faqs)

        # Ensure answers are comprehensive
        for item in faq:
            if len(item.get('answer', '')) < 50:
                item['answer'] = self._expand_faq_answer(
                    item.get('question', ''),
                    item.get('answer', ''),
                    business_type
                )

        return faq

    def _enhance_bio(self, member: Dict, business_type: str) -> str:
        """Generate enhanced bio for team member"""
        name = member.get('name', 'Team Member')
        title = member.get('title', 'Professional')
        return f"{name} is an experienced {title} with a proven track record in delivering exceptional results. With expertise in {business_type.replace('_', ' ')} services, {name} is committed to providing personalized solutions that exceed client expectations."

    # Helper methods for generating content

    def _generate_tagline(self, business_type: str) -> str:
        """Generate business tagline"""
        taglines = {
            'law_firm': "Your Trusted Legal Partner",
            'medical_practice': "Caring for Your Health",
            'dental_practice': "Creating Beautiful Smiles",
            'restaurant': "Exceptional Dining Experience",
            'consulting': "Strategic Solutions for Success",
            'real_estate': "Your Dream Home Awaits",
            'accounting_firm': "Financial Excellence Delivered",
            'fitness': "Transform Your Life Through Fitness",
            'salon_spa': "Where Beauty Meets Relaxation",
            'auto_service': "Expert Auto Care You Can Trust",
            'general_service': "Professional Service Excellence"
        }
        return taglines.get(business_type, "Quality Service You Can Trust")

    def _generate_business_description(self, business_type: str) -> str:
        """Generate business description"""
        descriptions = {
            'law_firm': "We are a full-service law firm dedicated to providing exceptional legal representation and counsel to individuals and businesses.",
            'medical_practice': "Our medical practice is committed to providing comprehensive healthcare services with a focus on patient-centered care.",
            'dental_practice': "We offer comprehensive dental care in a comfortable, modern environment with a focus on patient comfort and satisfaction.",
            'restaurant': "Experience exceptional cuisine in a welcoming atmosphere where quality ingredients meet culinary expertise.",
            'consulting': "We provide strategic consulting services to help businesses achieve their goals and maximize their potential.",
            'general_service': "We are dedicated to providing exceptional service and solutions tailored to meet your specific needs."
        }
        return descriptions.get(business_type, descriptions['general_service'])

    def _generate_service_areas(self, business_type: str) -> List[str]:
        """Generate service areas"""
        if business_type in ['law_firm', 'medical_practice', 'dental_practice', 'real_estate']:
            return ["Downtown", "North District", "South District", "East Side", "West Side", "Surrounding Areas"]
        else:
            return ["Local Area", "Greater Metropolitan Area", "Regional Service Area"]

    def _get_cta_text(self, business_type: str) -> str:
        """Get appropriate CTA text"""
        cta_map = {
            'law_firm': "Free Consultation",
            'medical_practice': "Book Appointment",
            'dental_practice': "Schedule Visit",
            'restaurant': "Make Reservation",
            'consulting': "Get Started",
            'real_estate': "View Properties",
            'accounting_firm': "Get Quote",
            'fitness': "Start Free Trial",
            'salon_spa': "Book Now",
            'auto_service': "Get Estimate"
        }
        return cta_map.get(business_type, "Contact Us")

    def _get_default_title(self, business_type: str) -> str:
        """Get default professional title"""
        titles = {
            'law_firm': "Attorney",
            'medical_practice': "Physician",
            'dental_practice': "Dentist",
            'restaurant': "Chef",
            'consulting': "Consultant",
            'real_estate': "Agent",
            'accounting_firm': "Accountant",
            'fitness': "Trainer",
            'salon_spa': "Stylist",
            'auto_service': "Technician"
        }
        return titles.get(business_type, "Professional")

    def _generate_hero_subtitle(self, business_type: str) -> str:
        """Generate hero subtitle"""
        subtitles = {
            'law_firm': "Expert legal representation when you need it most",
            'medical_practice': "Comprehensive healthcare for you and your family",
            'dental_practice': "Quality dental care in a comfortable environment",
            'restaurant': "Discover exceptional flavors and memorable dining",
            'consulting': "Strategic solutions to drive your business forward",
            'general_service': "Professional solutions tailored to your needs"
        }
        return subtitles.get(business_type, subtitles['general_service'])

    def _expand_service_description(self, title: str, description: str, business_type: str) -> str:
        """Expand service description"""
        if description:
            expansion = f"{description} Our experienced team provides comprehensive {title.lower()} solutions designed to meet your specific requirements. We combine industry expertise with personalized service to deliver exceptional results."
        else:
            expansion = f"Our {title} service provides comprehensive solutions tailored to your needs. With years of experience and a commitment to excellence, we deliver results that exceed expectations."
        return expansion

    def _generate_service_features(self, service_title: str, business_type: str) -> List[str]:
        """Generate service features"""
        return [
            f"Expert {service_title.lower()} solutions",
            "Personalized approach to your needs",
            "Proven track record of success",
            "Competitive pricing and value",
            "Ongoing support and guidance"
        ]

    def _generate_why_choose_points(self, business_type: str) -> List[Dict]:
        """Generate additional why choose points"""
        return [
            {
                "title": "Experienced Professionals",
                "description": "Our team brings years of industry experience and expertise."
            },
            {
                "title": "Customer Satisfaction",
                "description": "We prioritize your satisfaction and work to exceed expectations."
            },
            {
                "title": "Competitive Pricing",
                "description": "Quality service at fair and transparent prices."
            },
            {
                "title": "Proven Results",
                "description": "Track record of delivering successful outcomes for our clients."
            }
        ]

    def _generate_process_steps(self, business_type: str) -> List[Dict]:
        """Generate process steps"""
        return [
            {
                "title": "Initial Consultation",
                "description": "We begin with understanding your needs and objectives.",
                "duration": "30-60 minutes"
            },
            {
                "title": "Planning & Strategy",
                "description": "Develop a customized plan tailored to your requirements.",
                "duration": "1-2 days"
            },
            {
                "title": "Implementation",
                "description": "Execute the plan with precision and attention to detail.",
                "duration": "Varies"
            },
            {
                "title": "Review & Support",
                "description": "Ensure satisfaction and provide ongoing support.",
                "duration": "Ongoing"
            }
        ]

    def _generate_additional_faqs(self, page_name: str, business_type: str, count: int) -> List[Dict]:
        """Generate additional FAQ questions"""
        general_faqs = [
            {
                "question": "What makes your service different from competitors?",
                "answer": "We differentiate ourselves through our commitment to personalized service, proven expertise, and a track record of delivering exceptional results. Our team takes the time to understand your unique needs and develops customized solutions."
            },
            {
                "question": "How do I get started with your services?",
                "answer": "Getting started is easy. Simply contact us through our website, phone, or email to schedule an initial consultation. We'll discuss your needs and explain how we can help."
            },
            {
                "question": "What areas do you serve?",
                "answer": "We serve clients throughout the local area and surrounding regions. Contact us to confirm service availability in your specific location."
            },
            {
                "question": "Do you offer free consultations?",
                "answer": "Yes, we offer initial consultations to understand your needs and explain how our services can benefit you. Contact us to schedule your consultation."
            },
            {
                "question": "What are your hours of operation?",
                "answer": "We are typically open Monday through Friday during business hours. Contact us for specific hours or to schedule an appointment outside regular hours."
            },
            {
                "question": "How long have you been in business?",
                "answer": "We have been serving clients with dedication and expertise for several years, building a reputation for quality service and customer satisfaction."
            },
            {
                "question": "What payment methods do you accept?",
                "answer": "We accept various payment methods including cash, check, and major credit cards. Payment plans may be available for qualifying services."
            },
            {
                "question": "Can I get a quote before committing?",
                "answer": "Absolutely. We provide detailed quotes and transparent pricing so you know exactly what to expect before making any commitments."
            }
        ]

        return general_faqs[:count]

    def _expand_faq_answer(self, question: str, answer: str, business_type: str) -> str:
        """Expand FAQ answer"""
        if answer:
            expansion = f"{answer} Our team is committed to providing comprehensive solutions and ensuring your complete satisfaction. Contact us for more detailed information specific to your situation."
        else:
            expansion = "We provide comprehensive solutions tailored to your specific needs. Our experienced team is ready to assist you with professional service and expert guidance. Contact us to learn more."
        return expansion

    def _generate_default_keywords(self, page_name: str) -> List[str]:
        """Generate default keywords for a page"""
        page_keywords = {
            'index': ['professional services', 'quality solutions', 'expert team', 'trusted provider'],
            'about': ['about us', 'our team', 'company history', 'mission values'],
            'contact': ['contact us', 'get in touch', 'location', 'phone email'],
            'services': ['our services', 'solutions', 'professional service', 'expert assistance']
        }
        return page_keywords.get(page_name, ['professional', 'service', 'quality', 'expert'])