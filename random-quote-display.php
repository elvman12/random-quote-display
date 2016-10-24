<?php
/*
Plugin Name: Random Quote Display
Plugin URI:  Not applicable
Description: Custom Post Type to record quotes and credit names.
Version:     1.0
Author:      Elvis Sherman
Author URI:  http://wwww.clicktimedesign.com/
License:     Pretty much free, enjoy.
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function ctd_famous_quotes() {
	
	$labels = array(
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
		'hierarchical' => false,
		'labels' => $labels,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'rewrite' => array( 'slug' => 'quote' ),
		'menu_position' => 5,
		'show_ui' => true,
		'show_in_admin_bar' => true,
		'menu_icon' => 'dashicons-format-quote',
		'supports' => array ( 'title', 'editor' )
		//'supports' => false // This line removes the default metaboxes for Title and Editor fields
	);
	
	register_post_type( 'quote', $args );
}
add_action( 'init', 'ctd_famous_quotes' );

// Change the place holder text for entering a new famous quote
function ctd_change_title_text( $title ){
     $screen = get_current_screen();
 
     if  ( 'quote' == $screen->post_type ) {
          $title = 'Quote author name goes here';
     }
 
     return $title;
}
 
add_filter( 'enter_title_here', 'ctd_change_title_text' );

// Adding some js to the plugin
function ctd_quotes_loadscripts($hook) {
 
	global $pw_settings_page;
 
	// Load only on ?page=mypluginname
        if ( 'edit-quote.php' != $hook ) 
		return;
 
	wp_enqueue_script( 'custom-js', plugins_url( 'js/custom.js' , dirname(__FILE__) ) );
}
add_action('admin_enqueue_scripts', 'ctd_quotes_loadscripts');

?>