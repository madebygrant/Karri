<?php
/* 
    Template Name: Home
    Template Post Type: page
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

Theme::header();

Theme::slider();

Theme::view('pages/default');

Theme::footer();