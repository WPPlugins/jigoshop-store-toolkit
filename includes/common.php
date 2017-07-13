<?php
/*

Filename: common.php
Description: common.php loads commonly accessed functions across the Visser Labs suite.

- jigo_get_action

*/

if( is_admin() ) {

	/* Start of: WordPress Administration */

	include_once( 'common-dashboard_widgets.php' );

	/* End of: WordPress Administration */

}

if( !function_exists( 'jigo_get_action' ) ) {

	function jigo_get_action() {

		if( isset( $_GET['action'] ) )
			$action = $_GET['action'];
		else if( !isset( $action ) && isset( $_POST['action'] ) )
			$action = $_POST['action'];
		else
			$action = false;
		return $action;

	}

}
?>