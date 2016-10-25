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
		//'supports' => array ( 'title', 'editor' )
		'supports' => false // This line removes the default metaboxes for Title and Editor fields
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

// Adding some jquery to the plugin, the right way!!!
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

// Let's add some custom taxonomy stuff so we can have appropriate categories for the quotes we add.
// We might use things like famous, strange, motivational, etc.


// Here is where we add the Meta Boxes to add our custom fields and data.
// As you can see each meta box requires a function that defines the HTML Markup, or what is contained in the meta box.
function author_meta_box_markup() {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	?>
    <div id="author-text">
    <input name="meta-box-text" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-text", true); ?>">    
    </div>
<?php    
}


function quote_meta_box_markup() {
    
}

function add_custom_meta_box()
{
    add_meta_box("author-meta-box", "Author Name", "author_meta_box_markup", "quote", "normal", "high", null);
	add_meta_box("quote-meta-box", "Quote", "quote_meta_box_markup", "quote", "normal", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");
?>