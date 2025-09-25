<?php
// Include admin config
require_once 'includes/admin-config.php';

// Load content from JSON
$content = loadContent('service-direct-cremation');

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
        <strong>🔧 ADMIN MODE</strong> - Click text to edit |
        <button onclick="addNewFAQ()" style="background: #17a2b8; color: white; border: none; padding: 5px 15px; cursor: pointer; margin: 0 10px;">+ Add FAQ</button>
        <button onclick="saveAllChanges()" style="background: #28a745; color: white; border: none; padding: 5px 15px; cursor: pointer; margin: 0 10px;">Save Changes</button>
        <a href="?logout=true" style="color: #dc3545;">Exit Admin Mode</a>
    </div>
    <div style="height: 40px;"></div>
<?php endif; ?>

    <!-- Service Hero Section -->
    <section class="service-hero">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <?php foreach ($content['hero']['breadcrumb'] as $crumb): ?>
                    <li class="breadcrumb-item<?php echo $crumb['link'] ? '' : ' active'; ?>">
                        <?php if ($crumb['link']): ?>
                            <a href="<?php echo $crumb['link']; ?>"><?php echo $crumb['text']; ?></a>
                        <?php else: ?>
                            <?php echo $crumb['text']; ?>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <h1><?php echo editable($content['hero']['title'], 'hero.title'); ?></h1>
            <p class="lead"><?php echo editable($content['hero']['subtitle'], 'hero.subtitle'); ?></p>
        </div>
    </section>

    <!-- Main Content -->
    <main>
        <!-- Service Overview -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title"><?php echo editable($content['overview']['title'], 'overview.title'); ?></h2>
                <div class="content-with-image">
                    <div class="content-text">
                        <p class="lead"><?php echo editable($content['overview']['lead'], 'overview.lead'); ?></p>
                        <p><?php echo editable($content['overview']['paragraph1'], 'overview.paragraph1'); ?></p>
                        <p><?php echo editable($content['overview']['paragraph2'], 'overview.paragraph2'); ?></p>
                    </div>
                    <div class="content-image">
                        <?php if (ADMIN_MODE): ?>
                        <div class="image-upload-container" data-field="overview.image_url">
                            <?php if (!empty($content['overview']['image_url'])): ?>
                                <img src="<?php echo $content['overview']['image_url']; ?>" alt="Service image" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                                <button onclick="removeImage('overview.image_url')" style="background: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; display: block; margin: 10px auto;">Remove Image</button>
                            <?php else: ?>
                                <i class="fas <?php echo $content['overview']['image_icon'] ?? 'fa-dove'; ?> fa-3x"></i>
                            <?php endif; ?>
                            <input type="file" id="upload-overview" accept="image/*" style="display: none;" onchange="uploadImage(this, 'overview.image_url')">
                            <button onclick="document.getElementById('upload-overview').click()" style="background: #007bff; color: white; border: none; padding: 5px 10px; cursor: pointer; margin-top: 10px;">
                                <?php echo !empty($content['overview']['image_url']) ? 'Change Image' : 'Upload Image'; ?>
                            </button>
                        </div>
                        <?php else: ?>
                            <?php if (!empty($content['overview']['image_url'])): ?>
                                <img src="<?php echo $content['overview']['image_url']; ?>" alt="Service image" style="max-width: 100%; height: auto;">
                            <?php else: ?>
                                <i class="fas <?php echo $content['overview']['image_icon'] ?? 'fa-dove'; ?> fa-3x"></i>
                            <?php endif; ?>
                        <?php endif; ?>
                        <p><?php echo editable($content['overview']['image_text'], 'overview.image_text'); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Direct Cremation -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title"><?php echo editable($content['why_choose']['title'], 'why_choose.title'); ?></h2>
                <div class="content-with-image reverse">
                    <div class="content-text">
                        <h3><?php echo editable($content['why_choose']['subtitle'], 'why_choose.subtitle'); ?></h3>
                        <p><?php echo editable($content['why_choose']['intro'], 'why_choose.intro'); ?></p>
                        <ul>
                            <?php foreach ($content['why_choose']['reasons'] as $index => $reason): ?>
                            <li>
                                <strong><?php echo editable($reason['title'], "why_choose.reasons.$index.title"); ?></strong> -
                                <?php echo editable($reason['description'], "why_choose.reasons.$index.description"); ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <p><?php echo editable($content['why_choose']['conclusion'], 'why_choose.conclusion'); ?></p>
                    </div>
                    <div class="content-image">
                        <?php if (ADMIN_MODE): ?>
                        <div class="image-upload-container" data-field="why_choose.image_url">
                            <?php if (!empty($content['why_choose']['image_url'])): ?>
                                <img src="<?php echo $content['why_choose']['image_url']; ?>" alt="Why choose image" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                                <button onclick="removeImage('why_choose.image_url')" style="background: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; display: block; margin: 10px auto;">Remove Image</button>
                            <?php else: ?>
                                <i class="fas <?php echo $content['why_choose']['image_icon'] ?? 'fa-hands-holding-heart'; ?> fa-3x"></i>
                            <?php endif; ?>
                            <input type="file" id="upload-why-choose" accept="image/*" style="display: none;" onchange="uploadImage(this, 'why_choose.image_url')">
                            <button onclick="document.getElementById('upload-why-choose').click()" style="background: #007bff; color: white; border: none; padding: 5px 10px; cursor: pointer; margin-top: 10px;">
                                <?php echo !empty($content['why_choose']['image_url']) ? 'Change Image' : 'Upload Image'; ?>
                            </button>
                        </div>
                        <?php else: ?>
                            <?php if (!empty($content['why_choose']['image_url'])): ?>
                                <img src="<?php echo $content['why_choose']['image_url']; ?>" alt="Why choose image" style="max-width: 100%; height: auto;">
                            <?php else: ?>
                                <i class="fas <?php echo $content['why_choose']['image_icon'] ?? 'fa-hands-holding-heart'; ?> fa-3x"></i>
                            <?php endif; ?>
                        <?php endif; ?>
                        <p><?php echo editable($content['why_choose']['image_text'], 'why_choose.image_text'); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- What's Included -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title"><?php echo editable($content['included']['title'], 'included.title'); ?></h2>
                <div class="content-with-image">
                    <div class="content-text">
                        <?php foreach ($content['included']['services'] as $index => $service): ?>
                        <div class="service-box">
                            <h4><i class="fas <?php echo $service['icon']; ?>"></i> <?php echo editable($service['title'], "included.services.$index.title"); ?></h4>
                            <p><?php echo editable($service['description'], "included.services.$index.description"); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="content-image">
                        <i class="fas fa-pound-sign fa-3x"></i>
                        <p><?php echo editable($content['included']['price_text'], 'included.price_text'); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- The Process -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title"><?php echo editable($content['process']['title'], 'process.title'); ?></h2>
                <p class="lead text-center mb-40"><?php echo editable($content['process']['subtitle'], 'process.subtitle'); ?></p>

                <?php foreach ($content['process']['steps'] as $index => $step): ?>
                <div class="process-step">
                    <div class="step-number"><?php echo $index + 1; ?></div>
                    <div class="step-content">
                        <h4><?php echo editable($step['title'], "process.steps.$index.title"); ?></h4>
                        <p><?php echo editable($step['description'], "process.steps.$index.description"); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Memorial Options -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title"><?php echo editable($content['memorial']['title'], 'memorial.title'); ?></h2>
                <div class="content-with-image reverse">
                    <div class="content-text">
                        <h3><?php echo editable($content['memorial']['subtitle'], 'memorial.subtitle'); ?></h3>
                        <p><?php echo editable($content['memorial']['intro'], 'memorial.intro'); ?></p>
                        <p><?php echo editable($content['memorial']['intro2'], 'memorial.intro2'); ?></p>
                        <ul>
                            <?php foreach ($content['memorial']['ideas'] as $index => $idea): ?>
                            <li><?php echo editable($idea, "memorial.ideas.$index"); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p><?php echo editable($content['memorial']['conclusion'], 'memorial.conclusion'); ?></p>
                    </div>
                    <div class="content-image">
                        <i class="fas fa-tree fa-3x"></i>
                        <p><?php echo editable($content['memorial']['image_text'], 'memorial.image_text'); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title"><?php echo editable($content['faqs']['title'], 'faqs.title'); ?></h2>

                <div id="faq-container">
                    <?php foreach ($content['faqs']['items'] as $index => $faq): ?>
                    <div class="faq-item" data-index="<?php echo $index; ?>">
                        <?php if (ADMIN_MODE): ?>
                        <button class="faq-delete" onclick="deleteFAQ(<?php echo $index; ?>)" style="float: right; background: #dc3545; color: white; border: none; padding: 2px 8px; cursor: pointer; margin: 5px;">×</button>
                        <?php endif; ?>
                        <div class="faq-question">
                            <span <?php echo ADMIN_MODE ? 'class="editable" data-field="faqs.items.' . $index . '.question"' : ''; ?>>
                                <?php echo $faq['question']; ?>
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p <?php echo ADMIN_MODE ? 'class="editable" data-field="faqs.items.' . $index . '.answer"' : ''; ?>>
                                <?php echo $faq['answer']; ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="content-section">
            <div class="container">
                <div class="cta-box">
                    <h2>Arrange a Direct Cremation</h2>
                    <p class="lead">Simple, dignified, affordable</p>
                    <p>Our caring team is here to help you arrange a direct cremation with compassion and professionalism.</p>
                    <p><strong>Call us 24/7: 0161 969 2288</strong></p>
                    <div style="margin-top: 30px;">
                        <a href="/contact.php" class="btn btn-white" style="margin-right: 15px;">Contact Us</a>
                        <a href="tel:01619692288" class="btn btn-outline-white">Call Now</a>
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

.faq-delete {
    display: none;
}

.faq-item:hover .faq-delete {
    display: block;
}

.image-upload-container {
    border: 2px dashed #ccc;
    padding: 20px;
    text-align: center;
    background: rgba(0, 123, 255, 0.05);
    border-radius: 8px;
    transition: all 0.3s;
}

.image-upload-container:hover {
    border-color: #007bff;
    background: rgba(0, 123, 255, 0.1);
}

.image-upload-container img {
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

#new-faq-modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    z-index: 10000;
    width: 90%;
    max-width: 600px;
}

#modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
}
</style>

<!-- FAQ Modal -->
<div id="modal-overlay" onclick="closeFAQModal()"></div>
<div id="new-faq-modal">
    <h3>Add New FAQ</h3>
    <div style="margin: 20px 0;">
        <label style="display: block; margin-bottom: 5px;">Question:</label>
        <input type="text" id="new-faq-question" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <div style="margin: 20px 0;">
        <label style="display: block; margin-bottom: 5px;">Answer:</label>
        <textarea id="new-faq-answer" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
    </div>
    <div style="text-align: right;">
        <button onclick="closeFAQModal()" style="background: #6c757d; color: white; border: none; padding: 8px 20px; cursor: pointer; margin-right: 10px;">Cancel</button>
        <button onclick="saveFAQ()" style="background: #28a745; color: white; border: none; padding: 8px 20px; cursor: pointer;">Add FAQ</button>
    </div>
</div>

<script>
// Make elements editable on click
document.addEventListener('DOMContentLoaded', function() {
    const editables = document.querySelectorAll('.editable');
    const changes = {};
    let deletedFAQs = [];

    // Image upload function
    window.uploadImage = function(input, fieldPath) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File too large. Maximum size is 5MB.');
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Invalid file type. Only JPG, PNG, GIF, and WebP allowed.');
                return;
            }

            // Show loading indicator
            const container = input.closest('.image-upload-container');
            const originalContent = container.innerHTML;
            container.innerHTML = '<div style="text-align: center;"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Uploading...</p></div>';

            // Create FormData
            const formData = new FormData();
            formData.append('image', file);

            // Upload image
            fetch('admin-upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update changes object
                    changes[fieldPath] = data.file.url;

                    // Update UI with uploaded image
                    container.innerHTML = `
                        <img src="${data.file.url}" alt="Service image" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                        <button onclick="removeImage('${fieldPath}')" style="background: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; display: block; margin: 10px auto;">Remove Image</button>
                        <input type="file" id="upload-${fieldPath.replace(/\./g, '-')}" accept="image/*" style="display: none;" onchange="uploadImage(this, '${fieldPath}')">
                        <button onclick="document.getElementById('upload-${fieldPath.replace(/\./g, '-')}').click()" style="background: #007bff; color: white; border: none; padding: 5px 10px; cursor: pointer; margin-top: 10px;">Change Image</button>
                    `;

                    alert('Image uploaded successfully! Remember to click "Save Changes" to save permanently.');
                } else {
                    alert('Upload failed: ' + data.message);
                    container.innerHTML = originalContent;
                }
            })
            .catch(error => {
                alert('Upload error: ' + error);
                container.innerHTML = originalContent;
            });
        }
    };

    // Remove image function
    window.removeImage = function(fieldPath) {
        if (confirm('Remove this image?')) {
            changes[fieldPath] = null;

            const container = document.querySelector(`.image-upload-container[data-field="${fieldPath}"]`);
            container.innerHTML = `
                <i class="fas fa-dove fa-3x"></i>
                <input type="file" id="upload-${fieldPath.replace(/\./g, '-')}" accept="image/*" style="display: none;" onchange="uploadImage(this, '${fieldPath}')">
                <button onclick="document.getElementById('upload-${fieldPath.replace(/\./g, '-')}').click()" style="background: #007bff; color: white; border: none; padding: 5px 10px; cursor: pointer; margin-top: 10px;">Upload Image</button>
            `;
        }
    };

    editables.forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
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

    // FAQ Functions
    window.addNewFAQ = function() {
        document.getElementById('modal-overlay').style.display = 'block';
        document.getElementById('new-faq-modal').style.display = 'block';
    };

    window.closeFAQModal = function() {
        document.getElementById('modal-overlay').style.display = 'none';
        document.getElementById('new-faq-modal').style.display = 'none';
        document.getElementById('new-faq-question').value = '';
        document.getElementById('new-faq-answer').value = '';
    };

    window.saveFAQ = function() {
        const question = document.getElementById('new-faq-question').value;
        const answer = document.getElementById('new-faq-answer').value;

        if (!question || !answer) {
            alert('Please fill in both question and answer');
            return;
        }

        // Add to changes with special marker for new FAQ
        const newIndex = document.querySelectorAll('.faq-item').length;
        changes['faqs.items.' + newIndex + '.question'] = question;
        changes['faqs.items.' + newIndex + '.answer'] = answer;
        changes['faqs.items.NEW'] = true; // Marker for new FAQ

        // Add to DOM immediately for preview
        const faqContainer = document.getElementById('faq-container');
        const newFaqHtml = `
            <div class="faq-item" data-index="${newIndex}" style="background: #d4edda;">
                <button class="faq-delete" onclick="deleteFAQ(${newIndex})" style="float: right; background: #dc3545; color: white; border: none; padding: 2px 8px; cursor: pointer; margin: 5px;">×</button>
                <div class="faq-question">
                    <span>${question}</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>${answer}</p>
                </div>
            </div>
        `;
        faqContainer.insertAdjacentHTML('beforeend', newFaqHtml);

        closeFAQModal();
        alert('New FAQ added. Click "Save Changes" to save permanently.');
    };

    window.deleteFAQ = function(index) {
        if (confirm('Delete this FAQ?')) {
            deletedFAQs.push(index);
            changes['faqs.items.DELETE'] = deletedFAQs;
            document.querySelector(`.faq-item[data-index="${index}"]`).style.display = 'none';
        }
    };

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
                page: 'service-direct-cremation',
                changes: changes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Changes saved successfully!');
                location.reload(); // Reload to show updated content
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