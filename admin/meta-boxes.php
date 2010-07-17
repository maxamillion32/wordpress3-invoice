<?php
	
	/*--------------------------------------------------------------------------------------------
										Invoice Details
	--------------------------------------------------------------------------------------------*/
	
	
	function invoice_details() 
	{
		global $post;
		
		// Use nonce for verification
  		echo '<input type="hidden" name="ei_noncename" id="ei_noncename" value="' .wp_create_nonce('ei-n'). '" />';
		?>
		<ul>
        	<li><label>Invoice Number</label><input type="text" name="invoice-number" id="invoice-number" value="<?php invoice_number(); ?>"></li>
            <li><label>Invoice Status</label>
            	<select name="invoice-status" id="invoice-status">
                	<option value="Quote" <?php if(get_invoice_status() == 'Quote'){echo'selected="selected"';} ?>>Quote</option>
                    <option value="Invoice Sent" <?php if(get_invoice_status() == 'Invoice Sent'){echo'selected="selected"';} ?>>Invoice Sent</option>
                    <option value="Invoice Paid" <?php if(get_invoice_status() == 'Invoice Paid'){echo'selected="selected"';} ?>>Invoice Paid</option>
                </select></li>
        	
		</ul>
        <input type="hidden" name="wp3i_hidden_currency" id="wp3i_hidden_currency"  value="<?php wp3i_currency(); ?>" />
        <input type="hidden" name="wp3i_hidden_tax" id="wp3i_hidden_tax"  value="<?php wp3i_tax(); ?>" />
	
		<?php
		
		
	}
	
	function add_invoice_details() 
	{
		add_meta_box('invoice_details', 'Invoice Details', 'invoice_details', 'invoice', 'normal', 'high');
		add_meta_box('invoice_details', 'Quote Details', 'invoice_details', 'quote', 'normal', 'high');
	} 
	add_action('admin_menu', 'add_invoice_details');
	
	
	/*--------------------------------------------------------------------------------------------
											Project Breakdown
	--------------------------------------------------------------------------------------------*/
	$detailCount = 0;
	
	function project_breakdown() 
	{
		global $post;

	
		
		$detailCount = count($detailTitle);
		
		// Use nonce for verification
  		echo '<input type="hidden" name="ei_noncename" id="ei_noncename" value="' .wp_create_nonce('ei-n'). '" />';
		?>
		
        <div class="detail detail-header">
        	<table cellpadding="0" cellspacing="0" width="100%">
            	<tr>
                	<td>
                    	<ul>
                        	<li class="title">Title</li>
                            <li class="description">Description</li>
                        </ul>
                    </td>
                    <td width="340">
                        <ul>
                        <li class="type">Type</li>
                            <li class="rate">Rate<span class="hr"></span></li>
                            <li class="duration">Time</li>
                            <li class="subtotal">Subtotal</li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
        <div class="details">
        <?php if(invoice_has_details()): ?>
        	<?php while(invoice_detail()): ?>
            	<div class="detail">
            		<table cellpadding="0" cellspacing="0" width="100%">
                   	<tr>
                    <td>
						<ul>
                        <li class="title"><input type="text" name="detail-title[]" id="detail-title" value="<?php the_detail_title(); ?>"></li>
                        <li class="description"><textarea name="detail-description[]" id="detail-description"><?php the_detail_description(); ?>
                        </textarea></li>
                        </ul>
                    </td>
                    <td width="340">
                        <ul>
                       	<li class="type">
                        	<select name="detail-type[]" id="detail-type">
                            	<option value="Timed" <?php if(get_the_detail_type() == 'Timed'){echo'selected="selected"';} ?>>Timed</option>
                            	<option value="Fixed" <?php if(get_the_detail_type() == 'Fixed'){echo'selected="selected"';} ?>>Fixed</option>
                            </select>
                        </li>
                        <li class="rate">
							<?php wp3i_currency(); ?><input onblur="if (this.value == '') {this.value = '0.00';}" onfocus="if(this.value == '0.00') {this.value = '';}"  type="text" name="detail-rate[]" id="detail-rate" value="<?php the_detail_rate(); ?>">
                        </li>
                        <li class="duration">
                        	<input onblur="if (this.value == '') {this.value = '0.0';}" onfocus="if(this.value == '0.0') {this.value = '';}"  type="text" name="detail-duration[]" id="detail-duration" value="<?php the_detail_duration(); ?>">
                        </li>
                        <li class="subtotal">
                        	<input type="hidden" name="detail-subtotal[]" id="detail-subtotal" value="<?php the_detail_subtotal(); ?>" />
                            <p id="detail-subtotal"><?php wp3i_currency(); ?> <?php the_detail_subtotal(); ?></p>
                        </li>
                        </ul>
                    </td>
                    </tr>
                    </table> 
                    <a class="delete" href="#" title="Remove Detail"></a>
                    <div class="grab"></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
         		<div class="detail">
                	<table cellpadding="0" cellspacing="0" width="100%">
                    	<tr>
                        	<td>
                                <ul>
                                	<li class="title"><input type="text" name="detail-title[]" id="detail-title"></li>
                                	<li class="description"><textarea name="detail-description[]" id="detail-description"></textarea></li>
                                </ul>
                            </td>
                            <td width="340">
                            	<ul>
                                    <li class="type">
                                    <select name="detail-type[]" id="detail-type">
                                        <option value="Timed">Timed</option>
                                        <option value="Fixed">Fixed</option>
                                    </select>
                                    </li>
                                    <li class="rate"><?php wp3i_currency(); ?><input onblur="if (this.value == '') {this.value = '0.00';}" onfocus="if(this.value == '0.00') {this.value = '';}"  type="text" name="detail-rate[]" id="detail-rate" value="0.00"><span class="hr"></span></li>
                                    <li class="duration"><input onblur="if (this.value == '') {this.value = '0.0';}" onfocus="if(this.value == '0.0') {this.value = '';}"  type="text" name="detail-duration[]" id="detail-duration" value="0.0"></li>
                                    <li class="subtotal">
                                    	<input type="hidden" name="detail-subtotal[]" id="detail-subtotal" value="0.00" />
                                        <p id="detail-subtotal"><?php wp3i_currency(); ?> 0.00</p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </table> 
                    <a class="delete" href="#" title="Remove Detail"></a>
                    <div class="grab"></div>
                </div>
        <?php endif; ?> 
        </div>  
		<div class="detail detail-footer">
        <p>
        <strong>Subtotal:</strong> <?php wp3i_currency(); ?><span class="invoice-subtotal"><?php the_invoice_subtotal(); ?></span>&nbsp;&nbsp;&nbsp;
        <strong>Tax:</strong> <?php wp3i_currency(); ?><span class="invoice-tax"><?php the_invoice_tax(); ?></span>&nbsp;&nbsp;&nbsp;
        <strong>Total:</strong> <?php wp3i_currency(); ?><span class="invoice-total"><?php the_invoice_total(); ?></span>&nbsp;&nbsp;&nbsp;
        <a class="add-detail button-primary" href="#" title="Add Detail">Add Detail</a>
        </p>
        </div> 
		<?php
	}
	
	function add_project_breakdown() 
	{
		add_meta_box('project_breakdown', 'Project Breakdown', 'project_breakdown', 'invoice', 'normal', 'low');
		add_meta_box('project_breakdown', 'Project Breakdown', 'project_breakdown', 'quote', 'normal', 'low');
	} 
	add_action('admin_menu', 'add_project_breakdown');
	
	
	
	/*--------------------------------------------------------------------------------------------
										Save
	--------------------------------------------------------------------------------------------*/
	function save_attached_images($post_id) {
		// verify this with nonce because save_post can be triggered at other times
		if (!wp_verify_nonce($_POST['ei_noncename'], 'ei-n')) return $post_id;
	
		// do not save if this is an auto save routine
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
		
		update_post_meta($post_id, 'invoice_number',$_POST['invoice-number']);
		update_post_meta($post_id, 'invoice_status',$_POST['invoice-status']);
		//update_post_meta($post_id, 'invoice_client',$_POST['invoice-client']);
		
		update_post_meta($post_id, 'detail_title', serialize($_POST['detail-title']));
		update_post_meta($post_id, 'detail_description', serialize($_POST['detail-description']));
		update_post_meta($post_id, 'detail_type', serialize($_POST['detail-type']));
		update_post_meta($post_id, 'detail_rate', serialize($_POST['detail-rate']));
		update_post_meta($post_id, 'detail_duration', serialize($_POST['detail-duration']));
		update_post_meta($post_id, 'detail_subtotal', serialize($_POST['detail-subtotal']));
	}
	add_action('save_post', 'save_attached_images');

?>