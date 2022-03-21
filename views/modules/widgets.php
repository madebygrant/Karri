<?php 
/* 
    Module: Widgets
*/

// ! Prevent direct access.
if (!defined('ABSPATH')) { die(); }

/// ------------------------------------------------------------

if( !isset($sidebar_id) ){ return false; }
?>

<div class="widget-group widget-group--<?php echo esc_attr($sidebar_id); ?>">
    <?php dynamic_sidebar( $sidebar_id ); ?>
</div>