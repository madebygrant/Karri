<?php
// ----------- Karri: CMB2 Meta Boxes -----------

namespace Karri\Metaboxes\CMB2;

use \Karri\CMB2 as CMB2;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

/*
    // List of CMB2 field types: https://github.com/CMB2/CMB2/wiki/Field-types

    ----

    // -- Posts, Pages, Terms or Post Type Lists
    To include list to the fields: 'select', 'multicheck' and 'radio':
    - Posts, add parameter: 'options_cb' => 'CMB2::post_list'
    - Pages, add parameter: 'options_cb' => 'CMB2::page_list'
    - Post types, add parameter: 'options_cb' => 'CMB2::post_type_list'
    - Terms, add parameter: 'options_cb' => 'CMB2::get_term_list'

    // -- Custom Post Type List
    Create a function like below:

    function cpt_list() {
        return CMB2::get_post_options( [ 'post_type' => 'post_type_here' ] );
    }

    Then include it as an 'option_cb' parameter to a field:
    'options_cb' => 'Karri\Metaboxes\CMB2\cpt_list'

    // -- Custom Terms List
    Create a function like below:

    function custom_terms_list() {
        return CMB2::get_term_options( 'your_taxonomy_here' );
    }

    Then include it as an 'option_cb' parameter to a field:
    'options_cb' => 'Karri\Metaboxes\CMB2\custom_terms_list'

    ----

    // -- Custom CMB2 Meta Box Fields

    // Toggle Switch

    $mb->add_field([
        'name' => __('Confirm', 'karri'),
        //'desc' => __('', 'karri'),	
        'id' => PREFIX_ . 'switch_test',
        'type' => 'switch',
        'default' => 0,
        'label' => [ 'on' => 'Yes', 'off' => 'No' ], // Default: On, Off
    ]);

    Switch can disable a field or hide sections that are tabbed:

        - 'confirm' => 'ID of the field here' 			// Makes the field read only or not.
        - 'tab' => 'cmb2-metabox-dcp_metabox-tab-1' 	// Enter the HTML 'id' attribute for the tabbed section to toggle. Hint: it's the url in the tab's 'href' attribute and don't include the #.

*/

// ----------------------------

function register() {

    // Get post ID
    $post_id = isset( $_GET['post'] ) ? $_GET['post'] : '';

	// Get page template
	$page_template = basename( get_post_meta( $post_id, '_wp_page_template', true ) );

    // ----

    /*
    // -- Meta Box Example

	$mb = new_cmb2_box([
		'id'            => PREFIX_.'metabox_1',
		'title'         => __( 'Page Options', 'karri' ),
        'object_types'  => ['page' ], // Post type
        //'show_on_cb'    => 'CMB2::excluded_templates',
        //'show_on'       => [ 'key' => 'page-template', 'value' => 'page-templates/front.php' ],
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
	]);

	// Tab
	$mb->add_field([
        'name' => __( 'Tab Name Here', 'karri'),
        'id'   => 'tab_1',
		'type' => 'tab'
	]);

	// Field: Text
	$mb->add_field([
		'name' => __( 'Field name here', 'karri'),
        //'desc' => __( '', 'karri'),
        'id' => PREFIX_.'your_field_id_here',
		'type' => 'text'
    ]);

    // Field: Toggle Switch
    $mb->add_field([
        'name' => __('Confirm', 'karri'),
        'id' => PREFIX_ . 'test_switch',
        //'desc' => __('', 'karri'),	    
        'type' => 'switch',
        'default' => 0,
        'label' => [ 'on'=> 'Yes', 'off'=> 'No' ], // Default: On, Off
    ]);

    // Tab
	$mb->add_field([
        'name' => __( 'Tab Name Here', 'karri'),
        'id'   => 'tab_2',
		'type' => 'tab'
	]);

    // Field: Select w/ List of Pages
    $mb->add_field([
        'name'             => 'Test Select',
        'desc'             => __('Select an option', 'karri'),
        'id'               => PREFIX_ . 'test_select',
        'type'             => 'select',
        'show_option_none' => true,
        'options_cb'       => 'Papa\Vendors\CMB2\page_list'
    ]);
    */

}

//add_action( 'cmb2_admin_init', 'Karri\Metaboxes\CMB2\register' );