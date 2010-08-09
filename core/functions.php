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
		$detailDescription = unserialize(get_post_meta($post->ID, 'detail_description', true));
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
	function the_detail_title()
	{
		global $the_detail;
		echo $the_detail[0];
	}
	
	
	/*--------------------------------------------------------------------------------------------
										the_detail_description
	--------------------------------------------------------------------------------------------*/
	function the_detail_description()
	{
		global $the_detail;
		echo nl2br($the_detail[1]);
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
		global $the_detail;
		echo $the_detail[2];
	}
	
	
	/*--------------------------------------------------------------------------------------------
										the_detail_rate
	--------------------------------------------------------------------------------------------*/
	function the_detail_rate()
	{
		global $the_detail;
		echo $the_detail[3];
	}
	
	
	/*--------------------------------------------------------------------------------------------
										the_detail_duration
	--------------------------------------------------------------------------------------------*/
	function the_detail_duration()
	{
		global $the_detail;
		echo $the_detail[4];
	}
	
	
	/*--------------------------------------------------------------------------------------------
										the_detail_subtotal
	--------------------------------------------------------------------------------------------*/
	function the_detail_subtotal()
	{
		global $the_detail;
		echo number_format($the_detail[5], 2, '.', ''); 
	}
	


	/*--------------------------------------------------------------------------------------------
										invoice_template_url
	--------------------------------------------------------------------------------------------*/
	function invoice_template_url()
	{
		global $invoice_template_url;
		if(file_exists(TEMPLATEPATH . '/invoice/invoice.php'))
		{
			echo bloginfo('template_url') . '/invoice';
		}
		else
		{
			echo $invoice_template_url;
		}
	}





	/*--------------------------------------------------------------------------------------------
										invoice_number		
	--------------------------------------------------------------------------------------------*/
	function invoice_number() 
	{
		global $post;
		echo get_post_meta($post->ID, 'invoice_number', true)? get_post_meta($post->ID, 'invoice_number', true): get_next_invoice_number();	
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
										get_invoice_status
	--------------------------------------------------------------------------------------------*/
	function get_invoice_status() 
	{
		global $post;
		return get_post_meta($post->ID, 'invoice_status', true)? get_post_meta($post->ID, 'invoice_status', true): 'Quote';
	}
	
	
	/*--------------------------------------------------------------------------------------------
										invoice_client		
	--------------------------------------------------------------------------------------------*/
	function invoice_client()
	{
		global $post;
		$results = array();
		
		$terms = get_the_terms($post->ID , 'client');
		if($terms)
		{	
			foreach( $terms as $term ) 
			{
				array_push($results	,$term->name);
			}
		}
		
		echo implode(',',$results);
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
		$wp3i_currency =  get_option('wp3i_tax');	
		if($wp3i_currency)
		{
			return $wp3i_currency;
		}
		else
		{
			return '0.00';	
		}
	}
	
	
	
	
	/*--------------------------------------------------------------------------------------------
										the_invoice_total
	--------------------------------------------------------------------------------------------*/
	function the_invoice_subtotal()
	{
		global $post;
		echo wp3i_get_invoice_subtotal($post->ID);
	}
	
	function the_invoice_tax()
	{
		global $post;
		echo wp3i_get_invoice_tax($post->ID);
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