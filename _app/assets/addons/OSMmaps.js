// @file | OpenStreetMap and Leaflet
// @url https://madebygrant.com

const OpenStreetMap = {

    // -- Create the map
    create: function(id, options){

        // Variables

        let map, layer, dragging = false,  wheelZoom = false, zoomCtrl  = false, markers = [], locations = [];

        const elem = document.querySelector('#'+id);
        const elemParent = elem.parentNode.parentNode;
        const zoom = parseInt(elem.getAttribute('data-zoom'));
        const enableMarker = elem.getAttribute('data-markers');
        const drag = elem.getAttribute('data-drag');
        const locAttr = elem.getAttribute('data-locations');
        
        // Get the locations and sort the data
        const loc = locAttr.split('|');
        loc.forEach(function(item, index) {
            let current = [];
            let currentLoc = item.split(',');

            current.push( Number(currentLoc[0]) );
            current.push( Number(currentLoc[1]) );
            if(currentLoc[2]){
                current.push( currentLoc[2] );
            }
            else{
                current.push( '' );
            }
            if(currentLoc[3]){
                current.push( currentLoc[3] );
            }
            else{
                current.push( '' );
            }
            locations.push(current);
        });

        // Drag Control
        if( drag === '1' || drag === 'true' ){
            dragging = true;
            zoomCtrl = true;
            wheelZoom = true;
        }

        // Map Options
        let mapOptions = {
            twoFingerZoom: true,
            dragging: dragging,
            zoomControl: zoomCtrl,
            scrollWheelZoom: wheelZoom,
            zoom: zoom
        };

        // Setup the map
        map = new L.Map(id, mapOptions);

        // Add controls if drag not enabled
        if( drag === '0' || drag === '' ){
            L.control.pan().addTo(map);
            L.control.zoom().addTo(map);
        }

        // Create the tile layer with correct attribution
        if( typeof(items) !== 'undefined' && items.MB_api ){
            layer = L.tileLayer( 'https://api.mapbox.com/styles/v1/' + items.MB_api, 
            {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                minZoom: 10,
                maxZoom: 19,
                //id: 'mapbox.streets',
                //id: 'grantmap/cjqgfm88jj7mr2rpbd7psaybb',
                //accessToken: items.MB_api
            }).addTo(map);
        }
        else{
            layer = new L.TileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
            { 
                minZoom: 10, 
                maxZoom: 19, 
                attribution: 'Map data &copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
            });
        }
        map.addLayer( layer );

        // Create the marker/s
        if( locations ){

            locations.forEach(function(item, index) {
            
                let markerIconURL, customIcon, popupText;
                if(!item[3]){
                    markerIconURL = encodeURI("data:image/svg+xml," + options.marker);
                }
                else{
                    markerIconURL = item[3];
                }

                if(!item[2]){
                    popupText = elem.getAttribute('data-address');
                }
                else{
                    popupText = item[2];
                }

                customIcon = L.icon({
                    iconUrl: markerIconURL,
                    iconSize:     [32, 32], // size of the icon
                    iconAnchor:   [16, 32], // point of the icon which will correspond to marker's location [hor, vert]
                    popupAnchor:  [0, -32] // point from which the popup should open relative to the iconAnchor [hor, vert]
                });

                if( (enableMarker == '1' || enableMarker == 'true') && markerIconURL ){
                    //marker = L.marker([item[0], item[1]], { icon: customIcon }).addTo(map);
                    let marker = L.marker([item[0], item[1]], { icon: customIcon });
                    if( popupText && (enableMarker == '1' || enableMarker == 'true') ){
                        marker.bindPopup( popupText );
                    }
                    markers.push( marker );
                }

            });

            // Multiple Markers
            if( locations.length > 1 ){
                var group = L.featureGroup(markers).addTo(map);
                setTimeout(function () {
                    map.fitBounds(group.getBounds(), {padding: [50, 50]});
                }, 1000);
            }

            // Single Marker
            else if( locations.length == 1 ){
                map.setView( markers[0]._latlng, zoom );
                markers[0].addTo(map);
            }
        }
    },

    /*
        Leaflet.twofingerzoom, enables zooming the map out with a two finger tap on touch devices
        (c) 2014, Adam Ratcliffe, TomTom International BV
    */
    leafletZoom: function(){
        if( document.body.classList.contains('leaflet-active') && !document.body.classList.contains('leaflet-zoom') ){
            L.Map.mergeOptions({twoFingerZoom:L.Browser.touch&&!L.Browser.android23});L.Map.TwoFingerZoom=L.Handler.extend({statics:{ZOOM_OUT_THRESHOLD:100},_touchStartTime:[],addHooks:function(){L.DomEvent.on(this._map._container,"touchstart",this._onTouchStart,this);L.DomEvent.on(this._map._container,"touchend",this._onTouchEnd,this)},removeHooks:function(){L.DomEvent.off(this._map._container,"touchstart",this._onTouchStart,this);L.DomEvent.off(this._map._container,"touchend",this._onTouchEnd,this)},_onTouchStart:function(e){var touches=e.touches;if(touches.length!==2){return}this._touchStartTime=new Date},_onTouchEnd:function(e){var map=this._map,touches=e.changedTouches;if(!this._touchStartTime){return}L.DomEvent.preventDefault(e);if(new Date-this._touchStartTime<=L.Map.TwoFingerZoom.ZOOM_OUT_THRESHOLD){this._touchStartTime=null;map.zoomOut()}}});L.Map.addInitHook("addHandler","twoFingerZoom",L.Map.TwoFingerZoom);
            document.body.classList.add('leaflet-zoom');
        }
    },

    init: function(el, options){

        // Default Options
        const defaults = {
            marker: '<svg class="osm-marker" height="1792" viewBox="0 0 1792 1792" width="1792" xmlns="http://www.w3.org/2000/svg"><path d="M1152 640q0-106-75-181t-181-75-181 75-75 181 75 181 181 75 181-75 75-181zm256 0q0 109-33 179l-364 774q-16 33-47.5 52t-67.5 19-67.5-19-46.5-52l-365-774q-33-70-33-179 0-212 150-362t362-150 362 150 150 362z"/></svg>',
        };

        let settings = Object.assign(defaults, options);

        if( el.length > 0 ){
            __OpenStreetMap.leafletZoom();
            el.forEach(function (map, n) {
                let elemID = 'map-'+n;
                map.setAttribute('id', elemID);
                __OpenStreetMap.create(elemID, settings);
            });
        }
        
    }

}

export default OpenStreetMap;