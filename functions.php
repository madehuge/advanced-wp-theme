<?php
// Define Constants
if ( ! defined( 'CUSTOM_THEME_URI' ) ) {
    define( 'CUSTOM_THEME_URI', get_template_directory_uri() );
}

if ( ! defined( 'CUSTOM_THEME_PATH' ) ) {
    define( 'CUSTOM_THEME_PATH', get_template_directory() );
}


if ( ! defined( 'CUSTOM_LANGUAGE_CODE' ) ) {
    define( 'CUSTOM_LANGUAGE_CODE', defined('TRP_LANGUAGE_CODE') ? TRP_LANGUAGE_CODE : 'default' );
}


/**
 * Require Modular Theme Support Files
 */
require CUSTOM_THEME_PATH . '/inc/theme-setup.php';
require CUSTOM_THEME_PATH . '/inc/custom-functions.php';
require CUSTOM_THEME_PATH . '/inc/caching-setting.php';
require CUSTOM_THEME_PATH . '/inc/widgets.php'; 
require CUSTOM_THEME_PATH . '/inc/cpt.php'; 
require CUSTOM_THEME_PATH . '/inc/woocommerce-support.php';