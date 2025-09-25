# Service Page Template for Barringtons Funerals

## CRITICAL: This template MUST be used for ALL service pages to ensure consistency

### CSS Variables (MUST match exactly):
```css
:root {
    --primary-color: #2c3e50;
    --secondary-color: #34495e;
    --accent-color: #27ae60;
    --text-dark: #2c3e50;
    --text-light: #7f8c8d;
    --bg-light: #f8f9fa;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    color: var(--text-dark);
    line-height: 1.7;
    padding-top: 80px;
}
```

### Required Structure:

1. **Service Hero Section**
   - Background gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%)
   - Include breadcrumb navigation
   - Title and lead text

2. **Main Content Sections**
   - Use `.content-section` class
   - Alternate background colors using `:nth-child(even)`
   - Include side-by-side image layouts:
     ```css
     .content-with-image {
         display: flex;
         align-items: center;
         gap: 40px;
     }
     .content-text { flex: 1.2; }
     .content-image { flex: 0.8; }
     ```

3. **Service Details/Process Boxes**
   - Use `.service-box` or `.process-step` classes
   - Include icons from Font Awesome
   - Process steps MUST use green circular numbers:
     ```css
     .process-step {
         display: flex;
         margin-bottom: 40px;
     }
     .step-number {
         width: 50px;
         height: 50px;
         background: var(--accent-color); /* #27ae60 green */
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         color: white;
         font-weight: bold;
         font-size: 1.5rem;
         margin-right: 25px;
         flex-shrink: 0;
     }
     ```
   - HTML structure:
     ```html
     <div class="process-step">
         <div class="step-number">1</div>
         <div class="step-content">
             <h4>Step Title</h4>
             <p>Step description</p>
         </div>
     </div>
     ```

4. **FAQ Section (REQUIRED)**
   - Must include 5-6 questions minimum
   - Interactive accordion with JavaScript
   - Structure:
     ```html
     <div class="faq-item">
         <div class="faq-question">
             Question text
             <i class="fas fa-chevron-down"></i>
         </div>
         <div class="faq-answer">
             <p>Answer text</p>
         </div>
     </div>
     ```

5. **CTA Box (REQUIRED)**
   - Must be final content section
   - Include all office phone numbers
   - Structure:
     ```html
     <div class="cta-box">
         <h2>We're Here to Help</h2>
         <p class="lead mb-4">Our compassionate team is available 24/7</p>
         <p class="mb-4">
             <strong>Waterloo:</strong> 0151 928 1625 |
             <strong>Formby:</strong> 01704 461511 |
             <strong>Netherton:</strong> 0151 329 3525
         </p>
         <a href="/contact" class="btn-white">Contact Us</a>
     </div>
     ```

### Mobile Responsive Requirements:
```css
@media (max-width: 768px) {
    .content-with-image {
        flex-direction: column !important;
    }
    .content-image {
        min-height: 200px !important;
        height: 200px !important;
    }
}
```

### JavaScript (REQUIRED for FAQ):
```javascript
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', () => {
        const item = question.parentElement;
        item.classList.toggle('active');
        const icon = question.querySelector('i');
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    });
});
```

### Content Guidelines:
- 1500+ words of unique content
- Use only known facts about the business
- Include process explanations where relevant
- Add image placeholders with appropriate icons
- Maintain professional, compassionate tone
- Include internal links to related services