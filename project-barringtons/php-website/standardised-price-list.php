<?php
// Standardised Price List Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('standardised-price-list');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Funeral Price List - Transparent Pricing | Barringtons Liverpool';
$page_description = $content['meta']['description'] ?? 'Complete funeral price list with transparent pricing. No hidden costs. Funerals from £1,795. View all costs for burial, cremation, and additional services.';
$page_keywords = $content['meta']['keywords'] ?? 'funeral prices Liverpool, funeral costs, burial prices, cremation costs, funeral price list';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="standardised-price-list" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'Standardised Price List', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-section fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'Transparent Pricing for Your Peace of Mind', 'intro.title'); ?></h2>
                <p><?php echo editable($content['intro']['paragraphs'][0] ?? 'Following the Competition and Market Authority\'s (CMA) market investigation into the funeral sector it is compulsory that every funeral director has a Standard Price List. The price list should be available on their website, in the window of their branch, and available to be taken away for reflection.', 'intro.paragraphs.0'); ?></p>

                <p><?php echo editable($content['intro']['paragraphs'][1] ?? 'The Standard Price List should enable you to compare funeral directors on a like-for-like basis based on the CMA\'s attended funeral service and unattended funeral service specifications.', 'intro.paragraphs.1'); ?></p>

                <p><?php echo editable($content['intro']['paragraphs'][2] ?? 'The Standard Funeral Service is a set funeral package that includes all of the basic needs to make funeral arrangements, however it is not a flexible package. There are additional options that can be added to the service which are included in the Additional Options Price List at the bottom of the page. In most cases however if you do not want the fixed Standard Funeral Option the best value is achieved by choosing our bespoke service.', 'intro.paragraphs.2'); ?></p>
            </div>

            <div class="price-notice fade-in">
                <p class="date-notice"><?php echo editable($content['price_notice']['date'] ?? 'April 2025', 'price_notice.date'); ?></p>
                <h2><?php echo editable($content['price_notice']['title'] ?? 'STANDARDISED PRICE LIST', 'price_notice.title'); ?></h2>
                <p><?php echo editable($content['price_notice']['description'] ?? 'All funeral directors are legally required to publish this Price List for a standardised set of products and services. This is to help you think through your options and make choices, and to let you compare prices between different funeral directors (because prices can vary).', 'price_notice.description'); ?></p>
            </div>

            <div class="price-section fade-in">
                <h2><?php echo editable($content['attended_funeral']['title'] ?? 'ATTENDED FUNERAL', 'attended_funeral.title'); ?> <span class="subtitle"><?php echo editable($content['attended_funeral']['subtitle'] ?? '(funeral director\'s charges only)', 'attended_funeral.subtitle'); ?></span></h2>
                <p class="section-description"><?php echo editable($content['attended_funeral']['description'] ?? 'This is a funeral where family and friends have a ceremony, event or service for the deceased person at the same time as they attend their burial or cremation.', 'attended_funeral.description'); ?></p>

                <table class="price-table">
                    <tbody>
                        <?php if (isset($content['attended_funeral']['items']) && is_array($content['attended_funeral']['items'])): ?>
                            <?php foreach ($content['attended_funeral']['items'] as $index => $item): ?>
                        <tr>
                            <td class="service-description"><?php echo editable($item['description'] ?? '', "attended_funeral.items.{$index}.description"); ?></td>
                            <td class="price"><?php echo editable($item['price'] ?? '', "attended_funeral.items.{$index}.price"); ?></td>
                        </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <tr class="total-row">
                            <td class="service-description total"></td>
                            <td class="price total"><?php echo editable($content['attended_funeral']['total'] ?? '£2,470.00', 'attended_funeral.total'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="price-section fade-in">
                <h2><?php echo editable($content['unattended_funeral']['title'] ?? 'UNATTENDED FUNERAL', 'unattended_funeral.title'); ?></h2>
                <p class="section-description"><?php echo editable($content['unattended_funeral']['description'] ?? 'This is a funeral where family and friends may choose to have a ceremony, event or service for the deceased person, but they do not attend the burial or cremation itself.', 'unattended_funeral.description'); ?></p>

                <table class="price-table">
                    <tbody>
                        <?php if (isset($content['unattended_funeral']['items']) && is_array($content['unattended_funeral']['items'])): ?>
                            <?php foreach ($content['unattended_funeral']['items'] as $index => $item): ?>
                        <tr>
                            <td class="service-description"><?php echo editable($item['description'] ?? '', "unattended_funeral.items.{$index}.description"); ?></td>
                            <td class="price"><?php echo editable($item['price'] ?? '', "unattended_funeral.items.{$index}.price"); ?></td>
                        </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="fees-section fade-in">
                <h2><?php echo editable($content['fees']['title'] ?? 'FEES YOU MUST PAY', 'fees.title'); ?></h2>

                <div class="fee-item">
                    <p><strong><?php echo editable($content['fees']['burial']['title'] ?? 'For an Attended or Unattended burial funeral, the burial fee.', 'fees.burial.title'); ?><sup>1</sup></strong><br>
                    In this local area, the typical cost of the burial fee is: <strong><?php echo editable($content['fees']['burial']['cost_range'] ?? '£1008-£1200', 'fees.burial.cost_range'); ?></strong></p>

                    <p class="fee-note"><?php echo editable($content['fees']['burial']['note'] ?? 'For a new grave, you will also need to pay for the plot; for an existing grave with a memorial in place, you may need to pay a removal/replacement fee. In addition, the cemetery may charge a number of other fees.', 'fees.burial.note'); ?></p>
                </div>

                <div class="fee-item">
                    <p><strong><?php echo editable($content['fees']['cremation']['title'] ?? 'For an Attended cremation funeral, the cremation fee.', 'fees.cremation.title'); ?></strong><br>
                    In this local area, the typical cost of a cremation is: <strong><?php echo editable($content['fees']['cremation']['cost_range'] ?? '£848-£1125', 'fees.cremation.cost_range'); ?></strong></p>
                </div>

                <p class="notice"><?php echo editable($content['fees']['notice'] ?? 'Please discuss any specific religious, belief-based and/or cultural requirements that you have with the funeral director.', 'fees.notice'); ?></p>
            </div>

            <div class="price-section fade-in">
                <h2><?php echo editable($content['additional_services']['title'] ?? 'ADDITIONAL FUNERAL DIRECTOR PRODUCTS AND SERVICES', 'additional_services.title'); ?></h2>
                <p class="section-description"><?php echo editable($content['additional_services']['description'] ?? 'This funeral director can supply a range of optional, additional products and services, or to arrange (on your behalf) for a third party to supply them. Examples include:', 'additional_services.description'); ?></p>

                <table class="price-table">
                    <tbody>
                        <?php if (isset($content['additional_services']['items']) && is_array($content['additional_services']['items'])): ?>
                            <?php foreach ($content['additional_services']['items'] as $index => $item): ?>
                        <tr>
                            <td class="service-description"><?php echo editable($item['description'] ?? '', "additional_services.items.{$index}.description"); ?></td>
                            <td class="price"><?php echo editable($item['price'] ?? '', "additional_services.items.{$index}.price"); ?></td>
                        </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <p class="additional-note"><?php echo editable($content['additional_services']['note'] ?? 'The funeral director can give you a full list of what they can supply. They are likely to charge for these additional products and services, so you may choose to take care of some arrangements without their involvement, or you can use a different supplier.', 'additional_services.note'); ?></p>
            </div>

            <div class="footnotes fade-in">
                <p><sup>1</sup> <?php echo editable($content['footnotes']['1'] ?? 'This fee (which is sometimes called the interment fee) is the charge made for digging and closing a new grave, or for reopening and closing an existing grave.', 'footnotes.1'); ?></p>
            </div>
        </div>
    </section>

<?php
// Include footer
require_once 'includes/footer.php';
?>