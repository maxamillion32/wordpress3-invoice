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
            <h2>WordPress 3 Invoice Help</h2>
            <p>Topics: <a href="#creating-an-invoice">Creating an Invoice</a> &nbsp;<a href="#invoice-passwords">Invoice Passwords</a> &nbsp;<a href="#managing-clients">Managing Clients</a> &nbsp;<a href="#custom-templates">Custom Templates</a> &nbsp;<a href="#template-api">Template API</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Help: <a href="http://www.wordpress3invoice.com/support/">Support</a> &nbsp;<a href="http://www.wordpress3invoice.com/developers/">Developers</a></p>
        </div>
		<div id="poststuff">
			
            <div class="left-column">
            
            <a name="creating-an-invoice"></a>
            <div class="postbox">
                <h3 class="hndle"><span>Creating an Invoice</span></h3>
                <div class="inside">
                	<iframe height="430" width="720" frameborder="0" src="http://www.wordpress3invoice.com/help-videos/creating-an-invoice.html">
                    </iframe>
                    <p>Creating an Invoice is simple, enter a name, input data, enter your breakdown costs, select a client and click Publish!</p>
                </div>
            </div>
            
            <a name="invoice-passwords"></a>
            <div class="postbox">
                <h3 class="hndle"><span>Invoice Passwords</span></h3>
                <div class="inside">
                	<iframe height="430" width="720" frameborder="0" src="http://www.wordpress3invoice.com/help-videos/invoice-passwords.html">
                    </iframe>
                    <p>v2.0.0 now includes Invoice password privacy! Passwords will inherit from the client, but can be manually set.</p>
                </div>
            </div>
            
            <a name="managing-clients"></a>
            <div class="postbox">
                <h3 class="hndle"><span>Managing Clients</span></h3>
                <div class="inside">
                	<iframe height="430" width="720" frameborder="0" src="http://www.wordpress3invoice.com/help-videos/managing-clients.html">
                    </iframe>
                    <p>Clients can hold lots of useful data which can be extracted for use in your invoice template.</p>
                    <ul>
                    	<li>Adding an email address enables Invoice emailing</li>
                        <li>Passwords will automatically populate the password field in invoice visibility</li>
                    </ul>
                </div>
            </div>
            
            <a name="custom-templates"></a>
            <div class="postbox">
                <h3 class="hndle"><span>Custom Templates</span></h3>
                <div class="inside">
                	<iframe height="430" width="720" frameborder="0" src="http://www.wordpress3invoice.com/help-videos/custom-templates.html">
                    </iframe>
                    <p>Invoices can run off custom templates located inside your theme.</p>
                    <ul>
                    	<li>1. Copy the template folder to your current theme</li>
                        <li>2. Rename the folder "invoice"</li>
                        <li>3. View the Template API for functions</li>
                    </ul>
                </div>
            </div>
            
            <a name="template-api"></a>
            <div class="postbox">
					<h3 class="hndle"><span>WordPress 3 Invoice Template API</span></h3>
					<div class="inside">
						<table cellpadding="0" cellspacing="0" class="api">
							<tr class="odd">
								<td><h4>invoice_type()</h4><h6>+ get_invoice_type()</h6></td>
								<td><p>Displays the current invoice type (Invoice or Quote)</p></td>
								<td width="85"><p>Invoice</p></td>
							</tr>
							<tr>
								<td><h4>invoice_number()</h4><h6>+ get_invoice_number()</h6></td>
								<td><p>Displays the current invoice number</p></td>
								<td><p>Invoice</p></td>
							</tr>
							<tr class="odd">
								<td><h4>invoice_template_url()</h4><h6>+ get_invoice_template_url()</h6></td>
								<td><p>Displays the url of your invoice template folder.<br />
										This function will first look in your WordPress theme folder for a folder called 'invoice' and a file named 'invoice.php'. If no folder or file is found, the default template files (found in the plugin template folder) will be used</p></td>
								<td><p>Invoice</p></td>
							</tr>
							<tr>
								<td><h4>bloginfo('name')</h4><h6>+ get_bloginfo('name')</h6></td>
								<td><p>Displays the name of your blog</p></td>
								<td><p>WordPress</p></td>
							</tr>
							<tr class="odd">
								<td><h4>the_title()</h4><h6>+ get_the_title()</h6></td>
								<td><p>Displays the current invoice title</p></td>
								<td><p>WordPress</p></td>
							</tr>
							<tr>
								<td><h4>the_content()</h4><h6>+ get_the_content()</h6></td>
								<td><p>Displays the current invoice content</p></td>
								<td><p>WordPress</p></td>
							</tr>
							<tr class="odd">
								<td><h4>the_time('d/m/Y')</h4></td>
								<td><p>Displays the date when the invoice was created. (Not when it was sent or paid) <a href="http://codex.wordpress.org/Formatting_Date_and_Time">Read more about formating date and time here</a></p></td>
								<td><p>WordPress</p></td>
							</tr>
							<tr>
								<td><h4>if(invoice_has_details()):</h4></td>
								<td><p>Loads the details into an array to use in the detail loop</p></td>
								<td><p>Invoice Detail</p></td>
							</tr>
							<tr class="odd">
								<td><h4>while(invoice_detail()):</h4></td>
								<td><p>Loops through the details for the current invoice</p></td>
								<td><p>Invoice Detail</p></td>
							</tr>
							<tr>
								<td><h4>the_detail_title()</h4><h6>+ get_the_detail_title()</h6></td>
								<td><p>Displays the detail title</p></td>
								<td><p>Invoice Detail</p></td>
							</tr>
							<tr class="odd">
								<td><h4>the_detail_type()</h4><h6>+ get_the_detail_type()</h6></td>
								<td><p>Displays the detail type</p></td>
								<td><p>Invoice Detail</p></td>
							</tr>
							<tr>
								<td><h4>the_detail_rate()</h4><h6>+ get_the_detail_rate()</h6></td>
								<td><p>Displays the detail rate</p></td>
								<td><p>Invoice Detail</p></td>
							</tr>
							<tr class="odd">
								<td><h4>the_detail_duration()</h4><h6>+ get_the_detail_duration()</h6></td>
								<td><p>Displays the detail duration</p></td>
								<td><p>Invoice Detail</p></td>
							</tr>
							<tr>
								<td><h4>the_detail_subtotal()</h4><h6>+ get_the_detail_subtotal()</h6></td>
								<td><p>Displays the detail subtotal</p></td>
								<td><p>Invoice Detail</p></td>
							</tr>
							<tr class="odd">
								<td><h4>wp3i_currency()</h4><h6>+ wp3i_get_currency()</h6></td>
								<td><p>Displays the chosen currency</p></td>
								<td><p>Option</p></td>
							</tr>
							<tr>
								<td><h4>wp3i_has_tax()</h4></td>
								<td><p>Returns true if tax is set above 0.00</p></td>
								<td><p>Option</p></td>
							</tr>
							<tr class="odd">
								<td><h4>the_invoice_subtotal()</h4><h6>+ get_the_invoice_subtotal()</h6></td>
								<td><p>Displays the invoice subtotal amount</p></td>
								<td><p>Invoice</p></td>
							</tr>
							<tr>
								<td><h4>the_invoice_tax()</h4><h6>+ get_the_invoice_tax()</h6></td>
								<td><p>Displays the amount off tax (subtotal * tax)</p></td>
								<td><p>Invoice</p></td>
							</tr>
							<tr class="odd">
								<td><h4>the_invoice_total()</h4><h6>+ get_the_invoice_total()</h6></td>
								<td><p>Displays the current invoice total (subtotal + tax)</p></td>
								<td><p>Invoice</p></td>
							</tr>
							<tr>
								<td><h4>invoice_client()</h4><h6>+ get_invoice_client_name()</h6></td>
								<td><p>Displays the client name for the current invoice</p></td>
								<td><p>Client</p></td>
							</tr>
							<tr class="odd">
								<td><h4>invoice_client_description()</h4><h6>+ get_invoice_client_description()</h6></td>
								<td><p>Displays the client Description</p></td>
								<td><p>Client</p></td>
							</tr>
							<tr>
								<td><h4>invoice_client_email()</h4><h6>+ get_invoice_client_email()</h6></td>
								<td><p>Displays the client Email Address</p></td>
								<td><p>Client</p></td>
							</tr>
							<tr class="odd">
								<td><h4>invoice_client_business()</h4><h6>+ get_invoice_client_business()</h6></td>
								<td><p>Displays the client's business name</p></td>
								<td><p>Client</p></td>
							</tr>
							<tr>
								<td><h4>invoice_client_business_address()</h4><h6>+ get_invoice_client_business_address()</h6></td>
								<td><p>Displays the client's business address</p></td>
								<td><p>Client</p></td>
							</tr>
							<tr class="odd">
								<td><h4>invoice_client_phone()</h4><h6>+ get_invoice_client_phone()</h6></td>
								<td><p>Displays the client's Phone Number</p></td>
								<td><p>Client</p></td>
							</tr>
							<tr>
								<td><h4>invoice_client_number()</h4><h6>+ get_invoice_client_number()</h6></td>
								<td><p>Displays the client's (VAT or other) Number</p></td>
								<td><p>Client</p></td>
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
					<h3 class="hndle"><span>New in 2.0.1</span></h3>
					<div class="inside">
						<ul>
							<li>Invoice archive is now a blank page</li>
							<li>Added currency formating throughout wp3i</li>
							<li>New Currency drop down list on options page</li>
							<li>New Twitter feed on Help page (stay updated!)</li>
                            <li>General Housekeeping</li>
                            <li>Added Gateway folder (<a href="http://www.wordpress3invoice.com/shop/">PayPal gateway</a> $1.00)</li>
                            <li>Added backup and restore functionality for gateway files on auto update</li>
                            <li>All template functions that display a price, also display currency in appropriate format</li>
                            <li>Remove all wp3i_currency() calls from  your custom invoice and email templates.</li>
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