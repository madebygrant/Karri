<?php
// ----------- Karri: Miscellaneous Components -----------

namespace Karri\Components;

if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

if( !function_exists('Karri\Components\pagination') ){

    /**
    * Numbered Pagination
    *
    * @param string $custom_loop Add the custom loop variable used to query, if doing a custom loop.
    * @param string $prev_text Text for the 'Previous Page' link
    * @param string $next_text Text for the 'Next Page' link
    * @return string Echos the HTML code of the numbered pagination list
    */

    function pagination($paged, $args) {
        global $wp_query;

        $default_args = [ 'loop' => false, 'prev' => '&laquo;', 'next' => '&raquo;' ];
        $args = _merge_arrays($default_args, $args);

        $big = 999999999; // need an unlikely integer
        $output = false;

        $pagination_args = [
            //'base'        => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'base'        => @add_query_arg('paged', '%#%'),
            'format'      => '?paged=%#%',
            'current'     => max( 1, $paged),
            'type'        => 'array',
            'prev_next'   => true,
            'prev_text'   => __($args['prev'], 'karri'),
            'next_text'   => __($args['next'], 'karri'),
        ];

        $pagination_args['total'] = $args['loop'] !== false && !is_search() ? $args['loop']->max_num_pages : $wp_query->max_num_pages;
        
        $pages = paginate_links($pagination_args);

        if( is_array( $pages ) ) {
            
            $output =  '<nav class="pagination"><ul class="pagination__list">';

            foreach ( $pages as $link ) {
                $number = "";
                if( preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $link, $n) ){
                    $n = preg_replace('{/$}', '', $n['href'][0]);
                    $n = explode('/', $n);
                    $number = $n[count($n) - 1];
                }
                
                $link = $number !== '' ? 
                    str_replace('<a ', '<a class="pagination__link" rel="nofollow" data-link="'.$number.'" ', $link) : 
                    str_replace('<a ', '<a class="pagination__link" rel="nofollow" ', $link);
                
                    $output .= "<li class='pagination__li'>".$link."</li>";
            }

            $output .=  '</ul></nav>';
        }

        return $output;
    }

}

// ----------------------------

if( !function_exists('Karri\Components\excerpt') ){

    /**
    * Post Excerpt
    *
    * @param integer $count Number of characters
    * @param array $excerpt_args [ 'post-id' => false, 'button' => true, 'count' => 350, 'ellipsis' => true ]
    * @return string Returns the excerpt
    */

    function excerpt($args = []){

        $default_args = [ 'post-id' => false, 'button' => true, 'count' => 350, 'ellipsis' => true, 'first-paragraph' => false, 'more_text' => 'Read more', 'content' => '' ];
        $args = _merge_arrays($default_args, $args);

        $content = '';

        // Get content
        if( $args['post-id'] !== false && $args['content'] === '' ){
            $post_id = (int)$args['post-id'];
            $post = get_post($post_id);
            $content = $post->post_content;
        }
        else if( $args['post-id'] === false && $args['content'] === '' ){
            global $post;
            $content = $post->post_content;
        }
        else{
            $content = $args['content'];
            $args['button'] = false;
        }

        // Check if custom excerpt
        $has_custom_excerpt = !empty($post->post_excerpt);

        // Return if no content
        if( $content === '' && !$has_custom_excerpt ){
            return false;
        }

        $permalink = $args['button'] == true ? get_permalink($post->ID) : false;
        $excerpt_text = $has_custom_excerpt ? $post->post_excerpt : strip_shortcodes( $content );

        if( !$has_custom_excerpt ){

            if( isset($args['first-paragraph']) && $args['first-paragraph'] == false ){
                $count = $args['count'] == '' || $args['count'] == false ? 350 : (int)$args['count'];
                $excerpt_text = strip_tags($excerpt_text);
                $excerpt_text = substr($excerpt_text, 0, $count);
                $excerpt_text = substr($excerpt_text, 0, strripos($excerpt_text, " "));
            }
            else{
                $excerpt_text = strip_tags( $excerpt_text, '<p></p>' );

                // Remove empty paragraph tags
                $excerpt_text = force_balance_tags( $excerpt_text );
                $excerpt_text = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $excerpt_text );
                $excerpt_text = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $excerpt_text );

                // Get the first paragraph
                $excerpt_text = substr( $excerpt_text, 0, strpos( $excerpt_text, '</p>' ) + 4 );

                // Remove remaining paragraph tags
                $excerpt_text = strip_tags( $excerpt_text );
            }

        }
        else{
            $excerpt_text = strip_tags( $excerpt_text );
            $excerpt_text = wpautop($excerpt_text);
        }

        $excerpt = $args['ellipsis'] == true && !$has_custom_excerpt && $excerpt_text ? '<p class="excerpt">'.$excerpt_text.'...</p>' : '<p class="excerpt">'.$excerpt_text.'</p>';

        if( $permalink && !empty($args['more_text']) ){
            $excerpt .= '<a class="read-more" href="'.esc_url($permalink).'">'.esc_html($args['more_text']).'</a>';
        }

        return $excerpt;
    }
        
}

// ----------------------------

if( !function_exists('Karri\Components\site_logo') ){

    /**
    * Custom Site Logo
    *
    * @param boolean $url Output the image path only
    * @return string Returns the HTML code or url of the image
    */

    function site_logo($url = false){
        $image = false;
        $custom_logo_id = has_custom_logo() ? get_theme_mod( 'custom_logo' ) : false;

        if($custom_logo_id == false){
            return;
        }

        if( $url ){
            $src =  wp_get_attachment_image_src( $custom_logo_id, 'full' );
            $image = $src[0] ? $src[0] : false;
        }
        else{
            $image = \Karri\Components\Media\Image::get([ 'image-id' => $custom_logo_id, 'alt' => esc_html(get_bloginfo('name')), 'class' => ' image--site-logo' ] );
        }
        return $image;
    }

}