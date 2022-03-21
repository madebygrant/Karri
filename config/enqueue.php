<?php
// ----------- Karri: Configuration - Enqueue Stylesheets & JavaScript scripts -----------

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

// -- Enqueue Stylesheets

add_filter('karri-theme-styles', function(){
    return [
        'theme' => [ 'file' => 'theme.min.css', 'version' => THEME_VERSION, 'pages' => 'all', 'defer' => true ],
    ];
});

// Defer other enqueued stylesheets
add_filter( 'karri-theme-defer-styles', function(){
    return [
        'wp-block-library',
        'aube'
    ];
});

// ----

// -- Enqueue JavaScript scripts

add_filter('karri-theme-scripts', function(){

    return [
        'tinyslider' => [ 'url' => 'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.min.js', 'version' => '2.9.3', 'templates' => ['home.tpl.php'], 'footer' => true, 'defer' => true ],
        //'baguettebox' => [ 'url' => 'https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.js', 'version' => '1.11.1', 'pages' => 'all', 'footer' => true, 'defer' => true ],

        'theme' => [ 'file' => 'theme.min.js', 'version' => THEME_VERSION, 'pages' => 'all', 'footer' => true, 'defer' => true ],
    ];

});

// Defer other enqueued scripts
add_filter( 'karri-theme-defer-scripts', function(){
    return [
        'wp-embed',
        'dawn',
        'aube'
    ];
});

// ----

// -- Enqueue 'Block Editor' stylesheets & JavaScript scripts

add_filter('karri-editor-enqueue', function(){
    return [
        'css' => 'assets/css/blocks.min.css',
        'js' => 'assets/js/blocks.min.js'
    ];

});