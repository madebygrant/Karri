(function ( $ ) {

    var create_slug = function(string) {
      const a = 'àáâäæãåāăąçćčđďèéêëēėęěğǵḧîïíīįìłḿñńǹňôöòóœøōõőṕŕřßśšşșťțûüùúūǘůűųẃẍÿýžźż·/_,:;'
      const b = 'aaaaaaaaaacccddeeeeeeeegghiiiiiilmnnnnoooooooooprrsssssttuuuuuuuuuwxyyzzz------'
      const p = new RegExp(a.split('').join('|'), 'g')
    
      return string.toString().toLowerCase()
        .replace(/\s+/g, '-') // Replace spaces with -
        .replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
        .replace(/&/g, '-and-') // Replace & with 'and'
        .replace(/[^\w\-]+/g, '') // Remove all non-word characters
        .replace(/\-\-+/g, '-') // Replace multiple - with single -
        .replace(/^-+/, '') // Trim - from start of text
        .replace(/-+$/, '') // Trim - from end of text
    },
  
    theme_cmb2_tabs = function(refresh){

        $('.cmb2-metabox').each( function(i, mb) {

            var metabox = $(mb),
                tabs = metabox.find('.cmb-type-tab'),
                group_field = $('.cmb-repeatable-group', metabox);

            refresh = typeof refresh === 'undefined' ? false : refresh;
            
            // Meta boxes tabs
            if ( tabs.length > 1 && !refresh ) {

                var nav = $('<ul class="tab-nav tab-nav--metabox" />');
                nav.append('<div class="tab-slider tab-slider--metabox" />');

                tabs.each( function(index) {
                    var tab = $(this);
                    nav.append('<li><a class="nav-tab" href="#' + metabox.attr('id') + '-tab-' + index + '">' + tab.find('.cmb2-metabox-title').text() + '</a></li>');
                    tab.nextUntil('.cmb-type-tab').addBack().wrapAll('<div id="' + metabox.attr('id') + '-tab-' + index + '" class="tab tab--metabox" />');
                });

                metabox.prepend(nav);
                metabox.tabs();
                
                $(".tab-nav a", this).click(function() {
                    var position = $(this).parent().position(),
                        width = $(this).parent().width();
                    $(".tab-slider", metabox).css({ "left":+ position.left, "width": width });
                });

                $(window).on('load', function(){
                    var actWidth = $("li[aria-selected='true']", metabox).width(),
                        actPosition = $("li[aria-selected='true']", metabox).position();
                    $(".tab-slider", metabox).css({ "left":+ actPosition.left, "width": actWidth });
                });
                
            }

            // Group field tabs
            group_field.each( function(fieldIndex, group) {

                var $group = $(group),
                    group_id = $group.data('groupid'),
                    group_items = $('.cmb-repeatable-grouping', $group);
                
                if ( group_items.length >= 1 ) {

                    group_items.each( function(itemIndex) {
                        var block = $(this),
                            insideDiv = $('.inside', block),
                            tabs = $('.inside .cmb-type-group-tab', block),
                            iterator = block.data('iterator');
                        

                        if( tabs.length >= 1 ){

                        var group_nav = $('<ul class="tab-nav tab-nav--group" />');
                        group_nav.append('<div class="tab-slider tab-slider--group" />');
                        
                        tabs.each( function(tabIndex) {
                            var tabTitle = $('.cmb2-metabox-title', this).text();
                            var ID = group_id + '-' + iterator + '-' + tabIndex + '-tab-' + create_slug(tabTitle);

                            if( refresh && itemIndex !== 0 ){
                                $(this).parent().removeAttr('id').attr('id', ID);
                            }
                            else{
                                $(this).nextUntil('.cmb-type-group-tab').addBack().wrapAll('<div id="' + ID + '" class="tab tab--group" />');
                            }

                            group_nav.append('<li><a class="nav-tab" href="#' + ID + '">' + tabTitle + '</a></li>');
                        });

                        if( refresh ){
                            $('.inside .tab-nav--group', block).remove();
                            insideDiv.prepend(group_nav);
                        }
                        else{
                            insideDiv.prepend(group_nav);
                            $('.cmb-remove-field-row', block).insertAfter($('.tab--group:last-of-type', block));
                        }
                    
                        $(".tab-nav--group a", block).click(function() {
                            var parent = $(this).parent(),
                                position = parent.position(),
                                width = parent.width();
                            $(".tab-slider", block).css({ "left":+ position.left, "width": width });
                        });

                        $(window).on('load', function(){
                            var actWidth = $("li[aria-selected='true']", block).width(),
                                actPosition = $("li[aria-selected='true']", block).position();
                                
                            if(actPosition && actWidth){
                                $(".tab-slider", block).css({ "left":+ actPosition.left, "width": actWidth });
                            }
                        });

                        }
                        
                    });

                    $('.inside', $group).tabs({ active: 0 });

                    if( refresh ){
                        $('.inside', $group).tabs( "destroy" );
                        $('.inside', $group).tabs({ active: 0 });
                    }

                }

            });

        });
    };

    /// --------------------------------------------------------------------------------

    // Watch the div with '.cmb-repeatable-group' class if it gets modified.
    var obs = {
        config: { childList: true },
        observer: new MutationObserver(function(mut) {
            theme_cmb2_tabs(true);
        })
    }
    $('.cmb2-metabox .cmb-type-group .cmb-repeatable-group').each( function() {
        obs.observer.observe(this, obs.config);
    });

    /// --------------------------------------------------------------------------------

    $.noConflict();

    $(function(){

        // --------------- Tabs ---------------
    
        theme_cmb2_tabs();
    
        // --------------- Toggle Switch ---------------

        // Reset for 'confirmation'
        if( $('.cmb2-switch').hasClass('confirm') ){
            $(".cmb2-switch .switch-true").attr('checked', false);
            $(".cmb2-switch .switch-false").attr('checked', true);
            $(".cmb2-switch .cmb2-disable").addClass('selected');
            $('.cmb2-switch .cmb2-enable').removeClass('selected');
        }

        $(".cmb2-switch").each( function() {
            
            var parent = $(this),
                option_to_confirm = parent.data('confirm'),
                confirm_item = $("input[name='" + option_to_confirm + '_confirmed' + "']"), 
                active_switch = $('label', parent).first(),
                tab_id;

            if( parent.hasClass('confirm') ){    
                // Make the input or textarea readonly    
                $("input[name='" + option_to_confirm + "'], textarea[name='" + option_to_confirm  + "']").attr('readonly', true);
            }
            else if( parent.hasClass('toggle') ){
                $("input[name='" + option_to_confirm + "'], textarea[name='" + option_to_confirm  + "']").addClass('hide');
            }
            else if( parent.hasClass('tab') ){
                tab_id = $("a.nav-tab[href='#"+option_to_confirm+"']").parent();
                if(active_switch.hasClass('selected')){
                    tab_id.addClass('cmb-tab-confirm');
                }
                else{
                    tab_id.addClass('cmb-tab-confirm hide');
                }
            }

            $(".cmb2-enable", parent).on('click', function(){
                $('.cmb2-disable', parent).removeClass('selected');
                $(this).addClass('selected');
                if( parent.hasClass('confirm') ){
                    confirm_item.val(1);
                    $("input[name='" + option_to_confirm + "'], textarea[name='" + option_to_confirm  + "']").attr('readonly', false);
                }
                else if( parent.hasClass('toggle') ){
                    confirm_item.val(1);
                    $("input[name='" + option_to_confirm + "'], textarea[name='" + option_to_confirm  + "']").removeClass('hide');
                }
                else if( parent.hasClass('tab') ){
                    tab_id.removeClass('hide');
                }
            });

            $(".cmb2-disable", parent).on('click', function(){
                $('.cmb2-enable', parent).removeClass('selected');
                $(this).addClass('selected');
                if( parent.hasClass('confirm') ){
                    confirm_item.val(0);
                    $("input[name='" + option_to_confirm + "'], textarea[name='" + option_to_confirm  + "']").attr('readonly', true);
                }
                else if( parent.hasClass('toggle') ){
                    confirm_item.val(0);
                    $("input[name='" + option_to_confirm + "'], textarea[name='" + option_to_confirm  + "']").addClass('hide');
                }
                else if( parent.hasClass('tab') ){
                    tab_id.addClass('hide');
                }
            });

        });

        // --------------- Google Maps: Address Search & List ---------------

        $('.cmb-type-googlemaps-location-search, .cmb-type-googlemaps-location-search-single').each(function(){
            var autocomplete, 
            current = $(this),
            search_field = $('.google-location-search', current),
            search_field_first = $('.google-location-search', current)[0],
            add_button = $('.google-location-add-button', current),
            location_list = $('.google-location-list', current),
            location_list_textarea = $('.google-location-list-textarea .google-location-list', current),
            location_list_row = $('.google-location-list-row', current),
            options = { types: ['geocode'] },
            location_list_org_value = location_list.val();

            // Run the autocomplete in the search field
            if( !location_list.hasClass('location-search-not-enabled') ){
                search_field.val(""); // Empty search field
                autocomplete = new google.maps.places.Autocomplete( search_field_first, options );
            }

            // If not activated in the Theme Customizer
            if(location_list.hasClass('location-search-not-enabled')){
                var base = window.location.protocol + "//" + window.location.host + "/",
                    path = window.location.pathname.split( '/' ),
                    nofile_path = path.slice(0,-1),
                    theme_slug = location_list.data('theme'),
                    new_url = "";

                for (var i = 0; i < nofile_path.length; i++) {
                    new_url += nofile_path[i];
                    new_url += "/";
                }    

                current.html("<span class='location-search-error'>'Google Maps: Location Search' option is not enabled. Please enable it: <a href='"+new_url+"customize.php?"+theme_slug+"'>click here</a>.");
            }

            // Put each entry on a new line in the textarea (aka Address List)
            location_list_textarea.val( " " + location_list_org_value.replace(/\]/g, ']\r\n') );
        
            // Add address to the textarea (aka Address List)
            function addLocation(location_list) {
                var location_list_value, location_list_update,
                    place = autocomplete.getPlace(),          // Get the place details from the autocomplete object.
                    lat = place.geometry.location.lat(),      // Latitude
                    long = place.geometry.location.lng(),     // Longitude
                    address = place.formatted_address,        // Address
                    name = place.name;                        // Name
                    

                // If a single text inpt field, clear it.    
                if(location_list_row.hasClass('google-location-list-single')){
                    location_list.val("");
                    location_list_update = name+ ' : ' +address+ ' : [' +lat+ ', ' +long+ ']';
                }
                else{
                    location_list_value = location_list.val(); // Textarea value
                    location_list_update = location_list_value +" "+name+' : '+address+' : ['+lat+', '+long+']\r\n'; // Updated textarea (aka Address List) value
                }

                // Update textarea (aka Address List)  
                location_list.val(location_list_update);

                // Empty search field
                search_field.val("");
                add_button.prop('disabled', true);
            }

            if($.trim(search_field.val()) == ''){
                add_button.prop('disabled', true);
            }

            search_field.on('change', function(e){
                add_button.prop('disabled', false);
            });

            // When you click on the 'Add' button
            add_button.on('click tap', function(e){
                if($.trim(search_field.val()) != ''){
                    addLocation(location_list);
                }
                return false;
                e.preventDefault();
            });
        });

    });
      
  }( jQuery ));  