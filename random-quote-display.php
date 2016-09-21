<?php
/*
Plugin Name: Random Quote Display
Plugin URI:  Not applicable
Description: Simple plugin to display a random quote, using a custom post type
Version:     1.0
Author:      Elvis Sherman
Author URI:  http://wwww.clicktimedesign.com/
License:     
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Random Quote Display is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Random Quote Display is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Random Quote Display. If not, see {License URI}.
*/


register_activation_hook(__FILE__, 'rqd_activation');

function rqd_activation () {
	register_post_type( 'quote', $args );
	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'book' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	);	
}
add_action( 'init', 'rqd_activation' );


register_deactivation_hook(__FILE__, 'rqd_deactivation');

function rqd_deactivation()
{
    // our post type will be automatically removed, so no need to unregister it 
    // clear the permalinks to remove our post type's rules
    flush_rewrite_rules();
}


register_uninstall_hook(__FILE__, 'rqd_uninstall');

function rqd_uninstall() {
// The uninstall does not have to be done here, you can create an uninstall.php file in your plugin directory that runs when a user clicks 'Delete' after deactivating the plugin.	
}
?>