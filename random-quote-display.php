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

add_action( 'init', 'ctd_famous_quotes' );

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

// Add a custom sytlesheet to the plugin
function rqd_register_scripts(){
	wp_enqueue_style( 'rqd-style', plugins_url( 'rqd-style.css' , __FILE__ ) );
	wp_dequeue_script('autosave');
}
add_action('wp_enqueue_scripts','rqd_register_scripts');
add_action( 'admin_enqueue_scripts', 'rqd_register_scripts' );


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


// Here is where we add the Meta Boxes to add our custom fields and data.
// As you can see each meta box requires a function that defines the HTML Markup, or what is contained in the meta box.

// Markup for the author input
function author_meta_box_markup() {
	global $post;
	$author_box_text = get_post_meta( $post->ID, 'author-box-text', true );
	$author_box_text = htmlentities($author_box_text, ENT_QUOTES);
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	?>
    
    <div id="authorinput">
    <input name="author-box-text" type="text" value="<?php echo "$author_box_text"; ?>"><br>
    </div> 
   
<?php    
}

// Markup for the quote input
function quote_meta_box_markup() {
	global $post;
	//$quote_box_text = get_post_meta( $post->ID, 'quote-box-text', true );
	$quote_title = get_the_title();
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	?>
    
    <div id="quoteinput">
    <input name="quote-box-text" type="text" value="<?php echo "$quote_title"; ?>"><br>    
    </div> 
   
<?php
}

// This is what makes the meta boxes actually appear
function add_custom_meta_box()
{    
	add_meta_box("quote-meta-box", "Enter the Quote", "quote_meta_box_markup", "quote", "normal", "low", null);
	add_meta_box("author-meta-box", "Author Name", "author_meta_box_markup", "quote", "normal", "low", null);
}
add_action("add_meta_boxes", "add_custom_meta_box");

// Remove Unnecessary Metaboxes
function ctd_remove_meta_stuff() {
    remove_meta_box( 'sharing_meta' , 'quote' , 'advanced' );
	remove_meta_box( 'wpseo_meta' , 'quote' , 'normal' );
}
add_action('do_meta_boxes','ctd_remove_meta_stuff');

// Now we need to save the entered data to the dbase when someone clicks save or publish
function rqd_save_quote($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    $slug = "quote";
    if($slug != $post->post_type)
        return $post_id;

    $author_box_text_value = "";
	$quote_box_text_value = "";    

    if(isset($_POST["author-box-text"]))
    {
        $author_box_text_value = sanitize_text_field($_POST["author-box-text"]);
	}   
    update_post_meta($post_id, "author-box-text", $author_box_text_value);
	
	if(isset($_POST["quote-box-text"]))
    {
		$quote_box_text_value = sanitize_text_field($_POST["quote-box-text"]);
	}   
    update_post_meta($post_id, "quote-box-text", $quote_box_text_value);
	
	global $wpdb;
	if ( get_post_type( $post_id ) == 'quote' ) {
		$quotetitle = get_post_meta($post_id, 'quote-box-text', true);
		$quotetitle = trim($quotetitle,'"');
		$where = array( 'ID' => $post_id );
		$wpdb->update( $wpdb->posts, array( 'post_title' => $quotetitle ), $where );
	}			
}
add_action("save_post", "rqd_save_quote", 10, 3);


// Define Admin Columns
function rqd_set_columns ( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Quote' ),
		'quote-author' => __( 'Quote Author' ),
		'quote-type' => __( 'Type' ),
		'date' => __( 'Date' )
		
	);
	return $columns;
}
add_filter( 'manage_quote_posts_columns', 'rqd_set_columns' );

// Populate Admin Columns
function rqd_populate_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		// Display Quote Author
		case 'quote-author' :
			/* Get the post meta for quote author. */
			$rqd_author = get_post_meta( get_the_ID(), 'author-box-text', true );

			/* If no author is found, output a default message. */
			if ( empty( $rqd_author ) )
				echo __( 'Unknown' );
			/* If there is a quote author, display it. */
			else
				printf( $rqd_author );
			break;
			
		// Display Quote Type
		case 'quote-type' :
			/* Get the quote type. */
			$rqd_type = get_the_terms( $post_id, 'Type' );
			/* If types were found. */
			if ( !empty( $rqd_type ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific type. */
				foreach ( $rqd_type as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'type' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'type', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}
			/* If no terms were found, output a default message. */
			else {
				_e( 'No Quote Type' );
			}
			break;			
			
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}
add_action( 'manage_quote_posts_custom_column' , 'rqd_populate_columns', 10, 2 );

// Make the Quote Author Column Sortable
function rqd_sortable_columns( $columns ) {
	$columns['quote-author'] = 'quote-author';
	return $columns;
}
add_filter( 'manage_edit-quote_sortable_columns', 'rqd_sortable_columns' );

/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'rqd_edit_quote_load' );

function rqd_edit_quote_load() {
	add_filter( 'request', 'rqd_sort_authors' );
}

/* Sorts the movies. */
function rqd_sort_authors( $vars ) {	

	/* Check if we're viewing the 'movie' post type. */
	if ( isset( $vars['post_type'] ) && 'quote' == $vars['post_type'] ) {		
		

		/* Check if 'orderby' is set to 'duration'. */
		if ( isset( $vars['orderby'] ) && 'quote-author' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'author-box-text',
					'orderby' => 'meta_value'
				)
			);
		}
	}

	return $vars;
}

// Let's add a simple shortcode that can be used to add this to text widgets
function rqdshortcode () {
ob_start();
?>

<div class="rqd-container">
    	<?php
		$args=array('post_type'=>'quote', 'orderby'=>'rand', 'posts_per_page'=>'1');

		$randomquote=new WP_Query($args);
		while ($randomquote->have_posts()) : $randomquote->the_post();
			$ctd_newtitle = get_the_title();			
		
			?><p class="rqd-quote"><?php echo "\"" . $ctd_newtitle . "\"";?></p>
            
            <?php $authorname = get_post_meta( get_the_ID(), 'author-box-text', true ); ?>
            <p class="rqd-author"><?php echo "- " . $authorname . " -";?></p>
        <?php    
		endwhile;
		wp_reset_postdata();
		?> </div> <?php
		
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
add_shortcode( 'quickquote', 'rqdshortcode' );
add_filter( 'widget_text', 'do_shortcode' );

// Improvements for next IDP....
// Shortcodes with parameters.
?>