<?php
// ----------- Karri: Helper Functions -----------

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

if( !function_exists('_clean_array') ){

    /**
    * Cleans & Remove Tags from Multi-Dimensional Arrays
    *
    * @param array $array Array to clean
    * @return array Returns a clean array
    */

    
    function _clean_array($array){
        $clean = [];
        if ( !is_array($array) || !count($array)) {
            return [];
        }
        foreach ($array as $key => $value) {
            if ( !is_array($value) && !is_object($value) ) {
                $clean[$key] = esc_html($value);
            }
            if ( is_array($value) ) {
                $clean[$key] = _clean_array($value);
            }
        }
        return $clean;
    }
}

// ----

if( !function_exists('_config_options') ){

    /**
    * Get the Karri theme's config options
    *
    * @param string $key Add the filter key to gain options from it
    * @return array Returns an array of the options
    */
    
    function _config_options($key){
        $config_array = apply_filters( $key, []);
        $config_array = _clean_array( $config_array );
        return array_map( 'absint', $config_array ) && !empty($config_array) ?  $config_array : false;
    }
}

// ----

if( !function_exists('_get_file_ext') ){

    /**
     * Get file extension of a filename
     * 
     * @param string $filename
     * @return string Returns the extension
     */

    function _get_file_ext($filename){
        $n = strrpos($filename, ".");
        return ($n===false) ? null : substr($filename, $n + 1);
    }

}

// ----

if( !function_exists('_sidebar_meta') ){
    
    /**
     * Get sidebar meta
     * 
     * @param int $post_id Post ID
     * @param string $key Meta key
     * @param bool $output_as_array Output the meta data as an array. Default, as an object
     * 
     * @return mixed Either as object or an array
     */

    function _sidebar_meta($post_id, $key, $output_as_array = false){

        $meta = get_post_meta($post_id, $key, 1);
        $meta = json_decode($meta, $output_as_array);
        $meta = json_last_error() === 0 ? $meta : false;

        if($meta){
            $data = $output_as_array ? [] : (object)[];
            foreach($meta as $key => $val){
                if(!empty($val) && is_string($val)){
                    $decoded = json_decode($val, $output_as_array);
                    if(!$output_as_array){
                        $data->{$key} = $decoded ? $decoded : $val;
                    }
                    else{
                        $data[$key] = $decoded ? $decoded : $val;
                    }
                    
                }
            }
            return $data;
        }
        return $meta;
    }

}

// ----

if( !function_exists('_resize_image') ){

    /**
    * Returns new resized dimensions based on existing dimensions
    *
    * @param int $newWidth Give the new width
    * @param array $based [ width, height ] Give the existing dimensions
    * @return array Returns an array of the new, resized dimensions
    */

    function _resize_image($newWidth, $based){
        if( is_int($newWidth) && is_int($based[0]) && is_int($based[1]) ){
            $dimensions = [];
            $ratio = ($newWidth / $based[0]);
            $dimensions[] = $newWidth;
            $dimensions[] = round($based[1] * $ratio);
            return $dimensions;
        }
        return false;
    };

}

// ----

if( !function_exists('_post_id') ){

    /**
    * Get the post/page ID
    *
    * It can be used within or outside the loop
    *
    * @return string Returns the post/page ID
    */

    function _post_id(){
        return in_the_loop() ? get_the_ID() : get_queried_object_id();
    }

}

// ----

if( !function_exists('_page_template') ){

    /**
    * Get the page template's filename
    *
    * Works in the Admin area and the front end of the WordPress site
    *
    * @param bool $alt If true and not in Admin area, does an alternative method to get the filename
    * @return string Returns the page template's filename with the extension (Example: filename.php)
    */

    function _page_template($alt = false){
        $result = false;
        if( is_admin() && $alt === false ){
            $post_id = isset( $_GET['post'] ) ? $_GET['post'] : ( isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : 0 );
            $result = basename(get_post_meta($post_id, '_wp_page_template', TRUE));
        }
        elseif( ! is_admin() && $alt === false ){
            $result = basename( get_page_template() );
        }
        elseif( ! is_admin() && $alt === true ){
            $result = basename( get_page_template_slug( _post_id() ) );
        }
        return $result;
    }

}

// ----

if( !function_exists('_convert_newlines') ){

    /**
    * Convert new lines to HTML linebreak tags within a string
    *
    * @param string $string Add a string
    * @param string $type Determine to output as paragraphs ('p') or breaks ('br')
    * @return string $output Returns the altered string
    */

    function _convert_newlines($string, $type = 'p'){
        $output = null;

        if( !empty($string) ){
            $output = esc_html($string);

            if( $type === 'p' ){
                if(strstr($output, "\r\n")) {
                    $output = "<p>".implode("</p><p>", explode("\r\n", $output))."</p>";
                }

                if(strstr($output, "\n\n")) {
                    $output = "<p>".implode("</p><p>", explode("\n\n", $output))."</p>";
                }

                $output = str_replace("<p></p>", "", $output);
            }

            elseif($type === 'br' && strstr($output, "\n")){
                $output = implode("<br />", explode("\n", $output));
            }
        }

        return $output;
    }
}

// ----

if( !function_exists('_merge_arrays') ){

    /**
    * Check and two merge arrays
    *
    * @return array Returns a merged array
    */

    function _merge_arrays($default, $new){
        return !empty($new) && is_array($new) ? array_merge($default, $new) : $default;
    }
}

// ----

if( !function_exists('_print_r') ){

    /**
    * Alternative to print_r(). It just looks nicer.
    *
    * @return mixed Output
    */

    function _print_r($item){
        $text = print_r($item, true);
        if( $text ){
            print(
                "<pre class='nice-print' style='background-color:#fff;color:#222;padding:16px;margin-left:220px;border:1px solid #bbb;margin:.5rem 0;border-radius:4px;font-size:14px;white-space:pre-wrap;position:relative;z-index:99999;'>"
                    . $text .
                "</pre>"
            );
        }
    }

}

// ----

if( !function_exists('_printf') ){

    /**
     * Print a formatted string with the text domain already assigned
     * 
     * @param string $format The string to format
     * @param mixed $values (str|arr) An array of values
     * @return mixed Output the formatted string
     * 
     */

    function _printf($format = '', $values = []){
        if(is_array($values)){
            call_user_func_array('printf', array_merge((array)esc_html__($format, 'karri'), $values));
        }
        else{
            printf(esc_html__($format, 'karri'), $values);
        }
    }

}

// ----

if( !function_exists('_esc_html_e') ){

    /**
     * Escape and echo a string with the text domain already assigned
     * @param string $string The string to escape and echo
     * @return void
     */

    function _esc_html_e($string){
        esc_html_e($string, 'karri');
    }

}

// ----

if( !function_exists('_is_dark_colour') ){

    /**
    * Check if colour is dark
    *
    * @param string $colour The hex code of the colour
    * @param string $darker Than Adjustment, if darker than 155 by default
    * @return boolean Returns if the colour is dark or not
    */

    function _is_dark_colour($colour, $darkerThan = 155){
        $colour = ltrim($colour, '#');
        $r = hexdec($colour[0].$colour[1]);
        $g = hexdec($colour[2].$colour[3]);
        $b = hexdec($colour[4].$colour[5]);
        
        return ( ( $r*299 + $g*587 + $b*114 )/1000 <= $darkerThan );
    }

}

// ----

if( !function_exists('_colour_shade') ){

    /**
    * Provide a different shade of the given colour
    *
    * @param string $hex The hex code of the colour
    * @param string $float Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
    * @return boolean Returns the new hex code of the shade
    */

    function _colour_shade($hex, $float){
	
        $hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
        $new_hex = '#';
        
        if ( strlen( $hex ) < 6 ) {
            $hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
        }

        for ($i = 0; $i < 3; $i++) {
            $dec = hexdec( substr( $hex, $i * 2, 2 ) );
            $dec = min( max( 0, $dec + $dec * $float ), 255 ); 
            $new_hex .= str_pad( dechex( $dec ) , 2, 0, STR_PAD_LEFT );
        }		
        
        return $new_hex;
    }

}

// ----

if( !function_exists('_time_ago') ){

    /**
    * Time ago
    *
    * @param string $type 'post' or 'comment'
    * @param string $post_id Optional, add the post ID if not a 'post' type
    * @return string Returns the string
    */

    function _time_ago($type = 'post', $post_id = '') {
        $time = false;

        if( $type == 'post' ){
            $time = $post_id == '' ? get_the_time ( 'U' ) : get_the_time ( 'U', $post_id );
        }
        else if($type == 'comment'){
            $time = get_comment_time ( 'U' );
        }

        if($time){
            $time = sprintf( esc_html__( '%s ago', 'karri' ), human_time_diff( $time, current_time( 'timestamp' ) ) );
        }

        return $time;
    }

}

// ----

if( !function_exists('_get_paged') ){

    /**
    * Get the paged variable value
    *
    * @return string Return the 'paged' number
    */

    function _get_paged(){
        if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); }
        else if ( isset($_GET['paged']) ) { $paged = $_GET['paged']; }
        else { $paged = 1; }
        return absint($paged);
    }

}

// ----

if( !function_exists('_get_attachment_id') ){

    /**
    * Get the id of an attachment
    *
    * @param string $src Enter the url of the attachment
    * @return int Return the attachment's ID number
    */

    function _get_attachment_id($src){
        global $wpdb;
        $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$src'";
        $id = $wpdb->get_var($query);
        return $id;
    }

}