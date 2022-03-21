<?php
// ----------- Karri: Facades -----------

namespace Karri;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------


/**
 * Theme facade
 */

class Theme{

    /**
     * Add a module file
     *
     * @param string $file
     * @param array $params
     * @param boolean $file_check
     * @return void
     */
    static function module($file, $params = [], $file_check = false){
        \Karri\Components\Parts::module($file, $params, $file_check);
    }

    /**
     * Add a view file
     *
     * @param string $file
     * @param array $params
     * @param boolean $file_check
     * @return void
     */
    static function view($file, $params = [], $file_check = false){
        \Karri\Components\Parts::view($file, $params, $file_check);
    }


    /**
     * Add a post view file
     *
     * @param mixed $file
     * @param array $params
     * @return void
     */
    static function post_view($file, $params = []){
        \Karri\Components\Parts::post_view($file, $params);
    }

    /**
     * Add an image
     *
     * @param array $args
     * @return void
     */
    static function image($args = []){
        return \Karri\Components\Media\Image::get($args);
    }

    /**
     * Output the site logo
     *
     * @param boolean $url If true, output the logo's url
     * @return void
     */
    static function site_logo($url = false){
        return \Karri\Components\site_logo($url);
    }

    /**
     * Output pagination links
     *
     * @param object $paged
     * @param array $args
     * @return void
     */
    static function pagination($paged, $args = []){
        return \Karri\Components\pagination($paged, $args);
    }

    /**
     * Output social media buttons list
     *
     * @param array $args
     * @return void
     */
    static function social_buttons_list($args = []){
        return \Karri\Components\SocialMedia\button_list($args);
    }

    /**
     * Output the excerpt
     *
     * @param array $args
     * @return void
     */
    static function excerpt($args = []){
        return \Karri\Components\excerpt($args);
    }

    /**
     * Add the page's header
     *
     * @param string $classes
     * @return void
     */
    static function header($classes = ''){
        \Karri\Components\Parts::header($classes);
    }

    /**
     * Add the page's footer
     *
     * @return void
     */
    static function footer(){
        \Karri\Components\Parts::footer();
    }

    /**
     * Initialise a Customiser instance
     *
     * @param object $wp_customize
     * @return void
     */
    static function customizer($wp_customize){
        return new \Karri\Components\Customizer\Options($wp_customize);
    }

    /**
     * Initialise a Theme Options Page instance
     *
     * @param array $args
     * @return void
     */
    static function options_page($args = []){
        return new \Karri\Components\ThemeOptions\Page($args);
    }

    /**
     * Render a Karri slider
     *
     * @param string The id for sidebar meta being used
     * @param array $args
     * @return void
     */
    static function slider($meta_id = '', $args = []){
        $meta_id = $meta_id === '' ? '_page_slider_meta' : $meta_id;
        \Karri\Components\Slider\render($meta_id, $args);
    }
}

/**
 * Admin facade
 */

class Admin{

    /**
     * Add a column displaying featured images
     * 
     * @return void
     */
    static function column_featured_image($args = ['post_type' => 'post', 'title' => 'Image']){
        return new \Karri\Components\Admin\FeaturedImageColumn($args);
    }

    /**
     * Add column displaying attached page template to the page
     *
     * @return void
     */
    static function column_page_template(){
        return new \Karri\Components\Admin\PageTemplateColumn();
    }

    /**
     * Add a drop-down filter for a taxonomy in the posts admin screen
     *
     * @param [type] $post_type
     * @param [type] $taxonomy
     * @return void
     */
    static function filter_taxonomy($post_type, $taxonomy){
        return new \Karri\Components\Admin\TaxonomyFilter($post_type, $taxonomy);
    }

    /**
     * Add a drop-down filter for meta field in the posts admin screen
     *
     * @param [type] $meta_key
     * @param [type] $dropdown_label
     * @param string $type
     * @param string $post_status
     * @return void
     */
    static function filter_meta($meta_key, $dropdown_label, $type = 'post', $post_status = 'all'){
        return new \Karri\Components\Admin\MetaFilter($meta_key, $dropdown_label, $type, $post_status);
    }
}