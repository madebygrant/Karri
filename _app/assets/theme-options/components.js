const { __ } = wp.i18n;
const el = wp.element.createElement;
const { Button } = wp.components;

const KarriThemeOptionsComponents = {

    group: (opts, ...elements) => {
        let dataTabLabel = null;
        let groupClass = '';

        if( !!opts ){
            groupClass = typeof opts.className != 'undefined' ? ' ' . opts.className : '';
            
            if( !!opts.tab ){
                groupClass += ' theme-options__group--tabbed';
                dataTabLabel = opts.tab;
            }
        }

        return(
            el( 'div', { className: "theme-options__group" + groupClass, "data-tabLabel": dataTabLabel }, elements)
        )
    },
    
    item: (label, ...elements) => {
        return(
            el( 'div', { className: "theme-options__item" }, 
                el('span', { className: 'theme-options__label' }, __(label, 'karri')), 
                elements
            )
        )
    },

    separator: () => {
        return(
            el( 'div', { className: "theme-options__separator" } )
        )
    },

    pageHeader: (text) => {
        return(
            el('header', { className: 'theme-options__header theme-options__header--page' },
                el('h1', { className: 'theme-options__heading theme-options__heading--page' },
                    __(text, 'karri')
                )
            )
        )
    },

    heading: (text) => {
        return(
            el('h2', { className: 'theme-options__heading' }, __(text, 'karri'))
        )
    },

    note: (text) => {
        return(
            el('p', { className: 'theme-options__note' }, __(text, 'karri'))
        )
    },

    notices: () => {
        return(
            el('div', { className: 'notices' },
                el('p', { className: 'notices__notice' })
            )
        )
    },

    saveButtonRow: (state, options, buttonText) => {
        buttonText = buttonText ? buttonText : 'Update Options';
        let opts = {};

        options.forEach( val => {
            opts[val] = state[val];
        });

        return(
            el( 'div', { className: "theme-options__group theme-options__group--has-submit" },

                el(
                    Button, {
                        isPrimary: true,

                        onClick: () => {
                            let settings = new wp.api.models.Settings( opts );
                            settings.save();
                            KarriThemeOptionsComponents.other.notice('Options Updated!', 'success');
                        }
                        
                    }, 
                    __(buttonText, 'karri')
                )

            )
        )
    },

    other: {
        notice: (text, type) => {
            const notice = document.querySelector('.notices__notice');
            notice.classList.add('notices__notice--' + type);
            notice.classList.add('notices__notice--active');
            notice.textContent = text;
            setTimeout(() => {
                notice.classList.remove('notices__notice--active');
            }, 2500);
            setTimeout(() => {
                notice.textContent = '';
            }, 2600);
        },

        tabs: () => {
            const tabbedGroups = document.querySelectorAll('.theme-options__group--tabbed'),
                pageHeader = document.querySelector('.theme-options__header--page');
            
            if( tabbedGroups.length > 1 ){

                document.querySelector('.theme-options').classList.add('theme-options--has-tabs');

                // Create the tabs navbar
                let tabNavBar = document.createElement('div');
                tabNavBar.classList.add('theme-options__tabs');

                // Create the tabs container
                let tabbedGroupWrapper = document.createElement('div');
                tabbedGroupWrapper.classList.add('theme-options__tabbed-groups');

                tabbedGroups.forEach((group, index) => {
                    const label = group.getAttribute('data-tabLabel');

                    // Setup the tabbed group
                    group.classList.add('theme-options__group--tab-' + index);
                    group.setAttribute('data-tab', index);

                    tabbedGroupWrapper.appendChild(group);

                    // Create and append the tab navbar buttons
                    let tabButton = document.createElement('button');
                    tabButton.setAttribute('data-tab', index);
                    tabButton.classList.add('theme-options__tabs-button', 'theme-options__tabs-button--' + index);
                    tabButton.textContent = label;
                    tabNavBar.appendChild(tabButton);

                    if(index == 0){
                        group.classList.add('theme-options__group--active');
                        tabButton.classList.add('theme-options__tabs-button--active');
                    }

                    // Button actions
                    tabButton.addEventListener('click', () => {
                        tabbedGroupWrapper.style.minHeight = group.offsetHeight + 'px';

                        tabbedGroups.forEach((g) => {
                            g.classList.remove('theme-options__group--active');
                        });

                        document.querySelectorAll('.theme-options__tabs-button').forEach((b) => {
                            b.classList.remove('theme-options__tabs-button--active');
                        });

                        group.classList.add('theme-options__group--active');
                        tabButton.classList.add('theme-options__tabs-button--active');

                    }, false);
                });

                // Insert tab navbar after the page header
                pageHeader.parentNode.insertBefore(tabNavBar, pageHeader.nextSibling);

                // Insert groups wrapper
                tabNavBar.parentNode.insertBefore(tabbedGroupWrapper, tabNavBar.nextSibling);

                tabbedGroupWrapper.style.minHeight = document.querySelector('.theme-options__group--active').offsetHeight + 'px';

            }
        },

        tabbedGroupsWrapperHeight: () => {
            const activeGroup = document.querySelector('.theme-options__group--active'),
                tabbedGroupWrapper = document.querySelector('.theme-options__tabbed-groups');

            tabbedGroupWrapper.style.minHeight = activeGroup.offsetHeight + 'px';
        }
    },
};

window.addEventListener('load', () => {
    KarriThemeOptionsComponents.other.tabs();
});

window.addEventListener('resize', () => {
    KarriThemeOptionsComponents.other.tabbedGroupsWrapperHeight();
});