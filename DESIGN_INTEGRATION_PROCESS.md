# Design Integration Process

## Step 4.5: Design Integration Decision Point

After content generation but before final styling, you need to choose your design approach:

### 🎨 Design Approach Selection

**Question: How will you be handling the design for this website?**

Choose one of the following approaches:

---

### Option A: Custom Design with Designer
**"I'm working with a designer who will create custom templates"**

#### Process:
1. **Select 3 Representative Pages** to send to designer:
   - `index.php` - Homepage (unique layout)
   - One service page (e.g., `service-cremation.php`) - Template for all services
   - One location page (e.g., `funeral-directors-sale.php`) - Template for all locations

2. **Package for Designer**:
   ```bash
   # Create a designer package with the 3 pages
   mkdir designer-package
   cp index.php designer-package/
   cp service-cremation.php designer-package/
   cp funeral-directors-sale.php designer-package/
   cp -r assets designer-package/
   ```

3. **Designer Brief Should Include**:
   - These 3 pages with real content
   - List of all pages that will use each template
   - Any specific component needs (blog cards, FAQ sections, etc.)
   - Mobile responsiveness requirements

4. **Designer Delivers**:
   - Figma file with 3 page designs
   - Component library
   - Style guide with colors, fonts, spacing

5. **Continue to**: Figma Fetch & Apply (Step 4.6)

---

### Option B: Pre-made Figma Design
**"I'm using an existing Figma template/design"**

#### Process:
1. **Find Suitable Design**:
   - Browse Figma Community
   - Purchase premium template
   - Or use client-provided design

2. **Analyze Component Compatibility**:
   ```markdown
   Design Components Checklist:
   - [ ] Header/Navigation - Can it handle our menu structure?
   - [ ] Hero Section - Works for homepage?
   - [ ] Service Cards - Adaptable for funeral services?
   - [ ] Location Cards - Can show location info?
   - [ ] Content Sections - Flexible enough?
   - [ ] Footer - Room for all our links?
   - [ ] Blog/News Grid - Compatible with WordPress posts?
   - [ ] Contact Forms - Appropriate styling?
   ```

3. **Document Component Mapping**:
   ```json
   {
     "design_to_content_mapping": {
       "Hero Component": "homepage hero",
       "Card Component": "service cards, location cards",
       "Article Card": "blog posts",
       "CTA Section": "contact sections",
       "ignore": ["team section", "portfolio", "app download"]
     }
   }
   ```

4. **Continue to**: Figma Fetch & Adapt (Step 4.6)

---

## Step 4.6: Figma Fetch & Apply

### For Both Options:

1. **Use Figma MCP to fetch design**:
   - Design tokens (colors, typography, spacing)
   - Component specifications
   - Layout grids and breakpoints

2. **Generate CSS Framework**:
   ```css
   /* Design tokens from Figma */
   :root {
     --color-primary: [from Figma];
     --color-secondary: [from Figma];
     --font-heading: [from Figma];
     --spacing-unit: [from Figma];
   }
   ```

3. **Apply Design System**:

   #### Option A (Custom Design):
   - Direct application - components match content exactly
   - All pages get their designated template
   - No content adjustment needed

   #### Option B (Pre-made Design):
   - Selective application - use only relevant components
   - May need minor content adjustments:
     - Text length optimization
     - Image aspect ratio adjustments
     - Section reordering if needed
   - Ignore irrelevant components

---

## Step 4.7: Template Application

### Systematic Rollout:

```markdown
Template Application Map:
├── Unique Pages (apply individually):
│   ├── index.php → Homepage design
│   ├── contact.php → Contact page design
│   └── about.php → About page design
│
├── Template-based Pages (apply template to all):
│   ├── Service Template → All 4 service pages
│   │   ├── service-traditional-burial.php
│   │   ├── service-cremation.php
│   │   ├── service-direct-cremation.php
│   │   └── pre-paid-plans.php
│   │
│   └── Location Template → All 7 location pages
│       ├── funeral-directors-sale.php
│       ├── funeral-directors-stretford.php
│       ├── funeral-directors-altrincham.php
│       └── ... (all location pages)
```

---

## Decision Criteria

### Choose Custom Design (Option A) when:
- Client has specific brand requirements
- Budget allows for designer
- Content is unique/complex
- Industry has specific design needs
- Time allows for design iteration

### Choose Pre-made Design (Option B) when:
- Quick turnaround needed
- Budget conscious project
- Client likes existing design
- Standard business type
- Design inspiration needed

---

## Important Notes

1. **Keep HTML Semantic & Flexible**: During initial generation (Step 4), use semantic HTML with flexible class names that can accept either design approach

2. **Component-based Thinking**: Structure content in reusable blocks that can be styled differently:
   ```html
   <section class="hero" data-component="hero">
   <article class="card" data-component="service-card">
   <div class="content-block" data-component="features">
   ```

3. **Don't Over-commit Early**: Avoid design-specific decisions in content generation phase

4. **Document Your Choice**: Record which approach was used for future maintenance:
   ```json
   {
     "project": "kershaw-funeral",
     "design_approach": "custom|premade",
     "figma_url": "...",
     "templates_created": ["homepage", "service", "location"]
   }
   ```

---

## Next Steps

After design is applied:
- Step 5: Blog Integration (if needed)
- Step 6: Testing & Optimization
- Step 7: Deployment