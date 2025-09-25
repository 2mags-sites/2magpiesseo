# Process Update Template

## 🔴 MANDATORY: Update PROJECT_STATE.json at Each Step

### When Starting a New Step:

```bash
# At the START of each work session, update PROJECT_STATE.json with:
{
  "last_updated": "[TODAY'S DATE]",
  "current_phase": "Phase X: [PHASE NAME]",
  "current_step": "Working on: [SPECIFIC TASK]",
  "current_status": {
    "status": "IN PROGRESS - [WHAT YOU'RE DOING]",
    "next_action": "[WHAT NEEDS TO BE DONE NEXT]",
    "resume_point": "[WHERE TO PICK UP IF INTERRUPTED]"
  }
}
```

### When Completing a Step:

```bash
# At the END of each step, update PROJECT_STATE.json:
1. Move current phase to "phases_completed" array
2. Update "completed_tasks" with what was done
3. Update "remaining_tasks" by removing completed items
4. Set "current_step" to next action or "PAUSED - [REASON]"
```

---

## Process State Examples

### Example 1: Starting Design Work
```json
{
  "current_phase": "Phase 11: Design Integration",
  "current_step": "Sending pages to designer",
  "current_status": {
    "status": "IN PROGRESS - Preparing designer package",
    "next_action": "Wait for designer to create Figma templates",
    "resume_point": "Designer package sent, waiting for Figma URL"
  }
}
```

### Example 2: Waiting for External Input
```json
{
  "current_phase": "Phase 11: Design Integration",
  "current_step": "Waiting for designer",
  "current_status": {
    "status": "PAUSED - Awaiting designer templates (expected: 2024-01-20)",
    "next_action": "Apply Figma design once received",
    "resume_point": "Step 4.6: Figma Fetch & Apply (DESIGN_INTEGRATION_PROCESS.md)"
  }
}
```

### Example 3: Resuming After Break
```json
{
  "current_phase": "Phase 11: Design Integration",
  "current_step": "Applying Figma design",
  "current_status": {
    "status": "IN PROGRESS - Fetching design from Figma",
    "next_action": "Apply design tokens to all pages",
    "resume_point": "Figma fetched, need to apply to service pages"
  }
}
```

---

## Critical Fields to Track

### Always Update These:
- `last_updated` - Today's date
- `current_phase` - What major phase you're in
- `current_step` - Specific task within the phase
- `status` - IN PROGRESS, PAUSED, COMPLETED, BLOCKED
- `next_action` - Clear instruction for next step
- `resume_point` - Exact place to continue + which doc to reference

### Track Deliverables:
- Files created
- URLs generated
- Passwords/keys set
- External dependencies (designer work, client feedback)

---

## Resume Checklist

When returning to a project after a break:

1. **Check PROJECT_STATE.json first**
   - What phase were we in?
   - What's the resume_point?
   - Any pending external inputs?

2. **Check for time-sensitive items**
   - Designer deliverables expected?
   - Client feedback pending?
   - Cache that needs clearing?

3. **Verify environment**
   - Is WordPress still running?
   - Are all paths still valid?
   - Any config changes needed?

4. **Update status to "IN PROGRESS"**
   - Change from PAUSED to IN PROGRESS
   - Update last_updated date
   - Note what you're resuming

---

## Standard Process Phases

1. **Phase 1**: Discovery & Analysis
2. **Phase 2**: Approach Selection
3. **Phase 3**: Architecture Planning
4. **Phase 4**: Information Architecture
5. **Phase 5**: Keyword Mapping
6. **Phase 6**: Content Structure
7. **Phase 7**: HTML Preview Generation
8. **Phase 8**: PHP Website Generation
9. **Phase 9**: WordPress Installation
10. **Phase 10**: Blog Integration
11. **Phase 11**: Design Integration ← Current decision point
12. **Phase 12**: Testing & Optimization
13. **Phase 13**: Production Deployment

---

## Integration with Documentation

Each phase should reference its documentation:
- Blog Integration → BLOG_INTEGRATION_PROCESS.md
- Design Integration → DESIGN_INTEGRATION_PROCESS.md
- Production → PRODUCTION_DEPLOYMENT_CHECKLIST.md
- Caching → Cache section in BLOG_INTEGRATION_PROCESS.md

This ensures you always know WHERE to find the detailed instructions for resuming work.