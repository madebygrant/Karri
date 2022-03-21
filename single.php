<?php
/*
    Page: Single
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

Theme::header();

Theme::view('pages/default');

Theme::footer();