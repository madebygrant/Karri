<?php
/*
    Part: Footer
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------------

$meta = [
    'footer-text' => get_theme_mod(PREFIX_.'footer_text')
];
?>
        <?php // The start tag can be found in the 'header.php' file ?>
        </main>

        <?php // Widgets ?>
        <?php if( is_active_sidebar('primary') ): ?>
            <?php Theme::module('widgets', [ 'sidebar_id' => 'primary' ] ) ?>
        <?php endif; ?>

        <?php // Footer ?>
        <footer class="site-footer">

            <div class="site-footer__top">

                <?php // Footer: Text ?>
                <?php if( $meta['footer-text'] ): ?>
                    <div class="site-footer__item site-footer__item--text">
                        <?php echo apply_filters( 'the_content', $meta['footer-text'] ); ?>
                    </div>
                <?php endif; ?>

                <?php // Footer: Menu ?>
                <?php if ( has_nav_menu( 'footer-menu' ) ): ?>
                    <div class="site-footer__item site-footer__item--menu">
                        <?php Theme::module( 'menu', [ 'menu_slug' => 'footer-menu', 'class' => 'navigation--footer', 'menu_class' => 'navigation__menu--footer' ]); ?>
                    </div>
                <?php endif; ?>

            </div>

            <div class="site-footer__bottom">

                <?php // Footer: Copyright ?>
                <div class="site-footer__item site-footer__item--copyright">
                    <?php _printf('&copy; %s All Rights Reserved.', [ date('Y') ]); ?>
                </div>

            </div>

        </footer>

    </div>

<?php wp_footer(); ?>

</body>
</html>