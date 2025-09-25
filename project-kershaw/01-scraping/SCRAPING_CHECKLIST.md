# Website Scraping Checklist

## Phase 1: Initial Website Scrape

### Must Capture:

#### 1. Content Verbatim
- [ ] Homepage - FULL TEXT
- [ ] About page - FULL TEXT
- [ ] Each service page - FULL TEXT
- [ ] Contact page - FULL TEXT
- [ ] Any FAQ sections - EXACT Q&As
- [ ] Testimonials - EXACT QUOTES
- [ ] Footer text - EXACT WORDING

#### 2. Tone Analysis
- [ ] Professional vs Casual
- [ ] Formal vs Friendly
- [ ] Technical vs Simple
- [ ] Key phrases they repeat
- [ ] Words they avoid
- [ ] Emotional tone

#### 3. Services
- [ ] Service names (EXACT)
- [ ] Service descriptions (FULL)
- [ ] Prices mentioned
- [ ] What's included
- [ ] What's not included
- [ ] Service process/steps

#### 4. Location Information
- [ ] Areas served (SPECIFIC)
- [ ] Local landmarks mentioned
- [ ] Travel times/distances
- [ ] Local facilities used
- [ ] Parking information

#### 5. Contact Information
- [ ] Phone numbers (all)
- [ ] Email addresses
- [ ] Physical address
- [ ] Opening hours
- [ ] Emergency contact
- [ ] Social media links

#### 6. Company Information  
- [ ] Established date
- [ ] Company history
- [ ] Ownership structure
- [ ] Team/staff mentioned
- [ ] Qualifications/accreditations
- [ ] Awards/recognition

#### 7. Unique Selling Points
- [ ] What makes them different
- [ ] Key benefits they emphasize
- [ ] Guarantees/promises
- [ ] Values stated

#### 8. Visual/Design Elements
- [ ] Color scheme used
- [ ] Logo description
- [ ] Image types used
- [ ] Layout style

## Phase 2: Competitive Research

### Should Also Check:
- [ ] 2-3 competitor websites
- [ ] Industry standard language
- [ ] Common services offered
- [ ] Typical price ranges
- [ ] Standard FAQs

## Storage Format

### 1. Raw Scraped Content
```
scraped-pages/
├── homepage.txt (full HTML/text)
├── about.txt
├── service-1.txt
├── service-2.txt
└── contact.txt
```

### 2. Extracted Information
```
extracted-data/
├── business-info.json
├── services.json
├── tone-analysis.md
├── key-phrases.txt
└── testimonials.json
```

### 3. Complete Documentation
```
BUSINESS_COMPLETE.md (comprehensive narrative)
```

## Red Flags - When to Stop and Ask

1. **Missing Critical Info:**
   - No phone number
   - No address
   - No prices
   - No service details

2. **Conflicting Information:**
   - Different phone numbers
   - Inconsistent service names
   - Varying prices

3. **Tone Uncertainty:**
   - Mixed formal/casual
   - Unclear brand voice
   - No consistent style

## Example: What We're Looking For

### Good Capture:
```markdown
Original text from About page:
"Established in 1892, Arthur Kershaw Funeral Services has been 
a cornerstone of the Sale community for over 130 years. As an 
independent, family-owned funeral directors, we pride ourselves 
on providing personal, compassionate service."

Tone notes:
- Formal but warm
- Emphasizes heritage
- Uses "we" not "I"
- Community-focused language
```

### Bad Capture:
```markdown
"They're a funeral company in Sale that's been around a long time
and offers various funeral services."
[This loses all the specific language and tone]
```

## Critical for Funeral Services

### MUST Capture Exactly:
1. How they refer to death/deceased
2. Comfort language used
3. Religious/secular balance
4. Cultural sensitivity shown
5. Practical vs emotional content ratio

### NEVER Generate:
1. Testimonials (legal/ethical issues)
2. Specific staff names
3. Medical/legal advice
4. Promises about timelines
5. Guarantees about third-party services

## Validation Before Generation

Before generating ANY content, verify:
1. ✓ Have original text to reference
2. ✓ Understand their tone
3. ✓ Know their exact services
4. ✓ Have accurate contact info
5. ✓ Understand their USPs

If ANY of these are missing - STOP and gather more information.