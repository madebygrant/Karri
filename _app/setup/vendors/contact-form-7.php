<?php
// ----------- Karri: Contact Form 7 -----------

namespace Karri\Setup\Vendors\CF7;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

// Don't continue with the autoloading if disabled
if( apply_filters( 'karri-theme-cf7-scripts-autoload', true) === false ){
    return;
}

// ----------------------------

// Disable 'Contact Form 7' plugin (v3.9+) loading CSS & JS files on all pages
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );

// ----------------------------

// Stop CF7 automatically adding <p> tags to the forms
add_filter('wpcf7_autop_or_not', '__return_false');

// ----------------------------

/**
 * Enqueue Contact Form 7 stylesheets & scripts selectively, only where the shortcode is apparent
 *
 * @return void
 */

function enqueue(){

    if( is_404() ){
        return;
    }

    $post = get_post();

    if( !empty($post)){

        $merged_data = [];
        //$theme_mods = get_theme_mods();
        $post_meta = get_post_meta($post->ID);
        $has_shortcode_meta = false;

        //Add theme mods
        /*
        if( is_array($theme_mods) ){
            $merged_data = _merge_arrays($merged_data, $theme_mods);
        }
        */

        // Add post meta
        if( is_array($post_meta) && !empty($post_meta) ){
            $merged_data = _merge_arrays($merged_data, $post_meta);
        }

        // If in the post meta or theme mods
        if( !empty($merged_data) ){
            foreach( $merged_data as $data ){
                if( !is_array($data) && has_shortcode( $data, 'contact-form-7') && $has_shortcode_meta === false ){
                    $has_shortcode_meta = true;
                }
            }
        }

        // Check the content or $has_shortcode_meta is true
        if( has_shortcode($post->post_content, 'contact-form-7') || $has_shortcode_meta ) {

            if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
                wpcf7_enqueue_scripts();
            }

            if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
                wpcf7_enqueue_styles();
            }

            // Add a class to the <body> tag, indicating CF7 is used in the page.
            add_filter( 'body_class', function($classes){
                $classes[] = 'page--cf7';
                return $classes;
            } );
        }

    }
}