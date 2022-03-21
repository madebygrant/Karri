<?php 
/* 
    Module: Post Taxonomy
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------
$has_cats = has_category();
$has_tags = has_tag()
?>

<?php if( $has_cats || $has_tags ): ?>
<div class="gap-above taxonomy">
    <?php
        if( $has_cats ){
            echo '<div class="taxonomy__item taxonomy__cat"><span class="taxonomy__label">'.esc_html__('Category', 'karri').':</span> '; 
                the_category(', '); 
            echo '</div>';
        }
        if( $has_tags ){
            echo '<div class="taxonomy__item taxonomy__tags">'; 
                the_tags('<span class="taxonomy__label">'.esc_html__('Tags', 'karri').':</span> '); 
            echo '</div>';
        }
    ?>
</div>
<?php endif; ?>