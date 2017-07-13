<script type="text/javascript">
	function showProgress() {
		window.scrollTo(0,0);
		document.getElementById('progress').style.display = 'block';
		document.getElementById('content').style.display = 'none';
	}
</script>
<div id="content">
	<h3><?php _e( 'Nuke Jigoshop', 'jigo_st' ); ?></h3>
	<p><?php _e( 'Select the Jigoshop tables you wish to empty then click Start over to permanently remove Jigoshop generated details from your WordPress database.', 'jigo_st' ); ?></p>
	<form method="post" onsubmit="showProgress()">
		<div id="poststuff">
			<div class="postbox">
				<h3 class="hndle"><?php _e( 'Empty Jigoshop Tables', 'jigoshop_st' ); ?></h3>
				<div class="inside">
					<table class="form-table">

						<tr>
							<th>
								<label for="jigo_st_products"><?php _e( 'Delete Products', 'jigo_st' ); ?></label>
							</th>
							<td>
								<input type="checkbox" name="jigo_st_products"<?php if( $products == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $products; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="jigo_st_product_categories"><?php _e( 'Delete Product Categories', 'jigo_st' ); ?></label>
							</th>
							<td>
								<input type="checkbox" name="jigo_st_product_categories"<?php if( $categories == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $categories; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="jigo_st_product_tags"><?php _e( 'Delete Product Tags', 'jigo_st' ); ?></label>
							</th>
							<td>
								<input type="checkbox" name="jigo_st_product_tags"<?php if( $tags == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $tags; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="jigo_st_product_images"><?php _e( 'Delete Product Images', 'jigo_st' ); ?></label>
							</th>
							<td>
								<input type="checkbox" name="jigo_st_product_images"<?php if( $images == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $images; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="jigo_st_attributes"><?php _e( 'Delete Product Attributes', 'jigo_st' ); ?></label>
							</th>
							<td>
								<input type="checkbox" name="jigo_st_attributes"<?php if( $attributes == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $attributes; ?>)
							</td>
						</tr>

						<tr>
							<th>
								<label for="jigo_st_sales_orders"><?php _e( 'Delete Sales', 'jigo_st' ); ?></label>
							</th>
							<td>
								<input type="checkbox" name="jigo_st_sales_orders"<?php if( $orders == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $orders; ?>)
							</td>
						</tr>

					</table>
				</div>
			</div>
		</div>
		<p class="submit">
			<input type="submit" value="<?php _e( 'Start over', 'jigo_st' ); ?> &raquo;" class="button" />
		</p>
		<input type="hidden" name="action" value="nuke" />
	</form>
</div>
<div id="progress" style="display:none;">
	<p><?php _e( 'Chosen Jigoshop details are being nuked, this process can take awhile. Time for a beer?', 'jigo_st' ); ?></p>
	<img src="<?php echo plugins_url( '/templates/admin/images/progress.gif', $jigo_st['relpath'] ); ?>" alt="" />
</div>