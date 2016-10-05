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
		'rewrite' => array( 'slug' => 'quote' ),
		'menu_position' => 5,
		'show_ui' => true,
		'show_in_admin_bar' => true,
		'menu_icon' => 'dashicons-format-quote',
		'supports' => array ( 'title' )
	);
	
	register_post_type( 'quote', $args );
}
add_action( 'init', 'ctd_famous_quotes' );

// Change the place holder text for entering a new famous quote
function ctd_change_title_text( $title ){
     $screen = get_current_screen();
 
     if  ( 'quote' == $screen->post_type ) {
          $title = 'Enter a Famous Quote';
     }
 
     return $title;
}
 
add_filter( 'enter_title_here', 'ctd_change_title_text' );

///////////////////////////////
// Add Quote Credit (Author) //
///////////////////////////////

// HTML for the Metabox
function ctd_author_credit_markup() {
	
}

// Add the Box Itself
function ctd_author_credit_metabox()
{
    add_meta_box("ctd-author-credit-metabox", "Quote Credit (Author)", "ctd_author_credit_markup", "quote", "normal", "high", null);
}

add_action("add_meta_boxes", "ctd_author_credit_metabox");
?>