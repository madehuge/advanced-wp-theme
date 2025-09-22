<?php
/**
 * Single News Template
 * 
 * Template for displaying individual news articles
 */

get_header();

while (have_posts()) :
    the_post();
    
    // Get the first category for badge
    $categories = get_the_terms(get_the_ID(), 'news_cat');
    $category_slug = !empty($categories) ? $categories[0]->slug : 'news';
    $badge_class = custom_get_news_badge_class($category_slug);
?>

<!-- Breadcrumb Section -->
<?php
// Use auto-generated breadcrumbs for single news posts
get_template_part('template-parts/components/breadcrumb');
?>

<!-- Main Content Section -->
<section class="news_details_content_sec comn_sec_py position-relative">
    <div class="bg_pattern_holder position-absolute">
        <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/news/news_details_content_sec_pattern.svg" alt="" width="" height="" data-speed="0.9" data-lag="0">
    </div>

    <div class="container px-4 px-sm-3 position-relative z-2">
        <div class="row gy-4 gy-xl-0 gx-3 gx-lg-5 align-items-start">
            <!-- Main Article Content -->
            <div class="col col-news-details">
                <div class="news_details_content">
                    <div class="default-general-content">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Image Slider -->
                    <div class="default_content_img_slider_holder">
                        <div class="splide default_content_img_slider">
                            <div class="splide__track">
                                <div class="splide__list">
                                    <?php
                                    // Get gallery images from ACF field
                                    $gallery_images = get_field('news_gallery');
                                    if ($gallery_images && is_array($gallery_images)) {
                                        foreach ($gallery_images as $image) {
                                            ?>
                                            <div class="splide__slide">
                                                <div class="default_content_image">
                                                    <div class="ratio">
                                                        <img src="<?php echo esc_url($image['sizes']['large']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="object-fit-cover">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else if (has_post_thumbnail()) {
                                        // Fallback to featured image
                                        ?>
                                        <div class="splide__slide">
                                            <div class="default_content_image">
                                                <div class="ratio">
                                                    <?php the_post_thumbnail('large', ['class' => 'object-fit-cover', 'alt' => get_the_title()]); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    // Get additional content from ACF field
                    $additional_content = get_field('additional_content');
                    if (!empty($additional_content)):
                    ?>
                    <div class="default-general-content">
                        <?php echo wp_kses_post($additional_content); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sticky Share Icons -->
            <div class="col col-sticky-share sticky_thing">
                <div class="sticky_share_widget" id="stickyShareWidget">
                <?php echo do_shortcode('[custom_social_share style="vertical" size="medium" color="default" platforms="linkedin,twitter,facebook" show_title="true"]'); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related News Section -->
<?php
$related_news = new WP_Query([
    'post_type' => 'news',
    'posts_per_page' => 3,
    'post__not_in' => [get_the_ID()],
    'tax_query' => [
        [
            'taxonomy' => 'news_cat',
            'field' => 'term_id',
            'terms' => wp_get_post_terms(get_the_ID(), 'news_cat', ['fields' => 'ids'])
        ]
    ]
]);

if ($related_news->have_posts()):
?>
<section class="related_news_sec comn_sec_py pt-0">
    <div class="container px-4 px-sm-3">
        <div class="d-flex flex-row justify-content-between align-items-center related_news_sec_heading">
            <div class="sec_heading_wrap">
                <h2 class="sec_heading_text_2 words_slide_from_right split_text"><?php _e('Related news', 'custom-theme'); ?></h2>
            </div>
            <div class="btn_holder">
                <a href="<?php echo custom_get_news_listing_url(); ?>" class="btn btn_deep_blue_outline">
                    <span class="bnt_text_wrap"><span><?php _e('View all', 'custom-theme'); ?></span></span>
                    <span class="arrow"></span>
                </a>
            </div>
        </div>

        <div class="related_news_slider_holder">
            <div class="splide" id="relatedNewsSlider">
                <div class="splide__track">
                    <div class="splide__list">
                        <?php while ($related_news->have_posts()): $related_news->the_post(); ?>
                            <?php
                            $related_categories = get_the_terms(get_the_ID(), 'news_cat');
                            $related_category_slug = !empty($related_categories) ? $related_categories[0]->slug : 'news';
                            ?>
                            <div class="splide__slide">
                                <div class="news_card">
                                    <a href="<?php the_permalink(); ?>" class="total_link"></a>
                                    <div class="news_card_image">
                                        <div class="ratio">
                                            <?php if (has_post_thumbnail()): ?>
                                                <?php the_post_thumbnail('medium', ['class' => 'object-fit-cover', 'alt' => get_the_title()]); ?>
                                            <?php else: ?>
                                                <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/news/news-list-img1.jpg" alt="<?php the_title(); ?>" class="object-fit-cover">
                                            <?php endif; ?>
                                        </div>
                                        <div class="news_card_badge">
                                            <span><?php echo esc_html($related_categories[0]->name ?? __('News', 'custom-theme')); ?></span>
                                        </div>
                                    </div>
                                    <div class="news_card_content">
                                        <h3 class="news_card_title"><?php the_title(); ?></h3>
                                        <p class="news_card_date"><?php echo get_the_date('F j, Y'); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
// Use the reusable CTA component
get_template_part('template-parts/components/cta');
?>

<?php
endwhile;
get_footer();
?>
