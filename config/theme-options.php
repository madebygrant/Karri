<?php
// ----------- Karri: Configuration - Theme Options -----------

namespace Karri\Config;

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

/*

    Create Theme Option pages and storage. 
    Remember to edit the JavaScript file listed in the 'options_js' to correlate with the changes made here if required.

    -- Default options:

    [
        'options_js' => null,
        'page' => [
            'menu_title' => 'Theme Options',
            'class' => null
        ],
        'default_settings_args' => [ 'type' => 'string', 'default' => '', 'show_in_rest' => true ],
        'settings_group' => 'theme_settings',
        'settings' => []
    ]

    -- Example usage:

    $options = [
        'options_js' => 'assets/js/theme-options/sample.js',
        'settings' => [
            [
                'name' => 'theme_option_text_1',
            ],
            [
                'name' => 'theme_option_text_2',
            ],
            [
                'name' => 'theme_option_toggle',
                'args' => [ 'type' => 'boolean' ]
            ]
        ]
    ];

    Theme::options_page($options);
*/

// ----------------------------

