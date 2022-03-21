// @file: Lazy Polyfill

const lazyPolyfill = (selector) => {

    const items = document.querySelectorAll(selector);

    if ('IntersectionObserver' in window) {

        let observer = new IntersectionObserver( (entries, self) => {
            entries.forEach( (entry) => {
                let img = entry.target;

                if ( entry.isIntersecting && !img.classList.contains('loaded') ) {

                    // Stop watching
                    self.unobserve(img);

                    img.src = src;
                    img.srcset = img.dataset.srcset ? img.dataset.srcset : '';
                    img.sizes = img.dataset.sizes ? img.dataset.sizes : '';

                    img.removeAttribute('data-src');
                    img.removeAttribute('data-srcset');
                    img.removeAttribute('data-sizes');
                    img.classList.add('loaded');
                }

            })
        }, config);

        items.forEach(function(item) {
            item.classList.add('lazy');
            observer.observe(item);
        });
    }

    
}

if ('loading' in HTMLImageElement.prototype) {
    const images = document.querySelectorAll('img[loading="lazy"]');
    images.forEach(img => {
        img.src = img.dataset.src;
        img.srcset = img.dataset.srcset ? img.dataset.srcset : '';
        img.sizes = img.dataset.sizes ? img.dataset.sizes : '';
        img.removeAttribute('data-src');
        img.removeAttribute('data-srcset');
        img.removeAttribute('data-sizes');
    });
}
else{
    lazyPolyfill('img[loading="lazy"]');
}