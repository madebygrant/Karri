<?php
/* 
    Module: Featured Image - Large
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

$post_id = !isset($post_id) && isset($post) ? $post->ID : ( !isset($post_id) ? get_the_ID() : $post_id );

if ( has_post_thumbnail($post_id) ):
    $image_caption = isset($caption) && $caption == true ? wp_get_attachment_caption( get_post_thumbnail_id( $post_id ) ) : '';
?>

    <figure class="featured-image featured-image--hero">
        <?php echo Theme::image( [ 'post-id' => $post_id, 'featured' => true, 'size' => THEME_PREFIX . '-hero-xlarge', 'class' => ' image--featured', 'lazy' => true ] ); ?>

        <?php if( !empty($image_caption) ): ?>
            <figcaption class="featured-image__caption">
                <?php esc_html_e( $image_caption, 'karri' ) ?>
            </figcaption>
        <?php endif; ?>
    </figure>
    
<?php endif; ?>