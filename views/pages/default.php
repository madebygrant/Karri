<?php
/*
    View: Page - Default
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

// START of the Loop
if ( have_posts() ) : while ( have_posts() ) : the_post(); if( !post_password_required($post->ID) ):

?>
    
    <?php // Page: Featured Image ?>
    <?php Theme::module( 'images/featured-hero' ); ?>

    <div class="container inner inner--page">

        <?php // Page: Header ?>
        <?php Theme::module( 'headings/page' ); ?>

        <?php // Page: Content ?>
        <?php Theme::module( 'content' ) ?>

        <?php Theme::slider(); ?>
    </div>

    <?php // Page: Comments ?>
    <?php Theme::module( 'comments' ); ?>
    
<?php 
// END of the Loop
endif; endwhile; endif;