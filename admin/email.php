<?php
global $post;
	
/* Get From Email
-------------------------------------*/
$from = get_wp3i_email();

	
/* Set sent custom field
-------------------------------------*/
update_post_meta($post->ID, 'invoice_sent', date_i18n('j/m/Y'));
	
	
/* Headers
-------------------------------------*/
$headers = "From: ".$from."\n";
$headers .= "Reply-To: ".$from."\n";
$headers .= 'MIME-Version: 1.0' . "\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

	
	
/* Recipients
-------------------------------------*/
$to = get_invoice_client_email();
if(wp3i_get_emailrecipients() == 'both')
{
	$to .= ','.$from;
}

	
/* Email
-------------------------------------*/
$subject = get_invoice_type().' # '.get_invoice_number().' - '.get_the_title();
//$message = wp_remote_fopen(get_permalink().'?email=template');


/* Quick validation check
-------------------------------------*/
if(!$to)
{
	echo '<p class="error">Error: No recipient email address found</p>';
	die;
}
if(!$message)
{
	echo '<p class="error">Error: No message body found</p>';	
	die;
}


/* Mail it!
-------------------------------------*/
if ( mail($to,$subject,$message,$headers) ) 
{
	$edit_link = get_bloginfo('url').'/wp-admin/post.php?post='.$post->ID.'&action=edit&sent=success';
	if($edit_link)
	{
		wp_redirect($edit_link);
	}
	else
	{
		echo '<p class="success">Email was successfully sent!</p>';
	}
} 
else 
{
	// set sent custom field
	update_post_meta($post->ID, 'invoice_sent', 'Not yet');
	$edit_link = get_bloginfo('url').'/wp-admin/post.php?post='.$post->ID.'&action=edit&sent=fail';
	if($edit_link)
	{
		wp_redirect($edit_link);
	}
	else
	{
		echo '<p class="error">Email failed to send.</p>';
	}
   
}

?>