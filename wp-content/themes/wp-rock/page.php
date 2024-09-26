<?php
/**
 * General template for pages
 *
 * @package WP-rock
 * @since 4.4.0
 */

get_header();
?>


<?php
do_action( 'wp_rock_before_page_content' );

if ( have_posts() ) :
    // Start the loop.
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
endif;

do_action( 'wp_rock_after_page_content' );

//global $profile_functionality;

$order = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_NUMBER_INT);

if ( isset($order) && is_numeric($order) && $order > 0 ) {
	echo get_template_part('src/template-parts/success-popup-after-payment', null, array(
		'order_id' => $order
	));
}


?>


<?php
get_footer();
