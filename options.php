<div class="wrap">
<h2>RescueGroups.org Plugin Settings</h2>

<form method="post" action="options.php">
<?php settings_fields('rg_options_group'); ?>
<?php do_settings_sections('rg_options_group'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row">Available Key:</th>
<td>
  <input type="text" name="rg_avail_key" value="<?php echo get_option('rg_avail_key'); ?>" /> Use shortcode: <strong>[rg-available]</strong> on a page to display.
</td>
</tr>

<tr valign="top">
<th scope="row">Adopted Key:</th>
<td>
  <input type="text" name="rg_adopt_key" value="<?php echo get_option('rg_adopt_key'); ?>" /> Use shortcode: <strong>[rg-adopted]</strong> on a page to display.
</td>
</tr>

</table>

<?php submit_button(); ?>
</form>

The toolkit keys can be generated and managed at:<br/>
<a href="https://manage.rescuegroups.org/toolkits" target="_blank">https://manage.rescuegroups.org/toolkits</a><br/>
<br/>
More info about RescueGroups.org Toolkit:<br/>
<a href="https://www.rescuegroups.org/services/pet-adoption-toolkit/" target="_blank">https://www.rescuegroups.org/services/pet-adoption-toolkit/</a><br/>
<br/>
Documentation about generating keys and using toolkit:<br/>
<a href="https://userguide.rescuegroups.org/x/94IO" target="_blank">https://userguide.rescuegroups.org/x/94IO</a><br/>
<br/>
<strong>Don't mix available and adopted pets!</strong><br/>
Generate 2 keys, one for each type (available and adopted), and display them separate so there is no confusion!<br/>
Update the availability status of each animal in your pet portal and those changes will dynamically show up on your WordPress site.
</div>