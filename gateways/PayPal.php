<?php

/**
 * PayPal Plugin for WordPress 3 Invoice
 * @class PayPal
 *
 * @author Elliot Condon
 * @version 1.0.0
 * @copyright Elliot Condon, 2010
 * @since 2.0.1
 * 
 **/
 
class PayPal
{
	function PayPal($parent)
	{
		global $post;
		?>
        <style type="text/css">
			form.paypal {display:block; float:left; margin-left:5px; margin-top:3px;}
			form.paypal input[type=image] {width:89px; height:23px; cursor:pointer; background:url('<?php echo $parent->plugin_dir; ?>admin/images/payment-gateway-buttons.png') 0px 0px; display:block; overflow:hidden; white-space:nowrap; text-indent:200px;}
		</style>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="paypal">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="<?php wp3i_payment_gateway_account(); ?>">
        <input type="hidden" name="item_name" value="Invoice #<?php invoice_number(); ?> | <?php the_title(); ?>">
        <input type="hidden" name="amount" value="<?php echo get_the_invoice_subtotal(); ?>">
        <input type="hidden" name="tax" value="<?php echo get_the_invoice_tax(); ?>">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="currency_code" value="<?php wp3i_currency_code(); ?>">
        <input type="hidden" name="first_name" value="<?php invoice_client(); ?>">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="return" value="<?php the_permalink(); ?>">
        <input type="hidden" name="cancel_return" value="<?php the_permalink(); ?>">
        <input type="hidden" name="notify_url" value="<?php echo add_query_arg('paid', 'true', get_permalink($post->ID)); ?>">
        <input type="image" src="<?php echo $parent->plugin_dir; ?>admin/images/payment-gateway-buttons.png" border="0" name="submit">
        </form>
        <?php	
	}
}
?>