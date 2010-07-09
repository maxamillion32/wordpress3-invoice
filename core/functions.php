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
		
		if(count($detailTitle) > 0)
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
		echo $the_detail[5];
	}
	
	
	/*--------------------------------------------------------------------------------------------
										the_detail_subtotal
	--------------------------------------------------------------------------------------------*/
	function the_invoice_total()
	{
		$total = 0.00;
		global $detailSubtotal;
		foreach($detailSubtotal as $subtotal)
		{
			$total += floatval($subtotal);
		}
		echo number_format($total, 2, '.', '');
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
		echo get_post_meta($post->ID, 'invoice_number', true);	
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
										invoice_client		
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
	
?>