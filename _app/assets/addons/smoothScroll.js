// @file | Smooth Scroll links - Version: 0.2
// @url https://madebygrant.com

class SmoothScroll {

    constructor(selector, options){

        this.defaults = {
            speed: 3500,
            offset: 0,
            siteURL: 'theme',
        };

        this.selector = selector;
        this.options = Object.assign(this.defaults, options);
        this.links = document.querySelectorAll(this.selector);

        if(this.options.siteURL === true){
            this.siteURL = window.location.origin;
        }
        else if(this.options.siteURL === 'theme' && dawn.exists(themeVars.siteURL)){
            this.siteURL = themeVars.siteURL;
        }
        else if(this.options.siteURL !== '' && this.options.siteURL !== false){
            this.siteURL = this.options.siteURL;
        }
        else{
            this.siteURL = false;
        }

        let methods = {
            init: () => {
                
                const URLparameters = dawn.URLparameters();
                const links = this.links;

                for(let link of this.links){
                    link.classList.add('smooth-link');
                    if(window.location.hash === link.hash){
                        link.classList.add('smooth-current');
                    }
                    if(this.siteURL){
                        let linkURL = link.getAttribute('href');
                        link.setAttribute('data-url', this.siteURL + '/' + linkURL.replace(/^http(s?):\/\//i, "").split('#')[0]);
                        link.setAttribute('data-hash', link.hash.replace('#',''));
                    }
                    link.setAttribute('href', link.hash);
                }

                if( URLparameters && URLparameters.has('smooth') ){
                    let el = document.querySelector('#' + URLparameters.get('smooth'));
                    methods.scrollOnLoad(el);
                }

                document.addEventListener('click', function(e) {

                    let clickedLink = e.target;
                    if( clickedLink.classList.contains('smooth-link') ){

                        let elementExists = dawn.exists( document.querySelector( e.target.hash ) );

                        if( elementExists ){
                            for(let link of links){
                                link.hash === clickedLink.hash ? link.classList.add('smooth-current') : link.classList.remove('smooth-current');
                            }
                            methods.scrollAnchors(e);
                        }
                        else{
                            window.location = clickedLink.getAttribute('data-url') + '?smooth=' + clickedLink.getAttribute('data-hash');
                        }

                        return false;
                    }
                    
                });
            },

            scrollOnLoad: (elem) => {
                history.pushState("", document.title, window.location.pathname);
                const distanceToTop = el => Math.floor(el.getBoundingClientRect().top);
                const originalTop = distanceToTop(elem) + this.options.offset;
                window.scrollBy({ top: originalTop, left: 0, behavior: 'smooth' });

                const checkIfDone = setInterval(function() {
                    const atBottom = window.innerHeight + window.pageYOffset >= document.body.offsetHeight - 2;
                    if (distanceToTop(elem) === 0 || atBottom) {
                        clearInterval(checkIfDone);
                    }
                }, this.options.speed);
            },
           
            scrollAnchors: (e, respond = null) => {
                //const nativeSmoothScroll = 'scrollBehavior' in document.documentElement.style;
                const that = e.target;
                const distanceToTop = (el) => Math.floor(el.getBoundingClientRect().top);
                const noHistory = that.hasAttribute('no-history') ? true : false;
                e.preventDefault();

                const targetID = (respond) ? respond.getAttribute('href') : that.getAttribute('href');
                const targetAnchor = document.querySelector(targetID);
                if (!targetAnchor) return;

                const originalTop = distanceToTop(targetAnchor) + this.options.offset;
                window.scrollBy({ top: originalTop, left: 0, behavior: 'smooth' });

                const checkIfDone = setInterval(function() {
                    const atBottom = window.innerHeight + window.pageYOffset >= document.body.offsetHeight - 2;
                    if (distanceToTop(targetAnchor) === 0 || atBottom) {
                        targetAnchor.tabIndex = '-1';
                        targetAnchor.focus();
            
                        // Let's make sure the History API even exists first..
                        if( !noHistory ){
                            if ('history' in window) {
                                window.history.pushState('', '', targetID);
                            } 
                            else {
                                window.location = targetID;
                            }
                        }
                        clearInterval(checkIfDone);
                    }
                }, this.options.speed);
            }

        }
        

        methods.init();
    }

}

export default SmoothScroll;