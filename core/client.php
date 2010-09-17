<?php 

function client_init()
{
	/* 
	
	Register Client Taxonomy
	--------------------------------------------------------------------------------------------*/
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
	/* 
	
	
	Add Extra Fields
	--------------------------------------------------------------------------------------------*/
	add_action( 'client_add_form_fields', 'add_client', 10, 2);
	add_action( 'client_edit_form_fields', 'edit_client', 10, 2);
	
	function add_client($tag)
	{
		?>
		<div class="form-field">
			<label for="client_email">Email Address</label>
			<input type="text" id="client_email" name="client_email" size="40" value="">
		</div>
		<div class="form-field">
			<label for="client_business">Business Name</label>
			<input type="text" id="client_business" name="client_business" size="40" value="">
		</div>
		<div class="form-field">
			<label for="client_address">Business Address</label>
			<textarea id="client_address" name="client_address" cols="40" value="" rows="5"></textarea>
		</div>
        <div class="form-field">
			<label for="client_phone">Phone Number</label>
			<input type="text" id="client_phone" name="client_phone" size="40" value="">
		</div>
        <div class="form-field">
			<label for="client_number">Client Number</label>
			<input type="text" id="client_number" name="client_number" size="40" value="">
            <p>Could be used as a VAT Number</p>
		</div>
		
		<?php
	}
	
	function edit_client($tag)
	{
		$client_email = get_term_meta($tag->term_id, 'client_email', true);
		$client_business = get_term_meta($tag->term_id, 'client_business', true);
		$client_address = get_term_meta($tag->term_id, 'client_address', true);
		$client_phone = get_term_meta($tag->term_id, 'client_phone', true);
		$client_number = get_term_meta($tag->term_id, 'client_number', true);
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="client_email">Email Address</label></th>
			<td><input type="text" id="client_email" name="client_email" size="40" value="<?php echo $client_email; ?>"></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="client_business">Business Name</label></th>
			<td><input type="text" id="client_business" name="client_business" size="40" value="<?php echo $client_business; ?>"></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="client_address">Business Address</label></th>
			<td><textarea id="client_address" name="client_address" cols="40" value="" rows="5"><?php echo $client_address; ?></textarea></td>
		</tr>
        <tr class="form-field">
			<th scope="row" valign="top"><label for="client_phone">Phone Number</label></th>
			<td><input type="text" id="client_phone" name="client_phone" size="40" value="<?php echo $client_phone; ?>"></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="client_number">Client Number</label></th>
			<td><input type="text" id="client_number" name="client_number" size="40" value="<?php echo $client_number; ?>"><br />
            <span class="description">Could be used as a VAT Number</span></td>
		</tr>
		<?php
	}
	/* 
	
	
	Save Extra Fields
	--------------------------------------------------------------------------------------------*/
	add_action('edit_client', 'wp3i_save_client', 10, 2);
	add_action('create_client', 'wp3i_save_client', 10, 2);
	
	function wp3i_save_client($term_id, $tt_id)
	{
		if (!$term_id) return;
	   
		if (isset($_POST['client_email']))
			update_term_meta($term_id, 'client_email',$_POST['client_email']);
	
		if (isset($_POST['client_business']))
			update_term_meta($term_id, 'client_business', $_POST['client_business']);
	
		if (isset($_POST['client_address']))
			update_term_meta($term_id, 'client_address', $_POST['client_address']);
		
		if (isset($_POST['client_phone']))
			update_term_meta($term_id, 'client_phone', $_POST['client_phone']);
			
		if (isset($_POST['client_number']))
			update_term_meta($term_id, 'client_number', $_POST['client_number']);
	}
	/* 
	
	
	Delete Extra Fields
	--------------------------------------------------------------------------------------------*/
	add_action('delete_client', 'wp3i_delete_client', 10, 2);
	
	function wp3i_delete_client($term_id, $tt_id)
	{
		if (!$term_id) return;
	   
		delete_term_meta($term_id, 'client_email',$_POST['client_email']);
		delete_term_meta($term_id, 'client_business', $_POST['client_business']);
		delete_term_meta($term_id, 'client_address', $_POST['client_address']);
	}
	/* 
	
	
	Extra Columns
	--------------------------------------------------------------------------------------------*/ 
	function wp3i_client_columns($columns)
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

	function wp3i_admin_client_column_row( $row_content, $column_name, $term_id ) 
	{
		if ("client_business" == $column_name) return get_term_meta($term_id, 'client_business', true);
		elseif ("client_email" == $column_name) return get_term_meta($term_id, 'client_email', true);
		
	}

	add_filter("manage_edit-client_columns", "wp3i_client_columns", 10, 1);
	add_filter('manage_client_custom_column', 'wp3i_admin_client_column_row', 10, 3 );
	
	

}
add_action( 'init', 'client_init' );
/* 
	

	
Setup Taxonomy meta data
--------------------------------------------------------------------------------------------*/
function taxonomy_metadata_setup() {
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
/* 
	
	
	
Fix Taxonomy metadata
--------------------------------------------------------------------------------------------*/
function taxonomy_metadata_wpdbfix() {
 	global $wpdb;
  	$wpdb->taxonomymeta = "{$wpdb->prefix}taxonomymeta";
}
add_action('init','taxonomy_metadata_wpdbfix');
add_action('switch_blog','taxonomy_metadata_wpdbfix');
/* 
	
	
	
Functions
--------------------------------------------------------------------------------------------*/
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

/*--------------------------------------------------------------------------------------------
										invoice_client Name		
	--------------------------------------------------------------------------------------------*/
	function get_invoice_client_name()
	{
		global $post;
		
		$terms = get_the_terms($post->ID , 'client');
		$terms = array_values($terms);
		if($terms)
		{	
			return $terms[0]->name;
		}
	}
	
	function invoice_client()
	{
		echo get_invoice_client_name();
	}
	
	/*--------------------------------------------------------------------------------------------
										invoice_client Description
	--------------------------------------------------------------------------------------------*/
	function get_invoice_client_description()
	{
		global $post;
		
		$terms = get_the_terms($post->ID , 'client');
		$terms = array_values($terms);
		if($terms)
		{	
			return $terms[0]->description;
			
		}
	}
	
	function invoice_client_description()
	{
		echo nl2br(get_invoice_client_description());
	}
	
	/*--------------------------------------------------------------------------------------------
										invoice_client Email		
	--------------------------------------------------------------------------------------------*/
	function get_invoice_client_email()
	{
		global $post;
		
		$terms = get_the_terms($post->ID , 'client');
		$terms = array_values($terms);
		if($terms)
		{	
			return get_term_meta($terms[0]->term_id, 'client_email', true);
		}
	}
	
	function invoice_client_email()
	{
		echo get_invoice_client_email();
	}
	
	/*--------------------------------------------------------------------------------------------
										invoice_client Business		
	--------------------------------------------------------------------------------------------*/
	function get_invoice_client_business()
	{
		global $post;
		
		$terms = get_the_terms($post->ID , 'client');
		$terms = array_values($terms);
		if($terms)
		{	
			return get_term_meta($terms[0]->term_id, 'client_business', true);
		}
	}
	
	function invoice_client_business()
	{
		echo get_invoice_client_business();
	}
	
	/*--------------------------------------------------------------------------------------------
										invoice_client Business Address		
	--------------------------------------------------------------------------------------------*/
	function get_invoice_client_business_address()
	{
		global $post;
		
		$terms = get_the_terms($post->ID , 'client');
		$terms = array_values($terms);
		if($terms)
		{	
			return get_term_meta($terms[0]->term_id, 'client_address', true);
		}
	}
	
	function invoice_client_business_address()
	{
		echo nl2br(get_invoice_client_business_address());
	}
	
	/*--------------------------------------------------------------------------------------------
										invoice_client Phone Number
	--------------------------------------------------------------------------------------------*/
	function get_invoice_client_phone()
	{
		global $post;
		
		$terms = get_the_terms($post->ID , 'client');
		$terms = array_values($terms);
		if($terms)
		{	
			return get_term_meta($terms[0]->term_id, 'client_phone', true);
		}
	}
	
	function invoice_client_phone()
	{
		echo get_invoice_client_phone();
	}
	
	/*--------------------------------------------------------------------------------------------
										invoice_client Number
	--------------------------------------------------------------------------------------------*/
	function get_invoice_client_number()
	{
		global $post;
		
		$terms = get_the_terms($post->ID , 'client');
		$terms = array_values($terms);
		if($terms)
		{	
			return get_term_meta($terms[0]->term_id, 'client_number', true);
		}
	}
	
	function invoice_client_number()
	{
		echo get_invoice_client_number();
	}
	
	
?>