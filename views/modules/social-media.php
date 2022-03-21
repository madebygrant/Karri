<?php 
/* 
    Module: Social Media
*/

use \Karri\Theme as Theme;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

$data = [
    'class' => isset($class) ? ' '.$class : '',
    'html-id' => isset($html_id) ? 'id="'.esc_attr($html_id).'" ' : '',
    'heading-tag' => isset($heading_tag) ? $heading_tag : 'h4',
    'title' => isset($title) ? $title : false,
    'directory' => isset($directory) ? $directory : '',
    'labels' => isset($labels) ? $labels : false
];

$sm_list = Theme::social_buttons_list( [ 'directory' => $data['directory'], 'has_labels' => $data['labels'] ] );

if( !empty($sm_list) ):
?>

    <div <?php echo $data['html-id']; ?>class="sm-buttons<?php echo esc_attr( $data['class'] ) ?>">

        <?php // Section title
            if($data['title']): ?>
            <<?php echo esc_html($data['heading-tag']); ?> class="title title--social">
                <?php _esc_html_e($data['title']); ?>
            </<?php echo esc_html($data['heading-tag']); ?>>
        <?php endif; ?>

        <?php // Output the social media button list
            echo $sm_list; 
        ?>
    </div>

<?php endif; ?>