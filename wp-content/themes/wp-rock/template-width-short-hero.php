<?php

/**
 *
 * Template name: Short hero
 *
 */
get_header();

?>
<div class="short-hero">
    <div class="container">
        <div class="breadcrumbs d-flex">
            <?php if (function_exists('bcn_display')) {
                bcn_display();
            } ?>
        </div>
    </div>
</div>

<div class="short-hero-content">
    <div class="container">
        <?php
        if ( have_posts() ) :
            // Start the loop.
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
        endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>
