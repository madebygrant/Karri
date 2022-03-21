import KinMenu from '../../_app/assets/addons/kinMenu.js';

document.addEventListener( 'DOMContentLoaded', () => {

    /**
     * Kin Menu - Converts menus to a menu suited for small screens
     * For more information: https://github.com/madebygrant/kin-menu
    */

    const kinOptions = {
        windowWidth: 960,
        groups: [
            {
                element: 'ul',
                class: 'side-menu',
                clones: [
                    'ul.navigation__menu--main > li'
                ]
            },
        ]
    };
    const kin = new KinMenu(kinOptions);
    kin.setStyle('side');
    kin.init();

});