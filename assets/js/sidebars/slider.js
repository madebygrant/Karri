(async () => {

    const { wpSides } = await import(wpSidesPlugin.load); // Import the WP Sides controls

    const { __ } = wp.i18n;
    const el = wp.element.createElement;
    const { Fragment } = wp.element;
    const { PanelBody, Button } = wp.components;

    const sliderSidebar = () => {
        
        const details = {
            title: __('Slider', 'karri'),
            name: 'karri-slider-sidebar',
            metaKey: '_page_slider_meta'
        };

        const get = {
            meta: wpSides.utils.get.groupMeta(details.metaKey),
            postType: wp.data.useSelect( select => select( 'core/editor' )).getCurrentPostType(),
            pageTemplate: wp.data.useSelect( select => select( 'core/editor' )).getEditedPostAttribute('template'),
        };

        // ------------------------------------------------
        
        // Restrict to specific post types
        /*
        const allowedPostTypes = [ 'page' ];
        if( !allowedPostTypes.includes(get.postType) ){
            return null;
        }
        */
        
        // Restrict to specific page templates
        /*
        const allowedPageTemplates = [ 'page-templates/home.tpl.php' ];
        if( !allowedPageTemplates.includes(get.pageTemplate) ){
            return null;
        }
        */

        // Create the slide panels
        const createSlides = () => {
            let elements = [];
            
            for(let id in get.meta){

                if( id.indexOf('slide_media_') != -1 ){  
                
                    const suffix = id.split("_").pop();
                    let imageData = get.meta['slide_media_' + suffix] ? JSON.parse(get.meta['slide_media_' + suffix]) : null;
                    let panelName = imageData != null && imageData.title ? imageData.title : "Slide";

                    elements.push(

                        el('div', {
                            id: 'sidebar-slide-' + suffix, className: 'sidebar-slides__item', 'data-id': suffix
                        },
                        el( PanelBody, { title: panelName, initialOpen: false },

                            el( 
                                wpSides.groupControl('media'),
                                {
                                    id: 'slide_media_' + suffix,
                                    title : __('Media'),
                                    metaKey: details.metaKey,
                                }
                            ),

                            el( 
                                wpSides.groupControl('textURL'),
                                {
                                    id: 'slide_url_' + suffix,
                                    title : __('URL'),
                                    metaKey: details.metaKey,
                                }
                            ),

                            el( 
                                wpSides.groupControl('toggle'),
                                {
                                    id: 'slide_newtab_' + suffix,
                                    title : __('Open in a new tab?'),
                                    metaKey: details.metaKey,
                                    help: 'Use a comma delimetered list using the letter given to the slide'
                                }
                            ),

                            el( 
                                wpSides.groupControl('text'),
                                {
                                    id: 'slide_title_' + suffix,
                                    title : __('Title'),
                                    metaKey: details.metaKey,
                                }
                            ),

                            el( 
                                wpSides.groupControl('textarea'),
                                {
                                    id: 'slide_text_' + suffix,
                                    title : __('Paragraph'),
                                    metaKey: details.metaKey,
                                }
                            ),

                            el( 
                                wpSides.groupControl('text'),
                                {
                                    id: 'slide_button_' + suffix,
                                    title : __('Button Text'),
                                    metaKey: details.metaKey,
                                }
                            ),
                           
                            el( 
                                Button,
                                {
                                    className: 'slide_remove_' + suffix,
                                    isSecondary: true,
                                    style: { marginTop: '1em' },

                                    onClick: () => {
                                        let metadata = get.meta;
                                        const ids = [
                                            'slide_media_' + suffix, 'slide_url_' + suffix,
                                            'slide_newtab_' + suffix, 'slide_title_' + suffix,
                                            'slide_text_' + suffix, 'slide_button_' + suffix
                                        ];

                                        if( metadata.slides_positions.length > 0 ){
                                            let positions = metadata.slides_positions.includes(',') ? metadata.slides_positions.split(',') : [suffix],
                                                posIndex = positions.indexOf(suffix); // get index if value found otherwise -1

                                            if (posIndex > -1) {
                                                positions.splice(posIndex, 1);
                                                positions.join(",");
                                                metadata.slides_positions = positions;
                                            }
                                        }
                                        
                                        ids.forEach((id) => {
                                            delete metadata[id];
                                        });

                                        wp.data.dispatch('core/editor').editPost({ meta: { [details.metaKey]: JSON.stringify(metadata) } });
                                        wp.data.dispatch('core/notices').createNotice( 'success', 'Removed slide: ' + panelName, { type: 'snackbar' } );
                                        wp.data.dispatch('core/editor').savePost();
                                    }
                                },
                                'Remove Slide'
                            ),
                        )
                    )
                    );

                }
            }

            return elements;
        }

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
    
                    el( PanelBody, { title: "Slider Options" },

                        el( 
                            wpSides.groupControl('text'),
                            {
                                id: 'slide_delay',
                                title : __('Transitions Delay (ms)'),
                                metaKey: details.metaKey,
                            }
                        ),

                        el( 
                            Button,
                            {
                                className: 'add_slide',
                                isPrimary: true,
                                style: { marginTop: '1em' },

                                onClick: () => {
                                    let metadata = get.meta;
                                    const slideID = Math.random().toString(36).replace('0.', '');

                                    let slides_positions = metadata.slides_positions ? ( metadata.slides_positions.includes(',') ? metadata.slides_positions.split(',') : [metadata.slides_positions] ) : [];
                                    
                                    const ids = [
                                        'slide_media_' + slideID, 'slide_url_' + slideID,
                                        'slide_newtab_' + slideID, 'slide_title_' + slideID,
                                        'slide_text_' + slideID, 'slide_button_' + slideID
                                    ];
                                    
                                    ids.forEach((id) => {
                                        metadata[id] = '';
                                    });

                                    slides_positions.push(slideID);
                                    slides_positions = slides_positions.join();
                                    metadata.slides_positions = slides_positions;
                                    wp.data.dispatch('core/notices').createNotice( 'success', 'Slide added!', { type: 'snackbar' } );
                                    wp.data.dispatch('core/editor').editPost({ meta: { [details.metaKey]: JSON.stringify(metadata) } });
                                    wp.data.dispatch('core/editor').savePost();
                                }
                            },
                            'Add a slide'
                        ),
    
                    ),

                    // Wrap the slides a container.
                    el('div', { className: 'sidebar-slides', 'data-meta': details.metaKey },
                        createSlides()
                    )
                )
    
            )    

        )
    }

    // Register the sidebar
    wp.plugins.registerPlugin( 'karri-slider-sidebar', {
        render: sliderSidebar,
        icon: wpSides.icons.slider
    } );

})();
