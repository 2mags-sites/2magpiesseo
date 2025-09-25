<?php
// Header Component
$business_data = get_universal_data('business');
$navigation_data = get_universal_data('navigation');
?>
<header class="site-header" role="banner">
    <div class="container">
        <div class="header-wrapper">
            <div class="site-branding">
                <a href="/" class="site-logo">
                    <?php echo e($business_data['name']); ?>
                </a>
            </div>

            <nav class="main-navigation" role="navigation">
                <button class="menu-toggle" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <ul class="nav-menu">
                    <?php foreach ($navigation_data['primary_nav'] as $item): ?>
                    <li class="nav-item <?php echo is_active_page(basename($item['url'], '.php')) ? 'active' : ''; ?>">
                        <a href="<?php echo e($item['url']); ?>"><?php echo e($item['label']); ?></a>

                        <?php if (isset($item['dropdown']) && is_array($item['dropdown'])): ?>
                        <ul class="dropdown-menu">
                            <?php foreach ($item['dropdown'] as $subitem): ?>
                            <li><a href="<?php echo e($subitem['url']); ?>"><?php echo e($subitem['label']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <?php if (isset($navigation_data['cta_button'])): ?>
                <a href="<?php echo e($navigation_data['cta_button']['url']); ?>"
                   class="nav-cta <?php echo e($navigation_data['cta_button']['class']); ?>">
                    <?php echo e($navigation_data['cta_button']['label']); ?>
                </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>