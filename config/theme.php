<?php
// ----------- Karri: Theme Configuration -----------

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

// -- Menus

add_action( 'init', function(){
    register_nav_menus([

        'top-menu' => __( 'Top Menu', 'karri' ),
        //'footer-menu' => __( 'Footer Menu', 'karri' )
        
    ]);
});

// ----------------------------

// -- Widgets
/*
add_action( 'widgets_init', function(){
    
    register_sidebar([
        'name'=> __('Primary', 'karri'),
        'id' => 'primary',
        'before_widget' => '<div class="widget-item widget-item--primary">',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
    ]);

});
*/

// ----------------------------

// -- Social Media

add_filter('karri-theme-social-media', function(){
    return [
        ["slug" => "facebook", "name" => "Facebook"],
        ["slug" => "instagram", "name" => "Instagram"],
        ["slug" => "linkedin", "name" => "LinkedIn"],
        ["slug" => "pinterest", "name" => "Pinterest"],
        ["slug" => "tripadvisor", "name" => "TripAdvisor"],
        ["slug" => "twitter", "name" => "Twitter"],
        ["slug" => "youtube", "name" => "YouTube"],
    ];
});

// ----------------------------

// -- Colour Palette

/*
add_filter('karri-theme-colour-palette', function(){
    return [
        [ 'name' => 'Mauve', 'slug' => 'mauve', 'color' => '#d5b8ff' ]
    ];
});
*/

// ----------------------------

// -- Page Template State Messages

/*
add_filter('karri-theme-page-template-states', function(){
    return [
        'contact.tpl.php' => __('Contact Page', 'karri')
    ];
});
*/