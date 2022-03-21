// Select and load scripts
import FormClasses from '../../_app/assets/addons/formClasses.js';

import Accordion from '../../_app/assets/addons/accordion.js';
import Select from '../../_app/assets/addons/select.js';
import SmoothScroll from '../../_app/assets/addons/smoothScroll.js';
import { Tiled, TiledGallery } from '../../_app/assets/addons/tiled.js';

import Intersects from '../../_app/assets/addons/intersects.js';


window.addEventListener( 'DOMContentLoaded', () => {

    // -- Add classes to form inputs to standardise styling --

   // Ninja Forms
   if( typeof document.querySelector('.nf-form-wrap') != 'undefined' && typeof jQuery != 'undefined' ){
        jQuery(document).on( 'nfFormReady', function() {
            new FormClasses('.nf-form-wrap');
        });
    }

    // Other forms
    new FormClasses('.content form');

    new Intersects(document.querySelectorAll('img')); 

    // ----------------------------

    // -- Nice Select Boxes

    //new Select('.form-item--select');

    /*
    These are the 'Nice Select Boxes' default settings:

        parameter: '',                          The parameter within the URL to check if it's the active option
        hiddenInput: true                       Include a hidden input field to put the selection in.
        hiddenInputName: 'ns-selected-value',   Hidden input name attribute
        placeholder: 'Choose your option'       Placeholder text
        
    */

    // ----------------------------

    // -- Show & Hide Accordion
    // + Note: If wrapped in a parent element with a class '.accordion', it'll automatically run and detect the children divs or sections in it.

    //new Accordion('.an-accordion');

    /*
    These are the default settings:

        speed: 450,
        firstOpen: false,
        allOpen: false,
        trigger: '',
        content: '',
        slideToggle: true

    Example HTML:

        <div class="an-accordion">

            <div class="acc-item">
                <h3 class="title">Content Title</h3>
                <div>Content goes here.</div>
            </div>
            
            <div class="acc-item">
                <h3 class="title">Content Title</h3>
                <div>Content goes here.</div>
            </div>

        </div>
    */

    // ----------------------------   
    
    // -- Smooth scroll links
    // + The JavaScript file is not automatically enqueued. Enable it in: library/functions/child-theme.php

    /*
        siteURL: Your website URL here. It has auto-detect if none are given
    */

   //new SmoothScroll('li.smooth a');

   // ----------------------------

   // -- Tiled Grid

   /*
   These are the default settings:
       children: ''
   */

   // Non Gallery Specific
   // new Tiled('.tiled');

   // Gallery Specific
   /*
   if( document.querySelector('.wp-block-gallery.is-tiled') ){
       new TiledGallery( '.wp-block-gallery.is-tiled');
   }
   */

});
