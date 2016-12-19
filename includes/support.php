<?php

// Various Support Functions for the QQ Plugin

// Add a custom sytlesheet to the plugin
function rqd_register_scripts(){
	wp_enqueue_style( 'rqd-style', plugins_url( '../css/rqd-style.css' , __FILE__ ), false, '1.0', 'all' );
	wp_dequeue_script('autosave');
}
add_action('wp_enqueue_scripts','rqd_register_scripts');
add_action( 'admin_enqueue_scripts', 'rqd_register_scripts' );

// Plugin Activation
function ctd_flush_rewrites() {
	ctd_famous_quotes();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ctd_flush_rewrites' );

// Plugin Deactivation
function ctd_flush_rewrites_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ctd_flush_rewrites_deactivate' );

// Adding some jquery to the plugin, the right way to ONLY affect this custom post type!!!
// All this code does is connect to the external js file contained in the plugin.
function wpse_cpt_enqueue( $hook_suffix ){
    $cpt = 'quote';

    if( in_array($hook_suffix, array('post.php', 'post-new.php') ) ){
        $screen = get_current_screen();

        if( is_object( $screen ) && $cpt == $screen->post_type ){
            // Register, enqueue scripts and styles here
			wp_enqueue_script(  'myscript', plugins_url('random-quote-display/js/custom.js') );			
        }
    }
}
add_action( 'admin_enqueue_scripts', 'wpse_cpt_enqueue');