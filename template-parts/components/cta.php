<?php
/**
 * Global CTA Component
 * 
 * Usage Examples:
 * 
 * 1. Basic usage (uses ACF fields from page ID 2 or defaults):
 *    get_template_part('template-parts/components/cta');
 * 
 * 2. With custom parameters:
 *    get_template_part('template-parts/components/cta', null, [
 *        'heading' => 'Custom CTA Heading',
 *        'sub_heading' => 'Custom sub heading text',
 *        'details' => 'Custom details text',
 *        'form_shortcode' => '[contact-form-7 id="123" title="Custom Form"]',
 *        'show_video' => false,
 *        'section_class' => 'custom-cta-class'
 *    ]);
 * 
 * 3. Minimal CTA (no video, no logos):
 *    get_template_part('template-parts/components/cta', null, [
 *        'heading' => 'Get Started Today',
 *        'sub_heading' => 'Contact us for more information',
 *        'show_video' => false,
 *        'show_logos' => false
 *    ]);
 * 
 * @param array $args {
 *     @type string $heading        CTA heading (optional - will use ACF field from page ID 2 or default)
 *     @type string $sub_heading    CTA sub heading (optional - will use ACF field from page ID 2 or default)
 *     @type string $details        CTA details text (optional - will use ACF field from page ID 2 or default)
 *     @type string $video_url      Background video URL (optional - will use ACF field from page ID 2 or default)
 *     @type array  $clients_logos  Array of client logos (optional - will use ACF field from page ID 2 or default)
 *     @type string $form_shortcode Contact form shortcode (optional - will use ACF field from page ID 2 or default)
 *     @type string $class          Additional CSS classes for content wrapper
 *     @type string $section_class  Additional CSS classes for section
 *     @type bool   $show_video     Whether to show background video (default: true)
 *     @type bool   $show_logos     Whether to show client logos (default: true)
 *     @type bool   $show_form      Whether to show contact form (default: true)
 * }
 */

// Get ACF fields from page ID 2 (home page) for all environments
$acf_heading = '';
$acf_sub_heading = '';
$acf_details = '';
$acf_video = '';
$acf_logos = '';
$acf_form = '';

if (function_exists('get_field')) {
    $acf_heading = get_field('cta_heading', 2);
    $acf_sub_heading = get_field('cta_sub_heading', 2);
    $acf_details = get_field('cta_details', 2);
    $acf_video = get_field('final_cta_video', 2);
    $acf_logos = get_field('clients_logo', 2);
    $acf_form = get_field('cta_form_shortcode', 2);
}

// Default values
$defaults = [
    'heading' => $acf_heading ?: esc_html__('Final CTA', 'custom-theme'),
    'sub_heading' => $acf_sub_heading ?: esc_html__('Join the 10,000+ Labs that are Advancing Science with CUSTOM!', 'custom-theme'),
    'details' => $acf_details ?: esc_html__('Discover high-quality labware engineered for precision, value, and performance.', 'custom-theme'),
    'video_url' => '',
    'clients_logos' => $acf_logos ?: [],
    'form_shortcode' => $acf_form ?: '[contact-form-7 id="866fa05" title="Contact Form Home"]',
    'class' => '',
    'section_class' => '',
    'show_video' => true,
    'show_logos' => true,
    'show_form' => true
];

$args = wp_parse_args($args, $defaults);

// Process video URL
$video_url = $args['video_url'];
if (empty($video_url) && $acf_video && !empty($acf_video['url'])) {
    $video_url = $acf_video['url'];
}
if (empty($video_url)) {
    $video_url = CUSTOM_THEME_URI . '/assets/videos/default_cta_video.mp4';
}

// Process client logos
$clients_logos = $args['clients_logos'];
if (!is_array($clients_logos)) {
    $clients_logos = [];
}
?>

<section class="final_cta_sec bg_primary position-relative <?php echo esc_attr($args['section_class']); ?>">

    <?php if ($args['show_video']): ?>
    <div class="final_cta_bgvideo_holder">
        <video class="object-fit-cover" preload="metadata" loop muted playsinline autoplay>
            <source src="<?php echo esc_url($video_url); ?>" loading="lazy" type="video/mp4" />
        </video>
    </div>
    <?php endif; ?>

    <div class="container w-100 position-relative comn_sec_py px-4 px-sm-3">

        <div class="divider_line_holder position-absolute white_line">
            <div class="divider_line"></div>
            <div class="divider_line"></div>
            <div class="divider_line"></div>
            <div class="divider_line"></div>
        </div>

        <div class="container_inr position-relative z-2">

            <div class="row gx-xl-5">
                <div class="col-lg-5 col-xl-6">
                    <div class="final_cta_left_col_content <?php echo esc_attr($args['class']); ?>">

                        <div class="sec_heading_wrap text_color_white">
                            <h2 class="sec_heading_tag_text mb_25 left_line_blue words_slide_from_right split_text">
                                <?php echo esc_html($args['heading']); ?>
                            </h2>
                            <h3 class="sec_heading_text words_slide_from_right split_text">
                                <?php echo esc_html($args['sub_heading']); ?>
                            </h3>
                            <div class="comn_dsc_content text_color_white">
                                <p><?php echo esc_html($args['details']); ?></p>
                            </div>
                        </div>

                        <?php if ($args['show_logos'] && !empty($clients_logos)): ?>
                        <div class="finalcta_logo_slider_holder">
                            <div class="splide logo_slider">
                                <div class="splide__track">
                                    <div class="splide__list">
                                        <?php foreach ($clients_logos as $logo) :
                                            $logo_img = isset($logo['add_logo']) && !empty($logo['add_logo']['url'])
                                                ? $logo['add_logo']['url']
                                                : CUSTOM_THEME_URI . '/assets/images/home/default_logo.svg';
                                        ?>
                                            <div class="splide__slide">
                                                <div class="img_holder">
                                                    <img loading="lazy" class="object-fit-contain" src="<?php echo esc_url($logo_img); ?>" alt="<?php echo esc_attr($args['heading']); ?>" />
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
                
                <?php if ($args['show_form']): ?>
                <div class="col-lg-7 col-xl-6">
                    <?php echo do_shortcode($args['form_shortcode']); ?>
                </div>
                <?php endif; ?>

            </div>

        </div>

    </div>

</section>
