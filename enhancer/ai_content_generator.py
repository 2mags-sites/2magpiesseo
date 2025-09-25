"""
AI-Powered Content Generator
Uses AI to generate rich, detailed content for services and pages
"""

import logging
from typing import Dict, List
import re

logger = logging.getLogger(__name__)


class AIContentGenerator:
    """Generates comprehensive content using AI based on business context"""

    def __init__(self):
        self.templates = self._load_templates()

    def generate_service_content(self, service_name: str, business_info: Dict, business_type: str = 'law_firm') -> Dict:
        """
        Generate comprehensive content for a service page

        This simulates what an AI would generate - in production you'd call
        an actual AI API like OpenAI or Claude here
        """

        # Extract business context
        business_name = business_info.get('name', 'Our Firm')
        location = self._extract_location(business_info)

        # Generate rich content based on service type and business context
        content = {
            'hero': self._generate_hero_content(service_name, business_name),
            'overview': self._generate_service_overview(service_name, business_type, location),
            'detailed_services': self._generate_detailed_services(service_name, business_type),
            'process': self._generate_process_steps(service_name, business_type),
            'benefits': self._generate_benefits(service_name, business_type),
            'faqs': self._generate_faqs(service_name, business_type, location),
            'why_choose_us': self._generate_why_choose_us(service_name, business_name, business_type),
            'call_to_action': self._generate_cta(service_name, business_name)
        }

        return content

    def _generate_hero_content(self, service_name: str, business_name: str) -> Dict:
        """Generate hero section content"""

        # Clean up service name
        clean_name = service_name.replace('-', ' ').title()

        return {
            'title': f"{clean_name} Services",
            'subtitle': f"Expert {clean_name.lower()} solutions from {business_name}",
            'description': f"Get professional {clean_name.lower()} services tailored to your unique needs. "
                          f"Our experienced team provides comprehensive support and guidance throughout the entire process.",
            'cta_primary': "Get Free Consultation",
            'cta_secondary': "Learn More"
        }

    def _generate_service_overview(self, service_name: str, business_type: str, location: str) -> Dict:
        """Generate detailed service overview"""

        clean_name = service_name.replace('-', ' ').title()

        # Generate context-aware overview based on service type
        if 'divorce' in service_name.lower():
            overview = {
                'title': f"Comprehensive {clean_name} Services in {location}",
                'introduction': f"Navigating {clean_name.lower()} proceedings can be emotionally and legally complex. "
                               f"Our experienced {clean_name.lower()} attorneys provide compassionate, professional "
                               f"guidance to protect your rights and interests throughout the process.",
                'key_points': [
                    "Personalized legal strategies tailored to your unique situation",
                    "Expert negotiation and litigation skills",
                    "Comprehensive support for all aspects of divorce proceedings",
                    "Protection of your financial interests and parental rights",
                    "Confidential and compassionate client service"
                ],
                'detailed_description': f"With over 20 years of experience in {clean_name.lower()} law, our team "
                                       f"understands the complexities and sensitivities involved. We work diligently "
                                       f"to achieve favorable outcomes while minimizing stress and conflict. Whether "
                                       f"through mediation, collaborative divorce, or litigation, we're committed to "
                                       f"protecting your interests and helping you move forward with confidence."
            }
        elif 'corporate' in service_name.lower() or 'business' in service_name.lower():
            overview = {
                'title': f"Professional {clean_name} Services for Businesses",
                'introduction': f"In today's complex business environment, having expert {clean_name.lower()} "
                               f"guidance is essential for success. Our {clean_name.lower()} team provides "
                               f"comprehensive legal solutions for businesses of all sizes.",
                'key_points': [
                    "Full-service corporate legal support",
                    "Strategic business planning and structuring",
                    "Regulatory compliance and risk management",
                    "Mergers, acquisitions, and joint ventures",
                    "Contract drafting and negotiation"
                ],
                'detailed_description': f"Our {clean_name.lower()} practice combines deep legal expertise with "
                                       f"practical business acumen. We help clients navigate complex transactions, "
                                       f"ensure regulatory compliance, and structure their operations for maximum "
                                       f"efficiency and protection. From startups to established enterprises, we "
                                       f"provide the legal foundation for sustainable business growth."
            }
        elif 'family' in service_name.lower():
            overview = {
                'title': f"Compassionate {clean_name} Legal Services",
                'introduction': f"Family legal matters require both legal expertise and emotional sensitivity. "
                               f"Our {clean_name.lower()} attorneys provide comprehensive support for all "
                               f"aspects of family law with compassion and professionalism.",
                'key_points': [
                    "Child custody and visitation arrangements",
                    "Spousal and child support matters",
                    "Property division and asset protection",
                    "Prenuptial and postnuptial agreements",
                    "Adoption and guardianship proceedings"
                ],
                'detailed_description': f"We understand that {clean_name.lower()} matters are deeply personal and "
                                       f"often emotionally challenging. Our approach combines strong legal advocacy "
                                       f"with compassionate client support. We work to resolve matters efficiently "
                                       f"while protecting your family's best interests, whether through negotiation, "
                                       f"mediation, or court proceedings when necessary."
            }
        else:
            # Generic professional service
            overview = {
                'title': f"Expert {clean_name} Services",
                'introduction': f"Our {clean_name.lower()} services provide comprehensive solutions tailored to "
                               f"meet your specific needs. With extensive experience and a commitment to excellence, "
                               f"we deliver results that exceed expectations.",
                'key_points': [
                    "Comprehensive consultation and assessment",
                    "Customized strategies and solutions",
                    "Expert guidance throughout the process",
                    "Transparent communication and updates",
                    "Results-focused approach"
                ],
                'detailed_description': f"Our {clean_name.lower()} practice is built on a foundation of expertise, "
                                       f"integrity, and client-focused service. We take the time to understand your "
                                       f"unique situation and develop strategies that align with your goals. Our team's "
                                       f"extensive experience ensures you receive the highest quality representation "
                                       f"and support throughout your legal matter."
            }

        return overview

    def _generate_detailed_services(self, service_name: str, business_type: str) -> List[Dict]:
        """Generate list of detailed sub-services"""

        clean_name = service_name.replace('-', ' ').title()

        if 'divorce' in service_name.lower():
            services = [
                {
                    'title': 'Uncontested Divorce',
                    'description': 'Streamlined process for couples who agree on all terms, including property division, '
                                  'child custody, and support arrangements. We ensure all legal requirements are met '
                                  'while minimizing time and costs.',
                    'features': ['Document preparation', 'Filing assistance', 'Court representation', 'Settlement agreements']
                },
                {
                    'title': 'Contested Divorce',
                    'description': 'Comprehensive representation when agreements cannot be reached. Our skilled litigators '
                                  'advocate strongly for your interests in negotiations and court proceedings.',
                    'features': ['Asset investigation', 'Expert witnesses', 'Trial preparation', 'Appeals if necessary']
                },
                {
                    'title': 'High Net Worth Divorce',
                    'description': 'Specialized services for complex financial situations involving significant assets, '
                                  'business interests, investments, and international holdings.',
                    'features': ['Business valuations', 'Tax implications', 'Asset protection', 'Privacy considerations']
                }
            ]
        elif 'corporate' in service_name.lower():
            services = [
                {
                    'title': 'Business Formation',
                    'description': 'Complete support for establishing your business entity, from choosing the right '
                                  'structure to filing all necessary documentation and ensuring compliance.',
                    'features': ['Entity selection', 'Registration filing', 'Operating agreements', 'Initial compliance']
                },
                {
                    'title': 'Mergers & Acquisitions',
                    'description': 'Expert guidance through complex M&A transactions, including due diligence, '
                                  'negotiation, documentation, and post-merger integration support.',
                    'features': ['Due diligence', 'Deal structuring', 'Negotiation support', 'Closing coordination']
                },
                {
                    'title': 'Corporate Governance',
                    'description': 'Ensuring your company maintains proper governance structures and complies with '
                                  'all regulatory requirements while protecting stakeholder interests.',
                    'features': ['Board advisory', 'Compliance programs', 'Policy development', 'Risk management']
                }
            ]
        else:
            # Generic services
            services = [
                {
                    'title': f'Comprehensive {clean_name} Consultation',
                    'description': f'In-depth analysis of your {clean_name.lower()} needs with personalized '
                                  f'recommendations and strategic planning for optimal outcomes.',
                    'features': ['Initial assessment', 'Strategy development', 'Action planning', 'Follow-up support']
                },
                {
                    'title': f'{clean_name} Implementation',
                    'description': f'Full-service implementation of {clean_name.lower()} solutions with ongoing '
                                  f'support and adjustments as needed to ensure success.',
                    'features': ['Project management', 'Quality assurance', 'Progress monitoring', 'Optimization']
                },
                {
                    'title': f'{clean_name} Support Services',
                    'description': f'Ongoing support and maintenance for all your {clean_name.lower()} needs, '
                                  f'ensuring continued success and addressing any challenges that arise.',
                    'features': ['24/7 support', 'Regular updates', 'Performance reviews', 'Continuous improvement']
                }
            ]

        return services

    def _generate_process_steps(self, service_name: str, business_type: str) -> Dict:
        """Generate process/workflow steps"""

        clean_name = service_name.replace('-', ' ').title()

        return {
            'title': f'Our {clean_name} Process',
            'subtitle': 'A clear, structured approach to achieving your goals',
            'steps': [
                {
                    'number': '1',
                    'title': 'Initial Consultation',
                    'description': 'We begin with a comprehensive consultation to understand your situation, '
                                  'goals, and concerns. This allows us to develop a tailored strategy.',
                    'duration': '60-90 minutes',
                    'icon': 'consultation'
                },
                {
                    'number': '2',
                    'title': 'Case Assessment & Strategy',
                    'description': 'Our team conducts thorough research and analysis to develop the most '
                                  'effective strategy for your specific circumstances.',
                    'duration': '2-3 business days',
                    'icon': 'strategy'
                },
                {
                    'number': '3',
                    'title': 'Documentation & Preparation',
                    'description': 'We prepare all necessary documents, gather evidence, and ensure everything '
                                  'is properly organized for your case.',
                    'duration': '1-2 weeks',
                    'icon': 'documents'
                },
                {
                    'number': '4',
                    'title': 'Negotiation & Resolution',
                    'description': 'We work diligently to negotiate favorable terms and resolve matters '
                                  'efficiently, whether through mediation or litigation.',
                    'duration': 'Varies by case',
                    'icon': 'negotiation'
                },
                {
                    'number': '5',
                    'title': 'Finalization & Follow-up',
                    'description': 'We ensure all agreements are properly executed and provide ongoing '
                                  'support for any post-resolution matters.',
                    'duration': 'Ongoing support',
                    'icon': 'completion'
                }
            ]
        }

    def _generate_benefits(self, service_name: str, business_type: str) -> List[Dict]:
        """Generate benefits section"""

        return [
            {
                'title': 'Expert Legal Team',
                'description': 'Our attorneys have decades of combined experience and specialized expertise '
                              'in their practice areas.',
                'icon': 'expertise'
            },
            {
                'title': 'Personalized Approach',
                'description': 'Every case is unique. We develop customized strategies tailored to your '
                              'specific situation and goals.',
                'icon': 'personalized'
            },
            {
                'title': 'Clear Communication',
                'description': 'We keep you informed at every step with regular updates and clear explanations '
                              'of your options and progress.',
                'icon': 'communication'
            },
            {
                'title': 'Cost-Effective Solutions',
                'description': 'We work efficiently to minimize costs while maximizing results, offering '
                              'flexible fee arrangements when possible.',
                'icon': 'value'
            },
            {
                'title': 'Proven Track Record',
                'description': 'Our success rate speaks for itself, with numerous favorable outcomes '
                              'for clients across diverse cases.',
                'icon': 'success'
            },
            {
                'title': 'Confidential Service',
                'description': 'Your privacy is paramount. All consultations and case details are handled '
                              'with strict confidentiality.',
                'icon': 'privacy'
            }
        ]

    def _generate_faqs(self, service_name: str, business_type: str, location: str) -> List[Dict]:
        """Generate comprehensive FAQs"""

        clean_name = service_name.replace('-', ' ').title()

        if 'divorce' in service_name.lower():
            faqs = [
                {
                    'question': f'How long does a divorce take in {location}?',
                    'answer': f'The timeline varies depending on whether the divorce is contested or uncontested. '
                             f'Uncontested divorces typically take 3-6 months, while contested cases can take '
                             f'12-18 months or longer. Factors affecting timeline include court schedules, '
                             f'complexity of assets, and custody arrangements.'
                },
                {
                    'question': 'What are the grounds for divorce?',
                    'answer': 'Common grounds include irretrievable breakdown of marriage, adultery, unreasonable '
                             'behavior, desertion, and separation for a specified period. We can help you determine '
                             'the most appropriate grounds for your situation.'
                },
                {
                    'question': 'How is property divided in a divorce?',
                    'answer': 'Property division follows equitable distribution principles, considering factors such as '
                             'length of marriage, each spouse\'s contributions, earning capacity, and future needs. '
                             'We work to ensure a fair division that protects your interests.'
                },
                {
                    'question': 'What about child custody arrangements?',
                    'answer': 'Child custody decisions prioritize the best interests of the children. Factors include '
                             'each parent\'s relationship with the children, living situations, work schedules, and '
                             'the children\'s preferences if age-appropriate. We advocate for arrangements that '
                             'maintain strong parent-child relationships.'
                },
                {
                    'question': 'How much will divorce proceedings cost?',
                    'answer': 'Costs vary based on case complexity and whether the divorce is contested. We offer '
                             'transparent fee structures and can discuss payment plans. Initial consultations help '
                             'us provide accurate cost estimates for your specific situation.'
                }
            ]
        elif 'corporate' in service_name.lower():
            faqs = [
                {
                    'question': 'What type of business entity should I choose?',
                    'answer': 'The best entity type depends on factors including liability protection needs, tax '
                             'considerations, number of owners, and growth plans. Common options include LLC, '
                             'Corporation, Partnership, and Sole Proprietorship. We help you evaluate options '
                             'and choose the structure that best fits your business goals.'
                },
                {
                    'question': 'Do I need a shareholders agreement?',
                    'answer': 'Yes, if you have multiple owners. A shareholders agreement defines ownership rights, '
                             'decision-making processes, transfer restrictions, and exit strategies. It\'s essential '
                             'for preventing disputes and protecting all parties\' interests.'
                },
                {
                    'question': 'How can I protect my business from lawsuits?',
                    'answer': 'Risk mitigation strategies include proper entity structure, comprehensive insurance, '
                             'strong contracts, compliance programs, and asset protection planning. We develop '
                             'multi-layered protection strategies tailored to your industry and risk profile.'
                },
                {
                    'question': 'What ongoing compliance is required?',
                    'answer': 'Requirements vary by entity type and jurisdiction but typically include annual reports, '
                             'tax filings, meeting minutes, and license renewals. We provide compliance calendars '
                             'and ongoing support to ensure you meet all obligations.'
                },
                {
                    'question': 'When should I consult a corporate lawyer?',
                    'answer': 'Consult us before major decisions including forming a business, entering significant '
                             'contracts, hiring employees, raising capital, buying/selling businesses, or facing '
                             'disputes. Early legal guidance prevents costly mistakes.'
                }
            ]
        else:
            # Generic FAQs
            faqs = [
                {
                    'question': f'What {clean_name.lower()} services do you offer?',
                    'answer': f'We provide comprehensive {clean_name.lower()} services including consultation, '
                             f'planning, implementation, and ongoing support. Our services are tailored to meet '
                             f'the unique needs of each client, ensuring optimal outcomes.'
                },
                {
                    'question': 'How much do your services cost?',
                    'answer': 'Our fees vary based on the complexity and scope of services required. We offer '
                             'transparent pricing and flexible payment options. Contact us for a detailed quote '
                             'based on your specific needs.'
                },
                {
                    'question': 'How long does the process take?',
                    'answer': 'Timeline varies depending on the complexity of your case and specific requirements. '
                             'During the initial consultation, we provide realistic timelines and keep you '
                             'informed of progress throughout the process.'
                },
                {
                    'question': 'What documents do I need to provide?',
                    'answer': 'Required documents vary by service type. We provide a comprehensive checklist during '
                             'the initial consultation and assist with gathering and organizing all necessary '
                             'documentation.'
                },
                {
                    'question': 'Do you offer emergency services?',
                    'answer': 'Yes, we understand that urgent legal matters arise. We offer priority consultations '
                             'and expedited services for time-sensitive issues. Contact us immediately if you '
                             'have an urgent matter.'
                }
            ]

        # Add more location and service-specific FAQs
        faqs.extend([
            {
                'question': f'Do you handle cases outside of {location}?',
                'answer': f'While we\'re based in {location}, we can handle matters throughout the jurisdiction. '
                         f'For cases requiring local presence in other areas, we work with a network of '
                         f'qualified professionals to ensure comprehensive representation.'
            },
            {
                'question': 'What should I bring to the initial consultation?',
                'answer': 'Bring any relevant documents, correspondence, court papers, contracts, or agreements '
                         'related to your matter. Also prepare a timeline of events and a list of questions. '
                         'The more information you provide, the better we can assess your case.'
            },
            {
                'question': 'How do you keep clients informed?',
                'answer': 'We maintain regular communication through your preferred method - phone, email, or '
                         'in-person meetings. You\'ll receive updates on all significant developments, and '
                         'we\'re always available to answer questions or address concerns.'
            },
            {
                'question': 'What makes your firm different?',
                'answer': 'Our combination of extensive experience, personalized service, and commitment to '
                         'achieving optimal outcomes sets us apart. We treat each client with respect and '
                         'dedication, ensuring you receive the attention and results you deserve.'
            },
            {
                'question': 'Is the initial consultation free?',
                'answer': 'We offer initial consultations to assess your case and discuss how we can help. '
                         'Contact us to schedule your consultation and learn about our fee structure.'
            }
        ])

        return faqs[:12]  # Return up to 12 FAQs

    def _generate_why_choose_us(self, service_name: str, business_name: str, business_type: str) -> Dict:
        """Generate 'Why Choose Us' section"""

        return {
            'title': f'Why Choose {business_name}',
            'subtitle': 'Experience, Excellence, and Commitment to Your Success',
            'reasons': [
                {
                    'title': 'Decades of Experience',
                    'description': 'Our attorneys bring over 20 years of combined experience in their respective '
                                  'practice areas, handling cases of all complexities with proven success.',
                    'stats': '20+ Years Experience'
                },
                {
                    'title': 'Client-Focused Approach',
                    'description': 'We prioritize your needs and goals, developing strategies that align with your '
                                  'best interests while maintaining open, honest communication throughout.',
                    'stats': '98% Client Satisfaction'
                },
                {
                    'title': 'Proven Track Record',
                    'description': 'Our history of favorable outcomes speaks to our skill and dedication. We\'ve '
                                  'successfully resolved hundreds of cases across diverse practice areas.',
                    'stats': '500+ Cases Won'
                },
                {
                    'title': 'Comprehensive Services',
                    'description': 'From initial consultation through final resolution and beyond, we provide '
                                  'complete legal support, ensuring no aspect of your case is overlooked.',
                    'stats': 'Full Service Firm'
                },
                {
                    'title': 'Transparent Pricing',
                    'description': 'No hidden fees or surprises. We provide clear fee structures and work within '
                                  'your budget to deliver maximum value for your investment.',
                    'stats': 'Flexible Payment Options'
                },
                {
                    'title': 'Accessible & Responsive',
                    'description': 'We\'re here when you need us. Our team is readily accessible and responds '
                                  'promptly to your questions and concerns throughout your case.',
                    'stats': '24-Hour Response Time'
                }
            ],
            'testimonial_snippet': f'The team at {business_name} exceeded our expectations. Their expertise and '
                                  f'dedication made all the difference in achieving a favorable outcome.',
            'awards': [
                'Top Rated Law Firm 2024',
                'Excellence in Client Service',
                'Best Legal Services Award',
                'Professional Achievement Recognition'
            ]
        }

    def _generate_cta(self, service_name: str, business_name: str) -> Dict:
        """Generate call-to-action section"""

        clean_name = service_name.replace('-', ' ').title()

        return {
            'title': f'Ready to Get Started with {clean_name}?',
            'subtitle': 'Take the first step toward resolving your legal matters',
            'description': f'Contact {business_name} today for a confidential consultation. Our experienced '
                          f'{clean_name.lower()} attorneys are ready to help you navigate your legal challenges '
                          f'and achieve the best possible outcome.',
            'primary_button': 'Schedule Free Consultation',
            'secondary_button': 'Call Now',
            'urgency_text': 'Don\'t wait - early action often leads to better outcomes',
            'features': [
                'Free Initial Consultation',
                'Confidential & Discrete',
                'No Obligation to Proceed',
                'Flexible Appointment Times'
            ]
        }

    def generate_about_content(self, business_info: Dict, team_members: List = None, business_type: str = 'law_firm') -> Dict:
        """Generate comprehensive About page content"""

        business_name = business_info.get('name', 'Our Firm')

        return {
            'hero': {
                'title': f'About {business_name}',
                'subtitle': 'Dedicated to Excellence in Legal Services',
                'description': 'Learn about our history, values, and commitment to providing exceptional legal services.'
            },
            'our_story': {
                'title': 'Our Story',
                'content': f'{business_name} was founded on the principle that everyone deserves access to '
                          f'exceptional legal representation. Over the years, we\'ve built a reputation for '
                          f'excellence, integrity, and unwavering commitment to our clients\' success.\n\n'
                          f'From our humble beginnings, we\'ve grown into a full-service law firm, handling '
                          f'complex cases across multiple practice areas. Our growth reflects not just our '
                          f'success, but the trust our clients place in us.\n\n'
                          f'Today, we continue to uphold the values that have guided us from the start: '
                          f'professionalism, compassion, and an absolute dedication to achieving the best '
                          f'possible outcomes for those we serve.',
                'milestones': [
                    {'year': '2000', 'event': 'Firm established with focus on family law'},
                    {'year': '2005', 'event': 'Expanded to include corporate law practice'},
                    {'year': '2010', 'event': 'Recognized as Top Law Firm in the region'},
                    {'year': '2015', 'event': 'Opened second office to serve more clients'},
                    {'year': '2020', 'event': 'Celebrated 20 years of legal excellence'},
                    {'year': '2024', 'event': 'Continue to lead in multiple practice areas'}
                ]
            },
            'mission': {
                'title': 'Our Mission',
                'statement': f'To provide exceptional legal services that protect our clients\' rights, '
                            f'advance their interests, and exceed their expectations through skilled '
                            f'advocacy, personalized attention, and unwavering integrity.',
                'core_values': [
                    {
                        'title': 'Excellence',
                        'description': 'We strive for excellence in everything we do, from legal research '
                                      'to courtroom advocacy, ensuring the highest quality representation.'
                    },
                    {
                        'title': 'Integrity',
                        'description': 'We conduct ourselves with absolute integrity, maintaining the highest '
                                      'ethical standards and earning the trust of clients and colleagues alike.'
                    },
                    {
                        'title': 'Compassion',
                        'description': 'We understand that legal matters are often stressful and emotional. '
                                      'We approach each case with compassion and understanding.'
                    },
                    {
                        'title': 'Innovation',
                        'description': 'We embrace innovative approaches and technologies to deliver more '
                                      'efficient, effective legal solutions for our clients.'
                    },
                    {
                        'title': 'Commitment',
                        'description': 'We are fully committed to our clients\' success, going above and '
                                      'beyond to achieve favorable outcomes in every case.'
                    }
                ]
            },
            'what_sets_us_apart': {
                'title': 'What Sets Us Apart',
                'introduction': 'In a crowded legal landscape, we distinguish ourselves through:',
                'differentiators': [
                    {
                        'title': 'Personalized Service',
                        'description': 'Every client receives individual attention from senior attorneys who '
                                      'take the time to understand your unique situation and goals.'
                    },
                    {
                        'title': 'Strategic Thinking',
                        'description': 'We don\'t just react to legal challenges - we anticipate them, '
                                      'developing proactive strategies that position you for success.'
                    },
                    {
                        'title': 'Transparent Communication',
                        'description': 'No legal jargon or confusion. We explain everything clearly and keep '
                                      'you informed at every step of your case.'
                    },
                    {
                        'title': 'Results-Driven Approach',
                        'description': 'We measure our success by your success, focusing relentlessly on '
                                      'achieving the outcomes that matter most to you.'
                    }
                ]
            },
            'community_involvement': {
                'title': 'Giving Back to Our Community',
                'description': f'{business_name} believes in giving back to the community that has supported us. '
                              f'We regularly participate in pro bono work, sponsor local events, and support '
                              f'various charitable organizations.',
                'initiatives': [
                    'Pro bono legal services for those in need',
                    'Legal education workshops for the community',
                    'Sponsorship of local youth programs',
                    'Support for domestic violence shelters',
                    'Participation in legal aid societies'
                ]
            }
        }

    def _extract_location(self, business_info: Dict) -> str:
        """Extract location from business info"""

        if business_info.get('contact', {}).get('address'):
            # Try to extract city from address
            address = business_info['contact']['address']
            if isinstance(address, dict):
                return address.get('city', 'Hong Kong')
            elif isinstance(address, str):
                # Simple extraction - look for Hong Kong or other major cities
                if 'Hong Kong' in address:
                    return 'Hong Kong'
                # Add more city detection as needed

        return 'Hong Kong'  # Default

    def _load_templates(self) -> Dict:
        """Load content generation templates"""
        # In a production system, these would be loaded from files or a database
        return {}