<?php
/*
    Part: Header
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------------
?>

<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" >

    <?php if( !is_404() && comments_open()){ wp_enqueue_script( 'comment-reply' ); } // Check if comments enabled and load the JS for it ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class('');?>>

    <div class="site-container">

        <header class="site-header masthead">
            <div class="container masthead__inner">

                <?php // Logo ?>
                <div class='masthead__branding'>

                    <h1 class="screenreader-only masthead__heading"><?php echo esc_html(get_bloginfo('name')); ?></h1>

                    <figure class="masthead__logo">
                        <a class="masthead__logo-link" href="<?php echo home_url(); ?>">
                            <?php echo Theme::site_logo(); ?>
                        </a>
                    </figure>

                </div>

                <?php // Main Navigation ?>
                <?php Theme::module( 'menu', [ 'menu_slug' => 'top-menu', 'class' => 'navigation--main', 'menu_class' => 'navigation__menu--main' ]); ?>

            </div>
        </header>

        <?php // The closing tag of <main> can be found in the 'footer.php' file ?>
        <main class="site-main">