// @file: Intersects, v1.0

/*
const observer = new IntersectionObserver(callback, {
    threshold: [0, 0.25, 0.5, 0.75, 1]
});
const targets = document.querySelectorAll('.content');
Array.from(targets).forEach(target => observer.observe(target));
*/

// Intersects class
class Intersects {
    
    // Class constructor
    constructor(targets){
        this._targets = targets;

        const observer = new IntersectionObserver(this.callback, {
            threshold: [0, 0.25, 0.5, 0.75, 1]
        });

        Array.from(targets).forEach(target => observer.observe(target));
    }

    callback(entries, observer) {
        const target = entries[0]['target'];
        const intersection = entries[0]['intersectionRatio'];

        //Array.from(entries).forEach(entry => entry['target'].classList.remove('viewed'));
        if(!target.classList.contains('viewed') && intersection >= 0.55){
            target.classList.add('viewed');
        }
    }

}

export default Intersects;