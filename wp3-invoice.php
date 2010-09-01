<?php
/*
Plugin Name: WordPress 3 Invoice
Plugin URI: http://www.elliotcondon.com/wordpress/wordpress-3-invoice-plugin/
Description: An online Invoice solution for web designers. Manage and email invoices through wordpress and customise with html + css invoice templates.
Version: 1.0.9
Author: Elliot Condon
Author URI: http://www.elliotcondon.com/
License: GPL
*/

$invoice_plugin_url = plugins_url('',__FILE__);
$invoice_template_url = plugins_url('template',__FILE__);


/*--------------------------------------------------------------------------------------------
											Head
--------------------------------------------------------------------------------------------*/
function wp3i_head()
{
	?>
	<link rel="stylesheet" href="<?php echo plugins_url('admin/style.css',__FILE__); ?>" type="text/css" media="all" />	
    <script type="text/javascript" src="<?php echo plugins_url('admin/admin-jquery.js',__FILE__); ?>" ></script>
    <script type="text/javascript" src="<?php echo plugins_url('admin/highcharts.js',__FILE__); ?>"></script>
    <?php
}
add_action('admin_head', 'wp3i_head');


/*--------------------------------------------------------------------------------------------
										WP3I Init
--------------------------------------------------------------------------------------------*/
function wp3i_init()
{
	// flush and refresh permalinks
	global $wp_rewrite;
    $wp_rewrite->flush_rules();
	
	/* Regist Invoice Post Type
	----------------------------*/
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
	register_post_type('invoice', array(
		'labels' => $labels,
		'menu_icon' => plugins_url('admin/images/invoice-icon.gif',__FILE__),
		'public' => true,
		'show_ui' => true,
		'_builtin' =>  false,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array("slug" => "invoice"), // Permalinks format
		'query_var' => "invoice",
		'supports' => array('title'/*,'editor','custom-fields'*/),
	));
	
	
	/* Clients Taxonomy
	----------------------------*/
	$labels = array(
		'name' => _x( 'Clients', 'taxonomy general name' ),
		'singular_name' => _x( 'Client', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Clients' ),
		'all_items' => __( 'All Clients' ),
		'parent_item' => __( 'Parent Client' ),
		'parent_item_colon' => __( 'Parent Client:' ),
		'edit_item' => __( 'Edit Client' ), 
		'update_item' => __( 'Update Client' ),
		'add_new_item' => __( 'Add New Client' ),
		'new_item_name' => __( 'New Client Name' ),
	); 	
	register_taxonomy('client', 'invoice',
		array(
             'hierarchical' => true,
			 'labels' => $labels,
			 'query_var' => true,
			 'rewrite' => true
		)
	);


	/* Invoice Columns
	----------------------------*/
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
		elseif ("client" == $column) echo get_the_term_list( $post->ID, 'client', '', ', ', '' );  
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
	----------------------------*/
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
	
	
	/* Template Redirect
	----------------------------*/
	function wp3i_template_redirect()
	{
		// define invoice url variables
		global $wp, $post;
		$post_type = $wp->query_vars["post_type"];
		$email = $_GET['email'];

		// find email.php template file
		$emailTemplateURL = 'template/email.php';
		if(file_exists(STYLESHEETPATH . '/invoice/email.php'))
		{
			$emailTemplateURL = STYLESHEETPATH . '/invoice/email.php';
		}
		
		// find invoice.php template file
		$invoiceTemplateURL = 'template/invoice.php';
		if(file_exists(STYLESHEETPATH . '/invoice/invoice.php'))
		{
			$invoiceTemplateURL = STYLESHEETPATH . '/invoice/invoice.php';
		}

		if($post_type == 'invoice')
		{
			if($email == 'send')
			{
				// get html email and store as variable for sending
				ob_start();
				include('template/email.php');
				$message = ob_get_contents();
				ob_end_clean();
				include('admin/email.php');
			}
			elseif($email == 'template')
			{
				include($emailTemplateURL);
				
			}
			else
			{
				include($invoiceTemplateURL);
			}
			die();
		}
	}
	add_action('template_redirect', 'wp3i_template_redirect');
	
	// flush and refresh permalinks
	global $wp_rewrite;
    $wp_rewrite->flush_rules();
}
add_action('init', 'wp3i_init');



/* Includes
----------------------------*/
require_once('core/functions.php');
require_once('admin/meta-boxes.php');
require_once('admin/options.php');
require_once('admin/stats.php');
	
	
function wp3i_menu()
{
	add_submenu_page('edit.php?post_type=invoice', 'WP3 Invoice Stats', 'Stats', 'manage_options', 'wp3-invoice-stats', 'wp3i_stats');
	add_submenu_page('edit.php?post_type=invoice', 'WP3 Invoice Options', 'Options', 'manage_options', 'wp3-invoice-options', 'wp3i_options');
}
add_action('admin_menu', 'wp3i_menu');


/*--------------------------------------------------------------------------------------------
										WP3I Activate
--------------------------------------------------------------------------------------------*/
function wp3i_activate() 
{
	//echo 'Updating Invoice Meta Data...';
	
	// loop though all invoices, set custom fields.
	$invoices = get_posts(array(
		'post_type' => 'invoice', 
		'numberposts' => '-1', 
	));
	
	foreach($invoices as $invoice)
	{
		$invoice_paid = get_post_meta($invoice->ID, 'invoice_paid', true);
		$invoice_sent = get_post_meta($invoice->ID, 'invoice_sent', true);
		$invoice_type = get_post_meta($invoice->ID, 'invoice_type', true);
		
		if(!$invoice_paid) // if 1.0.5 invoice_paid doesnt exist
		{
			if(get_post_meta($invoice->ID, 'invoice_status', true) == 'Invoice Paid')// if 1.0.4 invoice_status is paid
			{
				update_post_meta($invoice->ID, 'invoice_paid', get_the_time('j/m/Y',$invoice->ID));
				update_post_meta($invoice->ID, 'invoice_sent', get_the_time('j/m/Y',$invoice->ID));
			}
			elseif(get_post_meta($invoice->ID, 'invoice_status', true) == 'Invoice Sent')// if 1.0.4 invoice_status is sent
			{
				update_post_meta($invoice->ID, 'invoice_paid', 'Not yet');
				update_post_meta($invoice->ID, 'invoice_sent', get_the_time('j/m/Y',$invoice->ID));
			}
			else
			{
				update_post_meta($invoice->ID, 'invoice_paid', 'Not yet');
				update_post_meta($invoice->ID, 'invoice_sent', 'Not yet');
			}
		}
		if(!$invoice_type) // if 1.0.5 invoice_type doesnt exist
		{
			if(get_post_meta($invoice->ID, 'invoice_status', true) == 'Quote')// if 1.0.4 invoice_status is Quote
			{
				update_post_meta($invoice->ID, 'invoice_type', 'Quote');
			}
			else
			{
				update_post_meta($invoice->ID, 'invoice_type', 'Invoice');	
			}
		}
		
	}// end for each invoice
	
	// flush and refresh permalinks
	global $wp_rewrite;
    $wp_rewrite->flush_rules();
	
	
}

register_activation_hook( __FILE__, 'wp3i_activate' );