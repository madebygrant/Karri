<?php
// ----------- Karri: Enqueue Controller -----------

namespace Karri\Enqueue;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

if( !class_exists('Karri\Enqueue\Controller') ){

    /**
     * Enqueue Controller
     * 
     * Controls the loading of enqueued files
     */

    class Controller{

        protected $files, $dir, $type;
        public $scripts_deferred, $styles_deferred, $scripts_async, $styles_async;

        /**
         * Checks if valid
         *
         * @param array $data
         * @return boolean
         */
        protected static function is_valid($data){
            
            $post_id = _post_id();
            $is_page_template = basename( get_page_template() );
            $is_post_type = get_post_type( $post_id );
            $is_post_content = get_post_field( 'post_content', $post_id );

            if( isset($data['templates']) && $data['templates'] === 'all' || isset($data['pages']) && $data['pages'] === 'all' ){
                return true;
            }
            else if( isset($data['templates']) && is_array($data['templates']) && in_array($is_page_template, $data['templates']) ){
                return true;
            }
            else if( isset($data['post-types']) && is_array($data['post-types']) && in_array($is_post_type, $data['post-types']) ){
                return true;
            }
            else if( isset($data['shortcode']) && has_shortcode($is_post_content, $data['shortcode']) ){
                return true;
            }
            else if( isset($data['pages']) && is_array($data['pages']) && in_array($post_id, $data['pages']) ){
                return true;
            }
            else if( isset($data['singles']) && is_array($data['singles']) && is_singular($data['singles']) ){
                return true;
            }
            else if( isset($data['content-match']) && strpos($is_post_content, $data['content-match']) ){
                return true;
            }
            
            return false;        

        }

        /**
         * Load the file
         *
         * @param array $files
         * @param string $type
         * @return void
         */
        protected function load( $files, $type ){
            
            if( $files ){
                $deferred_scripts = [];
                $deferred_styles = [];
                $async_scripts = [];
                $async_styles = [];

                foreach( $files as $handle => $data ){
                    $is_valid = self::is_valid( $data );
                    $can_load = !empty($handle) && (!empty($data['file']) || !empty($data['url'])) ? true : false;
                    $is_url = isset($data['url']);
                    $is_localize = isset($data['localize']);
                    $in_footer = isset($data['footer']) ? $data['footer'] : false;
                    $is_deferred = isset($data['defer']) ? $data['defer'] : false;
                    $is_async = isset($data['async']) ? $data['async'] : false;

                    $version = isset($data['version']) && !empty($data['version']) ? (float) $data['version'] : null;
                    $deps = isset($data['deps']) && is_array($data['deps']) ? $data['deps'] : [];
                    $media = isset($data['media']) && is_array($data['media']) ? $data['media'] : 'all';
                    
                    $companion = isset($data['companion']) ? $data['companion'] : false;

                    if( $type === 'scripts' && $is_valid ){
                        if( !$is_url && $can_load ){
                            wp_enqueue_script( $handle, get_theme_file_uri( '/assets/js/'.esc_html($data['file']) ), $deps, $version, $in_footer );
                        }
                        else if( $is_url && $can_load ){
                            wp_enqueue_script( $handle, esc_url($data['url']), $deps, $version, $in_footer );
                        }
                        if( $is_localize && is_array($is_localize) ){
                            wp_localize_script( $handle, $data['localize']['slug'], $data['localize']['vars'] );
                        }

                        if( $is_deferred ){
                            $deferred_scripts[] = $handle;
                        }
                        elseif( $is_async ){
                            $async_scripts[] = $handle;
                        }
                    }
                    else if( $type === 'styles' && $is_valid ){
                        if( !$is_url && $can_load ){
                            wp_enqueue_style( $handle, get_theme_file_uri( '/assets/css/'.esc_html($data['file']) ), $deps, $version, $media );
                        }
                        else if( $is_url && $can_load ){
                            wp_enqueue_style( $handle, esc_url($data['url']), $deps, $version, $media );
                        }

                        if( $is_deferred ){
                            $deferred_styles[] = $handle;
                        }
                        elseif( $is_async ){
                            $async_styles[] = $handle;
                        }
                    }

                    // Companion file 
                    // ? Example: A stylesheet that comes with a JS plugin.
                    if( !empty($handle) && !empty($companion) && is_array($companion) && $is_valid ){
                                
                        $comp_slug = isset($companion['slug']) ? $companion['slug'] : false;
                        $comp_type = isset($companion['type']) ? $companion['type'] : false;
                        $comp_url = isset($companion['url']) ? $companion['url'] : false;
                        $comp_file = isset($companion['file']) ? $companion['file'] : false;
                        
                        $comp_load = !empty($comp_slug) && (!empty($comp_file) || !empty($comp_url)) ? true : false;
                        $comp_footer = isset($companion['footer']) && is_bool($companion['footer'])  ? $companion['footer'] : false;
                        
                        if($comp_type === 'script' && $comp_load){
                            if( $comp_url && !$comp_file ){
                                wp_enqueue_script( $comp_slug, esc_url($comp_url), '', $version, $comp_footer );
                            }
                            else{
                                wp_enqueue_script( $comp_slug, get_theme_file_uri( '/assets/js/'.esc_html($comp_file) ), '', $version, $comp_footer );
                            }
                        }
                        else if($comp_type === 'style' && $comp_load){
                            
                            if( $comp_url && !$comp_file ){
                                wp_enqueue_style( $comp_slug, esc_url($comp_url), $version );
                            }
                            else{
                                wp_enqueue_style( $comp_slug, get_theme_file_uri( '/assets/css/'.esc_html($comp_file) ), $version );
                            }

                        }

                    }

                }

                $this->scripts_deferred = $deferred_scripts;
                $this->styles_deferred = $deferred_styles;
                $this->scripts_async = $async_scripts;
                $this->styles_async = $async_styles;
            }

        }

        /**
         * Defer selected scripts
         *
         * @param string $tag
         * @param string $handle
         * @param string $src
         * @return void
         */
        public function scripts_defer_async($tag, $handle, $src){
            // The handles of the enqueued scripts we want to defer
            $defer_scripts = $this->scripts_deferred;
            $others_defer = apply_filters( 'karri-theme-defer-scripts', []);
            $defer_scripts = _merge_arrays($defer_scripts, $others_defer);

            // The handles of the enqueued scripts we want to async
            $async_scripts = $this->scripts_async;
            $others_async = apply_filters( 'karri-theme-async-scripts', []);
            $async_scripts = _merge_arrays($async_scripts, $others_async);

            // Find scripts in the arrays and add the appropriate tag atrribute
            if ( in_array( $handle, $defer_scripts ) ) {
                return '<script src="' . esc_url($src) . '" id="' . esc_attr($handle) . '" defer="defer"></script>';
            }
            elseif ( in_array( $handle, $async_scripts ) ) {
                return '<script src="' . esc_url($src) . '" id="' . esc_attr($handle) . '" async="async"></script>';
            }
            
            return $tag;
        }

        /**
         * Defer selected styles
         *
         * @param string $tag
         * @param string $handle
         * @param string $src
         * @return void
         */
        public function styles_defer_async($tag, $handle, $src){

            // The handles of the enqueued stylesheets we want to defer
            $defer_styles = $this->styles_deferred;
            $others_defer = apply_filters( 'karri-theme-defer-styles', []);
            $defer_styles = _merge_arrays($defer_styles, $others_defer);

            // The handles of the enqueued stylesheets we want to async
            $async_styles = $this->styles_async;
            $others_async = apply_filters( 'karri-theme-async-styles', []);
            $async_styles = _merge_arrays($async_styles, $others_async);

            // Find stylesheets in the arrays and add the appropriate tag atrribute
            if ( in_array( $handle, $defer_styles ) ) {
                return '<link rel="stylesheet" id="' . esc_attr($handle) . '" href="' . esc_url($src) . '" defer="defer"></link>';
            }
            elseif ( in_array( $handle, $async_styles ) ) {
                return '<link rel="stylesheet" id="' . esc_attr($handle) . '" href="' . esc_url($src) . '" async="async"></link>';
            }

            return $tag;
        }

        function __construct($files, $type){
            self::load($files, $type);
            add_filter( 'script_loader_tag', [$this, 'scripts_defer_async'], 10, 3 );
            add_filter( 'style_loader_tag', [$this, 'styles_defer_async'], 10, 3 );
            
        }
    }
}