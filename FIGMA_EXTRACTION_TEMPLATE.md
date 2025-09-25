# Figma Design Extraction Template

## Instructions for Claude Desktop with Figma MCP

Use this template when extracting design data from Figma using the MCP server in Claude Desktop. Copy the extracted data into the specified files to bring back to this project.

---

## 1. Design Tokens → Save to: `design-tokens.json`

```json
{
  "colors": {
    "primary": "#______",
    "primary-dark": "#______",
    "primary-light": "#______",
    "secondary": "#______",
    "accent": "#______",
    "text-dark": "#______",
    "text-light": "#______",
    "text-muted": "#______",
    "background": "#______",
    "background-alt": "#______",
    "border": "#______",
    "error": "#______",
    "success": "#______",
    "warning": "#______"
  },
  "typography": {
    "font-families": {
      "heading": "'____', serif/sans-serif",
      "body": "'____', serif/sans-serif",
      "mono": "'____', monospace"
    },
    "font-sizes": {
      "xs": "____rem",
      "sm": "____rem",
      "base": "____rem",
      "lg": "____rem",
      "xl": "____rem",
      "2xl": "____rem",
      "3xl": "____rem",
      "4xl": "____rem",
      "5xl": "____rem"
    },
    "font-weights": {
      "light": "300",
      "normal": "400",
      "medium": "500",
      "semibold": "600",
      "bold": "700"
    },
    "line-heights": {
      "tight": "____",
      "normal": "____",
      "relaxed": "____"
    }
  },
  "spacing": {
    "unit": "____px",
    "xs": "____px",
    "sm": "____px",
    "md": "____px",
    "lg": "____px",
    "xl": "____px",
    "2xl": "____px",
    "3xl": "____px",
    "4xl": "____px",
    "5xl": "____px"
  },
  "borders": {
    "radius": {
      "none": "0",
      "sm": "____px",
      "md": "____px",
      "lg": "____px",
      "xl": "____px",
      "full": "9999px"
    },
    "width": {
      "thin": "1px",
      "medium": "2px",
      "thick": "4px"
    }
  },
  "shadows": {
    "sm": "____",
    "md": "____",
    "lg": "____",
    "xl": "____"
  },
  "breakpoints": {
    "mobile": "____px",
    "tablet": "____px",
    "desktop": "____px",
    "wide": "____px"
  },
  "container": {
    "max-width": "____px",
    "padding": "____px"
  }
}
```

---

## 2. Component Mapping → Save to: `component-mapping.md`

### Header Component
```markdown
## Header/Navigation
- Layout: [fixed/static/sticky]
- Height: [__px]
- Background: [color/transparent/gradient]
- Logo position: [left/center]
- Menu style: [horizontal/dropdown/mega-menu]
- Mobile menu: [hamburger/slide-out/accordion]
- Breakpoint: [__px]

### Figma Component Name: _______
Maps to our: header.php
```

### Hero Section
```markdown
## Hero Section (Homepage)
- Layout: [full-width/contained]
- Height: [viewport/fixed/__px]
- Content alignment: [left/center/right]
- Background: [image/video/gradient/solid]
- Overlay: [yes/no - opacity: __]
- CTA buttons: [count: __, style: ______]

### Figma Component Name: _______
Maps to our: Homepage hero section
```

### Service Card
```markdown
## Service Card Component
- Layout: [vertical/horizontal]
- Image position: [top/left/right/background]
- Image aspect ratio: [__:__]
- Content padding: [__px]
- Shadow: [yes/no]
- Hover effect: [scale/shadow/color/none]
- Border radius: [__px]

### Figma Component Name: _______
Maps to our: service-*.php pages
Used ____ times
```

### Location Card
```markdown
## Location Card Component
- Layout: [vertical/horizontal]
- Required fields: [title, address, phone, link]
- Optional fields: [image, hours, description]
- Map integration: [yes/no]
- Style variation from service card: _______

### Figma Component Name: _______
Maps to our: funeral-directors-*.php pages
Used ____ times
```

### Blog Post Card
```markdown
## Blog/News Card Component
- Layout: [vertical/horizontal/grid]
- Image: [required/optional]
- Image aspect ratio: [__:__]
- Meta shown: [date/author/category/read-time]
- Excerpt length: [__ characters]
- Read more style: [link/button/arrow]

### Figma Component Name: _______
Maps to our: Blog section on homepage, blog.php
```

### Content Sections
```markdown
## Text + Image Section
- Layouts available: [image-left/image-right/alternating]
- Image size: [50-50/60-40/40-60]
- Spacing between: [__px]
- Mobile stack order: [image-first/text-first]

### Figma Component Name: _______
Maps to our: Various content sections
```

### CTA Section
```markdown
## Call-to-Action Section
- Background: [color/gradient/image]
- Padding: [__px top/bottom]
- Text alignment: [left/center/right]
- Button count: [1/2]
- Button style: [solid/outline/ghost]

### Figma Component Name: _______
Maps to our: CTA sections on multiple pages
```

### Footer
```markdown
## Footer Component
- Layout: [columns: __]
- Background: [color: ____]
- Link columns: [count: __]
- Social icons: [yes/no - position: ____]
- Copyright position: [left/center/right]
- Newsletter signup: [yes/no]

### Figma Component Name: _______
Maps to our: footer.php
```

---

## 3. CSS Generation → Save to: `design-system.css`

```css
/* Auto-generated from Figma - [DATE] */

:root {
  /* Colors from Figma */
  --color-primary: ____;
  --color-secondary: ____;
  /* ... copy all color tokens ... */

  /* Typography from Figma */
  --font-heading: ____;
  --font-body: ____;
  /* ... copy all typography tokens ... */

  /* Spacing from Figma */
  --spacing-xs: ____;
  --spacing-sm: ____;
  /* ... copy all spacing tokens ... */

  /* Borders & Shadows from Figma */
  --radius-sm: ____;
  --shadow-sm: ____;
  /* ... copy all border/shadow tokens ... */
}

/* Component-specific styles extracted from Figma */

.hero {
  /* Figma: [Component Name] */
  /* Paste extracted styles */
}

.service-card {
  /* Figma: [Component Name] */
  /* Paste extracted styles */
}

/* ... continue for each component ... */
```

---

## 4. Special Considerations → Save to: `design-notes.md`

```markdown
## Design Adaptations Needed

### Must Preserve from Current Site:
- [ ] All SEO meta tags and Schema.org data
- [ ] WordPress blog integration points
- [ ] Admin mode functionality
- [ ] Current URL structure
- [ ] PHP includes structure

### Design Elements to Adapt:
- [ ] Animations (check appropriateness for funeral home)
- [ ] Color overlays on images (ensure text readability)
- [ ] Font sizes (check accessibility)
- [ ] Interactive elements (ensure mobile compatibility)

### Missing Components in Figma:
- [ ] _______
- [ ] _______
- [ ] _______

### Components in Figma We Won't Use:
- [ ] _______ (Reason: _____)
- [ ] _______ (Reason: _____)

### Responsive Breakpoint Changes:
- Mobile: ___ → ___px
- Tablet: ___ → ___px
- Desktop: ___ → ___px

### Accessibility Concerns:
- [ ] Color contrast issues: _______
- [ ] Font size minimums: _______
- [ ] Touch target sizes: _______

### Performance Considerations:
- [ ] Large images that need optimization
- [ ] Web fonts to load
- [ ] Heavy animations to simplify
```

---

## 5. Quick Extraction Script for Claude Desktop

When in Claude Desktop with Figma MCP, use this prompt:

```
Using the Figma MCP server, please extract:

1. All design tokens (colors, typography, spacing, shadows, borders)
2. Component specifications for:
   - Header/Navigation
   - Hero section
   - Service card
   - Location card
   - Blog post card
   - Content sections
   - CTA section
   - Footer

3. For each component, note:
   - Figma component name
   - Layout structure
   - Spacing/padding
   - Responsive behavior
   - Hover states
   - Any animations

4. Generate CSS variables for all design tokens

5. Note any components that exist in Figma but aren't needed for a funeral home website

Format the output according to the templates in FIGMA_EXTRACTION_TEMPLATE.md
```

---

## 6. File Structure to Create

After extraction, organize files as:

```
project-kershaw/
├── design-extraction/
│   ├── design-tokens.json
│   ├── component-mapping.md
│   ├── design-system.css
│   ├── design-notes.md
│   └── figma-link.txt (store Figma URL for reference)
```

---

## Checklist Before Returning to Code Environment

- [ ] Design tokens extracted and saved
- [ ] All component mappings documented
- [ ] CSS variables generated
- [ ] Responsive breakpoints noted
- [ ] Accessibility issues identified
- [ ] Performance concerns noted
- [ ] Figma URL saved for reference
- [ ] Any custom fonts identified
- [ ] Image assets listed (if needed)

This extracted data can then be applied systematically to all PHP pages using the process in DESIGN_INTEGRATION_PROCESS.md