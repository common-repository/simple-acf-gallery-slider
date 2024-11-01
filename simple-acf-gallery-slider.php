<?php
/**
 * @packge: SimpleACFGallerySlider
 */
/**
 * Plugin Name: Simple ACF Gallery Slider
 * Description: This Plugin Simple ACF Gallery Slider Creates A Responsive Slider Using Gallery Fields From ACF.
 * Version: 1.0.0
 * Author: Sorted Pixel
 * Author URI: https://sortedpixel.com
 * License: GPLv2 or later
 * Text Domain: simple-acf-gallery-slider
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
Copyright 2005-2015 Automattic, Inc.
 */

if (!defined('ABSPATH')) {
    die;
}

if ( ! function_exists( 'is_plugin_active' ) )
	 require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	 
if(is_plugin_active( 'advanced-custom-fields-pro/acf.php') ){

    define('PLUGIN_PATH', plugin_dir_path(__FILE__));
    define('PLUGIN_URL', plugin_dir_url(__FILE__));

    class SimpleACFGallerySlider
    {

        public function sacfgs_register()
        {

            add_action('wp_enqueue_scripts', array($this, 'sacfgs_enqueue_scripts'));

            // add_action('wp_head',array( $this, 'sacfgs_get_images' ));
            add_action('init', array($this, 'sacfgs_shortcode_init'));
        }

        public function sacfgs_enqueue_scripts()
        {
            // wp_deregister_script('jquery');
            //wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);

            wp_register_script('sacfgs_slick_slider_js', PLUGIN_URL . 'assets/js/slick.js', array('jquery'), null, '');
            wp_register_script('sacfgs_slick_slider_script', PLUGIN_URL . 'assets/js/slickscript.js');
            wp_register_style('sacfgs_slick_slider_style', PLUGIN_URL . 'assets/css/slick.css');
            wp_register_style('sacfgs_main_slider_style', PLUGIN_URL . 'assets/css/sacfgs-main.css');

            wp_enqueue_script('sacfgs_slick_slider_js');
            wp_enqueue_style('sacfgs_slick_slider_style');
            wp_enqueue_style('sacfgs_main_slider_style');
        }

        public function sacfgs_shortcode_init()
        {
            add_shortcode('simpleacfgallery', array($this, 'sacfgs_shortcode'));
        }

        public function sacfgs_shortcode($atts)
        {
            $atts = array_change_key_case((array) $atts, CASE_LOWER); // normalize attribute keys, lowercase
            extract(shortcode_atts(
                array(
                    'autoplay' => 'true',
                    'speed' => 2000,
                    'gallery' => '',
                    'post_id' => '',
                ),
                $atts,
                'simpleacfgallery'
            ));
            // localize script to make shortcode parameters available inside the JS script
            wp_localize_script('sacfgs_slick_slider_script', 'sacfgs_attribute_object', $atts);
            wp_enqueue_script('sacfgs_slick_slider_script');

            $images = $this->sacfgs_get_images(esc_attr($gallery), esc_attr($post_id));
            ?>

            <section class="sacfgs-slider-wrapper">
                <div class="sacfgs-slider-row">
                    <div class="sacfgs-slider">
                        <?php if (!empty($images)): ?>
                            <?php foreach ($images as $image) {?>
                                <?php
                                $slideimage = $image['url'];
                                ?>
                                <div class="slick-container">
                                <img src="<?php echo $slideimage; ?>" alt="<?php echo $image['alt']; ?>" />
                                </div>
                            <?php }?>
                        <?php endif;?>
                    </div>
            </section>

            <?php
    }

        public function sacfgs_get_images($gallery, $post_id)
        {

            $images = get_field($gallery, $post_id);
            if (!empty($images)) {
                return $images;
            }
            return null;
        }

    }

    //Create class instance to register the plugin
    if (class_exists('SimpleACFGallerySlider')) {
        $sacfgs = new SimpleACFGallerySlider();
        $sacfgs->sacfgs_register();
    }
}
else{
    	// If ACF isn't installed and activated, throw an error.
        ?>
        <div class="wpcf7-redirect-error error notice">
            <h3>
                <?php esc_html_e( 'Simple ACF Gallery Slider', 'acfcl' );?>
            </h3>
            <p>
                <?php esc_html_e( 'Error: Please install and activate ACF plugin.', 'acf' );?>
            </p>
        </div>
        <?php
	}
