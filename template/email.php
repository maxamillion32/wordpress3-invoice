<?php
	// Please remember email clients don't read <div> tags. Only tables. Enjoy.
?>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php invoice_type(); ?> #<?php invoice_number(); ?> | <?php bloginfo('name'); ?> | <?php the_title(); ?></title>
	<style type="text/css" media="screen">
		/*-----------------------------------------------------------------------------------------------------------------------------
 		Reset default browser CSS. Based on work by Eric Meyer: http://meyerweb.com/eric/tools/css/reset/index.html
		-----------------------------------------------------------------------------------------------------------------------------*/
		html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label { margin: 0; padding: 0; border: 0; vertical-align: baseline; background: transparent; color:#353535; font-family:Arial, Helvetica, Tahoma, Geneva, sans-serif; }
		body { line-height: 1; }
		h1, h2, h3, h4, h5, h6 { font-weight: normal; clear: both; }
		ol, ul { list-style: none; }
		blockquote { quotes: none; }
		blockquote:before, blockquote:after { content: ''; content: none; }
		del { text-decoration: line-through; }
		table, tr, td {vertical-align:top; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:14px;} 
		a img { border: none; cursor:pointer; }
		a {text-decoration:none;}
		p {font-family:Arial, Helvetica, sans-serif; font-size:11px; line-height:14px; color:#999; margin-top:2px;}
		
		
		/* Global
		-------------------------------------------------*/
		h1 { font-weight:bold; font-size:72px; line-height:72px;}
		h2 { font-weight:bold; font-size:18px; line-height:18px; position:relative; margin-top:-18px;}
		body { background-color: #ffffff; margin: 0; padding: 0; font-family:Arial, Helvetica, sans-serif; }
		a img { border: none; }
		table, tr, td {vertical-align:top;}
		
		.body {padding:30px 0px; border-top:#CCC solid 1px; border-bottom:#CCC solid 1px;}
		
		.meta { font-family: 'Lucida Grande'; color: #666666; padding-bottom: 20px; }
		.meta p { color: #666666; font-size:11px;}
		.meta p a { color: #333333; text-decoration:underline;}

		.container {background-color:#FFF; border:#DDDFDF solid 1px; text-align:left;}
		.greeting p {color:#000; font-size:12px; line-height:16px;}
		
		
		/* Header
		-------------------------------------------------*/
		.header {}
		.header img {padding-right:20px; position:relative;}
		
		.header h3 {font-weight:400; font-size:24px; line-height:24px;}
		.header h3 strong {font-weight:700;}
		.header p {font-size:10px; line-height:12px;}
		.header p a {color:#999;}
		.header p a:hover {text-decoration:underline;}
		
		
		/* Info
		-------------------------------------------------*/
		.info {padding-top:60px; overflow:hidden; clear:both;}
		
		fieldset {border:1px solid #F4F4F4; padding:10px; width:238px; }
		fieldset legend {display:block; padding:0px 5px; color:#0091db; font-size:12px;}
		fieldset p {font-size:11px; margin-bottom:5px;}
		fieldset p.hidden {display:none;}
		fieldset p strong {min-width:120px;}
		
		
		
		
		/* Project Breakdown
		-------------------------------------------------*/
		.breakdown {padding-top:60px; width:100%}
		.breakdown tr {}
		.breakdown tr td { padding:5px 5px; color:#999; font-size:12px; line-height:12px;}
		.breakdown tr.heading td {color:#0091db; text-transform:uppercase; font-size:14px; line-height:14px; padding:7px 5px; font-weight:bold; }
		.breakdown tr.title td {background-color:#f5f5f5!important; color:#616161; font-weight:bold; }
		.breakdown tr.description td {font-size:10px; line-height:12px;}
		.breakdown tr.heading td.total {background-color:#0091db; color:#FFF;}
		

		/* Payment Details
		-------------------------------------------------*/
		.payment-details {padding-top:60px;}
		
		
		/* Credits
		-------------------------------------------------*/
		p.credits {padding-top:60px; font-size:10px; line-height:14px; color:#999;}
		
    </style>
	<?php wp_get_archives('type=monthly&format=link'); ?>
    <?php wp_head(); ?>
</head>
<body>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <table width="100%" cellspacing="0" cellpadding="20">
    <tr>
    <td class="greeting">
        <p>
            Dear <?php invoice_client(); ?>, <br>
            <br>
            Please find your <?php invoice_type() ?> for <?php the_title(); ?><br>
            <br>
            Best regards, WordPress 3 Invoice
        </p>
    </td>
    </tr>
    </table>
    
	<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#F0F0F0" class="body">
    <tr>
    <td align="center">
    	
        <table width="600" cellspacing="0" cellpadding="0">
        <tr>
        <td align="center" class="meta">
        	<p>You can view this <?php invoice_type() ?> online <a href="<?php the_permalink(); ?>">here</a>.</p>
        </td>
        </tr>
        </table>
        
        <table width="600" cellspacing="0" cellpadding="30" class="container" bgcolor="#FFFFFF">
        <tr>
        <td align="left" valign="top">
        	
            <table cellpadding="0" cellspacing="0" class="header">
           	<tr>
            	<td><img src="<?php invoice_template_url(); ?>/images/wp3i-logo.png" style="margin-top:-35px;"/></td>
                <td>
                    <h3>WordPress3 <strong>Invoice</strong></h3>
                    <p>Created by Elliot Condon</p>
                    <p><a href="mailto:you@youremailhere.com">you@youremailhere.com</a></p>
                    <p><a href="www.yourwebsitehere.com">www.yourwebsitehere.com</a></p>
                </td>
            </tr>
            </table>
            
            <table cellpadding="0" cellspacing="0" class="info">
           	<tr>
            <td>
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
            </td>
            <td style="padding-left:20px;">
                <fieldset class="last">
                    <legend><?php invoice_type(); ?> Details</legend>
                    <p><strong>Project: </strong><?php the_title(); ?></p>
                    <p><strong><?php invoice_type(); ?> Number: </strong><?php invoice_number(); ?></p>
                    <p><strong>Date Issued: </strong><?php the_time('d/m/Y'); ?></p>
                </fieldset>
            </td>
            </tr>
            </table>
        
            <table cellpadding="0" cellspacing="0" class="breakdown">
            	<tr class="heading">
                	<td>Project Breakdown</td><td>Type</td><td>Rate</td><td>Hours</td><td style="width:75px;">Subtotal</td>
                </tr>
				<?php if(invoice_has_details()): ?>
                    <?php while(invoice_detail()): ?>
                            <tr class="title">
                                <td><?php the_detail_title(); ?></td><td><?php the_detail_type(); ?></td><td><?php the_detail_rate(); ?></td><td><?php the_detail_duration(); ?></td><td><?php the_detail_subtotal(); ?></td>
                            </tr>
                            <tr class="description">
                                <td><?php the_detail_description(); ?></td><td colspan="4"></td>
                            </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
                
                <?php if(wp3i_has_tax()):  ?>
                <tr class="heading">
                	<td colspan="3"></td><td>Subtotal</td><td><?php the_invoice_subtotal(); ?></td>
                </tr>
                <tr class="heading">
                    <td colspan="3"></td><td>Tax</td><td><?php the_invoice_tax(); ?></td>
                </tr>
                <?php endif; ?>
                
                <tr class="heading">
                	<td colspan="3"></td><td class="total">Total</td><td class="total"><?php the_invoice_total(); ?></td>
                </tr>
            </table>
            
            <table cellpadding="0" cellspacing="0" class="payment-details">
            <tr>
            <td>
            	<fieldset class="last">
            	<legend>Payment Details</legend>
                <?php if(get_invoice_type() == __('Invoice','wp3i') ): ?>
                	<p><strong>Bank: </strong>XXXXXXX</p>
                    <p><strong>Acc Name: </strong>XXXXXXX XXXXXXX</p>
                    <p><strong>Acc BSB: </strong>XXX-XXX</p>
                    <p><strong>Acc Number: </strong>XX-XXX-XXXX</p>
        		<?php else: ?>
                	<p><strong>This is a project quote, not an invoice.</strong></p>
                    <p>No payment is required.<br /> Hope to hear from you soon.</p>
                <?php endif; ?>
            	</fieldset>
                </td>
                </tr>
            </table>
            
            <?php if(get_invoice_type() == __('Invoice','wp3i') ): ?>
                <p class="credits">
                    IMPORTANT: The above invoice must be payed by Electronic Funds Transfer. Payment is due within 30 days from the date in this invoice. 
                    Late payment is subject to a fee of 5% per month.
                </p>
            <?php endif; ?>
            
        </td>
        </tr>
        </table>
        
    </td>
    </tr>
	</table>
    
<?php endwhile; endif; ?>
<?php wp_footer(); ?>
</body>
</html>
