<?php
/*
    View: Page - Archive
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

$search_term = esc_html($s, 1);
$page_number = is_paged() ? $wp_query->query_vars['paged'] : 1;
$custom_tax_title = is_tax() ? single_term_title('', false) : '';
?>

<div class="container inner inner--page inner--blog">

    <?php // Page: Header ?>
    <header class="gap-below--x3 header header--archives">
        <h1 class="heading heading--base heading--archives header__heading">

            <span class="header__term header__term--prefix">
                Results for:
            </span>

            <span class="header__term header__term--label">
            <?php
                if ( is_search() ) { echo esc_html($search_term); };
                if ( is_author() ) { the_author(); };
                if ( is_category() ) { single_cat_title( '', true ); };
                if ( is_tag() ) { single_tag_title( '', true ); };
                echo esc_html($custom_tax_title);
            ?>
            </span>

            <?php if( $wp_query->max_num_pages > 1 ): ?>
                <span class="header__separator">,</span>
                <span class="header__term header__term--results">
                    <?php echo esc_html($page_number)." out of ".esc_html($wp_query->max_num_pages); ?> pages
                </span>
            <?php endif; ?>

        </h1>
    </header>

    <?php 
        // Get the default loop
        Theme::module( 'loops/default' );
    ?>
    
</div>