# /phpsite Command Documentation

## Overview
The `/phpsite` command is a Claude-orchestrated workflow for building complete, SEO-optimized PHP websites. Unlike traditional scrapers that use regex patterns, this command leverages Claude's AI capabilities to intelligently analyze websites, extract services, create information architecture, and generate rich content before building the final PHP website.

## Command Purpose
Transform any existing website into a modern, SEO-optimized PHP website with AI-generated content, proper information architecture, and strategic keyword targeting.

## Key Differences from Traditional Approach
- **AI-Driven Analysis**: Claude analyzes content, not Python regex
- **Intelligent Service Extraction**: Understands context and relationships
- **Strategic IA Planning**: Creates proper hierarchy based on SEO best practices
- **Content Generation**: Rich, unique content for each page
- **Interactive Workflow**: User validates each step before proceeding

## Command Flow

### CRITICAL: Documentation Requirements

**📚 ESSENTIAL TEMPLATE DOCUMENTATION TO REFERENCE:**
- `DEPLOYMENT_STRATEGY.md` - **CRITICAL: Read before any deployment - contains ALL deployment procedures**
- `templates/SETUP_NEW_PROJECT.md` - Complete project setup checklist with all files to copy
- `templates/core/SESSION_CSRF_GUIDE.md` - Critical session/CSRF implementation guide
- `templates/core/config.php` - Proper session handling and CSRF token generation
- `templates/core/email-service.php` - SendGrid + PHP mail() fallback implementation
- `templates/core/env-loader.php` - Environment variable loader (CRITICAL - missing causes 500 errors)

**MANDATORY PROJECT STRUCTURE:**
Every project MUST maintain this folder structure:
```
project-[business-name]/
├── 01-scraping/
│   ├── scraped-data.json       # Raw scraped data
│   ├── business-info.json      # Extracted business details
│   └── existing-content.md     # Content summary
├── 02-planning/
│   ├── information-architecture.json
│   ├── navigation-structure.json
│   └── page-list.json
├── 03-seo/
│   ├── keyword-mapping.json    # Keywords for EVERY page
│   ├── meta-descriptions.json
│   └── schema-templates.json
├── 04-content/
│   ├── content-requirements.json
│   ├── service-descriptions.json
│   └── location-data.json
├── 05-design/
│   ├── design-decisions.json
│   └── component-list.json
├── html-preview/               # HTML preview files
├── php-website/                # Final PHP website
│   ├── includes/
│   ├── assets/
│   └── [PHP files]
└── PROJECT_STATE.json         # Current project status
```

**AT EACH PHASE:**
1. **READ** existing documentation first
2. **DOCUMENT** all decisions in JSON files
3. **UPDATE** PROJECT_STATE.json
4. **REFERENCE** documented data (never hallucinate)
5. **VALIDATE** against previous phase data

**CONTEXT RECOVERY:**
If context is lost and conversation resumes:
1. First action: Read PROJECT_STATE.json
2. Load relevant phase documentation
3. Continue from documented state
4. Never make up missing information

### Phase 1: Initial Input
```
User: /phpsite

Claude: Welcome to PHP Site Builder! What's the URL of the website you want to rebuild?

User: [provides URL]
```

### Phase 2: Discovery & Analysis
Claude performs:
1. Fetches website content using WebFetch/Bash tools
2. **CAPTURES FULL TEXT** from all main pages (not summaries)
3. Analyzes exact tone of voice and language patterns
4. Extracts services with complete descriptions
5. Documents all specific phrases and terminology used

**CRITICAL: Must capture:**
- Complete page text (verbatim)
- Exact service descriptions
- Specific language/phrases used
- Tone indicators (formal/casual/compassionate)
- All prices and specifications mentioned

**DOCUMENTATION CREATED:**
- `01-scraping/scraped-pages/` - Full text of each page
- `01-scraping/business-info.json` - Structured business data
- `01-scraping/BUSINESS_COMPLETE.md` - Comprehensive narrative with tone
- `01-scraping/tone-analysis.md` - Language patterns and style guide

**WARNING:** Never proceed without capturing actual content.
Do NOT generate content without source material to reference.

Output presented:
```
📊 Website Analysis Complete!

Business: [Name]
Type: [Industry]
Services Found: [Count]
Contact: [Details]

Services Discovered:
1. [Service Category]
   - [Sub-service 1]
   - [Sub-service 2]
2. [Service Category]
   - [Sub-service 1]
   - [Sub-service 2]
```

### Phase 3: Site Architecture Style Decision
```
Claude: What type of website architecture would you prefer?

1. 📱 Modern Single-Page Homepage + SEO Pages
   - Homepage: Long scrolling with all key sections
   - Sections: Hero, Services overview, About, Testimonials, Contact
   - SEO Pages: Separate pages for each service (for ranking)
   - Best for: Modern look, mobile-first, storytelling flow

2. 📑 Traditional Multi-Page Website
   - Homepage: Concise with clear navigation to other pages
   - All content distributed across pages
   - Each section gets its own dedicated page
   - Best for: Information-heavy sites, traditional navigation

3. 🔄 Hybrid Approach
   - Homepage: Medium-length with key highlights
   - Major sections on homepage with "Learn More" links
   - Detailed content on separate pages
   - Best for: Balance of modern and traditional

User: [chooses option]
```

### Phase 4: SEO Strategy Decision
Based on architecture choice, determine page depth:

```
Claude: How many SEO pages should we create?

1. Full SEO Approach - All individual service pages (24+ pages)
2. Hybrid Approach - Main categories + key services (12-15 pages)
3. Minimal Approach - Category pages only (6 pages)

User: [chooses option]
```

### Phase 4: Information Architecture & Validation

#### Step 4a: Page List Approval
Claude first presents the complete page list:

```
📋 Proposed Site Structure (X pages):

Main Pages:
1. Homepage
2. About Us
3. Contact

Service Pages:
4. [Service 1]
5. [Service 2]
...

Location Pages (if applicable):
...

Support Pages:
...

Would you like to:
1. ✅ Approve this structure
2. ➕ Add pages
3. ➖ Remove pages
4. 🔄 Reorganize

Your choice:
```

#### Step 4a: Contact Form Requirement Check (REQUIRED)

```
📧 CONTACT FORM SETUP
Would you like to add contact forms to your website?

1. ✅ Yes - Add contact forms
2. ❌ No - Static contact info only

Your choice: [USER INPUT]
```

**If YES, ask:**
```
What type of contact form?
1. Simple Contact Form (Name, Email, Phone, Message)
2. Service Inquiry Form (with service dropdown)
3. Consultation Request (with date/time preferences)
4. Business Contact (company details, budget)
5. Custom Form

Your choice: [USER INPUT]

EMAIL CONFIGURATION:
1. Who should receive submissions? [email]
2. BCC recipients? [comma-separated or none]
3. From Name in emails? [e.g., "ABC Company Website"]
4. Sender email address? [e.g., "noreply@domain.com"]

SECURITY:
Honeypot & rate limiting will be added automatically.
Add reCAPTCHA? (1=No, 2=Yes with keys, 3=Need help): [USER INPUT]
```

#### Step 4b: Blog Requirement Check (REQUIRED)

```
📝 BLOG/NEWS SECTION
Would you like to include a blog section on your website?

1. ✅ Yes - Include blog with latest news on homepage
2. ❌ No - No blog needed

Your choice: [USER INPUT]
```

**If YES, ask:**
```
What folder name would you like for the blog?
Common choices: blog, news, updates, articles, insights

Folder name: [USER INPUT]
```

**Configuration**:
- WordPress will be installed at /[folder-name]/
- Homepage will display 4 latest posts as cards (near bottom)
- REST API endpoint: /[folder-name]/wp-json/wp/v2/posts
- Include link in main navigation (e.g., "Blog", "News", "Articles")

#### Step 4b: Information Architecture Diagram (REQUIRED)
After page approval, present the FULL hierarchy:

```
🏗️ INFORMATION ARCHITECTURE DIAGRAM

Homepage
├── About
│   └── [About pages if multiple]
├── Services
│   ├── [Service Category 1]
│   │   ├── Service 1
│   │   └── Service 2
│   └── [Service Category 2]
│       ├── Service 3
│       └── Service 4
├── Locations (if applicable)
│   ├── Location 1
│   └── Location 2
├── Resources/Information
│   ├── FAQs
│   ├── Guides
│   └── Testimonials
└── Contact

URL Structure:
/ (homepage)
/about
/services/[service-name]
/locations/[location-name]
/[resource-name]
/contact
```

#### Step 4c: Navigation Structure (REQUIRED)
Present the navigation plan:

```
🔧 NAVIGATION STRUCTURE

Main Navigation Bar:
- Home
- About [dropdown if multiple pages]
- Services ▼
  └── Service 1
  └── Service 2
  └── Service 3
- Locations ▼ (if applicable)
  └── Location 1
  └── Location 2
- Resources ▼
  └── FAQs
  └── Guides
- Contact

Mobile Navigation:
[Describe mobile menu structure]

Footer Navigation:
Column 1: Services
Column 2: Locations
Column 3: Resources
Column 4: Contact Info

Approve navigation structure?
1. ✅ Yes, proceed
2. 🔄 Adjust navigation grouping
3. ➖ Simplify navigation
4. ➕ Add navigation items

Your choice:
```

**User Checkpoint**: Must validate BOTH IA diagram AND navigation before proceeding

### Phase 5: Keyword Mapping & Validation
Claude generates:
- Primary keyword for each page
- Secondary keywords (2-3 per page)
- Long-tail keywords (3-5 per page)
- Search intent mapping

**DOCUMENTATION CREATED:**
- `03-seo/keyword-mapping.json` - Complete keyword map for ALL pages
- `03-seo/meta-descriptions.json` - Meta descriptions for each page
- This data MUST be referenced when creating PHP meta tags

```
📍 Keyword Strategy:
/services/company-formation
  Primary: "Hong Kong company formation"
  Secondary: "incorporate company HK", "company registration Hong Kong"
  Long-tail: "how to register company in Hong Kong", "Hong Kong company setup cost"

[Shows all pages with keywords...]

Review Options:
1. ✅ Approve keyword strategy
2. ✏️ Edit keywords for specific pages
3. ➕ Add additional keywords
4. 🎯 Focus on different primary keywords
5. 📊 View keyword difficulty/volume (if available)

Your choice:
```

User can then:
- Approve and continue
- Edit specific page keywords
- Add competitor keywords they know about
- Refocus based on business priorities

### Phase 6: Content Generation & Preview

#### Phase 6a: Service Page Structure Decision
Before generating service pages, Claude asks:

```
📐 Service Page Layout Options:

1. Full Width (12 columns)
   - Content spans entire width
   - No sidebar
   - Best for: Content-focused pages, visual presentations
   - **INCLUDES: Side-by-side text/image layouts (60/40 or 50/50) to break up content**
   - **Images alternate left/right positioning for visual variety**

2. Content + Sidebar (8-4 split)
   - Main content: 8 columns
   - Sidebar: 4 columns (contact CTA, quick facts, resources)
   - Best for: Service pages with calls-to-action

3. Content + Narrow Sidebar (9-3 split)
   - Main content: 9 columns
   - Sidebar: 3 columns (minimal info)
   - Best for: Content-heavy pages with light CTAs

Your choice: [User selects]
```

**⚠️ CRITICAL: Full Width Layout Requirements**
If user selects Option 1 (Full Width):
- MUST include side-by-side content-with-image sections
- Use flexbox layouts with proper responsive behavior
- Text takes 60% (flex: 1.2), images 40% (flex: 0.8)
- Or 50/50 split (both flex: 1) for equal distribution
- Alternate image positions (left/right) throughout page
- Mobile: Stack vertically with min-height: 200px !important
- **CRITICAL: Use proper image placeholders, NOT icons (see Image Placeholder Guidelines below)**

### ⚠️ CRITICAL: Image Placeholder Guidelines

**NEVER USE ICON PLACEHOLDERS LIKE THIS:**
```html
<!-- ❌ WRONG - Don't use icons in divs -->
<div class="content-image">🏆</div>
<div class="content-image">🔧</div>
<div class="content-image">📍</div>
```

**ALWAYS USE PROPER IMAGE PLACEHOLDERS:**

#### For HTML Preview Phase:
```html
<!-- ✅ CORRECT - Use placehold.co with FULL descriptive text -->
<div class="content-image">
    <img src="https://placehold.co/600x400/e5e7eb/6b7280?text=Team+photo+showing+mechanics+at+work+in+garage" alt="Team photo" />
</div>

<!-- More examples -->
<img src="https://placehold.co/600x400/e5e7eb/6b7280?text=Professional+garage+exterior+with+signage" alt="Garage exterior" />
<img src="https://placehold.co/600x400/e5e7eb/6b7280?text=MOT+testing+bay+with+vehicle+on+ramp" alt="MOT testing bay" />
<img src="https://placehold.co/600x400/e5e7eb/6b7280?text=Mechanic+performing+brake+disc+replacement" alt="Brake repair" />
<img src="https://placehold.co/600x400/e5e7eb/6b7280?text=Happy+customer+receiving+car+keys" alt="Happy customer" />
```

**Placehold.co Text Guidelines:**
- Use FULL descriptions of what image should be
- Use + for spaces in URL
- Be specific about what should be shown
- Include context (e.g., "in garage", "at desk", "modern equipment")

#### For PHP Implementation Phase:
```php
<!-- ✅ CORRECT - Use editableImage with THE SAME description -->
<div class="content-image">
    <?php echo editableImage('', 'about.image', 'Team photo showing mechanics at work in garage', 'Premier Garage Team'); ?>
</div>
```

**CRITICAL: When converting HTML to PHP:**
1. Replace ALL placehold.co URLs with editableImage() calls
2. Use THE SAME description text (just without the + signs)
3. Add proper field paths for JSON storage
4. Ensure alt text is descriptive

**Conversion Example:**
```html
<!-- HTML Preview -->
<img src="https://placehold.co/600x400/e5e7eb/6b7280?text=Team+photo+showing+mechanics+at+work+in+garage" alt="Team photo" />

<!-- Converts to PHP (same description, just cleaner format) -->
<?php echo editableImage('', 'about.image', 'Team photo showing mechanics at work in garage', 'Premier Garage Team'); ?>
```

**PHP Helper Functions to Include in admin-config.php:**
```php
/**
 * Generate a placeholder image with descriptive text
 */
function placeholderImage($text, $width = 600, $height = 400) {
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '">';
    // ... SVG generation with text describing what image should go here
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

/**
 * Make an image editable in admin mode
 */
function editableImage($currentSrc, $fieldPath, $placeholder, $alt = '') {
    if (empty($currentSrc)) {
        $currentSrc = placeholderImage($placeholder);
    }
    // ... Return editable image HTML
}
```

**CSS Requirements for Images:**
```css
.content-image {
  width: 100%;
  height: 400px;
  border-radius: var(--border-radius);
  overflow: hidden;
  position: relative;
}

.content-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
```

**Benefits of This Two-Stage Approach:**
1. Admin can see exactly what image should go there
2. Easy to click and replace in admin mode
3. Maintains proper aspect ratios
4. No confusing small icons
5. Professional appearance even with placeholders

#### Phase 6b: Content Structure Proposal
Claude proposes content structure for service pages:

```
📝 Proposed Content Structure for Service Pages:

Based on your business type, I suggest the following sections:

1. Hero Section
   - Service name, tagline, breadcrumbs

2. Overview Section
   - What is this service?
   - Who is it for?
   - Key benefits summary

3. Detailed Services/Features
   - Service components breakdown
   - What's included
   - Deliverables

4. Process/Methodology
   - How we work
   - Timeline/steps
   - Our approach

5. Why Choose Us/Benefits
   - Unique value propositions
   - Expertise highlights
   - Success metrics

6. FAQ Section (5-6 questions)
   - Common concerns
   - Process questions
   - Pricing/timeline queries

7. Call-to-Action
   - Contact prompt
   - Consultation offer

Would you like to:
1. ✅ Approve this structure
2. ➕ Add sections (specify which)
3. ➖ Remove sections (specify which)
4. 🔄 See alternative structure

Your choice:
```

**Alternative Content Structures Available:**

**Option A: Problem-Solution Focus**
```
1. Hero Section
2. The Challenge (problems this service solves)
3. Our Solution (how we address these problems)
4. Success Stories/Case Studies
5. Our Approach/Methodology
6. Results & Benefits
7. FAQ Section
8. Get Started CTA
```

**Option B: Feature-Benefit Focus**
```
1. Hero Section
2. Service Overview
3. Key Features (what we do)
4. Benefits (what you get)
5. How It Works (process)
6. Pricing/Packages (if applicable)
7. FAQ Section
8. Contact CTA
```

**Option C: Educational Focus**
```
1. Hero Section
2. Understanding [Service Topic]
3. Why [Service] Matters
4. Our Expertise
5. Service Breakdown
6. Common Misconceptions
7. FAQ Section
8. Free Consultation CTA
```

**Option D: Results-Driven Focus**
```
1. Hero Section
2. Results We Deliver
3. Our Track Record
4. How We Achieve Results
5. Client Success Stories
6. Your Expected Outcomes
7. FAQ Section
8. Start Your Success Story CTA
```

**Page Structure Standards:**
1. **Homepage ONLY**
   - Include full HTML with header navigation and footer
   - Self-contained page for standalone preview
   - All other pages assume PHP includes for header/footer

2. **ALL Other Pages (Services, About, Contact, etc.)**
   - Generate ONLY the content area (no header/footer HTML)
   - Start directly with hero section or main content
   - These will ALL use PHP includes in final build
   - Follow the USER-APPROVED structure from Phase 6b

**Service Page Template Structure (Based on User's Layout Choice):**

**Option 1: Full Width (No Sidebar)**
```html
<!-- Service Hero Section -->
<section class="service-hero">
    <div class="container">
        <nav aria-label="breadcrumb">...</nav>
        <h1>[Service Name]</h1>
        <p class="lead">[Tagline]</p>
    </div>
</section>

<main class="section-padding">
    <div class="container">
        <!-- Full width content -->
        <div class="col-lg-12">
            [USER-APPROVED CONTENT SECTIONS]
        </div>

        <!-- CTA Section -->
        <div class="cta-box">
            [Call to action]
        </div>
    </div>
</main>
```

**Option 2: With Sidebar (8-4 or 9-3 split)**
```html
<!-- Service Hero Section -->
<section class="service-hero">
    <div class="container">
        <nav aria-label="breadcrumb">...</nav>
        <h1>[Service Name]</h1>
        <p class="lead">[Tagline]</p>
    </div>
</section>

<main class="section-padding">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-[8 or 9]">
                [USER-APPROVED CONTENT SECTIONS]
            </div>

            <!-- Sidebar (if chosen) -->
            <div class="col-lg-[4 or 3]">
                [SIDEBAR CONTENT AS SPECIFIED]
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-box">
            [Call to action]
        </div>
    </div>
</main>
```

**Content Sections (Generated based on user approval):**
The exact sections included will match what the user approved in Phase 6b.
Each section follows the agreed structure, not a rigid template.

**Content Requirements for Service Pages:**
- Hero section with compelling headline
- 1500+ words of unique, relevant content
- Service/practice area descriptions
- Process explanations with timelines
- Benefits sections
- 5-6 contextual FAQs (REQUIRED)
- Sidebar with contact info (REQUIRED) - if using sidebar layout
- Call-to-action sections (REQUIRED)
- Internal links to related services

**⚠️ CRITICAL SERVICE PAGE TEMPLATE REQUIREMENTS:**
When generating service pages, MUST maintain EXACT consistency:

1. **CSS Variables & Styling:**
   - Use EXACT same :root CSS variables across ALL pages
   - Primary color scheme must match homepage
   - Font family must be consistent
   - Button styles must be identical

2. **Required Sections (in order):**
   - Service Hero section (with breadcrumb)
   - Main content with side-by-side image layouts
   - Process/Timeline section (if applicable)
   - Benefits/Features section
   - FAQ Section (REQUIRED - 5-6 questions minimum)
   - CTA Box Section (REQUIRED - with phone numbers)

3. **Image Layout Pattern:**
   - Use content-with-image flex containers
   - 60/40 or 50/50 text-to-image ratio
   - Alternate left/right positioning
   - Mobile: Stack vertically with 200px min-height
   - **CRITICAL Mobile Fix:**
     ```css
     @media (max-width: 768px) {
         .content-image {
             min-height: 200px !important;
             height: 200px !important;
             flex: none !important;
         }
     }
     ```

4. **FAQ Implementation:**
   - Interactive accordion style
   - Consistent styling with chevron icons
   - JavaScript toggle functionality

5. **CTA Box Requirements:**
   - Background gradient matching brand
   - All office phone numbers
   - Contact button linking to contact page
   - Placed as final content section

```
✍️ Content Generation Process:

1. **FIRST:** Create `styles.css` with all design system styles
2. Generate homepage HTML linking to `styles.css`
3. Create service pages linking to the same `styles.css`

**Generation Order:**
```
Step 1: Create styles.css (complete CSS file)
Step 2: Create index.html (links to styles.css)
Step 3: Create service pages (all link to styles.css)
```

**BEFORE generating each new service page, ALWAYS state:**
"I'm about to create the [Service Name] page. I'll ensure it includes:
- ✅ Links to shared styles.css (NO inline styles)
- ✅ Hero section with breadcrumb
- ✅ Content sections with 60/40 image layouts (if full width layout)
- ✅ Process steps with green circular numbers (if applicable)
- ✅ 5-6 FAQ questions with accordion functionality
- ✅ CTA box with all office phone numbers
- ✅ Consistent HTML structure and class names
- ✅ Mobile-responsive image height fixes

Creating the page now..."

After each service page example:
"I've created the [Service Name] page. Would you like to:
1. 📄 See another service page example
2. ✅ Proceed to generate all remaining pages
3. ✏️ Adjust the content style/format

Your choice:"

⚠️ CRITICAL: Generation Approach to Avoid Inconsistency
- Generate pages ONE AT A TIME or in SMALL BATCHES (max 3-4)
- NEVER batch generate more than 4 pages at once
- Use the EXACT template structure for every service page
- Do NOT delegate to Task tool for bulk generation
- Verify each page has all mandatory sections before moving on
- ALWAYS copy CSS variables and structure from first approved service page
- Check that FAQ and CTA sections are present before saving

✓ Homepage - 2000 words
✓ Company Formation - 1800 words (verified: 8-4 layout, sidebar, FAQs)
✓ Litigation Services - 1650 words (verified: 8-4 layout, sidebar, FAQs)
✓ [Continue ONE BY ONE with verification...]
```

**Live Preview Process:**
1. Claude generates temporary HTML file with CSS
2. Saves to `preview/index.html`
3. Starts local server: `php -S localhost:8002`
4. Opens browser to `http://localhost:8002/`
5. User can interact with actual page
6. User provides feedback or approves

**Preview Structure for Consistency:**
- Homepage preview: Full page with navigation, hero, content sections, and footer
- Service page previews: Content area only with notice that header/footer will be PHP components
- All previews use shared CSS variables and design system for consistency

**⚠️ CRITICAL: CSS SEPARATION STRATEGY**
1. Create `styles.css` FIRST - before any HTML pages
2. ALL pages must link to this single CSS file
3. NO page should have ANY inline <style> tags
4. This ensures 100% consistency across all pages

File structure during preview phase:
```
/preview-[sitename]/
├── styles.css          # SINGLE source of truth for ALL styling
├── index.html          # Homepage (links to styles.css)
├── service-*.html      # Service pages (all link to styles.css)
└── contact.html        # Contact page (links to styles.css)
```

**styles.css MUST include:**
- CSS variables for colors, fonts, spacing
- All component styles (hero, content sections, FAQ, CTA)
- Process step styling with green circular numbers
- Content-with-image flexbox layouts
- Mobile responsive rules with image height fixes
- Button and link styles
- Consistent typography scale

**Component-Based Architecture (Final PHP Build):**
```
/components/
├── header.php         # Shared navigation across all pages
├── footer.php         # Shared footer with contact, services, social
├── sidebar.php        # Reusable sidebar template for service pages
├── schema.php         # Schema.org structured data generation
└── styles.css         # Shared CSS for design consistency

/services/
├── company-formation.php
├── litigation.php
└── [other service pages]

Service page PHP structure:
<?php include '../components/header.php'; ?>
<div class="container">
  <div class="row">
    <div class="col-lg-8">
      <!-- Page-specific content -->
    </div>
    <div class="col-lg-4">
      <?php include '../components/sidebar.php'; ?>
    </div>
  </div>
</div>
<?php include '../components/footer.php'; ?>

Main page PHP structure (Home, About, Contact):
<?php include 'components/header.php'; ?>
<!-- Full page content -->
<?php include 'components/footer.php'; ?>
```

**Preview Must Include:**
- Complete navigation with dropdowns
- Hero section
- All main content sections
- Footer with:
  - Service links (organized by category)
  - Contact information
  - Office hours
  - Social media links
  - Legal disclaimers
  - Copyright notice
  - Newsletter signup (optional)
  - Partner/association logos

### Phase 6b: Content Validation
Before creating the review page, validate consistency:

```
⚠️ Validation Checklist:
- All service pages have 8-4 column layout
- All service pages include sidebar with contact CTA
- All service pages have 5-6 FAQs
- No service pages have duplicate headers/footers
- Main pages (Home, About, Contact) have full HTML structure
- All pages have consistent meta tags and keywords
```

### Phase 6c: Project Review Page
After all content pages are generated and validated, Claude creates a review page:

```
📋 Creating Project Review Page...

All pages have been generated!

Created: preview/project-review.html

This page lists all 26 pages with their target keywords.
You can review all pages from this central location.

Open http://localhost:8002/project-review.html to review all pages.

Ready to proceed with PHP generation? (yes/no):
```

**Review Page Contains:**
- Simple list of all pages organized by category
- Target keywords for each page
- Links to preview completed pages
- Clean, minimal design for easy review

### Phase 7: Final Build
Claude prepares structured JSON with all content and calls Python script.

**SEO Enhancements Added at PHP Stage:**
- Schema.org structured data (FAQPage, LocalBusiness, Service)
- Open Graph meta tags
- Twitter Card meta tags
- JSON-LD for breadcrumbs
- Sitemap.xml generation
- Robots.txt

**CRITICAL: .htaccess File Creation**
- **DO NOT create .htaccess during development phase**
- .htaccess can interfere with PHP built-in development server
- Create .htaccess ONLY as the final step before deployment
- During development, use PHP files with .php extensions
- Clean URLs will be handled by .htaccess in production only

**Note:** These are NOT included in HTML previews to maintain clean separation of concerns.

```python
# Claude generates complete JSON structure
site_data = {
    "business_info": {...},
    "navigation": {...},
    "pages": {
        "index": {
            "meta": {...},
            "content": {...}
        },
        "services-company-formation": {
            "meta": {...},
            "content": {...}
        }
    }
}

# Calls Python script to build PHP
python claude_phpsite.py --data [json_data]
```

### Phase 7.5: PHP Conversion - SECURE EDITABLE WEBSITE ARCHITECTURE

**🚨 CRITICAL: USE STANDARDIZED DROP-IN COMPONENTS**

**📖 REFERENCE DOCUMENTATION:**
For complete implementation details, refer to **`EDITABLE_PHP_GUIDE.md`** which contains:
- Full admin mode setup instructions
- JSON content structure guidelines
- Hero background image editing setup
- Security best practices
- Troubleshooting guide

**MANDATORY: Copy These Drop-in Components for Admin Functionality**

**🔴 CRITICAL EMAIL SYSTEM COMPONENTS (Updated):**
- Follow `templates/SETUP_NEW_PROJECT.md` for complete setup checklist
- Read `templates/core/SESSION_CSRF_GUIDE.md` for session/CSRF fixes
1. **`templates/core/admin-upload.php`** → Copy to project root
   - Production-ready image upload handler
   - Validates file types, sizes, and permissions
   - Saves to `/assets/images/uploads/`

2. **`templates/core/admin-save.php`** → Copy to project root
   - Handles all content saving with dot notation
   - Includes updateNestedValue() function
   - CSRF protection included

3. **`templates/core/admin-functions.js`** → Include in footer.php
   - Complete admin JavaScript functionality
   - Text editing, image uploads, FAQ management
   - Add/remove FAQ capabilities

4. **`templates/core/email-service.php`** → Copy to includes/
   - Complete email service with SendGrid + PHP mail() fallback
   - Replaces old sendgrid-mailer.php
   - Includes contact form email templates

5. **`templates/core/env-loader.php`** → Copy to includes/ **[CRITICAL]**
   - Environment variable loader
   - **MUST EXIST or contact forms will throw 500 error**
   - Provides EnvLoader::get() method

6. **`templates/core/config.php`** → Copy to includes/
   - **CRITICAL: Has proper session_status() checking**
   - Generates CSRF tokens correctly
   - Includes business info configuration

7. **`templates/core/contact-handler.php`** → Copy to project root
   - Handles form submissions with CSRF protection
   - Localhost CSRF bypass for development
   - Rate limiting and honeypot protection

**Environment Variable Setup for All Projects**
1. Copy `templates/.env.template` to project as `.env`
2. Copy `templates/core/env-loader.php` to `includes/`
3. Copy `templates/core/sendgrid-mailer.php` to `includes/`
4. Copy security rules from `templates/.htaccess.security`
5. Update project-specific values in .env:
   - Replace PROJECT_NAME with actual project name
   - Update CONTACT_TO_EMAIL with client's email
   - Update APP_URL with production domain
   - Admin keys are auto-generated per project
6. SendGrid API key is pre-configured and shared across all projects

**Contact Form Implementation (if requested):**

**⚠️ CRITICAL: Session Management Requirements**
- **MUST use `session_status() === PHP_SESSION_NONE` checks**
- **NEVER use `!isset($_SESSION)` for session checking**
- **See `templates/core/SESSION_CSRF_GUIDE.md` for complete guide**

1. Copy `templates/core/contact-handler.php` to project root
2. Copy `templates/core/email-service.php` to `includes/` **[CRITICAL - Not sendgrid-mailer.php]**
3. Copy `templates/core/env-loader.php` to `includes/` **[CRITICAL - Missing causes 500 errors]**
4. Copy `templates/core/config.php` to `includes/` **[Has session/CSRF setup]**
5. Update `.env` with user's email configuration
6. Add form HTML with:
   - AJAX submission (no page refresh)
   - Honeypot field (hidden spam trap)
   - CSRF token protection
   - Privacy policy checkbox
   - Success/error messages inline
7. Add JavaScript to footer.php for AJAX handling
8. Test with rate limiting (20/hour default)
9. Localhost development has CSRF bypass for testing

**📚 CRITICAL REFERENCE DOCUMENTATION:**
- `templates/core/SESSION_CSRF_GUIDE.md` - **Session/CSRF implementation guide**
- `templates/SETUP_NEW_PROJECT.md` - **Complete project setup checklist**
- `SECURITY_PROCESS.md` - Security best practices
- `CONTACT_FORM_PROCESS.md` - Contact form implementation
- `EDITABLE_PHP_GUIDE.md` - Admin mode setup

**1. Enhanced Component Architecture for Editable Sites:**
```
php-website/
├── content/              # JSON content files (one per page)
│   ├── index.json
│   ├── about.json
│   └── [page-name].json
├── includes/
│   ├── header.php       # Contains ALL <head> tags, navigation, Schema.org
│   ├── footer.php       # Contains footer HTML AND all JavaScript
│   ├── admin-config.php # Admin mode configuration and functions
│   └── config.php       # Site-wide configuration
├── assets/
│   ├── css/
│   │   └── styles.css   # Single CSS file for entire site
│   └── images/
│       └── uploads/     # User uploaded images (protected)
├── admin-save.php       # Handles content saves (protected)
├── admin-upload.php     # Handles image uploads (protected)
└── [page-name].php      # Pages that read from JSON
```

**2. JavaScript Management:**
- ❌ NEVER put JavaScript in individual PHP pages
- ✅ ALL JavaScript goes in footer.php
- ✅ Use DOMContentLoaded wrapper for all scripts
- ✅ Check for element existence before attaching listeners
- ❌ NO duplicate scripts across pages

**3. Variable Naming Convention:**
```php
// Correct - use these in every PHP page:
$page_title = "Page Title | Business Name";
$page_description = "Meta description for SEO";
$page_keywords = "keyword1, keyword2, keyword3";

// Wrong - never use these:
$meta_title = "...";          // ❌
$meta_description = "...";    // ❌
$meta_keywords = "...";       // ❌
```

**4. EDITABLE Page Structure Template:**
```php
<?php
// Include admin configuration
require_once 'includes/admin-config.php';

// Load content from JSON
$content = loadContent('page-name');

// Set page meta from JSON
$page_title = $content['meta']['title'];
$page_description = $content['meta']['description'];
$page_keywords = $content['meta']['keywords'];

require_once 'includes/header.php';
?>

    <!-- Hero Section with Editable Background Image -->
    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/default-hero.jpg'; ?>');">
            <?php if (IS_ADMIN): ?>
                <div class="hero-edit-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                    📷 Click to Change Hero Image
                </div>
            <?php endif; ?>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'], 'hero.title'); ?></h1>
            <p><?php echo editable($content['hero']['subtitle'], 'hero.subtitle'); ?></p>
        </div>
    </section>

    <!-- Page Content Reading from JSON -->
    <section class="content-section">
        <h2><?php echo editable($content['section']['title'], 'section.title'); ?></h2>
        <p><?php echo editable($content['section']['text'], 'section.text'); ?></p>
    </section>

<?php require_once 'includes/footer.php'; ?>
```

**5. JSON Content Structure:**
```json
{
  "meta": {
    "title": "Page Title | Business Name",
    "description": "SEO meta description",
    "keywords": "keyword1, keyword2, keyword3"
  },
  "hero": {
    "title": "Main Heading",
    "subtitle": "Subheading text",
    "image": "assets/images/hero-background.jpg"
  },
  "sections": {
    "overview": {
      "title": "Section Title",
      "content": "Section content..."
    }
  },
  "faqs": {
    "items": [
      {
        "question": "FAQ Question?",
        "answer": "FAQ Answer."
      }
    ]
  }
}
```

**5. FAQ Implementation:**
- HTML structure in page content
- JavaScript functionality ONLY in footer.php
- Use consistent class names: .faq-item, .faq-question, .faq-answer
- Single accordion script handles all FAQ pages

**6. Schema.org Implementation:**
- LocalBusiness schema in header.php (site-wide)
- Page-specific schema can be added via $page_schema variable
- Never duplicate organization schema

**7. Admin Mode Implementation with Environment Variables:**
```php
// admin-config.php with secure environment variables
require_once __DIR__ . '/env-loader.php';

// Load secrets from .env file
define('ADMIN_SECRET_KEY', EnvLoader::get('ADMIN_SECRET_KEY'));
define('CACHE_CLEAR_KEY', EnvLoader::get('CACHE_CLEAR_KEY'));

// Activation via URL: site.com?admin={key-from-env}
// Cache clear: site.com?clearcache={key-from-env}
// Deactivation via: site.com?logout=true

// Helper functions:
loadContent($page)     // Load JSON content
saveContent($page, $content)  // Save JSON content
editable($value, $field_path) // Make field editable
editableImage($src, $field, $placeholder, $alt) // Make image editable
```

**CRITICAL Admin Mode Features Required:**
*See `EDITABLE_PHP_GUIDE.md` for complete implementation details*

- Text editing: Click to edit any text content
- **SEO Meta Editing:**
  - "Edit SEO" button in admin bar opens modal
  - Edit page title, meta description, keywords
  - Live preview updates browser immediately
  - See `templates/core/SEO_EDITING_SETUP.md` for implementation
- **Image editing: Production-ready upload (NOT base64)**
  - MUST create `admin-upload.php` file
  - Uploads to `/assets/images/uploads/` folder
  - Returns JSON with file URL
  - JavaScript uses FormData, not FileReader
- **Hero Background Image Editing:**
  - Editable CSS background-image in hero sections
  - Click overlay button to change hero images
  - Uses `editable-hero-bg` class with data attributes
  - Requires special handling in admin-functions.js
  - See `templates/core/HERO_IMAGE_SETUP.md` for setup guide
- **FAQ Management:**
  - Add FAQ button in admin bar
  - Remove button on each FAQ item
  - Dynamic add/remove without page refresh
- Save Changes button to persist all edits

**MANDATORY: Image Upload Implementation**
The `admin-upload.php` file MUST:
1. Validate admin authentication
2. Check file types (jpg, png, gif, webp only)
3. Limit file size (5MB max)
4. Generate unique filenames
5. Save to `/assets/images/uploads/`
6. Return JSON with file URL (NOT base64)

**8. Security Requirements (.htaccess):**
```apache
# CRITICAL: Protect .env files FIRST
<Files .env>
    Order deny,allow
    Deny from all
</Files>

# Block all dot files
<FilesMatch "^\.">
    Order deny,allow
    Deny from all
</FilesMatch>

# Protect sensitive file extensions
<FilesMatch "\.(env|json|log|md|gitignore|lock)$">
    Order deny,allow
    Deny from all
</FilesMatch>

# Disable directory browsing
Options -Indexes

# Protect includes directory
RewriteRule ^(includes|content|cache)/ - [F,L]

# Protect uploads directory (allow images only)
<Directory "/assets/images/uploads">
    # Disable PHP execution
    php_flag engine off

    # Only allow specific file types
    <FilesMatch "\.(gif|jpe?g|png|webp)$">
        Order Allow,Deny
        Allow from all
    </FilesMatch>
</Directory>
```

**9. Testing Checklist:**

**Session & Email Testing (CRITICAL):**
- [ ] **Session starts without warnings (check PHP error log)**
- [ ] **CSRF token generated on page load (view source)**
- [ ] **Contact form submits without 500 error**
- [ ] **Contact form doesn't show "Security validation failed"**
- [ ] **Email sends successfully (check spam folder)**
- [ ] **env-loader.php exists in includes/**
- [ ] **config.php uses session_status() checks**

**General Testing:**
- [ ] No JavaScript errors in console
- [ ] FAQ dropdowns work on all pages
- [ ] Mobile menu functions correctly
- [ ] All meta tags populated correctly from JSON
- [ ] Schema.org validates at schema.org/validator
- [ ] No duplicate scripts or styles
- [ ] Admin mode activates with secret key
- [ ] Content saves correctly to JSON
- [ ] Image uploads work and are secured
- [ ] JSON files are not directly accessible
- [ ] Admin files are protected

### Phase 8: Delivery & Deployment

#### Local Testing
```
✅ Website Generated Successfully!

📁 Location: project-[business_name]/php-website/
📊 Pages Created: 24
🎯 Keywords Targeted: 72
📝 Total Content: 36,000+ words

To preview locally:
1. cd project-[business_name]/php-website/
2. php -S localhost:8000
3. Open http://localhost:8000
```

#### Production Deployment

## 🚨 CRITICAL DEPLOYMENT REQUIREMENTS

**⚠️ STOP: MANDATORY READING BEFORE ANY DEPLOYMENT**

1. **📖 READ DEPLOYMENT_STRATEGY.md FIRST**
   - Location: `website-rebuilder/DEPLOYMENT_STRATEGY.md`
   - Contains ALL deployment procedures, templates, and lessons learned
   - **DO NOT** create custom deployment workflows
   - **ALWAYS** use the standardized templates

2. **🔄 FOLLOW EXACT PROCESS FROM DEPLOYMENT_STRATEGY.md**
   - Use the standardized `.github/workflows/deploy.yml` template
   - Use organization secrets: `WHM_HOST`, `WHM_USERNAME`, `WHM_SSH_KEY`
   - Deploy to standard path structure
   - **NEVER** hardcode server details or create custom workflows

## **DEPLOYMENT VALIDATION CHECKLIST**

**✅ Before Starting Deployment - Verify:**
- [ ] I have read DEPLOYMENT_STRATEGY.md completely
- [ ] I understand the organization's standard deployment process
- [ ] I am NOT creating a custom workflow
- [ ] I am using the exact template from DEPLOYMENT_STRATEGY.md

**✅ Repository Setup - Must Have:**
- [ ] Repository is PUBLIC (organization secrets require public repos)
- [ ] `.gitignore` created BEFORE `git init` (excludes .env)
- [ ] Using exact workflow template from DEPLOYMENT_STRATEGY.md
- [ ] Repository created in `2mags-sites` organization
- [ ] Following naming convention: `2mags-sites/client-name`

**✅ Workflow Requirements - Must Use:**
- [ ] `appleboy/scp-action@v0.1.4` for file deployment
- [ ] `appleboy/ssh-action@v0.1.5` for permissions
- [ ] Organization secrets: `WHM_HOST`, `WHM_USERNAME`, `WHM_SSH_KEY`
- [ ] Standard target path: `/home/CPANEL_USER/public_html`
- [ ] Proper permissions script from template

**✅ Post-Deployment - Must Complete:**
- [ ] Upload .env file manually (not in git)
- [ ] Verify file permissions are correct
- [ ] Test website functionality
- [ ] Test admin mode access

## **STANDARD DEPLOYMENT PROCESS**

**Following DEPLOYMENT_STRATEGY.md exactly:**

```bash
# 1. PREPARE (from php-website folder)
cd project-clientname/php-website

# 2. CREATE .gitignore FIRST (prevents secrets in git)
cat > .gitignore << 'EOF'
# Environment variables (CRITICAL - must exclude)
.env
.env.local
.env.*.local

# Backups
*.backup
*.bak

# System files
.DS_Store
Thumbs.db

# IDE files
.vscode/
.idea/

# Logs
*.log
error_log
EOF

# 3. INITIALIZE GIT
git init

# 4. CREATE REPOSITORY
gh repo create 2mags-sites/CLIENT-NAME --public --description "CLIENT business description"

# 5. ADD STANDARD WORKFLOW (copy exactly from DEPLOYMENT_STRATEGY.md)
mkdir -p .github/workflows
# COPY the exact deploy.yml template from DEPLOYMENT_STRATEGY.md
# UPDATE only the CPANEL_USER and target paths for the specific client

# 6. COMMIT AND DEPLOY
git add -A
git commit -m "Initial commit - CLIENT NAME website"
git branch -M main
git remote add origin https://github.com/2mags-sites/CLIENT-NAME.git
git push -u origin main
```

## **CRITICAL WARNINGS**

**❌ NEVER DO THESE:**
- Create custom FTP-based deployment workflows
- Hardcode server credentials in workflows
- Use private repositories (organization secrets won't work)
- Include .env in git commits
- Create client-specific deployment secrets

**✅ ALWAYS DO THESE:**
- Read DEPLOYMENT_STRATEGY.md before any deployment
- Use the exact standardized workflow template
- Use organization secrets (WHM_HOST, WHM_USERNAME, WHM_SSH_KEY)
- Create PUBLIC repositories in 2mags-sites organization
- Upload .env manually after first deployment

## **IF YOU DEVIATE FROM DEPLOYMENT_STRATEGY.md**

**🛑 STOP IMMEDIATELY**
1. Review why you're deviating
2. Check if DEPLOYMENT_STRATEGY.md needs updating
3. Follow the established process
4. **DO NOT** improvise or create custom solutions

**The deployment strategy exists because of hard-learned lessons from previous deployments. Follow it exactly.**

## User Checkpoints Summary

The `/phpsite` command includes 7 validation checkpoints where users can review and modify:

1. **After Discovery** - Confirm business info, add missing services
2. **Architecture Style** - Choose single-page, traditional, or hybrid
3. **SEO Strategy** - Choose between full/hybrid/minimal approach
4. **After IA Planning** - Approve site structure or modify
5. **After Keyword Mapping** - Review/edit keywords for each page
6. **After Content Generation** - Preview and approve content
7. **Before Final Build** - Confirm all settings before PHP generation

Each checkpoint allows users to:
- Approve and continue
- Make modifications
- Go back to previous step
- Exit and save progress (future enhancement)

## Implementation Requirements

### Claude Components Needed:
1. **Tools Access**:
   - WebFetch - for analyzing websites
   - Bash - for running Python scripts
   - Read/Write - for managing generated files

2. **Custom Command Handler**:
   ```python
   # Pseudo-code for Claude command handler
   @command("phpsite")
   def phpsite_command():
       # Interactive workflow orchestration
       url = get_user_input("Website URL:")

       # Discovery
       website_data = analyze_website(url)

       # IA Planning
       site_structure = plan_information_architecture(website_data)

       # Content Generation
       content = generate_all_content(site_structure)

       # Build Website
       build_php_website(content)
   ```

3. **Python Integration Script** (`claude_phpsite.py`):
   - Already created
   - Receives JSON from Claude
   - Generates PHP files
   - Creates router and assets

### Directory Structure:
```
website-rebuilder/
├── claude_phpsite.py          # Python script Claude calls
├── PHPSITE_COMMAND.md          # This documentation
├── generator/
│   └── php_generator.py        # PHP generation logic
└── output/                     # Generated websites
```

## Usage Examples

### Example 1: Law Firm
```
User: /phpsite
Claude: URL to rebuild?
User: https://lawfirm.com
Claude: Found 8 practice areas with 24 sub-services...
[Continues through workflow]
Result: 30-page website with legal content
```

### Example 2: Medical Practice
```
User: /phpsite
Claude: URL to rebuild?
User: https://medicalclinic.com
Claude: Found 5 specialties with 15 treatments...
[Continues through workflow]
Result: 20-page website with medical content
```

## Benefits Over Traditional Approach

1. **Intelligent Extraction**:
   - Traditional: Regex patterns miss context
   - /phpsite: AI understands "Corporate Law" is a service category

2. **Content Quality**:
   - Traditional: Copies existing sparse content
   - /phpsite: Generates rich, unique content for each service

3. **SEO Strategy**:
   - Traditional: Generic keywords
   - /phpsite: Specific keywords for each service with search intent

4. **Information Architecture**:
   - Traditional: Flat structure
   - /phpsite: Hierarchical with proper categorization

5. **Customization**:
   - Traditional: One-size-fits-all
   - /phpsite: Industry-specific content and structure

## Error Handling

The command handles:
- Invalid URLs
- Websites that block scraping
- Missing business information
- Incomplete service data

Each issue prompts user for correction or manual input.

## Future Enhancements

Potential additions:
1. Competitor analysis integration
2. Multi-language support
3. E-commerce functionality
4. Blog/news section generation
5. Custom design themes
6. Analytics integration
7. Contact form handling
8. Map integration for locations

## Command Registration

To register this command in Claude:

```javascript
// Command definition for Claude
{
  "command": "phpsite",
  "description": "Build SEO-optimized PHP website from any URL",
  "handler": "phpsite_workflow",
  "interactive": true,
  "requires_tools": ["WebFetch", "Bash", "Read", "Write"],
  "timeout": 600000, // 10 minutes
  "help_text": "Analyzes a website and rebuilds it as an SEO-optimized PHP site with AI-generated content"
}
```

## Testing

Test with various business types:
- Law firms
- Medical practices
- Accounting firms
- Real estate agencies
- Restaurants
- E-commerce sites
- Service businesses

Each should receive appropriate:
- Industry-specific categorization
- Relevant service structures
- Appropriate content tone
- Industry-specific keywords

## Current Implementation Status

### Completed:
1. ✅ Discovery & Analysis phase - extracts services from websites
2. ✅ Architecture Style Decision - single-page vs traditional
3. ✅ SEO Strategy - full/hybrid/minimal approaches
4. ✅ IA Planning - creates site structure
5. ✅ Keyword Mapping - assigns keywords to pages
6. ✅ Live Preview System - generates viewable HTML
7. ✅ Documentation - comprehensive command documentation

### In Progress:
- Footer implementation for preview
- Contact section for homepage
- Testimonials section

### To Be Implemented:
- Service page preview generation
- Final PHP build process
- Theme selection
- Multi-language support

## Example Usage Session

For Robert Lee Law Offices:
1. Discovered 6 practice areas with 18+ sub-services
2. User chose Modern Single-Page + SEO approach
3. User selected Full SEO (24+ pages)
4. Generated keyword mapping for all pages
5. Created live preview at localhost:8002
6. User feedback: Add footer section
7. Ready for final PHP generation

## Critical Production Learnings

### 1. .htaccess Configuration
**CRITICAL:** POST requests must be excluded from PHP redirect rules:
```apache
# WRONG - breaks POST requests
RewriteCond %{THE_REQUEST} /([^.]+)\.php [NC]
RewriteRule ^ /%1 [R=301,L]

# CORRECT - preserves POST data
RewriteCond %{REQUEST_METHOD} !POST
RewriteCond %{THE_REQUEST} /([^.]+)\.php [NC]
RewriteRule ^ /%1 [R=301,L]
```

### 2. GitHub Deployment Strategy
**Use separate PUBLIC repository per client:**
- Organization secrets only work with PUBLIC repos
- One repo = one site = one deployment pipeline
- Keep php-builder private, client sites public

### 3. Environment Variables
**NEVER hardcode secrets:**
```php
// WRONG
define('ADMIN_SECRET_KEY', 'hardcoded_key');

// CORRECT
require_once __DIR__ . '/env-loader.php';
define('ADMIN_SECRET_KEY', EnvLoader::get('ADMIN_SECRET_KEY', 'default'));
```

### 4. Error Logging
**Always include error logging for production debugging:**
- Add error-logger.php to includes/
- Add view-logs.php for admin viewing
- Log all admin save attempts
- Include request details and context

### 5. CSRF Token Implementation
**Ensure CSRF tokens are accessible:**
```html
<!-- In header.php -->
<meta name="csrf-token" content="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
```

### 6. JavaScript Fetch URLs
**Use relative paths with .php extension for POST:**
```javascript
// Use .php extension for POST (avoids redirect)
fetch('admin-save.php', { method: 'POST' ... })

// Clean URLs work for GET
fetch('about', { method: 'GET' ... })
```

### 7. Session Management
**Always check session status before starting:**
```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```

### 8. File Permissions
**Critical files after deployment:**
- `.env` - 600 (owner read/write only)
- `uploads/` - 777 (full write access)
- `.htaccess` - 644 (readable)
- PHP files - 644 (readable)

### 9. Testing Checklist
After deployment, always test:
- [ ] Admin mode login
- [ ] Content editing and saving
- [ ] Image uploads
- [ ] Contact form submission
- [ ] Blog display (if applicable)
- [ ] Check /view-logs.php for errors

### 10. Debugging Tools
Include in all projects:
- `/test-admin.php` - Test admin save functionality
- `/view-logs.php` - View error logs (admin only)
- Browser console logging in admin-functions.js

## Conclusion

The `/phpsite` command transforms website rebuilding from a mechanical copying process to an intelligent reconstruction that understands business context, creates proper information architecture, and generates rich, SEO-optimized content. It leverages Claude's AI capabilities to do what regex patterns cannot: understand meaning, context, and relationships in web content.

Combined with proper deployment practices and error handling, it creates robust, maintainable PHP websites that can be easily managed by clients through the admin interface.