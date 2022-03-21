<?php
// ----------- Karri: Admin -----------

namespace Karri\Components\Admin;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

if ( !class_exists( 'Karri\Components\Admin\FeaturedImageColumn' ) ) {

    /**
     * Featured Image column for specific post types
     */

    class FeaturedImageColumn{

        public $args;

        /**
         * Add the column
         *
         * @param array $columns
         * @return void
         */
        function add_post_admin_thumbnail_column($columns){
            $new_column_order = [];                                          // Array for the new column order
            $columns['featured-thumb'] = __(esc_html($this->args['title']), THEME_TEXT_DOMAIN);     // Add the Image column
            $image = $columns['featured-thumb'];                                  // Save the Image column

            // Move the image column
            foreach($columns as $key => $value) {
                if($key === 'title') {                              // Find the Title column
                    $new_column_order['featured-thumb'] = $image;   // Place the Image column before it
                }
                $new_column_order[$key] = $value;
            }
            return $new_column_order;
        }

        /**
         * Display Thumbnail
         *
         * @param array $columns
         * @param int $id
         * @return void
         */
        function show_post_thumbnail_column($columns, $id){
            if ($columns == 'featured-thumb') {
                if( function_exists('the_post_thumbnail') ){
                    echo "<span>";
                    echo the_post_thumbnail('thumbnail', ['title' => "",'class' => 'post-thumb res-image'] );
                    echo "</span>";
                }
            }
        }

        /**
         * CSS to tweak the ouput
         *
         * @return void
         */
        function admin_image_column_style(){
            echo "<style>.column-featured-thumb{width: 10%;} .column-featured-thumb span{display: block; width: 80px;} .column-featured-thumb img{max-width: 100%; height: auto;}</style>";
        }

        function __construct($args = ['post_type' => 'post', 'title' => 'Image']){
            $this->args = $args;
            add_action('admin_print_styles', [$this, 'admin_image_column_style'] );
            add_filter('manage_'.$args['post_type'].'_posts_columns', [$this, 'add_post_admin_thumbnail_column'], 5);
            add_action('manage_'.$args['post_type'].'_posts_custom_column', [$this, 'show_post_thumbnail_column'], 5, 2);
        }

    }

}

/// ------------------------------------------------------------

if ( !class_exists( 'Karri\Components\Admin\TaxonomyFilter' ) ) {

    /**
     * Adds taxonomy drop-down filters for posts' pages (Admin)
     */

    class TaxonomyFilter{
        // Credit for the orginal functions: Mike Hemberger - http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/

        public $type;
        public $tax;

        /**
         * Display a custom taxonomy dropdown in admin
         *
         * @return void
         */
        function filter_taxonomy() {
            global $typenow;
            if ($typenow == $this->type) {
                $selected = isset($_GET[$this->tax]) ? $_GET[$this->tax] : '';
                $info_taxonomy = get_taxonomy($this->tax);
                wp_dropdown_categories( [
                    'show_option_all' => __("Show All {$info_taxonomy->label}"),
                    'taxonomy'        => $this->tax,
                    'name'            => $this->tax,
                    'orderby'         => 'name',
                    'selected'        => $selected,
                    'show_count'      => true,
                    'hide_empty'      => true,
                ] );
            };
        }

        /**
         * Filter posts by taxonomy in admin
         *
         * @param object $query
         * @return void
         */
        function convert_id_to_term_in_query($query) {
            global $pagenow;
            $q_vars = &$query->query_vars;
            $condition = $pagenow === 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $this->type && isset($q_vars[$this->tax]) && is_numeric($q_vars[$this->tax]) && $q_vars[$this->tax] !== 0;

            if ( $condition ) {
                $term = get_term_by('id', $q_vars[$this->tax], $this->tax);
                $q_vars[$this->tax] = $term->slug;
            }
        }

        public function __construct($post_type, $taxonomy){
            $this->type = $post_type;
            $this->tax = $taxonomy;
            add_filter('parse_query', [$this, 'convert_id_to_term_in_query']);
            add_action('restrict_manage_posts', [$this, 'filter_taxonomy']);
        }
    }

}

/// ------------------------------------------------------------

if ( !class_exists( 'Karri\Components\Admin\PageTemplateColumn' ) ) {

    /**
    * Adds 'Current Page Template' column in Pages (Admin)
    */

    class PageTemplateColumn{

        /**
         * Add column
         *
         * @param array $columns
         * @return void
         */
        function add_template_column( $columns ) {
            $columns['reveal-template'] = 'Current Page Template';
            return $columns;
        }

        /**
         * Add column content
         *
         * @param array $column
         * @param int $id
         * @return void
         */
        function template_column_content( $column, $id ) {
            global $post;
            if( 'reveal-template' == $column ) {
                $templates = wp_get_theme()->get_page_templates();
                $template_slug = get_page_template_slug( $post->ID );

                foreach ( $templates as $template_slug2 => $template_name ) {
                    if($template_slug === $template_slug2){
                        echo esc_html($template_name);
                    }
                }

                if($template_slug === ''){
                    echo esc_html('Default Template');
                }
            }
        }

        public function __construct(){
            add_filter( 'manage_pages_columns', [$this, 'add_template_column'], 5 );
            add_action( 'manage_pages_custom_column', [$this, 'template_column_content'], 5, 2 );
        }

    }
}

/// ------------------------------------------------------------

if ( !class_exists( 'Karri\Components\Admin\MetaFilter' ) ) {

    /**
    * Adds a filter dropdown sorted via a meta key (Admin)
    */

    class MetaFilter{

        public $type;
        public $meta_key;
        public $dropdown_label;
        public $status;

        /**
         * Posts loop for the filter
         *
         * @return void
         */
        function meta_admin_posts_filter_posts_loop(){
            $linked_array = [];
            $query = new WP_Query( [ 'post_type' => $this->type, 'post_status' => $this->status, 'posts_per_page' => -1 ] );

            while ($query->have_posts()) {
                    $query->the_post();
                    $linked_id = get_post_meta(get_the_ID(), $this->meta_key, true);
                    $linked_array[] = $linked_id;
            }
            wp_reset_query();
            return array_unique($linked_array); // Remove duplicates
        }

        /**
         * Create the filter select field
         *
         * @return void
         */
        function meta_admin_posts_filter_restrict_manage_posts(){
            if (isset($_GET['post_type'])) {
                $type = $_GET['post_type'];
            }
            if ($type === $this->type){
                ?>
                <select name='meta_filter'>
                    <option value=""><?php echo esc_html($this->dropdown_label); ?></option>
                    <?php
                        foreach($this->meta_admin_posts_filter_posts_loop() as $id){
                            if($_GET['meta_filter'] === $id){
                                echo "<option selected value='".esc_attr($id)."'>".get_the_title($id)."</option>";
                            }
                            else{
                                echo "<option value='".esc_attr($id)."'>".get_the_title($id)."</option>";
                            }
                        }
                    ?>
                </select>
                <?php
            }
        }

        /**
         * Create the filter
         *
         * @param object $query
         * @return void
         */
        function meta_posts_filter( $query ){
            global $pagenow;
            if (isset($_GET['post_type'])) {
                $type = $_GET['post_type'];
            }
            if ( $type === $this->type && is_admin() && $pagenow === 'edit.php' && isset($_GET['meta_filter']) && $_GET['meta_filter'] !== '' && $query->is_main_query()) {
                $query->query_vars['meta_key'] = $this->meta_key;
                $query->query_vars['meta_value'] = $_GET['meta_filter'];
            }
        }

        function __construct($meta_key, $dropdown_label, $type = 'post', $post_status = 'all'){
            $this->type = $type;
            $this->meta_key = $meta_key;
            $this->dropdown_label = $dropdown_label;
            $this->status = $post_status;
            add_action( 'restrict_manage_posts', [$this, 'meta_admin_posts_filter_restrict_manage_posts'] );
            add_filter( 'parse_query', [$this, 'meta_posts_filter'] );
        }
    }

}