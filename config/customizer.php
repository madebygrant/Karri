<?php
// ----------- Karri: Configuration - Theme Customizer -----------

namespace Karri\Config;

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

/*
    List of pre-made functions to add controls to Customizer:

    --  Add a panel
        $kc->panel($panel_id, $title, $priorty = '', $description = '');

    --  Add a section
        $kc->section($section_id, $title, $priorty = '', $description = '', $panel_id = '');

    --  Add social media
        $kc->social_media($args);

    --  Add a header
        $kc->header($settings_id, $label, $section, $description = '');

    --  Add a spacer
        $kc->spacer($settings_id, $section);

    --  Add a WordPress built-in control
        $kc->standard_option($settings_id, $label, $section, $type, [ 'choices' => [], 'description' => '', 'default_value' => '', 'sanitize_callback' => 'sanitize_text_field' ]);

        Type ($type):
            - text (default)
            - checkbox
            - radio (requires array in 'choices')
            - select (requires array in 'choices')
            - dropdown-pages
            - textarea (since WordPress 4.0)

        For the most updated list of types visit: https://codex.wordpress.org/Class_Reference/WP_Customize_Control
        To allow HTML tags, use '__sanitize_html' in the ''$sanitize_callback' parameter.

    --  Add a Colour Picker option
        $kc->colour_picker_option($settings_id, $label, $section, [ 'description' => '', 'default_value' => '' ]);

    --  Add a TinyMCE Editor control
        $kc->text_editor_option($settings_id, $label, $section, [ 'description' => '', 'default_value' => '' ]);

    --  Add a Media Upload control
        $kc->media_upload_option($settings_id, $label, $section, [ 'description' => '', 'default_value' => '' ]);
        For the image meta data, add '_data' to the end of id when using 'get_theme_mod()'. Example: get_theme_mod('settings_id_data');

    --  Add a Post / Page ID select control
        $kc->post_select_option($settings_id, $label, $section, [ 'post_type' => ['post'], 'description' => '', 'default_value' => '' ]);

        It can also show posts from multiple post types.

    --  Add a Toggle Switch (If checked, it'll return the value of 'true')
        $kc->toggle_option($settings_id, $label, $section, $description='');
*/

// ----------------------------

/** 
 * Customizer Setup 
 */ 

if( !function_exists('Karri\Config\customizer') ){

    function customizer( $wp_customize ) {

        // -- Intialise
        $kc = Theme::customizer($wp_customize);

        // -- Customizer Tweaks

        $wp_customize->remove_section('colors'); // Remove the 'Colors' item
        $wp_customize->remove_section('static_front_page'); // Remove the 'Static Front Page' item
        $wp_customize->remove_control('blogdescription'); // Remove Tagline

        // Reorder some sections
        $wp_customize->get_section( 'title_tagline' )->priority = 30;
        $wp_customize->get_section( 'header_image' )->priority = 31;

        // ----

        // -- Panels & Sections

        //$kc->section('footer_section', __( 'Footer', 'karri' ), 35);

        // ----

        // -- Settings & Controls

        //  Footer

        // Footer Text
        //$kc->text_editor_option(PREFIX_.'footer_text', __('Footer Text' , 'karri'), 'footer_section');

        // ----

        // -- Social Media
        // Defaults: ['section' => 'social_section', 'priorty' => 33, 'labels' => false]

        $kc->social_media();
          
    }

    add_action( 'customize_register', 'Karri\Config\customizer', 20 );

}

// ----------------------------

// -- Customizer Tweaks

// Enable WordPress's Custom Header feature
/*
add_theme_support('custom-header', [
    'width'         => '',
    'height'        => '',
    'flex-width'    => true,
    'flex-height'    => true,
    'random-default' => false,
    'header-text' => false
]);
*/

// Enable WordPress's Custom Logo feature
add_theme_support('custom-logo', [
    'width'       => '',
    'height'      => '',
    'flex-height' => true,
    'flex-width' => true,
    'header-text' => false
]);
