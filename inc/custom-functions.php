<?php
/**
 * Theme Custom Functions
 */
function custom_theme_customizer($wp_customize) {

    // ------------------------------
    // Footer Section
    // ------------------------------
    $wp_customize->add_section('custom_footer_section', array(
        'title'    => __('Footer Settings', 'custom-theme'),
        'priority' => 50,
    ));

    // Footer Background Image
    $wp_customize->add_setting('custom_footer_bg', array(
        'default'           => CUSTOM_THEME_URI . '/assets/images/footer_bg_img.png',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'custom_footer_bg',
            array(
                'label'    => __('Footer Background Image', 'custom-theme'),
                'section'  => 'custom_footer_section',
                'settings' => 'custom_footer_bg',
                'mime_type'=> 'image/png,image/jpg,image/jpeg,image/svg',
            )
        )
    );

    // Footer Logo
    $wp_customize->add_setting('custom_footer_logo', array(
        'default'           => CUSTOM_THEME_URI . '/assets/images/footer_logo.svg',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'custom_footer_logo',
            array(
                'label'    => __('Footer Logo', 'custom-theme'),
                'section'  => 'custom_footer_section',
                'settings' => 'custom_footer_logo',
                'mime_type'=> 'image/png,image/jpg,image/jpeg,image/svg',
            )
        )
    );

    // Social Icons + URLs
    $social_icons = array(
        'fb' => __('Facebook', 'custom-theme'),
        'in' => __('LinkedIn', 'custom-theme'),
        'tw' => __('Twitter', 'custom-theme'),
    );

    foreach ($social_icons as $key => $label) {

        // Icon Image
        $wp_customize->add_setting('custom_' . $key . '_icon', array(
            'default'           => CUSTOM_THEME_URI . '/assets/images/icons/' . $key . '_icon.svg',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'custom_' . $key . '_icon',
                array(
                    'label'    => $label . __(' Icon', 'custom-theme'),
                    'section'  => 'custom_footer_section',
                    'settings' => 'custom_' . $key . '_icon',
                    'mime_type'=> 'image/svg,image/png,image/jpg,image/jpeg',
                )
            )
        );

        // Icon URL
        $wp_customize->add_setting('custom_' . $key . '_url', array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control(
            'custom_' . $key . '_url',
            array(
                'label'    => $label . __(' URL', 'custom-theme'),
                'section'  => 'custom_footer_section',
                'type'     => 'url',
            )
        );
    }

    // 12 Footer Fields with Show/Hide Switch
    $additional_fields = array(
        'about_content' => __('About Small Content', 'custom-theme'),
        'copyright_text' => __('Copyright Text', 'custom-theme'),
        'product_catalogue_name' => __('Download Product Catalogue Name', 'custom-theme'),
        'product_catalogue_url' => __('Download Product Catalogue URL', 'custom-theme'),
        'certificate_name' => __('Download Certificate of Analysis Name', 'custom-theme'),
        'certificate_url' => __('Download Certificate of Analysis URL', 'custom-theme'),
        'office_phone_text' => __('Office Phone Text', 'custom-theme'),
        'office_phone_number' => __('Office Phone Number', 'custom-theme'),
        'manufacture_phone_text' => __('Manufacture Phone Text', 'custom-theme'),
        'manufacture_phone_number' => __('Manufacture Phone Number', 'custom-theme'),
        'email_field_name' => __('Email Field Name', 'custom-theme'),
        'email_field' => __('Email Field', 'custom-theme'),
    );

    foreach ($additional_fields as $field_id => $label) {

        // Show/Hide Checkbox
        $wp_customize->add_setting('custom_' . $field_id . '_show', array(
            'default'           => '1',
            'sanitize_callback' => 'custom_sanitize_checkbox',
        ));

        $wp_customize->add_control(
            'custom_' . $field_id . '_show',
            array(
                'label'    => __('Show ' . $label, 'custom-theme'),
                'section'  => 'custom_footer_section',
                'type'     => 'checkbox',
            )
        );

        // Proper sanitization and field type
        if ($field_id === 'email_field') {
            $sanitize_cb = 'sanitize_email';
            $field_type = 'email';
        } elseif (strpos($field_id, 'url') !== false) {
            $sanitize_cb = 'esc_url_raw';
            $field_type = 'url';
        } else {
            $sanitize_cb = 'sanitize_text_field';
            $field_type = 'text';
        }

        $wp_customize->add_setting('custom_' . $field_id, array(
            'default'           => '',
            'sanitize_callback' => $sanitize_cb,
        ));

        $wp_customize->add_control(
            'custom_' . $field_id,
            array(
                'label'    => $label,
                'section'  => 'custom_footer_section',
                'type'     => $field_type,
            )
        );
    }
}
add_action('customize_register', 'custom_theme_customizer');

// Sanitization for checkbox
function custom_sanitize_checkbox($checked) {
    return ( ( isset($checked) && $checked == 1 ) ? 1 : 0 );
}

/**
 * News Helper Functions
 */

/**
 * Get featured news articles
 * 
 * @param int $limit Number of featured news to retrieve
 * @return WP_Query
 */
function custom_get_featured_news($limit = 3) {
    $args = [
        'post_type' => 'news',
        'posts_per_page' => $limit,
        'meta_query' => [
            [
                'key' => 'featured_news',
                'value' => '1',
                'compare' => '='
            ]
        ],
        'orderby' => 'date',
        'order' => 'DESC'
    ];
    
    return new WP_Query($args);
}

/**
 * Get all news articles with pagination
 * 
 * @param int $posts_per_page Number of posts per page
 * @param string $year Filter by year
 * @param string $category Filter by news category
 * @return WP_Query
 */
function custom_get_news_articles($posts_per_page = 6, $year = '', $category = '') {
    $args = [
        'post_type' => 'news',
        'posts_per_page' => $posts_per_page,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
        'orderby' => 'date',
        'order' => 'DESC'
    ];
    
    // Filter by year
    if (!empty($year)) {
        $args['date_query'] = [
            [
                'after' => [
                    'year' => intval($year),
                    'month' => 1,
                    'day' => 1
                ],
                'before' => [
                    'year' => intval($year),
                    'month' => 12,
                    'day' => 31
                ],
                'inclusive' => true
            ]
        ];
    }
    
    // Filter by category
    if (!empty($category)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'news_cat',
                'field' => 'slug',
                'terms' => $category
            ]
        ];
    }
    
    return new WP_Query($args);
}

/**
 * Get news categories for filter dropdown
 * 
 * @return array
 */
function custom_get_news_categories() {
    $categories = get_terms([
        'taxonomy' => 'news_cat',
        'hide_empty' => true,
    ]);
    
    return $categories;
}

/**
 * Get available years for news filter
 * 
 * @return array
 */
function custom_get_news_years() {
    global $wpdb;
    
    $years = $wpdb->get_col("
        SELECT DISTINCT YEAR(post_date) 
        FROM {$wpdb->posts} 
        WHERE post_type = 'news' 
        AND post_status = 'publish' 
        ORDER BY post_date DESC
    ");
    
    return $years;
}

/**
 * Get news category badge class
 * 
 * @param string $category_slug
 * @return string
 */
function custom_get_news_badge_class($category_slug) {
    $badge_classes = [
        'news' => 'news',
        'events' => 'events',
        'announcements' => 'announcements',
        'updates' => 'updates'
    ];
    
    return isset($badge_classes[$category_slug]) ? $badge_classes[$category_slug] : 'news';
}

/**
 * Add Featured News Meta Box
 */
function custom_add_featured_news_meta_box() {
    add_meta_box(
        'featured_news_meta_box',
        'Featured News',
        'custom_featured_news_meta_box_callback',
        'news',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'custom_add_featured_news_meta_box');

/**
 * Featured News Meta Box Callback
 */
function custom_featured_news_meta_box_callback($post) {
    wp_nonce_field('custom_featured_news_nonce', 'custom_featured_news_nonce');
    $featured = get_post_meta($post->ID, 'featured_news', true);
    ?>
    <label for="featured_news">
        <input type="checkbox" id="featured_news" name="featured_news" value="1" <?php checked($featured, '1'); ?>>
        Mark as Featured News
    </label>
    <p class="description">Check this box to display this news article in the featured news slider.</p>
    <?php
}

/**
 * Save Featured News Meta Box Data
 */
function custom_save_featured_news_meta_box($post_id) {
    if (!isset($_POST['custom_featured_news_nonce']) || !wp_verify_nonce($_POST['custom_featured_news_nonce'], 'custom_featured_news_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $featured = isset($_POST['featured_news']) ? '1' : '0';
    update_post_meta($post_id, 'featured_news', $featured);
}
add_action('save_post', 'custom_save_featured_news_meta_box');

/**
 * AJAX handler for filtering news
 */
function custom_filter_news_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'news_filter_nonce')) {
        wp_die('Security check failed');
    }

    $year = sanitize_text_field($_POST['year']);
    $category = sanitize_text_field($_POST['category']);
    
    // Get filtered news
    $news_query = custom_get_news_articles(6, $year, $category);
    
    ob_start();
    if ($news_query->have_posts()):
        while ($news_query->have_posts()): $news_query->the_post();
            $categories = get_the_terms(get_the_ID(), 'news_cat');
            $category_slug = !empty($categories) ? $categories[0]->slug : 'news';
            ?>
            <div class="col-lg-4 col-md-6">
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
                            <span><?php echo esc_html($categories[0]->name ?? 'News'); ?></span>
                        </div>
                    </div>
                    <div class="news_card_content">
                        <h3 class="news_card_title"><?php the_title(); ?></h3>
                        <p class="news_card_date"><?php echo get_the_date('F j, Y'); ?></p>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
    else:
        ?>
        <div class="col-12">
            <div class="text-center py-5">
                <?php if (!empty($year) || !empty($category)): ?>
                    <div class="no-results-message">
                        <div class="mb-4">
                            <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/icons/search-icon.svg" alt="No results" width="64" height="64" class="mb-3">
                        </div>
                        <h4 class="mb-3">No news articles found</h4>
                        <p class="mb-4">We couldn't find any news articles matching your current filters.</p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <?php if (!empty($year)): ?>
                                <span class="badge bg-secondary">Year: <?php echo esc_html($year); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($category)): ?>
                                <?php 
                                $category_obj = get_term_by('slug', $category, 'news_cat');
                                $category_name = $category_obj ? $category_obj->name : $category;
                                ?>
                                <span class="badge bg-secondary">Category: <?php echo esc_html($category_name); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="mt-4">
                            <a href="<?php echo remove_query_arg(['year', 'category']); ?>" class="btn btn-primary">
                                Clear Filters
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-results-message">
                        <h4 class="mb-3">No news articles available</h4>
                        <p class="mb-4">We don't have any news articles published yet. Please check back later for updates.</p>
                        <div class="mt-4">
                            <a href="<?php echo home_url('/'); ?>" class="btn btn-primary">
                                Go to Homepage
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    endif;
    
    $html = ob_get_clean();
    
    wp_send_json_success([
        'html' => $html,
        'max_pages' => $news_query->max_num_pages
    ]);
}
add_action('wp_ajax_filter_news', 'custom_filter_news_ajax');
add_action('wp_ajax_nopriv_filter_news', 'custom_filter_news_ajax');

/**
 * AJAX handler for loading more news
 */
function custom_load_more_news_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'news_filter_nonce')) {
        wp_die('Security check failed');
    }

    $page = intval($_POST['page']);
    $year = sanitize_text_field($_POST['year']);
    $category = sanitize_text_field($_POST['category']);
    
    // Set up query for specific page
    $args = [
        'post_type' => 'news',
        'posts_per_page' => 6,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC'
    ];
    
    // Filter by year
    if (!empty($year)) {
        $args['date_query'] = [
            [
                'after' => [
                    'year' => intval($year),
                    'month' => 1,
                    'day' => 1
                ],
                'before' => [
                    'year' => intval($year),
                    'month' => 12,
                    'day' => 31
                ],
                'inclusive' => true
            ]
        ];
    }
    
    // Filter by category
    if (!empty($category)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'news_cat',
                'field' => 'slug',
                'terms' => $category
            ]
        ];
    }
    
    $news_query = new WP_Query($args);
    
    ob_start();
    if ($news_query->have_posts()):
        while ($news_query->have_posts()): $news_query->the_post();
            $categories = get_the_terms(get_the_ID(), 'news_cat');
            $category_slug = !empty($categories) ? $categories[0]->slug : 'news';
            ?>
            <div class="col-lg-4 col-md-6">
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
                            <span><?php echo esc_html($categories[0]->name ?? 'News'); ?></span>
                        </div>
                    </div>
                    <div class="news_card_content">
                        <h3 class="news_card_title"><?php the_title(); ?></h3>
                        <p class="news_card_date"><?php echo get_the_date('F j, Y'); ?></p>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
    endif;
    
    $html = ob_get_clean();
    
    wp_send_json_success([
        'html' => $html
    ]);
}
add_action('wp_ajax_load_more_news', 'custom_load_more_news_ajax');
add_action('wp_ajax_nopriv_load_more_news', 'custom_load_more_news_ajax');

/**
 * Handle 404 redirects for news listing with parameters
 */
function custom_handle_news_404() {
    global $wp_query;
    
    // Check if this is a 404 and we have news parameters
    if (is_404() && (isset($_GET['year']) || isset($_GET['category']))) {
        // Try to find the news listing page by slug
        $news_page = get_page_by_path('news-listing');
        
        if (!$news_page) {
            // Try alternative slug variations
            $news_page = get_page_by_path('news');
        }
        
        if ($news_page) {
            // Set the page as found
            $wp_query->is_404 = false;
            $wp_query->is_page = true;
            $wp_query->is_singular = true;
            $wp_query->queried_object = $news_page;
            $wp_query->queried_object_id = $news_page->ID;
            $wp_query->post_count = 1;
            $wp_query->posts = [$news_page];
            $wp_query->found_posts = 1;
            
            // Set the template
            add_filter('template_include', function() {
                return get_template_directory() . '/page-news-listing.php';
            });
        }
    }
}
add_action('template_redirect', 'custom_handle_news_404');

/**
 * Generate breadcrumbs automatically based on post type and current page
 * 
 * @param string $post_type
 * @return array
 */
function custom_generate_breadcrumbs($post_type = '') {
    $breadcrumbs = [
        ['title' => 'Home', 'url' => home_url('/')]
    ];
    
    // Handle different post types and page types
    if (is_home() || is_front_page()) {
        // Home page - no additional breadcrumbs needed
        return $breadcrumbs;
    }
    
    if (is_single()) {
        if ($post_type === 'news') {
            $breadcrumbs[] = ['title' => 'News & Events', 'url' => get_post_type_archive_link('news')];
            $breadcrumbs[] = ['title' => 'News', 'url' => get_post_type_archive_link('news')];
        } elseif ($post_type === 'resource') {
            $breadcrumbs[] = ['title' => 'Resources', 'url' => get_post_type_archive_link('resource')];
        } elseif ($post_type === 'product') {
            $breadcrumbs[] = ['title' => 'Products', 'url' => get_post_type_archive_link('product')];
        } else {
            // Default for other post types
            $breadcrumbs[] = ['title' => ucfirst($post_type), 'url' => get_post_type_archive_link($post_type)];
        }
        $breadcrumbs[] = ['title' => get_the_title(), 'url' => '', 'active' => true];
    }
    
    elseif (is_page()) {
        // Get page hierarchy
        $page_id = get_the_ID();
        $page_ancestors = get_post_ancestors($page_id);
        
        if ($page_ancestors) {
            $page_ancestors = array_reverse($page_ancestors);
            foreach ($page_ancestors as $ancestor_id) {
                $breadcrumbs[] = [
                    'title' => get_the_title($ancestor_id),
                    'url' => get_permalink($ancestor_id)
                ];
            }
        }
        $breadcrumbs[] = ['title' => get_the_title(), 'url' => '', 'active' => true];
    }
    
    elseif (is_archive()) {
        if (is_post_type_archive()) {
            $post_type_obj = get_post_type_object($post_type);
            $breadcrumbs[] = ['title' => $post_type_obj->labels->name, 'url' => '', 'active' => true];
        } elseif (is_category()) {
            $breadcrumbs[] = ['title' => 'Blog', 'url' => get_permalink(get_option('page_for_posts'))];
            $breadcrumbs[] = ['title' => single_cat_title('', false), 'url' => '', 'active' => true];
        } elseif (is_tag()) {
            $breadcrumbs[] = ['title' => 'Blog', 'url' => get_permalink(get_option('page_for_posts'))];
            $breadcrumbs[] = ['title' => single_tag_title('', false), 'url' => '', 'active' => true];
        } elseif (is_tax()) {
            $taxonomy = get_taxonomy(get_queried_object()->taxonomy);
            $breadcrumbs[] = ['title' => $taxonomy->labels->name, 'url' => ''];
            $breadcrumbs[] = ['title' => single_term_title('', false), 'url' => '', 'active' => true];
        } else {
            $breadcrumbs[] = ['title' => get_the_archive_title(), 'url' => '', 'active' => true];
        }
    }
    
    elseif (is_search()) {
        $breadcrumbs[] = ['title' => 'Search Results', 'url' => '', 'active' => true];
    }
    
    elseif (is_404()) {
        $breadcrumbs[] = ['title' => 'Page Not Found', 'url' => '', 'active' => true];
    }
    
    return $breadcrumbs;
}

/**
 * Estimate reading time for content
 * 
 * @param string $content The content to estimate reading time for
 * @return int Estimated reading time in minutes
 */
function custom_estimate_reading_time($content) {
    // Remove HTML tags and count words
    $word_count = str_word_count(strip_tags($content));
    
    // Average reading speed: 200-250 words per minute
    // Using 225 as a middle ground
    $reading_time = ceil($word_count / 225);
    
    // Minimum 1 minute
    return max(1, $reading_time);
}

/**
 * Resource Management Functions
 */

/**
 * Get all resources (posts + custom resource post type) with filtering
 * 
 * @param string $type Filter by resource type (blogs, videos, webinars, all)
 * @param int $posts_per_page Number of posts per page
 * @param string $search Search term
 * @return WP_Query
 */
function custom_get_resources($type = 'all', $posts_per_page = 12, $search = '') {
    $args = [
        'post_type' => ['post', 'resource'],
        'posts_per_page' => $posts_per_page,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish'
    ];
    
    // Filter by resource type
    if ($type !== 'all') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'resource_type',
                'field' => 'slug',
                'terms' => $type
            ]
        ];
    }
    
    // Search functionality
    if (!empty($search)) {
        $args['s'] = $search;
    }
    
    return new WP_Query($args);
}

/**
 * Get featured resources for slider
 * 
 * @param int $limit Number of featured resources
 * @return WP_Query
 */
function custom_get_featured_resources($limit = 4) {
    $args = [
        'post_type' => ['post', 'resource'],
        'posts_per_page' => $limit,
        'meta_query' => [
            [
                'key' => 'featured_resource',
                'value' => '1',
                'compare' => '='
            ]
        ],
        'orderby' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish'
    ];
    
    return new WP_Query($args);
}

/**
 * Get resource type badge class
 * 
 * @param string $type_slug
 * @return string
 */
function custom_get_resource_type_badge_class($type_slug) {
    $badge_classes = [
        'blogs' => 'blog',
        'videos' => 'video',
        'webinars' => 'webinar'
    ];
    
    return isset($badge_classes[$type_slug]) ? $badge_classes[$type_slug] : 'blog';
}

/**
 * Get resource type display name
 * 
 * @param string $type_slug
 * @return string
 */
function custom_get_resource_type_name($type_slug) {
    $type_names = [
        'blogs' => 'Blog',
        'videos' => 'Video',
        'webinars' => 'Webinar'
    ];
    
    return isset($type_names[$type_slug]) ? $type_names[$type_slug] : 'Blog';
}

/**
 * Get resource reading time or duration
 * 
 * @param int $post_id
 * @return string
 */
function custom_get_resource_duration($post_id) {
    $post_type = get_post_type($post_id);
    $resource_type = wp_get_post_terms($post_id, 'resource_type', ['fields' => 'slugs']);
    
    if (in_array('videos', $resource_type) || in_array('webinars', $resource_type)) {
        // For videos/webinars, get duration from meta
        $duration = get_post_meta($post_id, 'resource_duration', true);
        return $duration ? $duration : '4 min';
    } else {
        // For blogs, calculate reading time
        $content = get_post_field('post_content', $post_id);
        $reading_time = custom_estimate_reading_time($content);
        return $reading_time . ' min read';
    }
}

/**
 * Get resource video URL for videos/webinars
 * 
 * @param int $post_id
 * @return string
 */
function custom_get_resource_video_url($post_id) {
    $video_url = get_post_meta($post_id, 'resource_video_url', true);
    return $video_url ? $video_url : '#';
}

/**
 * Check if resource has video overlay
 * 
 * @param int $post_id
 * @return bool
 */
function custom_resource_has_video($post_id) {
    $resource_type = wp_get_post_terms($post_id, 'resource_type', ['fields' => 'slugs']);
    return in_array('videos', $resource_type) || in_array('webinars', $resource_type);
}

/**
 * Add Featured Resource Meta Box
 */
function custom_add_featured_resource_meta_box() {
    add_meta_box(
        'featured_resource_meta_box',
        'Featured Resource',
        'custom_featured_resource_meta_box_callback',
        ['post', 'resource'],
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'custom_add_featured_resource_meta_box');

/**
 * Featured Resource Meta Box Callback
 */
function custom_featured_resource_meta_box_callback($post) {
    wp_nonce_field('custom_featured_resource_nonce', 'custom_featured_resource_nonce');
    $featured = get_post_meta($post->ID, 'featured_resource', true);
    ?>
    <label for="featured_resource">
        <input type="checkbox" id="featured_resource" name="featured_resource" value="1" <?php checked($featured, '1'); ?>>
        Mark as Featured Resource
    </label>
    <p class="description">Check this box to display this resource in the featured resources slider.</p>
    <?php
}

/**
 * Save Featured Resource Meta Box Data
 */
function custom_save_featured_resource_meta_box($post_id) {
    if (!isset($_POST['custom_featured_resource_nonce']) || !wp_verify_nonce($_POST['custom_featured_resource_nonce'], 'custom_featured_resource_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $featured = isset($_POST['featured_resource']) ? '1' : '0';
    update_post_meta($post_id, 'featured_resource', $featured);
}
add_action('save_post', 'custom_save_featured_resource_meta_box');

/**
 * Add Video URL Meta Box
 */
function custom_add_video_url_meta_box() {
    add_meta_box(
        'video_url_meta_box',
        'Video URL',
        'custom_video_url_meta_box_callback',
        ['post', 'resource'],
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'custom_add_video_url_meta_box');

/**
 * Video URL Meta Box Callback
 */
function custom_video_url_meta_box_callback($post) {
    wp_nonce_field('custom_video_url_nonce', 'custom_video_url_nonce');
    $video_url = get_post_meta($post->ID, 'resource_video_url', true);
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="resource_video_url">Video URL</label>
            </th>
            <td>
                <input type="url" id="resource_video_url" name="resource_video_url" value="<?php echo esc_attr($video_url); ?>" class="regular-text" placeholder="https://example.com/video.mp4">
                <p class="description">Enter the video URL for this resource. This will be used for video play functionality.</p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save Video URL Meta Box Data
 */
function custom_save_video_url_meta_box($post_id) {
    if (!isset($_POST['custom_video_url_nonce']) || !wp_verify_nonce($_POST['custom_video_url_nonce'], 'custom_video_url_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $video_url = sanitize_url($_POST['resource_video_url']);
    update_post_meta($post_id, 'resource_video_url', $video_url);
}
add_action('save_post', 'custom_save_video_url_meta_box');

/**
 * AJAX handler for filtering resources
 */
function custom_filter_resources_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'custom_filter_resources_nonce')) {
        wp_die('Security check failed');
    }
    
    $type = sanitize_text_field($_POST['type']);
    $search = sanitize_text_field($_POST['search']);
    $page = intval($_POST['page']);
    
    // Set up query args
    $args = [
        'post_type' => ['post', 'resource'],
        'posts_per_page' => 12,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish'
    ];
    
    // Filter by resource type
    if ($type !== 'all') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'resource_type',
                'field' => 'slug',
                'terms' => $type
            ]
        ];
    }
    
    // Search functionality
    if (!empty($search)) {
        $args['s'] = $search;
    }
    
    $query = new WP_Query($args);
    
    $response = [
        'success' => true,
        'data' => [],
        'has_more' => $query->max_num_pages > $page,
        'total_pages' => $query->max_num_pages,
        'current_page' => $page
    ];
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $resource_type = wp_get_post_terms($post_id, 'resource_type', ['fields' => 'slugs']);
            $type_slug = !empty($resource_type) ? $resource_type[0] : 'blogs';
            
            $response['data'][] = [
                'id' => $post_id,
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'permalink' => get_permalink(),
                'featured_image' => get_the_post_thumbnail_url($post_id, 'medium'),
                'type' => $type_slug,
                'type_name' => custom_get_resource_type_name($type_slug),
                'date' => get_the_date('j M, Y'),
                'duration' => custom_get_resource_duration($post_id),
                'has_video' => custom_resource_has_video($post_id),
                'video_url' => custom_get_resource_video_url($post_id)
            ];
        }
    }
    
    wp_reset_postdata();
    wp_send_json($response);
}
add_action('wp_ajax_filter_resources', 'custom_filter_resources_ajax');
add_action('wp_ajax_nopriv_filter_resources', 'custom_filter_resources_ajax');

/**
 * Get news listing page URL with multilingual support
 * 
 * @return string URL to news listing page
 */
function custom_get_news_listing_url() {
    // Try to find news listing page by slug
    $news_page = get_page_by_path('news-listing');
    
    if (!$news_page) {
        // Try alternative slug variations
        $news_page = get_page_by_path('news');
    }
    
    if (!$news_page) {
        // Try to find by template name
        $pages = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-news-listing.php'
        ]);
        
        if (!empty($pages)) {
            $news_page = $pages[0];
        }
    }
    
    if ($news_page) {
        return get_permalink($news_page);
    }
    
    // Fallback to home URL
    return home_url('/');
}

/**
 * Social Media Sharing Shortcode
 * 
 * Usage: [custom_social_share]
 * Usage: [custom_social_share title="Custom Title" url="https://example.com" description="Custom description"]
 * Usage: [custom_social_share platforms="linkedin,twitter,facebook" style="horizontal" show_title="true"]
 * 
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function custom_social_share_shortcode($atts) {
    // Default attributes
    $atts = shortcode_atts([
        'title' => '',
        'url' => '',
        'description' => '',
        'platforms' => 'linkedin,twitter,facebook',
        'style' => 'horizontal', // horizontal, vertical, minimal
        'show_title' => 'true',
        'title_text' => '',
        'class' => '',
        'size' => 'medium', // small, medium, large
        'color' => 'default', // default, colored, monochrome
        'target' => '_blank',
        'rel' => 'noopener noreferrer'
    ], $atts);
    
    // Get current post data if not provided
    if (empty($atts['title'])) {
        $atts['title'] = get_the_title();
    }
    
    if (empty($atts['url'])) {
        $atts['url'] = get_permalink();
    }
    
    if (empty($atts['description'])) {
        $atts['description'] = get_the_excerpt() ?: wp_trim_words(get_the_content(), 20, '...');
    }
    
    if (empty($atts['title_text'])) {
        $atts['title_text'] = __('Share', 'custom-theme');
    }
    
    // Sanitize platforms
    $platforms = array_map('trim', explode(',', $atts['platforms']));
    $allowed_platforms = ['linkedin', 'twitter', 'facebook', 'whatsapp', 'telegram', 'email'];
    $platforms = array_intersect($platforms, $allowed_platforms);
    
    if (empty($platforms)) {
        $platforms = ['linkedin', 'twitter', 'facebook'];
    }
    
    // Generate sharing URLs
    $share_urls = custom_generate_share_urls($atts['url'], $atts['title'], $atts['description']);
    
    // Generate CSS classes using existing theme classes
    $css_classes = ['share_widget_content'];
    
    // Add style-specific classes
    if ($atts['style'] === 'vertical') {
        $css_classes[] = 'vertical-share';
    } elseif ($atts['style'] === 'minimal') {
        $css_classes[] = 'minimal-share';
    }
    
    // Add size-specific classes
    if ($atts['size'] === 'small') {
        $css_classes[] = 'small-share';
    } elseif ($atts['size'] === 'large') {
        $css_classes[] = 'large-share';
    }
    
    // Add color-specific classes
    if ($atts['color'] === 'colored') {
        $css_classes[] = 'colored-share';
    } elseif ($atts['color'] === 'monochrome') {
        $css_classes[] = 'monochrome-share';
    }
    
    if (!empty($atts['class'])) {
        $css_classes[] = sanitize_html_class($atts['class']);
    }
    
    // Start output
    ob_start();
    ?>
    <div class="<?php echo esc_attr(implode(' ', $css_classes)); ?>">
        <?php if ($atts['show_title'] === 'true' && !empty($atts['title_text'])): ?>
            <h4 class="share_title"><?php echo esc_html($atts['title_text']); ?></h4>
        <?php endif; ?>
        
        <div class="share_icons">
            <?php foreach ($platforms as $platform): ?>
                <?php if (isset($share_urls[$platform])): ?>
                    <a href="<?php echo esc_url($share_urls[$platform]); ?>" 
                       class="share_icon <?php echo esc_attr($platform); ?>" 
                       target="<?php echo esc_attr($atts['target']); ?>" 
                       rel="<?php echo esc_attr($atts['rel']); ?>"
                       aria-label="<?php printf(__('Share on %s', 'custom-theme'), ucfirst($platform)); ?>">
                        <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/icons/<?php echo esc_attr($platform); ?>-icon.svg" 
                             alt="<?php echo esc_attr(ucfirst($platform)); ?>">
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    
    return ob_get_clean();
}
add_shortcode('custom_social_share', 'custom_social_share_shortcode');

/**
 * Generate sharing URLs for different platforms
 * 
 * @param string $url URL to share
 * @param string $title Title to share
 * @param string $description Description to share
 * @return array Array of platform URLs
 */
function custom_generate_share_urls($url, $title, $description) {
    $encoded_url = urlencode($url);
    $encoded_title = urlencode($title);
    $encoded_description = urlencode($description);
    
    return [
        'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$encoded_url}",
        'twitter' => "https://twitter.com/intent/tweet?url={$encoded_url}&text={$encoded_title}",
        'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$encoded_url}",
        'whatsapp' => "https://wa.me/?text={$encoded_title}%20{$encoded_url}",
        'telegram' => "https://t.me/share/url?url={$encoded_url}&text={$encoded_title}",
        'email' => "mailto:?subject={$encoded_title}&body={$encoded_description}%20{$encoded_url}"
    ];
}
