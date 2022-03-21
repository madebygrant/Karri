<?php
/*
    Page: Home
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

$post_id = get_option('page_for_posts', true);

Theme::header();

if($post_id):
?>

<?php // Page: Featured Image
    Theme::module( 'images/featured-hero', [ 'post_id' => $post_id ] ); 
?>

<div class="container inner inner--page inner--blog">
    
    <?php // Page: Header
        Theme::module( 'headings/page', [ 'text' => get_the_title( $post_id ) ] ); 
    ?>

    <?php // Get the default loop
        Theme::module( 'loops/default' );
    ?>

</div>

<?php
endif;

Theme::footer();