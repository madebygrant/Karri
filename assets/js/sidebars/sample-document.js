(async () => {

    let { wpSides } = await import(wpSidesPlugin.load); // Import the WP Sides controls

    const { __ } = wp.i18n;
    const el = wp.element.createElement;
    const { Fragment } = wp.element;

    const DocumentSidebarExtended = () => {

        const details = {
            metaKey: '_doc_sidebar_meta'
        };

        // Get the post type and page template
        const get = {
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
            el(Fragment, {},

                el(
                    wpSides.DocumentPanel,
                    {
                        className: 'doc-sidebar-opt',
                        title: __('Document Sidebar Option')
                    },
                    
                    el( 
                        wpSides.groupControl('text'),
                        {
                            title : __('Sample Text'),
                            metaKey: details.metaKey,
                        }
                    ),
                )
                
            )
        )
    }

    // -------------------------------------------

    // Add to document sidebar
    wp.plugins.registerPlugin( 'document-sidebar-extended', {
        render:  DocumentSidebarExtended,
        icon: ''
    } );

})();