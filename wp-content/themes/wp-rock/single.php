<?php
/**
 * General template for single posts
 *
 * @package WP-rock
 * @since 4.4.0
 */

get_header();

do_action( 'wp_rock_before_page_content' );
?>
    <div class="container single-blog">
        <div class="row">
            <div class="col-12">
                <?php
                if ( have_posts() ) :
                    while ( have_posts() ) :
                        the_post();
                        the_content();
                    endwhile;
                endif;
                ?>
            </div>
        </div>
    </div>

<?php do_action( 'wp_rock_after_page_content' ); ?>
<?php
get_footer();
