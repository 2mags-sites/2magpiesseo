<?php
// MuchLoved Tributes Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('muchloved-tributes');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Online Tributes & Memorial Pages | Barringtons Liverpool';
$page_description = $content['meta']['description'] ?? 'Create lasting online tributes and memorial pages. Share memories, photos, and donate to charity in memory of your loved one.';
$page_keywords = $content['meta']['keywords'] ?? 'online tributes, memorial pages, MuchLoved, remembrance, charity donations';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="muchloved-tributes" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'Online Tributes', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-block fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'MuchLoved Online Tributes', 'intro.title'); ?></h2>
                <p class="lead-text"><?php echo editable($content['intro']['lead_text'] ?? 'Create a beautiful, lasting tribute where family and friends can share memories, photos, and support chosen charities.', 'intro.lead_text'); ?></p>
            </div>

            <!--BEGIN MUCHLOVED FUNERAL LISTINGS WIDGETS-->
            <style type="text/css">

            .muchloved-notice-search {
                margin-top: 20px;
            }

            .muchloved-funerals-search-box {
                padding: 7px;
                box-sizing: border-box;
                margin-right: 10px;
            }


            input,
            textarea {
                border: 1px solid #062159 !important;
                font-family: inherit;
                padding: 5px;
            }

            .muchloved-funerals-search-box {
                margin-bottom: 10px;
                width: 300px !important;
            }

            input[type="button"],
            input[type="reset"] {
                background: #e05d22;
                background: -webkit-linear-gradient(top, #e05d22 0%, #de3280 100%);
                background: linear-gradient(to bottom, #e05d22 0%, #de3280 100%);
                border: none;
                border-bottom: 3px solid #b93207;
                border-radius: 2px;
                color: #fff;
                display: inline-block;
                padding: 11px 24px 10px;
                text-decoration: none;
            }
            .ml-notice {
                padding: 15px !important;
                margin-bottom: 20px !important;
            }
            .ml-thumb {
                float: left;
                width: 40%;
            }
            .ml-details {
                margin-left: 5%;
                width: 55%;
                float: left;
            }
            .text-page h2,
            .text-page h2 a {
                font-family: "Roboto Slab", Sans-serif; !important;
                font-size: 30px !important;
                color: #de3280 !important;
                padding-bottom: 17px !important;
            }

            .ml-notice {
                border: 2px ridge #de3280;
                padding: 30px 0 30px 0;
            }

            .ml-notice p {
                text-align: left;
            }

            .ml-notice:last-child {
                border: none;
            }

            h3.ml-name {
                margin-top: 0;
                margin-bottom: 5px;
            }

            h3.ml-name a{
                font-size:22px !important;
            }
            .ml-link {
                    color: #de3280 !important;
                text-decoration: none;
                font-size: 13px !important
            }

            .ml-link{

                font-weight: 600 !important;
            }

            .ml-link:hover {
                text-decoration: underline;
                color: #062159;
            }

            .ml-notice p {
                margin: 0;
            }

            .ml-clear {
                clear: both;
            }

            .ml-thumb {
                float: left;
            }



            .ml-thumb img {
                border-radius: 5px;
            }

            .ml-title {
                margin-bottom: 5px;
            }

            .ml-heading {
                margin-top: 20px;
                margin-bottom: 20px;
            }

            .muchloved-funerals-widget-search-results:empty {
                display: none;
            }

            .ml-funeral-list-item {
                margin-bottom: 10px;
                margin-left: 10px;
            }

            .ml-link {
                display: inline-block;
                margin-top: 5px;
            }

            .muchloved-funerals-search-summary {
                padding-top: 10px;
                padding-bottom: 10px;
            }

            .muchloved-funerals-search-box {
                padding: 7px;
                border: none;
                width: 250px !important;
                box-sizing: border-box;
            }

            .muchloved-funerals-search-button {
                color: #FFF;
                line-height: 1;
                background-color: #de3280 !important;
                border: 1px solid #de3280 !important;
                padding: 9px;
            }

            .ml-garden-link {
                margin-bottom: 0;
                margin-top: 6px;
            }

            .muchloved-notice-search {
                margin-top: 20px;
            }
            </style>
            <div class="muchloved-notice-search">
                <input type="text" class="muchloved-funerals-search-box textfield_effect" data-widgetid="338355093" placeholder="Search all funerals by name">
                <input type="button" class="muchloved-funerals-search-button" data-widgetid="338355093" value="Search">
            </div>
            <div class="muchloved-funerals-search-summary" data-widgetid="338355093">
            </div>
            <div class="muchloved-funerals-widget-search-results" data-widgetid="338355093"></div>
            <h2>Upcoming Funerals</h2>
            <div class="muchloved-funerals-widget" data-widgetid="338355095" data-view="listings"></div>
            <h2>Recent Funerals</h2>
            <div class="line"></div>
            <div class="muchloved-funerals-widget" data-widgetid="338355096" data-view="listings"></div>
            <script>
            ! function(d, s, id) {
                var js,
                    fjs = d.getElementsByTagName(s)[0],
                    p = /^http:/.test(d.location) ? "http" : "https";
                if(!d.getElementById(id)) {
                    js = d.createElement(s);
                    js.id = id;
                    js.src = p + "://www.muchloved.com/client/widgets/funerals.widget.min.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }
            }(document, "script", "muchloved-funerals-widget-js");
            </script>
            <!--END MUCHLOVED FUNERAL LISTINGS WIDGETS-->
        </div>
    </section>

<?php
// Include footer
require_once 'includes/footer.php';
?>