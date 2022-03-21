<?php
/* 
    Module: Heading - Page
*/

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

$text = isset($text) && $text ? $text : get_the_title();
$class = isset($class) && $class ? ' ' . $class : '';
$headers = isset($headers) && $headers ? $headers : true;
?>

<?php if($headers): ?>
<header class="gap-below gap-above header header--page">
<?php endif; ?>

<h1 class="heading heading--page header__heading<?php echo esc_attr( $class ) ?>">
    <?php _esc_html_e( $text ); ?>
</h1>

<?php if($headers): ?>
</header>
<?php endif; ?>