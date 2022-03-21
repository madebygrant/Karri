<?php
// ----------- Karri: Scripts -----------

namespace Karri\Setup;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

/**
 * Enqueue stylesheets and scripts
 *
 * @return void
 */
function enqueue() {

    if(is_admin()){
        return false;
    }

    // Enqueue Scripts
    new \Karri\Enqueue\Controller( _config_options('karri-theme-scripts'), 'scripts' );

    // Enqueue Stylesheets
    new \Karri\Enqueue\Controller( _config_options('karri-theme-styles'), 'styles' );

    // Enqueue Contact Form 7 stylesheets & scripts selectively, only where the shortcode is apparent
    if( defined('WPCF7_VERSION') ){
        \Karri\Setup\Vendors\CF7\enqueue();
    }

}

add_action( 'wp_enqueue_scripts', 'Karri\Setup\enqueue' );

// ----------------------------

/**
 * Enqueue 'Block Editor' stylesheets and scripts
 *
 * @return void
 */
function block_editor_enqueue() {
    
    $enqueue = _config_options('karri-editor-enqueue');

    if(isset($enqueue['js'])){
        wp_enqueue_script('theme-blocks', get_theme_file_uri($enqueue['js']), [ 'wp-blocks', 'wp-dom' ], THEME_VERSION, true );
    }
    if(isset($enqueue['css'])){
        wp_enqueue_style('theme-blocks', get_theme_file_uri($enqueue['css']), [ 'wp-edit-blocks' ], THEME_VERSION );
    }
    
}
add_action( 'enqueue_block_editor_assets', 'Karri\Setup\block_editor_enqueue' );

// ----------------------------

/**
 * Enqueue 'Dawn' scripts
 * 
 * @return void
 */
function dawn(){
    if ( !is_admin() ) {
        wp_enqueue_script('dawn', get_theme_file_uri('_app/assets/dawn/dawn.min.js'), [], 0.2, false );
    };
}
add_action( 'wp_enqueue_scripts', 'Karri\Setup\dawn', 1);

// ----------------------------

/**
 * Preload font stylesheets from Adobe (typekit) & Google Fonts
 *
 * @return void
 */
function preload_styles(){
    global $wp_styles;

    foreach( $wp_styles->queue as $handle ) {
        $src = isset($wp_styles->registered[$handle]->src) ? $wp_styles->registered[$handle]->src : false;
        $handle = isset($wp_styles->registered[$handle]->handle) ? $wp_styles->registered[$handle]->handle : false;
        
        if( $handle === 'google-fonts' ){
            echo '<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>';
        }
        if( $handle === 'adobe-fonts' ){
            echo '<link rel="preload" href="'.esc_url($src).'" as="style" crossorigin>';
        }
        
    }
}
add_action('wp_head', 'Karri\Setup\preload_styles', 1);