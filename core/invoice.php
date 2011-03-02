<?php
class Invoice
{
	var $name;
	var $dir;
	var $plugin_dir;
	var $plugin_path;
	
	/**
	 * Invoice Constructor
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 * @param object: Wp3i to find parent variables.
	 **/
	function Invoice($parent)
	{
		$this->name = $parent->name;					// Plugin Name
		$this->plugin_dir = $parent->dir;				// Plugin directory
		$this->plugin_path = $parent->path;				// Plugin Absolute Path
		$this->dir = plugins_url('/',__FILE__);			// This directory
		
		
		// Set up Actions
		add_action('init', array($this, 'create_custom_post'));
		add_action('init', array($this, 'action_init'));
		
		add_filter('manage_edit-invoice_columns', array($this, 'invoice_columns_setup'));
		add_filter('manage_edit-invoice_sortable_columns', array($this, 'invoice_columns_setup_sortable'));
		add_filter('request', array($this, 'invoice_column_orderby') );
		add_action('manage_posts_custom_column', array($this, 'invoice_columns_data'));
		
		add_action('restrict_manage_posts', array($this, 'invoice_columns_filter'));
		//add_filter('pre_get_posts', array($this, 'invoice_number_order'));
		
		add_action('admin_menu', array($this, 'create_meta_boxes'));
		add_action('save_post', array($this, 'save_invoice'));
		add_action('template_redirect', array($this, 'invoice_template_redirect'));
		
		add_filter('wp_footer', array($this, 'task_bar'));
		return true;
	}
	
	/**
	 * Creates Custom Posts type: Invoice
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function create_custom_post()
	{
		$labels = array(
			'name' => __( 'Invoices' ),
			'singular_name' => __( 'Invoice'),
			'search_items' =>  __( 'Search Invoices' ),
			'all_items' => __( 'All Invoices' ),
			'parent_item' => __( 'Parent Invoice' ),
			'parent_item_colon' => __( 'Parent Invoice:' ),
			'edit_item' => __( 'Edit Invoice' ), 
			'update_item' => __( 'Update Invoice' ),
			'add_new_item' => __( 'Add New Invoice' ),
			'new_item_name' => __( 'New Invoice Name' ),
		); 	
		
		$supports = array('title');
		//$supports[] = 'custom-fields';
		
		if(wp3i_get_content_editor() == 'enabled')
		{
			$supports[] = 'editor';
			
		}

		
		register_post_type('invoice', array(
			'labels' => $labels,
			'menu_icon' => $this->plugin_dir.'/admin/images/invoice-icon.gif',
			'public' => true,
			'show_ui' => true,
			'_builtin' =>  false,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => array("slug" => "invoice"), // Permalinks format
			'query_var' => "invoice",
			'supports' => $supports,
		));
		
		
	}
	
	
	
	/**
	 * Creates Custom Posts type: Invoice
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function invoice_columns_setup($columns)
	{	
		/*unset($columns['cb']);
		$columns["invoice_no"] = "Invoice No.";
		$columns["invoice_type"] = "Type";
		$columns["invoice_type"] = "Type";
		$columns["title"] = "Title";
		$columns["amount"] = "Amount";
		$columns["status"] = "Status";
		$columns["client"] = "Client";
		return $columns;*/
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"invoice_no" => "Invoice No.",
			"invoice_type" => "Type",
			"title" => "Title",
			"amount" => "Amount",
			"status" => "Status",
			"client" => "Client",
		);
		return $columns;
	}
	
	function invoice_columns_setup_sortable( $columns ) 
	{
		$columns["invoice_no"] = "invoice_no";
		$columns["invoice_type"] = "invoice_type";
		return $columns;
	}

	function invoice_column_orderby($vars) 
	{
		if(!isset($vars['orderby']))
		{
			return $vars;
		}
		
		if ($vars['orderby'] == 'invoice_no')
		{
			$vars = array_merge( $vars, array(
		      'meta_key' => 'invoice_number',
		      'orderby' => 'meta_value_num'
		    ) );
		}
		
		if ($vars['orderby'] == 'invoice_type')
		{
			$vars = array_merge( $vars, array(
		      'meta_key' => 'invoice_type',
		      'orderby' => 'meta_value'
		    ) );
		}

	 
		return $vars;
	}

	function invoice_columns_data($column)
	{
		global $post, $Wp3i;
		if ("ID" == $column) echo $post->ID;
		elseif ("invoice_no" == $column) echo get_post_meta($post->ID, 'invoice_number', true);
		elseif ("invoice_type" == $column) invoice_type($post->ID);
		elseif ("amount" == $column) echo wp3i_format_amount(wp3i_get_invoice_total($post->ID));
		elseif ("client" == $column) echo get_invoice_client_edit($post->ID);
		elseif ("status" == $column) echo get_invoice_status($post->ID); 
	}
	
	/**
	 * Orders Invoice by Invoice Number, not date created
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function invoice_number_order( $query ) 
	{
		if(!is_admin())
		{
			return $query;
		}
			
		if($query->query['post_type'] == 'invoice') 
		{
			$query->set('meta_key', 'invoice_number' );
			$query->set('meta_compare', '>=');
			$query->set('meta_value', false );
			$query->set('orderby', 'meta_value');
			$query->set('order', 'ASC');
			//$query->set('post_status', 'publish,pending,draft,future,private');

		}
		return $query;
	}

	

	/**
	 * Adds filters to Invoice Columns
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function invoice_columns_filter()
	{
		global $wp, $post;
		$post_type = $wp->query_vars["post_type"];
		
		if($post_type == 'invoice')
		{
			$the_terms = get_terms('client','orderby=name&hide_empty=0' );
						
			$content  = '<select name="client" id="client" class="postform">';
			$content .= '<option value="0">'.__('View all Clients','wp3i').'</option>';
			foreach ($the_terms as $term){
				$content .= '<option value="' . $term->slug . '">'. $term->name . ' ('.$term->count.')</option>';
			}
			$content .= '</select>';
					
			$content = str_replace('post_tag', 'tag', $content);
			echo $content;
		}
		
	}
	
	/**
	 * action Init function
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function action_init()
	{
		// 1. flush and refresh permalinks
		global $wp_rewrite;
    	$wp_rewrite->flush_rules();
		
		// 2. Rewrite Premalinks
		$rewrite_rules = $wp_rewrite->generate_rewrite_rules('invoice/');
		$rewrite_rules['invoice/?$'] = 'index.php?paged=1';
	
		foreach($rewrite_rules as $regex => $redirect)
		{
			if(strpos($redirect, 'attachment=') === false)
				{
					$redirect .= '&post_type=invoice';
				}
			if(0 < preg_match_all('@\$([0-9])@', $redirect, $matches))
				{
					for($i = 0; $i < count($matches[0]); $i++)
					{
						$redirect = str_replace($matches[0][$i], '$matches['.$matches[1][$i].']', $redirect);
					}
				}
			$wp_rewrite->add_rule($regex, $redirect, 'top');
		}
		
		// 3. flush and refresh permalinks
		global $wp_rewrite;
    	$wp_rewrite->flush_rules();

	}
	
	/**
	 * Meta Box: Invoice Details
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function invoice_details() 
	{
		global $post;
		
		// Use nonce for verification
  		echo '<input type="hidden" name="ei_noncename" id="ei_noncename" value="' .wp_create_nonce('ei-n'). '" />';
		?>

		<ul>
        	<li class="normal-detail">
            	<label><?php _e('Number:','wp3i'); ?> </label>
                <div class="front">
                	<span><?php invoice_number(); ?></span>
                	<a href="#" class="wp3i-edit"><?php _e('Edit','wp3i'); ?></a>
                </div>
                <div class="back">
                	<input type="text" name="invoice-number" id="invoice-number" value="<?php invoice_number(); ?>" size="2" />
                    <a href="#" class="button wp3i-ok"><?php _e('OK','wp3i'); ?></a>
                    <a href="#" class="wp3i-cancel"><?php _e('Cancel','wp3i'); ?></a>
                </div>
            </li>
            <li class="normal-detail">
            	<label><?php _e('Type:','wp3i'); ?> </label>
                <div class="front">
                	<span><?php invoice_type(); ?></span>
                	<a href="#" class="wp3i-edit"><?php _e('Edit','wp3i'); ?></a>
                </div>
                <div class="back">
                	<select name="invoice-type" id="invoice-type">
                    	<option value="<?php _e('Invoice','wp3i'); ?>" <?php if(get_invoice_type() == __('Invoice','wp3i')){echo'selected="selected"';} ?>><?php _e('Invoice','wp3i'); ?></option>
                        <option value="<?php _e('Quote','wp3i'); ?>" <?php if(get_invoice_type() == __('Quote','wp3i')){echo'selected="selected"';} ?>><?php _e('Quote','wp3i'); ?></option>
                    </select>
                    <a href="#" class="button wp3i-ok"><?php _e('OK','wp3i'); ?></a>
                    <a href="#" class="wp3i-cancel"><?php _e('Cancel','wp3i'); ?></a>
                </div>
            </li>
            <li class="normal-detail">
            	<label><?php _e('Tax:','wp3i'); ?> </label>
                <div class="front">
                	<span><?php wp3i_tax(); ?></span>
                	<a href="#" class="wp3i-edit"><?php _e('Edit','wp3i'); ?></a>
                </div>
                <div class="back">
                	<input type="text" name="invoice-tax" id="invoice-tax" value="<?php wp3i_tax(); ?>" size="2" />
                    <a href="#" class="button wp3i-ok update-subtotal"><?php _e('OK','wp3i'); ?></a>
                    <a href="#" class="wp3i-cancel update-subtotal"><?php _e('Cancel','wp3i'); ?></a>
                </div>
            </li>
            <li class="date-detail">
            	<label><?php _e('Sent:','wp3i'); ?> </label>
                <div class="front">
                	<span><?php echo get_invoice_sent_pretty(); ?></span>
                	<a href="#" class="wp3i-edit"><?php _e('Edit','wp3i'); ?></a>
                </div>
                <div class="back">
                	<select name="mm" id="mm">
                    	<option></option>
                        <option value="01"><?php _e('Jan','wp3i'); ?></option>
                        <option value="02"><?php _e('Feb','wp3i'); ?></option>
                        <option value="03"><?php _e('Mar','wp3i'); ?></option>
                        <option value="04"><?php _e('Apr','wp3i'); ?></option>
                        <option value="05"><?php _e('May','wp3i'); ?></option>
                        <option value="06"><?php _e('Jun','wp3i'); ?></option>
                        <option value="07"><?php _e('Jul','wp3i'); ?></option>
                        <option value="08"><?php _e('Aug','wp3i'); ?></option>
                        <option value="09"><?php _e('Sep','wp3i'); ?></option>
                        <option value="10"><?php _e('Oct','wp3i'); ?></option>
                        <option value="11"><?php _e('Nov','wp3i'); ?></option>
                        <option value="12"><?php _e('Dec','wp3i'); ?></option>
            		</select>
                    <input type="text" maxlength="2" size="1" value="" name="dd" id="dd" />, 
                    <input type="text" maxlength="4" size="3" value="" name="yyyy" id="yyyy" />
                	<input type="hidden" name="invoice-sent" id="invoice-sent" value="<?php echo get_invoice_sent(); ?>" />

                    <a href="#" class="button wp3i-ok"><?php _e('OK','wp3i'); ?></a>
                    <a href="#" class="wp3i-clear"><?php _e('Reset','wp3i'); ?></a>
                    <a href="#" class="wp3i-cancel"><?php _e('Cancel','wp3i'); ?></a>
                </div>
            </li>
            <li class="date-detail">
            	<label><?php _e('Paid:','wp3i'); ?> </label>
                <div class="front">
                	<span><?php echo get_invoice_paid_pretty(); ?></span>
                	<a href="#" class="wp3i-edit"><?php _e('Edit','wp3i'); ?></a>
                </div>
                <div class="back">
                	<select name="mm" id="mm">
                    	<option></option>
                        <option value="01"><?php _e('Jan','wp3i'); ?></option>
                        <option value="02"><?php _e('Feb','wp3i'); ?></option>
                        <option value="03"><?php _e('Mar','wp3i'); ?></option>
                        <option value="04"><?php _e('Apr','wp3i'); ?></option>
                        <option value="05"><?php _e('May','wp3i'); ?></option>
                        <option value="06"><?php _e('Jun','wp3i'); ?></option>
                        <option value="07"><?php _e('Jul','wp3i'); ?></option>
                        <option value="08"><?php _e('Aug','wp3i'); ?></option>
                        <option value="09"><?php _e('Sep','wp3i'); ?></option>
                        <option value="10"><?php _e('Oct','wp3i'); ?></option>
                        <option value="11"><?php _e('Nov','wp3i'); ?></option>
                        <option value="12"><?php _e('Dec','wp3i'); ?></option>
            		</select>
                    <input type="text" maxlength="2" size="1" value="31" name="dd" id="dd" />, 
                    <input type="text" maxlength="4" size="3" value="2010" name="yyyy" id="yyyy" />
                	<input type="hidden" name="invoice-paid" id="invoice-paid" value="<?php echo get_invoice_paid(); ?>" />

                    <a href="#" class="button wp3i-ok"><?php _e('OK','wp3i'); ?></a>
                    <a href="#" class="wp3i-clear"><?php _e('Reset','wp3i'); ?></a>
                    <a href="#" class="wp3i-cancel"><?php _e('Cancel','wp3i'); ?></a>
                </div>
            </li>
		</ul>
        
        <input type="hidden" name="wp3i_hidden_currency" id="wp3i_hidden_currency"  value="<?php wp3i_currency_format(); ?>" />
        <input type="hidden" name="wp3i_hidden_tax" id="wp3i_hidden_tax"  value="<?php wp3i_tax(); ?>" />
        <input type="hidden" name="wp3i_hidden_permalink" id="wp3i_hidden_permalink"  value="<?php echo wp3i_get_permalink(); ?>" />
        <input type="hidden" name="wp3i_hidden_password" id="wp3i_hidden_password"  value="<?php echo get_invoice_client_password(); ?>" />
		<?php
		
		
	}
	
	
	/*--------------------------------------------------------------------------------------------
										Send Invoice
	--------------------------------------------------------------------------------------------*/
	function invoice_send() 
	{
		global $post;
		?>
		<?php if($_GET['sent'] == 'success'): ?>
        	<div class="updated">
            	<p><?php _e('Invoice sent successfully!','wp3i'); ?></p>
            </div>
        <?php elseif($_GET['sent'] == 'fail'): ?>
        	<div class="error">
            	<p><?php _e('Invoice failed to send.','wp3i'); ?></p>
            </div>
        <?php endif; ?>
        <ul>
        	<li>
            	<a href="<?php the_permalink(); ?>" class="button"><?php _e('View Invoice','wp3i'); ?></a> <?php _e('copy link, print as pdf, style invoice template','wp3i'); ?>
            </li>
            <!--<li>
            	<a href="<?php echo add_query_arg('do', 'pdf', get_permalink($post->ID)); ?>" class="button">Save as PDF</a> 
            </li>-->
            <li>
            	<a href="<?php echo add_query_arg('email', 'view', get_permalink($post->ID)); ?>" class="button"><?php _e('View Email','wp3i'); ?></a> <?php _e('check before sending, style email template','wp3i'); ?>
            </li>
            <li>
            	<?php if(get_invoice_client_name()): ?>
					<?php if(get_invoice_client_email()): ?>
                        <a href="<?php echo add_query_arg('email', 'send', get_permalink($post->ID)); ?>" class="button"><?php _e('Send Email','wp3i'); ?></a> <?php _e('to','wp3i'); ?> <?php invoice_client_email(); ?> <a href="<?php invoice_client_edit_link(); ?>"><?php _e('Edit Client','wp3i'); ?></a>  
                    <?php else: ?>
                        <a class="button disabled"><?php _e('Send Email','wp3i'); ?></a> <?php _e('no email address','wp3i'); ?> <a href="<?php invoice_client_edit_link(); ?>"><?php _e('Edit Client','wp3i'); ?></a> 
                    <?php endif; ?>
                <?php else: ?>
                    <a class="button disabled"><?php _e('Send Email','wp3i'); ?></a> <?php _e('no Client Selected','wp3i'); ?>
                <?php endif; ?>
            </li>
        </ul>

        
		<?php
	}
	
	
	
	/*--------------------------------------------------------------------------------------------
											Project Breakdown
	--------------------------------------------------------------------------------------------*/
	
	
	function project_breakdown() 
	{
		global $post;
		$detailCount = 0;
	
		
		$detailCount = count($detailTitle);
		
		// Use nonce for verification
  		echo '<input type="hidden" name="ei_noncename" id="ei_noncename" value="' .wp_create_nonce('ei-n'). '" />';
		?>
		
        <div class="detail detail-header">
        	<table cellpadding="0" cellspacing="0" width="100%">
            	<tr>
                	<td>
                    	<ul>
                        	<li class="title"><?php _e('Title','wp3i'); ?></li>
                            <li class="description"><?php _e('Description','wp3i'); ?></li>
                        </ul>
                    </td>
                    <td width="312">
                        <ul>
                        <li class="type"><?php _e('Type','wp3i'); ?></li>
                            <li class="rate"><?php _e('Rate','wp3i'); ?><span class="hr"></span></li>
                            <li class="duration"><?php _e('Time','wp3i'); ?></li>
                            <li class="subtotal"><?php _e('Subtotal','wp3i'); ?></li>
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
                        <li class="title"><input type="text" name="detail-title[]" id="detail-title" value="<?php the_detail_title(); ?>" /></li>
                        <li class="description"><textarea name="detail-description[]" id="detail-description"><?php echo get_the_detail_description(); ?></textarea>
                        </li>
                        </ul>
                    </td>
                    <td width="312">
                        <ul>
                       	<li class="type">
                        	<select name="detail-type[]" id="detail-type">
                            	<option value="<?php _e('Timed','wp3i'); ?>" <?php if(get_the_detail_type() == __('Timed','wp3i')){echo'selected="selected"';} ?>>Timed</option>
                            	<option value="<?php _e('Fixed','wp3i'); ?>" <?php if(get_the_detail_type() == __('Fixed','wp3i')){echo'selected="selected"';} ?>>Fixed</option>
                            </select>
                        </li>
                        <li class="rate">
							<input onBlur="if (this.value == '') {this.value = '0.00';}" onFocus="if(this.value == '0.00') {this.value = '';}"  type="text" name="detail-rate[]" id="detail-rate" value="<?php echo get_the_detail_rate(); ?>" />
                        </li>
                        <li class="duration">
                        	<input onBlur="if (this.value == '') {this.value = '0.0';}" onFocus="if(this.value == '0.0') {this.value = '';}"  type="text" name="detail-duration[]" id="detail-duration" value="<?php the_detail_duration(); ?>" />
                        </li>
                        <li class="subtotal">
                        	<input type="hidden" name="detail-subtotal[]" id="detail-subtotal" value="<?php the_detail_subtotal(); ?>" />
                            <p><?php echo wp3i_format_amount('<span id="detail-subtotal">'.get_the_detail_subtotal().'</span>'); ?></p>
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
                                	<li class="title"><input type="text" name="detail-title[]" id="detail-title" /></li>
                                	<li class="description"><textarea name="detail-description[]" id="detail-description"></textarea></li>
                                </ul>
                            </td>
                            <td width="312">
                            	<ul>
                                    <li class="type">
                                    <select name="detail-type[]" id="detail-type">
                                        <option value="Timed"><?php _e('Timed','wp3i'); ?></option>
                                        <option value="Fixed"><?php _e('Fixed','wp3i'); ?></option>
                                    </select>
                                    </li>
                                    <li class="rate">
							<input onBlur="if (this.value == '') {this.value = '0.00';}" onFocus="if(this.value == '0.00') {this.value = '';}"  type="text" name="detail-rate[]" id="detail-rate" value="0.00" />
                            		</li>
                                    <li class="duration">
                                    <input onBlur="if (this.value == '') {this.value = '0.0';}" onFocus="if(this.value == '0.0') {this.value = '';}"  type="text" name="detail-duration[]" id="detail-duration" value="0.0" />
                                    </li>
                                    <li class="subtotal">
                                    	<input type="hidden" name="detail-subtotal[]" id="detail-subtotal" value="0.00" />
                                        <p><?php echo wp3i_format_amount('<span id="detail-subtotal">0.00</span>'); ?></p>
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
        <strong><?php _e('Subtotal','wp3i'); ?>:</strong> <?php echo wp3i_format_amount('<span class="invoice-subtotal">'.get_the_invoice_subtotal().'</span>'); ?>	
        &nbsp;&nbsp;&nbsp;
        <?php //if(wp3i_has_tax()): ?>
        <strong><?php _e('Tax','wp3i'); ?>:</strong> <?php echo wp3i_format_amount('<span class="invoice-tax">'.get_the_invoice_tax().'</span>'); ?>
        &nbsp;&nbsp;&nbsp;
        <?php //endif; ?>
        <strong><?php _e('Total','wp3i'); ?>:</strong> <?php echo wp3i_format_amount('<span class="invoice-total">'.get_the_invoice_total().'</span>'); ?>
        &nbsp;&nbsp;&nbsp;
        <a class="add-detail button-primary" href="#" title="Add Detail"><?php _e('Add Detail','wp3i'); ?></a>
        </p>
        </div> 
		<?php
	}
	
	function create_meta_boxes() 
	{
		add_meta_box('invoice_details', 'Invoice Details', array($this, 'invoice_details'), 'invoice', 'normal', 'high');
		add_meta_box('project_breakdown', 'Project Breakdown', array($this, 'project_breakdown'), 'invoice', 'normal', 'low');
		add_meta_box('invoice_send', 'View, Print, Email', array($this, 'invoice_send'), 'invoice', 'normal', 'low');
	} 
	
	


	/*--------------------------------------------------------------------------------------------
										Save
	--------------------------------------------------------------------------------------------*/
	function save_invoice($post_id) {
		// verify this with nonce because save_post can be triggered at other times
		if (!wp_verify_nonce($_POST['ei_noncename'], 'ei-n')) return $post_id;
	
		// do not save if this is an auto save routine
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
		
		update_post_meta($post_id, 'invoice_number',$_POST['invoice-number']);
		update_post_meta($post_id, 'invoice_type',$_POST['invoice-type']);
		update_post_meta($post_id, 'invoice_tax',$_POST['invoice-tax']);
		update_post_meta($post_id, 'invoice_sent',$_POST['invoice-sent']);
		update_post_meta($post_id, 'invoice_paid',$_POST['invoice-paid']);
		//update_post_meta($post_id, 'invoice_client',$_POST['invoice-client']);
		
		update_post_meta($post_id, 'detail_title', serialize($_POST['detail-title']));
	
		$temp_description = serialize($_POST['detail-description']);
		$temp_description = addslashes($temp_description);
		
		update_post_meta($post_id, 'detail_description', $temp_description);
		update_post_meta($post_id, 'detail_type', serialize($_POST['detail-type']));
		update_post_meta($post_id, 'detail_rate', serialize($_POST['detail-rate']));
		update_post_meta($post_id, 'detail_duration', serialize($_POST['detail-duration']));
		update_post_meta($post_id, 'detail_subtotal', serialize($_POST['detail-subtotal']));
	}
	
	/**
	 * Invoice Template Redirect
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function invoice_template_redirect()
	{
		// define invoice url variables
		global $wp, $post;
		
		$post_type = get_query_var('post_type');
		$email = $_GET['email'];
		$paid = $_GET['paid'];
		
		if($post_type == 'invoice')
		{
			if($paid == 'true')
			{
				update_post_meta($post->ID, 'invoice_paid',date('d/m/Y'));
			}
			// 1. find invoice.php template file
			$invoice_template = get_stylesheet_directory().'/invoice/invoice.php';
			if(!file_exists($invoice_template)){$invoice_template = $this->plugin_path.'/template/invoice.php';}
			
			// 2. find email.php template file
			$email_template = get_stylesheet_directory().'/invoice/email.php';
			if(!file_exists($email_template)){$email_template = $this->plugin_path.'/template/email.php';}
			
			$this->invoice_security();
			if($email == 'send')
			{
				// get html email and store as variable for sending
				ob_start();
					include($email_template);
					$message = ob_get_contents();
				ob_end_clean();
				include($this->plugin_path.'/admin/email.php');
			}
			elseif($email == 'view')
			{
				include($email_template);
			}
			elseif(is_single())
			{
				include($invoice_template);
			}
			die();
		}
	}
	
	/**
	 * Invoice Security
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	 function invoice_security()
	 {
		if (post_password_required()) 
		{ 
            ?>
            	<style type="text/css" media="all">
					body{background:#F9F9F9;}
					.form {width:340px; margin:200px auto; padding:25px 20px; background-color:#FFF; -moz-border-radius: 10px; -webkit-border-radius: 10px;-khtml-border-radius: 10px; border-radius: 10px; border:1px solid #E5E5E5; box-shadow:0 4px 18px #C8C8C8; -moz-box-shadow:0 4px 18px #C8C8C8; -webkit-box-shadow:0 4px 18px #C8C8C8;}
					.form form {margin:0px; padding:0px;}
					.form img {float:left; position:relative; margin-top:-25px; margin-right:20px;}
					.form p {color:#666; font-size:14px; line-height:14px; margin:0px 0px 12px; padding:0px;}
					.form input[type=text], .form input[type=password] {font-size:20px; line-height:40px; height:40px; padding:0px 10px; -moz-border-radius: 6px; -webkit-border-radius: 6px;-khtml-border-radius: 6px; border-radius: 6px; border:3px solid #E5E5E5; font-family:Georgia, "Times New Roman", Times, serif; font-style:italic; margin:0px 0px 10px 0px; width:auto; }
					.form input[type=password]{}
					.form input[type=submit]{-moz-border-radius: 5px; -webkit-border-radius: 5px;-khtml-border-radius: 5px; border-radius: 5px;
background:url("<?php echo $this->plugin_dir; ?>admin/images/big_button_bg.png") repeat-x scroll center top #87B500;border-color:#DDDDDD #689300 #689300 #DDDDDD;border-style:solid;border-width:0 1px 1px 0;color:#FFFFFF;cursor:pointer;font-size:15px;height:30px;line-height:30px;margin:0;overflow:visible;padding:0 15px;text-shadow:1px 1px #719E03;}
				</style>
                <div class="form">
                	<img src="<?php echo $this->plugin_dir; ?>admin/images/password-protected.png" />
                    <form method="post" action="<?php bloginfo('url'); ?>/wp-pass.php">
                    <p><?php _e('This','wp3i'); ?> <?php invoice_type(); ?> <?php _e('is password protected','wp3i'); ?>.</p>
                    <input type="text" id="pwbox-531" name="post_password" value="<?php _e('Enter Password','wp3i'); ?>" onfocus="if(this.value == '<?php _e('Enter Password','wp3i'); ?>') {this.value = '';this.type='password'}" onblur="if (this.value == '') {this.value = '<?php _e('Enter Password','wp3i'); ?>'; this.type='text'}"/>
                    <input type="submit" value="Submit" name="Submit"/>
                    </form>
                </div>
            <?php
            die;
    	}
	 }
	 
	 /**
	 * Print Bar
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	 function task_bar()
	 {
		global $post;
		if($_GET['email'] == 'send')
		{
			return false;	
		}
		if(get_post_type($post->ID) == 'invoice'): ?>
        	<style type="text/css" media="all">
				.task_bar {position:fixed; bottom:0px; left:0px; width:100%; height:30px; background:url("<?php echo $this->plugin_dir; ?>admin/images/task-bar.png") repeat-x scroll left top #F2F2F2; font-size:11px; color:#999; text-shadow:#000 0px -1px 0px; overflow:hidden; border-top:#343434 solid 1px;
}
				.task_bar .container {width:600px; margin:0 auto; background:none transparent; border:none; padding:0px; overflow:hidden; height:30px; line-height:30px;}
				.task_bar p {color:#999; margin:0px; padding:0px; }
				.task_bar .status {float:left; color:#999;}
				.task_bar .status.paid {float:left; color:#95db30;}
				.task_bar .buttons {float:right;}
				.task_bar .buttons a {-moz-border-radius: 11px; -webkit-border-radius: 11px;-khtml-border-radius: 11px; border-radius: 11px; cursor:pointer; font-size:11px; padding:4px 8px 3px 8px; text-decoration:none; background:url("<?php bloginfo('url'); ?>/wp-admin/images/white-grad.png") repeat-x scroll left top #F2F2F2; text-shadow:0 1px 0 #FFFFFF; margin-left:5px; display:block; float:left; line-height:13px; margin-top:4px; color:#333;}
				.task_bar .print a:hover {background:#fff none;}
			</style>
            <style type="text/css" media="print">
				.task_bar {display:none; height:0px;}
			</style>
			<div class="task_bar">
            	<div class="container">
                <div class="status <?php if(get_invoice_status() == 'Paid'){_e('paid','wp3i'); } ?>">
					<?php if(get_invoice_type() == __('Invoice','wp3i')): ?>
                        <?php _e('Invoice status','wp3i'); ?>: <?php invoice_status(); ?>
                    <?php else: ?>
                        <?php _e('Invoice status: Quote','wp3i'); ?>	
                    <?php endif; ?>
                 </div>
            	
                <div class="buttons">
                	<?php edit_post_link('Edit '.get_invoice_type()); ?> 
                    <?php if(!$_GET['email']): //viewing online version ?>
                    	<?php if(is_user_logged_in()): ?>
                    		<a href="<?php echo add_query_arg('email', 'view', get_permalink($post->ID)); ?>"><?php _e('Email Version','wp3i'); ?></a>
                        <?php endif; ?>
                        <a href="javascript:print()"><?php _e('Print PDF','wp3i'); ?></a>
                        <?php if(get_invoice_type() == __('Invoice','wp3i')): ?>
                        	<?php $this->wp3i_payment_gateway_button(); ?>
                        <?php endif; ?>
                    <?php elseif($_GET['email'] == 'view'): //viewing email version?>
                    	<a href="<?php the_permalink(); ?>"><?php _e('Online Version','wp3i'); ?></a>
                        <?php //if(get_invoice_client_email()): // only send if there is a client email ?>
                        	<a href="<?php echo add_query_arg('email', 'send', get_permalink($post->ID)); ?>"><?php _e('Send Email','wp3i'); ?></a>
                        <?php //endif; ?>
                    <?php endif; ?>
                	
                </div>
             
                </div>
            </div>
		<?php endif;
	 }
	
	/**
	 * wp3i_payment_gateway_button
	 *
	 * @author Elliot Condon
	 * @since 2.0.1
	 *
	 * Creates the chosen payment gateway button
	 **/
	function wp3i_payment_gateway_button()
	{
		$payment_gateway_name = wp3i_get_payment_gateway();
		$payment_gateway_account = wp3i_get_payment_gateway_account();
		
		if(get_invoice_status() == 'Paid'){return false;}
		if($payment_gateway_name == 'None'){return false;}
		if($payment_gateway_account == ''){return false;}
	
		include $this->plugin_path.'gateways/'.$payment_gateway_name.'.php';
		$gateway = new $payment_gateway_name($this);
		
		
	}
}

?>