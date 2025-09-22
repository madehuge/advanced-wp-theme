<?php
/**
 * Global Breadcrumb Component
 * 
 * @param array $args {
 *     @type string $title       Page title (optional - will auto-detect if not provided)
 *     @type string $description Page description (optional - will use ACF or post excerpt)
 *     @type array  $breadcrumbs Array of breadcrumb items (optional - will auto-generate if not provided)
 *     @type string $bg_image    Background image URL (optional - will use ACF field or default)
 *     @type string $class       Additional CSS classes
 *     @type string $post_type   Post type for auto-generating breadcrumbs
 * }
 */

// Get ACF background image if available
$acf_bg_image = '';
if (function_exists('get_field')) {
    $acf_bg_image = get_field('bg_image');
    if (is_array($acf_bg_image) && isset($acf_bg_image['url'])) {
        $acf_bg_image = $acf_bg_image['url'];
    } elseif (is_string($acf_bg_image)) {
        $acf_bg_image = $acf_bg_image;
    }
}

// Get ACF description if available
$acf_description = '';
$show_hero_description = true; // Default to true
if (function_exists('get_field')) {
    $acf_description = get_field('hero_description');
    $show_hero_description = get_field('show_hero_description');
    // If field doesn't exist or is null, default to true
    if ($show_hero_description === null) {
        $show_hero_description = true;
    }
}

// Get ACF field for showing date and reading time
$show_date_reading_time = false; // Default to false
if (function_exists('get_field')) {
    $show_date_reading_time = get_field('show_date_&_reading_time_on_banner');
    // If field doesn't exist or is null, default to false
    if ($show_date_reading_time === null) {
        $show_date_reading_time = false;
    }
}

// Get ACF field for full width breadcrumb container
$show_breadcrumb_container_in_full_width = false; // Default to false
if (function_exists('get_field')) {
    $show_breadcrumb_container_in_full_width = get_field('show_breadcrumb_container_in_full_width');
    // If field doesn't exist or is null, default to false
    if ($show_breadcrumb_container_in_full_width === null) {
        $show_breadcrumb_container_in_full_width = false;
    }
}

// Auto-detect page title
$auto_title = '';
if (is_home() || is_front_page()) {
    $auto_title = get_bloginfo('name');
} elseif (is_single()) {
    $auto_title = get_the_title();
} elseif (is_page()) {
    $auto_title = get_the_title();
} elseif (is_archive()) {
    $auto_title = get_the_archive_title();
} elseif (is_search()) {
    $auto_title = 'Search Results for: ' . get_search_query();
} elseif (is_404()) {
    $auto_title = 'Page Not Found';
} else {
    $auto_title = get_the_title();
}

// Auto-detect description
$auto_description = '';
if (empty($acf_description)) {
    if (is_single() || is_page()) {
        $auto_description = get_the_excerpt();
    } elseif (is_archive()) {
        $auto_description = get_the_archive_description();
    }
}

$defaults = [
    'title' => $auto_title,
    'description' => $acf_description ?: $auto_description,
    'breadcrumbs' => [],
    'bg_image' => $acf_bg_image ?: CUSTOM_THEME_URI . '/assets/images/news/news-banner-img.jpg',
    'class' => '',
    'post_type' => get_post_type(),
    'show_hero_description' => $show_hero_description,
    'show_date_reading_time' => $show_date_reading_time,
    'show_breadcrumb_container_in_full_width' => $show_breadcrumb_container_in_full_width
];

$args = wp_parse_args($args, $defaults);

// Auto-generate breadcrumbs if not provided
if (empty($args['breadcrumbs'])) {
    $args['breadcrumbs'] = custom_generate_breadcrumbs($args['post_type']);
}
?>

<section class="comn_hero_sec bg_primary position-relative d-flex <?php echo esc_attr($args['class']); ?>">
    <div class="news_hero_bg_holder">
        <img src="<?php echo esc_url($args['bg_image']); ?>" alt="<?php echo esc_attr($args['title']); ?>" class="object-fit-cover w-100 h-100">
    </div>

    <div class="comn_hero_main">

        <div class="container w-100 position-relative px-4 px-sm-3 h-100">
          <div class="divider_line_holder position-absolute white_line z-2">
            <div class="divider_line"></div>
            <div class="divider_line"></div>
            <div class="divider_line"></div>
            <div class="divider_line"></div>
          </div>

          <div class="container_inr position-relative z-2 h-100">


            <div class="comn_hero_content text_color_white d-flex justify-content-between flex-column h-100">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <?php foreach ($args['breadcrumbs'] as $index => $breadcrumb): ?>
                    <?php if (isset($breadcrumb['active']) && $breadcrumb['active']): ?>
                      <li class="breadcrumb-item active" aria-current="page">
                        <?php echo esc_html($breadcrumb['title']); ?>
                      </li>
                    <?php else: ?>
                      <li class="breadcrumb-item">
                        <a href="<?php echo esc_url($breadcrumb['url']); ?>" class="text-decoration-none">
                          <?php echo esc_html($breadcrumb['title']); ?>
                        </a>
                      </li>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </ol>
              </nav>

              <div class="banner_dsc_content <?php echo $args['show_breadcrumb_container_in_full_width'] ? 'mxw-lg' : 'mxw-md'; ?>">
                <h1 class="comn_hero_title"><?php echo esc_html($args['title']); ?></h1>
                <?php if ($args['show_hero_description'] && !empty($args['description'])): ?>
                  <div class="comn_dsc_content text_color_white">
                    <p><?php echo esc_html($args['description']); ?></p>
                  </div>
                <?php endif; ?>
                <?php if ($args['show_date_reading_time'] && (is_single() || is_page())): ?>
                  <div class="comn_dsc_content text_color_white">
                    <p><?php echo get_the_date('F j, Y'); ?> | <?php echo custom_estimate_reading_time(get_the_content()); ?> MINUTES READ</p>
                  </div>
                <?php endif; ?>
                
              </div>


            </div>


          </div>
        </div>

      </div>

    <div class="comn_hero_banner_height"></div>
</section>
