<?php

global $post;
	
/* Get Current User's Email
-------------------------------------*/
$current_user = wp_get_current_user();
$from = '';
if($current_user){$from = $current_user->user_email;}

	
/* Set sent custom field
-------------------------------------*/
update_post_meta($post->ID, 'invoice_sent', date_i18n('j/m/Y'));
	
	
/* Headers
-------------------------------------*/
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From: ".$from."\r\n";
$headers .= "Reply-To: ".$from."\r\n";
	
	
/* Recipients
-------------------------------------*/
$to = get_invoice_client_email();
if(wp3i_get_emailrecipients() == 'both')
{
	$to .= ','.$from;
}

	
/* Email
-------------------------------------*/
$subject = get_invoice_type().' # '.get_invoice_number().': '.get_the_title().' from '.get_bloginfo('name');
$message = wp_remote_fopen(get_permalink().'?email=template');
	

if ( mail($to,$subject,$message,$headers) ) 
{
	$edit_link = get_bloginfo('url').'/wp-admin/post.php?post='.$post->ID.'&action=edit&sent=success';
	if($edit_link)
	{
		wp_redirect($edit_link);
		//echo $message;
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