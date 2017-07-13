<?php
/*
Plugin Name: Jigoshop - Store Toolkit
Plugin URI: http://www.visser.com.au/jigoshop/plugins/store-toolkit/
Description: Permanently remove all store-generated details of your Jigoshop store.
Version: 1.2
Author: Visser Labs
Author URI: http://www.visser.com.au/about/
License: GPL2
*/

load_plugin_textdomain( 'jigo_st', null, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

include_once( 'includes/functions.php' );

include_once( 'includes/common.php' );

$jigo_st = array(
	'filename' => basename( __FILE__ ),
	'dirname' => basename( dirname( __FILE__ ) ),
	'abspath' => dirname( __FILE__ ),
	'relpath' => basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ )
);

$jigo_st['prefix'] = 'jigo_st';
$jigo_st['name'] = __( 'Store Toolkit for Jigoshop', 'jigo_st' );
$jigo_st['menu'] = __( 'Store Toolkit', 'jigo_st' );

if( is_admin() ) {

	function jigo_st_init() {

		$action = jigo_get_action();
		switch( $action ) {

			case 'nuke':

				if( !ini_get( 'safe_mode' ) )
					set_time_limit( 0 );

				if( isset( $_POST['jigo_st_products'] ) )
					jigo_st_clear_dataset( 'products' );
				if( isset( $_POST['jigo_st_product_categories'] ) )
					jigo_st_clear_dataset( 'categories' );
				if( isset( $_POST['jigo_st_product_tags'] ) )
					jigo_st_clear_dataset( 'tags' );
				if( isset( $_POST['jigo_st_product_images'] ) )
					jigo_st_clear_dataset( 'images' );
				if( isset( $_POST['jigo_st_sales_orders'] ) )
					jigo_st_clear_dataset( 'orders' );
				if( isset( $_POST['jigo_st_attributes'] ) )
					jigo_st_clear_dataset( 'attributes' );
				break;

		}

	}
	add_action( 'admin_init', 'jigo_st_init' );

	function jigo_st_default_html_page() {

		global $wpdb, $jigo_st;

		$products = jigo_st_return_count( 'products' );
		$categories = jigo_st_return_count( 'categories' );
		$tags = jigo_st_return_count( 'tags' );
		$images = jigo_st_return_count( 'images' );
		$orders = jigo_st_return_count( 'orders' );
		$attributes = jigo_st_return_count( 'attributes' );

		include_once( 'templates/admin/jigo-admin_st-toolkit.php' );

	}

	function jigo_st_html_page() {

		global $wpdb, $jigo_st;

		jigo_st_template_header();
		$action = jigo_get_action();
		switch( $action ) {

			case 'nuke':
				$message = __( 'Chosen Jigoshop details have been permanently erased from your Jigoshop store.', 'jigo_st' );
				$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				echo $output;

				jigo_st_default_html_page();
				break;

			default:
				jigo_st_default_html_page();
				break;

		}
		jigo_st_template_footer();

	}

}
?>