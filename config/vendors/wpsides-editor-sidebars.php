<?php
// ----------- Karri: Custom sidebars for Gutenberg Editor -----------
// Requires the 'WP Sides' plugin to be activated.

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// Don't load if the 'WP Sides' plugin is not activated
if( !defined( 'WPSIDES_PLUGIN_VERSION' ) ) return false;

// ----------------------------

// -- Register the custom sidebar JavaScript files here.

add_action( 'enqueue_block_editor_assets', function(){
    // Slider sidebar
    /*
    wp_enqueue_script( 'draggable', 'https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.12/lib/draggable.bundle.js', ['wpsides'], THEME_VERSION, true );
    wp_enqueue_script( 'sidebar-slider-editor', get_theme_file_uri( '/assets/js/sidebars/slider/editor.js' ), ['wpsides'], THEME_VERSION, true );
    wp_enqueue_script( 'sidebar-slider', get_theme_file_uri( '/assets/js/sidebars/slider.js' ), ['wpsides'], THEME_VERSION, true );
    */

    // Sidebars
    //wp_enqueue_script( 'sidebar-sample', get_theme_file_uri( '/assets/js/sidebars/sample.js' ), ['wpsides'], THEME_VERSION, true );
    //wp_enqueue_script( 'sidebar-sample-document', get_theme_file_uri( '/assets/js/sidebars/sample-document.js' ), ['wpsides'], THEME_VERSION, true );
});

// ----------------------------

// -- Register the meta for the sidebar data to be saved into.
// Underscore prefix is required for the key when registering! In order to have changes to saved in the meta field.

add_action('init', function() {
    
    /*
    register_meta(
        'post', 
        '_page_sidebar_meta', // Underscore prefix required!
        [
            'type' => 'string',
            'object_subtype' => 'page',
            'show_in_rest' => true,
            'single' => true,
            'auth_callback' => function(){
                return current_user_can('edit_posts');
            }
        ]
    );
    */

    
    register_meta(
        'post', 
        '_page_slider_meta', // Underscore prefix required!
        [
            'type' => 'string',
            'object_subtype' => 'page',
            'show_in_rest' => true,
            'single' => true,
            'auth_callback' => function(){
                return current_user_can('edit_posts');
            }
        ]
    );
    

});