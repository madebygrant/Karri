(async () => {

    const { __ } = wp.i18n;
    const el = wp.element.createElement;
    const { Component, Fragment, render } = wp.element;
    const { ToggleControl, TextControl } = wp.components;
    const options = KarriThemeOptionsComponents;

    // ------------------------------------------------

    // Add your option names to the array below. 
    // They have to match what you've added in your 'config/theme-options.php' file.
    
    const optionList = [
        
    ];

    // ------------------------------------------------

    class App extends Component {
        
       constructor() {
            super( ...arguments );

            this.state = {
                isAPILoaded: false,
                isAPISaving: false,
            };

            optionList.forEach( (name) =>{
                this.state[name] = '';
            })
        }

        // ----

        componentDidMount() {
            wp.api.loadPromise.then( () => {
                this.settings = new wp.api.models.Settings();

                const { isAPILoaded } = this.state;
                if ( isAPILoaded === false ) {
                    this.settings.fetch().then( ( response ) => {
                        let setOptions = {
                            isAPILoaded: true
                        };
                        optionList.forEach( (name) =>{
                            setOptions[name] = response[name];
                        })
                        this.setState(setOptions);

                    } );
                }
            } );
        }

        // ----

        /**
         * Methods to help you to get or update values for options
         */
        values = {
            app: this,

            /**
             * Get the option's value
             * @param int key The key of the option within the 'optionList' array
             */
            get(key){
                return this.app.state[optionList[key]]
            },

            /**
             * Update the option's value
             * @param int key The key of the option within the 'optionList' array
             * @param any value 
             */
            update(key, value){
                this.app.setState({ [optionList[key]]: value })
            }

        }

        // ------------------------------------------------

        // -- Page Output
    
        render() {

            return (
                
                el( Fragment, {},

                    // -- Add your options below --

                    el( 'div', { className: "theme-options__inner" },

                        // Page Header
                        options.pageHeader('Theme Options'),

                        // ----

                        
                        
                        // ----

                        // Submit/Save Button
                        options.saveButtonRow(this.state, optionList)

                    ),

                    // ------------------------------------------------

                    // Notices
                    options.notices()
                ) 
            )

        }
        
    }

    render(
        el(App), document.getElementById( 'karri-theme-options' )
    );

})();
