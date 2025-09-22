<?php

/**
 * Front Page Template
 *
 * Template for static homepage in custom-theme theme.
 */

get_header();  // Load header.php
?>

<section class="hero_banner_sec d-flex">

  <div class="hero_banner_slider_all position-relative w-100">

    <?php
    $cache_key = 'custom_hero_slides';
    $cache_time = 12 * HOUR_IN_SECONDS;  // Cache for 12 hours

    $hero_slides_markup = get_transient($cache_key);

    if (false === $hero_slides_markup) {

      $hero_slides = new WP_Query([
        'post_type'      => 'hero_slide',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
      ]);

      $image_slides = [];
      $content_slides = [];

      if ($hero_slides->have_posts()):
        while ($hero_slides->have_posts()): $hero_slides->the_post();
          $bg_image      = get_field('background_img');
          $bg_image_url  = esc_url($bg_image ?: '');

          $main_image    = get_field('main_image');
          $main_image_url = ($main_image && isset($main_image['url'])) ? esc_url($main_image['url']) : '';
          $main_image_alt = ($main_image && isset($main_image['alt'])) ? esc_attr($main_image['alt']) : '';

          $heading       = get_the_title();
          $description   = apply_filters('the_content', get_the_content());

          $button_url    = get_field('button_url');
          $button_text   = get_field('button_text');

          $image_slides[] = '
                        <div class="splide__slide">
                            <div class="slide">
                                <div class="bg" style="background-image:url(' . $bg_image_url . ')"></div>
                                <div class="container px-4 px-sm-3">
                                    <div class="main_image_col">
                                        <div class="main_image_holder animf_right_image">
                                            <div class="ratio">
                                                <img src="' . $main_image_url . '" alt="' . $main_image_alt . '" loading="lazy" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';

          $content_slides[] = '
                        <div class="splide__slide">
                            <div class="dsc_content_slide">
                                <div class="hero_dsc_content_col">
                                    <div class="hero_dsc_content">
                                        <h2 class="main_heading_text animf_right">' . esc_html($heading) . '</h2>
                                        <div class="comn_dsc_content animf_right">' . wp_kses_post($description) . '</div>
                                        ' . ($button_url && $button_text ? '<div class="hero-actions d-flex align-items-center animf_right"><a href="' . esc_url($button_url) . '" class="btn btn_deep_blue" aria-label="' . esc_attr($button_text) . '"><span class="bnt_text_wrap"><span>' . esc_html($button_text) . '</span></span><span class="arrow"></span></a></div>' : '') . '
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';

        endwhile;
        wp_reset_postdata();
      endif;

      // Generate full HTML markup
      $hero_slides_markup = [
        'image'   => implode("\n", $image_slides),
        'content' => implode("\n", $content_slides),
      ];

      set_transient($cache_key, $hero_slides_markup, $cache_time);
    }


    if (isset($hero_slides_markup)):
    ?>
      <div class="splide hero_banner_slider">
        <div class="splide__track">
          <div class="splide__list">
            <?php echo $hero_slides_markup['image']; ?>
          </div>
        </div>
      </div>

      <div class="hero_banner_dsc_content_slider_holder">
        <div class="container px-4 px-sm-3">
          <div class="splide hero_banner_dsc_content_slider">
            <div class="splide__track">
              <div class="splide__list">
                <?php echo $hero_slides_markup['content']; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
    endif;

    ?>



  </div>

  <div class="hero_banner_height"></div>

</section>


<section class="home_about_sec hero_banner_after_sec">

  <?php
  $cache_key    = 'about_section_content_cache_' . CUSTOM_LANGUAGE_CODE;

  // Attempt to retrieve cached content
  $cached_content = get_transient($cache_key);

  if (false === $cached_content) {

    ob_start(); // Start output buffering

  ?>

    <div class="container w-100 position-relative comn_sec_py px-4 px-sm-3">

      <div class="divider_line_holder position-absolute">
        <div class="divider_line"></div>
        <div class="divider_line"></div>
        <div class="divider_line"></div>
        <div class="divider_line"></div>
      </div>

      <div class="container_inr">

        <div class="sec_heading_wrap mb_50">
          <h2 class="sec_heading_tag_text mb_25 words_slide_from_right split_text">
            <?php echo esc_html(get_field('about_section_heading_tag')); ?>
          </h2>
          <h3 class="sec_heading_text words_slide_from_right split_text">
            <?php echo esc_html(get_field('about_section_main_heading')); ?>
          </h3>
        </div>

        <div class="row gy-5 gy-lg-0">

          <div class="col-lg-9">
            <div class="row gy-4 gy-md-0">

              <div class="col-md-6">
                <div class="comn_dsc_content_holder">
                  <div class="comn_dsc_content fade_word_ani split_text">
                    <?php echo wp_kses_post(get_field('about_us_section_details')); ?>
                  </div>

                  <?php
                  $btn_text = get_field('about_the_company_button_text');
                  $btn_url  = get_field('about_section_button_url');

                  if ($btn_text && $btn_url) : ?>
                    <div class="btn_holder mt_35">
                      <a href="<?php echo esc_url($btn_url); ?>" class="btn btn_deep_blue" aria-label="<?php echo esc_attr($btn_text); ?>">
                        <span class="bnt_text_wrap">
                          <span><?php echo esc_html($btn_text); ?></span>
                        </span>
                        <span class="arrow"></span>
                      </a>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <div class="col-md-6">
                <div class="home_about_image_box_holder position-relative">

                  <div class="home_about_image_box position-relative">

                    <div class="ratio home_about_image_holder">
                      <?php
                      $main_img = get_field('about_section_main_image');
                      if ($main_img && isset($main_img['url'], $main_img['alt'])): ?>
                        <img src="<?php echo esc_url($main_img['url']); ?>" alt="<?php echo esc_attr($main_img['alt']); ?>" loading="lazy" />
                      <?php endif; ?>
                    </div>

                    <div class="text_content color-white" data-speed="0.9" data-lag="0">
                      <?php
                      $ce_img  = get_field('about_section_ce_image');
                      $ce_text = get_field('about_section_ce_text');

                      if ($ce_img && isset($ce_img['url'], $ce_img['alt']) && $ce_text): ?>
                        <img src="<?php echo esc_url($ce_img['url']); ?>" alt="<?php echo esc_attr($ce_img['alt']); ?>" width="57" height="40" />
                        <p><?php echo esc_html($ce_text); ?></p>
                      <?php endif; ?>
                    </div>

                  </div>

                  <?php
                  $overlay_img = get_field('about_section_overlay_image');
                  if ($overlay_img && isset($overlay_img['url'], $overlay_img['alt'])): ?>
                    <div class="home_about_overlay_image_holder">
                      <img src="<?php echo esc_url($overlay_img['url']); ?>" alt="<?php echo esc_attr($overlay_img['alt']); ?>" />
                    </div>
                  <?php endif; ?>

                </div>
              </div>

            </div>
          </div>

          <div class="col-lg-3">

            <div class="counter_content_holder d-flex flex-lg-column flex-wrap">
              <?php
              $counters = get_field('about_section_counters');

              if ($counters && is_array($counters)):
                $total = count($counters);
                $index = 0;

                foreach ($counters as $counter):
                  $counter_number = isset($counter['counter_number']) ? $counter['counter_number'] : '';
                  $counter_label  = isset($counter['counter_label']) ? $counter['counter_label'] : '';
                  $index++;
              ?>

                  <div class="counter-content-item">
                    <div class="counter-content">
                      <div class="counter-number d-flex align-items-center flex-shrink-0">

                        <span class="counter <?php echo ($index === $total) ? '' : 'counter_ani'; ?>"
                          <?php echo ($index === $total) ? '' : 'data-target="' . esc_attr($counter_number) . '"'; ?>>
                          <?php echo ($index === $total) ? esc_html($counter_number) : '0'; ?>
                        </span>

                        <?php if ($index !== $total): ?>
                          <span>+</span>
                        <?php else: ?>
                          <!-- <span>+</span> -->
                        <?php endif; ?>

                      </div>
                      <div class="counter-infoText">
                        <p class="m-0"><?php echo esc_html($counter_label); ?></p>
                      </div>
                    </div>
                  </div>

              <?php endforeach;
              endif;
              ?>
            </div>

          </div>

        </div>

      </div>

    </div>

  <?php

    // Store the generated HTML in transient (12 hours expiration)
    $cached_content = ob_get_clean();
    set_transient($cache_key, $cached_content, 12 * HOUR_IN_SECONDS);
  }

  // Output the cached block
  echo $cached_content;
  ?>

</section>


<?php

$section_bg_img      = get_field('core_values_section_bg_image');
$core_values_title   = sanitize_text_field(get_field('core_values_title'));
$core_values_details = sanitize_text_field(get_field('core_values_details'));

// Use default fallback values
$section_bg_img_url = isset($section_bg_img['url']) ? esc_url($section_bg_img['url']) : esc_url(CUSTOM_THEME_URI . '/assets/images/home/ocv_sec_tranparent_bg_img.png');
$core_values_title   = $core_values_title ?: __('Our Core Values', 'custom-theme');
$core_values_details = $core_values_details ?: __("Driving our Brand's Vision and Impact Through", 'custom-theme');


?>

<section class="ocv_sec" style="background-image: url('<?php echo $section_bg_img_url; ?>');">

  <div class="container w-100 position-relative comn_sec_py px-4 px-sm-3">

    <div class="divider_line_holder position-absolute white_line">
      <div class="divider_line"></div>
      <div class="divider_line"></div>
      <div class="divider_line"></div>
      <div class="divider_line"></div>
    </div>

    <div class="container_inr">

      <div class="sec_heading_wrap mb_50 fade-in text_color_white">
        <h2 class="sec_heading_tag_text mb_25 words_slide_from_right split_text">
          <?php echo esc_html($core_values_title); ?>
        </h2>
        <h3 class="sec_heading_text words_slide_from_right split_text">
          <?php echo esc_html($core_values_details); ?>
        </h3>
      </div>

      <div class="ocv_content_wrap">
        <?php if (have_rows('core_values_flexible_content')): ?>
          <div class="ocv_accordion_holder d-block d-xl-none">
            <div class="accordion ocv_accordion" id="ocv_accordion_id">

              <?php $index = 1;
              while (have_rows('core_values_flexible_content')): the_row();
                $bg_image   = get_sub_field('bg_image');
                $bg_image_url = isset($bg_image['url']) ? esc_url($bg_image['url']) : esc_url(CUSTOM_THEME_URI . '/assets/images/home/ec_tile_bg_img.jpg');
                $icon       = get_sub_field('icon');
                $title      = get_sub_field('title');
                $description = get_sub_field('description');
                $link_url   = get_sub_field('link_url');
              ?>
                <div class="accordion-item" style="background-image: url('<?php echo $bg_image_url; ?>');">
                  <h3 class="accordion-header" id="heading<?php echo esc_attr($index); ?>">
                    <button class="accordion-button <?php echo $index === 1 ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse"
                      data-bs-target="#collapse<?php echo esc_attr($index); ?>" aria-expanded="<?php echo $index === 1 ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo esc_attr($index); ?>">
                      <div class="accordion_btn_content d-flex align-items-center column-gap-3">
                        <div class="icon_holder flex-shrink-0">
                          <?php if ($icon && isset($icon['url'], $icon['alt'])): ?>
                            <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>" width="" height="">
                          <?php endif; ?>
                        </div>
                        <span class="heading_text fw-medium lh-base flex-fill"><?php echo esc_html($title); ?></span>
                      </div>
                    </button>
                  </h3>

                  <div id="collapse<?php echo esc_attr($index); ?>" class="accordion-collapse collapse <?php echo $index === 1 ? 'show' : ''; ?>" aria-labelledby="heading<?php echo esc_attr($index); ?>" data-bs-parent="#ocv_accordion_id">
                    <div class="accordion-body">
                      <div class="accordion-body-content">
                        <div class="dsc_text"><?php echo esc_html($description); ?></div>
                        <?php if ($link_url): ?>
                          <a class="link_arrow_box" href="<?php echo esc_url($link_url); ?>">
                            <img src="<?php echo esc_url(CUSTOM_THEME_URI . '/assets/images/home/right-arrow-icon.svg'); ?>" alt="">
                          </a>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>

              <?php $index++;
              endwhile; ?>

            </div>
          </div>

          <div class="ec_tiles d-none d-xl-flex" id="ec_tiles">
            <?php while (have_rows('core_values_flexible_content')): the_row();
              $icon       = get_sub_field('icon');
              $title      = get_sub_field('title');
              $description = get_sub_field('description');
              $link_url   = get_sub_field('link_url');
            ?>
              <div class="ec_tile" tabindex="0">
                <a class="total_link" href="<?php echo esc_url($link_url ?: '#'); ?>"></a>
                <div class="tile_bgimg_onhover">
                  <img src="<?php echo esc_url(CUSTOM_THEME_URI . '/assets/images/home/ec_tile_bg_img.jpg'); ?>" alt="">
                </div>

                <div class="ec_tile_contant">
                  <div class="icon_box">
                    <?php if ($icon && isset($icon['url'], $icon['alt'])): ?>
                      <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>">
                    <?php endif; ?>
                  </div>
                  <div class="tile_dsc_cotent">
                    <div class="title"><?php echo esc_html($title); ?></div>
                    <div class="subtitle"><?php echo esc_html($description); ?></div>
                  </div>
                  <span class="link_arrow_box">
                    <img src="<?php echo esc_url(CUSTOM_THEME_URI . '/assets/images/home/right-arrow-icon.svg'); ?>" alt="">
                  </span>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<section class="home_product_list_sec">

  <div class="container w-100 position-relative comn_sec_py px-4 px-sm-3">

    <div class="divider_line_holder position-absolute">
      <div class="divider_line"></div>
      <div class="divider_line"></div>
      <div class="divider_line"></div>
      <div class="divider_line"></div>
    </div>

    <div class="container_inr">

      <?php
      // Get ACF fields
      $product_title     = get_field('product_highlights_title');       // e.g., "Product Highlights"
      $product_sub_title = get_field('product_highlights_sub_title');   // e.g., "Explore our high-quality lab solutions"
      ?>

      <div class="sec_heading_wrap mb_50 mx-auto text-center">
        <?php if ($product_title): ?>
          <h2 class="sec_heading_tag_text mb_25 tag_center words_slide_from_right split_text">
            <?php echo esc_html($product_title); ?>
          </h2>
        <?php endif; ?>

        <?php if ($product_sub_title): ?>
          <h3 class="sec_heading_text words_slide_from_right split_text">
            <?php echo esc_html($product_sub_title); ?>
          </h3>
        <?php endif; ?>
      </div>


      <div
        class="product_list_card_item_mobile d-xl-none d-flex flex-column flex-md-row column-gap-4 row-gap-4">
        <div class="splide" id="home_product_slider">
          <div class="splide__track">
            <div class="splide__list">
              <!-- All product_list_card_item elements except the last one will go here -->
              <?php
              // Query latest 9 WooCommerce products
              $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 9,
                'post_status'    => 'publish',
              );
              $products_query = new WP_Query($args);

              if ($products_query->have_posts()) :
                while ($products_query->have_posts()) : $products_query->the_post();
                  $product_id      = get_the_ID();
                  $product_title   = get_the_title();
                  $product_excerpt = get_the_excerpt();
                  $product_url     = get_permalink();
                  $product_img_id  = get_post_thumbnail_id($product_id);
                  $product_img_url = $product_img_id ? wp_get_attachment_image_url($product_img_id, 'full') : '';
              ?>
                  <div class="splide__slide">
                    <div class="product_list_card_item">
                      <div class="product_list_card">
                        <div class="product_list_card_body d-flex flex-column">

                          <div class="product_list_dscinfo_content">
                            <?php if ($product_title) : ?>
                              <h4 class="product_heading_text"><?php echo esc_html($product_title); ?></h4>
                            <?php endif; ?>

                            <?php if ($product_excerpt || $product_url) : ?>
                              <div class="product_list_dscinfo_onhover_box">
                                <?php if ($product_excerpt) : ?>
                                  <p><?php echo esc_html($product_excerpt); ?></p>
                                <?php endif; ?>
                                <?php if ($product_url) : ?>
                                  <a href="<?php echo esc_url($product_url); ?>" class="btn btn_deep_blue" aria-label="<?php echo esc_attr('View Product: ' . $product_title); ?>">
                                    <span class="bnt_text_wrap"><span>View Product</span></span>
                                    <span class="arrow"></span>
                                  </a>
                                <?php endif; ?>
                              </div>
                            <?php endif; ?>
                          </div>

                          <?php if ($product_img_url) : ?>
                            <div class="product_img_wrap">
                              <div class="ratio ratio-1x1">
                                <img src="<?php echo esc_url($product_img_url); ?>" alt="<?php echo esc_attr($product_title); ?>" loading="lazy" />
                              </div>
                            </div>
                          <?php endif; ?>

                        </div>
                      </div>
                    </div>
                  </div>
              <?php
                endwhile;
                wp_reset_postdata();
              endif;
              ?>

            </div>
          </div>
        </div>

        <!-- Last Product (no slider) -->
        <?php
        // Get ACF fields
        $card_heading = get_field('know_more_title');          // e.g., "Know More About our Lab Products"
        $btn_text     = get_field('download_catalogue_title'); // e.g., "Download Catalogue"
        $btn_url      = get_field('download_catalogue_url');   // e.g., link to PDF or page
        ?>

        <div class="product_list_card_item product_list_download_card_item mt-4 mt-md-0 mx-auto">
          <div class="product_list_download_card">
            <div class="product_list_download_card_body d-flex flex-column">

              <?php if ($card_heading): ?>
                <h4 class="heading_text mx-auto text-center" data-speed="0.95" data-lag="0">
                  <?php echo esc_html($card_heading); ?>
                </h4>
              <?php endif; ?>

              <?php if ($btn_text && $btn_url): ?>
                <div class="btn_holder">
                  <a href="<?php echo esc_url($btn_url); ?>" class="btn btn_white w-100" aria-label="<?php echo esc_attr($btn_text); ?>">
                    <span class="bnt_text_wrap"><span><?php echo esc_html($btn_text); ?></span></span>
                    <span class="arrow"></span>
                  </a>
                </div>
              <?php endif; ?>

            </div>
          </div>
        </div>

      </div>


      <div class="product_list_card_item_desktop d-xl-block d-none">
        <div class="row row-cols-md-3 row-cols-lg-4 row-cols-xl-5 gx-3 gy-4">

          <?php
          // Query WooCommerce products
          $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 9, // Adjust number of products to show
            'post_status'    => 'publish',
          );
          $products_query = new WP_Query($args);

          if ($products_query->have_posts()) :
            while ($products_query->have_posts()) : $products_query->the_post();

              $product_id      = get_the_ID();
              $product_title   = get_the_title();
              $product_excerpt = get_the_excerpt();
              $product_url     = get_permalink();
              $product_img_id  = get_post_thumbnail_id($product_id);
              $product_img_url = $product_img_id ? wp_get_attachment_image_url($product_img_id, 'full') : '';

          ?>
              <div class="product_list_card_item">
                <div class="product_list_card">
                  <div class="product_list_card_body d-flex flex-column">

                    <div class="product_list_dscinfo_content">
                      <?php if ($product_title) : ?>
                        <h4 class="product_heading_text"><?php echo esc_html($product_title); ?></h4>
                      <?php endif; ?>

                      <?php if ($product_excerpt || $product_url) : ?>
                        <div class="product_list_dscinfo_onhover_box">
                          <?php if ($product_excerpt) : ?>
                            <p><?php echo esc_html($product_excerpt); ?></p>
                          <?php endif; ?>
                          <?php if ($product_url) : ?>
                            <a href="<?php echo esc_url($product_url); ?>" class="btn btn_deep_blue" aria-label="<?php echo esc_attr('View Product: ' . $product_title); ?>">
                              <span class="bnt_text_wrap"><span>View Product</span></span>
                              <span class="arrow"></span>
                            </a>
                          <?php endif; ?>
                        </div>
                      <?php endif; ?>
                    </div>

                    <?php if ($product_img_url) : ?>
                      <div class="product_img_wrap">
                        <div class="ratio ratio-1x1">
                          <img src="<?php echo esc_url($product_img_url); ?>" alt="<?php echo esc_attr($product_title); ?>" loading="lazy" />
                        </div>
                      </div>
                    <?php endif; ?>

                  </div>
                </div>
              </div>
          <?php
            endwhile;
            wp_reset_postdata();
          endif;
          ?>

          <?php
          // Get ACF fields
          $card_heading = get_field('know_more_title'); // e.g., "Know More About our Lab Products"
          $btn_text     = get_field('download_catalogue_title'); // e.g., "Download Catalogue"
          $btn_url      = get_field('download_catalogue_url'); // e.g., link to PDF or page
          ?>

          <div class="product_list_card_item product_list_download_card_item">
            <div class="product_list_download_card">
              <div class="product_list_download_card_body d-flex flex-column">
                <?php if ($card_heading): ?>
                  <h4 class="heading_text mx-auto text-center" data-speed="0.95" data-lag="0">
                    <?php echo esc_html($card_heading); ?>
                  </h4>
                <?php endif; ?>

                <?php if ($btn_text && $btn_url): ?>
                  <div class="btn_holder">
                    <a href="<?php echo esc_url($btn_url); ?>" class="btn btn_white w-100" aria-label="<?php echo esc_attr($btn_text); ?>">
                      <span class="bnt_text_wrap"><span><?php echo esc_html($btn_text); ?></span></span>
                      <span class="arrow"></span>
                    </a>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>


        </div>
      </div>

    </div>
  </div>
</section>

<section class="home_bgvideo_sec position-relative bg_light">

  <?php
  // Get ACF fields
  $video_url     = get_field('upload_video'); // URL of the uploaded video
  $video_title   = get_field('video_title');  // Title above video
  $video_content = get_field('video_content'); // Content/subtitle
  ?>
  <?php if ($video_url) : ?>
    <div class="home_bgvideo_holder w-100 home_bgvideo_holder_ani">
      <div class="container one_side_full_container ms-auto me-0 pe-0">
        <video class="object-fit-cover video_ani" preload="metadata" loop muted playsinline autoplay>
          <source src="<?php echo esc_url($video_url); ?>" type="video/mp4" />
        </video>
      </div>
    </div>
  <?php endif; ?>

  <div class="container w-100 position-relative px-4 px-sm-3">
    <div class="container_inr">
      <div class="home_bgvideo_dsc_content w-100">
        <div class="sec_heading_wrap comn_sec_py">
          <?php if ($video_title) : ?>
            <h2 class="sec_heading_tag_text mb_25 tag_center words_slide_from_right split_text">
              <?php echo esc_html($video_title); ?>
            </h2>
          <?php endif; ?>

          <?php if ($video_content) : ?>
            <h3 class="sec_heading_text_2 words_slide_from_right split_text">
              <?php echo esc_html($video_content); ?>
            </h3>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

</section>

<section class="wcu_sec bg_white">

  <?php
  // Get main ACF fields
  $wcu_title        = get_field('why_choose_us_title');          // e.g., "Why Choose Us"
  $wcu_sub_heading  = get_field('why_choose_us_sub_heading');    // e.g., "The CUSTOM Advantage"
  $wcu_sub_adv      = get_field('why_choose_us_sub_heading_adv');    // e.g., "The CUSTOM Advantage"

  $wcu_details      = get_field('why_choose_us_details');        // main paragraph/content
  $wcu_bg_image     = get_field('why_choose_us_bg_image');       // background image
  $know_more_heading = get_field('know_more_heading');           // CTA heading
  $learn_more_title  = get_field('learn_more_title');            // CTA button text
  $learn_more_url    = get_field('learn_more_url');              // CTA button URL
  ?>

  <div class="container w-100 position-relative comn_sec_py px-4 px-sm-3">

    <div class="divider_line_holder position-absolute">
      <div class="divider_line"></div>
      <div class="divider_line"></div>
      <div class="divider_line"></div>
      <div class="divider_line"></div>
    </div>

    <div class="container_inr">

      <div class="row gy-4 gy-lg-0">

        <!-- Left Column -->
        <div class="col-lg-6">
          <div class="wcu_left_col_content">
            <div class="sec_heading_wrap mb_30">
              <?php if ($wcu_title) : ?>
                <h2 class="sec_heading_tag_text mb_25 words_slide_from_right split_text">
                  <?php echo esc_html($wcu_title); ?>
                </h2>
              <?php endif; ?>

              <?php if ($wcu_sub_heading) : ?>
                <h3 class="sec_heading_text words_slide_from_right split_text">
                  <?php echo esc_html($wcu_sub_heading); ?> <br> <?php echo esc_html($wcu_sub_adv); ?>
                </h3>
              <?php endif; ?>
            </div>

            <?php if ($wcu_details) : ?>
              <div class="comn_dsc_content_holder">
                <div class="comn_dsc_content fade_word_ani split_text">
                  <p><?php echo esc_html($wcu_details); ?></p>
                </div>
              </div>
            <?php endif; ?>

            <!-- CTA -->
            <?php if ($know_more_heading || $learn_more_title) : ?>
              <div class="wcu_cta_wrap position-relative mt_30">
                <?php if ($wcu_bg_image) : ?>
                  <div class="wcu_cta_bg_img">
                    <img src="<?php echo esc_url($wcu_bg_image['url']); ?>" alt="<?php echo esc_attr($wcu_bg_image['alt']); ?>" data-speed="0.9" data-lag="0">
                  </div>
                <?php endif; ?>

                <div class="wcu_cta_content position-relative">
                  <?php if ($know_more_heading) : ?>
                    <h3 class="heading_text_24 color-primary"><?php echo esc_html($know_more_heading); ?></h3>
                  <?php endif; ?>

                  <?php if ($learn_more_title && $learn_more_url) : ?>
                    <div class="btn_holder mt_35">
                      <a href="<?php echo esc_url($learn_more_url); ?>" class="btn btn_deep_blue" aria-label="<?php echo esc_attr($learn_more_title); ?>">
                        <span class="bnt_text_wrap"><span><?php echo esc_html($learn_more_title); ?></span></span>
                        <span class="arrow"></span>
                      </a>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>

          </div>
        </div>

        <!-- Right Column (Repeater Items) -->
        <div class="col-lg-6">
          <div class="wcu_right_col_content">
            <div class="row gy-4 gx-4">
              <?php if (have_rows('why_choose_us_repeat_area')): ?>
                <?php while (have_rows('why_choose_us_repeat_area')): the_row();
                  $icon    = get_sub_field('icon');
                  $heading = get_sub_field('heading');
                  $content = get_sub_field('content');
                ?>
                  <div class="col-md-6">
                    <div class="wcu_icon_dsc_item">
                      <div class="item_body">
                        <?php if ($icon) : ?>
                          <div class="icon_box d-flex align-items-center justify-content-center mb_20">
                            <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>" width="" height="">
                          </div>
                        <?php endif; ?>

                        <?php if ($heading) : ?>
                          <h3 class="heading_text_24 color-black mb_30"><?php echo esc_html($heading); ?></h3>
                        <?php endif; ?>

                        <?php if ($content) : ?>
                          <p><?php echo esc_html($content); ?></p>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

</section>

<section class="certificates_sec position-relative">

  <?php
  // Get main ACF fields
  $certificate_bg_image = get_field('certificate_bg_image'); // Image array
  $title                = get_field('title');                // e.g., "Quality"
  $heading              = get_field('heading');              // e.g., "Committed to Quality, Certified Globally"
  $sub_heading          = get_field('sub_heading');          // e.g., description paragraph
  $certifications       = get_field('certifications');       // Flexible array: certificate + certificate_image
  ?>

  <?php if ($certificate_bg_image) : ?>
    <div class="certificates_bg_img_holder position-absolute">
      <img src="<?php echo esc_url($certificate_bg_image['url']); ?>" alt="<?php echo esc_attr($certificate_bg_image['alt']); ?>" data-speed="0.9" data-lag="0">
      <div class="certificates_graphic_wrap position-absolute">
        <div id='stars'></div>
        <div id='stars2'></div>
        <div id='stars3'></div>
      </div>
    </div>
  <?php endif; ?>

  <div class="container w-100 position-relative comn_sec_py px-4 px-sm-3">
    <div class="container_inr">

      <!-- Section Heading -->
      <div class="sec_heading_wrap mb_50 fade-in text_color_white mx-auto text-center">
        <?php if ($title) : ?>
          <h2 class="sec_heading_tag_text tag_center mb_25 words_slide_from_right split_text"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($heading) : ?>
          <h3 class="sec_heading_text words_slide_from_right split_text"><?php echo esc_html($heading); ?></h3>
        <?php endif; ?>

        <?php if ($sub_heading) : ?>
          <div class="comn_dsc_content text_color_white mt_30 fade_word_ani split_text">
            <p><?php echo esc_html($sub_heading); ?></p>
          </div>
        <?php endif; ?>
      </div>

      <!-- Certificates Slider -->
      <?php if ($certifications) : ?>
        <div class="w-100 certificates_slider_wrap">
          <div class="certificates_slider_holder w-100 position-relative">
            <div class="splide certificates_slider">
              <div class="splide__track">
                <div class="splide__list">

                  <?php foreach ($certifications as $cert) :
                    $cert_image = $cert['certificate_image'];
                    $cert_name  = $cert['certificate'];
                  ?>
                    <div class="splide__slide">
                      <div class="certificates_item">
                        <div class="img_wrap">
                          <div class="img_holder">
                            <?php if ($cert_image) : ?>
                              <img src="<?php echo esc_url($cert_image['url']); ?>" alt="<?php echo esc_attr($cert_name); ?>" loading="lazy">
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>

                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>


<section class="sustainability_sec position-relative">

  <?php
  // Fetch ACF fields
  $sustainability_title        = get_field('sustainability_title');
  $sustainability_heading      = get_field('sustainability_heading');
  $sustainability_sub_heading  = get_field('sustainability_sub_heading');
  $explore_more_title          = get_field('explore_more_title');
  $explore_more_url            = get_field('explore_more_url');


  // Default fallback images
  $default_bg_image        = CUSTOM_THEME_URI . '/assets/images/home/sustainability_sec_bg_img.jpg';
  $default_mobile_image    = CUSTOM_THEME_URI . '/assets/images/home/eco-friendly-img_for_mobile.jpg';

  // ACF Fields
  $sustainability_bg_image     = get_field('sustainability_bg_image');
  $sustainability_mobile_image = get_field('sustainability_mobile_image');
  ?>

  <div class="container w-100 position-relative comn_sec_py px-4 px-sm-3">

    <div class="divider_line_holder position-absolute">
      <div class="divider_line"></div>
      <div class="divider_line"></div>
      <div class="divider_line"></div>
      <div class="divider_line"></div>
    </div>

    <div class="container_inr position-relative z-1">

      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="sustainability_content_wrap">

            <div class="sec_heading_wrap mb_30">
              <?php if ($sustainability_title) : ?>
                <h2 class="sec_heading_tag_text mb_25 words_slide_from_right split_text">
                  <?php echo esc_html($sustainability_title); ?>
                </h2>
              <?php endif; ?>

              <?php if ($sustainability_heading) : ?>
                <h3 class="sec_heading_text words_slide_from_right split_text">
                  <?php echo esc_html($sustainability_heading); ?>
                </h3>
              <?php endif; ?>
            </div>

            <?php if ($sustainability_sub_heading) : ?>
              <div class="comn_dsc_content fade_word_ani split_text">
                <p><?php echo esc_html($sustainability_sub_heading); ?></p>
              </div>
            <?php endif; ?>

            <?php if ($explore_more_title && $explore_more_url) : ?>
              <div class="btn_holder mt_30">
                <a href="<?php echo esc_url($explore_more_url); ?>" class="btn btn_deep_blue" aria-label="Explore More">
                  <span class="bnt_text_wrap"><span><?php echo esc_html($explore_more_title); ?></span></span>
                  <span class="arrow"></span>
                </a>
              </div>
            <?php endif; ?>

          </div>
        </div>

        <div class="col-lg-6">
          <!-- Reserved for future content if needed -->
        </div>
      </div>

    </div>
  </div>

  <div class="sustainability_bg_img">
    <img src="<?php echo esc_url($sustainability_bg_image['url'] ?? $default_bg_image); ?>"
      alt="<?php echo esc_attr($sustainability_bg_image['alt'] ?? 'Sustainability Background'); ?>"
      data-speed="0.9" data-lag="0"
      class="d-lg-block d-none">

    <img src="<?php echo esc_url($sustainability_mobile_image['url'] ?? $default_mobile_image); ?>"
      alt="<?php echo esc_attr($sustainability_mobile_image['alt'] ?? 'Sustainability Mobile Background'); ?>"
      data-speed="0.9" data-lag="0"
      class="d-block d-lg-none">

    <div class="sustainability_graphic_wrap">
      <div class="earth_globe">
        <div class="greenery_halo">
          <div class="tree tree-1"></div>
          <div class="tree tree-2"></div>
          <div class="tree tree-3"></div>
          <div class="tree tree-4"></div>
          <div class="tree tree-5"></div>
          <div class="tree tree-6"></div>
          <div class="tree tree-7"></div>
          <div class="tree tree-8"></div>
        </div>

        <div class="floating_elements">
          <div class="leaf leaf-1"></div>
          <div class="leaf leaf-2"></div>
          <div class="leaf leaf-3"></div>
          <div class="leaf leaf-4"></div>
          <div class="leaf leaf-5"></div>
          <div class="particle particle-1"></div>
          <div class="particle particle-2"></div>
          <div class="particle particle-3"></div>
          <div class="sparkle sparkle-1"></div>
          <div class="sparkle sparkle-2"></div>
          <div class="sparkle sparkle-3"></div>
        </div>
      </div>
    </div>
  </div>

</section>


<section class="resources_sec position-relative">
  <div class="container w-100 position-relative comn_sec_py px-4 px-sm-3">
    <div class="container_inr">


      <div
        class="resources_sec_heading_holder d-flex justify-content-between flex-column flex-md-row mb_50 column-gap-3 row-gap-3 align-items-md-end">

        <?php
        $resources_title        = get_field('resources_title');
        $resources_subtitle     = get_field('resources_subtitle');
        $explore_button_text    = get_field('explore_button_text');
        $explore_button_url     = get_field('explore_button_url');
        ?>

        <div class="sec_heading_wrap">
          <h2 class="sec_heading_tag_text mb_25 words_slide_from_right split_text">
            <?php echo esc_html($resources_title); ?>
          </h2>
          <h3 class="sec_heading_text words_slide_from_right split_text">
            <?php echo esc_html($resources_subtitle); ?>
          </h3>
        </div>

        <div class="btn_holder flex-shrink-0">
          <a href="<?php echo esc_url($explore_button_url); ?>" class="btn btn_deep_blue" aria-label="<?php echo esc_attr($explore_button_text); ?>">
            <span class="bnt_text_wrap">
              <span><?php echo esc_html($explore_button_text); ?></span>
            </span>
            <span class="arrow"></span>
          </a>
        </div>

      </div>


      <?php
      $args = [
        'post_type'      => 'resource',
        'posts_per_page' => 3,
      ];

      $resources_query = new WP_Query($args);

      if ($resources_query->have_posts()) :
        $post_counter = 0;
      ?>
        <div class="row gy-4 gy-xl-0 gx-4 gx-xl-0">

          <?php while ($resources_query->have_posts()) : $resources_query->the_post();
            $post_counter++;
            $featured_img = get_the_post_thumbnail_url(get_the_ID(), 'full') ? get_the_post_thumbnail_url(get_the_ID(), 'full') : CUSTOM_THEME_URI . '/assets/images/default_resource_img.jpg';
            $post_url     = get_permalink();
            $post_title   = get_the_title();
            $post_date    = get_the_date('d M, Y');
            $read_time    = get_field('read_time'); // Assuming you store this in a custom field
          ?>

            <?php if ($post_counter === 1) : ?>
              <div class="col-12 col-xl-7 pe-xl-4">
                <div class="resources_main_card has_link">
                  <a href="<?php echo esc_url($post_url); ?>" class="total_link"></a>

                  <div class="resources_main_image position-relative">
                    <div class="ratio">
                      <img src="<?php echo esc_url($featured_img); ?>" alt="<?php echo esc_attr($post_title); ?>">
                    </div>
                  </div>

                  <div class="resources_main_overlay">
                    <div class="resources_main_content">
                      <h3 class="resources_main_title heading_text_24 multi-3-line-ellipsis"><?php echo esc_html($post_title); ?></h3>
                      <div class="resources_main_meta">
                        <span class="resources_date"><?php echo esc_html($post_date); ?></span>
                        <span class="resources_read_time"><?php echo esc_html($read_time); ?> min read</span>
                      </div>
                      <span class="read_more_arrow_link color_blue" aria-label="Learn More">Read more</span>
                    </div>
                  </div>
                </div>
              </div>
            <?php else : ?>
              <?php if ($post_counter === 2) : ?>
                <div class="col-12 col-xl-5">
                  <div class="resources_side_cards d-flex flex-column">
                  <?php endif; ?>

                  <div class="resources_side_card has_link">
                    <a href="<?php echo esc_url($post_url); ?>" class="total_link"></a>
                    <div class="resources_side_card_body d-flex">
                      <div class="resources_side_image">
                        <div class="ratio">
                          <img src="<?php echo esc_url($featured_img); ?>" alt="<?php echo esc_attr($post_title); ?>">
                        </div>
                      </div>
                      <div class="resources_side_content">
                        <h4 class="resources_side_title multi-3-line-ellipsis"><?php echo esc_html($post_title); ?></h4>
                        <div class="resources_side_meta">
                          <span class="resources_date"><?php echo esc_html($post_date); ?></span>
                          <span class="resources_read_time"><?php echo esc_html($read_time); ?> min read</span>
                        </div>
                        <span class="read_more_arrow_link" aria-label="Learn More">Read more</span>
                      </div>
                    </div>
                  </div>

                  <?php if ($post_counter === 3) : ?>
                  </div>
                </div>
              <?php endif; ?>
            <?php endif; ?>

          <?php endwhile; ?>
        </div>
      <?php
        wp_reset_postdata();
      endif;
      ?>

    </div>
  </div>
</section>


<?php
// Use the reusable CTA component
get_template_part('template-parts/components/cta');
?>


<?php
get_footer();  // Load footer.php
