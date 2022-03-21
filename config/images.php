<?php
// ----------- Karri: Configuration - Images -----------

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

// -- Set the image sizes here

add_filter('karri-theme-image-sizes', function(){

    $largest_image_dimensions = [ 1920, 670 ];

    return [

        // Hero Image Dimensions
        'hero' => [
            'xlarge' => [
                'label' => 'Hero: Extra Large',
                'dims'  => $largest_image_dimensions,
                'crop'  => true
            ],
            'large' => [ 
                'label' => 'Hero: Large',
                'dims'  => _resize_image( 1280, $largest_image_dimensions ),  // Get image size with the same aspect ratio
                'crop'  => true
            ],
            'medium' => [
                'label' => 'Hero: Medium',
                'dims'  => _resize_image( 768, $largest_image_dimensions ),    // Get image size with the same aspect ratio
                'crop'  => true
            ],
            'small' => [
                'label' => 'Hero: Small',
                'dims'  => _resize_image( 475, $largest_image_dimensions ),    // Get image size with the same aspect ratio
                'crop'  => true
            ]
        ],
        
        /*
        // Other image size dimensions
        'other' => [
            'medium' => [
                'label' => '',
                'dims'  => '',
                'crop'  => false
            ]
        ]
        */

    ];

});

// ----------------------------

// -- Disable Image Sizes

add_filter('intermediate_image_sizes_advanced', function($sizes){
    unset($sizes['medium_large']);  // Disable hidden 'medium large' 768px size images
    unset($sizes['1536x1536']);     // Disable 2x medium-large size
	return $sizes;
});