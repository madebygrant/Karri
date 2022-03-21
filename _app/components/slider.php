<?php
// ----------- Karri: Slider -----------

namespace Karri\Components\Slider;

if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

/**
 * Get slider sidebar meta
 * 
 * @param string $id The id for sidebar meta being used
 * @param int $post_id The post id (optional)
 */

function meta($id, $post_id = false){
    $arr = [];

    if( defined('WPSIDES_PLUGIN_VERSION') ){
    
        $meta = \WPSides\meta($id, $post_id);

        if( $meta ){

            $sortedArr = [];

            foreach( $meta as $key => $value ){

                $notSlides = ['slides_positions', 'slide_delay'];

                if( in_array($key, $notSlides) ){
                    $arr[$key] = $value;
                }
                else{
                    $id = substr($key, strrpos($key, "_") + 1);

                    if( strpos($key, '_' . $id) !== false ){
                        $key = str_replace('_' . $id, '', $key);
                        $key = str_replace('slide_', '', $key);
                        $arr['slides'][$id][$key] = $value;
                        
                    }
                }
            }

            // Sort
            if( isset($arr['slides_positions']) && isset($arr['slides']) ){
                $positions = preg_replace('/\s+/', '', strtolower($arr['slides_positions']) );
                $order = explode(",", $positions);
                foreach( $order as $key => $value ){
                    if( isset($arr['slides'][$value]) ){
                        $sortedArr[$value] = $arr['slides'][$value];
                    }
                }
                $arr['slides'] = $sortedArr;
            }

        }

    }
    
    return $arr;
}

// ----------------------------

/**
 * Render a Karri slider
 *
 * @param string The id for sidebar meta being used
 * @param array $args
 * @return void
 */

function render($meta_id, $args = []){

    $default_args = ['image-size' => 'full'];
    $args = _merge_arrays($default_args, $args);

    $slider = meta($meta_id);
    $slides_amount = isset($slider['slides']) ? count($slider['slides']) : 0;

    if( !empty($slider) && isset($slider['slides']) && $slides_amount > 0 ):
        $i = 0;

        $class = isset($args['class']) ? ' ' . $args['class'] : '';
        $delay = isset($slider['slide_delay']) ? ' data-delay='.$slider['slide_delay'] : '';
    ?>

    <div class="slider<?php echo esc_attr($class); ?>"<?php echo esc_attr($delay); ?>>

        <?php if( $slider['slides'] && !empty($slider['slides']) ): foreach( $slider['slides'] as $k => $slide ): 

            if($slides_amount == $i){
                break;
            }
            
            $media = $slide['media'];

            $isset = [
                'title' => isset($slide['title']),
                'text' => isset($slide['text']),
                'button' => isset($slide['button']),
                'url' => isset($slide['url'])
            ];

            $newtab = isset($slide['newtab']) && $slide['newtab'] == 1 ? ' target="_blank" rel="noopener noreferrer"' : '';
        ?>
            <div class="slide slide--<?php echo esc_attr($media->type); ?> slide--<?php echo esc_attr($i); ?> slide--<?php echo esc_attr($k); ?>">

                <?php if($media->type == 'image'): ?>

                    <figure class="slide__image">
                        <?php
                            if(!$isset['button'] && $isset['url']){
                                echo '<a class="slide__link" href="'.esc_url($slide['url']).'"'.$newtab.'>';
                            }

                            $image = new \Karri\Components\Media\Image;
                            echo $image::get( ['image-id' => $media->id, 'size' => $args['image-size']] );

                            if(!$isset['button'] && $isset['url']){
                                echo '</a>';
                            }
                        ?>
                    </figure>

                <?php elseif($media->type == 'video'): ?>

                    <div class="slide__video">
                        <video width="<?php echo esc_attr($media->width); ?>" height="<?php echo esc_attr($media->height); ?>" controls>
                            <source src="<?php echo esc_url($media->url); ?>" type="<?php echo esc_attr($media->mime); ?>">
                        </video>
                    </div>

                <?php endif; ?>

                <?php if($isset['title'] || $isset['text'] || $isset['button']): ?>

                    <div class="slide__content">

                        <?php if($isset['title']): ?>
                            <header class="slide__header">
                                <h2 class="slide__heading"><?php _esc_html_e($slide['title']) ?></h2>
                            </header>
                        <?php endif; ?>

                        <?php if($isset['text']): ?>
                            <div class="slide__text">
                                <?php echo _convert_newlines($slide['text']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if($isset['button'] && $isset['url']): ?>
                            <a class="slide__button" href="<?php echo esc_url($slide['url']); ?>"<?php echo $newtab; ?>>
                                <?php _esc_html_e($slide['button']) ?>
                            </a>
                        <?php endif; ?>

                    </div>

                <?php endif; ?>

            </div>
        <?php 
            $i++; 

        endforeach; endif;
        ?>

    </div>

    <?php
    endif;

}