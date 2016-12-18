<?php

// Various Support Functions for the QQ Plugin

// Add a custom sytlesheet to the plugin
function rqd_register_scripts(){
	wp_enqueue_style( 'rqd-style', plugins_url( 'css/rqd-style.css' , __FILE__ ), false, '1.0', 'all' );
	wp_dequeue_script('autosave');
}
add_action('wp_enqueue_scripts','rqd_register_scripts');
add_action( 'admin_enqueue_scripts', 'rqd_register_scripts' );