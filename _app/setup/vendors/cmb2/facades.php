<?php
// ----------- Karri: CMB2 Facades -----------

namespace Karri;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

class CMB2{

    /**
     * Get specific posts' data
     *
     * @param array $query_args
     * @return array
     */
    static function get_post_options($args){
        return \Karri\Setup\Vendors\CMB2\Helpers::page_list($args);
    }
    
    /**
     * Gets list of all posts and displays them as options
     * 
     * @return array An array of options that matches the CMB2 options array
     */
    static function get_post_list(){
        return \Karri\Setup\Vendors\CMB2\Helpers::post_list();
    }

    /**
     * Gets list of all post types and displays them as options
     *
     * @return array An array of options that matches the CMB2 options array
     */
    static function get_post_type_list(){
        return \Karri\Setup\Vendors\CMB2\Helpers::post_type_list();
    }
    
    /**
     * Gets list of all pages and displays them as options
     * 
     * @return array An array of options that matches the CMB2 options array
     */
    static function get_page_list(){
        return \Karri\Setup\Vendors\CMB2\Helpers::page_list();
    }

    /**
     * Get the list of terms
     *
     * @param string $taxonomy
     * @return array
     */
    static function get_term_list($taxonomy){
        return \Karri\Setup\Vendors\CMB2\Helpers::get_term_list($taxonomy);
    }

    /**
     * Exclude specific templates using the meta box
     * 
     * @param array $excluded
     * @return bool
     */
    static function exclude_templates($excluded){
        return \Karri\Setup\Vendors\CMB2\Helpers::exclude_templates($excluded);
    }

    /**
     * Determine if it's the posts (Blog) page
     *
     * @return boolean
     */
    static function is_posts_page(){
        return \Karri\Setup\Vendors\CMB2\Helpers::is_posts_page();
    }

}