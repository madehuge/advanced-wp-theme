<?php
/**
 * Template Name: News Listing
 * 
 * Custom page template for news listing with featured news slider and news grid
 */

get_header();

// Get filter parameters from URL query string
$selected_year = isset($_GET['year']) ? sanitize_text_field($_GET['year']) : '';
$selected_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';

// Get featured news
$featured_news_query = custom_get_featured_news(3);

// Get all news articles
$news_query = custom_get_news_articles(6, $selected_year, $selected_category);

// Get available years and categories for filters
$available_years = custom_get_news_years();
$available_categories = custom_get_news_categories();
?>

<!-- Breadcrumb Section -->
<?php
// Custom breadcrumb for news listing page
$breadcrumb_args = [
    'title' => 'News',
    'description' => 'Stay informed and inspired about the latest trends and technology updates with our collection of insights and perspectives.',
    'breadcrumbs' => [
        ['title' => 'Home', 'url' => home_url('/')],
        ['title' => 'News & Events', 'url' => home_url('/news/')],
        ['title' => 'News', 'url' => '', 'active' => true]
    ]
];
get_template_part('template-parts/components/breadcrumb', null, $breadcrumb_args);
?>

<!-- Featured News Section -->
<section class="featured_news_sec bg_light comn_sec_py position-relative">
    <div class="bg_pattern_holder position-absolute">
        <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/news/feature-news-sec-pattern.svg" alt="" width="" height="" data-speed="0.9" data-lag="0">
    </div>

    <div class="container px-4 px-sm-3 z-2">
        <div class="featured_news_wrapper">
            <div class="featured_news_sliders_container">
                <!-- Image Slider -->
                <div class="splide featured_news_image_slider">
                    <div class="splide__track">
                        <div class="splide__list">
                            <?php
                            
                            if ($featured_news_query->have_posts()): ?>
                                <?php while ($featured_news_query->have_posts()): $featured_news_query->the_post(); ?>
                                    <div class="splide__slide">
                                        <div class="featured_news_image">
                                            <div class="ratio ratio-4x3">
                                                <?php if (has_post_thumbnail()): ?>
                                                    <?php the_post_thumbnail('large', ['class' => 'object-fit-cover', 'alt' => get_the_title()]); ?>
                                                <?php else: ?>
                                                    <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/news/feature_news_img1.jpg" alt="<?php the_title(); ?>" class="object-fit-cover">
                                                <?php endif; ?>
                                            </div>
                                            <div class="featured_news_badge">
                                                <span>Featured News</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                                <?php wp_reset_postdata(); ?>
                            <?php else: ?>
                                <!-- Fallback slides if no featured news -->
                                <?php for ($i = 1; $i <= 3; $i++): ?>
                                    <div class="splide__slide">
                                        <div class="featured_news_image">
                                            <div class="ratio ratio-4x3">
                                                <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/news/feature_news_img1.jpg" alt="Featured News <?php echo $i; ?>" class="object-fit-cover">
                                            </div>
                                            <div class="featured_news_badge">
                                                <span>Featured News</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="featured_news_content_slider_holder">
                    <!-- Content Slider -->
                    <div class="splide featured_news_content_slider">
                        <div class="splide__track">
                            <div class="splide__list">
                                <?php if ($featured_news_query->have_posts()): ?>
                                    <?php while ($featured_news_query->have_posts()): $featured_news_query->the_post(); ?>
                                        <div class="splide__slide">
                                            <div class="featured_news_content">
                                                <h2 class="featured_news_title multi-3-line-ellipsis"><?php the_title(); ?></h2>
                                                <div class="featured_news_desc">
                                                    <p><?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?></p>
                                                </div>
                                                <div class="featured_news_meta">
                                                    <div class="news_date">
                                                        <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/icons/calendar.svg" alt="calendar" width="18" height="20">
                                                        <span><?php echo get_the_date('F j, Y'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                    <?php wp_reset_postdata(); ?>
                                <?php else: ?>
                                    <!-- Fallback content if no featured news -->
                                    <?php for ($i = 1; $i <= 3; $i++): ?>
                                        <div class="splide__slide">
                                            <div class="featured_news_content">
                                                <h2 class="featured_news_title multi-3-line-ellipsis">Sample Featured News Title <?php echo $i; ?></h2>
                                                <div class="featured_news_desc">
                                                    <p>This is a sample description for featured news article. It provides a brief overview of the content and encourages readers to learn more.</p>
                                                </div>
                                                <div class="featured_news_meta">
                                                    <div class="news_date">
                                                        <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/icons/calendar.svg" alt="calendar" width="18" height="20">
                                                        <span><?php echo date('F j, Y', strtotime('-' . $i . ' days')); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="slider_nav_holder d-flex align-items-center">
                        <!-- Custom Navigation -->
                        <div class="featured_news_navigation">
                            <button class="nav_btn prev_btn" aria-label="Previous">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 12L6 8L10 4" stroke="#1D3458" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <button class="nav_btn next_btn" aria-label="Next">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 4L10 8L6 12" stroke="#1D3458" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>

                        <!-- Custom Pagination -->
                        <div class="featured_news_pagination">
                            <div class="pagination_dots">
                                <button class="pagination_dot active" data-slide="0" aria-label="Go to slide 1"></button>
                                <button class="pagination_dot" data-slide="1" aria-label="Go to slide 2"></button>
                                <button class="pagination_dot" data-slide="2" aria-label="Go to slide 3"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- All News & Updates Section -->
<section class="all_news_sec comn_sec_py">
    <div class="bg_pattern_holder position-absolute">
        <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/news/all_news_sec_bg_pattern.svg" alt="" width="" height="" data-speed="0.9" data-lag="0">
    </div>

    <div class="container px-4 px-sm-3 z-2 position-relative">
        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center all_news_sec_heading">
            <div class="sec_heading_wrap mb-4 mb-md-0">
                <h3 class="sec_heading_text_2 words_slide_from_right split_text">All News & Updates</h3>
            </div>

            <div class="sort_by_year_wrapper sort_by_filter">
                <div class="d-flex gap-3">
                    <select name="year" id="sortByYear" class="sort_select">
                        <option value="">Sort by Year</option>
                        <?php foreach ($available_years as $year): ?>
                            <option value="<?php echo esc_attr($year); ?>" <?php selected($selected_year, $year); ?>>
                                <?php echo esc_html($year); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <?php if (!empty($available_categories)): ?>
                        <select name="category" id="sortByCategory" class="sort_select">
                            <option value="">All Categories</option>
                            <?php foreach ($available_categories as $category): ?>
                                <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($selected_category, $category->slug); ?>>
                                    <?php echo esc_html($category->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="news_grid">
            <div class="row gx-4 gy-5" id="news-grid-container">
                <?php if ($news_query->have_posts()): ?>
                    <?php while ($news_query->have_posts()): $news_query->the_post(); ?>
                        <?php
                        // Get the first category for badge
                        $categories = get_the_terms(get_the_ID(), 'news_cat');
                        $category_slug = !empty($categories) ? $categories[0]->slug : 'news';
                        $badge_class = custom_get_news_badge_class($category_slug);
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
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <?php if (!empty($selected_year) || !empty($selected_category)): ?>
                                <div class="no-results-message">
                                   
                                    <h4 class="mb-3">No news articles found</h4>
                                    <p class="mb-4">We couldn't find any news articles matching your current filters.</p>
                                    <div class="d-flex flex-wrap justify-content-center gap-3">
                                        <?php if (!empty($selected_year)): ?>
                                            <span class="badge bg-secondary">Year: <?php echo esc_html($selected_year); ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($selected_category)): ?>
                                            <?php 
                                            $category_obj = get_term_by('slug', $selected_category, 'news_cat');
                                            $category_name = $category_obj ? $category_obj->name : $selected_category;
                                            ?>
                                            <span class="badge bg-secondary">Category: <?php echo esc_html($category_name); ?></span>
                                        <?php endif; ?>
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
                <?php endif; ?>
            </div>
        </div>

        <!-- Loading indicator -->
        <div class="text-center mt-5" id="news-loading" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-5" id="load-more-container">
            <?php if ($news_query->max_num_pages > 1): ?>
                <button class="btn btn_deep_blue load-more-news" type="button" 
                        data-page="2" 
                        data-max-pages="<?php echo $news_query->max_num_pages; ?>"
                        data-year="<?php echo esc_attr($selected_year); ?>"
                        data-category="<?php echo esc_attr($selected_category); ?>">
                    <span class="bnt_text_wrap"><span>Load More</span></span>
                    <span class="arrow"></span>
                </button>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php 
get_template_part('template-parts/components/cta');
get_footer(); ?>
