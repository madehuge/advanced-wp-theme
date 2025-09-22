<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package custom-theme
 */

// If in Customizer preview, always render fresh
if ( is_customize_preview() ) {
    ob_start();
} else {
    $footer_cache_key = 'custom_footer_html_cache';
    $footer_html = get_transient($footer_cache_key);
    if ( false !== $footer_html ) {
        echo $footer_html;
        wp_footer();
        echo '</body></html>';
        return; // Serve cached footer and exit
    }
    ob_start();
}
?>

<!-- Footer Section -->
<footer class="comn_footer_sec">

    <div class="bg_img_holder">
        <img src="<?php echo esc_url(get_theme_mod('custom_footer_bg', CUSTOM_THEME_URI . '/assets/images/footer_bg_img.png')); ?>" alt="Footer Background Image">
    </div>

    <div class="container px-4 px-sm-3">

        <div class="divider_line_holder position-absolute white_line">
            <div class="divider_line"></div>
            <div class="divider_line"></div>
            <div class="divider_line"></div>
            <div class="divider_line"></div>
        </div>

        <div class="container_inr position-relative">

            <div class="comn_footer_content">

                <div class="row align-items-start gx-4">

                    <!-- Company Branding -->
                    <div class="col-lg-3 col-xl-3">
                        <div class="comn_footer_left_content">
                            <div class="footer-brand">
                                <div class="footer-logo">
                                    <img src="<?php echo esc_url(get_theme_mod('custom_footer_logo', CUSTOM_THEME_URI . '/assets/images/footer_logo.svg')); ?>" alt="Footer Logo">
                                </div>

                                <?php if ( get_theme_mod('custom_about_content_show', '1') == 1 ) : ?>
                                    <p class="comn_dsc_content text_color_white">
                                        <?php echo esc_html(get_theme_mod('custom_about_content', 'Part of the 58-year-strong CUSTOM Group, we are a leading manufacturer of laboratory consumables and scientific research products.')); ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="footer-bottom-left">
                                <?php if ( get_theme_mod('custom_copyright_text_show', '1') == 1 ) : ?>
                                    <p class="copyright">
                                        <?php echo esc_html(get_theme_mod('custom_copyright_text', '&copy; ' . date('Y') . ' All rights reserved.')); ?>
                                    </p>
                                <?php endif; ?>

                                <div class="social-media">
                                    <?php
                                    $socials = ['fb','in','tw'];
                                    foreach ($socials as $s) :
                                        $icon = get_theme_mod('custom_' . $s . '_icon');
                                        $url  = get_theme_mod('custom_' . $s . '_url');
                                        if ( $icon && $url ) : ?>
                                            <a href="<?php echo esc_url($url); ?>" class="social-icon" aria-label="<?php echo esc_attr(ucfirst($s)); ?>">
                                                <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr(ucfirst($s)); ?>">
                                            </a>
                                    <?php endif; endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Download Buttons and Footer Menu -->
                    <div class="col-lg-9 col-xl-9">
                        <div class="comn_footer_right_content ps-xl-5 ps-lg-3">
                            <div class="footer-top">
                                <div class="download-buttons">
                                    <?php if ( get_theme_mod('custom_product_catalogue_name_show', '1') == 1 ) : ?>
                                        <a href="<?php echo esc_url(get_theme_mod('custom_product_catalogue_url', '#')); ?>" class="download-btn">
                                            <span><?php echo esc_html(get_theme_mod('custom_product_catalogue_name', 'Download Product Catalogue')); ?></span>
                                            <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/icons/download_icon_blue.svg" alt="Download Icon" class="arrow-icon">
                                        </a>
                                    <?php endif; ?>

                                    <?php if ( get_theme_mod('custom_certificate_name_show', '1') == 1 ) : ?>
                                        <a href="<?php echo esc_url(get_theme_mod('custom_certificate_url', '#')); ?>" class="download-btn">
                                            <span><?php echo esc_html(get_theme_mod('custom_certificate_name', 'Download Certificate Of Analysis')); ?></span>
                                            <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/icons/download_icon_blue.svg" alt="Download Icon" class="arrow-icon">
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="footer-middle">
                                <div class="row gy-4 gy-md-0 gx-lg-5">
                                  <div class="col-md-6 col-lg-8">
                                        <div class="footer_menu_content">
                                            <div class="footer_menu_header" data-accordion="product-range">
                                                <h3 class="footer_menu_title">
                                                    <?php
                                                    if ( is_active_sidebar( 'footer-product-widget' ) ) {
                                                        // Get all widgets in the sidebar
                                                        $sidebars_widgets = wp_get_sidebars_widgets();
                                                        $widget_title = 'CUSTOM PRODUCT RANGE'; // Default title

                                                        if ( !empty( $sidebars_widgets['footer-product-widget'] ) ) {
                                                            foreach ( $sidebars_widgets['footer-product-widget'] as $widget_id ) {
                                                                // Get widget base and number
                                                                $id_base = preg_replace( '/-\d+$/', '', $widget_id );
                                                                $widget_number = str_replace( $id_base . '-', '', $widget_id );
                                                                $widget_options = get_option( 'widget_' . $id_base );
                                                                if ( isset( $widget_options[$widget_number]['title'] ) && !empty( $widget_options[$widget_number]['title'] ) ) {
                                                                    $widget_title = esc_html( $widget_options[$widget_number]['title'] );
                                                                    break; // Use the first widget title found
                                                                }
                                                            }
                                                        }
                                                        echo $widget_title;
                                                    } else {
                                                        echo 'CUSTOM PRODUCT RANGE';
                                                    }
                                                    ?>
                                                </h3>
                                                <button class="footer-menu-accordion-toggle d-lg-none" aria-label="Toggle Product Range"><span class="toggle-icon">+</span></button>
                                            </div>
                                            <div class="product-range-grid footer-menu-accordion-content" data-accordion-content="product-range">
                                                <?php
                                                if ( is_active_sidebar( 'footer-product-widget' ) ) {
                                                    dynamic_sidebar( 'footer-product-widget' );
                                                } else {
                                                    wp_nav_menu( array(
                                                        'theme_location' => 'product-footer',
                                                        'container'      => false,
                                                        'menu_class'     => 'footer-links',
                                                        'fallback_cb'    => false
                                                    ) );
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="footer_menu_content">
                                            <div class="footer_menu_header" data-accordion="company">
                                            <h3 class="footer_menu_title">
                                                <?php
                                                if ( is_active_sidebar( 'footer-company-widget' ) ) {
                                                    $sidebars_widgets = wp_get_sidebars_widgets();
                                                    $widget_title = 'COMPANY'; // Default title

                                                    if ( !empty( $sidebars_widgets['footer-company-widget'] ) ) {
                                                        foreach ( $sidebars_widgets['footer-company-widget'] as $widget_id ) {
                                                            $id_base = preg_replace( '/-\d+$/', '', $widget_id );
                                                            $widget_number = str_replace( $id_base . '-', '', $widget_id );
                                                            $widget_options = get_option( 'widget_' . $id_base );
                                                            if ( isset( $widget_options[$widget_number]['title'] ) && !empty( $widget_options[$widget_number]['title'] ) ) {
                                                                $widget_title = esc_html( $widget_options[$widget_number]['title'] );
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    echo $widget_title;
                                                } else {
                                                    echo 'COMPANY';
                                                }
                                                ?>
                                            </h3>
                                            <button class="footer-menu-accordion-toggle d-lg-none" aria-label="Toggle Company"><span class="toggle-icon">+</span></button>
                                        </div>
                                            <div class="footer-menu-accordion-content" data-accordion-content="company">
                                            <?php
                                            if ( is_active_sidebar( 'footer-company-widget' ) ) {
                                                dynamic_sidebar( 'footer-company-widget' );
                                            } else {
                                                wp_nav_menu( array(
                                                    'theme_location' => 'company-footer',
                                                    'container'      => false,
                                                    'menu_class'     => 'footer-links',
                                                    'fallback_cb'    => false
                                                ) );
                                            }
                                            ?>
                                        </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            

                            <div class="footer-bottom">
                                <div class="row align-items-end">
                                    <div class="col-lg-12">
                                        <div class="contact-info">
                                            <div class="row gx-md-5 justify-content-center justify-content-lg-start">

                                                <?php if ( get_theme_mod('custom_office_phone_text_show', '1') == 1 ) : ?>
                                                    <div class="col-md-auto">
                                                        <div class="contact-item">
                                                            <h4 class="contact-label"><?php echo esc_html(get_theme_mod('custom_office_phone_text', 'OFFICE PHONE')); ?></h4>
                                                            <p class="contact-value">
                                                                <a href="tel:<?php echo esc_attr(get_theme_mod('custom_office_phone_number', '+1 891 989-11-92')); ?>">
                                                                    <?php echo esc_html(get_theme_mod('custom_office_phone_number', '+1 891 989-11-92')); ?>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ( get_theme_mod('custom_manufacture_phone_text_show', '1') == 1 ) : ?>
                                                    <div class="col-md-auto">
                                                        <div class="contact-item">
                                                            <h4 class="contact-label"><?php echo esc_html(get_theme_mod('custom_manufacture_phone_text', 'MANUFACTURE PHONE')); ?></h4>
                                                            <p class="contact-value">
                                                                <a href="tel:<?php echo esc_attr(get_theme_mod('custom_manufacture_phone_number', '+1 891 989-11-93')); ?>">
                                                                    <?php echo esc_html(get_theme_mod('custom_manufacture_phone_number', '+1 891 989-11-93')); ?>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ( get_theme_mod('custom_email_field_name_show', '1') == 1 ) : ?>
                                                    <div class="col-md-auto">
                                                        <div class="contact-item">
                                                            <h4 class="contact-label"><?php echo esc_html(get_theme_mod('custom_email_field_name', 'EMAIL')); ?></h4>
                                                            <p class="contact-value">
                                                                <a href="mailto:<?php echo sanitize_email(get_theme_mod('custom_email_field', 'info@logoipsum.com')); ?>">
                                                                    <?php echo sanitize_email(get_theme_mod('custom_email_field', 'info@logoipsum.com')); ?>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</footer>

<?php
if ( ! is_customize_preview() ) {
    // Cache footer HTML for 12 hours
    set_transient($footer_cache_key, ob_get_clean(), 12 * HOUR_IN_SECONDS);
} else {
    // Just output for Customizer preview
    echo ob_get_clean();
}

wp_footer();
?>
</body>
</html>
