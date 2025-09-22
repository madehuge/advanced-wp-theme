<?php
/**
 * Template part for displaying page content in a secure, optimized, and multi-language-ready way.
 *
 * @package custom-theme
 */

?>

<section class="resources_sec position-relative" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="container w-100 position-relative comn_sec_py px-4 px-sm-3">
        <div class="container_inr">

            <div class="resources_sec_heading_holder d-flex justify-content-between flex-column flex-md-row mb-50 column-gap-3 row-gap-3 align-items-md-end">

                <div class="sec_heading_wrap">

                    <h1 class="sec_heading_tag_text mb-25 words_slide_from_right split_text">
                        <?php echo esc_html(get_the_title()); ?>
                    </h1>

                    <div class="page_content words_slide_from_right split_text">
                        <?php
                        // Output content safely and support multi-language plugins like WPML or Polylang || TemplatePress
                        the_content();
                        ?>
                    </div>

                </div>

            </div>

        </div>
    </div>
</section>
