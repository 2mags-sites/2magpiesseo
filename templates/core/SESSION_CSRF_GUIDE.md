# Session and CSRF Token Management Guide

## Critical Learnings from Implementation

### 1. Session Management Issues & Solutions

#### Problem:
- Multiple files trying to start sessions causing conflicts
- Incorrect session status checking using `!isset($_SESSION)`
- Session not starting before CSRF token generation

#### Solution:
Always use proper session status checking:

```php
// CORRECT - Check session status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// WRONG - This doesn't check if session is started
if (!isset($_SESSION)) {
    session_start();
}
```

### 2. CSRF Token Implementation

#### Essential Setup:

**In header.php or config.php (runs on every page):**
```php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
```

**In contact form:**
```html
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
```

**In contact handler:**
```php
// Verify CSRF token (with localhost bypass for development)
$skip_csrf = (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost');

if (!$skip_csrf) {
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) ||
        $_SESSION['csrf_token'] !== $_POST['csrf_token']) {
        // Token validation failed
        $response['message'] = 'Security validation failed. Please refresh and try again.';
        echo json_encode($response);
        exit;
    }
}
```

### 3. Common Pitfalls to Avoid

1. **Starting sessions multiple times**
   - Always check session_status() before session_start()
   - Don't rely on `@session_start()` error suppression

2. **Session conflicts between files**
   - config.php, header.php, and contact-handler.php all need proper checks
   - Use consistent session checking method across all files

3. **CSRF token not generated**
   - Must generate token BEFORE form is displayed
   - Best place: header.php or config.php (included on every page)

4. **PHP built-in server issues**
   - localhost development may have session path issues
   - Consider bypassing CSRF in development with proper checks

### 4. File Load Order

Correct order for includes:
1. Session start (with status check)
2. CSRF token generation
3. Config file loading
4. Other includes

Example:
```php
// 1. Session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 3. Configuration
require_once 'includes/config.php';

// 4. Other includes
require_once 'includes/env-loader.php';
```

### 5. Development vs Production

**Development (localhost):**
- Can bypass CSRF for easier testing
- Use relaxed security for debugging
- Enable error reporting

**Production:**
- Full CSRF protection enabled
- Strict session handling
- Error logging only (no display)

### 6. Testing Checklist

Before deploying, verify:
- [ ] Sessions start without errors
- [ ] CSRF token is generated on page load
- [ ] Form includes CSRF token in hidden field
- [ ] Contact handler validates token (production)
- [ ] Session conflicts resolved
- [ ] Error messages are user-friendly

### 7. Debugging Tips

If contact form fails with "Security validation failed":
1. Check browser developer tools > Application > Cookies for session
2. Verify CSRF token exists in form (View Source)
3. Check PHP error logs for session warnings
4. Test with the test-contact.php debug page
5. Temporarily enable SHOW_ERRORS in config.php

### 8. Required Template Files

For email functionality, these files must be copied to every project:
- `templates/core/config.php` - Proper session handling
- `templates/core/env-loader.php` - Environment variables
- `templates/core/email-service.php` - Email sending with SendGrid/PHP mail
- `templates/core/contact-handler.php` - Form processing with CSRF
- `templates/.env.template` - Environment configuration

### 9. Environment Variables

Essential .env settings for email:
```env
# Email Configuration
SENDGRID_API_KEY=SG.xxxxx
CONTACT_TO_EMAIL=info@example.com
CONTACT_FROM_EMAIL=noreply@example.com
EMAIL_SERVICE=sendgrid
EMAIL_FALLBACK=true
```

### 10. Implementation Order

When setting up a new project:
1. Copy all template files
2. Update .env with project-specific values
3. Verify session handling in config.php
4. Test contact form locally
5. Deploy and test in production

## Summary

The main issues encountered were:
1. **Session conflicts** - Solved with proper session_status() checks
2. **Missing CSRF tokens** - Solved by generating in header.php
3. **Missing env-loader.php** - Must be included for EmailService to work
4. **Incorrect session checking** - Use session_status(), not isset($_SESSION)

These solutions are now incorporated into all template files for future projects.