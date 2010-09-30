<?php
	
	$the_detail = NULL;
	$detailCount=NULL;
	
	$detailTitle = NULL;
	$detailDescription = NULL;
	$detailType = NULL;
	$detailRate = NULL;
	$detailDuration = NULL;
	$detailSubtotal = NULL;
	
	
	
	
	/*--------------------------------------------------------------------------------------------
										Invoice_has_details
										
	* This function is called before the detail loop. 
	* Populates the detail's data array's
	* Checks that there are details
	* Then it returns either true or false.
	--------------------------------------------------------------------------------------------*/
	function invoice_has_details()
	{
		global $post, $detailCount, $detailTitle, $detailDescription, $detailType, $detailRate, $detailDuration, $detailSubtotal;
		$detailCount=0;
		
		$detailTitle = unserialize(get_post_meta($post->ID, 'detail_title', true));
		//$safe_description = get_post_meta($post->ID, 'detail_description', true);
		//echo $safe_description.'<br>';
		//$safe_description = unserialize($safe_description);
		//echo $safe_description.'<br>';
		//$detailDescription = html_entity_decode(get_post_meta($post->ID, 'detail_description', true));
		//echo $detailDescription;
		$detailDescription = get_post_meta($post->ID, 'detail_description', true);
		$detailDescription = unserialize($detailDescription);
		//echo 'string = '.$detailDescription;
		//$detailDescription = stripslashes($detailDescription);
		//echo $detailDescription;
		$detailType = unserialize(get_post_meta($post->ID, 'detail_type', true));
		$detailRate = unserialize(get_post_meta($post->ID, 'detail_rate', true));
		$detailDuration = unserialize(get_post_meta($post->ID, 'detail_duration', true));
		$detailSubtotal = unserialize(get_post_meta($post->ID, 'detail_subtotal', true));
		
		if($detailTitle[0])
		{
			return true;	
		}
		else
		{
			return false;	
		}
	}
	
	
	/*--------------------------------------------------------------------------------------------
										 Invoice_detail
										
	* This function is called at the start of the detail loop. 
	* It sets up the detail data and returns either true or false.
	--------------------------------------------------------------------------------------------*/
	function invoice_detail()
	{
		global $the_detail, $detailCount, $detailTitle, $detailDescription, $detailType, $detailRate, $detailDuration, $detailSubtotal;
		if($detailTitle[$detailCount] != '')
		{
			$the_detail = array(
				$detailTitle[$detailCount],
				$detailDescription[$detailCount],
				$detailType[$detailCount],
				$detailRate[$detailCount],
				$detailDuration[$detailCount],
				$detailSubtotal[$detailCount]
			);
			
			$detailCount++;
			return true;
		}
		else
		{
			return false;	
		}
	}
	
	
	/*--------------------------------------------------------------------------------------------
										 the_detail_title
	--------------------------------------------------------------------------------------------*/
	function get_the_detail_title()
	{
		global $the_detail;
		return $the_detail[0];
	}
	
	function the_detail_title()
	{
		echo get_the_detail_title();
	}
	
	
	/*--------------------------------------------------------------------------------------------
										the_detail_description
	--------------------------------------------------------------------------------------------*/
	function get_the_detail_description()
	{
		global $the_detail;
		return stripslashes($the_detail[1]);
	}
	function the_detail_description()
	{
		echo nl2br(get_the_detail_description());
	}
	
	
	/*--------------------------------------------------------------------------------------------
										the_detail_type
	--------------------------------------------------------------------------------------------*/
	function get_the_detail_type()
	{
		global $the_detail;
		return $the_detail[2];
	}
	
	function the_detail_type()
	{
		echo get_the_detail_type();
	}
	
	
	/*--------------------------------------------------------------------------------------------
										the_detail_rate
	--------------------------------------------------------------------------------------------*/
	function get_the_detail_rate()
	{
		global $the_detail;
		return $the_detail[3];
	}
	function the_detail_rate()
	{
		echo get_the_detail_rate();
	}
	
	
	/*--------------------------------------------------------------------------------------------
										the_detail_duration
	--------------------------------------------------------------------------------------------*/
	function get_the_detail_duration()
	{
		global $the_detail;
		return $the_detail[4];
	}
	function the_detail_duration()
	{
		echo get_the_detail_duration();
	}
	
	
	/*--------------------------------------------------------------------------------------------
										the_detail_subtotal
	--------------------------------------------------------------------------------------------*/
	function get_the_detail_subtotal()
	{
		global $the_detail;
		return number_format($the_detail[5], 2, '.', ''); 
	}
	function the_detail_subtotal()
	{
		echo get_the_detail_subtotal();
	}
	


	/*--------------------------------------------------------------------------------------------
										invoice_template_url
	--------------------------------------------------------------------------------------------*/
	function get_invoice_template_url()
	{
		global $invoice_template_url;
		if(file_exists(STYLESHEETPATH . '/invoice/invoice.php'))
		{
			return bloginfo('template_url') . '/invoice';
		}
		else
		{
			return $invoice_template_url;
		}
	}
	
	function invoice_template_url()
	{
		echo get_invoice_template_url();
	}





	/*--------------------------------------------------------------------------------------------
										invoice_number		
	--------------------------------------------------------------------------------------------*/
	function get_invoice_number() 
	{
		global $post;
		return get_post_meta($post->ID, 'invoice_number', true)? get_post_meta($post->ID, 'invoice_number', true): get_next_invoice_number();	
	}
	
	function invoice_number() 
	{
		echo get_invoice_number();
	}
	
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
	
	
	
	
	/*--------------------------------------------------------------------------------------------
										get_invoice_type
	--------------------------------------------------------------------------------------------*/
	function invoice_type()
	{
		echo get_invoice_type();	
	}
	
	function get_invoice_type()
	{
		global $post;
		return get_post_meta($post->ID, 'invoice_type', true)? get_post_meta($post->ID, 'invoice_type', true): 'Invoice';
	}
	
	
	
	/*--------------------------------------------------------------------------------------------
										get_invoice_sent
	--------------------------------------------------------------------------------------------*/
	function get_invoice_sent()
	{
		global $post;
		return get_post_meta($post->ID, 'invoice_sent', true)? get_post_meta($post->ID, 'invoice_sent', true): 'Not yet';
	}
	
	function get_invoice_sent_pretty()
	{
		$sent = get_invoice_sent();
		$months = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		if($sent == 'Not yet')
		{
			return $sent;	
		}
		else
		{
			$sent = explode('/',$sent);
			return $months[intval($sent[1])] . ' ' . $sent[0] . ', ' . $sent[2];
		}
	}
	
	function invoice_sent()
	{
		echo get_invoice_sent();
	}
	
	function invoice_has_sent($postID)
	{
		$invoice_sent = get_post_meta($postID, 'invoice_sent', true)? get_post_meta($postID, 'invoice_sent', true): 'Not yet';
		if($invoice_sent != 'Not yet')
		{
			return true;	
		}
		else
		{
			return false;	
		}
	}
	
	
	/*--------------------------------------------------------------------------------------------
										get_invoice_paid
	--------------------------------------------------------------------------------------------*/
	function get_invoice_paid()
	{
		global $post;
		return get_post_meta($post->ID, 'invoice_paid', true)? get_post_meta($post->ID, 'invoice_paid', true): 'Not yet';
	}
	
	function get_invoice_paid_pretty()
	{
		$sent = get_invoice_paid();
		$months = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		if($sent == 'Not yet')
		{
			return $sent;	
		}
		else 
		{
			$sent = explode('/',$sent);
			return $months[intval($sent[1])] . ' ' . $sent[0] . ', ' . $sent[2];
		}
	}
	
	function invoice_has_paid($postID)
	{
		$invoice_paid = get_post_meta($postID, 'invoice_paid', true)? get_post_meta($postID, 'invoice_paid', true): 'Not yet';
		if($invoice_paid != 'Not yet')
		{
			return true;	
		}
		else
		{
			return false;	
		}
	}
	
	/*--------------------------------------------------------------------------------------------
										get_invoice_status
	--------------------------------------------------------------------------------------------*/
	function get_invoice_status() 
	{
		global $post;
		return get_post_meta($post->ID, 'invoice_status', true)? get_post_meta($post->ID, 'invoice_status', true): 'Quote';
	}
	
	
	
	
	
	/*--------------------------------------------------------------------------------------------
											Currency		
	--------------------------------------------------------------------------------------------*/
	function wp3i_currency()
	{
		echo get_wp3i_currency();
	}
	
	function get_wp3i_currency()
	{
		$wp3i_currency =  get_option('wp3i_currency');	
		if($wp3i_currency)
		{
			return $wp3i_currency;
		}
		else
		{
			return '$';	
		}
	}
	
	
	/*--------------------------------------------------------------------------------------------
												Tax	
	--------------------------------------------------------------------------------------------*/
	function wp3i_tax()
	{
		echo get_wp3i_tax();
	}
	
	function get_wp3i_tax()
	{
		global $post;
		if(get_post_meta($post->ID, 'invoice_tax', true))
		{
			return get_post_meta($post->ID, 'invoice_tax', true);
		}
		elseif(get_option('wp3i_tax'))
		{
			return get_option('wp3i_tax');
		}
		else
		{
			return '0.00';	
		}
	}
	
	function wp3i_has_tax()
	{
		if(get_wp3i_tax() == '0.00')
		{
			return false;	
		}
		else
		{
			return true;	
		}
	}
	
	
	
	/*--------------------------------------------------------------------------------------------
											Email Recipients
	--------------------------------------------------------------------------------------------*/
	function wp3i_get_emailrecipients()
	{
		$wp3i_emailrecipients = get_option('wp3i_emailrecipients');	
		if($wp3i_emailrecipients)
		{
			return $wp3i_emailrecipients;
		}
		else
		{
			return 'client';	
		}
	}
	
	
	/*--------------------------------------------------------------------------------------------
											Invoice Permalinks
	--------------------------------------------------------------------------------------------*/
	function wp3i_get_permalink()
	{
		$wp3i_permalink = get_option('wp3i_permalink');	
		if($wp3i_permalink)
		{
			return $wp3i_permalink;
		}
		else
		{
			return 'encoded';	
		}
	}
	
	
	/*--------------------------------------------------------------------------------------------
										Invoice Content Editor
	--------------------------------------------------------------------------------------------*/
	function wp3i_get_content_editor()
	{
		$wp3i_content_editor = get_option('wp3i_content_editor');	
		if($wp3i_content_editor)
		{
			return $wp3i_content_editor;
		}
		else
		{
			return 'disabled';	
		}
	}
	
	
	/*--------------------------------------------------------------------------------------------
										the_invoice_total
	--------------------------------------------------------------------------------------------*/
	function get_the_invoice_subtotal()
	{
		global $post;
		return wp3i_get_invoice_subtotal($post->ID);
	}
	function the_invoice_subtotal()
	{
		global $post;
		echo wp3i_get_invoice_subtotal($post->ID);
	}
	
	function get_the_invoice_tax()
	{
		global $post;
		return wp3i_get_invoice_tax($post->ID);
	}
	function the_invoice_tax()
	{
		global $post;
		echo wp3i_get_invoice_tax($post->ID);
	}
	
	function get_the_invoice_total()
	{
		global $post;
		return wp3i_get_invoice_total($post->ID);
	}
	function the_invoice_total()
	{
		global $post;
		echo wp3i_get_invoice_total($post->ID);
	}
	
	
	
	/*--------------------------------------------------------------------------------------------
												Invoice Subtotal
	--------------------------------------------------------------------------------------------*/
	function wp3i_get_invoice_subtotal($invoiceID)
	{
		$total = 0.00;
		$detailSubtotal = unserialize(get_post_meta($invoiceID, 'detail_subtotal', true));
		if($detailSubtotal)
		{
			foreach($detailSubtotal as $subtotal)
			{
				$total += floatval($subtotal);
			}
		}
		return number_format($total, 2, '.', '');
	}
	
	
	/*--------------------------------------------------------------------------------------------
												Invoice Tax
	--------------------------------------------------------------------------------------------*/
	function wp3i_get_invoice_tax($invoiceID)
	{
		$total = floatval(wp3i_get_invoice_subtotal($invoiceID) * get_wp3i_tax());
		return number_format($total, 2, '.', ''); 
	}
	
	
	/*--------------------------------------------------------------------------------------------
												Invoice Total
	--------------------------------------------------------------------------------------------*/
	function wp3i_get_invoice_total($invoiceID)
	{
		$total = floatval(wp3i_get_invoice_subtotal($invoiceID) + wp3i_get_invoice_tax($invoiceID));
		return number_format($total, 2, '.', ''); 
	}
	
	
?>