<?php
/**
 * Template part for displaying a "No Content Found" message.
 *
 * @package custom-theme
 */
?>

<section class="no-content-notice" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="container comn_sec_py px-4 px-sm-3">
        <div class="container_inr">
            <div class="no_content_message text-center py-5">

                <h2 class="no_content_title mb-3">
                    <?php esc_html_e('Nothing Found', 'custom-theme'); ?>
                </h2>

                <p class="no_content_description mb-4">
                    <?php esc_html_e('Sorry, but nothing matched your criteria. Please try searching again.', 'custom-theme'); ?>
                </p>

                <?php get_search_form(); ?>

            </div>
        </div>
    </div>
</section>
