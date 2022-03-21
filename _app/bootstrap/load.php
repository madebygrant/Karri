<?php
// ----------- Karri: Bootstrap Loader -----------

namespace Karri\Bootstrap;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

class Load{

    public $errors = [];
    public $paths;

    // ----

    /**
     * Load a file
     *
     * @param string $path
     * @return void
     */

    function load($path){
        file_exists( $path ) ? require_once $path : $this->errors[] = esc_html("Error: '{$path}' not found!");
    }

    // ----

    /**
     * Load the theme config options
     *
     * @return void
     */

    function load_configs(){
        $configs = _clean_array( apply_filters( 'karri-theme-load-configs', []) );

        if( file_exists( $this->paths['config'] ) && \is_array($configs) ){
            foreach( $configs as $file ){
                $path = pathinfo($file);
                $filepath = $this->paths['config'] . '/' . $path['dirname'] . '/' . $path['filename'] . '.php';
                $this->load($filepath);
            }
        }
    }

    // ----

    /**
     * Create error notices 
     *
     * @return void
     */

    function notices(){
        if( count($this->errors) > 0 ){
            ?>
                <div class="notice notice-error">
                    <ul style="margin:12px 24px;">
                        <?php foreach($this->errors as $error): ?>
                        <li style="line-height:1.75em;list-style:square;">
                            <?php esc_html_e( $error, 'karri' ); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php
        }
    }

    // ----

    /**
     * The controller to load files for this theme
     *
     * @return void
     */
    function initialise(){

        // Configuration
        $this->load($this->paths['config'] . 'app.php');

        // Helper functions
        $this->load($this->paths['lib'] . 'helpers.php');

        // Theme Supports
        $this->load($this->paths['setup'] . 'supports.php');

        // Enqueue Controller
        $this->load($this->paths['lib'] . 'enqueue-controller.php');

        // Components: Admin Components
        $this->load($this->paths['components'] . 'admin.php');

        // Components: ARIA Nav Walker
        $this->load($this->paths['components'] . 'nav-walker.aria.php');

        // Components: Theme Customiser
        $this->load($this->paths['components'] . 'customizer.controls.php');
        $this->load($this->paths['components'] . 'customizer.options.php');

        // Components: Media
        $this->load($this->paths['config'] . 'images.php');
        $this->load($this->paths['components'] . 'media.php');

        // Components: Page Parts
        $this->load($this->paths['components'] . 'parts.php');

        // Components: Social Media
        $this->load($this->paths['components'] . 'social-media.php');

        // Components: Slider
        $this->load($this->paths['components'] . 'slider.php');

        // Components: Theme Options
        $this->load($this->paths['components'] . 'theme-options.php');

        // Components: Miscellaneous
        $this->load($this->paths['components'] . 'misc.php');

        // Load frontend related
        $this->load($this->paths['setup'] . 'frontend.php');

        // Load Scripts & Stylesheets
        $this->load($this->paths['setup'] . 'enqueue.php');

        // Load theme filters
        $this->load($this->paths['setup'] . 'filters.php');

        // Facades
        $this->load($this->paths['app'] . 'facades.php');
        
        // Contact Form 7
        if( defined('WPCF7_VERSION') ){
            $this->load($this->paths['setup'] . 'vendors/contact-form-7.php');
        }

    }

    // ----
    
    function __construct(){
        $this->paths = KARRI_APP_PATHS;
        $this->initialise();
        $this->load_configs();
        add_action( 'admin_notices', [ $this, 'notices' ] );
    }

}

// ----------------------------

// Load the bootstrap paths
require_once __DIR__ . '/../paths.php';

// Load the theme constants
require_once __DIR__ . '/../setup/constants.php';

// Load the bootstrap files
new Load();