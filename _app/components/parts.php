<?php
// ----------- Karri: Parts (Getters for page views and modules) -----------

namespace Karri\Components;

if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

class Parts{

    /**
    * Header part
    * 
    * Loads the header, wraps the template file in standardise divs and if the page is password protected, adds the form.
    *
    * @param string $classes Classes to include in the main wrapper div.
    * @return void
    */
    static function header($classes = ''){

        $post_id = _post_id();
        $title = sanitize_title( get_the_title( $post_id ) );
        $post_type = get_post_type($post_id);
        $is_template = is_page_template();
        $password_required = post_password_required( $post_id );
        $page_classes = 'wrap';
        $extra_classes = apply_filters('__wrap_classes', '');

        $page_classes .= is_404() ? ' wrap--404' : '';
        $page_classes .= $post_type === 'page' && !$is_template ? ' wrap--page' : '';
        $page_classes .= $post_type === 'page' && $is_template ? ' wrap--template-'.basename( get_page_template(), '.php' ) : '';
        $page_classes .= $post_type !== 'page' && is_singular( $post_type ) ? ' wrap--single wrap--single-'.esc_attr($post_type) : '';
        $page_classes .= $post_type && !is_home() && $post_type != 'page' ? ' wrap--'.esc_attr($post_type) : '';
        $page_classes .= has_post_thumbnail($post_id) ? ' wrap--has-featured-image' : ' wrap--no-featured-image';
        $page_classes .= $extra_classes !== '' ? ' ' . $extra_classes : '';
        $page_classes .= $classes !== '' ? ' '.esc_attr($classes) : '';

        // Add classes to the <body> tag
        add_filter( 'body_class', function( $classes ) {
            $body_classes = [];
            $body_classes[] = has_post_thumbnail(_post_id()) ? 'page--has-featured-image' : 'page--no-featured-image';

            return array_merge( $classes, $body_classes );
        } );

        get_header();
        
        if ( $password_required ){
            echo '<div class="wrap wrap--protected">';
            echo do_action('__protected_start');
            echo '<div class="inner inner--'.$post_type.'">';
            echo get_the_password_form($post_id);
            echo '</div>';
            echo do_action('__protected_end');
            echo '</div>';
        }
        else{
            echo '<div class="'.esc_attr($page_classes).'">';
            echo do_action('__wrap_start');
        }

    }

    // ----

    /**
     * Footer part
     * 
     * @return void
     */

    static function footer(){
        $password_required = post_password_required( _post_id() );

        if ( !$password_required ){
            echo do_action('__wrap_end');
            echo '</div>';
        }

        get_footer();
    }

    // ----

    /**
     * Fetch a part
     *
     * @param string $file Path of the file
     * @param array $params Pass any parameters to the file
     * @param bool $file_check Check if file exists
     * @return void
     */
    static function fetch($file, $params = [], $file_check = false){
        if( $file_check && !file_exists( $file ) ){
            return;
        }

        if( !empty($params) ){
            extract($params);
            include(locate_template( $file ) ) ;
        }
        else{
            $path = pathinfo($file);
            get_template_part( $path['dirname'] . '/' . $path['filename'] );
        }
    }

    // ----

    /**
     * View
     *
     * Get a view file
     * 
     * @param string $file Path of the file
     * @param array $params Pass any parameters to the file
     * @param bool $file_check Check if file exists
     * @return void
     */
    static function view($file, $params = [], $file_check = false){
        $file = KARRI_VIEWS['views'] . $file . '.php';
        self::fetch($file, $params, $file_check);
    }

    // ----

    /**
     * Post View
     *
     * Get a post view file. 
     * If ($file) has no file path given or given a false value, it'll look for a file with same post type in the 'views/posts' directory.
     * 
     * @param string $file Path of the file
     * @param array $params Pass any parameters to the file
     * @return void
     * 
     */
    static function post_view($file, $params = []){
        $views_dir = KARRI_VIEWS['posts'];
        $path = false;

        if( !$file ){
            $post_type = get_post_type();
            $path = file_exists( get_stylesheet_directory(  ) . '/' . $views_dir . $post_type . '.php' ) ? $views_dir . $post_type . '.php' : $views_dir . 'post.php';
            $file_check = false;
        }
        else{
            $file = _get_file_ext($file) == 'php' ? $file : $file . '.php';
            $path = $views_dir . $file;
            $file_check = false;
        }

        if( $path ){
            self::fetch($path, $params, $file_check);
        }
    }

    // ----

    /**
     * Module
     *
     * Get a module file
     * 
     * @param string $file Path of the file
     * @param array $params Pass any parameters to the file
     * @param bool $file_check Check if file exists
     * @return void
     */
    static function module($file, $params = [], $file_check = false){
        $file = KARRI_VIEWS['modules'] . $file . '.php';
        self::fetch($file, $params, $file_check);
    }

}