<?php
// ----------- Karri: Social Media Components -----------

namespace Karri\Component\SocialMedia;

if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

if( !function_exists('Karri\Components\SocialMedia\button') ){

    /**
    * Social Media Button
    *
    * Making it easy to add social media buttons
    *
    * @param int $id The option ID
    * @param array $args ['name' => '', 'li' => true, 'inline_svg' => true, 'label' => '', 'directory' => '', 'theme_mod' => PREFIX_.'socialbutton_']
    * @return string Echos a generated social media button
    */

    function button($id, $args = []){

        $default_args = ['name' => '', 'li' => true, 'inline_svg' => true, 'label' => '', 'class_prefix' => 'sm-buttons', 'theme_mod' => PREFIX_.'socialbutton_'];
        $args = _merge_arrays($default_args, $args);

        // Theme option check
        $social_id = !empty($args['theme_mod']) ? get_theme_mod( $args['theme_mod'].$id ) : false;

        // The sub directory
        $directory = isset($args['directory']) ? $args['directory'].'/' : 'white/';

        if( $social_id ){

            $button = $args['li'] ? "<li" : "<div";

            $button .=  " class='".esc_attr($args['class_prefix'])."__item ".esc_attr($args['class_prefix'])."__item--".esc_attr($id);
            
            $button .= "'>";

            if($id !== 'email'){ // Not the email button
                $button .= "<a href='".esc_url($social_id)."' title='".esc_attr($args['name'])."' aria-label='".esc_html($args['name'])."' target='_blank' rel='noopener noreferrer' class='".esc_attr($args['class_prefix'])."__button'>";
            }
            else if($id === 'email'){ // Is the email button
                $button .= "<a class='email-link ".esc_attr($args['class_prefix'])."__button' href='".antispambot($social_id)."' title='".esc_attr($args['name'])."' aria-label='".esc_attr($args['name'])."' target='_blank' rel='noopener noreferrer'>";
            }

            if( $args['inline_svg'] ){
                $inlineSVG = \Karri\Media\inline_SVG('assets/images/social/'.esc_attr($directory).esc_attr($id).'.svg');
                $inlineSVG = str_replace('<svg', '<svg aria-label="'.esc_attr($args['name']).'"', $inlineSVG);
                $button .= $inlineSVG;
            }
            else{
                $button .= "<img class='".esc_attr($args['class_prefix'])."__image ".esc_attr($args['class_prefix'])."__image--svg' alt='".esc_html($args['name'])."' src='".esc_url( get_theme_file_uri('assets/images/social/'.esc_attr($directory).esc_attr($id).'.svg') )."' />";
            }

            if ($args['label'] !== ''){
                $button .= "<span class='".esc_attr($args['class_prefix'])."__label'>".esc_html($args['label'])."</span>";
            }

            $button .= "</a>";

            $button .= $args['li'] === true ? "</li>" : "</div>";

            return $button;
        }
    };

}

// ----------------------------

if( !function_exists('Karri\Components\SocialMedia\button_list') ){

    /**
    * Social Media Button List
    *
    * Making it easy to add social media buttons
    *
    * @param array $args ['class_prefix' => 'sm-buttons', 'list' => true, 'has_label' => false, 'directory' => '']
    * @return mixed Returns as a HTML list or an array
    */

    function button_list( $args = [] ){
        $default_args = ['class_prefix' => 'sm-buttons', 'list' => true, 'has_labels' => false ];
        $args = _merge_arrays($default_args, $args);
        
        $buttons_args = $buttons = [];
        $output = false;
        
        $settings = _config_options();
        $social_media = isset($settings['theme_options']['social_media']) ? $settings['theme_options']['social_media'] : false;

        if( is_array($social_media) ){

            foreach( $social_media as $social_item ){
                if( $args['list'] === true ){
                    $buttons_args['li'] = true;
                }
                if($args['has_labels'] === true){
                    $get_label = get_theme_mod(PREFIX_.'socialbutton_'.$social_item['slug'].'_label');
                    $buttons_args['label'] = $get_label? $get_label : $social_item['name'];
                }
                if( !empty($args['class_prefix']) ){
                    $buttons_args['class_prefix'] = $args['class_prefix'];
                }
                if( isset($args['directory']) && !empty($args['directory']) ){
                    $buttons_args['directory'] = $args['directory']; 
                }
                $buttons_args['name'] = $social_item['name'];
                $buttons[] = social_button($social_item['slug'], $buttons_args);
            }

        }

        if( !empty($buttons) ){

            if( $args['list'] ){
                $output = "<ul class='".esc_attr($args['class_prefix'])."__list'>";
                foreach( $buttons as $button ){
                    $output .= $button;
                }
                $output .= "</ul>";
            }
            else{
                $output = $buttons;
            }
        }

        return $output;
    }

}

if( !function_exists('Karri\Components\SocialMedia\customizer') ){

    /**
    * Social Media Button List
    *
    * Making it easy to add social media buttons
    *
    * @param array $args ['class_prefix' => 'sm-buttons', 'list' => true, 'has_label' => false, 'directory' => '']
    * @return mixed Returns as a HTML list or an array
    */

    function customizer_social_buttons($has_labels = false){
        $social_media = _config_options('karri-theme-social-media');
        $is_array = is_array($social_media);

        if(!$has_labels && $is_array){
            foreach( $social_media as $option ){
                $kc->standard_option(PREFIX_.'socialbutton_'.$option['slug'], $option['name'], 'social_section', 'text', ['sanitize_callback' => 'esc_url']);
            }
        }
        else if($is_array){
            foreach( $social_media as $key => $option ){
                $kc->section('social_section_' . $key, __( $option['name'], 'karri' ), '', '', 'social_panel');
                $kc->standard_option(PREFIX_.'socialbutton_'.$option['slug'], __( 'URL', 'karri' ), 'social_section_' . $key, 'text', ['sanitize_callback' => 'esc_url']);
                $kc->standard_option(PREFIX_.'socialbutton_'.$option['slug'].'_label', __( 'Label', 'karri' ), 'social_section_' . $key, 'text', ['sanitize_callback' => 'esc_html', 'description' => 'Note: This option is dependant on your theme&#39;s design.']);
            }
        }

    }
}