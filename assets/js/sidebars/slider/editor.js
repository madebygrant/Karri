const isJSON = (str) => {
    try { return (JSON.parse(str) && !!str); } 
    catch (e) { return false; }
};

// Meta related
const meta = {
    get: () => {
        const metaKey = document.querySelector('.sidebar-slides').getAttribute('data-meta');
        let getMeta = wp.data.select('core/editor').getEditedPostAttribute('meta')[metaKey];
        return isJSON(getMeta) ? JSON.parse(getMeta) : {};
    },
    remove: (id) => {
        const metaKey = document.querySelector('.sidebar-slides').getAttribute('data-meta');
        data = meta.get();
        delete data[id];
        wp.data.dispatch('core/editor').editPost({ meta: { [metaKey]: JSON.stringify(data) } });
    },
    update: (id, value) => {
        const metaKey = document.querySelector('.sidebar-slides').getAttribute('data-meta');
        data = meta.get();
        data[id] = value;
        wp.data.dispatch('core/editor').editPost({ meta: { [metaKey]: JSON.stringify(data) } });
    }
}

// Sort the panels positions
const sortSlidePanels = () => {
    const data = meta.get();
    const orderData = data.slides_positions ? ( data.slides_positions.includes(',') ? data.slides_positions.split(',') : [data.slides_positions] ) : [];
    const slidesContainer = document.querySelector('.sidebar-slides');

    if(orderData.length > 0 && slidesContainer.children.length > 0){
        let newOrder = [];
   
        // Get the panels and save them in an array
        orderData.forEach((key) => {
            const slide = document.getElementById('sidebar-slide-' + key);
            newOrder.push(slide);
        });

        if( newOrder.length > 0 ){
            slidesContainer.innerHTML = ''; // Remove all the current panels

            newOrder.forEach((panel) => {
                if(panel instanceof Element){
                    slidesContainer.appendChild(panel); // Loop and add the panels via the new order
                }
            });

            // To prevent FOUC
            /*
            setTimeout(() => {
                slidesContainer.style.opacity = 1;
            }, 100);
            */

        }

    }

};

window.addEventListener('load', () => {
    // Reorder the panels to their correct positions
    sortSlidePanels();

    // Drag and drop with draggable.js
    const swappable = new Draggable.Swappable(document.querySelectorAll('.sidebar-slides'), {
        draggable: '.sidebar-slides__item',
        delay: 100,
        mirror: {
            appendTo: '.sidebar-slides',
            constrainDimensions: true,
        },
        plugins: [Draggable.Plugins.ResizeMirror],
    });

    swappable.on('swappable:stop', () => {
        let order = [];
        document.querySelectorAll('.sidebar-slides__item').forEach( (item) => {
            let id = item.getAttribute('data-id');
            if( order.indexOf(id) == -1 ){
                order.push(id);
            }
        });
        order = order.join(",");
        meta.update('slides_positions', order);
    });

});