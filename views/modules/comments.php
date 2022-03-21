<?php
/* 
    Module: Comments
*/

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------
?>

<?php if( comments_open() && !is_404() ): ?>
    <?php comments_template() ?>
<?php endif; ?>