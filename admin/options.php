<?php 

/*--------------------------------------------------------------------------------------------
									Options Page
--------------------------------------------------------------------------------------------*/
function wp3i_options()
{
	?>
    
    
    <div class="wrap">
    	<!--<div class="icon32" id="icon-wp3i"><br></div>-->
    	<h2>WP3 Invoice Options</h2>
        
        <div id="poststuff">
        	<div class="postbox">
            	<h3 class="hndle"><span>Do you like this plugin?</span></h3>
                <div class="inside">
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="U8VV93Z7WXYJA">
                        <input type="image" src="https://www.paypal.com/en_AU/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
                        <img alt="" border="0" src="https://www.paypal.com/en_AU/i/scr/pixel.gif" width="1" height="1">
                    </form>
                </div>
        	</div>
                
            <form method="post" action="options.php" id="wp3i-options">
				<?php wp_nonce_field('update-options'); ?>
   
                <div class="postbox">
                    <h3 class="hndle"><span>WordPress 3 Invoice Options</span></h3>
                    <div class="inside">
                        <table cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td style="width:193px;"><label>Currency</label><p>Enter your currency Symbol</p></td><td><input name="wp3i_currency" value="<?php wp3i_currency(); ?>" type="text" size="1" maxlength="3"></td>
                            </tr>
                            <tr>
                            	<td><label>Tax</label><p>Enter Tax Amount (5% = .05)</p></td><td><input name="wp3i_tax" value="<?php wp3i_tax(); ?>" type="text" size="2" maxlength="5"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="page_options" value="wp3i_currency, wp3i_tax" />
                
                <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>

				<div class="postbox">
                    <h3 class="hndle"><span>WordPress 3 Invoice Template API</span></h3>
                    <div class="inside">
                        <table cellpadding="0" cellspacing="0" class="api"> 
                        	<tr><td><h4>invoice_number()</h4></td><td><p>Displays the current invoice number</p></td></tr>
                            <tr><td><h4>bloginfo('name')</h4></td><td><p>Displays the name of your blog</p></td></tr>
                            <tr><td><h4>the_title()</h4></td><td><p>Displays the current invoice title</p></td></tr>
                            <tr><td><h4>invoice_template_url</h4></td><td><p>Displays the url of your invoice template folder.<br />This function will first look in your WordPress theme folder for a folder called 'invoice' and a file named 'invoice.php'. If no folder or file is found, the default template files (found in the plugin template folder) will be used</p></td></tr>
                            <tr><td><h4>invoice_client()</h4></td><td><p>Displays the client name for the current invoice</p></td></tr>
                            <tr><td><h4>the_time('d/m/Y')</h4></td><td><p>Displays the date when the invoice was created. <a href="http://codex.wordpress.org/Formatting_Date_and_Time">Read more about formating date and time here</a></p></td></tr>
                         	<tr><td><h4>if(invoice_has_details()):</h4></td><td><p>Loads the details into an array to use in the detail loop</p></td></tr>
                            <tr><td><h4>while(invoice_detail()):</h4></td><td><p>Loops through the details for the current invoice</p></td></tr>
                            <tr><td><h4>the_detail_title()</h4></td><td><p>Displays the detail title</p></td></tr>
                            <tr><td><h4>the_detail_type()</h4></td><td><p>Displays the detail type</p></td></tr>
                            <tr><td><h4>the_detail_rate()</h4></td><td><p>Displays the detail rate</p></td></tr>
                            <tr><td><h4>the_detail_duration()</h4></td><td><p>Displays the detail duration</p></td></tr>
                            <tr><td><h4>the_detail_subtotal()</h4></td><td><p>Displays the detail subtotal</p></td></tr>
                            <tr><td><h4>wp3i_currency()</h4></td><td><p>Displays the chosen currency</p></td></tr>
                            <tr><td><h4>the_invoice_subtotal()</h4></td><td><p>Displays the invoice subtotal amount</p></td></tr>
                            <tr><td><h4>the_invoice_tax()</h4></td><td><p>Displays the amount off tax (subtotal * tax)</p></td></tr>
                            <tr><td><h4>the_invoice_total()</h4></td><td><p>Displays the current invoice total (subtotal + tax)</p></td></tr>
                        </table>
                    </div>
                </div>
			</form>
            
            	
            
        </div>
    </div>
<?php
}

?>