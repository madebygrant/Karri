document.addEventListener( 'DOMContentLoaded', () => {

    // Setup the sliders 
    // It uses Tiny Slider 2, https://github.com/ganlanyuan/tiny-slider

    const allSliders = document.querySelectorAll('.slider');

    if( allSliders.length > 0 ){
        let sliders = []; // Catalog the sliders on the page
        
        allSliders.forEach((item, index) => {

            const slides = item.querySelectorAll('.slide'),
                isLazy = item.classList.contains('slider--lazy'),
                itemDelay = item.getAttribute('data-delay');

            let options = {
                container: item,
                mode: 'carousel',
                speed: 500,
                autoplayTimeout: itemDelay != null ? parseInt(itemDelay) : 3500,
                items: 1,
                slideBy: 'page',
                autoplay: true,
                autoHeight: true,
                autoplayHoverPause: false,
                controlsText: ['', ''],
                controlsPosition: 'bottom',
                nav: true,
                navPosition: 'bottom',
                autoplayButtonOutput: false,
                touch: (slides.length > 1) ? true: false
            };

            // Is lazy
            if( isLazy ){
                options.lazyload = true;
                options.lazyloadSelector = '.slide__image--lazy';
            }

            // Initialise slider
            sliders[index] = tns(options);

            // Pause on hover
            item.addEventListener('mouseenter', () => {
                sliders[index].pause();
            });
            item.addEventListener('mouseleave', () => {
                sliders[index].play();
            });

        });

        // ** Independent Next and Previous Buttons
        /*
        document.querySelectorAll('.slider-navigation__button').forEach((item) => {
            item.addEventListener('click', (ev) => {
                const $self = ev.currentTarget;
                const index = $self.getAttribute('data-index');
                if( $self.classList.contains('slider-navigation__button--next') ){
                    sliders[index].goTo('next');
                }
                else if( $self.classList.contains('slider-navigation__button--prev') ){
                    sliders[index].goTo('prev');
                }
            }, false);
        });
        */

    }

});

window.addEventListener('load', () => {

    // Slider Next/Prev buttons - Improve Accesibility

    const sliderNavButtons = document.querySelectorAll('.tns-controls button');

    sliderNavButtons.forEach((item) => {
        item.setAttribute('type', 'button');
        
        if(item.hasAttribute('data-controls') && item.getAttribute('data-controls') == 'prev'){
            item.setAttribute('aria-label', 'Previous Slide');
        }
        else if(item.hasAttribute('data-controls') && item.getAttribute('data-controls') == 'next'){
            item.setAttribute('aria-label', 'Next Slide');
        }
    });

});