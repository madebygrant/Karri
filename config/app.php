<?php
// ----------- Karri: Configuration -----------

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

const THEME_PREFIX = 'karri';
const PREFIX_ = 'karri_';

// ----------------------------

// -- Load configurations and function files
// images.php loads automatically, no need to include in the configs list below.

add_filter('karri-theme-load-configs', function(){
    $configs = [
        'enqueue',
        'customizer',
        'theme',
        //'vendors/wpsides-editor-sidebars',
        'theme-options'
    ];

    return $configs;
});