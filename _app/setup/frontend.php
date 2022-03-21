<?php
// ----------- Karri: Frontend -----------

namespace Karri\Setup;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

class Frontend{
    
    /**
     * Add colour palette css to the <head> tag
     *
     * @return void
     */
    function colour_palette(){
        $colours = apply_filters( 'karri-theme-colour-palette', []);
        $css = '<style id="theme-colour-palette">';

        if( !empty($colours) ){
            foreach( $colours as $colour ){
                $slug = wp_strip_all_tags($colour['slug']);
                $hex =  wp_strip_all_tags($colour['color']);

                if( !is_admin() ){
                    $css .= ".has-".$slug."-color{color:".$hex."}";
                    $css .= ".has-".$slug."-background-color{background-color:".$hex."}";
                    $css .= ".wp-block-button a.has-".$slug."-background-color{background-color:".$hex."}";
                    $css .= ".wp-block-button a:link.has-".$slug."-background-color{background-color:".$hex."}";
                    $css .= ".wp-block-button a:visited.has-".$slug."-background-color{background-color:".$hex."}";
                }
                
                if( defined('AUBE_PLUGIN_VERSION') ){
                    $css .= ".hover-".substr($hex, 1)." a:hover{background-color:".$hex." !important}";
                    $css .= ".hover-".substr($hex, 1)." a:focus{background-color:".$hex." !important}";
                    $css .= ".text-colour-".substr($hex, 1)."{color:".$hex."}";
                    $css .= ".background-colour-".substr($hex, 1)."{background-color:".$hex."}";
                }
            }
            $css .= '</style>';
            
            echo $css;
        }
    }

    /**
     * Alter the login's logo link url and text
     *
     * @return void
     */
    function login_header(){
        add_filter( 'login_headerurl', function(){
            return esc_url( get_bloginfo('url') );
        } );
    
        add_filter( 'login_headertext', function(){
            return esc_html( get_bloginfo('name') );
        } );
    }

    function __construct(){
        $this->login_header();

        add_action('wp_head', [$this, 'colour_palette']);
        add_action('admin_head', [$this, 'colour_palette']);
        
    }

}

new Frontend;