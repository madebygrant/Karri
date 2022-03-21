<?php
// ----------- Karri: Theme Options -----------

namespace Karri\Components\ThemeOptions;

if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

/**
 * Create a 'Theme Options' page
 */

class Page{

    protected $opts;
    
    /**
     * Load  assets (styles and scripts) needed
     *
     * @return void
     */
    function options_assets() {
        wp_enqueue_style( 'karri-theme-options', get_theme_file_uri('_app/assets/theme-options/style.min.css'), [ 'wp-components' ] );
        wp_enqueue_script( 'karri-theme-options-components', get_theme_file_uri('_app/assets/theme-options/components.min.js'), [ 'wp-api', 'wp-i18n', 'wp-components', 'wp-element' ], THEME_VERSION, true );
        wp_enqueue_script( 'karri-theme-options', get_theme_file_uri($this->opts['options_js']), [ 'wp-api', 'wp-i18n', 'wp-components', 'wp-element' ], THEME_VERSION, true );
    }

    /**
     * Register the page
     *
     * @return void
     */
    function add_option_menu() {
        add_options_page(
            __( esc_html($this->opts['page']['menu_title']), 'karri' ),
            __( esc_html($this->opts['page']['menu_title']), 'karri' ),
            'manage_options',
            'theme-options',
            function(){
                ?>
                    <div id="karri-theme-options" class="theme-options<?php echo $this->opts['page']['class'] ? ' ' . sanitize_title($this->opts['page']['class']) : '' ?>"></div>
                <?php
            }
        );
    }

    /**
     * Register the setting meta values
     *
     * @return void
     */
    function register_settings() {

        foreach( $this->opts['settings'] as $setting ){
            $args = isset($setting['args']) ? $setting['args'] : [];
            $args = _merge_arrays($this->opts['default_settings_args'], $args);
            register_setting(
                $this->opts['settings_group'],
                $setting['name'], // ID of the option
                $args
            );
        }

    }

    function __construct($args){
        $default_args = [
            'options_js' => null,
            'page' => [
                'menu_title' => 'Theme Options',
                'class' => null
            ],
            'default_settings_args' => [ 'type' => 'string', 'default' => '', 'show_in_rest' => true ],
            'settings_group' => 'theme_settings',
            'settings' => []
        ];
        $this->opts = _merge_arrays($default_args, $args);

        if( $this->opts['options_js'] ){
            add_action( 'admin_menu', [$this, 'add_option_menu'], 10 );
            add_action( 'init', [$this, 'register_settings'] );
            add_action( 'admin_enqueue_scripts', [$this, 'options_assets'], 10 );
        }

    }

}