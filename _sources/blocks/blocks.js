wp.domReady( () => {

	// ---- Blocks ----

	wp.blocks.unregisterBlockType( 'core/archives' );
	wp.blocks.unregisterBlockType( 'core/categories' );
	wp.blocks.unregisterBlockType( 'core/latest-comments' );
	wp.blocks.unregisterBlockType( 'core/latest-posts' );
	wp.blocks.unregisterBlockType( 'core/more' );
	wp.blocks.unregisterBlockType( 'core/nextpage' );
	wp.blocks.unregisterBlockType( 'core/verse' );
    wp.blocks.unregisterBlockType( 'core/pullquote' );
    wp.blocks.unregisterBlockType( 'core/calendar' );
    wp.blocks.unregisterBlockType( 'core/tag-cloud' );
    wp.blocks.unregisterBlockType( 'core/rss' );
    wp.blocks.unregisterBlockType( 'core/search' );

// ------------------------------------------------------

	// ---- Block Styles ----

	// Button
	wp.blocks.unregisterBlockStyle( 'core/button', 'default' );
	wp.blocks.unregisterBlockStyle( 'core/button', 'outline' );
    wp.blocks.unregisterBlockStyle( 'core/button', 'squared' );
    wp.blocks.unregisterBlockStyle( 'core/button', 'fill' );
    
    wp.blocks.registerBlockStyle( 'core/button', { name: 'default', label: 'Button: Default', isDefault: true } );

	// Separator
	//wp.blocks.unregisterBlockStyle( 'core/separator', 'default' );
	//wp.blocks.unregisterBlockStyle( 'core/separator', 'wide' );
	wp.blocks.unregisterBlockStyle( 'core/separator', 'dots' );

	// Pullquote
	wp.blocks.unregisterBlockStyle( 'core/pullquote', 'default' );
	wp.blocks.unregisterBlockStyle( 'core/pullquote', 'solid-color' );

	// Quote
	//wp.blocks.unregisterBlockStyle( 'core/quote', 'default' );
    //wp.blocks.unregisterBlockStyle( 'core/quote', 'large' );
    
    // Table
    wp.blocks.unregisterBlockStyle( 'core/table', 'default' );
    wp.blocks.unregisterBlockStyle( 'core/table', 'stripes' );

} );