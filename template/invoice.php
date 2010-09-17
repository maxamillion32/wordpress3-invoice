<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

    <title><?php invoice_type(); ?> #<?php invoice_number(); ?> | <?php bloginfo('name'); ?> | <?php the_title(); ?></title>

    <link rel="stylesheet" href="<?php invoice_template_url(); ?>/style.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php invoice_template_url(); ?>/style-print.css" type="text/css" media="print" />
	<meta name="robots" content="noindex, nofollow">

    <?php wp_get_archives('type=monthly&format=link'); ?>
    <?php wp_head(); ?>
    
    
</head>

<body>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <div class="container">
    	<div class="header">
        	<table cellpadding="0" cellspacing="0" align="left">
            	<tr>
                	<td><img src="<?php invoice_template_url(); ?>/images/wp3i-logo.png" style="margin-top:-35px;"/></td>
                	<td>
                    <h3>WordPress3 <strong>Invoices</strong></h3>
                    <p>Created by Elliot Condon</p>
                    <p><a href="mailto:you@youremailhere.com">you@youremailhere.com</a></p>
                    <p><a href="www.yourwebsitehere.com">www.yourwebsitehere.com</a></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="info">
            <fieldset>
            	<legend>Sent to</legend>
                <p><strong><?php invoice_client(); ?></strong></p>
                <p class="hidden"><strong>Description: </strong><?php invoice_client_description(); ?></p>
                <p><?php invoice_client_business(); ?></p>
                <p><?php invoice_client_business_address(); ?></p>
                <p class="hidden"><strong>Email: </strong><?php invoice_client_email(); ?></p>
                <p class="hidden"><strong>Phone: </strong><?php invoice_client_phone(); ?></p>
                <p class="hidden"><strong>VAT Number: </strong><?php invoice_client_number(); ?></p>
                
            </fieldset>
            
            <fieldset class="last">
            	<legend><?php invoice_type(); ?> Details</legend>
                <p><strong>Project: </strong><?php the_title(); ?></p>
                <p><strong><?php invoice_type(); ?> Number: </strong><?php invoice_number(); ?></p>
                <p><strong>Date Issued: </strong><?php the_time('d/m/Y'); ?></p>
            </fieldset>
            
            
        </div>
        
        <div class="breakdown">
        	<table cellpadding="0" cellspacing="0">
            	<tr class="heading">
                	<td>Project Breakdown</td><td>Type</td><td>Rate</td><td>Hours</td><td style="width:75px;">Subtotal</td>
                </tr>
				<?php if(invoice_has_details()): ?>
                    <?php while(invoice_detail()): ?>
                            <tr class="title">
                                <td><?php the_detail_title(); ?></td><td><?php the_detail_type(); ?></td><td><?php wp3i_currency(); ?> <?php the_detail_rate(); ?></td><td><?php the_detail_duration(); ?></td><td><?php wp3i_currency(); ?> <?php the_detail_subtotal(); ?></td>
                            </tr>
                            <tr class="description">
                                <td><?php the_detail_description(); ?></td><td colspan="4"></td>
                            </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
                
				<?php if(wp3i_has_tax()):  ?>
                <tr class="heading">
                	<td colspan="3"></td><td>Subtotal</td><td><?php wp3i_currency(); ?> <?php the_invoice_subtotal(); ?></td>
                </tr>
                <tr class="heading">
                    <td colspan="3"></td><td>Tax</td><td><?php wp3i_currency(); ?> <?php the_invoice_tax(); ?></td>
                </tr>
                <?php endif; ?>
                
                <tr class="heading">
                	<td colspan="3"></td><td class="total">Total</td><td class="total"><?php wp3i_currency(); ?> <?php the_invoice_total(); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="payment-details">
        	<fieldset class="last">
            	<legend>Payment Details</legend>
                <?php if(get_invoice_type() == 'Invoice' ): ?>
                	<p><strong>Bank: </strong>XXXXXXX</p>
                    <p><strong>Acc Name: </strong>XXXXXXX XXXXXXX</p>
                    <p><strong>Acc BSB: </strong>XXX-XXX</p>
                    <p><strong>Acc Number: </strong>XX-XXX-XXXX</p>
        		<?php else: ?>
                	<p><strong>This is a project quote, not an invoice.</strong></p>
                    <p>No payment is required.<br /> Hope to hear from you soon.</p>
                <?php endif; ?>
            </fieldset>
        </p>
        
        <?php if(get_invoice_type() == 'Invoice' ): ?>
            <p class="credits">
        		IMPORTANT: The above invoice must be payed by Electronic Funds Transfer. Payment is due within 30 days from the date in this invoice. 
                Late payment is subject to a fee of 5% per month.
			</p>
        <?php endif; ?>
        
        
    </div>
    
<?php endwhile; endif; ?>

</body>
</html>