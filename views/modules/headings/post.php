<?php 
/* 
    Module: Heading - Post
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

$text = isset($text) && $text ? $text : get_the_title();
$class = isset($class) && $class ? ' ' . $class : '';
$headers = isset($headers) && $headers ? $headers : true;
$meta = isset($meta) && $meta ? $meta: true;

$link_default = ['enabled' => true, 'url' => get_the_permalink(), 'newtab' => false ];
$link = isset($link) && is_array($link) ? _merge_arrays($link_default, $link) : $link_default;
$newtab = $link['newtab'] ? ' target="_blank" rel="noopener noreferrer"' : '';
?>

<?php if($headers): ?>
<header class="gap-above gap-below header header--post">
<?php endif; ?>

    <h1 class="heading heading--post heading--h3 header__heading<?php echo esc_attr( $class ) ?>">

        <?php if( $link['enabled'] ): ?>
        <a class="heading__link" href="<?php echo esc_url($link['url']); ?>"<?php echo $newtab ?>>
        <?php endif; ?>

            <?php _esc_html_e( $text ); ?>

        <?php if( $link['enabled'] ): ?>
        </a>
        <?php endif; ?>
    </h1>

    <?php if( $post->post_type === 'post' && $meta ): ?>
        <small class="header__meta">
            <span class="header__date-author">
                <?php the_time('F jS, Y') ?> by <?php the_author(); ?>
            </span>
        </small>
    <?php endif; ?>

<?php if($headers): ?>
</header>
<?php endif; ?>