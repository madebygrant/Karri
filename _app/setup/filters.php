<?php
// ----------- Karri: Filters -----------

namespace Karri\Setup;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

/**
 * Enable editor on the posts page
 */

if( apply_filters( 'karri-theme-posts-page-editor', false) ){
    
    add_filter( 'admin_footer_text', function($post){
        if ($post->ID === get_option('page_for_posts')){
            add_post_type_support( 'page', 'editor' );
        }
    });

};

// ----------------------------

/**
 * Add a front page menu link under the 'Pages' menu within the Admin menu
 */

if( apply_filters( 'karri-theme-front-page-admin-menu', true) ){

    add_action( 'admin_menu' , function(){
        global $submenu;
        if ( get_option( 'page_on_front' ) ) {
            $submenu['edit.php?post_type=page'][501] = [
                __( 'Front Page', 'karri' ), 
                'manage_options', 
                get_edit_post_link( get_option( 'page_on_front' ) )
            ]; 
        }
    } );

}

// ----------------------------

/**
 * Display post state message
 */

add_filter( 'display_post_states', function($post_states, $post){
    $get_template = basename( get_post_meta($post->ID, '_wp_page_template', 1) );
    $page_states = _config_options('karri-theme-page-template-states');

    if($page_states){
        foreach( $page_states as $template => $message ){
            if($get_template == $template){
                $post_states[] =  esc_html($message);
            }
        }
        return $post_states;
    }

}, 10, 2 );