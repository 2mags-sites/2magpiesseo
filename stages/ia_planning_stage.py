"""
Information Architecture Planning Stage - Creates site structure and navigation
"""

import json
import logging
from pathlib import Path
from typing import Dict, List, Optional
import re

logger = logging.getLogger(__name__)


class IAPlanningStage:
    """Handles the Information Architecture planning phase"""

    def __init__(self, project_dir: Path):
        self.project_dir = project_dir
        self.output_dir = project_dir / 'ia_planning'
        self.output_dir.mkdir(exist_ok=True)

    def run(self, input_data: Dict) -> Dict:
        """Run the IA planning stage"""
        logger.info("Starting Information Architecture planning")

        # Extract discovery data
        discovery_data = input_data
        business_info = discovery_data.get('business_info', {})
        business_type = discovery_data.get('business_type', 'general')
        services = discovery_data.get('services', [])
        service_taxonomy = discovery_data.get('service_taxonomy', {})
        content_patterns = discovery_data.get('content_patterns', {})

        # Create site structure
        site_structure = self._create_site_structure(
            business_info,
            service_taxonomy,
            content_patterns
        )

        # Design URL patterns
        url_structure = self._design_url_structure(site_structure)

        # Create navigation hierarchy
        navigation = self._create_navigation(site_structure, service_taxonomy)

        # Plan internal linking strategy
        linking_strategy = self._plan_internal_linking(site_structure)

        # Determine page templates needed
        page_templates = self._identify_page_templates(site_structure)

        # Create breadcrumb structure
        breadcrumbs = self._design_breadcrumbs(site_structure)

        # Map keywords to pages
        keyword_mapping = self._initial_keyword_mapping(site_structure, services)

        # Compile IA output
        ia_output = {
            'site_structure': site_structure,
            'url_structure': url_structure,
            'navigation': navigation,
            'service_taxonomy': service_taxonomy,
            'linking_strategy': linking_strategy,
            'page_templates': page_templates,
            'breadcrumbs': breadcrumbs,
            'keyword_mapping': keyword_mapping,
            'total_pages': self._count_pages(site_structure),
            'depth_levels': self._calculate_depth(site_structure)
        }

        # Generate IA report
        self._generate_ia_report(ia_output)

        # Generate visual sitemap
        self._generate_sitemap_visualization(ia_output)

        # Save output
        output_file = self.output_dir / 'ia_planning_output.json'
        with open(output_file, 'w') as f:
            json.dump(ia_output, f, indent=2)

        return ia_output

    def _create_site_structure(self, business_info: Dict, service_taxonomy: Dict, content_patterns: Dict) -> Dict:
        """Create hierarchical site structure"""

        structure = {
            'home': {
                'title': 'Home',
                'url': '/',
                'template': 'homepage',
                'priority': 1.0,
                'children': {}
            }
        }

        # About section
        structure['about'] = {
            'title': 'About',
            'url': '/about',
            'template': 'about_page',
            'priority': 0.9,
            'children': {}
        }

        # Add team page if team members exist
        if content_patterns.get('has_team_profiles'):
            structure['about']['children']['team'] = {
                'title': 'Our Team',
                'url': '/about/team',
                'template': 'team_page',
                'priority': 0.8,
                'children': {}
            }

        # Add history/story page
        structure['about']['children']['our-story'] = {
            'title': 'Our Story',
            'url': '/about/our-story',
            'template': 'content_page',
            'priority': 0.7,
            'children': {}
        }

        # Services section
        if service_taxonomy:
            structure['services'] = {
                'title': 'Services',
                'url': '/services',
                'template': 'services_hub',
                'priority': 0.9,
                'children': {}
            }

            # Create service categories and pages
            for category_name, category_services in service_taxonomy.items():
                category_slug = self._slugify(category_name)

                # Only create category pages if there are multiple services
                if len(category_services) > 1:
                    structure['services']['children'][category_slug] = {
                        'title': category_name,
                        'url': f'/services/{category_slug}',
                        'template': 'service_category',
                        'priority': 0.8,
                        'children': {}
                    }

                    # Add individual service pages
                    for service in category_services:
                        service_slug = self._slugify(service['name'])
                        structure['services']['children'][category_slug]['children'][service_slug] = {
                            'title': service['name'],
                            'url': f'/services/{category_slug}/{service_slug}',
                            'template': 'service_page',
                            'priority': 0.7,
                            'original_data': service,
                            'children': {}
                        }
                else:
                    # Single service - put directly under services
                    for service in category_services:
                        service_slug = self._slugify(service['name'])
                        structure['services']['children'][service_slug] = {
                            'title': service['name'],
                            'url': f'/services/{service_slug}',
                            'template': 'service_page',
                            'priority': 0.7,
                            'original_data': service,
                            'children': {}
                        }

        # Resources section (if applicable)
        if content_patterns.get('has_blog') or content_patterns.get('has_case_studies'):
            structure['resources'] = {
                'title': 'Resources',
                'url': '/resources',
                'template': 'resources_hub',
                'priority': 0.6,
                'children': {}
            }

            if content_patterns.get('has_blog'):
                structure['resources']['children']['blog'] = {
                    'title': 'Blog',
                    'url': '/resources/blog',
                    'template': 'blog_listing',
                    'priority': 0.5,
                    'children': {}
                }

            if content_patterns.get('has_case_studies'):
                structure['resources']['children']['case-studies'] = {
                    'title': 'Case Studies',
                    'url': '/resources/case-studies',
                    'template': 'case_studies_listing',
                    'priority': 0.5,
                    'children': {}
                }

        # Contact section
        structure['contact'] = {
            'title': 'Contact',
            'url': '/contact',
            'template': 'contact_page',
            'priority': 0.9,
            'children': {}
        }

        # Add location pages if multiple locations
        if content_patterns.get('has_location_pages'):
            structure['contact']['children']['locations'] = {
                'title': 'Our Locations',
                'url': '/contact/locations',
                'template': 'locations_page',
                'priority': 0.7,
                'children': {}
            }

        return structure

    def _design_url_structure(self, site_structure: Dict) -> Dict:
        """Design URL patterns and rules"""
        return {
            'pattern': 'hierarchical',  # hierarchical, flat, or mixed
            'use_trailing_slash': False,
            'file_extension': '',  # empty for clean URLs
            'case': 'lowercase',
            'separator': '-',  # hyphen for word separation
            'max_depth': 3,
            'rules': [
                'Remove stop words from URLs',
                'Use descriptive keywords',
                'Keep URLs short and readable',
                'Maintain consistent structure'
            ]
        }

    def _create_navigation(self, site_structure: Dict, service_taxonomy: Dict) -> Dict:
        """Create navigation structure"""
        primary_nav = []

        # Home
        primary_nav.append({
            'label': 'Home',
            'url': '/',
            'active_pages': ['home', 'index']
        })

        # About (with dropdown if has children)
        about_item = {
            'label': 'About',
            'url': '/about',
            'active_pages': ['about']
        }
        if site_structure.get('about', {}).get('children'):
            about_item['dropdown'] = []
            for child_key, child_data in site_structure['about']['children'].items():
                about_item['dropdown'].append({
                    'label': child_data['title'],
                    'url': child_data['url']
                })
        primary_nav.append(about_item)

        # Services (with mega menu for categories)
        if 'services' in site_structure:
            services_item = {
                'label': 'Services',
                'url': '/services',
                'active_pages': ['services']
            }

            # Create mega menu if multiple categories
            if len(service_taxonomy) > 1:
                services_item['mega_menu'] = {}
                for category_name, category_services in service_taxonomy.items():
                    services_item['mega_menu'][category_name] = []
                    for service in category_services[:5]:  # Limit to 5 per category in nav
                        service_slug = self._slugify(service['name'])
                        services_item['mega_menu'][category_name].append({
                            'label': service['name'],
                            'url': self._get_service_url(site_structure, service_slug)
                        })
            elif service_taxonomy:
                # Simple dropdown for single category
                services_item['dropdown'] = []
                for services in service_taxonomy.values():
                    for service in services[:8]:  # Limit to 8 in dropdown
                        service_slug = self._slugify(service['name'])
                        services_item['dropdown'].append({
                            'label': service['name'],
                            'url': self._get_service_url(site_structure, service_slug)
                        })

            primary_nav.append(services_item)

        # Resources (if exists)
        if 'resources' in site_structure:
            resources_item = {
                'label': 'Resources',
                'url': '/resources',
                'active_pages': ['resources']
            }
            if site_structure['resources'].get('children'):
                resources_item['dropdown'] = []
                for child_key, child_data in site_structure['resources']['children'].items():
                    resources_item['dropdown'].append({
                        'label': child_data['title'],
                        'url': child_data['url']
                    })
            primary_nav.append(resources_item)

        # Contact
        primary_nav.append({
            'label': 'Contact',
            'url': '/contact',
            'active_pages': ['contact']
        })

        # Footer navigation
        footer_nav = {
            'column1': {
                'title': 'Services',
                'links': []
            },
            'column2': {
                'title': 'About',
                'links': [
                    {'label': 'About Us', 'url': '/about'},
                    {'label': 'Our Team', 'url': '/about/team'},
                    {'label': 'Our Story', 'url': '/about/our-story'}
                ]
            },
            'column3': {
                'title': 'Resources',
                'links': []
            },
            'column4': {
                'title': 'Contact',
                'links': [
                    {'label': 'Contact Us', 'url': '/contact'},
                    {'label': 'Privacy Policy', 'url': '/privacy'},
                    {'label': 'Terms of Service', 'url': '/terms'}
                ]
            }
        }

        # Add top services to footer
        for category_services in list(service_taxonomy.values())[:1]:  # First category
            for service in category_services[:5]:
                service_slug = self._slugify(service['name'])
                footer_nav['column1']['links'].append({
                    'label': service['name'],
                    'url': self._get_service_url(site_structure, service_slug)
                })

        return {
            'primary_nav': primary_nav,
            'footer_nav': footer_nav,
            'mobile_nav': primary_nav,  # Can be customized differently
            'cta_button': {
                'label': 'Get Started',
                'url': '/contact',
                'class': 'btn-primary'
            }
        }

    def _plan_internal_linking(self, site_structure: Dict) -> Dict:
        """Plan internal linking strategy"""
        return {
            'strategy': 'hub_and_spoke',
            'rules': [
                'Link from service pages to related services',
                'Link from content pages to relevant service pages',
                'Create topic clusters around main services',
                'Use breadcrumbs for hierarchical navigation',
                'Include contextual links within content'
            ],
            'link_distribution': {
                'homepage': 'Links to all main categories',
                'category_pages': 'Links to all pages in category',
                'service_pages': 'Links to 3-5 related services',
                'content_pages': 'Links to relevant services'
            },
            'anchor_text_strategy': 'Use descriptive, keyword-rich anchor text'
        }

    def _identify_page_templates(self, site_structure: Dict) -> List[str]:
        """Identify unique page templates needed"""
        templates = set()

        def extract_templates(structure):
            for key, data in structure.items():
                if isinstance(data, dict):
                    if 'template' in data:
                        templates.add(data['template'])
                    if 'children' in data:
                        extract_templates(data['children'])

        extract_templates(site_structure)
        return list(templates)

    def _design_breadcrumbs(self, site_structure: Dict) -> Dict:
        """Design breadcrumb navigation structure"""
        breadcrumbs = {}

        def generate_breadcrumb_path(structure, current_path=None, parent_title='Home'):
            if current_path is None:
                current_path = []

            for key, data in structure.items():
                if isinstance(data, dict) and 'url' in data:
                    new_path = current_path + [{'title': data['title'], 'url': data['url']}]
                    breadcrumbs[data['url']] = new_path

                    if 'children' in data:
                        generate_breadcrumb_path(data['children'], new_path, data['title'])

        generate_breadcrumb_path(site_structure)
        return breadcrumbs

    def _initial_keyword_mapping(self, site_structure: Dict, services: List[Dict]) -> Dict:
        """Create initial keyword mapping for pages"""
        keyword_map = {}

        # Homepage keywords
        keyword_map['/'] = {
            'primary': [],  # Will be filled based on business
            'secondary': [],
            'long_tail': []
        }

        # Service page keywords
        for service in services:
            service_slug = self._slugify(service['name'])
            service_url = self._get_service_url(site_structure, service_slug)
            if service_url:
                keyword_map[service_url] = {
                    'primary': [service['name'].lower()],
                    'secondary': [
                        f"{service['name'].lower()} services",
                        f"{service['name'].lower()} near me"
                    ],
                    'long_tail': [
                        f"best {service['name'].lower()} services",
                        f"affordable {service['name'].lower()}",
                        f"professional {service['name'].lower()}"
                    ]
                }

        return keyword_map

    def _count_pages(self, structure: Dict) -> int:
        """Count total pages in structure"""
        count = 0

        def count_recursive(struct):
            nonlocal count
            for key, data in struct.items():
                if isinstance(data, dict) and 'url' in data:
                    count += 1
                    if 'children' in data:
                        count_recursive(data['children'])

        count_recursive(structure)
        return count

    def _calculate_depth(self, structure: Dict) -> int:
        """Calculate maximum depth of site structure"""
        def get_depth(struct, current_depth=0):
            if not struct:
                return current_depth

            max_depth = current_depth
            for key, data in struct.items():
                if isinstance(data, dict) and 'children' in data:
                    child_depth = get_depth(data['children'], current_depth + 1)
                    max_depth = max(max_depth, child_depth)

            return max_depth

        return get_depth(structure)

    def _slugify(self, text: str) -> str:
        """Convert text to URL-friendly slug"""
        text = re.sub(r'[^\w\s-]', '', text.lower())
        text = re.sub(r'[-\s]+', '-', text)
        return text.strip('-')

    def _get_service_url(self, structure: Dict, service_slug: str) -> Optional[str]:
        """Find service URL in structure"""
        def find_url(struct, slug):
            for key, data in struct.items():
                if isinstance(data, dict):
                    if key == slug and 'url' in data:
                        return data['url']
                    if 'children' in data:
                        result = find_url(data['children'], slug)
                        if result:
                            return result
            return None

        return find_url(structure, service_slug)

    def _generate_ia_report(self, ia_output: Dict):
        """Generate human-readable IA report"""
        report_file = self.output_dir / 'ia_report.md'

        report = []
        report.append("# Information Architecture Report")
        report.append(f"\n**Total Pages:** {ia_output['total_pages']}")
        report.append(f"**Site Depth:** {ia_output['depth_levels']} levels")
        report.append(f"**Page Templates:** {len(ia_output['page_templates'])}")

        report.append("\n## Site Structure")
        report.append("```")
        report.append(self._format_structure_tree(ia_output['site_structure']))
        report.append("```")

        report.append("\n## Navigation Structure")
        report.append("\n### Primary Navigation")
        for item in ia_output['navigation']['primary_nav']:
            report.append(f"- {item['label']} ({item['url']})")
            if 'dropdown' in item:
                for sub in item['dropdown']:
                    report.append(f"  - {sub['label']}")

        report.append("\n## Page Templates Required")
        for template in ia_output['page_templates']:
            report.append(f"- {template}")

        report.append("\n## URL Structure")
        url_structure = ia_output['url_structure']
        report.append(f"- Pattern: {url_structure['pattern']}")
        report.append(f"- Case: {url_structure['case']}")
        report.append(f"- Separator: {url_structure['separator']}")

        with open(report_file, 'w') as f:
            f.write('\n'.join(report))

        logger.info(f"IA report generated: {report_file}")

    def _format_structure_tree(self, structure: Dict, indent=0) -> str:
        """Format structure as tree for display"""
        lines = []
        for key, data in structure.items():
            if isinstance(data, dict) and 'title' in data:
                prefix = "  " * indent + ("├── " if indent > 0 else "")
                lines.append(f"{prefix}{data['title']} ({data['url']})")
                if 'children' in data and data['children']:
                    lines.append(self._format_structure_tree(data['children'], indent + 1))
        return '\n'.join(lines)

    def _generate_sitemap_visualization(self, ia_output: Dict):
        """Generate visual sitemap (HTML)"""
        html_file = self.output_dir / 'sitemap.html'

        html = ['<!DOCTYPE html><html><head><title>Sitemap Visualization</title>']
        html.append('<style>')
        html.append('body { font-family: Arial; padding: 20px; }')
        html.append('.node { margin-left: 20px; margin-top: 10px; }')
        html.append('.node-title { font-weight: bold; }')
        html.append('.node-url { color: #666; font-size: 0.9em; }')
        html.append('</style></head><body>')
        html.append('<h1>Site Structure Visualization</h1>')

        def render_node(structure, level=0):
            output = []
            for key, data in structure.items():
                if isinstance(data, dict) and 'title' in data:
                    output.append(f'<div class="node" style="margin-left: {level * 30}px">')
                    output.append(f'<span class="node-title">{data["title"]}</span>')
                    output.append(f' <span class="node-url">{data["url"]}</span>')
                    output.append('</div>')
                    if 'children' in data:
                        output.extend(render_node(data['children'], level + 1))
            return output

        html.extend(render_node(ia_output['site_structure']))
        html.append('</body></html>')

        with open(html_file, 'w') as f:
            f.write('\n'.join(html))

        logger.info(f"Sitemap visualization generated: {html_file}")