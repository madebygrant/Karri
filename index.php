<?php
/*
    Page: Index
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

if( is_search() || is_archive() ){

    Theme::header();

    Theme::view('pages/archive');

    Theme::footer();

}