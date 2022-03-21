<?php
// ----------- Karri: Customizer Controls -----------

namespace Karri\Components\Customizer\Controls;

use WP_Customize_Control;
use WP_Customize_Setting;
use WP_Query;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

const CUSTOMIZER_PREFIX = 'karri';

if( class_exists('WP_Customize_Control') ){

    // Enqueue the theme's customiser JS script
    add_action( 'customize_controls_enqueue_scripts', function () {
        wp_enqueue_script( 'wp-editor-customizer', get_template_directory_uri() . '/_app/assets/customizer.js', ['jquery'], rand(), true );
    });

    // ----

    // Custom Customizer Controls

    /**
     * TinyMCE Editor Control
     */

    class TinyMCE_Editor_Control extends WP_Customize_Control{
        public $type = CUSTOMIZER_PREFIX.'_customizer_textarea';
        public function render_content() { ?>
            <label class="customise-control customise-control--<?php echo esc_attr(CUSTOMIZER_PREFIX); ?>">
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            <?php
                $settings = [
                    'media_buttons' => false,
                    'quicktags' => true,
                    'teeny' => true,
                    'textarea_rows' => 5,
                ];
                $this->filter_editor_setting_link();
                wp_editor($this->value(), $this->id, $settings );
            ?>
            </label>
            <?php
            do_action('admin_footer');

            if( version_compare( $GLOBALS['wp_version'], '4.8', '>=' )){
                do_action('admin_print_footer_scripts');
            }
            elseif(!empty($this->choices['total']) && $this->choices['total'] !== 0 && $this->choices['number'] === $this->choices['total']){
                do_action('admin_print_footer_scripts'); // Load if the last editor and WordPress is below version 4.8
            }
        }

        private function filter_editor_setting_link() {
            add_filter( 'the_editor', function( $output ) { return preg_replace( '/<textarea/', '<textarea ' . $this->get_link(), $output, 1 ); } );
        }
    }

    // ----

    /**
     * Multiple Images Control
     */

    class Multi_Image_Control extends WP_Customize_Control{

        public $type = CUSTOMIZER_PREFIX.'_customizer_multi_image';

        public function render_content() {

        /*
        jQuery(document).ready(function($) {
            
            $('#multi-images--<?php echo esc_attr( $this->id ) ?> .multi-images__button')
            .click( function() {
                var button = this,
                    hiddenInput = $('#multi-images--<?php echo esc_attr( $this->id ) ?> .multi-images__input'),
                    preview = $('#multi-images--<?php echo esc_attr( $this->id ) ?> .multi-images__preview');
                
                if (this.window === undefined) {

                    this.window = wp.media({
                        title: 'Select one or multiple images',
                        library: {type: 'image'},
                        multiple: true,
                        button: {text: 'Insert'}
                    });

                    var $self = this.window; // Needed to retrieve our variable in the anonymous function below

                    this.window.on('select', function() {

                        var attachments = $self.state().get('selection').map( 
                            function( attachment ) {
                                attachment.toJSON();
                                return attachment;
                            }
                        ),
                        items = {};
                        preview.html('');
                        
                        for(var i = 0; i < attachments.length; i++){
                            items[i] = {
                                id: attachments[i].id,
                                url: attachments[i].attributes.url
                            }

                            var previewThumb = attachments[i].attributes.sizes.thumbnail.url ? attachments[i].attributes.sizes.thumbnail.url : attachments[i].attributes.url,
                                previewName = attachments[i].attributes.name ? attachments[i].attributes.name : attachments[i].attributes.title;

                            preview.append('<img src="'+previewThumb+'" alt="'+previewName+'" />');  

                        }
                        
                        // Set and apply the images object
                        hiddenInput.val(JSON.stringify(items)).trigger('change');
                        wp.customize( '<?php echo $this->id; ?>', function ( obj ) {
                            obj.set( JSON.stringify(items) );
                        });
                        
                    });

                }

                this.window.open();
                return false;
            } );

        });
        */
        ?>

        <script>
            jQuery(document).ready(function(i){i("#multi-images--<?php echo esc_attr( $this->id ) ?> .multi-images__button").click(function(){var t=this,r=i("#multi-images--<?php echo esc_attr( $this->id ) ?> .multi-images__input"),a=i("#multi-images--<?php echo esc_attr( $this->id ) ?> .multi-images__preview");if(this.window===undefined){this.window=wp.media({title:"Select one or multiple images",library:{type:"image"},multiple:true,button:{text:"Insert"}});var u=this.window;this.window.on("select",function(){var t=u.state().get("selection").map(function(t){t.toJSON();return t}),i={};a.html("");for(var e=0;e<t.length;e++){i[e]={id:t[e].id,url:t[e].attributes.url};var s=t[e].attributes.sizes.thumbnail.url?t[e].attributes.sizes.thumbnail.url:t[e].attributes.url,n=t[e].attributes.name?t[e].attributes.name:t[e].attributes.title;a.append('<img src="'+s+'" alt="'+n+'" />')};r.val(JSON.stringify(i)).trigger("change");wp.customize("<?php echo $this->id; ?>",function(t){t.set(JSON.stringify(i))})})}this.window.open();return false})});
        </script>

        <label id="multi-images--<?php echo esc_attr( $this->id ) ?>" class="customise-control customise-control--<?php echo esc_attr(CUSTOMIZER_PREFIX); ?>">
            <span class="customize-control-title customize-multi-select"><?php echo esc_html( $this->label ); ?></span>
            <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            <div class="customize-control-inner">

                <?php if($this->value()): ?>
                    <div class="multi-images__preview" style="display: grid; gap: 1rem; grid-template-columns: repeat(4, 1fr); background-color: #eaeaea; border: 1px solid #ddd; padding: 1rem;">
                        <?php
                            $images = json_decode( get_theme_mod( str_replace('-control', '', $this->id) ), true );
                            foreach($images as $image):
                                $src = wp_get_attachment_image_src( $image['id'] , 'thumbnail' )[0];
                        ?>
                                <img src="<?php echo esc_url($src); ?>" />

                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <input <?php $this->link(); ?> type="hidden" class="multi-images__input" value="<?php echo esc_html($this->value()); ?>" />
                <div class="actions">
                    <button type="button" class="button multi-images__button" style="float:right;">Add Images</button>
                </div>
            </div>
        </label>

    <?php 
        }
    }

    // ----

    /**
     * Post/Page Select Control
     */

    class Post_Select_Control extends WP_Customize_Control{
        public $type = CUSTOMIZER_PREFIX.'_customizer_post_select';

        public function render_content() {
            $post_type = !empty($this->choices) && is_array($this->choices) ? $this->choices : 'post';
            $latest = new WP_Query( [
                'post_type'   => $post_type,
                'post_status' => 'publish',
                'orderby'     => 'title',
                'order'       => 'ASC',
                'posts_per_page' => -1
            ]);

    ?>
            <label class="customise-control customise-control--<?php echo esc_attr(CUSTOMIZER_PREFIX); ?>">
                <span class="customize-control-title customize-page-select"><?php echo esc_html( $this->label ); ?></span>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <select <?php $this->link(); ?>>
                    <option <?php echo selected( $this->value(), get_the_ID() ) ?> value=""></option>
                    <?php
                    while( $latest->have_posts() ) {
                        $latest->the_post();
                        echo "<option " . selected( $this->value(), get_the_ID() ) . " value='" . get_the_ID() . "'>" . the_title( '', '', false ) . "</option>";
                    }
                    ?>
                </select>
            </label>
    <?php }
    }

    // ----

    
    /**
     * Image Setting
     * 
     * Overwrites the `update()` method so we can save some extra data.
    */

    class Setting_Image_Data extends WP_Customize_Setting {
        protected function update( $value ) {
    
            if ( $value ) {
    
                $post_id = attachment_url_to_postid( $value );
    
                if ( $post_id ) {
    
                    $image = wp_get_attachment_image_src( $post_id );
    
                    if ( $image ) {
    
                        /* Set up a custom array of data to save. */
                        $data = [
                            'url'    => esc_url_raw( $image[0] ),
                            'width'  => absint( $image[1] ),
                            'height' => absint( $image[2] ),
                            'id'     => absint( $post_id )
                        ];
    
                        set_theme_mod( "{$this->id_data[ 'base' ]}_data", $data );
                    }
                }
            }
    
            // No media? Remove the data mod.
            if ( empty( $value ) || empty( $post_id ) || empty( $image ) )
                remove_theme_mod( "{$this->id_data[ 'base' ]}_data" );
    
            // Let's send this back up and let the parent class do its thing.
            return parent::update( $value );
        }
    }

    // ----

    /**
     * Toggle Switch Control
     */

    class Toggle_Switch_Control extends WP_Customize_Control{
        public $type = CUSTOMIZER_PREFIX.'_customizer_toggle_switch';
        public function render_content(){
        ?>
            <label class="customise-control customise-control--<?php echo esc_attr(CUSTOMIZER_PREFIX); ?>">
                <span class="customise-toggle">
                    <span class="toggle-label"><?php echo esc_html( $this->label ); ?></span>
                    <span class="switch-wrap">
                        <input <?php $this->link(); ?> type="checkbox" value="true" <?php checked( $this->value(), true ) ?> />
                        <span class="slider round"></span>
                    </span>
                    
                </span>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            </label>
        <?php
        }
    }

    // ----

    /**
     * Header
     */

    class Header_Control extends WP_Customize_Control{
        public $type = CUSTOMIZER_PREFIX.'_customizer_header';
        public function render_content(){
        ?>

            <div class="customise-header">
                <h3><?php echo esc_html( $this->label ); ?></h3>
                <span class="description customize-control-description">
                    <?php echo esc_html( $this->description ); ?>
                </span>
            </div>

        <?php
        }
    }

    // ----

    /**
     * Spacer
     */
    
    class Spacer_Control extends WP_Customize_Control{
        public $type = CUSTOMIZER_PREFIX.'_customizer_spacer';
        public function render_content(){
        ?>
            <div class="customise-spacer"></div>
        <?php
        }
    }

}
