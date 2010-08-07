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
        	<li class="normal-detail">
            	<label>Number: </label>
                <div class="front">
                	<span><?php invoice_number(); ?></span>
                	<a href="#" class="wp3i-edit">Edit</a>
                </div>
                <div class="back">
                	<input type="text" name="invoice-number" id="invoice-number" value="<?php invoice_number(); ?>" size="2">
                    <a href="#" class="button wp3i-ok">OK</a>
                    <a href="#" class="wp3i-cancel">Cancel</a>
                </div>
            </li>
            <li class="normal-detail">
            	<label>Type: </label>
                <div class="front">
                	<span><?php echo get_invoice_type(); ?></span>
                	<a href="#" class="wp3i-edit">Edit</a>
                </div>
                <div class="back">
                	<select name="invoice-type" id="invoice-type">
                    	<option value="Invoice" <?php if(get_invoice_type() == 'Invoice'){echo'selected="selected"';} ?>>Invoice</option>
                        <option value="Quote" <?php if(get_invoice_type() == 'Quote'){echo'selected="selected"';} ?>>Quote</option>
                    </select>
                    <a href="#" class="button wp3i-ok">OK</a>
                    <a href="#" class="wp3i-cancel">Cancel</a>
                </div>
            </li>
            <li class="date-detail">
            	<label>Sent: </label>
                <div class="front">
                	<span><?php echo get_invoice_sent_pretty(); ?></span>
                	<a href="#" class="wp3i-edit">Edit</a>
                </div>
                <div class="back">
                	<select name="mm" id="mm">
                    	<option></option>
                        <option value="01">Jan</option>
                        <option value="02">Feb</option>
                        <option value="03">Mar</option>
                        <option value="04">Apr</option>
                        <option value="05">May</option>
                        <option value="06">Jun</option>
                        <option value="07">Jul</option>
                        <option value="08">Aug</option>
                        <option value="09">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
            		</select>
                    <input type="text" maxlength="2" size="2" value="" name="dd" id="dd">, 
                    <input type="text" maxlength="4" size="4" value="" name="yyyy" id="yyyy">
                	<input type="hidden" name="invoice-sent" id="invoice-sent" value="<?php echo get_invoice_sent(); ?>">

                    <a href="#" class="button wp3i-ok">OK</a>
                    <a href="#" class="wp3i-clear">Reset</a>
                    <a href="#" class="wp3i-cancel">Cancel</a>
                </div>
            </li>
            <li class="date-detail">
            	<label>Paid: </label>
                <div class="front">
                	<span><?php echo get_invoice_paid_pretty(); ?></span>
                	<a href="#" class="wp3i-edit">Edit</a>
                </div>
                <div class="back">
                	<select name="mm" id="mm">
                    	<option></option>
                        <option value="01">Jan</option>
                        <option value="02">Feb</option>
                        <option value="03">Mar</option>
                        <option value="04">Apr</option>
                        <option value="05">May</option>
                        <option value="06">Jun</option>
                        <option value="07">Jul</option>
                        <option value="08">Aug</option>
                        <option value="09">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
            		</select>
                    <input type="text" maxlength="2" size="2" value="31" name="dd" id="dd">, 
                    <input type="text" maxlength="4" size="4" value="2010" name="yyyy" id="yyyy">
                	<input type="hidden" name="invoice-paid" id="invoice-paid" value="<?php echo get_invoice_paid(); ?>">

                    <a href="#" class="button wp3i-ok">OK</a>
                    <a href="#" class="wp3i-clear">Reset</a>
                    <a href="#" class="wp3i-cancel">Cancel</a>
                </div>
            </li>
		</ul>
        
        <input type="hidden" name="wp3i_hidden_currency" id="wp3i_hidden_currency"  value="<?php wp3i_currency(); ?>" />
        <input type="hidden" name="wp3i_hidden_tax" id="wp3i_hidden_tax"  value="<?php wp3i_tax(); ?>" />
		<?php
		
		
	}
	
	function add_invoice_details() 
	{
		add_meta_box('invoice_details', 'Invoice Details', 'invoice_details', 'invoice', 'normal', 'high');
	} 
	add_action('admin_menu', 'add_invoice_details');
	
	
	
	/*--------------------------------------------------------------------------------------------
										Send Invoice
	--------------------------------------------------------------------------------------------*/
	
	
	function invoice_send() 
	{
		global $post;
		?>
        
			
        <?php if(get_invoice_client_name()): ?>
        	<?php if(get_invoice_client_email()): ?>
            	<!--<form method="post" action="email.php">
                    <input type="hidden" name="to" value="<?php echo get_invoice_client_email(); ?>" />
                    <input type="hidden" name="url" value="<?php the_permalink(); ?>" />
                    <input type="hidden" name="return" value="<?php echo get_edit_post_link(); ?>" />
        			<p><input type="submit" class="button" name="email" value="Send Invoice"> to <?php echo get_invoice_client_email(); ?></p>-->
                    <?php 
					//$send_invoice_link = plugins_url('email.php',__FILE__);
					//$send_invoice_link .= '?to='.get_invoice_client_email();
					//$send_invoice_link .= '&url='.get_permalink();
					//$send_invoice_link .= '&return='.get_edit_post_link();
					 ?>
                     <?php if($_GET['sent'] == 'success'): ?>
                     	<div class="updated">
                     		<p>Invoice sent successfully!</p>
                        </div>
                     <?php elseif($_GET['sent'] == 'fail'): ?>
                     	<div class="error">
                     		<p>Invoice failed to send.</p>
                        </div>
                     <?php endif; ?>
                     <p><a href="<?php the_permalink(); ?>?email=send" class="button">Send Invoice</a> to <?php echo get_invoice_client_email(); ?></p>
                <!--</form>-->
       		<?php else: ?>
            	<p>The client <?php echo get_invoice_client_name(); ?> has no registered email address.</p>
            <?php endif; ?>
		<?php else: ?>
        	<p>Please select a Client and Save your Invoice before emailing.</p>
        <?php endif; ?>
        
		<?php
	}
	
	function add_invoice_send() 
	{
		add_meta_box('invoice_send', 'Send Invoice to Client', 'invoice_send', 'invoice', 'normal', 'low');
	} 
	add_action('admin_menu', 'add_invoice_send');
	
	
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
                        <li class="description"><textarea name="detail-description[]" id="detail-description"><?php the_detail_description(); ?></textarea>
                        </li>
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
        <?php if(wp3i_has_tax()): ?>
        	<strong>Tax:</strong> <?php wp3i_currency(); ?><span class="invoice-tax"><?php the_invoice_tax(); ?></span>&nbsp;&nbsp;&nbsp;
        <?php endif; ?>
        <strong>Total:</strong> <?php wp3i_currency(); ?><span class="invoice-total"><?php the_invoice_total(); ?></span>&nbsp;&nbsp;&nbsp;
        <a class="add-detail button-primary" href="#" title="Add Detail">Add Detail</a>
        </p>
        </div> 
		<?php
	}
	
	function add_project_breakdown() 
	{
		add_meta_box('project_breakdown', 'Project Breakdown', 'project_breakdown', 'invoice', 'normal', 'low');
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
		update_post_meta($post_id, 'invoice_type',$_POST['invoice-type']);
		update_post_meta($post_id, 'invoice_sent',$_POST['invoice-sent']);
		update_post_meta($post_id, 'invoice_paid',$_POST['invoice-paid']);
		//update_post_meta($post_id, 'invoice_client',$_POST['invoice-client']);
		
		update_post_meta($post_id, 'detail_title', serialize($_POST['detail-title']));
		$save_description = array();
		foreach($_POST['detail-description'] as $description)
		{
				array_push($save_description,preg_replace('/[^A-Za-z0-9 ]/','', $description));
		}
		
		update_post_meta($post_id, 'detail_description', serialize($save_description));
		update_post_meta($post_id, 'detail_type', serialize($_POST['detail-type']));
		update_post_meta($post_id, 'detail_rate', serialize($_POST['detail-rate']));
		update_post_meta($post_id, 'detail_duration', serialize($_POST['detail-duration']));
		update_post_meta($post_id, 'detail_subtotal', serialize($_POST['detail-subtotal']));
	}
	add_action('save_post', 'save_attached_images');

?>