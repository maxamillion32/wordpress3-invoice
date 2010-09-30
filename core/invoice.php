<?php

function invoice_init()
{
	global $invoice_plugin_url;
	
	// flush and refresh permalinks
	global $wp_rewrite;
    $wp_rewrite->flush_rules();
	/* 
	
	
	Regist Invoice Post Type
	--------------------------------------------------------------------------------------------*/
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
	$supports_array = array('title'/*, 'editor','custom-fields'*/);
	if(wp3i_get_content_editor() == 'enabled')
	{
		$supports_array = array('title','editor'/*,'custom-fields'*/);
	}
	register_post_type('invoice', array(
		'labels' => $labels,
		'menu_icon' => $invoice_plugin_url.'/admin/images/invoice-icon.gif',
		'public' => true,
		'show_ui' => true,
		'_builtin' =>  false,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array("slug" => "invoice"), // Permalinks format
		'query_var' => "invoice",
		'supports' => $supports_array,
	));
	/* 
	
	
	Invoice Columns
	--------------------------------------------------------------------------------------------*/
	function wp3i_columns($columns)
	{
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
	
	function wp3i_date_diff($start, $end) 
	{
		$start_ts = strtotime($start);
		$end_ts = strtotime($end);
		$diff = $end_ts - $start_ts;
		return round($diff / 86400);
	}

	function my_custom_columns($column)
	{
		global $post;
		if ("ID" == $column) echo $post->ID;
		elseif ("description" == $column) echo $post->post_content;
		elseif ("invoice_no" == $column) echo get_post_meta($post->ID, 'invoice_number', true);
		elseif ("invoice_type" == $column) echo get_post_meta($post->ID, 'invoice_type', true);
		elseif ("amount" == $column) echo get_wp3i_currency().number_format(wp3i_get_invoice_total($post->ID), 2, '.', '');
		elseif ("client" == $column) echo get_invoice_client_edit($post->ID);
		elseif ("status" == $column)
		{
			$invoice_paid = get_post_meta($post->ID, 'invoice_paid', true);
			$invoice_sent = get_post_meta($post->ID, 'invoice_sent', true);
			if($invoice_paid && $invoice_paid != 'Not yet')
			{
				echo 'Paid';
			}
			elseif($invoice_sent && $invoice_sent != 'Not yet')
			{
				$invoice_sent = explode('/',$invoice_sent);
				$invoice_sent = intval($invoice_sent[2]).'-'.intval($invoice_sent[1]).'-'.intval($invoice_sent[0]);
	
				$days = wp3i_date_diff($invoice_sent, date_i18n('Y-m-d'));
				if($days == 0){echo 'Sent today';}
				elseif($days == 1){echo 'Sent 1 day ago';}
				else{ echo 'Sent '.$days.' days ago';} 
			}
			else{echo 'Not sent yet';}
		}
	}
	
	add_action("manage_posts_custom_column", "my_custom_columns");
	add_filter("manage_edit-invoice_columns", "wp3i_columns");
	
	
	
	/* wp3i_add_new_rules
	--------------------------------------------------------------------------------------------*/
	function wp3i_add_new_rules(){
	
		global $wp_rewrite;
	
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
	}
	wp3i_add_new_rules();
	
	
	function wp3i_filter_invoice_columns()
	{
		global $wp, $post;
		$post_type = $wp->query_vars["post_type"];
		
		if($post_type == 'invoice')
		{
			$the_terms = get_terms('client','orderby=name&hide_empty=0' );
						
			$content  = '<select name="client" id="client" class="postform">';
			$content .= '<option value="0">View all Clients</option>';
			foreach ($the_terms as $term){
				$content .= '<option value="' . $term->slug . '">'. $term->name . ' ('.$term->count.')</option>';
			}
			$content .= '</select>';
					
			$content = str_replace('post_tag', 'tag', $content);
			echo $content;
		}
		
	}
	
	add_action('restrict_manage_posts', 'wp3i_filter_invoice_columns');
	
	
	// flush and refresh permalinks
	global $wp_rewrite;
    $wp_rewrite->flush_rules();
}
add_action('init', 'invoice_init');
?>