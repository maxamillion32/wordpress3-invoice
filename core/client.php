<?php 
class Client
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
	function Client($parent)
	{
		$this->name = $parent->name;					// Plugin Name
		$this->plugin_dir = $parent->dir;				// Plugin directory
		$this->plugin_path = $parent->path;				// Plugin Absolute Path
		$this->dir = plugins_url('/',__FILE__);			// This directory
		
		
		// Init
		add_action('init', array($this, 'create_custom_post'));
		
		// Client extra fields
		add_action('client_add_form_fields', array($this,'add_client'), 10, 2);
		add_action('client_edit_form_fields', array($this,'edit_client'), 10, 2);
		
		
		// Edit, Create, Delete Client
		add_action('edit_client', array($this,'save_client'), 10, 2);
		add_action('create_client', array($this,'save_client'), 10, 2);
		add_action('delete_client', array($this,'delete_client'), 10, 2);
		
		// Client Columns
		add_filter('manage_edit-client_columns', array($this,'client_columns_setup'), 10, 1);
		add_filter('manage_client_custom_column', array($this,'client_columns_data'), 10, 3 );
		
		// Client Taxonomy Table
		add_action('init',array($this,'taxonomy_metadata_wpdbfix'));
		add_action('switch_blog',array($this,'taxonomy_metadata_wpdbfix'));
		

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
	}
	
	/**
	 * Add Extra Fields to Add Client
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function add_client($tag)
	{
		?>
		<div class="form-field">
			<label for="client_email"><?php _e('Email Address','wp3i'); ?></label>
			<input type="text" id="client_email" name="client_email" size="40" value="">
		</div>
        <div class="form-field">
			<label for="client_password"><?php _e('Password','wp3i'); ?></label>
			<input type="text" id="client_password" name="client_password" size="40" value="">
		</div>
		<div class="form-field">
			<label for="client_business"><?php _e('Business Name','wp3i'); ?></label>
			<input type="text" id="client_business" name="client_business" size="40" value="">
		</div>
		<div class="form-field">
			<label for="client_address"><?php _e('Business Address','wp3i'); ?></label>
			<textarea id="client_address" name="client_address" cols="40" value="" rows="5"></textarea>
		</div>
        <div class="form-field">
			<label for="client_phone"><?php _e('Phone Number','wp3i'); ?></label>
			<input type="text" id="client_phone" name="client_phone" size="40" value="">
		</div>
        <div class="form-field">
			<label for="client_number"><?php _e('Client Number','wp3i'); ?></label>
			<input type="text" id="client_number" name="client_number" size="40" value="">
            <p><?php _e('Could be used as a VAT Number','wp3i'); ?></p>
		</div>
		
		<?php
	}
	
	/**
	 * Add Extra Fields to Edit Client
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function edit_client($tag)
	{
		$client_email = get_term_meta($tag->term_id, 'client_email', true);
		$client_password = get_term_meta($tag->term_id, 'client_password', true); 
		$client_business = get_term_meta($tag->term_id, 'client_business', true);
		$client_address = get_term_meta($tag->term_id, 'client_address', true);
		$client_phone = get_term_meta($tag->term_id, 'client_phone', true);
		$client_number = get_term_meta($tag->term_id, 'client_number', true);
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="client_email"><?php _e('Email Address','wp3i'); ?></label></th>
			<td><input type="text" id="client_email" name="client_email" size="40" value="<?php echo $client_email; ?>"></td>
		</tr>
        <tr class="form-field">
			<th scope="row" valign="top"><label for="client_password"><?php _e('Password','wp3i'); ?></label></th>
			<td><input type="text" id="client_password" name="client_password" size="40" value="<?php echo $client_password; ?>"></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="client_business"><?php _e('Business Name','wp3i'); ?></label></th>
			<td><input type="text" id="client_business" name="client_business" size="40" value="<?php echo $client_business; ?>"></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="client_address"><?php _e('Business Address','wp3i'); ?></label></th>
			<td><textarea id="client_address" name="client_address" cols="40" value="" rows="5"><?php echo $client_address; ?></textarea></td>
		</tr>
        <tr class="form-field">
			<th scope="row" valign="top"><label for="client_phone"><?php _e('Phone Number','wp3i'); ?></label></th>
			<td><input type="text" id="client_phone" name="client_phone" size="40" value="<?php echo $client_phone; ?>"></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="client_number"><?php _e('Client Number','wp3i'); ?></label></th>
			<td><input type="text" id="client_number" name="client_number" size="40" value="<?php echo $client_number; ?>"><br />
            <span class="description"><?php _e('Could be used as a VAT Number','wp3i'); ?></span></td>
		</tr>
		<?php
	}
	
	/**
	 * Save Extra Fields for Client
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function save_client($term_id, $tt_id)
	{
		if (!$term_id) return;

		if (isset($_POST['client_email']))
			update_term_meta($term_id, 'client_email',$_POST['client_email']);
			
		if (isset($_POST['client_password']))
			update_term_meta($term_id, 'client_password',$_POST['client_password']);
			
		if (isset($_POST['client_business']))
			update_term_meta($term_id, 'client_business', $_POST['client_business']);
	
		if (isset($_POST['client_address']))
			update_term_meta($term_id, 'client_address', $_POST['client_address']);
		
		if (isset($_POST['client_phone']))
			update_term_meta($term_id, 'client_phone', $_POST['client_phone']);
			
		if (isset($_POST['client_number']))
			update_term_meta($term_id, 'client_number', $_POST['client_number']);
	}
	
	/**
	 * Delete Extra Fields for Client
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function delete_client($term_id, $tt_id)
	{
		if (!$term_id) return;
		delete_term_meta($term_id, 'client_email',$_POST['client_email']);
		delete_term_meta($term_id, 'client_password',$_POST['client_password']);
		delete_term_meta($term_id, 'client_business', $_POST['client_business']);
		delete_term_meta($term_id, 'client_address', $_POST['client_address']);
		delete_term_meta($term_id, 'client_phone', $_POST['client_phone']);
		delete_term_meta($term_id, 'client_number', $_POST['client_number']);
	}
	
	/**
	 * Client Columns Setup
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function client_columns_setup($columns)
	{
	
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"name" => "Name",
			"client_business" => "Business",
			"client_email" => "Email Address",
			"posts" => "Invoices"
		);
		return $columns;
	}

	function client_columns_data( $row_content, $column_name, $term_id ) 
	{
		if ("client_business" == $column_name) return get_term_meta($term_id, 'client_business', true);
		elseif ("client_email" == $column_name) return get_term_meta($term_id, 'client_email', true);
		
	}

	/**
	 * Extend Taxonomy Table for Client (run on acivation, called by parent)
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function taxonomy_metadata_setup() 
	{
	
		global $wpdb;
		$charset_collate = '';  
		if ( ! empty($wpdb->charset) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) )
			$charset_collate .= " COLLATE $wpdb->collate";
	  
		$tables = $wpdb->get_results("show tables like '{$wpdb->prefix}taxonomymeta'");
		if (!count($tables))
			$wpdb->query("CREATE TABLE {$wpdb->prefix}taxonomymeta (
			meta_id bigint(20) unsigned NOT NULL auto_increment,
			taxonomy_id bigint(20) unsigned NOT NULL default '0',
			meta_key varchar(255) default NULL,
			meta_value longtext,
			PRIMARY KEY  (meta_id),
			KEY taxonomy_id (taxonomy_id),
			KEY meta_key (meta_key)
			) 	$charset_collate;");
	}
	
	function taxonomy_metadata_wpdbfix() 
	{
		global $wpdb;
		$wpdb->taxonomymeta = "{$wpdb->prefix}taxonomymeta";
	}
	
	
	
	
}

/**
 * Client Table Functions
 *
 * @author Elliot Condon
 * @since 2.0.0
 * 
 **/
 
function add_term_meta($term_id, $meta_key, $meta_value, $unique = false) {
	return add_metadata('taxonomy', $term_id, $meta_key, $meta_value, $unique);
}
function delete_term_meta($term_id, $meta_key, $meta_value = '') {
	return delete_metadata('taxonomy', $term_id, $meta_key, $meta_value);
}
function get_term_meta($term_id, $key, $single = false) {
	return get_metadata('taxonomy', $term_id, $key, $single);
}
function update_term_meta($term_id, $meta_key, $meta_value, $prev_value = '') {
	return update_metadata('taxonomy', $term_id, $meta_key, $meta_value, $prev_value);
}

/**
 * Client Name
 *
 * @author Elliot Condon
 * @since 2.0.0
 * 
 **/
function get_invoice_client_name()
{
	global $post;
	
	$terms = get_the_terms($post->ID , 'client');
	if($terms)
	{	
		$terms = array_values($terms);
		return $terms[0]->name;
	}
}

function invoice_client()
{
	echo get_invoice_client_name();
}

/**
 * Client Description
 *
 * @author Elliot Condon
 * @since 2.0.0
 * 
 **/
function get_invoice_client_description()
{
	global $post;
	
	$terms = get_the_terms($post->ID , 'client');
	if($terms)
	{	
		$terms = array_values($terms);
		return $terms[0]->description;
		
	}
}

function invoice_client_description()
{
	echo nl2br(get_invoice_client_description());
}

/**
 * Client Email
 *
 * @author Elliot Condon
 * @since 2.0.0
 * 
 **/
function get_invoice_client_email()
{
	global $post;
	
	$terms = get_the_terms($post->ID , 'client');
	if($terms)
	{	
		$terms = array_values($terms);
		return get_term_meta($terms[0]->term_id, 'client_email', true);
	}
}

function invoice_client_email()
{
	echo get_invoice_client_email();
}

/**
 * Client Business
 *
 * @author Elliot Condon
 * @since 2.0.0
 * 
 **/
function get_invoice_client_business()
{
	global $post;
	
	$terms = get_the_terms($post->ID , 'client');
	if($terms)
	{	
		$terms = array_values($terms);
		return get_term_meta($terms[0]->term_id, 'client_business', true);
	}
}

function invoice_client_business()
{
	echo get_invoice_client_business();
}

/**
 * Client Business Address
 *
 * @author Elliot Condon
 * @since 2.0.0
 * 
 **/
function get_invoice_client_business_address()
{
	global $post;
	
	$terms = get_the_terms($post->ID , 'client');
	if($terms)
	{	
		$terms = array_values($terms);
		return get_term_meta($terms[0]->term_id, 'client_address', true);
	}
}

function invoice_client_business_address()
{
	echo nl2br(get_invoice_client_business_address());
}

/**
 * Client Phone Number
 *
 * @author Elliot Condon
 * @since 2.0.0
 * 
 **/
function get_invoice_client_phone()
{
	global $post;
	
	$terms = get_the_terms($post->ID , 'client');
	if($terms)
	{	
		$terms = array_values($terms);	
		return get_term_meta($terms[0]->term_id, 'client_phone', true);
	}
}

function invoice_client_phone()
{
	echo get_invoice_client_phone();
}

/**
 * Client VAT Number
 *
 * @author Elliot Condon
 * @since 2.0.0
 * 
 **/
function get_invoice_client_number()
{
	global $post;
	
	$terms = get_the_terms($post->ID , 'client');
	if($terms)
	{	
		$terms = array_values($terms);
		return get_term_meta($terms[0]->term_id, 'client_number', true);
	}
}

function invoice_client_number()
{
	echo get_invoice_client_number();
}


/**
 * Client Edit Link
 *
 * @author Elliot Condon
 * @since 2.0.0
 * 
 **/
function get_invoice_client_edit_link()
{
	global $post;
	$terms = get_the_terms($post->ID , 'client');
	if($terms)
	{	
		$terms = array_values($terms);
		return get_bloginfo('url').'/wp-admin/edit-tags.php?action=edit&taxonomy=client&post_type=invoice&tag_ID='.$terms[0]->term_id;
	}
}

function invoice_client_edit_link()
{
	echo get_invoice_client_edit_link();
}

function get_invoice_client_edit($postID = NULL)
{
	global $post;
	if($postID == NULL){$postID = $post->ID;}
	$terms = get_the_terms($postID , 'client');
	if($terms)
	{	
		$terms = array_values($terms);
		return '<a title="Edit Client" href="'.get_bloginfo('url').'/wp-admin/edit-tags.php?action=edit&taxonomy=client&post_type=invoice&tag_ID='.$terms[0]->term_id.'">'.$terms[0]->name.'</a>';
	}
}

/**
 * Client Password
 *
 * @author Elliot Condon
 * @since 2.0.0
 * 
 **/
function get_invoice_client_password()
{
	global $post;
	$terms = get_the_terms($post->ID , 'client');
	if($terms)
	{	
		$terms = array_values($terms);
		return get_term_meta($terms[0]->term_id, 'client_password', true);
	}
	else
	{
		return false;	
	}
}

?>