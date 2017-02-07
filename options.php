<div class="wrap">
<h2>RescueGroups.org Plugin Settings</h2>

<form method="post" action="options.php">
<?php settings_fields('rg_options_group'); ?>
<?php do_settings_sections('rg_options_group'); ?>

Token: <?php echo get_option('rg_token'); ?><br/>
TokenHash: <?php echo get_option('rg_tokenhash'); ?><br/><br/>


Use the following form to update the token and tokenhash.
<table class="form-table">
  <tr valign="top">
    <th scope="row">Account:</th>
    <td>
      <input type="text" name="rg_account" value="<?php echo get_option('rg_account'); ?>" />
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Username:</th>
    <td>
      <input type="text" name="rg_username" value="<?php echo get_option('rg_username'); ?>" />
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Password:</th>
    <td>
      <input type="password" name="rg_password" />
    </td>
  </tr>
</table>
<?php submit_button(); ?>

</form>
</div>
