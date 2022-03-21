<?php
/* 
    Module: Menus
*/

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

if( has_nav_menu($menu_slug) ):

    $data = [
        'class' => isset($class) ? ' '.$class : '',
        'heading-tag' => isset($heading_tag) ? $heading_tag : 'h4',
        'title' => isset($title) ? $title : false,
        'menu-class' => isset($menu_class) ? ' ' . $menu_class : ''
    ];
?>
    <nav class="navigation<?php echo esc_attr( $data['class'] ) ?>">

        <?php if($data['title']): ?>
            <<?php echo esc_html( $data['heading-tag'] ); ?> class="heading heading--menu navigation__heading">
                <?php _esc_html_e( $data['title'] ); ?>
            </<?php echo esc_html( $data['heading-tag'] ); ?>>
        <?php endif; ?>

        <?php wp_nav_menu ( ['container' => false, 'menu_class' => 'navigation__menu'. $data['menu-class'], 'theme_location' => $menu_slug, 'walker' => new \Karri\Components\WalkerARIA ] ); ?>
    </nav>

<?php endif; ?>