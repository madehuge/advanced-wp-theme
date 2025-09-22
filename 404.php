<?php
/**
 * The template for displaying 404 (Page Not Found) pages
 *
 * @package custom-theme
 */

get_header();
?>

<section class="resources_sec position-relative" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="container w-100 position-relative comn_sec_py px-4 px-sm-3">
        <div class="container_inr">

            <div class="resources_sec_heading_holder d-flex justify-content-between flex-column flex-md-row mb_50 column-gap-3 row-gap-3 align-items-md-end">

                <div class="sec_heading_wrap">

                    <!-- Page Heading -->
                    <h1 class="sec_heading_tag_text mb_25 words_slide_from_right split_text">
                        <?php esc_html_e( 'Page Not Found', 'custom-theme' ); ?>
                    </h1>

                    <!-- Page Description -->
                    <h3 class="sec_heading_text words_slide_from_right split_text">
                        <?php esc_html_e( 'Sorry, the page you are looking for does not exist.', 'custom-theme' ); ?>
                    </h3>

                    <!-- Optional: Add helpful link back to homepage -->
                    <p class="words_slide_from_right split_text">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <?php esc_html_e( 'Return to Homepage', 'custom-theme' ); ?>
                        </a>
                    </p>

                </div>

            </div>

        </div>
    </div>
</section>

<?php get_footer(); ?>
