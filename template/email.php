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
		p {font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:16px; color:#000;}
		
		
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

		
		.container {background-color:#FFF; border:#CCC solid 1px; text-align:left;}
		
		
		
		/* Header
		-------------------------------------------------*/
		.header {padding-bottom:60px; overflow:hidden;}
		.header img {padding-right:10px;}
		
		.header h3 {font-weight:400; font-size:24px; line-height:24px;}
		.header h3 strong {font-weight:700;}
		.header p {font-size:10px; line-height:12px;}
		.header p a {color:#999;}
		.header p a:hover {text-decoration:underline;}
		
		
		/* Info
		-------------------------------------------------*/
		.info {padding-bottom:60px; overflow:hidden;}
		.info h4{font-weight:bold; font-size:12px; line-height:12px; margin-bottom:4px;}
		.info h4 span {color:#00b7f2;}
		
		
		
		/* Project Breakdown
		-------------------------------------------------*/
		.breakdown {padding-bottom:44px; width:100%;}
		.breakdown tr {}
		.breakdown tr td { padding:5px 5px; color:#999; font-size:12px; line-height:12px;}
		.breakdown tr.heading td {color:#00b7f2; text-transform:uppercase; font-size:14px; line-height:14px; padding:7px 5px; font-weight:bold;}
		.breakdown tr.title td {background-color:#f5f5f5!important; color:#616161; font-weight:bold; }
		.breakdown tr.description td {font-size:10px; line-height:12px;}
		

		/* Payment Details
		-------------------------------------------------*/
		.payment-details {width:250px; padding:20px 0px 20px 15px; background-color:#f6f6f6; }
		.payment-details table tr td.label {color:#999; }
		.payment-details table tr td {font-size:11px; line-height:11px; color:#333; padding:3px 5px;}
		
		
		/* Credits
		-------------------------------------------------*/
		p.credits {margin-top:60px; font-size:10px; line-height:14px; color:#999;}
		
    </style>
	<?php wp_get_archives('type=monthly&format=link'); ?>
    <?php wp_head(); ?>
</head>
<body>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <table width="100%" cellspacing="0" cellpadding="20">
    <tr>
    <td>
        <p>
            Dear <?php invoice_client(); ?>, <br>
            <br>
            Please find your <?php invoice_type() ?> for <?php the_title(); ?><br>
            <br>
            Best regards, Elliot
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
        <td align="left" valign="top" class="mainbar">
        	
            <table cellpadding="0" cellspacing="0" class="header">
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
            
            <table cellpadding="0" cellspacing="0" class="info">
           	<tr>
            <td>
                <h4><span>To:</span> <?php invoice_client(); ?></h4>
                <h4><span>Project:</span> <?php the_title(); ?></h4>
                <h4><span>Date:</span> <?php the_time('d/m/Y'); ?></h4>
                <h4><span><?php invoice_type(); ?> Number:</span> <?php invoice_number(); ?></h4>
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
                	<td colspan="3"></td><td>Total</td><td><?php wp3i_currency(); ?> <?php the_invoice_total(); ?></td>
                </tr>
            </table>
            
            <table cellpadding="0" cellspacing="0" class="payment-details">
            	<?php if(get_invoice_type() == 'Invoice' ): ?>
                    <tr>
                        <td class="label">Bank</td><td>XXXXXXX</td>
                    </tr>
                    <tr>
                        <td class="label">Acc Name</td><td>XXXXXXX XXXXXXX</td>
                    </tr>
                    <tr>
                        <td class="label">Acc BSB</td><td>XXX-XXX</td>
                    </tr>
                    <tr>
                        <td class="label">Acc Number</td><td>XX-XXX-XXXX</td>
                    </tr>
				<?php else: ?>
                    <tr>
                        <td class="label">
                            This is a project quote, not an invoice.
                            No payment is required.<br /><br />
                            Hope to hear from you soon.<br /><br />
                        </td>
                    </tr>
				<?php endif; ?>
            </table>
            
            <?php if(get_invoice_type() == 'Invoice' ): ?>
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

</body>
</html>
