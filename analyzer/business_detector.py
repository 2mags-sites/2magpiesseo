"""
Business Detector - Automatically detects business type from website content
"""

import re
import logging
from typing import Dict, List, Optional

logger = logging.getLogger(__name__)


class BusinessDetector:
    """Detects business type from analyzed website data"""

    def __init__(self):
        # Define business type patterns and signals
        self.business_patterns = {
            'law_firm': {
                'keywords': ['attorney', 'lawyer', 'legal', 'law firm', 'litigation', 'counsel',
                            'practice area', 'bar association', 'esquire', 'paralegal'],
                'services': ['litigation', 'divorce', 'criminal defense', 'estate planning',
                            'personal injury', 'immigration', 'bankruptcy'],
                'titles': ['attorney', 'lawyer', 'partner', 'associate', 'counsel', 'paralegal'],
                'weight': 0
            },
            'medical_practice': {
                'keywords': ['doctor', 'physician', 'medical', 'clinic', 'hospital', 'patient',
                            'appointment', 'health', 'treatment', 'diagnosis', 'dr.', 'md'],
                'services': ['consultation', 'surgery', 'examination', 'therapy', 'treatment',
                            'diagnosis', 'checkup', 'screening'],
                'titles': ['doctor', 'physician', 'surgeon', 'nurse', 'specialist', 'md', 'do'],
                'weight': 0
            },
            'dental_practice': {
                'keywords': ['dentist', 'dental', 'teeth', 'oral', 'orthodontic', 'tooth',
                            'cavity', 'crown', 'implant', 'hygienist'],
                'services': ['cleaning', 'filling', 'extraction', 'whitening', 'braces',
                            'root canal', 'crown', 'implant'],
                'titles': ['dentist', 'orthodontist', 'hygienist', 'dds', 'dmd'],
                'weight': 0
            },
            'accounting_firm': {
                'keywords': ['accountant', 'cpa', 'tax', 'audit', 'bookkeeping', 'financial',
                            'accounting', 'payroll', 'irs', 'taxation'],
                'services': ['tax preparation', 'audit', 'bookkeeping', 'payroll', 'consulting',
                            'financial planning', 'tax planning'],
                'titles': ['accountant', 'cpa', 'bookkeeper', 'auditor', 'tax preparer'],
                'weight': 0
            },
            'real_estate': {
                'keywords': ['real estate', 'property', 'realtor', 'listing', 'home', 'house',
                            'mortgage', 'rent', 'lease', 'broker', 'agent'],
                'services': ['buying', 'selling', 'listing', 'property management', 'rental',
                            'commercial real estate', 'residential'],
                'titles': ['realtor', 'agent', 'broker', 'property manager'],
                'weight': 0
            },
            'consulting': {
                'keywords': ['consulting', 'consultant', 'advisory', 'strategy', 'management',
                            'business', 'solutions', 'expertise', 'analysis'],
                'services': ['consulting', 'strategy', 'implementation', 'analysis', 'advisory',
                            'training', 'optimization'],
                'titles': ['consultant', 'advisor', 'strategist', 'analyst', 'partner'],
                'weight': 0
            },
            'restaurant': {
                'keywords': ['restaurant', 'menu', 'food', 'dining', 'cuisine', 'chef',
                            'reservation', 'takeout', 'delivery', 'catering'],
                'services': ['dining', 'takeout', 'delivery', 'catering', 'reservation',
                            'private events'],
                'titles': ['chef', 'manager', 'server', 'cook', 'bartender'],
                'weight': 0
            },
            'salon_spa': {
                'keywords': ['salon', 'spa', 'beauty', 'hair', 'nail', 'massage', 'facial',
                            'treatment', 'stylist', 'beautician'],
                'services': ['haircut', 'coloring', 'manicure', 'pedicure', 'massage',
                            'facial', 'waxing', 'treatment'],
                'titles': ['stylist', 'beautician', 'therapist', 'esthetician', 'colorist'],
                'weight': 0
            },
            'fitness': {
                'keywords': ['gym', 'fitness', 'workout', 'training', 'exercise', 'trainer',
                            'membership', 'class', 'yoga', 'pilates'],
                'services': ['personal training', 'group classes', 'membership', 'yoga',
                            'pilates', 'crossfit', 'nutrition'],
                'titles': ['trainer', 'instructor', 'coach', 'nutritionist'],
                'weight': 0
            },
            'auto_service': {
                'keywords': ['auto', 'car', 'vehicle', 'repair', 'service', 'mechanic',
                            'oil change', 'tire', 'brake', 'engine'],
                'services': ['repair', 'maintenance', 'oil change', 'tire service', 'brake service',
                            'inspection', 'diagnostic'],
                'titles': ['mechanic', 'technician', 'service advisor'],
                'weight': 0
            },
            'construction': {
                'keywords': ['construction', 'contractor', 'building', 'renovation', 'remodeling',
                            'project', 'residential', 'commercial'],
                'services': ['construction', 'renovation', 'remodeling', 'design', 'building',
                            'project management'],
                'titles': ['contractor', 'builder', 'architect', 'project manager'],
                'weight': 0
            },
            'education': {
                'keywords': ['school', 'education', 'learning', 'student', 'teacher', 'course',
                            'class', 'tutor', 'academy', 'institute'],
                'services': ['teaching', 'tutoring', 'training', 'courses', 'certification',
                            'education', 'learning'],
                'titles': ['teacher', 'instructor', 'tutor', 'professor', 'educator'],
                'weight': 0
            },
            'technology': {
                'keywords': ['software', 'technology', 'it', 'digital', 'computer', 'app',
                            'development', 'programming', 'tech support'],
                'services': ['development', 'support', 'consulting', 'implementation', 'hosting',
                            'maintenance', 'security'],
                'titles': ['developer', 'engineer', 'programmer', 'technician', 'analyst'],
                'weight': 0
            },
            'general_service': {
                'keywords': ['service', 'professional', 'business', 'company', 'quality'],
                'services': [],
                'titles': [],
                'weight': 0
            }
        }

    def detect(self, analysis_data: Dict) -> str:
        """
        Detect business type from analyzed website data

        Args:
            analysis_data: Website analysis data

        Returns:
            Detected business type
        """
        logger.info("Detecting business type from website content...")

        # Reset weights
        for business_type in self.business_patterns:
            self.business_patterns[business_type]['weight'] = 0

        # Analyze different data sources
        self._analyze_business_info(analysis_data.get('business_info', {}))
        self._analyze_services(analysis_data.get('services', []))
        self._analyze_team(analysis_data.get('team', []))
        self._analyze_content(analysis_data.get('pages', {}))
        self._analyze_seo_data(analysis_data.get('seo_data', {}))

        # Find business type with highest weight
        detected_type = 'general_service'
        max_weight = 0

        for business_type, pattern in self.business_patterns.items():
            if pattern['weight'] > max_weight:
                max_weight = pattern['weight']
                detected_type = business_type

        logger.info(f"Detected business type: {detected_type} (confidence: {max_weight})")

        # Log weights for debugging
        for business_type, pattern in self.business_patterns.items():
            if pattern['weight'] > 0:
                logger.debug(f"  {business_type}: {pattern['weight']}")

        return detected_type

    def _analyze_business_info(self, business_info: Dict):
        """Analyze business info for type detection"""
        text = ' '.join([
            str(business_info.get('name', '')),
            str(business_info.get('tagline', '')),
            str(business_info.get('about_summary', ''))
        ]).lower()

        for business_type, pattern in self.business_patterns.items():
            for keyword in pattern['keywords']:
                if keyword in text:
                    pattern['weight'] += 2

    def _analyze_services(self, services: List[Dict]):
        """Analyze services for type detection"""
        service_text = ' '.join([
            service.get('title', '') + ' ' + service.get('description', '')
            for service in services
        ]).lower()

        for business_type, pattern in self.business_patterns.items():
            # Check service keywords
            for keyword in pattern['keywords']:
                if keyword in service_text:
                    pattern['weight'] += 1

            # Check specific services
            for service in pattern['services']:
                if service in service_text:
                    pattern['weight'] += 3

    def _analyze_team(self, team: List[Dict]):
        """Analyze team members for type detection"""
        team_text = ' '.join([
            member.get('name', '') + ' ' +
            member.get('title', '') + ' ' +
            member.get('bio', '')
            for member in team
        ]).lower()

        for business_type, pattern in self.business_patterns.items():
            # Check titles
            for title in pattern['titles']:
                if title in team_text:
                    pattern['weight'] += 3

            # Check keywords in bios
            for keyword in pattern['keywords']:
                if keyword in team_text:
                    pattern['weight'] += 1

    def _analyze_content(self, pages: Dict):
        """Analyze page content for type detection"""
        content_text = ' '.join([
            page.get('content', '') for page in pages.values()
        ]).lower()

        # Limit content analysis to avoid over-weighting
        content_text = content_text[:5000]

        for business_type, pattern in self.business_patterns.items():
            for keyword in pattern['keywords']:
                # Count occurrences but cap the weight
                count = min(content_text.count(keyword), 5)
                pattern['weight'] += count

    def _analyze_seo_data(self, seo_data: Dict):
        """Analyze SEO data for type detection"""
        seo_text = ' '.join([
            str(seo_data.get('title', '')),
            str(seo_data.get('description', '')),
            str(seo_data.get('keywords', ''))
        ]).lower()

        for business_type, pattern in self.business_patterns.items():
            for keyword in pattern['keywords']:
                if keyword in seo_text:
                    pattern['weight'] += 2

    def get_business_characteristics(self, business_type: str) -> Dict:
        """
        Get characteristics for a specific business type

        Args:
            business_type: Type of business

        Returns:
            Dictionary of business characteristics
        """
        characteristics = {
            'law_firm': {
                'needs_disclaimer': True,
                'needs_credentials': True,
                'formal_tone': True,
                'trust_critical': True,
                'local_seo_important': True,
                'service_pages_needed': 10,
                'faq_questions_min': 10
            },
            'medical_practice': {
                'needs_disclaimer': True,
                'needs_credentials': True,
                'formal_tone': True,
                'trust_critical': True,
                'local_seo_important': True,
                'service_pages_needed': 8,
                'faq_questions_min': 10
            },
            'dental_practice': {
                'needs_disclaimer': False,
                'needs_credentials': True,
                'formal_tone': False,
                'trust_critical': True,
                'local_seo_important': True,
                'service_pages_needed': 6,
                'faq_questions_min': 8
            },
            'restaurant': {
                'needs_disclaimer': False,
                'needs_credentials': False,
                'formal_tone': False,
                'trust_critical': False,
                'local_seo_important': True,
                'service_pages_needed': 4,
                'faq_questions_min': 5
            },
            'consulting': {
                'needs_disclaimer': False,
                'needs_credentials': True,
                'formal_tone': True,
                'trust_critical': True,
                'local_seo_important': False,
                'service_pages_needed': 6,
                'faq_questions_min': 8
            }
        }

        return characteristics.get(business_type, {
            'needs_disclaimer': False,
            'needs_credentials': False,
            'formal_tone': False,
            'trust_critical': True,
            'local_seo_important': True,
            'service_pages_needed': 5,
            'faq_questions_min': 6
        })