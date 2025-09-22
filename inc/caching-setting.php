<?php

// Clear footer cache when Customizer settings are saved
function custom_clear_and_regenerate_footer_cache() {
    // Delete old cache
    delete_transient('custom_footer_html_cache');

    ob_start();
    get_template_part('template-parts/components/footer'); // or include your footer content here
    $footer_html = ob_get_clean();

    // Store transient
    set_transient('custom_footer_html_cache', $footer_html, 12 * HOUR_IN_SECONDS);
}
add_action('customize_save_after', 'custom_clear_and_regenerate_footer_cache');
add_action('wp_update_nav_menu', 'custom_clear_and_regenerate_footer_cache');


// Clear caches related to pages, posts, CPTs, and multilingual About section
function custom_clear_all_related_caches( $post_id ) {
    // Skip revisions
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    $cache_key = 'custom_page_' . $post_id;
    delete_transient( $cache_key );
    delete_transient( 'about_section_content_cache_' . CUSTOM_LANGUAGE_CODE );

    $post_type = get_post_type( $post_id );
    if ( 'hero_slide' === $post_type ) {
        delete_transient( 'custom_hero_slides' );
    }
}
add_action( 'save_post', 'custom_clear_all_related_caches' );
add_action( 'deleted_post', 'custom_clear_all_related_caches' );
add_action( 'trash_post', 'custom_clear_all_related_caches' );
