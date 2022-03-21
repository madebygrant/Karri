<?php
/*
    Page: 404
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

Theme::header();
?>

    <div class="inner inner--page inner--404 gap-above--x4 gap-below--x4">

        <?php // Page: Header ?>
        <?php Theme::module( 'headings/page', [ 'text' => 'Page Not Found!' ] ); ?>

        <?php // Page: Content ?>
        <div class="content content--entry">
            <p>
                <?php _esc_html_e( "Sorry, this page doesn't seem to exist."); ?>
                <?php _printf("Go back to our %s", ["<a href='".esc_url(site_url())."'>Home page?</a>"]) ?>
            </p>
            <p>
                <em><?php _esc_html_e("Error code: 404") ?></em>
            </p>
        </div>

    </div>

<?php
Theme::footer();