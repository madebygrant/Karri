// @file | Nice Select Boxes - Version: 0.1
// @url https://madebygrant.com

class Select {

    constructor(selector, options){
        let defaults = {
            parameter: '',
            hiddenInput: false,
            hiddenInputName: 'ns-selected-value',
            placeholder: 'Choose your option',
            pills: true
        };

        this.vars = {
            options: Object.assign(defaults, options),
            self: this,
            items: document.querySelectorAll(selector) 
        }

        this.init();
    }

    // Sort array naturally
    naturalCompare(a, b) {
        let ax = [], bx = [];
        a.replace(/(\d+)|(\D+)/g, function(_, $1, $2) { ax.push([$1 || Infinity, $2 || ""]) });
        b.replace(/(\d+)|(\D+)/g, function(_, $1, $2) { bx.push([$1 || Infinity, $2 || ""]) });
        while(ax.length && bx.length) {
            let an = ax.shift(), 
            bn = bx.shift(), 
            nn = (an[0] - bn[0]) || an[1].localeCompare(bn[1]);
            if(nn) return nn;
        }
        return ax.length - bx.length;
    }

    init(){

        const wrappers = this.vars.items,
            URLparameters = dawn.URLparameters(); // + Get the URL parameters

        let valuesArray = [];

        // ** Loop through wrappers
        if( wrappers.length > 0 ){

            for(let [wrapIndex, wrap] of wrappers.entries()){
            //wrappers.forEach((wrap, wrapIndex) => {

                const select = wrap.querySelector('select'),
                    options = select.querySelectorAll('option');

                let inputName = null, placeholder = null, multi = false, placeholderText, pillsContainer, hiddenInput, nsBox, nsPlaceholder, parameter;
                
                wrap.classList.add('n-select', 'n-select--loading', 'n-select--enabled', 'n-select--' + wrapIndex);

                select.style.display = 'none';
                select.classList.add('n-select__original-select');

                hiddenInput = ( dawn.exists(wrap.getAttribute('data-hidden-input')) && wrap.getAttribute('data-hidden-input') == 'true' ) ? true : false;

                inputName = this.vars.options.hiddenInputName !== '' ? this.vars.options.hiddenInputName : '';

                if( this.vars.options.hiddenInputName !== '' ){
                    inputName = this.vars.options.hiddenInputName;
                }
                else if( dawn.exists( wrap.getAttribute('data-name') ) && wrap.getAttribute('data-name') !== '' ){
                    inputName = wrap.getAttribute('data-name');
                }

                placeholder = ( dawn.exists( wrap.getAttribute('data-placeholder') ) && wrap.getAttribute('data-placeholder') !== '' ) ? wrap.getAttribute('data-placeholder') : null;

                parameter = ( dawn.exists( wrap.getAttribute('data-parameter') ) && wrap.getAttribute('data-parameter') !== '' ) ? wrap.getAttribute('data-parameter') : this.vars.options.parameter !== '' ? this.vars.options.parameter : select.getAttribute('name');

            /// ---------------------------------------------------------

                // -- Create the new select box and elements

                // Multiple Select
                if( dawn.exists(select.getAttribute('multiple')) && select.getAttribute('multiple') == 'multiple' ){
                    multi = true;
                    wrap.classList.add('n-select--multi');

                    // Create the pills container
                    if( this.vars.options.pills ){
                        pillsContainer = document.createElement('div');
                        pillsContainer.classList.add('ns-pills', 'ns-pills--' + wrapIndex);
                        dawn.addElement(wrap, 'after', pillsContainer);
                    }
                }

                // Loop through the original select box options
                for(let [optionIndex, option] of options.entries()){
                    
                    let checkboxName, childClass = '';
                    
                    if( option.classList.contains('child') ){
                        childClass = ' ns-box-options--child';
                    }

                    // First option
                    if (optionIndex === 0) {

                        // Create the replacement select box, ns-box
                        nsBox = document.createElement('div');
                        nsBox.classList.add('ns-box');
                        dawn.addElement(wrap, 'prepend', nsBox);
                    
                        if( placeholder !== null ){
                            placeholderText = placeholder;
                        }
                        else if( this.vars.options.placeholder ){
                            placeholderText = this.vars.options.placeholder;
                        }
                        else{
                            placeholderText = option.textContent;
                        }

                        // Create placeholder
                        let placeholderSpan = document.createElement('span');
                        placeholderSpan.classList.add('ns-placeholder');
                        placeholderSpan.setAttribute('data-placeholder', placeholderText);
                        placeholderSpan.textContent = placeholderText;
                        dawn.addElement(wrap, 'prepend', placeholderSpan);
                    }

                    nsBox = wrap.querySelector('.ns-box');
                    nsPlaceholder = wrap.querySelector('.ns-placeholder');

                    // Create options for the ns-box
                    if(option.getAttribute('value') !== ''){
                        let boxOption = document.createElement('span');
                        boxOption.classList.add('ns-box__option');
                        boxOption.classList.add('ns-box__option--' + optionIndex);
                        (childClass !== '') ? boxOption.classList.add(childClass) : false;
                        boxOption.setAttribute('data-value', option.value.trim());

                        if( multi == true){
                            checkboxName = "ns--"+wrapIndex+"-"+optionIndex;

                            let boxOptionCheckbox = document.createElement('checkbox');
                            boxOptionCheckbox.setAttribute('name', checkboxName);
                            boxOptionCheckbox.setAttribute('value', option.value);

                            let boxOptionLabel = document.createElement('label');
                            boxOptionLabel.setAttribute('for', checkboxName);
                            boxOptionLabel.textContent = option.textContent;

                            boxOption.append(boxOptionCheckbox);
                            boxOption.append(boxOptionLabel);
                        }
                        else{
                            boxOption.textContent = option.textContent;
                        }

                        dawn.addElement(nsBox, 'append', boxOption);
                    }
                }

                // Hidden input
                if( hiddenInput || this.vars.options.hiddenInput ){
                    let hInput = document.createElement('input');
                    hInput.classList.add('ns-selected-value');
                    hInput.setAttribute('value', '');
                    hInput.setAttribute('name', inputName);
                    hInput.setAttribute('type', 'hidden');
                    dawn.addElement(wrap, 'append', hInput);
                }

                // If parameter exists, select the matching option
                if( !multi && URLparameters !== false ){
                    for( let bOpt of wrap.querySelectorAll('.ns-box__option') ){

                        if( bOpt.getAttribute('data-value') === URLparameters.get(parameter) ){
                            bOpt.classList.add('ns-box__option--selected');
                            nsPlaceholder.textContent = bOpt.textContent;
                            nsPlaceholder.setAttribute('data-placeholder', bOpt.textContent);
                        }
                    }
                }

                wrap.classList.remove('n-select--loading', 'loading');

        /// ---------------------------------------------------------

            // -- Events

                // Toggling the 'selected' state on the options.
                for( let boxOpt of nsBox.children ){

                    dawn.on('click', boxOpt, (e) =>{
                    //boxOpt.addEventListener( 'click', () => {

                        let value, txt, isActive = false, that = e.target;

                        if( that.classList.contains('ns-box__option--selected') ){
                            isActive = true;
                        }

                        nsPlaceholder.setAttribute('data-placeholder', '');

                        // Multiple selections
                        if(multi === true){

                            value = boxOpt.children[0].getAttribute('value');
                            boxOpt.classList.toggle('ns-box__option--selected');

                            // Apply to the hidden select field
                            if( boxOpt.classList.contains('ns-box__option--selected') ){

                                for( let multiOpt of options ){
                                    if( multiOpt.value === value ){
                                        if( isActive ){
                                            var index = valuesArray.indexOf(value)
                                            if (index > -1) {
                                                valuesArray.splice(index, 1);
                                            }
                                            multiOpt.removeAttribute('selected');
                                        }
                                        else{
                                            multiOpt.setAttribute('selected', 'selected');
                                            valuesArray.push(value);
                                        }
                                    }
                                }

                            }

                            if( this.vars.options.pills ){

                                // Add and remove the pills
                                if(valuesArray.length > 0){
                                    var n;
            
                                    valuesArray.sort(this.naturalCompare);

                                    pillsContainer.innerHTML = '';
                                    pillsContainer.classList.add('ns-pills--visible')
                                    
                                    for( n = 0; n < valuesArray.length; n++ ){
                                        let pill = document.createElement('span');
                                        pill.classList.add('ns-pills__pill');
                                        pill.textContent = valuesArray[n];
                                        pill.title = 'Unselect ' + valuesArray[n] + '?';
                                        pillsContainer.append(pill);
                                    }
                                }
                                else{
                                    pillsContainer.innerHTML = '';
                                    pillsContainer.classList.remove('ns-pills--visible');
                                    nsPlaceholder.innerHTML = '';
                                    nsPlaceholder.textContent = placeholderText;
                                }

                            }
                            else{
                                let selectedAmount = 0, first = false, updatedText = '';
                                
                                wrap.querySelectorAll('.ns-box__option').forEach((option) => {
                                    const optionItem = select.querySelector('option[value="'+option.textContent+'"]');

                                    if( option.classList.contains('ns-box__option--selected') ){
                                        optionItem.selected = true;
                                        selectedAmount++;

                                        if(first != true){
                                            first = true;
                                            updatedText += option.textContent;
                                        }
                                        else{
                                            updatedText += ', ' + option.textContent;
                                            nsPlaceholder.classList.add('ns-placeholder--truncate');
                                        }
                                        nsPlaceholder.textContent = updatedText;
                                    }
                                    else{
                                        optionItem.removeAttribute('selected');
                                    }
                                });

                                if(selectedAmount == 0){
                                    nsPlaceholder.classList.remove('ns-placeholder--truncate');
                                    nsPlaceholder.textContent = placeholderText;
                                }
                            }
                        }

                        // Single selection
                        else{
                            value = that.getAttribute('data-value');

                            for( let selOpt of options ){
                                selOpt.removeAttribute('selected');
                                if( selOpt.value === value ){
                                    selOpt.setAttribute('selected', 'selected');
                                }
                            }
                            for( let bOpt of nsBox.children ){
                                bOpt.classList.remove('ns-box__option--selected');
                            }
                            
                            that.classList.add('ns-box__option--selected');
                            txt = that.textContent;

                            nsPlaceholder.textContent = txt;
                            if( this.vars.options.hiddenInput ){
                                wrap.getElementsByClassName('ns-selected-value')[0].setAttribute('value', value);
                            }

                        }

                    });
                }

                // -- Unselect options via clicking on the pills
                if( multi == true ){ 

                    dawn.on('click', document.querySelector( '.ns-pills--' + wrapIndex), (e) =>{
                        let pill = event.target, isParent;
                        if (pill.classList.contains('ns-pills__pill')) {
                        
                            const pillVal = pill.textContent;
                            isParent = pill.parentNode;

                            for( let sel of nsBox.children ){
                                if( sel.classList.contains('ns-box__option--selected') ){
                                    let cBox = sel.children[0].getAttribute('value');
                                    if( pillVal === cBox ){
                                        var index = valuesArray.indexOf(pillVal)
                                        if (index > -1) {
                                            valuesArray.splice(index, 1);
                                        }
                                        
                                        sel.classList.remove('ns-box__option--selected');
                                        select.querySelector('[value="'+pillVal+'"]').removeAttribute('selected');
                                        pill.parentNode.removeChild(pill);
                                    }
                                }
                            }
                            if( valuesArray.length == 0 ){
                                isParent.classList.remove('ns-pills--visible');
                                nsPlaceholder.innerHTML = '';
                                nsPlaceholder.textContent = placeholderText;
                            }

                        }
                        
                    })
                }

                // Toggling the `active` state
                dawn.on('click', wrap, (e) => {
                    wrap.classList.contains('n-select--multi-select') ? wrap.classList.add('n-select--active') : wrap.classList.toggle('n-select--active');
                });

                // Close it if clicked elsewhere on the page.
                dawn.on('click', document, (e) => {
                    for( let el of document.querySelectorAll('.n-select--enabled') ){
                        if ( !el.contains(e.target)) {
                            el.classList.remove('n-select--active');
                        }
                    }
                });


            } // End of loop

        }

    }

}

export default Select;