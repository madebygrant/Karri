<?php
// ----------- Karri: Constants -----------

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

const KARRI_VERSION = '1.1';

// ----------------------------

$theme = wp_get_theme();

// Theme name
define('THEME_NAME', $theme->get('Name') );

// Theme Version
define('THEME_VERSION', $theme->get('Version') );
