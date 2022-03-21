<?php
// ----------- Karri: CMB2 -----------

namespace Karri\Setup\Vendors\CMB2;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// Exit if CMB2 is not defined
if ( !defined('CMB2_LOADED') ) return;

// ----------------------------

// -- Load CSS & JS files

if( !class_exists('Karri\Setup\Vendors\CMB2\Assets') ){

    class Assets{

        function scripts(){
            if( is_admin() ){
                wp_enqueue_script('jquery-ui-tabs');
                wp_enqueue_script('karri-cmb2', get_theme_file_uri('_app/assets/cmb2/cmb2.karri.min.js'), [], '0.1', false );
                wp_enqueue_style('karri-cmb2', get_theme_file_uri('_app/assets/cmb2/cmb2.karri.min.css'), [], '0.1', false );
            }
        }

        function __construct(){
            add_action( 'cmb2_after_form', [$this, 'scripts'] , 10, 4 );
        }
    }

    new Assets;
}

// ----------------------------

// -- Add custom fields

if( !class_exists('Karri\Setup\Vendors\CMB2\Fields') ){

    class Fields{

        // Tabs
        function add_tabs( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
            echo $field_type_object->title( array( 'classes' => 'cmb2-tab' ) );
        }
        
        function add_group_tabs( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
            echo $field_type_object->title( array( 'classes' => 'cmb2-group-tab' ) );
        }

        // Switch - Credit: https://github.com/improy/CMB2-Switch-Button-Metafield
        function add_switch( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
            $confirm_class = '';
            $confirm_item = '';
            
            if( isset($field->args['confirm']) ){ 
                $confirm_class = " confirm"; 
                $confirm_item = $field->args['confirm'];
            }
            else if( isset($field->args['toggle'])){ 
                $confirm_class = " toggle"; 
                $confirm_item = $field->args['toggle'];
            }
            else if( isset($field->args['tab']) ){ 
                $confirm_class = " tab"; 
                $confirm_item = $field->args['tab'];
            }
            else if( isset($field->args['field']) ){ 
                $confirm_class = " field"; 
                $confirm_item = $field->args['field'];
            }
        
            $conditional_value = ( isset($field->args['attributes']['data-conditional-value']) ? 'data-conditional-value="' .esc_attr($field->args['attributes']['data-conditional-value']) .'"' : '' );
            $conditional_id = ( isset($field->args['attributes']['data-conditional-id']) ? ' data-conditional-id="'.esc_attr($field->args['attributes']['data-conditional-id']) .'"' : '' );
            $label_on = ( isset($field->args['label']) ? esc_attr($field->args['label']['on']) : 'On' );
            $label_off = ( isset($field->args['label']) ? esc_attr($field->args['label']['off']) : 'Off' );
            
            $switch = '<div class="cmb2-switch'.$confirm_class.'" data-confirm="'.esc_attr($confirm_item).'">';
            $switch .= '
            <input class="switch-true" '.$conditional_value.$conditional_id.' type="radio" id="' . $field->args['_id'] . '1" value="1"  '. ($escaped_value == 1 ? 'checked="checked"' : '') . ' name="' . esc_attr($field->args['_name']) . '" />
            <input class="switch-false" '.$conditional_value.$conditional_id.' type="radio" id="' . $field->args['_id'] . '2" value="0" '. (( $escaped_value == '' || $escaped_value == 0) ? 'checked="checked"' : '') . ' name="' . esc_attr($field->args['_name']) . '" />
            <label for="' . $field->args['_id'] . '1" class="cmb2-enable '.($escaped_value == 1?'selected':'').'"><span>'.$label_on.'</span></label>
            <label for="' . $field->args['_id'] . '2" class="cmb2-disable '.(($escaped_value == '' || $escaped_value == 0) ? 'selected': '').'"><span>'.$label_off.'</span></label>';
            $switch .= '</div>';
            $switch .= $field_type_object->_desc( true );
            
            echo $switch;
        
            if( isset($field->args['confirm']) || isset($field->args['toggle']) || isset($field->args['tab']) ){ 
                echo "<input class='switch-confirm' name='".esc_attr($confirm_item)."_confirmed' ". (( $escaped_value == '' || $escaped_value == 0) ? 'value="0"' : 'value="1"') . " type='hidden' />";
            }
        
        }
        
        function __construct(){
            add_action( 'cmb2_render_tab', [$this, 'add_tabs'], 10, 5 );
            add_action( 'cmb2_render_group_tab', [$this, 'add_group_tabs'], 10, 5 );
            add_action( 'cmb2_render_switch', [$this, 'add_switch'], 10, 5 );
        }
    }

    new Fields;

}

// ----------------------------

// -- Helper methods

class Helpers{

    /**
     * Get specific posts' data
     *
     * @param array $query_args
     * @return array
     */
    static function get_post_options( $query_args ) {
        $post_options = [];
    
        $args = wp_parse_args( $query_args, [
            'post_type'   => 'post',
            'numberposts' => -1,
        ] );
        $posts = get_posts( $args );
        
        if ( $posts ) {
            foreach ( $posts as $post ) {
                $post_options[ $post->ID ] = $post->post_title;
            }
        }
        return $post_options;
    }

    /**
     * Gets list of all posts and displays them as options
     * 
     * @return array An array of options that matches the CMB2 options array
     */
    static function post_list() {
        return self::get_post_options( [ 'post_type' => 'post', 'orderby' => 'title', 'order' => 'ASC' ] );
    }

    /**
     * Gets list of all pages and displays them as options
     * 
     * @return array An array of options that matches the CMB2 options array
     */
    static function page_list() {
        return self::get_post_options( [ 'post_type' => 'page', 'orderby' => 'title', 'order' => 'ASC' ] );
    }

    /**
     * Gets list of all post types and displays them as options
     *
     * @return array An array of options that matches the CMB2 options array
     */
    static function post_type_list(){
        $get_types = self::get_post_types( ['public' => true, '_builtin' => false], 'objects');
        
        $types = [ 'page' => 'Pages', 'post' => 'Posts' ];
        foreach( $get_types as $type ){
            $types[$type->name] = $type->label;
        }
    
        return $types;
    }

    /**
     * Get the list of terms
     *
     * @param string $taxonomy
     * @return array
     */
    static function get_term_list( $taxonomy ) {
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);
        $terms_options = [];
        if ( $terms) {
            foreach ( $terms as $t ) {
                $terms_options[ $t->term_id ] = $t->name;
            }
        }
        return $terms_options;
    }

    /**
     * Exclude specific templates using the meta box
     * 
     * @param array $excluded
     * @return bool
     */
    static function exclude_templates($excluded) {
        $page_template = basename( get_page_template() );

        if ( in_array($page_template, $excluded) ) {
            return false;
        }

        return true;
    }

    /**
     * Determine if it's the posts (Blog) page
     *
     * @return boolean
     */
    static function is_posts_page(){
        $post_id = isset( $_GET['post'] ) ? $_GET['post'] : '';
        return $post_id === get_option( 'page_for_posts' );
    }

}

