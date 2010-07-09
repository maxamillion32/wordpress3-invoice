<?php
	
	/*--------------------------------------------------------------------------------------------
										Invoice Details
	--------------------------------------------------------------------------------------------*/
	function get_next_invoice_number()
	{
		$newNumber = 0;
		$invoices = get_posts(array('post_type' => 'invoice', 'numberposts' => '-1'));
		foreach($invoices as $invoice)
		{
			$tempNumber = intval(get_post_meta($invoice->ID, 'invoice_number', true));
			if($tempNumber > $newNumber){$newNumber = $tempNumber;}
		}
		
		$newNumber +=1;
		return $newNumber;
	}
	
	function invoice_details() 
	{
		global $post;
		$invoiceNumber = get_post_meta($post->ID, 'invoice_number', true)?get_post_meta($post->ID, 'invoice_number', true): get_next_invoice_number();
		$invoiceStatus = get_post_meta($post->ID, 'invoice_status', true)?get_post_meta($post->ID, 'invoice_status', true):'Quote';
		//$invoiceClient = get_post_meta($post->ID, 'invoice_client', true);

		// Use nonce for verification
  		echo '<input type="hidden" name="ei_noncename" id="ei_noncename" value="' .wp_create_nonce('ei-n'). '" />';
		?>
		<ul>
        	<li><label>Invoice Number</label><input type="text" name="invoice-number" id="invoice-number" value="<?php echo $invoiceNumber; ?>"></li>
            <li><label>Invoice Status</label>
            	<select name="invoice-status" id="invoice-status">
                	<option value="Quote" <?php if($invoiceStatus == 'Quote'){echo'selected="selected"';} ?>>Quote</option>
                    <option value="Invoice Sent" <?php if($invoiceStatus == 'Invoice Sent'){echo'selected="selected"';} ?>>Invoice Sent</option>
                    <option value="Invoice Paid" <?php if($invoiceStatus == 'Invoice Paid'){echo'selected="selected"';} ?>>Invoice Paid</option>
                </select></li>
        	
		</ul>
        <input type="hidden" name="wp3i_hidden_currency" id="wp3i_hidden_currency"  value="<?php wp3i_currency(); ?>" />
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

		$detailTitle = unserialize(get_post_meta($post->ID, 'detail_title', true));// if(empty($detailTitle[0])){$detailTitle = array('Title');}
		$detailDescription = unserialize(get_post_meta($post->ID, 'detail_description', true));// if(empty($detailDescription[0])){$detailDescription = array('Description');}
		$detailType = unserialize(get_post_meta($post->ID, 'detail_type', true));// if(empty($detailType[0])){$detailType = array('Timed');}
		$detailRate = unserialize(get_post_meta($post->ID, 'detail_rate', true)); if(empty($detailRate[0])){$detailRate = array('0.00');}
		$detailDuration = unserialize(get_post_meta($post->ID, 'detail_duration', true)); if(empty($detailDuration[0])){$detailDuration = array('0.0');}
		$detailSubtotal = unserialize(get_post_meta($post->ID, 'detail_subtotal', true));// if(empty($detailSubtotal[0])){$detailSubtotal = array('0.00');}
		
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
                
                
            	<?php for($i = 0; $i < $detailCount; $i++):?>
				<div class="detail">
                	<table cellpadding="0" cellspacing="0" width="100%">
                    	<tr>
                        	<td>
                                <ul>
                                	<li class="title"><input type="text" name="detail-title[]" id="detail-title" value="<?php echo $detailTitle[$i]; ?>"></li>
                                	<li class="description"><textarea name="detail-description[]" id="detail-description"><?php echo $detailDescription[$i]; ?></textarea></li>
                                </ul>
                            </td>
                            <td width="340">
                            	<ul>
                                    <li class="type">
                                    <select name="detail-type[]" id="detail-type">
                                        <option value="Timed" <?php if($detailType[$i] == 'Timed'){echo'selected="selected"';} ?>>Timed</option>
                                        <option value="Fixed" <?php if($detailType[$i] == 'Fixed'){echo'selected="selected"';} ?>>Fixed</option>
                                    </select>
                                    </li>
                                    <li class="rate"><?php wp3i_currency(); ?><input onblur="if (this.value == '') {this.value = '0.00';}" onfocus="if(this.value == '0.00') {this.value = '';}"  type="text" name="detail-rate[]" id="detail-rate" value="<?php echo $detailRate[$i]; ?>"><span class="hr"></span></li>
                                    <li class="duration"><input onblur="if (this.value == '') {this.value = '0.0';}" onfocus="if(this.value == '0.0') {this.value = '';}"  type="text" name="detail-duration[]" id="detail-duration" value="<?php echo $detailDuration[$i]; ?>"></li>
                                    <li class="subtotal">
                                    	<input type="hidden" name="detail-subtotal[]" id="detail-subtotal" value="<?php echo $detailSubtotal[$i]; ?>" />
                                        <p id="detail-subtotal"><?php wp3i_currency(); ?> <?php echo $detailSubtotal[$i]; ?></p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </table> 
                    <a class="delete" href="#" title="Remove Detail"></a>
                    <div class="grab"></div>
                </div>
                <?php endfor; ?>
                
			</div>
            
            <div class="detail detail-footer">
            	<p><a class="add-detail button-primary" href="#" title="Add Detail">Add Detail</a></p>
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