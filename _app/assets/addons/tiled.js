// @file | Tiled Grid
// @url https://madebygrant.com

class Tiled {

    constructor(selector, options){

        let defaults = {
            children: ''
        };

        this.vars = {
            options: Object.assign(defaults, options),
            self: this,
            grid: document.querySelectorAll(selector) 
        }
        
        this.build();
        window.addEventListener('resize', dawn.debounce(this.build.bind(this), 100) );
    }

    build(){

        const settings = this.vars.options;

        //for(let item of this.vars.grid){
        this.vars.grid.forEach(function (item) {
            
            let rowSize = getComputedStyle(item).getPropertyValue("grid-auto-rows"),
                rowGap = getComputedStyle(item).getPropertyValue("grid-gap"),
                gridItems = settings.children ? item.querySelectorAll(settings.children) : item.children;

            if(rowSize){
                rowSize = parseInt(rowSize, 10);
            }
            if(rowGap){
                rowGap = parseInt(rowGap, 10);
            }

            item.classList.add('tiled--loading');

            if( typeof imagesLoaded === 'function' && dawn.exists( item.querySelector('img') ) ){
                imagesLoaded( item, () => {
                    item.classList.remove('tiled--loading');
                    item.classList.add('tiled--loaded');
                    item.classList.add('tiled--active');

                    //for(let item of gridItems){
                    [...gridItems].forEach(function (item) {
                        if(item){
                            const rowSpan = Math.ceil( (item.offsetHeight + rowGap) / (rowSize + rowGap) );
                            item.style.setProperty("--row-span", rowSpan);
                        }
                    });
                });
            }
            else{
                item.classList.remove('tiled--loading');
                item.classList.add('tiled--loaded');
                item.classList.add('tiled--active');

                [...gridItems].forEach(function (item) {
                    if(item){
                        const rowSpan = Math.ceil( (item.offsetHeight + rowGap) / (rowSize + rowGap) );
                        item.style.setProperty("--row-span", rowSpan);
                    }
                })
            }

        })
    }

}

// -- Gallery Specific
class TiledGallery {
    
    constructor(selector, options){

        let defaults = {
            children: '',
            isLazyActive: false
        };

        this.vars = {
            options: Object.assign(defaults, options),
            self: this,
            grid: document.querySelectorAll(selector) 
        }
        
        this.build();
        window.addEventListener('resize', dawn.debounce(this.build.bind(this), 100) );
    }

    
    build(){

        if( this.vars.grid.length > 0 ){

            const settings = this.vars.options;

            this.vars.grid.forEach( (currentGrid, n) => {

                const firstChild = currentGrid.firstChild;

                currentGrid.classList.add('tiled-gallery');
                currentGrid.classList.add('tiled-gallery--loading');
                firstChild.classList.add('tiled-gallery__group');

                const gridItems = settings.children != '' ? firstChild.querySelectorAll(settings.children) : firstChild.children;

                if( !settings.isLazyActive ){
                    imagesLoaded( currentGrid, function() {

                        const rowSize = parseInt(getComputedStyle(firstChild).getPropertyValue("grid-auto-rows"), 10);
                        const rowGap = parseInt(getComputedStyle(firstChild).getPropertyValue("grid-gap"), 10);

                        currentGrid.classList.add('tiled-gallery--'+ n, 'tiled-gallery--active');

                        [...gridItems].forEach(function (item) {
                            
                            item.classList.add('tiled-gallery__item');
                            const content = item.querySelector("img");
                            
                            if( content ){
                                const rowSpan = Math.ceil((content.offsetHeight + rowGap) / (rowSize + rowGap));
                                item.style.setProperty("--row-span", rowSpan);
                            }
                            else{
                                item.parentNode.removeChild(item);
                            }
                        });

                        currentGrid.classList.remove('tiled-gallery--loading');
                        currentGrid.classList.add('tiled-gallery--loaded');
                    });
                }
                else{

                    const rowSize = parseInt(getComputedStyle(firstChild).getPropertyValue("grid-auto-rows"), 10);
                    const rowGap = parseInt(getComputedStyle(firstChild).getPropertyValue("grid-gap"), 10);

                    currentGrid.classList.add('tiled-gallery--'+ n, 'tiled-gallery--active');

                    [...gridItems].forEach(function (item) {
                        const content = item.querySelector("img");
                        item.classList.add('tiled-gallery__item');

                        if( content ){
                            const rowSpan = Math.ceil((content.offsetHeight + rowGap) / (rowSize + rowGap));
                            item.style.setProperty("--row-span", rowSpan);
                        }
                        else{
                            item.parentNode.removeChild(item);
                        }
                        
                    });

                    currentGrid.classList.remove('tiled-gallery--loading');
                    currentGrid.classList.add('tiled-gallery--loaded');
                }

            });
        }

    }

}

export  { Tiled, TiledGallery};