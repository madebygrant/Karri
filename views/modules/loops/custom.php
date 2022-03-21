<?php
/*
    Module: Loop - Posts
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

    $tax_query = $meta_query = [];

    if( !isset($paged) ){
        $paged = _get_paged();
    }

    if( !isset($paginate) || $paginate !== false ){
        $paginate = true;
    }

    if( !isset($class) ){
        $class = 'grid gaps columns--3';
    }
    
    if( !isset($post_class) ){
        $post_class = false;
    }

    if( !isset($post_type) ){
        $post_type = 'post';
    }

    if( !isset($amount) ){
        $amount = get_option( 'posts_per_page' );
    }

    if( !isset($order) ){
        $order = '';
    }

    if( !isset($orderby) ){
        $orderby = '';
    }

    if( !isset($category) ){
        $category = '';
    }

    if( !isset($view) ){
        // If false, it'll attempt to search in the 'views/posts' directory for a file named with the same post type slug.
        $view = false;
    }

    if( !isset($error_message) ){
        $error_message = 'Sorry, no posts are available.';
    }

    if( isset($term) && isset($taxonomy) ){
        $tax_query = [
            [
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $term,
            ]
        ];
    }

    if( isset($meta) && is_array($meta) ){
        // Example: $meta = [ [ 'key' => PREFIX_ . 'POST_META_ID_HERE', 'value' => 'SOME VALUE', 'type' => 'numeric', 'compare' => '=' ] ]
        $meta_query = $meta;
    }
    
?>
    <div class="<?php echo esc_attr($class); ?>">

        <?php
            global $post;
            
            $loop_args = [ 
                'post_type' => $post_type, 'posts_per_page' => $amount, 'orderby' => $orderby, 'order' => $order, 
                'category_name' => $category, 'tax_query' => $tax_query, 'meta_query' => $meta_query, 'paged' => $paged 
            ];
            $loop_args = isset($other) && is_array($other) ? _merge_arrays($loop_args, $other) : $loop_args;
            
            $the_loop = new WP_Query( $loop_args );

            // Start of the Loop
            if ( $the_loop->have_posts() ) : while ( $the_loop->have_posts() ) : $the_loop->the_post();
            setup_postdata( $post );
        ?>

            <?php // Get the standard post type template ?>
            <?php Theme::post_view( $view, [ 'post_class' => $post_class, 'post' => isset($post) ? $post : false ] ); ?>

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
    // For Custom Post Types: Theme::pagination($paged, $custom_loop = false);
    
    if( $paginate == true ){
        echo Theme::pagination( $paged, $the_loop );
    }  
?>