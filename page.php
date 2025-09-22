<?php
/**
 * The template for displaying all static pages
 *
 * Optimized for performance, security, and proper caching.
 *
 * @package custom-theme
 */

get_header();
?>

<main id="primary" class="site-main">

<?php
// Check if we're in Customizer preview
if ( is_customize_preview() ) {
    // Always render fresh for live preview
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            get_template_part('template-parts/content/content', 'page');
        endwhile;
    else :
        get_template_part('template-parts/content/content', 'none');
    endif;
} else {
    // Normal frontend → use transient cache
    $cache_key = 'custom_page_' . get_the_ID();
    $page_html = get_transient( $cache_key );

    if ( false === $page_html ) {
        ob_start();

        if ( have_posts() ) :
            while ( have_posts() ) : the_post();
                get_template_part('template-parts/content/content', 'page');
            endwhile;
        else :
            get_template_part('template-parts/content/content', 'none');
        endif;

        $page_html = ob_get_clean();

        // Cache the page HTML for 24 hours
        set_transient( $cache_key, $page_html, DAY_IN_SECONDS );
    }

    // Output cached content
    echo $page_html;
}
?>

</main>

<?php get_footer(); ?>
