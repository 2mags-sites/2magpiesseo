"""
Pipeline Manager - Orchestrates the multi-stage website building process with checkpoints
"""

import json
import os
from pathlib import Path
from datetime import datetime
from typing import Dict, List, Optional, Any
import logging

logger = logging.getLogger(__name__)


class WebsiteBuilderPipeline:
    """Manages the multi-stage website building process with validation checkpoints"""

    STAGES = [
        'discovery',
        'ia_planning',
        'content_strategy',
        'content_generation',
        'website_building'
    ]

    def __init__(self, project_name: str = None, output_dir: str = 'output'):
        self.project_name = project_name or f"project_{datetime.now().strftime('%Y%m%d_%H%M%S')}"
        self.output_dir = Path(output_dir)
        self.project_dir = self.output_dir / self.project_name
        self.state_file = self.project_dir / 'pipeline_state.json'

        self.current_stage = 0
        self.stage_outputs = {}
        self.user_modifications = {}
        self.checkpoints_passed = []

        # Create project directory
        self.project_dir.mkdir(parents=True, exist_ok=True)

        # Load existing state if available
        if self.state_file.exists():
            self.load_state()

    def save_state(self):
        """Persist current pipeline state to disk"""
        state = {
            'project_name': self.project_name,
            'current_stage': self.current_stage,
            'current_stage_name': self.STAGES[self.current_stage] if self.current_stage < len(self.STAGES) else 'complete',
            'stage_outputs': self.stage_outputs,
            'user_modifications': self.user_modifications,
            'checkpoints_passed': self.checkpoints_passed,
            'timestamp': datetime.now().isoformat()
        }

        with open(self.state_file, 'w') as f:
            json.dump(state, f, indent=2)

        logger.info(f"Pipeline state saved for project: {self.project_name}")

    def load_state(self):
        """Load pipeline state from disk"""
        try:
            with open(self.state_file, 'r') as f:
                state = json.load(f)

            self.current_stage = state.get('current_stage', 0)
            self.stage_outputs = state.get('stage_outputs', {})
            self.user_modifications = state.get('user_modifications', {})
            self.checkpoints_passed = state.get('checkpoints_passed', [])

            logger.info(f"Pipeline state loaded. Current stage: {self.STAGES[self.current_stage]}")
        except Exception as e:
            logger.error(f"Error loading pipeline state: {e}")

    def get_current_stage_name(self) -> str:
        """Get the name of the current stage"""
        if self.current_stage < len(self.STAGES):
            return self.STAGES[self.current_stage]
        return 'complete'

    def run_stage(self, stage_name: str, input_data: Dict = None) -> Dict:
        """Run a specific stage of the pipeline"""
        if stage_name not in self.STAGES:
            raise ValueError(f"Unknown stage: {stage_name}")

        logger.info(f"Running stage: {stage_name}")

        # Get input data from previous stage if not provided
        if input_data is None and self.current_stage > 0:
            prev_stage = self.STAGES[self.current_stage - 1]
            input_data = self.stage_outputs.get(prev_stage, {})

        # Route to appropriate stage handler
        if stage_name == 'discovery':
            from stages.discovery_stage import DiscoveryStage
            stage = DiscoveryStage(self.project_dir)
            output = stage.run(input_data)
        elif stage_name == 'ia_planning':
            from stages.ia_planning_stage import IAPlanningStage
            stage = IAPlanningStage(self.project_dir)
            output = stage.run(input_data)
        elif stage_name == 'content_strategy':
            from stages.content_strategy_stage import ContentStrategyStage
            stage = ContentStrategyStage(self.project_dir)
            output = stage.run(input_data)
        elif stage_name == 'content_generation':
            from stages.content_generation_stage import ContentGenerationStage
            stage = ContentGenerationStage(self.project_dir)
            output = stage.run(input_data)
        elif stage_name == 'website_building':
            from stages.website_building_stage import WebsiteBuildingStage
            stage = WebsiteBuildingStage(self.project_dir)
            output = stage.run(input_data)
        else:
            raise ValueError(f"Stage handler not implemented: {stage_name}")

        # Store output
        self.stage_outputs[stage_name] = output
        self.save_state()

        return output

    def checkpoint(self, stage_name: str, validation_func=None) -> Dict:
        """Create a checkpoint for user validation"""
        logger.info(f"Checkpoint reached for stage: {stage_name}")

        # Get stage output
        stage_output = self.stage_outputs.get(stage_name, {})

        # Run validation if provided
        validation_results = {
            'passed': True,
            'warnings': [],
            'errors': []
        }

        if validation_func:
            validation_results = validation_func(stage_output)

        # Generate checkpoint report
        checkpoint_data = {
            'stage': stage_name,
            'timestamp': datetime.now().isoformat(),
            'validation_results': validation_results,
            'output_summary': self._generate_summary(stage_name, stage_output),
            'next_stage': self.STAGES[self.current_stage + 1] if self.current_stage + 1 < len(self.STAGES) else None,
            'can_proceed': validation_results['passed']
        }

        # Save checkpoint report
        checkpoint_file = self.project_dir / f"checkpoint_{stage_name}.json"
        with open(checkpoint_file, 'w') as f:
            json.dump(checkpoint_data, f, indent=2)

        return checkpoint_data

    def apply_user_modifications(self, stage_name: str, modifications: Dict):
        """Apply user modifications to stage output"""
        logger.info(f"Applying user modifications to stage: {stage_name}")

        # Store modifications
        if stage_name not in self.user_modifications:
            self.user_modifications[stage_name] = []

        self.user_modifications[stage_name].append({
            'timestamp': datetime.now().isoformat(),
            'modifications': modifications
        })

        # Apply modifications to stage output
        if stage_name in self.stage_outputs:
            self._apply_modifications(self.stage_outputs[stage_name], modifications)

        self.save_state()

    def proceed_to_next_stage(self) -> bool:
        """Move to the next stage if checkpoint is passed"""
        current_stage_name = self.get_current_stage_name()

        if current_stage_name == 'complete':
            logger.info("Pipeline already complete")
            return False

        # Mark current checkpoint as passed
        self.checkpoints_passed.append({
            'stage': current_stage_name,
            'timestamp': datetime.now().isoformat()
        })

        # Move to next stage
        self.current_stage += 1
        self.save_state()

        if self.current_stage < len(self.STAGES):
            logger.info(f"Proceeding to stage: {self.STAGES[self.current_stage]}")
            return True
        else:
            logger.info("Pipeline complete!")
            return False

    def restart_from_stage(self, stage_name: str):
        """Restart the pipeline from a specific stage"""
        if stage_name not in self.STAGES:
            raise ValueError(f"Unknown stage: {stage_name}")

        self.current_stage = self.STAGES.index(stage_name)
        self.save_state()
        logger.info(f"Pipeline restarted from stage: {stage_name}")

    def get_pipeline_status(self) -> Dict:
        """Get current pipeline status"""
        return {
            'project_name': self.project_name,
            'current_stage': self.get_current_stage_name(),
            'current_stage_index': self.current_stage,
            'total_stages': len(self.STAGES),
            'completed_stages': list(self.stage_outputs.keys()),
            'checkpoints_passed': len(self.checkpoints_passed),
            'has_modifications': bool(self.user_modifications),
            'project_directory': str(self.project_dir)
        }

    def generate_progress_report(self) -> str:
        """Generate a human-readable progress report"""
        status = self.get_pipeline_status()

        report = []
        report.append("=" * 60)
        report.append(f"Pipeline Progress Report: {status['project_name']}")
        report.append("=" * 60)
        report.append(f"Current Stage: {status['current_stage']} ({status['current_stage_index'] + 1}/{status['total_stages']})")
        report.append("")

        report.append("Stages Progress:")
        for i, stage in enumerate(self.STAGES):
            status_icon = "✓" if stage in status['completed_stages'] else ("→" if i == self.current_stage else "○")
            report.append(f"  {status_icon} {stage}")

        report.append("")
        report.append(f"Checkpoints Passed: {status['checkpoints_passed']}")
        report.append(f"User Modifications: {'Yes' if status['has_modifications'] else 'No'}")
        report.append(f"Project Directory: {status['project_directory']}")

        return "\n".join(report)

    def _generate_summary(self, stage_name: str, stage_output: Dict) -> Dict:
        """Generate a summary of stage output for checkpoint review"""
        summary = {}

        if stage_name == 'discovery':
            summary = {
                'business_name': stage_output.get('business_info', {}).get('name', 'Unknown'),
                'services_found': len(stage_output.get('services', [])),
                'pages_discovered': len(stage_output.get('pages', [])),
                'has_contact_info': bool(stage_output.get('contact', {}))
            }
        elif stage_name == 'ia_planning':
            summary = {
                'total_pages': len(stage_output.get('site_structure', {})),
                'service_categories': len(stage_output.get('service_taxonomy', {})),
                'navigation_items': len(stage_output.get('navigation', {}).get('primary_nav', [])),
                'url_structure': stage_output.get('url_pattern', 'Not defined')
            }
        elif stage_name == 'content_strategy':
            summary = {
                'content_templates': len(stage_output.get('page_templates', {})),
                'total_keywords': len(stage_output.get('keyword_mapping', {})),
                'content_outlines': len(stage_output.get('content_outlines', {})),
                'estimated_pages': stage_output.get('estimated_pages', 0)
            }
        elif stage_name == 'content_generation':
            summary = {
                'pages_generated': len(stage_output.get('generated_pages', {})),
                'total_word_count': stage_output.get('total_word_count', 0),
                'internal_links_created': stage_output.get('internal_links', 0),
                'seo_optimized': stage_output.get('seo_optimized', False)
            }
        elif stage_name == 'website_building':
            summary = {
                'php_files_created': stage_output.get('php_files_count', 0),
                'assets_copied': stage_output.get('assets_copied', False),
                'sitemap_generated': stage_output.get('sitemap_generated', False),
                'ready_for_deployment': stage_output.get('ready', False)
            }

        return summary

    def _apply_modifications(self, data: Dict, modifications: Dict):
        """Apply modifications to data recursively"""
        for key, value in modifications.items():
            if '.' in key:
                # Handle nested keys like 'business_info.name'
                keys = key.split('.')
                target = data
                for k in keys[:-1]:
                    if k not in target:
                        target[k] = {}
                    target = target[k]
                target[keys[-1]] = value
            else:
                data[key] = value


class PipelineValidator:
    """Validates stage outputs at checkpoints"""

    @staticmethod
    def validate_discovery(output: Dict) -> Dict:
        """Validate discovery stage output"""
        results = {
            'passed': True,
            'warnings': [],
            'errors': []
        }

        # Check for required fields
        if not output.get('business_info', {}).get('name'):
            results['errors'].append("Business name not found")
            results['passed'] = False

        if not output.get('services'):
            results['warnings'].append("No services found - will use default services")

        if len(output.get('services', [])) < 3:
            results['warnings'].append(f"Only {len(output.get('services', []))} services found - consider adding more")

        if not output.get('contact', {}).get('email') and not output.get('contact', {}).get('phone'):
            results['warnings'].append("No contact information found")

        return results

    @staticmethod
    def validate_ia_planning(output: Dict) -> Dict:
        """Validate IA planning stage output"""
        results = {
            'passed': True,
            'warnings': [],
            'errors': []
        }

        if not output.get('site_structure'):
            results['errors'].append("Site structure not defined")
            results['passed'] = False

        if not output.get('navigation'):
            results['errors'].append("Navigation not defined")
            results['passed'] = False

        # Check for minimum pages
        if len(output.get('site_structure', {})) < 5:
            results['warnings'].append("Less than 5 pages in site structure")

        return results

    @staticmethod
    def validate_content_strategy(output: Dict) -> Dict:
        """Validate content strategy stage output"""
        results = {
            'passed': True,
            'warnings': [],
            'errors': []
        }

        if not output.get('content_outlines'):
            results['errors'].append("No content outlines created")
            results['passed'] = False

        if not output.get('keyword_mapping'):
            results['warnings'].append("No keyword mapping defined")

        return results