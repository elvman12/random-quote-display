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
	
	$label = array(
		'name'               => 'Quotes',
		'singular_name'      => 'Quote',
		'menu_name'          => 'Quote',
		'name_admin_bar'     => 'Quote',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Quote',
		'new_item'           => 'New Quote',
		'edit_item'          => 'Edit Quote',
		'view_item'          => 'View Quote',
		'all_items'          => 'All Quotes',
		'search_items'       => 'Search Quotes',
		'parent_item_colon'  => 'Parent Quotes:',
		'not_found'          => 'No Quotes found.',
		'not_found_in_trash' => 'No Quotes found in Trash.'
	);
	
	$args = array( 
		'public' => true,
		'label' => 'Quotes',
		'publicly_queryable' => false,
		'rewrite' => array( 'slug' => 'quote' ),
		'menu_position' => 5,
		'menu_icon' => 'dashicons-format-quote',
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
	);
	
	register_post_type( 'quote', $args );
}
add_action( 'init', 'ctd_famous_quotes' );

?>