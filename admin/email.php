<?php
/**
 * Send Invoice as HTML Email
 *
 * @author Elliot Condon
 * @since 2.0.0
 *
 **/
 
global $post;

$from = get_wp3i_email();															// 1. Get From email
update_post_meta($post->ID, 'invoice_sent', date_i18n('j/m/Y'));					// 2. Set sent custom field

$headers = "From: ".$from."\n";														// 3. Set Email Headers
$headers .= "Reply-To: ".$from."\n";
$headers .= 'MIME-Version: 1.0' . "\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

$to = get_invoice_client_email();													// 4. Send email to ...
if(wp3i_get_emailrecipients() == 'both')
{
	$to .= ','.$from;
}

$subject = get_invoice_type().' # '.get_invoice_number().' - '.get_the_title();		// 5. Email Subject

if(!$to)																			// 6. Quick validation check
{
	echo '<p class="error">'.__('Error: No recipient email address found','wp3i').'</p>';
	die;
}
if(!$message)
{
	echo '<p class="error">'.__('Error: No message body found','wp3i').'</p>';	
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
		echo '<p class="success">'.__('Email was successfully sent!','wp3i').'</p>';
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
		echo '<p class="error">'.__('Email failed to send','wp3i').'</p>';
	}
   
}

?>