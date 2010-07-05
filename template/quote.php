<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

    <title>Invoice #<?php invoice_number(); ?> | <?php bloginfo('name'); ?> | <?php the_title(); ?></title>

    <link rel="stylesheet" href="<?php invoice_template_url(); ?>/style.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php invoice_template_url(); ?>/style-print.css" type="text/css" media="print" />

    <?php wp_get_archives('type=monthly&format=link'); ?>
    <?php wp_head(); ?>
</head>

<body>
	
    <div class="container">
    	<div class="header">
        	<table cellpadding="0" cellspacing="0" align="left">
            	<tr>
                	<td><img src="<?php invoice_template_url(); ?>/images/wp3i-logo.jpg"/></td>
                	<td>
                    <h3>WordPress3 <strong>Invoices</strong></h3>
                    <p>(XX) XXXX XXXX</p>
                    <p><a href="mailto:test@test.com">test@test.com</a></p>
                    <p><a href="http://www.test.com">www.test.com</a></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="info">
            <h4><span>To:</span> <?php invoice_client(); ?></h4>
            <h4><span>Project:</span> <?php the_title(); ?></h4>
            <h4><span>Date:</span> <?php the_time('d/m/Y'); ?></h4>
            <h4><span>Invoice Number:</span> <?php invoice_number(); ?></h4>
        </div>
        
        <div class="breakdown">
        	<table cellpadding="0" cellspacing="0">
            	<tr class="heading">
                	<td>Project Breakdown</td><td>Type</td><td>Rate</td><td>Hours</td><td style="width:75px;">Subtotal</td>
                </tr>
				<?php if(invoice_has_details()): ?>
                    <?php while(invoice_detail()): ?>
                            <tr class="title">
                                <td><?php the_detail_title(); ?></td><td><?php the_detail_type(); ?></td><td>$ <?php the_detail_rate(); ?></td><td><?php the_detail_duration(); ?></td><td>$ <?php the_detail_subtotal(); ?></td>
                            </tr>
                            <tr class="description">
                                <td><?php the_detail_description(); ?></td><td colspan="4"></td>
                            </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
                <tr class="heading">
                	<td colspan="3"></td><td>Total</td><td>$ <?php the_invoice_total(); ?></td>
                </tr>
            </table>
        </div>
        

            
        <div class="payment-details">
            <table cellpadding="0" cellspacing="0">
            	<tr>
                	<td class="label">
                    	This is a project quote, not an invoice.
                        No payment is required.<br /><br />
                        Hope to hear from you soon.<br /><br />
					</td>
                </tr>
            </table>
        </div>
        
        <p class="credits">
        	IMPORTANT: The above invoice must be payed by Electronic Funds Transfer. Payment is due within 30 days from the date in this invoice. Late payment is subject to a fee of 5% per month.<br />
        </p>
        
        
        
    </div>

</body>
</html>