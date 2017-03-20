<?php
class rqd_widget extends WP_Widget {

	public function __construct() {
		$widget_options = array(
		'classname' => 'quote_widget',
		'description' => 'Widget to display Random Quote',
		);
	parent::__construct( 'quote_widget', 'Random Quote Display', $widget_options );
	}
}
?>

<?php
function rqd_register_widget() {
	register_widget( 'rqd_widget' );
}
add_action( 'widgets_init', 'rqd_register_widget' );
?>