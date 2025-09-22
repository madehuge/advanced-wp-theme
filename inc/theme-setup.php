<?php
/**
 * Theme Setup (Add supports, load text domain, register menus)
 */

// WORDPRESS LOGIN LOGO CHANGE.
function modify_logo() {
    $logo_style = '<style type="text/css">';
    $logo_style .= '.login h1 a {
        background-image: url(' . CUSTOM_THEME_URI. '/assets/images/logo.svg) !important; 
        background-size: 100% auto !important;
        width: 250px;
        height:150px;
    }';

    $logo_style .='body{background:#fff !important;}';
    $logo_style .= '</style>';
    echo $logo_style;
}
add_action('login_head', 'modify_logo');

// WORDPRESS LOGIN LOGO URL CHANGE.
add_filter('login_headerurl', 'custom_loginlogo_url');
function custom_loginlogo_url($url) {
    return home_url();
}

function custom_india_setup() {
    load_theme_textdomain('custom-theme', CUSTOM_THEME_PATH . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', ['search-form', 'gallery', 'caption']);
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus([
        'main-menu' => __('Main Menu', 'custom-theme'),
    ]);
}
add_action('after_setup_theme', 'custom_india_setup');

/**
 * Enqueue Styles and Scripts
 */
function custom_india_enqueue_assets() {
    // CSS Files
    wp_enqueue_style('bootstrap-css', CUSTOM_THEME_URI . '/assets/css/bootstrap.min.css', [], '5.0.0');
    wp_enqueue_style('splide-css', CUSTOM_THEME_URI . '/assets/css/splide.min.css', [], '3.6.11');
    wp_enqueue_style('choice-css', CUSTOM_THEME_URI . '/assets/css/choices.min.css', [], '1.0.0');
    wp_enqueue_style('fancybox-css', CUSTOM_THEME_URI . '/assets/css/fancybox.css', [], '1.0.0');

    wp_enqueue_style('custom-style', CUSTOM_THEME_URI . '/assets/scss/style.css', [], wp_get_theme()->get('Version'));

    // JS Files (in Footer)

    wp_deregister_script('jquery'); // Remove default jQuery
    wp_register_script('jquery', CUSTOM_THEME_URI . '/assets/js/jquery.min.js', [], '3.6.0', true);
    wp_enqueue_script('jquery');   // Enqueue our custom jQuery

    wp_enqueue_script('bootstrap-bundle', CUSTOM_THEME_URI . '/assets/js/bootstrap.bundle.min.js', ['jquery'], '5.0.0', true);
    wp_enqueue_script('splide-js', CUSTOM_THEME_URI . '/assets/js/splide.min.js', ['jquery'], '3.6.11', true);
    wp_enqueue_script('splide-auto-scroll', CUSTOM_THEME_URI . '/assets/js/splide-extension-auto-scroll.min.js', ['splide-js'], '1.0.0', true);
    wp_enqueue_script('choices-js', CUSTOM_THEME_URI . '/assets/js/choices.min.js', [], '1.0.0', true);
    wp_enqueue_script('split-type', CUSTOM_THEME_URI . '/assets/js/split-type.js', [], '1.0.0', true);
    wp_enqueue_script('gsap', CUSTOM_THEME_URI . '/assets/js/gsap.min.js', [], '3.12.0', true);
    wp_enqueue_script('scroll-trigger', CUSTOM_THEME_URI . '/assets/js/ScrollTrigger.min.js', ['gsap'], '3.12.0', true);
    wp_enqueue_script('scroll-smoother', CUSTOM_THEME_URI . '/assets/js/ScrollSmoother.min.js', ['gsap'], '3.12.0', true);
    wp_enqueue_script('init-script', CUSTOM_THEME_URI . '/assets/js/init.js', ['jquery', 'choices-js'], wp_get_theme()->get('Version'), true);
    wp_enqueue_script('cf7-cleanup-script', CUSTOM_THEME_URI . '/assets/js/cf7-cleanup.js', ['jquery'], wp_get_theme()->get('Version'), true);
    wp_enqueue_script('news-listing-script', CUSTOM_THEME_URI . '/assets/js/news-listing.js', ['jquery', 'choices-js'], wp_get_theme()->get('Version'), true);
    
    // Enqueue resource filter script (always, but only initialize on resources page)
    wp_enqueue_script('fancybox', CUSTOM_THEME_URI . '/assets/js/fancybox.umd.js', ['jquery'], '3.5.7', true);
    wp_enqueue_script('resource-filter', CUSTOM_THEME_URI . '/assets/js/resource-filter.js', ['jquery', 'splide-js'], wp_get_theme()->get('Version'), true);
    
    // Localize script for resource filtering
    $ajax_data = [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('custom_filter_resources_nonce'),
        'theme_uri' => CUSTOM_THEME_URI,
        'is_resources_page' => is_page_template('page-resources.php')
    ];
    
    wp_localize_script('resource-filter', 'custom_ajax', $ajax_data);

    // Localize script for AJAX
    wp_localize_script('init-script', 'newsFilterAjax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('news_filter_nonce'),
        'theme_uri' => CUSTOM_THEME_URI,
        'home_url' => home_url('/')
    ]);

    // Localize script for news listing
    wp_localize_script('news-listing-script', 'newsListingAjax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('news_filter_nonce'),
        'theme_uri' => CUSTOM_THEME_URI,
        'home_url' => home_url('/')
    ]);
}
add_action('wp_enqueue_scripts', 'custom_india_enqueue_assets');

/**
 * Add dynamic favicon: use Site Icon if set, fallback to default theme favicon.
 */
function custom_add_favicon() {
    // Check if WordPress Site Icon is set
    if ( function_exists('get_site_icon_url') && get_site_icon_url() ) {
        $favicon_url = get_site_icon_url();
    } else {
        // Fallback to default favicon in the theme
        $favicon_url = CUSTOM_THEME_URI . '/assets/images/favicon.png';
    }

    // Output the favicon link tag
    echo '<link rel="icon" href="' . esc_url($favicon_url) . '" type="image/png" sizes="32x32">' . "\n";
}
add_action('wp_head', 'custom_add_favicon');

// Footer Setting & Arrow Image Config

// Add Scroll Up Button in Footer
function custom_add_scroll_up_button() {
    ?>
    <!-- Scroll Up Button -->
    <div class="scrollup position-fixed d-flex align-items-center justify-content-center" id="scrollUpBtn">
        <img src="<?php echo esc_url( CUSTOM_THEME_URI . '/assets/images/icons/up_arrow.svg' ); ?>" alt="<?php esc_attr_e( 'Scroll Up', 'custom-theme' ); ?>" />
    </div>
    <?php
}
add_action( 'wp_footer', 'custom_add_scroll_up_button' );

/**
 * Allow SVG uploads.
 */
function custom_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'custom_mime_types');


/**
 * Sanitize SVG files on upload by removing <script> tags.
 */
function custom_sanitize_svg($file) {
    if ($file['type'] === 'image/svg+xml') {
        $svg_content = file_get_contents($file['tmp_name']);
        // Remove any <script> tags for basic security
        $svg_content = preg_replace('/<script.*?<\/script>/is', '', $svg_content);
        file_put_contents($file['tmp_name'], $svg_content);
    }
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'custom_sanitize_svg');


/**
 * Ensure SVG works in Gutenberg Image block.
 */
function custom_fix_svg_in_gutenberg($block_content, $block) {
    if ($block['blockName'] === 'core/image') {
        add_filter('upload_mimes', 'custom_mime_types');
    }
    return $block_content;
}
add_filter('render_block', 'custom_fix_svg_in_gutenberg', 10, 2);



// Register theme menu locations
function custom_register_menus() {
    register_nav_menus(
        array(
            'company-footer'    => __( 'Company Footer Menu', 'custom-theme' ),
            'product-footer'    => __( 'Product Footer Menu', 'custom-theme' ),
        )
    );
}
add_action( 'after_setup_theme', 'custom_register_menus' );