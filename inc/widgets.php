<?php

function custom_footer_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Product Footer Widget', 'custom-theme' ),
        'id'            => 'footer-product-widget',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="footer-widget-title d-none" style="display:none;">',
        'after_title'   => '</span>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Company Footer Widget', 'custom-theme' ),
        'id'            => 'footer-company-widget',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="footer-widget-title d-none" style="display:none;">',
        'after_title'   => '</span>',
    ) );
}
add_action( 'widgets_init', 'custom_footer_widgets_init' );