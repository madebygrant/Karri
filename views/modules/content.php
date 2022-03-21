<?php
/* 
    Module: Content
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

$content = isset($content) ? apply_filters( 'the_content', $content ) : apply_filters( 'the_content', get_the_content() );
$class = isset($class) ? ' ' . $class : '';
$class .= empty($the_content) ? ' content--none' : '';
$excerpt_args = isset($excerpt_args) ? $excerpt_args : [];
$the_content = !isset($excerpt) || !$excerpt ? $content : Theme::excerpt($excerpt_args);
?>

<div class="content content--entry<?php echo esc_attr( $class ) ?>">
    <?php echo $the_content; ?>
</div>