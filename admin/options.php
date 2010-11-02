<?php 
class Options
{
	var $name;
	var $dir;
	var $plugin_dir;
	var $plugin_path;
	var $version;

	/**
	 * Options Constructor
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 * @param object: Wp3i to find parent variables.
	 **/
	function Options($parent)
	{
		$this->name = $parent->name;					// Plugin Name
		$this->plugin_dir = $parent->dir;				// Plugin directory
		$this->plugin_path = $parent->path;				// Plugin Absolute Path
		$this->dir = plugins_url('/',__FILE__);			// This directory
		$this->version = $parent->version;
		return true;	
	}
	
	/**
	 * Options Admin Page
	 *
	 * @author Elliot Condon
	 * @since 2.0.0
	 * 
	 **/
	function admin_page()
	{
		?>
	
	<div class="wrap" id="wp3i-options"> 
        <div class="wp3i-heading">
            <div class="icon32" id="icon-wp3i"><br></div>
            <h2>WordPress 3 Invoice Options</h2>
            <p>Version <?php echo $this->version; ?></p>
        </div>
        
        <div id="poststuff">
        <form method="post" action="options.php" >
            <?php wp_nonce_field('update-options'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label>Currency</label><span>This is used throughout the plugin</span></th>
                    <td>
                    <select name="wp3i_currency">
                    <option value="Select a Currency">Select a Currency</option>
                    <?php foreach(wp3i_get_countries() as $key => $value): ?>
                    	<option value="<?php echo $key; ?>" <?php if(wp3i_get_currency() == $key){echo 'selected="selected"'; } ?> >
						<?php echo $value['name']; ?> (<?php echo $value['currency']['code']; ?>)
                        </option>
                    <?php endforeach; ?>
                    </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label>Tax</label><span>Enter Tax Amount (5% = .05)</span></th>
                    <td><input name="wp3i_tax" value="<?php wp3i_tax(); ?>" type="text" size="2" maxlength="5"> </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label>Send Invoice</label><span>Select invoice recipients</span></th>
                    <td><input name="wp3i_emailrecipients" type="radio" value="client" <?php if(wp3i_get_emailrecipients() == 'client'){echo'checked="checked"';} ?>>
                        Send Invoice to Client Only <br />
                        <input name="wp3i_emailrecipients" type="radio" value="both" <?php if(wp3i_get_emailrecipients() == 'both'){echo'checked="checked"';} ?>>
                        Send Invoice to Client &amp; Me (<a href="profile.php">see Profile</a>)</td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label>Permalinks</label><span> Encoded is more secure</span></th>
                    <td><input name="wp3i_permalink" type="radio" value="encoded" <?php if(wp3i_get_permalink() == 'encoded'){echo'checked="checked"';} ?>>
                        Encoded <br />
                        <input name="wp3i_permalink" type="radio" value="standard" <?php if(wp3i_get_permalink() == 'standard'){echo'checked="checked"';} ?>>
                        Standard</td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label>Content Editor</label><span>Add content to your invoice</span></th>
                    <td><input name="wp3i_content_editor" type="radio" value="enabled" <?php if(wp3i_get_content_editor() == 'enabled'){echo'checked="checked"';} ?>>
                        Enabled <br />
                        <input name="wp3i_content_editor" type="radio" value="disabled" <?php if(wp3i_get_content_editor() == 'disabled'){echo'checked="checked"';} ?>>				
                        Disabled 
                        </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label>Email</label><span>Appears as "sent from" in emails</span></th>
                    <td><input name="wp3i_email" value="<?php wp3i_email(); ?>" type="text" size="30"> </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label>Payment Gateway</label><span>Let clients pay invoice's online</span></th>
                    <td>
                    <select name="wp3i_payment_gateway" style="float:left; margin-right:10px;">
                    <option value="None">None</option>
                    <?php foreach($this->get_payment_gateways() as $gateway): ?>
                    	<option value="<?php echo $gateway; ?>" <?php if(wp3i_get_payment_gateway() == $gateway){echo 'selected="selected"'; } ?> ><?php echo $gateway; ?></option>
                    <?php endforeach; ?>
                    </select>
                    <div class="none" style="float:left; margin-right:10px;">
                    	<span class="description show"><a href="http://www.wordpress3invoice.com/shop/">Purchase a payment gateway</a> and upload it to the wordpress3-invoice/gateway/ folder</span>
                    </div>
                    <div class="account" style="float:left; margin-right:10px;">
                    	<input name="wp3i_payment_gateway_account" value="<?php wp3i_payment_gateway_account(); ?>" type="text" size="30"> <br />
                    	<label for="wp3i_payment_gateway_account">Enter your account email.</label>
                    </div>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="wp3i_currency, wp3i_tax, wp3i_emailrecipients, wp3i_permalink, wp3i_content_editor, wp3i_email, wp3i_payment_gateway, wp3i_payment_gateway_account" />
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
		</div>
	</div>
    <script type="text/javascript">
		jQuery(document).ready(function($){
			var payment_gateway_select = $('select[name=wp3i_payment_gateway]');
			function wp3i_payment_gateway_switch()
			{
				if(payment_gateway_select.attr('value') == 'None')
				{
					payment_gateway_select.siblings('.none').show();
					payment_gateway_select.siblings('.account').hide();	
				}
				else
				{
					payment_gateway_select.siblings('.none').hide();
					payment_gateway_select.siblings('.account').find('label').html('Enter your '+payment_gateway_select.attr('value')+' account email');
					payment_gateway_select.siblings('.account').show();	
				}
			}
			payment_gateway_select.change(function(){
				wp3i_payment_gateway_switch();
			});
			wp3i_payment_gateway_switch();

		});
	</script>
	<?php
	}
	
	/**
	 * Get Payment Gateways
	 *
	 * @author Elliot Condon
	 * @since 2.0.1
	 *
	 **/
	function get_payment_gateways()
	{
		$plugins = array();
		$gateways_path = $this->plugin_path.'gateways/';
		
		$files = array_diff(scandir($gateways_path), array('.', '..')); 
		if($files)
		{
			foreach($files as $file)
			{
				if(is_dir($gateways_path.$file)){break;}							// cancel out the folders
				$file_contents = file_get_contents($gateways_path.$file);			// 1. Reads file
				preg_match( '|@class (.*)$|mi', $file_contents, $matches);			// 2. Finds Temaplte Name, stores in $matches
				if(!empty($matches[1]))
				{
					$plugins[] = $matches[1]; 											// 3. Adds array ([name] => array(path, dir)) 
				}
			}
		}
		return $plugins;
	}
	
}
?>