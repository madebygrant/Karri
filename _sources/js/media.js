window.addEventListener('load', () => {

    // Setup lightbox functionality for galleries. 
    // It uses baguetteBox, https://github.com/feimosi/baguetteBox.js

    if( document.body.classList.contains('baguetteBox-active') ){
        const galleries = document.querySelectorAll('.wp-block-gallery:not(.no-lightbox)');

        if( galleries.length > 0 ){

            galleries.forEach((gallery) => {
                const figures = gallery.querySelectorAll('figure');
                
                if(gallery.querySelectorAll('a img').length > 0){
                    gallery.classList.add('lightbox');
                }
                
                figures.forEach((fig) => {
                    let caption = fig.querySelector('figcaption');
                    caption && caption.previousSibling.tagName == 'A' ? caption.previousSibling.setAttribute('data-caption', caption.textContent) : '';
                });
            });

            baguetteBox.run('.wp-block-gallery.lightbox');
        }
    }

});