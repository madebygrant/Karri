<?php
// ----------- Karri: Customizer -----------

namespace Karri\Components\Customizer;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

if( !class_exists('Karri\Components\Customizer\Options') ){

    class Options{

        public $is_wp_customize;
        public $total_editors;

        // ----

        // Panels & Sections Functions

        function panel($panel_id, $title, $priorty = '', $description = ''){
            $wp_customize = $this->is_wp_customize;
            $wp_customize->add_panel( $panel_id , [
                'title'       => $title,
                'priority'    => $priorty,
                'description' => $description
            ] );
        }

        function section($section_id, $title, $priorty = '', $description = '', $panel_id = ''){
            $wp_customize = $this->is_wp_customize;
            $wp_customize->add_section( $section_id , [
                'title'       => $title,
                'priority'    => $priorty,
                'description' => $description,
                'panel'       => $panel_id
            ] );
        }


        // ----

        // Custom Customizer Option Functions

        /**
        * Add a WordPress in-built customizer option
        *
        * For a list of current types: https://codex.wordpress.org/Class_Reference/WP_Customize_Control
        *
        * @param string $settings_id Unique ID
        * @param string $label Name of the option
        * @param string $section Add the ID of the section to apply it to
        * @param string $type Type of option
        * @param array $args [ 'choices' => [], 'description' => '', 'default_value' => '', 'sanitize_callback' => 'sanitize_text_field' ]
        */

        function standard_option($settings_id, $label, $section, $type, $args =[] ){

            $default_args = [ 'choices' => [], 'description' => '', 'default_value' => '', 'sanitize_callback' => 'sanitize_text_field' ];
            $args = _merge_arrays($default_args, $args);

            $wp_customize = $this->is_wp_customize;
            $sanitize = $type === 'checkbox' ? '__sanitize_checkbox' : ( $type === 'dropdown-pages' ? 'absint' : $args['sanitize_callback'] );
            $choices = is_array($args['choices']) && !empty($args['choices']) ? $args['choices'] : false ;

            $wp_customize->add_setting( $settings_id, [
                'default'=> $args['default_value'],
                'sanitize_callback' => $sanitize,
            ]);
            $wp_customize->add_control($settings_id.'-control', [
                'label'   => __($label, 'karri'),
                'description' => __($args['description'], 'karri'),
                'section' => $section,
                'settings' => $settings_id,
                'type'    => $type,
                'choices' => $choices
            ]);
        }

        /**
        * Add Colour Picker option
        *
        * @param string $settings_id Unique ID
        * @param string $label Name of the option
        * @param string $section Add the ID of the section to apply it to
        * @param array $args [ 'description' => '', 'default_value' => '' ]
        */

        function colour_picker_option($settings_id, $label, $section, $args = []){

            $default_args = [ 'description' => '', 'default_value' => '' ];
            $args = _merge_arrays($default_args, $args);

            $wp_customize = $this->is_wp_customize;
            $wp_customize->add_setting( $settings_id, [
                'default' => $args['default_value'],
                'sanitize_callback' => 'sanitize_hex_color',
            ]);

            $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, $settings_id.'-control', 
                [
                    'label'   => __($label, 'karri'),
                    'description' => __($args['description'], 'karri'),
                    'section' => $section,
                    'settings' => $settings_id,
                ]
            ));
        }

        /**
        * Add TinyMCE editor option
        *
        * @param string $settings_id Unique ID
        * @param string $label Name of the option
        * @param string $section Add the ID of the section to apply it to
        * @param array $args [ 'description' => '', 'default_value' => '' ]
        */

        function text_editor_option($settings_id, $label, $section, $args = []){

            $default_args = [ 'description' => '', 'default_value' => '' ];
            $args = _merge_arrays($default_args, $args);  

            $amount = [];
            static $count = 1;

            $wp_customize = $this->is_wp_customize;

            $wp_customize->add_setting( $settings_id, [
                'default'=> $args['default_value'],
                'sanitize_callback' => 'wp_kses_post',
            ]);
            $wp_customize->add_control(new \Karri\Components\Customizer\Controls\TinyMCE_Editor_Control($wp_customize, $settings_id.'-control', 
                [
                    'label'   => __($label, 'karri'),
                    'description' => __($args['description'], 'karri'),
                    'section' => $section,
                    'settings' => $settings_id,
                    'choices' => $amount
                ]
            ));
        }

        /**
        * Add media upload option
        *
        * @param string $settings_id Unique ID
        * @param string $label Name of the option
        * @param string $section Add the ID of the section to apply it to
        * @param array $args [ 'description' => '', 'default_value' => '' ]
        * @return void
        */

        function media_upload_option($settings_id, $label, $section, $args = []){
            
            $default_args = [ 'description' => '', 'default_value' => '' ];
            $args = _merge_arrays($default_args, $args);

            $wp_customize = $this->is_wp_customize;

            // ++ Adds another option that has array of information about the image. Just add '_data' at the end of the slug to view the the data.
            $wp_customize->add_setting( new \Karri\Components\Customizer\Controls\Setting_Image_Data(
                $wp_customize, $settings_id, [ 'default' => get_theme_mod( $settings_id, '' ) ]
            ));

            $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, $settings_id.'-control', 
                [
                    'label'   => __($label, 'karri'),
                    'description' => __($args['description'], 'karri'),
                    'section' => $section,
                    'settings' => $settings_id,
                ]
            ));
        }

        /**
        * Multiple Images
        *
        * @param string $settings_id Unique ID
        * @param string $label Name of the option
        * @param string $section Add the ID of the section to apply it to
        * @param string $description Description of the option
        * @return void
        */

        function multi_images($settings_id, $label, $section, $description=''){
            $wp_customize = $this->is_wp_customize;
            $wp_customize->add_setting( $settings_id, [
                'default'=> '',
                'sanitize_callback' => 'sanitize_text_field',
            ]);
            $wp_customize->add_control(new \Karri\Components\Customizer\Controls\Multi_Image_Control($wp_customize, $settings_id.'-control', 
                [
                    'label'   => __($label, 'karri'),
                    'description' => __($description, 'karri'),
                    'section' => $section,
                    'settings' => $settings_id,
                ]
            ));
        }

        /**
        * Add Page/Post Select options
        *
        * It'll display a drop down list of posts/pages. It can show posts from multiple post types.
        *
        * @param string $settings_id Unique ID
        * @param string $label Name of the option
        * @param string $section Add the ID of the section to apply it to
        * @param array $args [ 'post_type' => ['post'], description' => '', 'default_value' => '' ]
        * @return void
        */

        function post_select_option($settings_id, $label, $section, $args = []){

            $default_args = [ 'post_type' => ['post'], 'description' => '', 'default_value' => '' ];
            $args = _merge_arrays($default_args, $args);

            $wp_customize = $this->is_wp_customize;

            $wp_customize->add_setting( $settings_id, [
                'default' => $args['default_value'],
                'sanitize_callback' => 'absint',
            ]);
            $wp_customize->add_control(new \Karri\Components\Customizer\Controls\Post_Select_Control($wp_customize, $settings_id.'-control', 
                [
                    'label'   => __($label, 'karri'),
                    'description' => __($args['description'], 'karri'),
                    'section' => $section,
                    'settings' => $settings_id,
                    'choices' => $args['post_type']
                ]
            ));
        }

        /**
        * Add Toggle Switch option
        *
        * @param string $settings_id Unique ID
        * @param string $label Name of the option
        * @param string $section Add the ID of the section to apply it to
        * @param string $description Description of the option
        * @return void
        */

        function toggle_option($settings_id, $label, $section, $description=''){
            $wp_customize = $this->is_wp_customize;
            $wp_customize->add_setting( $settings_id, [
                'default'=> '',
                'sanitize_callback' => '__sanitize_checkbox',
            ]);
            $wp_customize->add_control(new \Karri\Components\Customizer\Controls\Toggle_Switch_Control($wp_customize, $settings_id.'-control', 
                [
                    'label'   => __($label, 'karri'),
                    'description' => __($description, 'karri'),
                    'section' => $section,
                    'settings' => $settings_id,
                ]
            ));
        }

        /**
        * Add Header
        *
        * @param string $settings_id Unique ID
        * @param string $label Name of the option
        * @param string $section Add the ID of the section to apply it to
        * @return void
        */

        function header($settings_id, $label, $section, $description=''){
            $wp_customize = $this->is_wp_customize;
            $wp_customize->add_setting( $settings_id, [
                'default'=> '',
                'sanitize_callback' => 'sanitize_text_field',
            ]);
            $wp_customize->add_control(new \Karri\Components\Customizer\Controls\Header_Control($wp_customize, $settings_id.'-control', 
                [
                    'label'   => __($label, 'karri'),
                    'description' => __($description, 'karri'),
                    'section' => $section,
                    'settings' => $settings_id,
                ]
            ));
        }

        /**
        * Add Spacer
        *
        * @param string $settings_id Unique ID
        * @param string $section Add the ID of the section to apply it to
        * @return void
        */

        function spacer($settings_id, $section){
            $wp_customize = $this->is_wp_customize;
            $wp_customize->add_setting( $settings_id, [
                'default'=> '',
            ]);
            $wp_customize->add_control(new \Karri\Components\Customizer\Controls\Spacer_Control($wp_customize, $settings_id.'-control', 
                [
                    'label'   => '',
                    'description' => '',
                    'section' => $section,
                    'settings' => $settings_id,
                ]
            ));
        }

        /**
         * Social Media
         *
         * @param array $args ['section' => 'social_section', 'priorty' => 33, 'labels' => false]
         * @return void
         */

        function social_media($args = []){
            $default_args = [ 'section' => 'social_section', 'priorty' => 33, 'labels' => false ];
            $args = _merge_arrays($default_args, $args);

            $social_media = _config_options('karri-theme-social-media');

            if($social_media == false){ return; }

            if($args['labels'] == false){
                foreach( $social_media as $option ){
                    $this->section($args['section'], __( 'Social Media', 'karri' ), $args['priorty'], __('Remember to include <strong>https://</strong> at the beginning of your URLs.', 'karri'));
                    $this->standard_option(PREFIX_.'socialbutton_'.$option['slug'], $option['name'], $args['section'], 'text', ['sanitize_callback' => 'esc_url']);
                }
            }
            else {
                foreach( $social_media as $key => $option ){
                    $section = $args['section'] . '_' . $key;
                    $this->panel('social_panel', __('Social Media', 'karri' ));
                    $this->section($section, __( $option['name'], 'karri' ), '', '', 'social_panel');
                    $this->standard_option(PREFIX_.'socialbutton_'.$option['slug'], __( 'URL', 'karri' ), $section, 'text', ['sanitize_callback' => 'esc_url']);
                    $this->standard_option(PREFIX_.'socialbutton_'.$option['slug'].'_label', __( 'Label', 'karri' ), $section, 'text', ['sanitize_callback' => 'esc_html', 'description' => 'Note: This option is dependant on your theme&#39;s design.']);
                }
            }
        }

        /**
        * Google Maps Instructions
        *
        * @return string Returns instructions
        */

        function google_maps_instructions(){
            return 'Google API Key is free and is required to use Google Maps. You can bundle different Google APIs with the key.<br /><br /><strong>Google Map Types:</strong><br /><ul style="padding-left: 1rem; margin-top: 0.5rem; list-style: square;"><li style="margin-left: 0.5rem;"><strong>JavaScript:</strong> This has usage quotas but the map can be styled, customised and supports multiple map markers.<br /><i>This is the default setting.</i> <a href="https://developers.google.com/maps/documentation/javascript/usage" target="_blank">More Info</a></li><li style="margin-left: 0.5rem;"><strong>iFrame:</strong> Has currently no usage quotas but only supports one map marker.<br /> <a href="https://developers.google.com/maps/documentation/embed/guide#overview" target="_blank">More Info</a></li></ul><strong>Required APIs for Google Maps:</strong><br /><ul style="padding-left: 1rem; margin-top: 0.5rem; list-style: square;"><li style="margin-left: 0.5rem;"><strong>Google Places API Web Service:</strong> Need it for the &#39;Location Search&#39; option in the meta boxes to function. <a href="https://developers.google.com/maps/documentation/javascript/places-autocomplete" target="_blank">More Info</a></li><li style="margin-left: 0.5rem;"><strong>Google Maps Embed API</strong> and <strong>Google Static Maps API:</strong> Need these for the iFrame map type to work correctly.</li></ul>';
        }

        function __construct($wp_customize){
            $this->is_wp_customize = $wp_customize;
        }
    }

}