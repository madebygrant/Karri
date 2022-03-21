(async () => {

    const { wpSides } = await import(wpSidesPlugin.load); // Import the WP Sides controls

    const { __ } = wp.i18n;
    const el = wp.element.createElement;
    const { Fragment } = wp.element;
    const { PanelBody } = wp.components;

    const sampleSidebar = () => {
        
        const details = {
            title: __('WPSides - Sample Sidebar'),
            name: 'wpsides-sample-sidebar',
            metaKey: '_page_sidebar_meta'
        };

        // Get the meta data, post type and page template
        const get = {
            meta: wpSides.utils.get.groupMeta(details.metaKey),
            postType: wp.data.useSelect( select => select( 'core/editor' )).getCurrentPostType(),
            pageTemplate: wp.data.useSelect( select => select( 'core/editor' )).getEditedPostAttribute('template'),
        };
        
        // Restrict to specific post types
        /*
        const allowedPostTypes = [ 'page' ];
        if( !allowedPostTypes.includes(get.postType) ){
            return null;
        }
        */
        
        // Restrict to specific page templates
        /*
        const allowedPageTemplates = [ 'page-templates/some-template.php' ];
        if( !allowedPageTemplates.includes(get.pageTemplate) ){
            return null;
        }
        */

        return (

            el( Fragment, {},

                // Adds the sidebar to the 'Plugins' section
                el( wpSides.AddSidebar,
                    { target: details.name, icon: details.icon }, details.title
                ),
                
                // Create the sidebar
                el( wpSides.Sidebar,
                    { name: details.name, icon: details.icon, title: details.title },
    
                    // ------------------ Add your options below ------------------
    
                    el( PanelBody, { title: "Group 1" },
    
                        el( 
                            wpSides.groupControl('text'),
                            {
                                id: 'text_1',
                                title : __('Sample Text Field'),
                                metaKey: details.metaKey,
                            }
                        ),
    
                        el( 
                            wpSides.groupControl('checkbox'),
                            {
                                id: 'checkbox',
                                title : __('Checkbox test'),
                                metaKey: details.metaKey,
                            }
                        ),
            
                        el( 
                            wpSides.groupControl('toggle'),
                            {
                                id: 'toggle',
                                title : __('Boolean test'),
                                metaKey: details.metaKey,
                            }
                        ),
    
                        el( 
                            wpSides.groupControl('date'),
                            {
                                id: 'date',
                                title : __('Date test'),
                                metaKey: details.metaKey,
                            }
                        ),
    
                    ),
    
                    el( PanelBody, { title: "Group 2", initialOpen: false },
    
                        el( 
                            wpSides.groupControl('colour'),
                            {
                                id: 'colour',
                                title : __('Colour test'),
                                metaKey: details.metaKey,
                            }
                        ),
    
                        el( 
                            wpSides.groupControl('fontSize', { 
                                fontSizes: [
                                    {
                                        name: __( 'Small' ),
                                        slug: 'small',
                                        size: 12,
                                    },
                                    {
                                        name: __( 'Large' ),
                                        slug: 'large',
                                        size: 32,
                                    },
                                ] 
                            }),
                            {
                                id: 'font_size',
                                title : __('Font size test'),
                                metaKey: details.metaKey,
                            }
                        ),
    
                        el( 
                            wpSides.groupControl('radio', { 
                                options: [
                                    { label: 'Author', value: 'author' },
                                    { label: 'Editor', value: 'editor' },
                                ]
                            }),
                            {
                                id: 'radio',
                                title : __('Radio test'),
                                metaKey: details.metaKey,
                            }
                        ),
    
                        el( 
                            wpSides.groupControl('range', { min: 0, max: 100 }),
                            {
                                id: 'range',
                                title : __('Range test'),
                                metaKey: details.metaKey,
                            }
                        ),
    
                        el( 
                            wpSides.groupControl('time'),
                            {
                                id: 'time',
                                title : __('Time test'),
                                metaKey: details.metaKey,
                            }
                        ),
    
                        el( 
                            wpSides.groupControl('select', {
                                options: [
                                    { label: 'Big', value: '100%' },
                                    { label: 'Medium', value: '50%' },
                                    { label: 'Small', value: '25%' },
                                ]
                            }),
                            {
                                id: 'select',
                                title : __('Select test'),
                                metaKey: details.metaKey,
                            }
                        ),
                        
                        el( 
                            wpSides.groupControl('media'),
                            {
                                id: 'media',
                                title : __('Media test'),
                                metaKey: details.metaKey,
                            }
                        ),
                    )
                )
    
            )    

        )
    }

    // Register the sidebar
    wp.plugins.registerPlugin( 'wpsides-sample-sidebar', {
        render: sampleSidebar,
        icon: wpSides.icons.sliders
    } );

})();
