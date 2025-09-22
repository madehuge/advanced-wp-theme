<?php
defined('ABSPATH') || exit;
get_header();
?>

<main>
    <?php if (woocommerce_product_loop()) :
        woocommerce_output_all_notices();
        woocommerce_product_loop_start();
        while (have_posts()) : the_post();
            wc_get_template_part('content', 'product');
        endwhile;
        woocommerce_product_loop_end();
        get_template_part('template-parts/components/pagination');
    else :
        echo '<p>' . esc_html__('No products found', 'custom-theme') . '</p>';
    endif; ?>
</main>

<?php get_footer(); ?>
