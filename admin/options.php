<?php 

/*--------------------------------------------------------------------------------------------
									Options Page
--------------------------------------------------------------------------------------------*/
function wp3i_options()
{
	?>

<div class="wrap" id="wp3i-options"> 
    <!--<div class="icon32" id="icon-wp3i"><br></div>-->
    <h2>WP3 Invoice Options</h2>
    <div id="poststuff">
        <div class="left-column">
            <div class="postbox">
                <h3 class="hndle"><span>WordPress 3 Invoice Options</span></h3>
                <div class="inside">
                    <form method="post" action="options.php" >
                        <?php wp_nonce_field('update-options'); ?>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="width:193px;"><label>Currency</label>
                                    <p>Enter your currency Symbol</p></td>
                                <td><input name="wp3i_currency" value="<?php wp3i_currency(); ?>" type="text" size="1" maxlength="3"></td>
                            </tr>
                            <tr>
                                <td><label>Tax</label>
                                    <p>Enter Tax Amount (5% = .05)</p></td>
                                <td><input name="wp3i_tax" value="<?php wp3i_tax(); ?>" type="text" size="2" maxlength="5"></td>
                            </tr>
                            <tr>
                                <td><label>Email</label>
                                    <p>Select invoice recipients</p></td>
                                <td><input name="wp3i_emailrecipients" type="radio" value="client" <?php if(wp3i_get_emailrecipients() == 'client'){echo'checked="checked"';} ?>>
                                    Send Invoice to Client Only <br />
                                    <input name="wp3i_emailrecipients" type="radio" value="both" <?php if(wp3i_get_emailrecipients() == 'both'){echo'checked="checked"';} ?>>
                                    Send Invoice to Client &amp; Me (<a href="profile.php">see Profile</a>)</td>
                            </tr>
                        </table>
                        <input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="wp3i_currency, wp3i_tax, wp3i_emailrecipients" />
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
                        <tr>
                            <td><h4>invoice_type()</h4></td>
                            <td><p>Displays the current invoice type (Invoice or Quote)</p></td>
                        </tr>
                        <tr>
                            <td><h4>get_invoice_type()</h4></td>
                            <td><p>Returns the current invoice type (Invoice or Quote)</p></td>
                        </tr>
                        <tr>
                            <td><h4>invoice_number()</h4></td>
                            <td><p>Displays the current invoice number</p></td>
                        </tr>
                        <tr>
                            <td><h4>bloginfo('name')</h4></td>
                            <td><p>Displays the name of your blog</p></td>
                        </tr>
                        <tr>
                            <td><h4>the_title()</h4></td>
                            <td><p>Displays the current invoice title</p></td>
                        </tr>
                        <tr>
                            <td><h4>invoice_template_url</h4></td>
                            <td><p>Displays the url of your invoice template folder.<br />
                                    This function will first look in your WordPress theme folder for a folder called 'invoice' and a file named 'invoice.php'. If no folder or file is found, the default template files (found in the plugin template folder) will be used</p></td>
                        </tr>
                        <tr>
                            <td><h4>invoice_client()</h4></td>
                            <td><p>Displays the client name for the current invoice</p></td>
                        </tr>
                        <tr>
                            <td><h4>the_time('d/m/Y')</h4></td>
                            <td><p>Displays the date when the invoice was created. (Not when it was sent or paid) <a href="http://codex.wordpress.org/Formatting_Date_and_Time">Read more about formating date and time here</a></p></td>
                        </tr>
                        <tr>
                            <td><h4>if(invoice_has_details()):</h4></td>
                            <td><p>Loads the details into an array to use in the detail loop</p></td>
                        </tr>
                        <tr>
                            <td><h4>while(invoice_detail()):</h4></td>
                            <td><p>Loops through the details for the current invoice</p></td>
                        </tr>
                        <tr>
                            <td><h4>the_detail_title()</h4></td>
                            <td><p>Displays the detail title</p></td>
                        </tr>
                        <tr>
                            <td><h4>the_detail_type()</h4></td>
                            <td><p>Displays the detail type</p></td>
                        </tr>
                        <tr>
                            <td><h4>the_detail_rate()</h4></td>
                            <td><p>Displays the detail rate</p></td>
                        </tr>
                        <tr>
                            <td><h4>the_detail_duration()</h4></td>
                            <td><p>Displays the detail duration</p></td>
                        </tr>
                        <tr>
                            <td><h4>the_detail_subtotal()</h4></td>
                            <td><p>Displays the detail subtotal</p></td>
                        </tr>
                        <tr>
                            <td><h4>wp3i_currency()</h4></td>
                            <td><p>Displays the chosen currency</p></td>
                        </tr>
                        <tr>
                            <td><h4>wp3i_has_tax()</h4></td>
                            <td><p>Returns true if tax is set above 0.00</p></td>
                        </tr>
                        <tr>
                            <td><h4>the_invoice_subtotal()</h4></td>
                            <td><p>Displays the invoice subtotal amount</p></td>
                        </tr>
                        <tr>
                            <td><h4>the_invoice_tax()</h4></td>
                            <td><p>Displays the amount off tax (subtotal * tax)</p></td>
                        </tr>
                        <tr>
                            <td><h4>the_invoice_total()</h4></td>
                            <td><p>Displays the current invoice total (subtotal + tax)</p></td>
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
                </div>
            </div>
            <div class="postbox" id="new">
                <h3 class="hndle"><span>New in 1.0.8</span></h3>
                <div class="inside">
                    <p>Version 1.0.8 should fix email problems.<br />
                    Also, I'm proud to anounce the opening of the <a href="http://www.wordpress3invoice.com">WordPress 3 Invoice website</a>!</p>
                    <ul>
                    	<li>Removed Content editor from invoices.</li>
                    	<li>Invoice templates don't use the_content() anymore.</li>
                    	<li>Fixed Email Issues by removing fopen functionality!</li>
                        <li>New website for support, help and ideas.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

?>
