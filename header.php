<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- Wrapper Header -->
<header class="wraper-header w-100 z-index-9" id="siteHeader">

    <div class="wraper-header-main">
        <div class="container px-4 px-sm-3">

            <div class="navbar navbar-expand-xl header-main justify-content-between">

                <div class="d-flex flex-wrap align-items-center flex-shrink-0">
                    <div class="brand-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo-link">

                            <?php 
                            // Check if a custom logo is set in Site Identity
                            if (function_exists('the_custom_logo') && has_custom_logo()) : 
                                the_custom_logo();
                            else : 
                                // Fallback to default static SVG in your theme
                            ?>
                                <img src="<?php echo esc_url(CUSTOM_THEME_URI . '/assets/images/logo.svg'); ?>" 
                                    alt="<?php bloginfo('name'); ?>" 
                                    width="150" 
                                    height="40" 
                                    class="colorLogo" />
                            <?php endif; ?>

                        </a>
                    </div>   

                </div>

                <div class="d-flex flex-wrap align-items-center flex-fill justify-content-end justify-content-xl-center">

                    <!-- Main Nav -->
                    <nav class="main-nav ms-auto me-auto d-xl-flex d-lg-none d-md-none d-sm-none d-none align-items-center">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'main-menu',
                            'container'      => false,
                            'menu_class'    => 'menu',
                        ]);
                        ?>
                    </nav>

                    <div class="header-right">
                        <div class="d-flex align-items-center">

                            <div class="header_other_link_holder mr_20">
                                <?php if ( is_user_logged_in() ) : 
                                    // User is logged in → show Logout
                                    $logout_url = wp_logout_url( home_url() ); // Redirect to homepage after logout
                                ?>
                                    <a href="<?php echo esc_url( $logout_url ); ?>" class="header_other_link">
                                        <span><?php esc_html_e( 'Logout', 'custom-theme' ); ?></span>
                                    </a>
                                <?php else : 
                                    // User is logged out → show Login
                                    $login_url = wp_login_url(); // Redirects back to current page by default
                                ?>
                                    <a href="<?php echo esc_url( $login_url ); ?>" class="header_other_link">
                                        <span><?php esc_html_e( 'Login', 'custom-theme' ); ?></span>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-header btn_deep_blue fw-medium d-none d-xl-block">
                                <span class="bnt_text_wrap"><span>Contact</span></span>
                                <span class="arrow"></span>
                            </a>

                            <button class="header-mobilemenu-trigger toggle-mobile-menu float-right btn p-0 no-hover-effect d-xl-none d-lg-block d-md-block d-sm-block d-block" type="button">
                                <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/h-menu.svg" alt="Menu" />
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</header>

<!-- Wrapper Mobile Header -->
<div class="wraper-mobile-header">
    <div class="mobile-header d-flex">
        <div class="mobile-header-top w-100">
            <div class="mobile-header-brand-wrap">
                <div class="row align-items-center">
                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-8 text-left">
                        <div class="brand-logo d-inline-block">
                            <a href="<?php echo esc_url(home_url('/')); ?>">
                                <?php 
                                    // Check if a custom logo is set in Site Identity
                                    if (function_exists('the_custom_logo') && has_custom_logo()) : 
                                        the_custom_logo();
                                    else : 
                                        // Fallback to default static SVG in your theme
                                    ?>
                                        <img src="<?php echo esc_url(CUSTOM_THEME_URI . '/assets/images/logo.svg'); ?>" 
                                            alt="<?php bloginfo('name'); ?>" 
                                            width="150" 
                                            height="40" 
                                            class="colorLogo" />
                                    <?php endif; ?>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-4 text-end">
                        <button type="button" class="toggle-mobile-menu mobile-menu-close d-inline-block">
                            <img src="<?php echo CUSTOM_THEME_URI; ?>/assets/images/close.svg" alt="Close Menu" width="18" height="18" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="mobile-header-menu">
                <?php
                wp_nav_menu([
                    'theme_location' => 'main-menu',
                    'container'      => false,
                    'menu_class'    => 'menu',
                ]);
                ?>
            </div>
        </div>
    </div>
</div>


<main id="main">
    <div id="content">
      <div class="site-wrapper">