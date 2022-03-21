<?php
// ----------- Karri: Theme Supports -----------

namespace Karri\Setup;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

if( !function_exists('Karri\Setup\supports') ){

    /**
     * Add Theme Supports
     *
     * @return void
     */
    function supports() {

        // Enable HTML 5 markup
        add_theme_support( 'html5', [ 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form', 'script', 'style' ] );

        // Enables post and comment RSS feed links to head
        //add_theme_support( 'automatic-feed-links' );

        // Let plugins manage document titles
        add_theme_support( 'title-tag' );

        // Set colour palette presets
        add_theme_support( 'editor-color-palette', apply_filters( 'karri-theme-colour-palette', []) ); 

        // Set gradient presets
        add_theme_support('editor-gradient-presets', []);

        // Enable relative length units
        add_theme_support('custom-units');

        // Enable custom line heights
        add_theme_support('custom-line-height');

        // Enable custom spacing
        add_theme_support('custom-spacing');

        // Enable responsive Embeds
        add_theme_support( 'responsive-embeds' );

        // Enable post thumbnails aka Featured Images
        add_theme_support( 'post-thumbnails' );

        // Enable wide Alignment
        add_theme_support( 'align-wide' );

        // Selective refresh for widgets in customizer
        add_theme_support('customize-selective-refresh-widgets');

        // Disable Custom Colours
        add_theme_support( 'disable-custom-colors' );

        // Disable custom gradients
        add_theme_support('disable-custom-gradients');

        // Disable custom font sizes
        add_theme_support('disable-custom-font-sizes');

        // Remove default block patterns
        remove_theme_support('core-block-patterns');

    }
    
    add_action( 'after_setup_theme', 'Karri\Setup\supports' );

}