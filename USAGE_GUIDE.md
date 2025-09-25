# Website Rebuilder - Complete Usage Guide

## Quick Start

The Website Rebuilder system is now fully operational and ready to convert ANY website into a database-free PHP website with SEO optimization.

### Basic Commands

1. **Run the Demo (Test the System)**
   ```
   python main.py --demo
   ```
   Or use the batch file:
   ```
   run_demo.bat
   ```

2. **Rebuild Any Website**
   ```
   python main.py --url "https://example.com"
   ```

3. **Rebuild with Custom Keywords**
   ```
   python main.py --url "https://example.com" --keywords "service1,service2,service3"
   ```

4. **Interactive Mode**
   ```
   run_website_rebuild.bat
   ```
   This will prompt you for URL and keywords interactively.

## What the System Does

### Input
- Any business website URL (law firm, restaurant, medical practice, etc.)
- Optional: Custom keywords for SEO service pages

### Output
A complete PHP website with:
- **27+ PHP files** including homepage, about, contact, and multiple service pages
- **JSON data files** for content storage (no database needed)
- **HTML5 Boilerplate** templates with semantic markup
- **SEO optimization** including meta tags, schema markup, sitemap
- **Responsive design** with mobile-first CSS
- **Clean URLs** via .htaccess configuration

## Supported Business Types

The system automatically detects and optimizes for:
- Law firms
- Medical practices
- Dental practices
- Restaurants
- Consulting firms
- Real estate agencies
- Accounting firms
- Fitness centers
- Salons/Spas
- Auto services
- Construction companies
- Educational institutions
- Technology companies
- General services

## Example Commands for Different Businesses

### Law Firm
```
python main.py --url "https://smithlaw.com" --keywords "divorce lawyer,family law,criminal defense,personal injury,estate planning"
```

### Restaurant
```
python main.py --url "https://joesbistro.com" --keywords "italian food,pizza delivery,catering service,private dining,lunch specials"
```

### Medical Practice
```
python main.py --url "https://cityclinic.com" --keywords "family doctor,pediatrics,urgent care,vaccinations,health checkup"
```

### Real Estate
```
python main.py --url "https://homerealty.com" --keywords "homes for sale,property listings,real estate agent,buying guide,selling tips"
```

## Output Structure

After running, your website will be in: `output/[timestamp]/`

```
output/demo_20250913_104558/
├── index.php                 # Homepage
├── about.php                 # About page
├── contact.php              # Contact page with form
├── services.php             # Services overview
├── team.php                 # Team/staff page
├── [keyword1].php           # SEO service page 1
├── [keyword2].php           # SEO service page 2
├── ...                      # More service pages
├── config.php               # Site configuration
├── functions.php            # Helper functions
├── .htaccess               # URL rewriting rules
├── robots.txt              # SEO directives
├── sitemap.xml             # XML sitemap
├── components/
│   ├── header.php          # Reusable header
│   └── footer.php          # Reusable footer
├── handlers/
│   └── contact.php         # Form handler
├── data/
│   ├── universal/          # Site-wide data
│   │   ├── business.json
│   │   ├── navigation.json
│   │   ├── contact.json
│   │   ├── team.json
│   │   └── social.json
│   └── pages/              # Page-specific data
│       ├── home.json
│       ├── about.json
│       └── [service].json
└── assets/
    ├── css/
    │   ├── main.css        # Main styles
    │   └── normalize.css   # CSS reset
    └── js/
        └── main.js         # JavaScript

```

## Deployment Instructions

### Local Testing
1. Navigate to output folder:
   ```
   cd output/demo_20250913_104558
   ```

2. Start PHP server:
   ```
   php -S localhost:8000
   ```

3. Open browser to:
   ```
   http://localhost:8000
   ```

### Web Hosting Deployment
1. Upload all files from output folder to your web host
2. Ensure PHP 7.4+ is enabled
3. Update email in `config.php` for contact form
4. Test all pages and functionality
5. Submit `sitemap.xml` to Google Search Console

## Advanced Options

### Custom Business Name
```
python main.py --url "https://example.com" --business-name "My Custom Business Name"
```

### Specify Output Directory
```
python main.py --url "https://example.com" --output-dir "my-website"
```

### Verbose Mode (See All Processing)
```
python main.py --url "https://example.com" --verbose
```

## Configuration

Edit `config/settings.json` to customize:
- Maximum pages to crawl
- Minimum word counts
- SEO parameters
- Supported business types
- Performance settings

## Troubleshooting

### "PHP not found" Warning
- This is optional - only needed for local testing
- Install PHP 7.4+ from https://www.php.net/downloads

### Website Analysis Fails
- Check if the website is accessible
- Try with --verify-ssl false for sites with certificate issues
- Increase timeout in config/settings.json

### Not Enough Content Generated
- Add more keywords to generate more service pages
- The system creates one optimized page per keyword

## SEO Features

Every generated website includes:
- **Optimized meta tags** for all pages
- **Schema.org markup** (LocalBusiness, FAQPage)
- **Clean URL structure** (no .php extensions visible)
- **Mobile-responsive design**
- **Fast loading** (no database queries)
- **Semantic HTML5 structure**
- **Comprehensive FAQ sections**
- **XML sitemap** for search engines
- **Robots.txt** with proper directives

## System Requirements

- Python 3.8 or higher
- PHP 7.4+ (optional, for local testing)
- 100MB free disk space
- Internet connection (for analyzing websites)

## Support

For issues or questions:
- Check the log files in `logs/` folder
- Review the demo output for reference
- Run `python setup.py` to verify installation

## Tips for Best Results

1. **Choose Relevant Keywords**: Pick 5-15 keywords that match the business
2. **Include Location**: Add location-based keywords for local businesses
3. **Mix Keyword Types**: Combine service + location + intent keywords
4. **Test Locally First**: Always test the generated site locally before deploying
5. **Review Content**: Check generated content for accuracy and relevance

## Next Steps

1. Run the demo to see how it works
2. Try rebuilding a real website
3. Deploy to your web hosting
4. Monitor SEO performance
5. Regenerate periodically with new keywords

The system is designed to be fully autonomous - just provide a URL and let it work!