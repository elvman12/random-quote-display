<?php

// Work with the displayed columns in the admin panel

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