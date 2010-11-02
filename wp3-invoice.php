<?php
/*
Plugin Name: WordPress 3 Invoice
Plugin URI: http://www.wordpress3invoice.com/
Description: An online Invoice solution for web designers. Manage, print and email invoices through WordPress and customise with php + html + css invoice templates.
Version: 2.0.1
Author: Elliot Condon
Author URI: http://www.elliotcondon.com/
License: GPL
Copyright Elliot Condon
*/

include('core/functions.php');
include('core/invoice.php');
include('core/client.php');
include('admin/stats.php');
include('admin/options.php');
include('admin/help.php');

$Wp3i = new Wp3i();

class Wp3i
{ 
	var $name;
	var $dir;
	var $path;
	var $siteurl;
	var $wpadminurl;
	var $version;
	
	var $invoice;
	var $client;
	var $stats;
	var $options;
	var $help;
	
	function Wp3i()
	{
		
		// set class variables
		$this->name = 'WordPress 3 Invoice';
		$this->path = dirname(__FILE__).'/';
		$this->dir = plugins_url('/',__FILE__);
		$this->siteurl = get_bloginfo('url');
		$this->wpadminurl = admin_url();
		$this->version = '2.0.1';
		
		
		$this->invoice = new Invoice($this);
		$this->client = new Client($this);
		$this->stats = new Stats($this);
		$this->options = new Options($this);
		$this->help = new Help($this);
		
		add_action('admin_head', array($this,'admin_head'));
		register_activation_hook( __FILE__, array($this,'activate'));
	
		add_action('admin_menu', array($this,'create_menu'));
		
		add_filter('upgrader_pre_install', array($this,'wp3i_backup'), 10, 2);
		add_filter('upgrader_post_install', array($this,'wp3i_recover'), 10, 2);
		return true;
	}

	/**
	 * Adds Style + Javascript to admin head
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * @Todo - only add to wp3i admin pages
	 * 
	 **/
	function admin_head()
	{
		global $post;
		// 1. add style + jquery to all invoice related pages
		if(get_post_type($post->ID) == 'invoice' || $_GET['post_type'] == 'invoice') 
		{
			echo '<link rel="stylesheet" href="'.$this->dir.'admin/style.css" type="text/css" media="all" />';	
			echo '<script type="text/javascript" src="'.$this->dir.'admin/admin-jquery.js" ></script>';
		}
		// 2. only add highcharts to stats page
		if($_GET['page'] == 'stats') 
		{
			echo '<script type="text/javascript" src="'.$this->dir.'admin/highcharts.js"></script>';	
		}
	}
	
	/**
	 * Performs plugin installation actions upon activation in Wordpress plugin menu
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 * @return bool Successfully activated
	 **/
	function activate() 
	{
		$this->client->taxonomy_metadata_setup();
		
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
		
		return true;
	}
	
	/**
	 * Creates Admin Menu
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 *
	 **/
	function create_menu() {
	
		add_menu_page('wp3i', 'WP3 Invoice', 'manage_options', 'edit.php?post_type=invoice','',$this->dir.'admin/images/menu-icon.png');
		//add_submenu_page('edit.php?post_type=invoice', 'Clients', 'Clients', 'manage_options','edit-tags.php?taxonomy=client&post_type=invoice');
		add_submenu_page('edit.php?post_type=invoice', 'Stats', 'Stats', 'manage_options','stats',array($this->stats,'admin_page'));
		add_submenu_page('edit.php?post_type=invoice', 'Options', 'Options', 'manage_options','options',array($this->options,'admin_page'));
		add_submenu_page('edit.php?post_type=invoice', 'Help', 'Help', 'manage_options','help',array($this->help,'admin_page'));
		
		global $menu;
		global $submenu;
	
		$restricted = array('Invoices');
		end ($menu);
		while (prev($menu)){
			$value = explode(' ',$menu[key($menu)][0]);
			if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
		}
		
		unset($submenu['edit.php?post_type=invoice'][10]);
	}
	
	/**
	 * Copy a folder
	 *
	 * @author Clay Lua: http://hungred.com/how-to/prevent-wordpress-plugin-update-deleting-important-folder-plugin/
	 * @since 2.0.1
	 *
	 **/
	function wp3i_copy($source, $dest)
	{
		// Check for symlinks
		if (is_link($source)) {
			return symlink(readlink($source), $dest);
		}
	
		// Simple copy for a file
		if (is_file($source)) {
			return copy($source, $dest);
		}
	
		// Make destination directory
		if (!is_dir($dest)) {
			mkdir($dest);
		}
	
		// Loop through the folder
		$dir = dir($source);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}
	
			// Deep copy directories
			$this->wp3i_copy("$source/$entry", "$dest/$entry");
		}
	
		// Clean up
		$dir->close();
		return true;
	}
	
	/**
	 * Remove a folder
	 *
	 * @author Aidan Lister: http://putraworks.wordpress.com/2006/02/27/php-delete-a-file-or-a-folder-and-its-contents/
	 * @since 2.0.1
	 *
	 **/
	function wp3i_remove($dirname)
	{
		// Sanity check
		if (!file_exists($dirname)) {
			return false;
		}
		
		// Simple delete for a file
		if (is_file($dirname)) {
			return unlink($dirname);
		}
		
		// Loop through the folder
		$dir = dir($dirname);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}
			
			// Recurse
			$this->wp3i_remove("$dirname/$entry");
		}
		
		// Clean up
		$dir->close();
		return rmdir($dirname);
		
	}
	/**
	 * Backup Gateway folder on auto update
	 *
	 * @author Elliot Condon
	 * @since 2.0.1
	 *
	 **/	
	function wp3i_backup()
	{
		$to = $this->path.'../wp3i_backup/';
		$from = $this->path.'gateways/';
		if(is_dir($from))
		{
			$this->wp3i_copy($from, $to);
		}
	}
	/**
	 * Restore Gateway folder on auto update
	 *
	 * @author Elliot Condon
	 * @since 2.0.1
	 *
	 **/	
	function wp3i_recover()
	{
		$from = $this->path.'../wp3i_backup/';
		$to = $this->path.'gateways/';
		if(is_dir($from))
		{
			$this->wp3i_copy($from, $to);
			$this->wp3i_remove($from);
		}
			
	}
	

	
	
	
}