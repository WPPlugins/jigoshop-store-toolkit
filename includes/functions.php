<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	function jigo_st_admin_menu() {

		add_management_page( __( 'Store Toolkit', 'jigo_st' ), __( 'Store Toolkit', 'jigo_st' ), 'manage_options', 'jigo_st', 'jigo_st_html_page' );

	}
	add_action( 'admin_menu', 'jigo_st_admin_menu' );

	function jigo_st_return_count( $dataset ) {

		global $wpdb;

		$count_sql = null;
		switch( $dataset ) {

			case 'products':
				$count = 0;
				$statuses = wp_count_posts( 'product' );
				if( $statuses ) {
					foreach( $statuses as $status )
						$count = $count + $status;
				}
				break;

			case 'categories':
				$count_sql = "SELECT COUNT(terms.`term_id`) FROM `" . $wpdb->terms . "` as terms, `" . $wpdb->term_taxonomy . "` as term_taxonomy WHERE terms.`term_id` = term_taxonomy.`term_id` AND term_taxonomy.`taxonomy` = 'product_cat'";
				break;

			case 'tags':
				$count_sql = "SELECT COUNT(terms.`term_id`) FROM `" . $wpdb->terms . "` as terms, `" . $wpdb->term_taxonomy . "` as term_taxonomy WHERE terms.`term_id` = term_taxonomy.`term_id` AND term_taxonomy.`taxonomy` = 'product_tag'";
				break;

			case 'images':
				$count_sql = "SELECT COUNT(`post_id`) FROM `" . $wpdb->postmeta . "` WHERE `meta_key` = '_jigoshop_exclude_image'";
				break;

			case 'orders':
				$count = 0;
				$statuses = wp_count_posts( 'shop_order' );
				if( $statuses ) {
					foreach( $statuses as $status )
						$count = $count + $status;
				}
				break;

			case 'attributes':
				$count_sql = "SELECT COUNT(`attribute_id`) FROM `" . $wpdb->prefix . "jigoshop_attribute_taxonomies`";
				break;

		}
		if( isset( $count ) || $count_sql ) {
			if( isset( $count ) )
				return $count;
			else
				$count = $wpdb->get_var( $count_sql );
				return $count;
		} else {
			return false;
		}

	}

	function jigo_st_clear_dataset( $dataset ) {

		global $wpdb;

		$post_statuses = array(
			'publish',
			'pending',
			'draft',
			'auto-draft',
			'future',
			'private',
			'inherit',
			'trash'
		);

		switch( $dataset ) {

			case 'products':
				$products = (array)get_posts( array( 
					'post_type' => 'product',
					'post_status' => $post_statuses,
					'numberposts' => -1
				) );
				if( $products ) {
					foreach( $products as $product ) {
						wp_delete_post( $product->ID, true );
						wp_set_object_terms( $product->ID, null, 'product_tag' );
						$attributes_sql = "SELECT `attribute_id` as ID, `attribute_name` as name, `attribute_label` as label, `attribute_type` as type FROM `" . $wpdb->prefix . "jigoshop_attribute_taxonomies`";
						$attributes = $wpdb->get_results( $attributes_sql );
						if( $attributes ) {
							foreach( $attributes as $attribute ) {
								echo $product->ID . ': pa_' . $attribute->name . '<br />';
								wp_set_object_terms( $product->ID, null, 'pa_' . $attribute->name );
							}
						}
					}
				}
				break;

			case 'categories':
				$categories_sql = "SELECT `term_id`, `term_taxonomy_id` FROM `" . $wpdb->term_taxonomy . "` WHERE `taxonomy` = 'product_cat'";
				$categories = $wpdb->get_results( $categories_sql );
				if( $categories ) {
					foreach( $categories as $category ) {
						wp_delete_term( $category->term_id, 'product_cat' );
						$wpdb->query( "DELETE FROM `" . $wpdb->terms . "` WHERE `term_id` = " . $category->term_id );
						$wpdb->query( "DELETE FROM `" . $wpdb->term_relationships . "` WHERE `term_taxonomy_id` = " . $category->term_taxonomy_id );
						$wpdb->query( "DELETE FROM `" . $wpdb->prefix . "jigoshop_termmeta` WHERE `jigoshop_term_id` = " . $category->term_id );
						delete_metadata( 'jigoshop_term', $category->term_id, 'thumbnail_id' );
					}
				}
				break;

			case 'tags':
				$tags_sql = "SELECT `term_id` FROM `" . $wpdb->term_taxonomy . "` WHERE `taxonomy` = 'product_tag'";
				$tags = $wpdb->get_results( $tags_sql );
				if( $tags ) {
					foreach( $tags as $tag ) {
						wp_delete_term( $tag->term_id, 'product_tag' );
						$wpdb->query( "DELETE FROM `" . $wpdb->terms . "` WHERE `term_id` = " . $tag->term_id );
						$wpdb->query( "DELETE FROM `" . $wpdb->term_relationships . "` WHERE `term_taxonomy_id` = " . $tag->term_id );
					}
				}
				break;

			case 'images':
				$images_sql = "SELECT `post_id` FROM `" . $wpdb->postmeta . "` WHERE `meta_key` = '_jigoshop_exclude_image'";
				$images = $wpdb->get_results( $images_sql );
				if( $images ) {
					foreach( $images as $image ) {
						wp_delete_post( $image->post_id );
					}
				}
				break;

			case 'orders':
				$orders = (array)get_posts( array(
					'post_type' => 'shop_order',
					'post_status' => $post_statuses,
					'numberposts' => -1
				) );
				if( $orders ) {
					foreach( $orders as $order )
						wp_delete_post( $order->ID, true );
				}
				break;

			case 'attributes':
				$attributes_sql = "SELECT `attribute_id` as ID, `attribute_name` as name, `attribute_label` as label, `attribute_type` as type FROM `" . $wpdb->prefix . "jigoshop_attribute_taxonomies`";
				$attributes = $wpdb->get_results( $attributes_sql );
				if( $attributes ) {
					foreach( $attributes as $attribute ) {
						$values_sql = "SELECT `jigoshop_term_id` as term_id FROM `" . $wpdb->prefix . "jigoshop_termmeta` WHERE `meta_key` = 'order_pa_" . $attribute->name . "'";
						$values = $wpdb->get_results( $values_sql );
						if( $values ) {
							foreach( $values as $value )
								wp_delete_term( $value->term_id, 'pa_' . $attribute->name );
						}
						$wpdb->query( "DELETE FROM `" . $wpdb->prefix . "jigoshop_termmeta` WHERE `meta_key` = 'order_pa_" . $attribute->name . "'" );
						$wpdb->query( "DELETE FROM `" . $wpdb->term_relationships . "` WHERE `term_taxonomy_id` = " . $attribute->ID );
					}
				}
				$wpdb->query( "DELETE FROM `" . $wpdb->prefix . "jigoshop_attribute_taxonomies`" );
				break;

		}

	}

	function jigo_st_template_header() {
		
		global $jigo_st; ?>
<div class="wrap">
	<div id="icon-tools" class="icon32"><br /></div>
	<h2><?php echo $jigo_st['menu']; ?></h2>
<?php
	}

	function jigo_st_template_footer() { ?>
</div>
<?php
	}

	if( !function_exists( 'remove_filename_extension' ) ) {

		function remove_filename_extension( $filename ) {

			$extension = strrchr( $filename, '.' );
			$filename = substr( $filename, 0, -strlen( $extension ) );

			return $filename;

		}

	}

	/* End of: WordPress Administration */

}
?>