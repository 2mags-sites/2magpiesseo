#!/usr/bin/env python3
"""
Website Rebuilder - Pipeline-based Main Controller with Checkpoints
"""

import os
import sys
import argparse
import json
import logging
from datetime import datetime
from pathlib import Path
from typing import Dict, Optional

# Add project root to path
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from pipeline.pipeline_manager import WebsiteBuilderPipeline, PipelineValidator

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('pipeline.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)


class InteractivePipeline:
    """Interactive pipeline with user checkpoints"""

    def __init__(self, project_name: str = None):
        self.pipeline = WebsiteBuilderPipeline(project_name)
        self.validator = PipelineValidator()

    def run(self, url: str):
        """Run the pipeline with interactive checkpoints"""

        print("\n" + "=" * 60)
        print("WEBSITE REBUILDER - PIPELINE MODE")
        print("=" * 60)
        print(f"Project: {self.pipeline.project_name}")
        print(f"URL: {url}")
        print("=" * 60 + "\n")

        # Check if resuming
        if self.pipeline.current_stage > 0:
            print(f"Resuming from stage: {self.pipeline.get_current_stage_name()}")
            if not self._confirm("Continue from saved state?"):
                self.pipeline.restart_from_stage('discovery')

        # Stage 1: Discovery
        if self.pipeline.get_current_stage_name() == 'discovery':
            print("\n[STAGE 1/5] DISCOVERY & ANALYSIS")
            print("-" * 40)

            discovery_output = self.pipeline.run_stage('discovery', {'url': url})

            # Show discovery summary
            self._show_discovery_summary(discovery_output)

            # Checkpoint 1
            checkpoint_data = self.pipeline.checkpoint('discovery', self.validator.validate_discovery)
            self._show_checkpoint_results(checkpoint_data)

            if checkpoint_data['can_proceed']:
                # Allow modifications
                modifications = self._get_discovery_modifications(discovery_output)
                if modifications:
                    self.pipeline.apply_user_modifications('discovery', modifications)

                if self._confirm("Proceed to Information Architecture planning?"):
                    self.pipeline.proceed_to_next_stage()
                else:
                    print("Pipeline paused. Run again to continue.")
                    return
            else:
                print("Discovery validation failed. Please fix issues and retry.")
                return

        # Stage 2: IA Planning
        if self.pipeline.get_current_stage_name() == 'ia_planning':
            print("\n[STAGE 2/5] INFORMATION ARCHITECTURE PLANNING")
            print("-" * 40)

            ia_output = self.pipeline.run_stage('ia_planning')

            # Show IA summary
            self._show_ia_summary(ia_output)

            # Checkpoint 2
            checkpoint_data = self.pipeline.checkpoint('ia_planning', self.validator.validate_ia_planning)
            self._show_checkpoint_results(checkpoint_data)

            if checkpoint_data['can_proceed']:
                # Show sitemap visualization
                self._display_sitemap(ia_output)

                # Allow modifications
                modifications = self._get_ia_modifications(ia_output)
                if modifications:
                    self.pipeline.apply_user_modifications('ia_planning', modifications)

                if self._confirm("Approve this site structure?"):
                    self.pipeline.proceed_to_next_stage()
                else:
                    print("Pipeline paused. Run again to continue.")
                    return
            else:
                print("IA validation failed. Please fix issues and retry.")
                return

        # Stage 3: Content Strategy
        if self.pipeline.get_current_stage_name() == 'content_strategy':
            print("\n[STAGE 3/5] CONTENT STRATEGY")
            print("-" * 40)

            # For now, skip to demonstrate
            print("Content Strategy stage not yet implemented")
            print("Would generate content outlines and keyword mapping here")

            if self._confirm("Skip to Content Generation?"):
                self.pipeline.current_stage = 3  # Skip ahead
                self.pipeline.save_state()

        # Stage 4: Content Generation
        if self.pipeline.get_current_stage_name() == 'content_generation':
            print("\n[STAGE 4/5] CONTENT GENERATION")
            print("-" * 40)

            print("Content Generation stage not yet implemented")
            print("Would use AI to generate all content here")

            if self._confirm("Skip to Website Building?"):
                self.pipeline.current_stage = 4  # Skip ahead
                self.pipeline.save_state()

        # Stage 5: Website Building
        if self.pipeline.get_current_stage_name() == 'website_building':
            print("\n[STAGE 5/5] WEBSITE BUILDING")
            print("-" * 40)

            print("Would generate PHP website here using existing generator")
            print("Pipeline complete!")

        # Show final status
        print("\n" + "=" * 60)
        print(self.pipeline.generate_progress_report())
        print("=" * 60)

    def _confirm(self, message: str) -> bool:
        """Get user confirmation"""
        while True:
            response = input(f"\n{message} (y/n): ").lower()
            if response in ['y', 'yes']:
                return True
            elif response in ['n', 'no']:
                return False
            print("Please enter 'y' or 'n'")

    def _show_discovery_summary(self, discovery_output: Dict):
        """Display discovery summary"""
        print("\nDISCOVERY SUMMARY:")
        print(f"  Business Name: {discovery_output['business_info'].get('name', 'Unknown')}")
        print(f"  Business Type: {discovery_output['business_type']}")
        print(f"  Services Found: {len(discovery_output['services'])}")

        if discovery_output['service_taxonomy']:
            print("\n  Service Categories:")
            for category, services in discovery_output['service_taxonomy'].items():
                print(f"    - {category}: {len(services)} services")

        print(f"\n  Contact Info:")
        contact = discovery_output['contact']
        print(f"    Phone: {contact.get('phone', 'Not found')}")
        print(f"    Email: {contact.get('email', 'Not found')}")

        print(f"\n  Content Analysis:")
        analysis = discovery_output['content_analysis']
        print(f"    Total Pages: {analysis['total_pages']}")
        print(f"    Average Content Length: {analysis['average_content_length']} chars")

    def _show_ia_summary(self, ia_output: Dict):
        """Display IA planning summary"""
        print("\nINFORMATION ARCHITECTURE SUMMARY:")
        print(f"  Total Pages Planned: {ia_output['total_pages']}")
        print(f"  Site Depth: {ia_output['depth_levels']} levels")
        print(f"  Page Templates: {len(ia_output['page_templates'])}")

        print("\n  Main Sections:")
        for key, data in ia_output['site_structure'].items():
            if isinstance(data, dict) and 'title' in data:
                children_count = len(data.get('children', {}))
                print(f"    - {data['title']}: {data['url']}")
                if children_count > 0:
                    print(f"      ({children_count} sub-pages)")

    def _show_checkpoint_results(self, checkpoint_data: Dict):
        """Display checkpoint validation results"""
        print("\nCHECKPOINT VALIDATION:")

        validation = checkpoint_data['validation_results']

        if validation['passed']:
            print("  [OK] Validation PASSED")
        else:
            print("  [X] Validation FAILED")

        if validation['warnings']:
            print("\n  Warnings:")
            for warning in validation['warnings']:
                print(f"    [!] {warning}")

        if validation['errors']:
            print("\n  Errors:")
            for error in validation['errors']:
                print(f"    [X] {error}")

    def _get_discovery_modifications(self, discovery_output: Dict) -> Optional[Dict]:
        """Get user modifications for discovery data"""
        print("\nOPTIONAL MODIFICATIONS:")
        print("1. Correct business name")
        print("2. Add missing services")
        print("3. Update contact info")
        print("4. Skip modifications")

        choice = input("\nSelect option (1-4): ")

        modifications = {}

        if choice == '1':
            new_name = input("Enter correct business name: ")
            if new_name:
                modifications['business_info.name'] = new_name

        elif choice == '2':
            print("Enter services to add (comma-separated):")
            new_services = input().split(',')
            if new_services:
                services_to_add = []
                for service in new_services:
                    service = service.strip()
                    if service:
                        services_to_add.append({
                            'name': service,
                            'description': '',
                            'source': 'user_added'
                        })
                if services_to_add:
                    modifications['additional_services'] = services_to_add

        elif choice == '3':
            phone = input("Phone number (or press Enter to skip): ")
            email = input("Email address (or press Enter to skip): ")
            if phone:
                modifications['contact.phone'] = phone
            if email:
                modifications['contact.email'] = email

        return modifications if modifications else None

    def _get_ia_modifications(self, ia_output: Dict) -> Optional[Dict]:
        """Get user modifications for IA structure"""
        print("\nWould you like to modify the site structure?")

        if not self._confirm("Make modifications?"):
            return None

        print("\nModification options:")
        print("1. Reorganize service categories")
        print("2. Change URL structure")
        print("3. Modify navigation")
        print("4. Cancel")

        choice = input("\nSelect option (1-4): ")

        # For demo, we'll keep this simple
        # In production, this would be more sophisticated

        return None

    def _display_sitemap(self, ia_output: Dict):
        """Display visual sitemap"""
        print("\nSITE STRUCTURE:")
        print("-" * 40)

        def print_structure(structure, indent=0):
            for key, data in structure.items():
                if isinstance(data, dict) and 'title' in data:
                    prefix = "  " * indent + ("├── " if indent > 0 else "")
                    print(f"{prefix}{data['title']} ({data['url']})")
                    if 'children' in data and data['children']:
                        print_structure(data['children'], indent + 1)

        print_structure(ia_output['site_structure'])


def main():
    parser = argparse.ArgumentParser(description='Website Rebuilder - Pipeline Mode')
    parser.add_argument('--url', '-u', required=False, help='Website URL to analyze')
    parser.add_argument('--project', '-p', help='Project name (for resuming)')
    parser.add_argument('--reset', action='store_true', help='Reset and start fresh')
    parser.add_argument('--status', action='store_true', help='Show pipeline status')

    args = parser.parse_args()

    # Create interactive pipeline
    pipeline = InteractivePipeline(args.project)

    # Show status if requested
    if args.status:
        print(pipeline.pipeline.generate_progress_report())
        return

    # Reset if requested
    if args.reset:
        pipeline.pipeline.restart_from_stage('discovery')
        print("Pipeline reset to discovery stage")

    # Run pipeline
    if args.url:
        pipeline.run(args.url)
    elif pipeline.pipeline.current_stage > 0:
        # Resume from saved state
        discovery_data = pipeline.pipeline.stage_outputs.get('discovery', {})
        url = discovery_data.get('url', '')
        if url:
            pipeline.run(url)
        else:
            print("No URL found in saved state. Please provide --url")
    else:
        print("Please provide a URL with --url or a project to resume with --project")


if __name__ == "__main__":
    main()