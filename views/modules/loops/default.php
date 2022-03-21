<?php
/*
    Module: Loop - Posts Default
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

    if( !isset($paginate) ){
        $paginate = true;
        $paged = _get_paged();
    }

    if( !isset($class) ){
        $class = 'grid gaps columns--3';
    }

    if( !isset($post_class) ){
        $post_class = false;
    }

    if( !isset($view) ){
        // ? If false, it'll attempt to search in the 'views/posts' directory for a file named with the same post type slug.
        $view = false;
    }

    if( !isset($error_message) ){
        $error_message = 'Sorry, no posts are available.';
    }
?>

<div class="<?php echo esc_attr($class); ?>">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();  ?>

        <?php // Get the standard post type template ?>
        <?php Theme::post_view( $view, [ 'post_class' => $post_class, 'post' => $post ] ); ?>

    <?php // End of Loop ?>
    <?php endwhile; elseif( $error_message && $error_message !== '' ): ?>

        <p class="posts-error posts-error--default">
            <?php echo _esc_html_e($error_message) ?>
        </p>

    <?php // REALLY stop The Loop. ?>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>

</div>

<?php 
    // Pagination
    if( $paginate == true ){
        echo Theme::pagination( $paged );
    }  
?>