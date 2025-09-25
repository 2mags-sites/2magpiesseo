# Website Rebuilder - PHP Website Generator

A powerful Python-based system that analyzes any website and generates a complete PHP website with SEO optimization, HTML5 Boilerplate templates, and no database requirements.

## Features

- 🌐 **Universal Business Support** - Works with ANY type of website (law firms, restaurants, medical practices, consulting, etc.)
- 🔍 **Automatic Business Detection** - Intelligently detects business type from website content
- 📊 **Complete Website Analysis** - Extracts business info, services, team, contact details
- 🚀 **PHP Website Generation** - Creates complete PHP websites that work on any hosting
- 📈 **SEO Optimization** - Generates optimized meta tags, schema markup, and sitemap
- 🎨 **HTML5 Boilerplate** - Clean, semantic HTML5 with responsive CSS
- 💾 **No Database Required** - Uses JSON files for data storage
- ⚡ **Fast Performance** - Static files with PHP templating for speed

## Quick Start

### Prerequisites

- Python 3.8 or higher
- PHP 7.4+ (for testing generated websites locally)

### Installation

1. Clone or download this repository
2. Install Python dependencies:

```bash
pip install -r requirements.txt
```

### Run Demo

Test the system with demo data:

```bash
python main.py --demo
```

This generates a complete demo website in the `output` folder.

### Generate Website from URL

Analyze and rebuild any website:

```bash
python main.py --url "https://example-business.com" --keywords "keyword1,keyword2,keyword3"
```

## Usage Examples

### Law Firm Website
```bash
python main.py --url "https://lawfirm.com" --keywords "divorce lawyer,family law,child custody"
```

### Restaurant Website
```bash
python main.py --url "https://restaurant.com" --keywords "italian restaurant,pizza delivery,fine dining"
```

### Medical Practice
```bash
python main.py --url "https://clinic.com" --keywords "family doctor,medical clinic,health checkup"
```

### Any Business
```bash
python main.py --url "https://any-business.com"
# Keywords are auto-generated if not provided
```

## Output Structure

The system generates:

```
output/
└── [business-name]_[timestamp]/
    ├── index.php              # Homepage
    ├── about.php              # About page
    ├── contact.php            # Contact page
    ├── [service-pages].php    # SEO-optimized service pages
    ├── 404.php                # Error page
    ├── config.php             # Configuration
    ├── functions.php          # Helper functions
    ├── components/            # Reusable PHP components
    │   ├── header.php
    │   └── footer.php
    ├── handlers/              # Form handlers
    │   └── contact.php
    ├── data/                  # JSON data files
    │   ├── universal/         # Business, navigation, team data
    │   └── pages/             # Page-specific content
    ├── assets/                # CSS, JS, images
    │   ├── css/
    │   │   ├── main.css
    │   │   └── normalize.css
    │   └── js/
    │       └── main.js
    ├── robots.txt             # SEO directives
    ├── sitemap.xml            # XML sitemap
    ├── .htaccess              # URL rewriting rules
    └── README.txt             # Deployment instructions
```

## Testing Generated Websites

### Local Testing with PHP

1. Navigate to the generated website folder:
```bash
cd output/[generated-folder]
```

2. Start PHP development server:
```bash
php -S localhost:8000
```

3. Open browser to: http://localhost:8000

### Production Deployment

1. Upload all files to your PHP web hosting
2. Ensure PHP 7.4+ is enabled
3. Set file permissions (644 for files, 755 for directories)
4. Update `config.php` with your email for contact form
5. Test all functionality

## How It Works

1. **Analysis** - Scrapes and analyzes the target website
2. **Detection** - Automatically detects business type
3. **Generation** - Creates JSON data files with extracted content
4. **Enhancement** - Improves content for SEO and readability
5. **PHP Creation** - Generates PHP files with HTML5 templates
6. **Optimization** - Adds schema markup, meta tags, sitemaps

## Generated Website Features

- ✅ **SEO Optimized** - Meta tags, schema markup, clean URLs
- ✅ **Mobile Responsive** - Works on all devices
- ✅ **Fast Loading** - No database queries, optimized assets
- ✅ **Contact Forms** - Working PHP contact form
- ✅ **FAQ Sections** - Auto-generated FAQs for each service
- ✅ **Clean Code** - Semantic HTML5, organized PHP
- ✅ **Easy to Edit** - Simple JSON files for content updates

## Configuration

### Customizing Output

Edit `config/settings.json` to customize:
- Minimum word counts
- Number of FAQ questions
- SEO parameters
- Business type detection rules

### Adding Business Types

Add new business types in `analyzer/business_detector.py`:
- Define keywords and patterns
- Add specific content requirements
- Customize SEO strategies

## Command Line Options

```bash
python main.py [options]

Options:
  --url URL             Website URL to analyze and rebuild
  --keywords KEYWORDS   Comma-separated target keywords for SEO
  --name NAME          Override business name
  --output OUTPUT      Output directory (default: output)
  --demo               Run demo with example data
  --help               Show help message
```

## Troubleshooting

### Common Issues

1. **Module not found errors**
   - Run: `pip install -r requirements.txt`

2. **Website not loading**
   - Check if site uses heavy JavaScript (may need Selenium)
   - Verify URL is accessible

3. **PHP errors in generated site**
   - Ensure PHP 7.4+ is installed
   - Check file permissions

4. **Contact form not working**
   - Update email in `config.php`
   - Ensure mail() function is enabled on server

## Advanced Usage

### Batch Processing

Process multiple websites:

```python
# batch_process.py
import subprocess

websites = [
    ("https://site1.com", "keyword1,keyword2"),
    ("https://site2.com", "keyword3,keyword4"),
]

for url, keywords in websites:
    subprocess.run([
        "python", "main.py",
        "--url", url,
        "--keywords", keywords
    ])
```

### Custom Templates

Add custom PHP templates in `templates/` folder:
- Create new page templates
- Modify existing components
- Add custom styling

## System Requirements

### Development Machine
- Python 3.8+
- 4GB RAM minimum
- 1GB free disk space

### Generated Website Hosting
- PHP 7.4 or higher
- No database required
- Standard shared hosting works
- 50MB disk space per site

## Support

For issues or questions:
1. Check the FAQ section below
2. Review error logs in `website_rebuilder.log`
3. Ensure all dependencies are installed

## FAQ

**Q: Can this rebuild any website?**
A: Yes, it works with any business website. Complex web apps may need customization.

**Q: Do I need coding knowledge?**
A: No, just run the commands. The system handles everything.

**Q: Can I edit the generated websites?**
A: Yes, edit the JSON files in `/data` folder or modify PHP files directly.

**Q: Is the output SEO-friendly?**
A: Yes, includes meta tags, schema markup, sitemaps, and clean URLs.

**Q: What hosting do I need?**
A: Any PHP 7.4+ hosting. No database required.

## License

This project is for educational and commercial use. Feel free to modify and distribute.

## Credits

Built with:
- Python for analysis and generation
- PHP for website functionality
- HTML5 Boilerplate for templates
- BeautifulSoup for web scraping

---

**Ready to rebuild websites?** Run `python main.py --demo` to see it in action!