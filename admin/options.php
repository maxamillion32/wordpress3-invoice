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
                    <th scope="row"><label>Currency Symbol</label><span>This is used throughout the plugin</span></th>
                    <td><input name="wp3i_currency" value="<?php wp3i_currency(); ?>" type="text" size="1" maxlength="3"> </td>
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
            </table>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="wp3i_currency, wp3i_tax, wp3i_emailrecipients, wp3i_permalink, wp3i_content_editor, wp3i_email" />
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
		</div>
	</div>
	<?php
	}
}
?>