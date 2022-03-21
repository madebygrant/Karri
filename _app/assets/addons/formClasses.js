// @file | Form Classes
// @url https://madebygrant.com

class FormClasses {

    constructor(selector){
        this.selector = document.querySelectorAll(selector);
        this.init();
    }

    init(){
        const textStyleInputs = [
            'text', 'date', 'datetime-local', 'email', 'month', 'number', 'password', 'tel', 'time', 'url', 'week'
        ];

        this.selector.forEach( (form) => {

            let inputs = form.querySelectorAll('input, textarea, select'),
                labels = form.querySelectorAll('label'),
                buttons = form.querySelectorAll('button');

            inputs.forEach( (input) => {
                if( typeof input.type != 'undefined' && input.type != '' ){
                    
                    if( input.type == 'submit' || input.type == 'reset' || input.type == 'button' ){
                        input.classList.add('btn', 'btn--form', 'btn--input', 'btn--input-' + input.type);
                    }
                    else{
                        if( textStyleInputs.includes(input.type) ){
                            input.classList.add('input', 'input--text', 'input--' + input.type);
                        }
                        else{
                            input.type.includes('select') ? input.classList.add('input', 'input--select', 'input--' + input.type) : input.classList.add('input', 'input--' + input.type);
                        }
                    }
                }
                else if(input.tagName == 'BUTTON'){
                    input.classList.add('btn', 'btn--input');
                }

            });
            
            buttons.forEach( (button) => {
                let type = typeof button.type != 'undefined' ? 'btn--' + button.type : '';
                button.classList.add('btn', 'btn--form', type);
            });

            labels.forEach( (label) => {
                label.classList.add('label');
            });

        })

    }

}

export default FormClasses;