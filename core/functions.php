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
		$detailDescription = get_post_meta($post->ID, 'detail_description', true);
		$detailDescription = unserialize($detailDescription);
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
		echo wp3i_format_amount(get_the_detail_rate());
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
		echo wp3i_format_amount(get_the_detail_subtotal());
	}
	


	/*--------------------------------------------------------------------------------------------
										invoice_template_url
	--------------------------------------------------------------------------------------------*/
	function get_invoice_template_url()
	{
		if(file_exists(get_stylesheet_directory().'/invoice/invoice.php'))
		{
			return get_bloginfo('stylesheet_directory').'/invoice';
			
		}
		else
		{
			global $Wp3i;
			return $Wp3i->dir.'template';
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
	function invoice_type($postID = NULL)
	{
		if(!$postID){global $post; $postID = $post->ID;}
		echo get_invoice_type($postID);
	}
	
	function get_invoice_type($postID = NULL)
	{
		if(!$postID){global $post; $postID = $post->ID;}
		return get_post_meta($postID, 'invoice_type', true)? get_post_meta($postID, 'invoice_type', true): __('Invoice','wp3i');
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
		$months = array('',__('Jan','wp3i'), __('Feb','wp3i'), __('Mar','wp3i'), __('Apr','wp3i'), __('May','wp3i'), __('Jun','wp3i'), __('Jul','wp3i'), __('Aug','wp3i'), __('Sep','wp3i'), __('Oct','wp3i'), __('Nov','wp3i'), __('Dec','wp3i'));
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
		$months = array('',__('Jan','wp3i'), __('Feb','wp3i'), __('Mar','wp3i'), __('Apr','wp3i'), __('May','wp3i'), __('Jun','wp3i'), __('Jul','wp3i'), __('Aug','wp3i'), __('Sep','wp3i'), __('Oct','wp3i'), __('Nov','wp3i'), __('Dec','wp3i'));
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
	function get_invoice_status($postID = NULL) 
	{
		if(!$postID){global $post; $postID = $post->ID;}
		$invoice_paid = get_post_meta($postID, 'invoice_paid', true);
		$invoice_sent = get_post_meta($postID, 'invoice_sent', true);
		if($invoice_paid && $invoice_paid != 'Not yet')
		{
			return __('Paid','wp3i');
		}
		elseif($invoice_sent && $invoice_sent != 'Not yet')
		{
			$invoice_sent = explode('/',$invoice_sent);
			$invoice_sent = intval($invoice_sent[2]).'-'.intval($invoice_sent[1]).'-'.intval($invoice_sent[0]);
	
			$days = wp3i_date_diff($invoice_sent, date_i18n('Y-m-d'));
			if($days == 0){return __('Sent today','wp3i');}
			elseif($days == 1){return __('Sent 1 day ago','wp3i');}
			else{ return __('Sent ','wp3i').$days.__(' days ago','wp3i');} 
		}
		else
		{
			return __('Not sent yet','wp3i');
		}
	}
	function invoice_status() 
	{
		echo get_invoice_status();	
	}
	
	function wp3i_date_diff($start, $end) 
	{
		$start_ts = strtotime($start);
		$end_ts = strtotime($end);
		$diff = $end_ts - $start_ts;
		return round($diff / 86400);
	}
	
	
	
	
	
	/*--------------------------------------------------------------------------------------------
											Currency		
	--------------------------------------------------------------------------------------------*/
	function wp3i_currency()
	{
		echo wp3i_get_currency();
	}
	
	function wp3i_get_currency()
	{
		$wp3i_currency =  get_option('wp3i_currency');	
		if($wp3i_currency)
		{
			return $wp3i_currency;
		}
		else
		{
			return 'AU'; // australia is default	
		}
	}
	
	function wp3i_currency_code()
	{
		echo wp3i_get_currency_code();
	}
	
	function wp3i_get_currency_code()
	{
		$countries = wp3i_get_countries();
		return $countries[wp3i_get_currency()]['currency']['code'];
	}
	
	function wp3i_currency_format()
	{
		echo wp3i_get_currency_format();
	}
	
	function wp3i_get_currency_format()
	{
		$countries = wp3i_get_countries();
		return $countries[wp3i_get_currency()]['currency']['format'];
	}
	
	function wp3i_format_amount($amount)
	{
		return str_replace('#',$amount,wp3i_get_currency_format());
	}
	
	function wp3i_get_countries() 
	{
		$countries = array();
		$countries['CA'] = array('name'=>'Canada','currency'=>array('code'=>'CAD','format'=>'$#')); 
		$countries['US'] = array('name'=>'USA','currency'=>array('code'=>'USD','format'=>'$#')); 
		$countries['GB'] = array('name'=>'United Kingdom','currency'=>array('code'=>'GBP','format'=>'£#')); 
		$countries['DZ'] = array('name'=>'Algeria','currency'=>array('code'=>'DZD','format'=>'# د.ج')); 
		$countries['AR'] = array('name'=>'Argentina','currency'=>array('code'=>'ARS','format'=>'$#'));
		$countries['AW'] = array('name'=>'Aruba','currency'=>array('code'=>'AWG','format'=>'ƒ#'));
		$countries['AU'] = array('name'=>'Australia','currency'=>array('code'=>'AUD','format'=>'$#'));
		$countries['AT'] = array('name'=>'Austria','currency'=>array('code'=>'EUR','format'=>'€#'));
		$countries['BB'] = array('name'=>'Barbados','currency'=>array('code'=>'BBD','format'=>'$#'));
		$countries['BS'] = array('name'=>'Bahamas','currency'=>array('code'=>'BSD','format'=>'$#'));
		$countries['BH'] = array('name'=>'Bahrain','currency'=>array('code'=>'BHD','format'=>'ب.د #'));
		$countries['BE'] = array('name'=>'Belgium','currency'=>array('code'=>'EUR','format'=>'# €'));
		$countries['BR'] = array('name'=>'Brazil','currency'=>array('code'=>'BRL','format'=>'R$#'));
		$countries['BG'] = array('name'=>'Bulgaria','currency'=>array('code'=>'BGN','format'=>'# лв.'));
		$countries['CL'] = array('name'=>'Chile','currency'=>array('code'=>'CLP','format'=>'$#'));
		$countries['CN'] = array('name'=>'China','currency'=>array('code'=>'CNY','format'=>'¥#'));
		$countries['CO'] = array('name'=>'Colombia','currency'=>array('code'=>'COP','format'=>'$#'));
		$countries['CR'] = array('name'=>'Costa Rica','currency'=>array('code'=>'CRC','format'=>'₡#'));
		$countries['HR'] = array('name'=>'Croatia','currency'=>array('code'=>'HRK','format'=>'# kn'));
		$countries['CY'] = array('name'=>'Cyprus','currency'=>array('code'=>'CYP','format'=>'£#'));
		$countries['CZ'] = array('name'=>'Czech Republic','currency'=>array('code'=>'CZK','format'=>'# Kč'));
		$countries['DK'] = array('name'=>'Denmark','currency'=>array('code'=>'DKK','format'=>'# kr')); 
		$countries['DO'] = array('name'=>'Dominican Republic','currency'=>array('code'=>'DOP','format'=>'$#')); 
		$countries['EC'] = array('name'=>'Ecuador','currency'=>array('code'=>'ESC','format'=>'$#')); 
		$countries['EG'] = array('name'=>'Egypt','currency'=>array('code'=>'EGP','format'=>'£#'));
		$countries['EE'] = array('name'=>'Estonia','currency'=>array('code'=>'EEK','format'=>'# EEK'));
		$countries['FI'] = array('name'=>'Finland','currency'=>array('code'=>'EUR','format'=>'€#'));
		$countries['FR'] = array('name'=>'France','currency'=>array('code'=>'EUR','format'=>'€#'));
		$countries['DE'] = array('name'=>'Germany','currency'=>array('code'=>'EUR','format'=>'€#')); 
		$countries['GR'] = array('name'=>'Greece','currency'=>array('code'=>'EUR','format'=>'€#')); 
		$countries['GP'] = array('name'=>'Guadeloupe','currency'=>array('code'=>'EUR','format'=>'€#')); 
		$countries['GT'] = array('name'=>'Guatemala','currency'=>array('code'=>'GTQ','format'=>'Q#')); 
		$countries['HK'] = array('name'=>'Hong Kong','currency'=>array('code'=>'HKD','format'=>'$#')); 
		$countries['HU'] = array('name'=>'Hungary','currency'=>array('code'=>'HUF','format'=>'# Ft')); 
		$countries['IS'] = array('name'=>'Iceland','currency'=>array('code'=>'ISK','format'=>'# kr.')); 
		$countries['IN'] = array('name'=>'India','currency'=>array('code'=>'INR','format'=>'₨#')); 
		$countries['ID'] = array('name'=>'Indonesia','currency'=>array('code'=>'IDR','format'=>'Rp #')); 
		$countries['IE'] = array('name'=>'Ireland','currency'=>array('code'=>'EUR','format'=>'€#')); 
		$countries['IL'] = array('name'=>'Israel','currency'=>array('code'=>'ILS','format'=>'₪ #')); 
		$countries['IT'] = array('name'=>'Italy','currency'=>array('code'=>'EUR','format'=>'€#')); 
		$countries['JM'] = array('name'=>'Jamaica','currency'=>array('code'=>'JMD','format'=>'$#')); 
		$countries['JP'] = array('name'=>'Japan','currency'=>array('code'=>'JPY','format'=>'¥#')); 
		$countries['LV'] = array('name'=>'Latvia','currency'=>array('code'=>'LVL','format'=>'# Ls')); 
		$countries['LT'] = array('name'=>'Lithuania','currency'=>array('code'=>'LTL','format'=>'# Lt')); 
		$countries['LU'] = array('name'=>'Luxembourg','currency'=>array('code'=>'EUR','format'=>'€#')); 
		$countries['MY'] = array('name'=>'Malaysia','currency'=>array('code'=>'MYR','format'=>'RM#')); 
		$countries['MT'] = array('name'=>'Malta','currency'=>array('code'=>'MTL','format'=>'€#')); 
		$countries['MX'] = array('name'=>'Mexico','currency'=>array('code'=>'MXN','format'=>'$#')); 
		$countries['NL'] = array('name'=>'Netherlands','currency'=>array('code'=>'EUR','format'=>'€#')); 
		$countries['NZ'] = array('name'=>'New Zealand','currency'=>array('code'=>'NZD','format'=>'$#')); 
		$countries['NG'] = array('name'=>'Nigeria','currency'=>array('code'=>'NGN','format'=>'₦#'));
		$countries['NO'] = array('name'=>'Norway','currency'=>array('code'=>'NOK','format'=>'kr #')); 
		$countries['PK'] = array('name'=>'Pakistan','currency'=>array('code'=>'PKR','format'=>'₨#')); 
		$countries['PE'] = array('name'=>'Peru','currency'=>array('code'=>'PEN','format'=>'S/. #')); 
		$countries['PH'] = array('name'=>'Philippines','currency'=>array('code'=>'PHP','format'=>'Php #')); 
		$countries['PL'] = array('name'=>'Poland','currency'=>array('code'=>'PLZ','format'=>'# zł')); 
		$countries['PT'] = array('name'=>'Portugal','currency'=>array('code'=>'EUR','format'=>'€#')); 
		$countries['PR'] = array('name'=>'Puerto Rico','currency'=>array('code'=>'USD','format'=>'$#')); 
		$countries['RO'] = array('name'=>'Romania','currency'=>array('code'=>'ROL','format'=>'# lei'));
		$countries['RU'] = array('name'=>'Russia','currency'=>array('code'=>'RUB','format'=>'# руб')); 
		$countries['SG'] = array('name'=>'Singapore','currency'=>array('code'=>'SGD','format'=>'$#')); 
		$countries['SK'] = array('name'=>'Slovakia','currency'=>array('code'=>'EUR','format'=>'€#')); 
		$countries['SI'] = array('name'=>'Slovenia','currency'=>array('code'=>'EUR','format'=>'€#')); 
		$countries['ZA'] = array('name'=>'South Africa','currency'=>array('code'=>'ZAR','format'=>'R#')); 
		$countries['KR'] = array('name'=>'South Korea','currency'=>array('code'=>'KRW','format'=>'₩#')); 
		$countries['ES'] = array('name'=>'Spain','currency'=>array('code'=>'EUR','format'=>'€#')); 
		$countries['VC'] = array('name'=>'St. Vincent','currency'=>array('code'=>'XCD','format'=>'$#')); 
		$countries['SE'] = array('name'=>'Sweden','currency'=>array('code'=>'SEK','format'=>'# kr')); 
		$countries['CH'] = array('name'=>'Switzerland','currency'=>array('code'=>'CHF','format'=>"# CHF")); 
		$countries['TW'] = array('name'=>'Taiwan','currency'=>array('code'=>'TWD','format'=>'NT$#')); 
		$countries['TH'] = array('name'=>'Thailand','currency'=>array('code'=>'THB','format'=>'#฿')); 
		$countries['TT'] = array('name'=>'Trinidad and Tobago','currency'=>array('code'=>'TTD','format'=>'TT$#')); 
		$countries['TR'] = array('name'=>'Turkey','currency'=>array('code'=>'TRL','format'=>'# TL')); 
		$countries['UA'] = array('name'=>'Ukraine','currency'=>array('code'=>'UAH','format'=>'# ₴')); 
		$countries['AE'] = array('name'=>'United Arab Emirates','currency'=>array('code'=>'AED','format'=>'Dhs. #')); 
		$countries['UY'] = array('name'=>'Uruguay','currency'=>array('code'=>'UYP','format'=>'$#')); 
		$countries['VE'] = array('name'=>'Venezuela','currency'=>array('code'=>'VUB','format'=>'Bs. #')); 
		return apply_filters('shopp_countries',$countries);
	}
	
	
	/*--------------------------------------------------------------------------------------------
												Tax	
	--------------------------------------------------------------------------------------------*/
	function wp3i_tax()
	{
		global $post;
		echo get_wp3i_tax($post->ID);
	}
	
	function get_wp3i_tax($invoiceID = NULL)
	{
		if(get_post_meta($invoiceID, 'invoice_tax', true))
		{
			return get_post_meta($invoiceID, 'invoice_tax', true);
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
		global $post;
		if(get_wp3i_tax($post->ID) == '0.00')
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
										Invoice Email
	--------------------------------------------------------------------------------------------*/
	function get_wp3i_email()
	{
		$wp3i_email = get_option('wp3i_email');	
		$current_user = wp_get_current_user();
		if($wp3i_email)
		{
			return $wp3i_email;
		}
		elseif($current_user)
		{
			return $current_user->user_email;
		}
		else
		{
			return '';	
		}
	}
	
	function wp3i_email()
	{
		echo get_wp3i_email();
	}
	
	/**
	 * wp3i_payment_gateway
	 *
	 * @author Elliot Condon
	 * @since 2.0.1
	 *
	 * Returns an array of files found in the gateway folder
	 **/
	function wp3i_get_payment_gateway()
	{
		$wp3i_payment_gateway = get_option('wp3i_payment_gateway');	
		if($wp3i_payment_gateway)
		{
			return $wp3i_payment_gateway;
		}
		else
		{
			return 'None';	
		}
	}
	
	function wp3i_payment_gateway()
	{
		echo wp3i_get_payment_gateway();
	}
	
	/**
	 * wp3i_payment_gateway_account
	 *
	 * @author Elliot Condon
	 * @since 2.0.1
	 *
	 **/
	 function wp3i_get_payment_gateway_account()
	{
		$wp3i_payment_gateway_account = get_option('wp3i_payment_gateway_account');	
		if($wp3i_payment_gateway_account)
		{
			return $wp3i_payment_gateway_account;
		}
		else
		{
			return '';	
		}
	}
	function wp3i_payment_gateway_account()
	{
		echo wp3i_get_payment_gateway_account();
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
		echo wp3i_format_amount(wp3i_get_invoice_subtotal($post->ID));
	}
	
	function get_the_invoice_tax()
	{
		global $post;
		return wp3i_get_invoice_tax($post->ID);
	}
	function the_invoice_tax()
	{
		global $post;
		echo wp3i_format_amount(wp3i_get_invoice_tax($post->ID));
	}
	
	function get_the_invoice_total()
	{
		global $post;
		return wp3i_get_invoice_total($post->ID);
	}
	function the_invoice_total()
	{
		global $post;
		echo wp3i_format_amount(wp3i_get_invoice_total($post->ID));
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
		$total = floatval(wp3i_get_invoice_subtotal($invoiceID) * get_wp3i_tax($invoiceID));
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
		global $Wp3i;
		$Wp3i->invoice->wp3i_payment_gateway_button();
	}
	
?>