<?php
/**
 * Resource Card Component
 * 
 * @param array $resource Resource data array
 * @param string $card_type Type of card (slider, list)
 */

if (!isset($resource)) {
    return;
}

$post_id = $resource['id'];
$title = $resource['title'];
$permalink = $resource['permalink'];
$featured_image = $resource['featured_image'];
$type_slug = $resource['type'];
$type_name = $resource['type_name'];
$date = $resource['date'];
$duration = $resource['duration'];
$has_video = $resource['has_video'];
$video_url = $resource['video_url'];
$card_type = isset($resource['card_type']) ? $resource['card_type'] : 'list';

$badge_class = custom_get_resource_type_badge_class($type_slug);
?>

<?php if ($card_type === 'slider'): ?>
<!-- Slider Card -->
<div class="splide__slide">
    <div class="resources_blog_slide_card has_link">
        <a href="<?php echo esc_url($permalink); ?>" class="total_link"></a>
        <div class="resource-cat">
            <span><?php echo esc_html($type_name); ?></span>
        </div>
        <div class="resources_main_image">
            <div class="ratio">
                <?php if ($featured_image): ?>
                    <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr($title); ?>">
                <?php else: ?>
                    <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/resource/blog-slider-img.jpg" alt="<?php echo esc_attr($title); ?>">
                <?php endif; ?>
            </div>
        </div>
        <div class="resources_main_overlay">
            <div class="resources_main_content">
                <h3 class="resources_main_title heading_text_24 multi-3-line-ellipsis">
                    <?php echo esc_html($title); ?>
                </h3>
                <div class="resources_main_meta">
                    <span class="resources_date"><?php echo esc_html($date); ?></span>
                    <span class="resources_read_time"><?php echo esc_html($duration); ?></span>
                </div>
                <span class="read_more_arrow_link color_blue" aria-label="Learn More">Read more</span>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<!-- List Card -->
<div class="col-md-6 col-lg-4">
    <div class="resources_blog_list_card has_link fade-in">
        <a href="<?php echo esc_url($permalink); ?>" class="total_link"></a>
        <div class="resource-cat">
            <span><?php echo esc_html($type_name); ?></span>
        </div>
        <div class="resources_side_card_body d-flex flex-column">
            <div class="resources_side_image">
                <div class="ratio">
                    <?php if ($featured_image): ?>
                        <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr($title); ?>">
                    <?php else: ?>
                        <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/resource/blog-list-img-1.jpg" alt="<?php echo esc_attr($title); ?>">
                    <?php endif; ?>
                </div>
                <?php if ($has_video): ?>
                    <div class="resources_image_text">
                        <p><?php echo esc_html($title); ?></p>
                    </div>
                    <div class="video-overlay">
                        <a href="<?php echo esc_url($video_url); ?>" 
                           data-caption="<?php echo esc_attr($title); ?>" 
                           data-fancybox="blogListVideo" 
                           class="play-video-btn" 
                           aria-label="Video">
                            <i>
                                <svg xmlns="http://www.w3.org/2000/svg" width="71" height="77" viewBox="0 0 71 77" fill="none">
                                    <path d="M13.3787 1.69779C6.43671 -2.28423 0.808594 0.977889 0.808594 8.97812V68.0163C0.808594 76.0245 6.43671 79.2824 13.3787 75.3041L64.981 45.7105C71.9253 41.7271 71.9253 35.2734 64.981 31.2909L13.3787 1.69779Z" fill="#4BC4D6"/>
                                </svg>
                            </i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="resources_side_content">
                <h4 class="resources_side_title multi-3-line-ellipsis">
                    <?php echo esc_html($title); ?>
                </h4>
                <div class="resources_side_meta">
                    <span class="resources_date"><?php echo esc_html($date); ?></span>
                    <span class="resources_read_time"><?php echo esc_html($duration); ?></span>
                </div>
                <span class="read_more_arrow_link" aria-label="Learn More">Read more</span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
