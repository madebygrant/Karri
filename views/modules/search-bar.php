<?php 
/* 
    Module: Search Bar
*/

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

$id = isset($id) ? ' ' . $id : 's';
$class = isset($class) ? ' ' . $class : '';
?>

<div class="search-bar<?php echo esc_attr( $class ) ?>">
    <form role="search" method="get" class="search-bar__form" action="<?php echo home_url( '/' ); ?>" >

        <label class="search-bar__label screen-reader-text" for="<?php echo esc_attr( $id ) ?>">
            <?php _esc_html_e('Search for') ?>:
        </label>

        <span>
            <input type="search" value="<?php echo get_search_query(); ?>" class="search-bar__input" name="s" id="<?php echo esc_attr( $id ) ?>">
            <button title="<?php _esc_html_e('Search') ?>" class="search-bar__submit" type="submit"></button>
        </span>

    </form>
</div>