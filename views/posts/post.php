<?php 
/*
    Post: Default
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------
    
if( empty($post) ){
    $post = get_post(get_the_ID());   
}

//  If it's not protected
if( !post_password_required($post->ID) ):
    
?>

    <article class="is-post <?php echo esc_attr($post->post_type) ?>" id="<?php echo esc_attr($post->post_type.'-'.$post->ID) ?>">

        <?php // Post: Featured Thumbnail ?>
        <?php Theme::module( 'images/featured-thumb' ); ?>

        <div class="container--rigid is-post__inner">

            <?php // Post: Header ?>
            <?php Theme::module( 'headings/post' ); ?>

            <?php 
                if( is_search() ):
                    $post_type_obj = get_post_type_object( $post->post_type );
                ?>
                    <span class="gap-below post-type post-type--<?php _esc_html_e($post_type_obj->name) ?>">
                        <?php _esc_html_e($post_type_obj->labels->singular_name) ?>
                    </span>
            <?php endif; ?>

            <?php // Post: Excerpt ?>
            <?php Theme::module('content', [ 'excerpt' => true ]); ?>

            <?php // Post: Taxonomy ?>
            <?php if( has_category() || has_tag() ): ?>
                <?php Theme::module( 'taxonomy' ); ?>
            <?php endif; ?>

        </div>

    </article>

<?php
endif;