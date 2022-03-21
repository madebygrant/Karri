// @file | Accordion
// @url https://madebygrant.com

class Accordion {

    constructor(selector, options){

        let defaults = {
            speed: 450,
            firstOpen: false,
            allOpen: false,
            trigger: '',
            content: '',
            slideToggle: true
        };

        this.vars = {
            options: Object.assign(defaults, options),
            self: this,
            items: document.querySelectorAll(selector) 
        }

        this.init();

    }

    init(){
        let trigger, content;

        this.vars.items.forEach( (item, index) => {

            let accItems = item.children,
                firstOpen = this.vars.options.firstOpen,
                allOpen = this.vars.options.allOpen,
                n = 0,
                opened = [];
            
            item.style.visibility = 'visible';
            item.classList.add('loading');
            item.classList.add('accordion-' + index);

            if( item.classList.contains('first-open') ){
                firstOpen = true;
            }
            
            if( item.classList.contains('all-open') ){
                allOpen = true;
                firstOpen = false;
            }

            for(let child of accItems){
                
                child.classList.add('acc-item-' + n);

                trigger = this.vars.options.trigger == '' ? child.children[0] : child.querySelector(this.vars.options.trigger);
                content = this.vars.options.content == '' ? child.children[1] : child.querySelector(this.vars.options.content);
                
                content.classList.add('acc-content');
                trigger.classList.add('acc-trigger');

                let triggerInner = document.createElement('div');
                triggerInner.classList.add('acc-trigger__inner');
                trigger.append(triggerInner);

                for (let i = 0; i < trigger.children.length; i++) {
                    triggerInner.append(trigger.children[i]);
                }

                let icon = document.createElement('div');
                icon.classList.add('acc-icon');
                triggerInner.append(icon);
                
                if(firstOpen === true && n === 0){
                    child.classList.add('item-active');
                    trigger.classList.add('trigger-active');
                    content.classList.add('content-active');
                    content.style.display = 'block';
                }

                if(allOpen === true){
                    child.classList.add('item-active');
                    trigger.classList.add('trigger-active');
                    content.classList.add('content-active');
                    content.style.display = 'block';
                }

                dawn.on('click', trigger, function(e){

                    e.stopPropagation();
                    const that = e.currentTarget,
                        parent = that.parentNode,
                        grandparent = that.parentNode.parentNode,
                        childSiblings = parent.children,
                        siblingContent = childSiblings[1];

                    siblingContent.classList.contains('content-active') ? siblingContent.classList.remove('content-active') : siblingContent.classList.add('content-active');
                    that.classList.contains('trigger-active') ? that.classList.remove('trigger-active') : that.classList.add('trigger-active');
                    
                    if( this.vars.options.slideToggle === true ){
                        parent.classList.toggle('item-active');
                        dawn.slideToggle(siblingContent, this.vars.options.speed);
                    }
                    else{
                        parent.classList.toggle('item-active');
                    }

                    siblingContent.classList.contains('content-active') ? opened.push(1) : opened.pop();

                    (opened.length > 0) ? grandparent.classList.add('accordion-active') : grandparent.classList.remove('accordion-active');

                }.bind(this), false, true);

                n++;
            }

            item.classList.remove('loading');
            
            setTimeout( function(){
                item.classList.add('loaded');
                item.style.visibility = 'visible';
            }.bind(item), 300);
            
        });
        
    }
}

export default Accordion;