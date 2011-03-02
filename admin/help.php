<?php 
class Help
{
	var $name;
	var $dir;
	var $plugin_dir;
	var $plugin_path;
	var $version;

	/**
	 * Help Constructor
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 * @param object: Wp3i to find parent variables.
	 **/
	function Help($parent)
	{
		$this->name = $parent->name;					// Plugin Name
		$this->plugin_dir = $parent->dir;				// Plugin directory
		$this->plugin_path = $parent->path;				// Plugin Absolute Path
		$this->dir = plugins_url('/',__FILE__);			// This directory
		$this->version = $parent->version;
		return true;	
	}
	
	function admin_page()
	{
		?>
	
	<div class="wrap wp3i help"> 
        <div class="wp3i-heading">
            <div class="icon32" id="icon-wp3i"><br></div>
            <h2><?php _e('WordPress 3 Invoice Help','wp3i'); ?></h2>
            <p><?php _e('Topics','wp3i'); ?>: <a href="#creating-an-invoice"><?php _e('Creating an Invoice','wp3i'); ?></a> &nbsp;<a href="#invoice-passwords"><?php _e('Invoice Passwords','wp3i'); ?></a> &nbsp;<a href="#managing-clients"><?php _e('Managing Clients','wp3i'); ?></a> &nbsp;<a href="#custom-templates"><?php _e('Custom Templates','wp3i'); ?></a> &nbsp;<a href="#template-api"><?php _e('Template API','wp3i'); ?></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php _e('Help','wp3i'); ?>: <a href="http://support.plugins.elliotcondon.com/categories/wordpress-3-invoice"><?php _e('Support Forum','wp3i'); ?></a></p>
        </div>
		<div id="poststuff">
			
            <div class="left-column">
            
            <a name="creating-an-invoice"></a>
            <div class="postbox">
                <h3 class="hndle"><span><?php _e('Creating an Invoice','wp3i'); ?></span></h3>
                <div class="inside">
                	<iframe height="430" width="720" frameborder="0" src="http://www.wordpress3invoice.com/help-videos/creating-an-invoice.html">
                    </iframe>
                    <p><?php _e('Creating an Invoice is simple, enter a name, input data, enter your breakdown costs, select a client and click Publish!','wp3i'); ?></p>
                </div>
            </div>
            
            <a name="invoice-passwords"></a>
            <div class="postbox">
                <h3 class="hndle"><span><?php _e('Invoice Passwords','wp3i'); ?></span></h3>
                <div class="inside">
                	<iframe height="430" width="720" frameborder="0" src="http://www.wordpress3invoice.com/help-videos/invoice-passwords.html">
                    </iframe>
                    <p><?php _e('v2.0.0 now includes Invoice password privacy! Passwords will inherit from the client, but can be manually set.','wp3i'); ?></p>
                </div>
            </div>
            
            <a name="managing-clients"></a>
            <div class="postbox">
                <h3 class="hndle"><span><?php _e('Managing Clients','wp3i'); ?></span></h3>
                <div class="inside">
                	<iframe height="430" width="720" frameborder="0" src="http://www.wordpress3invoice.com/help-videos/managing-clients.html">
                    </iframe>
                    <p><?php _e('Clients can hold lots of useful data which can be extracted for use in your invoice template.','wp3i'); ?></p>
                    <ul>
                    	<li><?php _e('Adding an email address enables Invoice emailing','wp3i'); ?></li>
                        <li><?php _e('Passwords will automatically populate the password field in invoice visibility','wp3i'); ?></li>
                    </ul>
                </div>
            </div>
            
            <a name="custom-templates"></a>
            <div class="postbox">
                <h3 class="hndle"><span><?php _e('Custom Templates','wp3i'); ?></span></h3>
                <div class="inside">
                	<iframe height="430" width="720" frameborder="0" src="http://www.wordpress3invoice.com/help-videos/custom-templates.html">
                    </iframe>
                    <p><?php _e('Invoices can run off custom templates located inside your theme.','wp3i'); ?></p>
                    <ul>
                    	<li><?php _e('1. Copy the template folder to your current theme','wp3i'); ?></li>
                        <li><?php _e('2. Rename the folder "invoice"','wp3i'); ?></li>
                        <li><?php _e('3. View the Template API for functions','wp3i'); ?></li>
                    </ul>
                </div>
            </div>
            
            <a name="template-api"></a>
            <div class="postbox">
					<h3 class="hndle"><span><?php _e('WordPress 3 Invoice Template API','wp3i'); ?></span></h3>
					<div class="inside">
						<table cellpadding="0" cellspacing="0" class="api">
							<tr class="odd">
								<td><h4>invoice_type()</h4><h6>+ get_invoice_type()</h6></td>
								<td><p><?php _e('Displays the current invoice type (Invoice or Quote)','wp3i'); ?></p></td>
								<td width="85"><p><?php _e('Invoice','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>invoice_number()</h4><h6>+ get_invoice_number()</h6></td>
								<td><p><?php _e('Displays the current invoice number','wp3i'); ?></p></td>
								<td><p><?php _e('Invoice','wp3i'); ?></p></td>
							</tr>
							<tr class="odd">
								<td><h4>invoice_template_url()</h4><h6>+ get_invoice_template_url()</h6></td>
								<td><p><?php _e('Displays the url of your invoice template folder.<br />
										This function will first look in your WordPress theme folder for a folder called "invoice" and a file named "invoice.php". If no folder or file is found, the default template files (found in the plugin template folder) will be used','wp3i'); ?></p></td>
								<td><p><?php _e('Invoice','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>bloginfo('name')</h4><h6>+ get_bloginfo('name')</h6></td>
								<td><p><?php _e('Displays the name of your blog','wp3i'); ?></p></td>
								<td><p><?php _e('WordPress','wp3i'); ?></p></td>
							</tr>
							<tr class="odd">
								<td><h4>the_title()</h4><h6>+ get_the_title()</h6></td>
								<td><p><?php _e('Displays the current invoice title','wp3i'); ?></p></td>
								<td><p><?php _e('WordPress','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>the_content()</h4><h6>+ get_the_content()</h6></td>
								<td><p><?php _e('Displays the current invoice content','wp3i'); ?></p></td>
								<td><p><?php _e('WordPress','wp3i'); ?></p></td>
							</tr>
							<tr class="odd">
								<td><h4>the_time('d/m/Y')</h4></td>
								<td><p><?php _e('Displays the date when the invoice was created. (Not when it was sent or paid) <a href="http://codex.wordpress.org/Formatting_Date_and_Time">Read more about formating date and time here</a>','wp3i'); ?></p></td>
								<td><p><?php _e('WordPress','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>if(invoice_has_details()):</h4></td>
								<td><p><?php _e('Loads the details into an array to use in the detail loop','wp3i'); ?></p></td>
								<td><p><?php _e('Invoice Detail','wp3i'); ?></p></td>
							</tr>
							<tr class="odd">
								<td><h4>while(invoice_detail()):</h4></td>
								<td><p><?php _e('Loops through the details for the current invoice','wp3i'); ?></p></td>
								<td><p><?php _e('Invoice Detail','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>the_detail_title()</h4><h6>+ get_the_detail_title()</h6></td>
								<td><p><?php _e('Displays the detail title','wp3i'); ?></p></td>
								<td><p><?php _e('Invoice Detail','wp3i'); ?></p></td>
							</tr>
							<tr class="odd">
								<td><h4>the_detail_type()</h4><h6>+ get_the_detail_type()</h6></td>
								<td><p><?php _e('Displays the detail type','wp3i'); ?></p></td>
								<td><p><?php _e('Invoice Detail','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>the_detail_rate()</h4><h6>+ get_the_detail_rate()</h6></td>
								<td><p><?php _e('Displays the detail rate','wp3i'); ?></p></td>
								<td><p><?php _e('Invoice Detail','wp3i'); ?></p></td>
							</tr>
							<tr class="odd">
								<td><h4>the_detail_duration()</h4><h6>+ get_the_detail_duration()</h6></td>
								<td><p><?php _e('Displays the detail duration','wp3i'); ?></p></td>
								<td><p><?php _e('Invoice Detail','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>the_detail_subtotal()</h4><h6>+ get_the_detail_subtotal()</h6></td>
								<td><p><?php _e('Displays the detail subtotal','wp3i'); ?></p></td>
								<td><p><?php _e('Invoice Detail','wp3i'); ?></p></td>
							</tr>
							<tr class="odd">
								<td><h4>wp3i_currency()</h4><h6>+ wp3i_get_currency()</h6></td>
								<td><p><?php _e('Displays the chosen currency','wp3i'); ?></p></td>
								<td><p><?php _e('Option','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>wp3i_has_tax()</h4></td>
								<td><p><?php _e('Returns true if tax is set above 0.00','wp3i'); ?></p></td>
								<td><p><?php _e('Option','wp3i'); ?></p></td>
							</tr>
							<tr class="odd">
								<td><h4>the_invoice_subtotal()</h4><h6>+ get_the_invoice_subtotal()</h6></td>
								<td><p><?php _e('Displays the invoice subtotal amount','wp3i'); ?></p></td>
								<td><p><?php _e('Invoice','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>the_invoice_tax()</h4><h6>+ get_the_invoice_tax()</h6></td>
								<td><p><?php _e('Displays the amount off tax (subtotal * tax)','wp3i'); ?></p></td>
								<td><p><?php _e('Invoice','wp3i'); ?></p></td>
							</tr>
							<tr class="odd">
								<td><h4>the_invoice_total()</h4><h6>+ get_the_invoice_total()</h6></td>
								<td><p><?php _e('Displays the current invoice total (subtotal + tax)','wp3i'); ?></p></td>
								<td><p><?php _e('Invoice','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>invoice_client()</h4><h6>+ get_invoice_client_name()</h6></td>
								<td><p><?php _e('Displays the client name for the current invoice','wp3i'); ?></p></td>
								<td><p><?php _e('Client','wp3i'); ?></p></td>
							</tr>
							<tr class="odd">
								<td><h4>invoice_client_description()</h4><h6>+ get_invoice_client_description()</h6></td>
								<td><p><?php _e('Displays the client Description','wp3i'); ?></p></td>
								<td><p><?php _e('Client','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>invoice_client_email()</h4><h6>+ get_invoice_client_email()</h6></td>
								<td><p><?php _e('Displays the client Email Address','wp3i'); ?></p></td>
								<td><p><?php _e('Client','wp3i'); ?></p></td>
							</tr>
							<tr class="odd">
								<td><h4>invoice_client_business()</h4><h6>+ get_invoice_client_business()</h6></td>
								<td><p><?php _e('Displays the client\'s business name','wp3i'); ?></p></td>
								<td><p><?php _e('Client','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>invoice_client_business_address()</h4><h6>+ get_invoice_client_business_address()</h6></td>
								<td><p><?php _e('Displays the client\'s business address','wp3i'); ?></p></td>
								<td><p><?php _e('Client','wp3i'); ?></p></td>
							</tr>
							<tr class="odd">
								<td><h4>invoice_client_phone()</h4><h6>+ get_invoice_client_phone()</h6></td>
								<td><p><?php _e('Displays the client\'s Phone Number','wp3i'); ?></p></td>
								<td><p><?php _e('Client','wp3i'); ?></p></td>
							</tr>
							<tr>
								<td><h4>invoice_client_number()</h4><h6>+ get_invoice_client_number()</h6></td>
								<td><p><?php _e('Displays the client\'s (VAT or other) Number','wp3i'); ?></p></td>
								<td><p><?php _e('Client','wp3i'); ?></p></td>
							</tr>
                            <tr class="odd">
								<td><h4>wp3i_payment_gateway_button()</h4></td>
								<td><p><?php _e('Display\'s the chosen payment gateway button. Good for email template','wp3i'); ?></p></td>
								<td><p><?php _e('Gateway','wp3i'); ?></p></td>
							</tr>
						</table>
					</div>
				</div>
				</div>
                
                <div class="right-column">
					
                    <div class="buttons">
                    	<table cellpadding="0" cellspacing="0">
						<tr>
						<td>
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="U8VV93Z7WXYJA">
                                <input type="image" border="0" name="submit" alt="PayPal - The safer, easier way to pay online." class="wp3i-button donate">
                                <img alt="" border="0" src="https://www.paypal.com/en_AU/i/scr/pixel.gif" width="1" height="1">
                            </form>
						</td>
						<td>
							<a href="http://wordpress.org/extend/plugins/wordpress3-invoice/" class="wp3i-button rate"></a>
						</td>
						</tr>
						</table>
                    </div>
						
                <div class="postbox twitter">
					<div class="inside">
						<script src="http://widgets.twimg.com/j/2/widget.js"></script>
						<script>
                        new TWTR.Widget({
                          version: 2,
                          type: 'profile',
                          rpp: 5,
                          interval: 6000,
                          width: 'auto',
                          height: 300,
                          theme: {
                            shell: {
                              background: '#dfdfdf',
                              color: '#666666'
                            },
                            tweets: {
                              background: '#ffffff',
                              color: '#333333',
                              links: '#21749b'
                            }
                          },
                          features: {
                            scrollbar: true,
                            loop: false,
                            live: false,
                            hashtags: true,
                            timestamp: true,
                            avatars: false,
                            behavior: 'all'
                          }
                        }).render().setUser('wp3i').start();
                        </script>
					</div>
				</div>
                
				<div class="postbox" id="new">
					<h3 class="hndle"><span><?php _e('New in ','wp3i'); ?>v<?php echo $this->version;?></span></h3>
					<div class="inside">
						<ul>
							<li>Fixed WP 3.1 Column Bug</li>
							<li>Included PayPal payment gateway!</li>
                            <li>Opened Support forum <a href="http://support.plugins.elliotcondon.com/categories/wordpress-3-invoice">here</a></li>
						</ul>
					</div>
				</div>
                
                
                
			</div> <!--ends right column-->
            
		</div>
	</div>
	<?php
	}
	
}

?>