<?php
// ----------- Karri: Media -----------

namespace Karri\Components\Media;

if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

/**
 * Image Class
 * 
 * Get images for the theme, featured or by attachment id
 */

class Image{

    /**
     * Output the image
     *
     * @param array $args
     * @return void
     */
    private static function output($args){
        $result = [];

        if(\is_bool($args['featured']) && $args['featured']){
            $post_id = $args['post-id'] != null ? $args['post-id'] : \_post_id();
            $image_id = get_post_thumbnail_id( $post_id );
        }
        else if($args['image-id'] != null){
            $image_id = $args['image-id'];
        }
        else{
            return;
        }
        
        $attrs = [ 
            'class' => 'image image--responsive'.$args['class'], 
            'srcset' => wp_get_attachment_image_srcset($image_id, $args['size']),
            'sizes' => wp_calculate_image_sizes($args['size'], null, null, $image_id)
        ];

        if($args['alt']){
            $get_alt = get_post_meta($image_id, '_wp_attachment_image_alt', 1);
            $attrs['alt'] = $get_alt ? esc_html($get_alt) : "";
        }

        $result['html'] = wp_get_attachment_image( $image_id, $args['size'], false, $attrs );
        $result['src'] = wp_get_attachment_image_src( $image_id, $args['size'] )[0];

        return $result;
    }

    /**
     * Get the image data
     *
     * @param int $image_id
     * @return void
     */
    public static function data($image_id){
        $attachment = get_post( $image_id );
        return array(
            'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
            'caption' => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'href' => get_permalink( $attachment->ID ),
            'src' => $attachment->guid,
            'title' => $attachment->post_title
        );
    }

    /**
     * Get the image
     *
     * @param [type] $args
     * @return void
     */
    public static function get( $args ){

        $default_args = ['post-id' => null, 'image-id' => null, 'size' => THEME_PREFIX.'-featured-hero', 'alt' => true, 'title' => false, 'class' => '', 'featured' => true, 'output' => 'html'];
        $args = _merge_arrays($default_args, $args);

        $args['featured'] = $args['image-id'] === null ?: false;

        // If a featured image
        if( is_bool($args['featured']) && $args['featured'] ){

            // If in the loop
            if( (is_main_query() || in_the_loop()) && empty($args['post-id']) && $args['output'] == 'html' ){
                $img_alt = $args['title'] ? get_the_title() : ( $args['alt'] ? get_post_meta(get_post_thumbnail_id( get_the_ID() ), '_wp_attachment_image_alt', true) : '' );

                the_post_thumbnail($args['size'], [ 'title' => '', 'alt' => esc_attr($img_alt), 'class' => 'image image--responsive' . $args['class']] );
            }
            else{
                $output = self::output($args);
                return $args['output'] == 'src' ? $output['src'] : $output['html'];
            }

        }

        // Any image uploaded into WordPress, just needs the image id.
        else{
            $output = self::output($args);
            return $args['output'] == 'src' ? $output['src'] : $output['html'];
        }
    }

}

// ----------------------------

if( !function_exists('Karri\Media\resize_dimensions') ){

    /**
    * Returns new resized dimensions based on existing dimensions
    *
    * @param int $newWidth Give the new width
    * @param array $based [ width, height ] Give the existing dimensions
    * @return array Returns an array of the new, resized dimensions
    */

    function resize_dimensions($newWidth, $based){
        if( is_int($newWidth) && is_int($based[0]) && is_int($based[1]) ){
            $dimensions = [];
            $ratio = ($newWidth / $based[0]);
            $dimensions[] = $newWidth;
            $dimensions[] = round($based[1] * $ratio);
            return $dimensions;
        }
        return false;
    };

}

// ----------------------------

/**
 * Register Image sizes
 */ 
if ( function_exists( 'add_theme_support' ) ) {

    $image_sizes = _config_options('karri-theme-image-sizes');

    // Hero image sizes
    if( isset( $image_sizes['hero'] ) && !empty($image_sizes['hero']) ){
        foreach( $image_sizes['hero'] as $key => $data ){
            add_image_size( THEME_PREFIX.'-hero-'.esc_html($key), $data['dims'][0], $data['dims'][1], $data['crop']);
            // ? Example: add_image_size( THEME_PREFIX.'-hero-xlarge', 1920, 670, true);
        }
    }

    // Other image sizes
    if( isset( $image_sizes['other'] ) && !empty($image_sizes['other']) ){
        foreach( $image_sizes['other'] as $key => $data ){
            add_image_size( THEME_PREFIX.'-image-'.esc_html($key), $data['dims'][0], $data['dims'][1], $data['crop']);
            // ? Example: add_image_size( THEME_PREFIX.'-image-square', 600, 600, true);
        }
    }

}