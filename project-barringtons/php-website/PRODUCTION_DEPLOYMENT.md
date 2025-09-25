# Production Deployment Checklist

## 🚀 Ready for Production Deployment

This website is now ready for production deployment. Follow these steps to go live:

## 1. Pre-Deployment Setup (Local)

### ✅ Update .env File
Edit `.env` with your production credentials:

```env
# Generate random strings for these (32+ characters)
ADMIN_SECRET_KEY=YOUR_RANDOM_32_CHAR_STRING_HERE
CACHE_CLEAR_KEY=YOUR_DIFFERENT_RANDOM_STRING_HERE

# Update email addresses if needed
CONTACT_TO_EMAIL=info@barringtonsfunerals.co.uk
CONTACT_BCC_EMAIL=backup@barringtonsfunerals.co.uk  # Optional

# Add your SendGrid API key
SENDGRID_API_KEY=SG.YOUR_ACTUAL_SENDGRID_API_KEY_HERE

# Update domain
APP_URL=https://barringtonsfunerals.co.uk
```

**To generate secure random strings:**
- Use: https://passwordsgenerator.net/ (32+ characters, mixed case, numbers)
- Or in PHP: `echo bin2hex(random_bytes(16));`

### ✅ Files Ready for Upload
All files in `/php-website/` directory are ready for production.

## 2. Upload to Production Server

### Via cPanel File Manager or FTP:
1. Connect to your hosting account
2. Navigate to `public_html` (or your domain's root directory)
3. Upload all files from `/php-website/` directory
4. Maintain the folder structure

### File Permissions (Important!):
```bash
# Directories
chmod 755 /public_html
chmod 755 /public_html/assets
chmod 755 /public_html/includes
chmod 755 /public_html/content

# Writable directories for uploads
chmod 775 /public_html/assets/images/uploads
chmod 775 /public_html/content

# Files
chmod 644 *.php
chmod 644 .htaccess
chmod 600 .env  # CRITICAL - Protect env file
```

## 3. Configure .htaccess

1. Rename `.htaccess.production` to `.htaccess`
2. Edit .htaccess and:
   - Update error log path (line ~38)
   - Uncomment HTTPS redirect (lines ~84-85) after SSL is active
   - Choose www or non-www preference (lines ~88-94)

## 4. Test Critical Functions

### Admin Mode:
- Access: `https://yourdomain.com?admin=YOUR_ADMIN_SECRET_KEY`
- Test editing some text
- Test image upload
- Save changes
- Logout: `https://yourdomain.com?logout=true`

### Contact Form:
1. Fill out and submit the contact form
2. Check that you receive the email
3. Verify the user gets a confirmation email
4. Test spam protection (leave honeypot field empty)

### External Services:
- MuchLoved widget should load on production domain
- Funeral notices link opens correctly
- YouTube video loads when clicked

## 5. Install WordPress for Blog (via cPanel)

1. In cPanel, use "WordPress Manager" or "Softaculous"
2. Install to: `/public_html/blog/`
3. Configure:
   - Site Title: Barringtons Blog
   - Admin Username: (secure username)
   - Admin Password: (strong password)
   - Admin Email: your email

4. After installation:
   - Go to Settings → Permalinks → Choose "Post name"
   - Test REST API: `https://yourdomain.com/blog/wp-json/wp/v2/posts`

5. Enable blog on homepage:
   - Edit `/index.php`
   - Uncomment lines 203-206 (blog fetcher code)

## 6. SSL & Security

### In cPanel:
1. Enable FREE SSL certificate (Let's Encrypt)
2. Force HTTPS redirect in .htaccess (uncomment lines)
3. Set up automatic SSL renewal

### Security Checks:
- Verify .env file is not accessible: `https://yourdomain.com/.env` (should 403)
- Check JSON files blocked: `https://yourdomain.com/content/index.json` (should 403)
- Confirm uploads directory protected

## 7. Performance & SEO

### Submit to Search Engines:
- Google Search Console: https://search.google.com/search-console
- Bing Webmaster Tools: https://www.bing.com/webmasters

### Create sitemap.xml:
Basic sitemap structure provided - customize with your pages.

### Monitor Performance:
- Google PageSpeed Insights
- GTmetrix
- Check mobile responsiveness

## 8. Backup Strategy

### Regular Backups:
1. Set up automated cPanel backups (weekly)
2. Download monthly manual backups of:
   - All files
   - MySQL database (when WordPress installed)
   - `/content/` directory (contains all editable content)

## 9. Go Live Checklist

- [ ] .env file updated with production values
- [ ] Files uploaded to server
- [ ] .htaccess configured and active
- [ ] SSL certificate installed and working
- [ ] Admin mode tested and working
- [ ] Contact form tested and emails received
- [ ] External services (MuchLoved, etc.) working
- [ ] WordPress installed (optional, can be done later)
- [ ] Site submitted to search engines
- [ ] Backup system configured
- [ ] DNS pointed to new hosting (if moving hosts)

## 10. Post-Launch

### Monitor for 24-48 hours:
- Check error logs
- Verify all pages load correctly
- Monitor contact form submissions
- Check external service integrations

### Future Maintenance:
- Regular WordPress updates (if installed)
- Content updates via admin mode
- Regular backups
- Monitor disk space usage

## Support Contacts

**Hosting Issues:** Contact your hosting provider
**Domain/DNS:** Your domain registrar
**SendGrid:** https://sendgrid.com/support
**WordPress:** https://wordpress.org/support

## Emergency Rollback

If issues arise:
1. Keep a full backup of the working development version
2. Can quickly restore via cPanel backup
3. Disable problematic features via .env settings

---

## 🎉 Congratulations!

Your website is production-ready with:
- ✅ Fully functional PHP website
- ✅ Admin editing capabilities
- ✅ SendGrid email integration with fallback
- ✅ Security hardening via .htaccess
- ✅ SEO optimization
- ✅ Mobile responsive design
- ✅ WordPress blog integration ready
- ✅ Performance optimizations

Estimated deployment time: 2-3 hours (including testing)