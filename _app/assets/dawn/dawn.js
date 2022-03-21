// @file | Dawn - Micro JS library and add appropriate classes/attributes to elements in the theme - Version: 0.2
// @url https://madebygrant.com

// # -- Polyfills --

// ** closest
if (window.Element && !Element.prototype.closest) {
    Element.prototype.closest = function(s) {
        var matches = (this.document || this.ownerDocument).querySelectorAll(s),
            i, el = this;
        do {
            i = matches.length;
            while (--i >= 0 && matches.item(i) !== el) {};
        } while ((i < 0) && (el = el.parentElement));
        return el;
    };
}

// ** forEach
Array.prototype.forEach||(Array.prototype.forEach=function(r){var o,t;if(null==this)throw new TypeError("this is null or not defined");var n=Object(this),e=n.length>>>0;if("function"!=typeof r)throw new TypeError(r+" is not a function");for(arguments.length>1&&(o=arguments[1]),t=0;t<e;){var i;t in n&&(i=n[t],r.call(o,i,t,n)),t++}});

/// ------------------------------------------------------------

// # -- JS Helpers --

    // ** Selectors
    
const d_ = (selector, scope) => {
        scope = scope ? scope : document;
        return scope.querySelector(selector);
    },

    dd_ = (selector, scope) => {
        scope = scope ? scope : document;
        return scope.querySelectorAll(selector);
    };

/// ------------------------------------------------------------

// # ------ Dawn ------

const dawn = {

    ready : (fn) => {
        if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading"){
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    },

    loaded : (fn) => {
        window.addEventListener('load', fn);
    },

    // ** If element exists
    exists : (el) => {
        return typeof el !== 'undefined' && el !== null ? true : false;
    },

    // ** On
    on : (events, elem, callback, capture = false) => {
        //let touch = ( ('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch ) ? true : false;
        let touch = dawn.isTouch();
        if (typeof elem === 'function') {
            capture = callback;
            callback = elem;
            elem = window;
        }
        capture = capture ? true : false;
        (typeof elem === 'string') ? document.querySelector(elem) : elem;
        if (!elem) return;
        events = (touch && events === 'click') ? 'touchstart' : 'click';
        ( typeof events === 'string' ) ? elem.addEventListener(events, callback, capture) : events.forEach(function(event) { elem.addEventListener(event, callback , capture); });
        
    },

    css: ( el, styles ) => {
        if( typeof styles === 'object' ){ for(let property in styles){ el.style[property] = styles[property] } }
        else{ return undefined; }
    },

    // ** Remove item
    remove : (el) => {
        el.parentNode.removeChild(el);
    },

    // ** Clone element
    clone : (el, string) => {
        if( string == true ){
            let clone = el.cloneNode(true);
            return clone.outerHTML;
        }else{
            return el.cloneNode(true);
        }
    },

    // ** Add element
    addElement : (el, position, html) => {
        if(typeof html === 'string'){
            let pos;
            switch(position){
                case 'append':
                    pos = 'beforeend';
                    break;
                case 'prepend':
                    pos = 'afterbegin';
                    break;
                case 'before':
                    pos = 'beforebegin';
                    break;
                case 'after':
                    pos = 'afterend';
                    break;
            }
            el.insertAdjacentHTML(position, html);
        }
        else if(typeof html === 'object'){
            switch(position){
                case 'append':
                    el.append(html);
                    break;
                case 'prepend':
                    el.prepend(html);
                    break;
                case 'before':
                    el.parentNode.insertBefore(html, el);
                    break;
                case 'after':
                    el.parentNode.insertBefore(html, el.nextSibling);
                    break;
            }
        }
    },

    // ** Create Element
    createElement : (type, data) => {
        if(type){
            const el = document.createElement(type);
            if(data.attr.length != 0){
                for (let i in data.attr) {
                    el.setAttribute(i, data.attr[i]);
                }
            }
            if(data.html){
                el.innerHTML = data.html;
            }
            return el;
        }
    },

    wrapInner : (el, wrapper, _class) => {
        wrapper = typeof wrapper === "string" ? document.createElement(wrapper) : document.createElement('div');
        typeof _class != 'undefined' ? wrapper.classList.add(_class) : '';
        el.parentNode.appendChild(wrapper);
        wrapper.appendChild(el);
    },

    // ** Next sibling
    nextSibling : (el, selector) => {
        let sibling = el.nextElementSibling;
        if (!selector) return sibling;
        while (sibling) {
            if (sibling.matches(selector)) return sibling;
            sibling = sibling.nextElementSibling
        }
    },

    attrs : (el) =>{
        var data = {};
        [].forEach.call(el.attributes, function(attr) {
            var camelCaseName = attr.name.substr(5).replace(/-(.)/g, function ($0, $1) {
                return $1.toUpperCase();
            });
            data[camelCaseName] = attr.value;
        });
        return data;
    },

    dataAttrs : (el) =>{
        var data = {};
        [].forEach.call(el.attributes, function(attr) {
            if (/^data-/.test(attr.name)) {
                var camelCaseName = attr.name.substr(5).replace(/-(.)/g, function ($0, $1) {
                    return $1.toUpperCase();
                });
                data[camelCaseName] = attr.value;
            }
        });
        return data;
    },

    // ** Get URL parameters
    URLparameters : () => {
        const params =  window.location.search.substring(1).split("&").map(v => v.split("=")).reduce((map, [key, value]) => map.set(key, decodeURIComponent(value)), new Map());
        return !params.get('') ? params : false;
    },

    // ** Extend properties
    extend : (source, properties) => {
        var property;
        for(property in properties) {
            if( properties.hasOwnProperty(property)) {
                source[property] = properties[property];
            }
        }
        return source;
    },
    
    // ** Debounce
    debounce : (func, wait, immediate) => {
        let timeout, result;
        return function() {
            const context = this, args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) result = func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) result = func.apply(context, args);
            return result;
        };
    },

    // ** If a dark colour
    isDarkColour : function(bgColor) {
        var color = (bgColor.charAt(0) === '#') ? bgColor.substring(1, 7) : bgColor;
        var r = parseInt(color.substring(0, 2), 16); // hexToR
        var g = parseInt(color.substring(2, 4), 16); // hexToG
        var b = parseInt(color.substring(4, 6), 16); // hexToB
        return (((r * 0.299) + (g * 0.587) + (b * 0.114)) > 186) ? false : true;
    },

    // ** Performance Test
    perfTest : (name, method) => {
        console.time(`Method - ${name}`);
        method.apply();
        console.timeEnd(`Method - ${name}`);
    },

    // ** Touch Screen Detection
    isTouch: () => {
        return ( ('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch ) ? true : false;
    },

    // ** Mobile OS Detection
    isMobile : {
        Android: () => { return navigator.userAgent.match(/Android/i);},
        BlackBerry: () => {return navigator.userAgent.match(/BlackBerry/i);},
        iOS: () => {return navigator.userAgent.match(/iPhone|iPad|iPod/i);},
        Opera: () => {return navigator.userAgent.match(/Opera Mini/i);},
        Windows: () => {return navigator.userAgent.match(/IEMobile/i);},
        any: () => {return (dawn.isMobile.Android() || dawn.isMobile.BlackBerry() || dawn.isMobile.iOS() || dawn.isMobile.Opera() || dawn.isMobile.Windows());}
    },

    // ** OS Detection
    isOS : {
        Mac: () => { return navigator.userAgent.match(/Macintosh|MacIntel|MacPPC|Mac68K/i);},
        Windows: () => { return navigator.userAgent.match(/Win32|Win64|Windows|WinCE/i);},
        any: () => { return (dawn.isOS.Mac() || dawn.isOS.Windows()); }
    },

    /// ----------------------------------

    // ** Animation: Slide Up
    slideUp: (el, duration = 500) => {
        el.style.transitionProperty = 'height, margin, padding';
        el.style.transitionDuration = duration + 'ms';
        el.style.boxSizing = 'border-box';
        el.style.height = el.offsetHeight + 'px';
        el.offsetHeight;
        el.style.overflow = 'hidden';
        el.style.height = 0;
        el.style.paddingTop = 0;
        el.style.paddingBottom = 0;
        el.style.marginTop = 0;
        el.style.marginBottom = 0;
        window.setTimeout( () => {
            el.style.display = 'none';
            el.style.removeProperty('height');
            el.style.removeProperty('padding-top');
            el.style.removeProperty('padding-bottom');
            el.style.removeProperty('margin-top');
            el.style.removeProperty('margin-bottom');
            el.style.removeProperty('overflow');
            el.style.removeProperty('transition-duration');
            el.style.removeProperty('transition-property');
        }, duration);
    },

    // ** Animation: Slide Down
    slideDown: (el, duration = 500) => {
        el.style.removeProperty('display');
        let display = window.getComputedStyle(el).display;

        if (display === 'none')
        display = 'block';

        el.style.display = display;
        let height = el.offsetHeight;
        el.style.overflow = 'hidden';
        el.style.height = 0;
        el.style.paddingTop = 0;
        el.style.paddingBottom = 0;
        el.style.marginTop = 0;
        el.style.marginBottom = 0;
        el.offsetHeight;
        el.style.boxSizing = 'border-box';
        el.style.transitionProperty = "height, margin, padding";
        el.style.transitionDuration = duration + 'ms';
        el.style.height = height + 'px';
        el.style.removeProperty('padding-top');
        el.style.removeProperty('padding-bottom');
        el.style.removeProperty('margin-top');
        el.style.removeProperty('margin-bottom');
        window.setTimeout( () => {
            el.style.removeProperty('height');
            el.style.removeProperty('overflow');
            el.style.removeProperty('transition-duration');
            el.style.removeProperty('transition-property');
        }, duration);
    },

    // ** Animation: Slide Toggle
    slideToggle: (el, duration = 500) => {
        if (window.getComputedStyle(el).display === 'none') {
            return dawn.slideDown(el, duration);
        } else {
            return dawn.slideUp(el, duration);
        }
    },

    fadeIn: (el, duration, display) => {
        let step = 25/(duration || 300);
        el.style.opacity = el.style.opacity || 0;
        el.style.display = ( display ) ? display : "block";
        (function fade() { (el.style.opacity = parseFloat(el.style.opacity)+step) > 1 ? el.style.opacity = 1 : setTimeout(fade, 25); })();
    },

    fadeOut: (el, duration) => {
        let step = 25/(duration || 300);
        el.style.opacity = el.style.opacity || 1;
        (function fade() { (el.style.opacity -= step) < 0 ? el.style.display = "none" : setTimeout(fade, 25); })();
    },

    /// ----------------------------------

    // # Sunrise - Setup the theme
    sunrise : () => {

        const htmlTag = d_('html'),
            inactiveLinks = dd_('a[href="#"], .inactive > a'),
            newTabLinks = dd_('a[target="_blank"]'),
            currentLink = dd_('.site-menu nav a[href="'+window.location+'"]'),
            imageLinks = dd_('a img'),
            glyphs = dd_( '.sr-hide, .glyph' ),
            selectFields = dd_('select'),
            layout = d_('.layout'),
            entryContent = dd_( '.content--entry' );

            /// -------------------------------

            // ** Remove no JavaScript class
            htmlTag.classList.remove('no-js');

            /// -------------------------------

            // ** Setup classes for elements in the entry content
            entryContent.forEach(function(item) {

                // + Headings
                for (let i = 1; i <= 6; i++) {
                    let headings = item.getElementsByTagName('h' + i);
                    if( headings.length > 0 ){
                        for (let x = 0; x < headings.length; x++) {
                            headings[x].classList.add("heading", "heading--entry");
                        }
                    }
                }
    
                // + Buttons
                let buttons = item.querySelectorAll('.wp-block-button__link, input[type="submit"], input[type="button"], input[type="reset"], button');
                if( buttons.length > 0 ){
                    for (let x = 0; x < buttons.length; x++) {
                        buttons[x].classList.add("btn", "btn--entry");
    
                        if( buttons[x].parentNode.classList.contains('wp-block-button') ){
                            buttons[x].classList.add("btn--block");
                        
                            var styles = [...buttons[x].parentNode.classList].filter(function($class){
                                if($class) {
                                    return ($class.substring(0, 'is-style'.length) === 'is-style');
                                }
                            });
                            if(styles[0]){
                                buttons[x].classList.add('btn--' + styles[0]);
                            }
                        }
                    }
                }

            });

            /// -------------------------------

            // ** Setup grid classes
            // ? Flex Grids, automatically add bottom margins to the children if it overflows to another line
            // ? Determine if an odd or even amount of children within them
            
            dd_('.flex:not(.gaps--below-none)').forEach(function(item) {  
                var childrenNo = item.children.length,
                    i;
    
                for( i = 0; i < 12; i++){
                    if( item.classList.contains('columns--'+i) && i < childrenNo ){
                        item.classList.add('gaps--below-none');
                    }
                }
    
            });
    
            dd_('.flex, .grid').forEach(function(item) {
                
                var classPrefix = item.classList.contains('grid') ? 'grid' : 'flex',
                    excluded = item.querySelectorAll('.dont-count'),
                    excludedNo = excluded ? excluded.length : 0,
                    childrenNo = item.children.length - excludedNo;
    
                if( childrenNo % 2 == 0){
                    item.classList.add(classPrefix + '--children-even');
                }
                else{
                    item.classList.add(classPrefix + '--children-odd');
                }
                
            });

            /// -------------------------------

            // + WordPress Columns
            dd_('.wp-block-columns').forEach(function(columns){
                if( columns.children.length % 2 == 0 ){
                    columns.classList.add('wp-block-columns--even');
                }
                else{
                    columns.classList.add('wp-block-columns--odd');
                }
            });

            /// -------------------------------

            // ** Inactive links
            if( inactiveLinks.length > 0 ){
                inactiveLinks.forEach(function(item) {
                    item.classList.add('inactive-link');
                    item.onclick = function(){ return false; };
                });
            }

            /// -------------------------------

            // ** Prevent XSS
            if(newTabLinks.length > 0){
                newTabLinks.forEach(function(item) {  
                    if( item.getAttribute('rel') !== 'noopener noreferrer' ){
                        item.setAttribute( 'rel', 'noopener noreferrer' );
                    }
                });
            }

            /// -------------------------------

            // ** Menus
            // + Detect drop-down menu or not
            dd_('.site-menu nav ul').forEach(function(item) {
                let hasChildren = false;
                for(let child of item.children){
                    if( child.classList.contains('menu-item-has-children') && hasChildren === false ){
                        child.closest('nav').parentNode.classList.add('is-drop-down');
                        hasChildren = true;
                    }
                };
            });

            // + Aria Labels
            dd_( '.site-menu nav li[aria-expanded="false"]').forEach(function(item) {
                item.addEventListener('mouseenter', function(e){
                    e.target.setAttribute( 'aria-expanded', 'true' );
                }, false);
                item.addEventListener('focus', function(e){
                    e.target.setAttribute( 'aria-expanded', 'true' );
                }, false);
            });

            dd_('.site-menu nav li[aria-expanded="false"]').forEach(function(item) {
                item.addEventListener('mouseleave', function(e){
                    e.target.setAttribute( 'aria-expanded', 'false' );
                }, false);
                item.addEventListener('blur', function(e){
                    e.target.setAttribute( 'aria-expanded', 'false' );
                }, false);
            });

            // + Detect Current Page Link
            if( currentLink.length > 0 ){
                currentLink.forEach(function(item) { 
                    item.classList.add('current-page-link');
                });
            }

            /// -------------------------------

            // ** Image Links
            if( imageLinks.length > 0 ){
                imageLinks.forEach(function(item) {
                    item.parentNode.classList.add('link--image');
                });
            }

            /// -------------------------------

            // ** Adds the 'aria-hidden="true"' attribute to elements with the class(es)
            if( glyphs.length > 0 ){
                glyphs.forEach(function(item) {
                    item.attr('aria-hidden', true);
                });
            }

            /// -------------------------------

            // ** Is a popular mobile OS
            if(dawn.isMobile.any()){
                htmlTag.classList.add('is-mobile-OS');
                if(dawn.isMobile.Android()){htmlTag.classList.add('android-mobile-OS');}
                if(dawn.isMobile.BlackBerry()){htmlTag.classList.add('blackberry-mobile-OS');}
                if(dawn.isMobile.iOS()){htmlTag.classList.add('ios-mobile-OS');}
                if(dawn.isMobile.Opera()){htmlTag.classList.add('opera-mobile-OS');}
                if(dawn.isMobile.Windows()){htmlTag.classList.add('windows-mobile-OS');}
            }
            else{
                htmlTag.classList.add('not-mobile-OS');
            }

            // ** Select boxes
            if( dawn.isMobile.iOS() && selectFields.length > 0 ){
                selectFields.forEach(function(item) {
                    item.classList.add('iOS-select');
                });
            }
            else if( dawn.isOS.Mac() && selectFields.length > 0 ){
                selectFields.forEach(function(item) {
                    item.classList.add('macOS-select');
                });
            }

        }
    }

/// ------------------------------------------------------------

// # -- Intialise --
window.onload = d_('html').classList.remove('website-loading');
dawn.ready( () => {
    if( !document.body.classList.contains('no-sunrise') ){
        dawn.sunrise();
    }
});