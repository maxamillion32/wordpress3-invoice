<?php
/*
Plugin Name: WordPress 3 Invoice
Plugin URI: http://www.wordpress3invoice.com/
Description: An online Invoice solution for web designers. Manage and email invoices through wordpress and customise with html + css invoice templates.
Version: 1.1.1
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
}
add_action('init', 'wp3i_init');



/* Includes
----------------------------*/
require_once('core/invoice.php');
require_once('core/client.php');
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
	
	// activate client taxonomy
	taxonomy_metadata_setup();
	
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