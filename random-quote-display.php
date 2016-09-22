<?php
/*
Plugin Name: Random Quote Display
Plugin URI:  Not applicable
Description: Custom Post Type to record quotes and credit names.
Version:     1.0
Author:      Elvis Sherman
Author URI:  http://wwww.clicktimedesign.com/
License:     
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function ctd_famous_quotes() {
	register_post_type( 'quote',
	array (
		'public' => true,
		'label' => 'Quotes',
		'publicly_queryable' => false,
		'rewrite' => array( 'slug' => 'quote' ),
		'menu_position' => 5,
		'menu_icon' => 'dashicons-format-quote',
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
	));
}
add_action( 'init', 'ctd_famous_quotes' );

?>