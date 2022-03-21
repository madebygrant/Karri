<?php
// ----------- Karri: Paths -----------

namespace Karri\Bootstrap;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

// The theme directory 'app' filepath
$theme_path = get_stylesheet_directory();

// Paths of the bootstrapped files
$app_paths = [
    'theme'         => $theme_path . '/',
    'app'           => $theme_path . '/_app/',
    'assets'        => $theme_path . '/_app/assets/',
    'bootstrap'     => $theme_path . '/_app/bootstrap/',
    'lib'           => $theme_path . '/_app/lib/',
    'components'    => $theme_path . '/_app/components/',
    'setup'         => $theme_path . '/_app/setup/',
    'config'        => $theme_path . '/config/'
];

define('KARRI_APP_PATHS', $app_paths);

// ----

// Paths of theme related, not related to the '_app' directory
$theme_paths = [
    'assets' => $theme_path . '/assets/',
    'config' => $theme_path . '/config/',
    'page-templates' => $theme_path . '/page-templates/',
    'views' => $theme_path . '/views/',
];

define('KARRI_THEME_PATHS', $theme_paths);

// ----

// Paths of the 'views' directories relative to the theme
$views_paths = [
    'views'         => 'views/',
    'modules'       => 'views/modules/',
    'pages'         => 'views/pages/',
    'posts'         => 'views/posts/',
];
define('KARRI_VIEWS', $views_paths);