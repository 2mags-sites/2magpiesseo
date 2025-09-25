<?php
// Include configuration (defines BASE_URL and includes admin-config)
require_once 'includes/config.php';

// Load content from JSON
$content = loadContent('index');

// Set page meta from JSON
$page_title = $content['meta']['title'];
$page_description = $content['meta']['description'];
$page_keywords = $content['meta']['keywords'];

// Include header
require_once 'includes/header.php';
?>

<?php if (ADMIN_MODE): ?>
    <!-- Admin Bar -->
    <div style="background: #333; color: white; padding: 10px; text-align: center; position: fixed; top: 0; width: 100%; z-index: 9999;">
        <strong>🔧 ADMIN MODE</strong> - Click on any text to edit |
        <button onclick="saveAllChanges()" style="background: #28a745; color: white; border: none; padding: 5px 15px; cursor: pointer; margin: 0 10px;">Save Changes</button>
        <a href="?logout=true" style="color: #dc3545;">Exit Admin Mode</a>
    </div>
    <div style="height: 40px;"></div>
<?php endif; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1><?php echo editable($content['hero']['title'], 'hero.title'); ?></h1>
            <p class="lead"><?php echo editable($content['hero']['subtitle'], 'hero.subtitle'); ?></p>
            <div style="margin-top: 30px;">
                <a href="<?php echo $content['hero']['cta_primary']['link']; ?>" class="btn btn-primary" style="margin-right: 15px;">
                    <?php echo editable($content['hero']['cta_primary']['text'], 'hero.cta_primary.text'); ?>
                </a>
                <a href="<?php echo $content['hero']['cta_secondary']['link']; ?>" class="btn btn-outline">
                    <?php echo editable($content['hero']['cta_secondary']['text'], 'hero.cta_secondary.text'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main>
        <!-- Welcome Section -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title"><?php echo editable($content['welcome']['title'], 'welcome.title'); ?></h2>
                <div class="content-with-image">
                    <div class="content-text">
                        <p class="lead"><?php echo editable($content['welcome']['lead'], 'welcome.lead'); ?></p>
                        <p><?php echo editable($content['welcome']['paragraph1'], 'welcome.paragraph1'); ?></p>
                        <p><?php echo editable($content['welcome']['paragraph2'], 'welcome.paragraph2'); ?></p>
                    </div>
                    <div class="content-image">
                        <i class="fas fa-heart fa-3x"></i>
                        <p><?php echo editable($content['welcome']['image_text'], 'welcome.image_text'); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title"><?php echo editable($content['services']['title'], 'services.title'); ?></h2>
                <div class="service-grid">
                    <?php foreach ($content['services']['items'] as $index => $service): ?>
                    <div class="service-card">
                        <i class="fas <?php echo $service['icon']; ?> fa-2x"></i>
                        <h3><?php echo editable($service['title'], "services.items.$index.title"); ?></h3>
                        <p><?php echo editable($service['description'], "services.items.$index.description"); ?></p>
                        <a href="<?php echo $service['link']; ?>" class="btn-link">Learn More →</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title"><?php echo editable($content['why_choose']['title'], 'why_choose.title'); ?></h2>
                <div class="content-with-image reverse">
                    <div class="content-text">
                        <?php foreach ($content['why_choose']['points'] as $index => $point): ?>
                        <div class="service-box">
                            <h4><i class="fas <?php echo $point['icon']; ?>"></i> <?php echo editable($point['title'], "why_choose.points.$index.title"); ?></h4>
                            <p><?php echo editable($point['description'], "why_choose.points.$index.description"); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="content-image">
                        <i class="fas fa-award fa-3x"></i>
                        <p><?php echo editable($content['why_choose']['image_text'], 'why_choose.image_text'); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Service Areas -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title"><?php echo editable($content['areas']['title'], 'areas.title'); ?></h2>
                <p class="lead text-center mb-40"><?php echo editable($content['areas']['subtitle'], 'areas.subtitle'); ?></p>
                <div class="locations-grid">
                    <?php foreach ($content['areas']['locations'] as $index => $location): ?>
                    <a href="<?php echo $location['link']; ?>" class="location-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4><?php echo editable($location['name'], "areas.locations.$index.name"); ?></h4>
                        <p><?php echo editable($location['postcode'], "areas.locations.$index.postcode"); ?></p>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="content-section">
            <div class="container">
                <div class="cta-box">
                    <h2>Need Immediate Assistance?</h2>
                    <p class="lead">We're here for you 24 hours a day, 7 days a week</p>
                    <p>Our compassionate team is ready to help guide you through this difficult time with care and professionalism.</p>
                    <p><strong>Call us anytime: 0161 969 2288</strong></p>
                    <div style="margin-top: 30px;">
                        <a href="/contact.php" class="btn btn-white" style="margin-right: 15px;">Contact Us</a>
                        <a href="/arranging-a-funeral.php" class="btn btn-outline-white">Arranging a Funeral</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php if (ADMIN_MODE): ?>
<style>
.editable {
    position: relative;
    background-color: rgba(255, 255, 0, 0.1);
    outline: 1px dashed #ccc;
    cursor: text;
    min-height: 20px;
    display: inline-block;
}

.editable:hover {
    background-color: rgba(255, 255, 0, 0.2);
    outline: 2px dashed #999;
}

.editable[contenteditable="true"] {
    background-color: rgba(0, 123, 255, 0.1);
    outline: 2px solid #007bff;
}
</style>

<script>
// Make elements editable on click
document.addEventListener('DOMContentLoaded', function() {
    const editables = document.querySelectorAll('.editable');
    const changes = {};

    editables.forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            this.contentEditable = true;
            this.focus();
        });

        element.addEventListener('blur', function() {
            this.contentEditable = false;
            // Track changes
            const field = this.dataset.field;
            const value = this.innerHTML;
            changes[field] = value;
            console.log('Changed:', field, value);
        });

        element.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.blur();
            }
        });
    });

    // Save function
    window.saveAllChanges = function() {
        if (Object.keys(changes).length === 0) {
            alert('No changes to save');
            return;
        }

        // Send changes to server
        fetch('admin-save.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                page: 'index',
                changes: changes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Changes saved successfully!');
                // Clear changes
                Object.keys(changes).forEach(key => delete changes[key]);
            } else {
                alert('Error saving changes: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error saving changes: ' + error);
        });
    };
});
</script>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>