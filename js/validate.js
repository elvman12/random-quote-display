( function ( $ ) {
	$( document ).ready( function () {

		//Require post title when adding/editing Project Summaries
		$( 'body' ).on( 'submit.edit-post', '#post', function () {

			// If the title isn't set
			if ( $( "#quoteinput" ).val().replace( / /g, '' ).length === 0 ) {

				// Show the alert
				window.alert( 'Both Quote and Author fields must be filled in.' );

				// Hide the spinner
				$( '#major-publishing-actions .spinner' ).hide();

				// The buttons get "disabled" added to them on submit. Remove that class.
				$( '#major-publishing-actions' ).find( ':button, :submit, a.submitdelete, #post-preview' ).removeClass( 'disabled' );

				return false;
			}
		});
	});
}( jQuery ) );