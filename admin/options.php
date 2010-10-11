<?php 

/*--------------------------------------------------------------------------------------------
									Options Page
--------------------------------------------------------------------------------------------*/
function wp3i_options()
{
	?>

<div class="wrap" id="wp3i-options"> 
    <h2>WordPress 3 Invoice Options</h2>
    <div id="poststuff">
        <div class="left-column">
            <div class="postbox">
                <h3 class="hndle"><span>Global Options</span></h3>
                <div class="inside">
                    <form method="post" action="options.php" >
                        <?php wp_nonce_field('update-options'); ?>
                        <table cellpadding="0" cellspacing="0" class="api" width="100%">
                            <tr class="odd">
                                <td style="width:220px;"><label>Currency</label>
                                    <h6>Enter your currency Symbol</h6></td>
                                <td><input name="wp3i_currency" value="<?php wp3i_currency(); ?>" type="text" size="1" maxlength="3"></td>
                            </tr>
                            <tr>
                                <td><label>Tax</label>
                                    <h6>Enter Tax Amount (5% = .05)</h6></td>
                                <td><input name="wp3i_tax" value="<?php wp3i_tax(); ?>" type="text" size="2" maxlength="5"></td>
                            </tr>
                            <tr class="odd">
                                <td><label>Email</label>
                                    <h6>Select invoice recipients</h6></td>
                                <td><input name="wp3i_emailrecipients" type="radio" value="client" <?php if(wp3i_get_emailrecipients() == 'client'){echo'checked="checked"';} ?>>
                                    Send Invoice to Client Only <br />
                                    <input name="wp3i_emailrecipients" type="radio" value="both" <?php if(wp3i_get_emailrecipients() == 'both'){echo'checked="checked"';} ?>>
                                    Send Invoice to Client &amp; Me (<a href="profile.php">see Profile</a>)</td>
                            </tr>
                            <tr>
                                <td><label>Permalinks</label>
                                    <h6>Encoded is more secure</h6></td>
                                <td><input name="wp3i_permalink" type="radio" value="encoded" <?php if(wp3i_get_permalink() == 'encoded'){echo'checked="checked"';} ?>>
                                    Encoded <br />
                                    <input name="wp3i_permalink" type="radio" value="standard" <?php if(wp3i_get_permalink() == 'standard'){echo'checked="checked"';} ?>>
                                    Standard</td>
                            </tr>
                            <tr class="odd">
                                <td><label>Content Editor</label>
                                    <h6>Add content to your invoice.</h6></td>
                                <td><input name="wp3i_content_editor" type="radio" value="enabled" <?php if(wp3i_get_content_editor() == 'enabled'){echo'checked="checked"';} ?>>
                                    Enabled <br />
                                    <input name="wp3i_content_editor" type="radio" value="disabled" <?php if(wp3i_get_content_editor() == 'disabled'){echo'checked="checked"';} ?>>
                                    Disabled</td>
                            </tr>
                            <tr>
                                <td style="width:193px;"><label>Email</label>
                                    <h6>Enter your email address</h6></td>
                                <td><input name="wp3i_email" value="<?php wp3i_email(); ?>" type="text" size="30"></td>
                            </tr>
                        </table>
                        <input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="wp3i_currency, wp3i_tax, wp3i_emailrecipients, wp3i_permalink, wp3i_content_editor, wp3i_email" />
                        <p class="submit">
                            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                        </p>
                    </form>
                </div>
            </div>
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
                            <td><h4>wp3i_currency()</h4><h6>+ get_wp3i_currency()</h6></td>
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
            <div class="postbox" id="new">
                <h3 class="hndle"><span>Say Thankyou.</span></h3>
                <div class="inside">
                	<table cellpadding="10" cellspacing="0">
                    <tr>
                    <td>
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="U8VV93Z7WXYJA">
                        <input type="image" src="<?php echo plugins_url('',__FILE__); ?>/images/options-donate.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
                        <img alt="" border="0" src="https://www.paypal.com/en_AU/i/scr/pixel.gif" width="1" height="1">
                    </form>
                    </td>
                    <td>
                    	<a href="http://wordpress.org/extend/plugins/wordpress3-invoice/">
                    		<img src="<?php echo plugins_url('',__FILE__); ?>/images/options-rate-it.gif" alt="Rate wp3i" />
                        </a>
                    </td>
                    </tr>
                    </table>
                    <p>Hi guys. If you like the wp3i plugin, let me know. <br />
                    Donate $1 or just leave a comment on the website. <br />
                    Happy invoiceing.</p>
                    <ul>
                    	<li><a href="http://www.wordpress3invoice.com/tour/">Tutorials</a></li>
                    	<li><a href="http://www.wordpress3invoice.com/support/">Support / FAQ.</a></li>
                    	<li><a href="http://www.wordpress3invoice.com/developers/">Developer Community</a></li>
                    </ul>
                    <p>P.S. It would be great to see what you have done with the template files. <br />
                    Take a screenshot and comment a link to it on the developer page.</p>
                </div>
            </div>
            <div class="postbox" id="new">
                <h3 class="hndle"><span>New in 1.1.2</span></h3>
                <div class="inside">
                    <ul>
                    	<li>Sexier Stats Page</li>
                        <li>Sexier Invoice edit Page</li>
                        <li>New Option: Email address (Emails appear sent from x)</li>
                        <li>Fixed Email template bug (well, one of many...)</li>
                    </ul>
                    <p>Thank you to Jeremy Edmiston for his work on the stats page.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

?>
