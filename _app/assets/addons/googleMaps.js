// Google Maps - 2.0
// Author URL: http://www.madebygrant.com
    
const GoogleMaps = function(options) {
    const mapCanvas = this,
        bounds = new google.maps.LatLngBounds();

    let defaults = {
            canvas: '#map-canvas-1',
            styles: '',
            map_type_id: google.maps.MapTypeId.ROADMAP,
            disable_default_UI: false,
            enable_scrollwheel: false,
            enable_custom_markers: true
        },
        settings = Object.assign(defaults, options);

    const init = function() {
        
        let map, maps_icon,
            map_canvas = document.querySelector(settings.canvas),
            locations = JSON.parse(map_canvas.getAttribute('data-coords')),
            zoom_amount = Number(map_canvas.getAttribute('data-zoom'));
            
        if(settings.enable_custom_markers === true){
            maps_icon = map_canvas.getAttribute('data-icon');
        }

        let mapOptions = {
            zoom: zoom_amount,
            disableDefaultUI: settings.disable_default_UI,
            scrollwheel:  settings.enable_scrollwheel,
            mapTypeId: settings.map_type_id,
        };

        if(settings.styles){
            mapOptions.styles = settings.styles;
        }

        map = new google.maps.Map(map_canvas, mapOptions);

        map.fitBounds(bounds); //

        google.maps.event.addListenerOnce(map, 'idle', function() {
            if (locations.length == 1) {
                map.setZoom(mapOptions.zoom);
            }
        });

    // --------------------------------------------------------------------------------------------------

        // Custom Map Markers
        let marker;
        const infowindow = new google.maps.InfoWindow();
        
        /*
        // Markers with Labels
        for (i = 0; i < locations.length; i++) {

            marker = new MarkerWithLabel({
                position: new google.maps.LatLng(locations[i]['lat'], locations[i]['long']),
                map: map,
                icon: maps_icon,
                
                //labelContent: locations[i]['name'],
                //labelClass: "marker-label", // the CSS class for the label
                //labelAnchor: new google.maps.Point(-20, 40),
                //labelStyle: {opacity: 0.75},
            });
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                infowindow.setContent(locations[i]['name']);
                infowindow.open(map, marker);
                }
            })(marker, i));

            bounds.extend(marker.position);
        }
        */

        // Default Markers
        for (i = 0; i < locations.length; i++) { 
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i]['lat'], locations[i]['long']),
                map: map,
                icon: maps_icon
            });
            if(locations[i]['content']){
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infowindow.setContent("<div class='marker-info'><span class='mi-name'>"+locations[i]['name']+"</span><span class='mi-content'>"+locations[i]['content']+"</span></div>");
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
            else{
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infowindow.setContent(locations[i]['name']);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }

            bounds.extend(marker.position);
        }
        

        // Center the map and adjust the zoom when resizing the window
        google.maps.event.addDomListener(window, 'resize', function() {
            
            // If more than one marker
            if( locations.length > 1 ){
                google.maps.event.trigger(map, "resize");
                map.fitBounds(bounds);
            }
            // If only one marker
            else {
                var center = map.getCenter();
                google.maps.event.trigger(map, "resize");
                map.setCenter(center);
            }
        });
        
    }
    init();

};

export default GoogleMaps;