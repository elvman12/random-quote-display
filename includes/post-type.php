<?php

// Create the custom post type, and custom taxonomy.

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
		'exclude_from_search' => false,
		'rewrite' => array( 'slug' => 'quote' ),
		'menu_position' => 5,
		'show_ui' => true,
		'slug' => 'quote',
		'show_in_admin_bar' => true,
		'menu_icon' => 'dashicons-format-quote',
		'supports' => false // This line removes the default metaboxes for Title and Editor fields
	);
	
	register_post_type( 'quote', $args );
}
add_action( 'init', 'ctd_famous_quotes' );

// Create Taxonomy for this custom post type
function rqd_taxonomy() {
	$labels = array(
		'name'                           => 'Quote Type',
		'singular_name'                  => 'Quote Types',
		'search_items'                   => 'Search Types',
		'all_items'                      => 'All Types',
		'edit_item'                      => 'Edit Type',
		'update_item'                    => 'Update Type',
		'add_new_item'                   => 'Add New Type',
		'new_item_name'                  => 'New Type Name',
		'menu_name'                      => 'Type',
		'view_item'                      => 'View Type',
		'popular_items'                  => 'Popular Type',
		'separate_items_with_commas'     => 'Separate Types with commas',
		'add_or_remove_items'            => 'Add or remove Types',
		'choose_from_most_used'          => 'Choose from the most used Types',
		'not_found'                      => 'No Types found'
		);
	
	register_taxonomy(
		'Type',
		'quote',
		array(
			'label' => 'Quote Type',
			'hierarchical' => true,
			'labels' => $labels,
			'public' => true,
			'show_admin_column' => true
		)
	);
}
add_action( 'init', 'rqd_taxonomy' );