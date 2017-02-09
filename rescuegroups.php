<?php
/*
Plugin Name: RescueGroups.org Plugin v2
Plugin URI: https://github.com/bderstine/RescueGroups.org-Plugin-v2
Description: This plugin provides API integration support for RescueGroups.org
Version: 2.0
Author: Brad Derstine
Author URI: http://bizzartech.com
License: GPL2
*/

function rg_rescue() {
        $args = array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode( YOUR_USERNAME . ':' . YOUR_PASSWORD )
            )
        );
        $response = wp_remote_post( 'https://api.rescuegroups.org/http/', $args );

        $output = "It worked! 
                Provided by RescueGroups.org completely free of cost,
                commitment, external links or advertisements
                http://www.rescuegroups.org
                <!-- End Pet Adoption Toolkit -->";

        return $output;
}

add_shortcode('rescue', 'rg_rescue');

#######################################################
## The following will add the admin menu and generate the options page

add_action( 'admin_menu', 'rg_add_admin_menu' );
add_action( 'admin_init', 'rg_settings_init' );


function rg_add_admin_menu(  ) { 

        add_options_page('RescueGroups Settings', 'RescueGroups.org', 'manage_options', 'rescuegroups.org_plugin_v2', 'rg_options_page');

}


function rg_settings_init(  ) { 

	register_setting( 'pluginPage', 'rg_settings' );

	add_settings_section(
		'rg_pluginPage_section', 
		__( 'Your section description', 'wordpress' ), 
		'rg_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'rg_account', 
		__( 'RescueGroups.org Account', 'wordpress' ), 
		'rg_text_field_0_render', 
		'pluginPage', 
		'rg_pluginPage_section' 
	);

	add_settings_field( 
		'rg_username', 
		__( 'RescueGroups.org Username', 'wordpress' ), 
		'rg_text_field_1_render', 
		'pluginPage', 
		'rg_pluginPage_section' 
	);

	add_settings_field( 
		'rg_password', 
		__( 'RescueGroups.org Password', 'wordpress' ), 
		'rg_text_field_2_render', 
		'pluginPage', 
		'rg_pluginPage_section' 
	);

}


function rg_text_field_0_render(  ) { 

	$options = get_option( 'rg_settings' );
	?>
	<input type='text' name='rg_settings[rg_account]' value='<?php echo $options['rg_account']; ?>'>
	<?php

}


function rg_text_field_1_render(  ) { 

	$options = get_option( 'rg_settings' );
	?>
	<input type='text' name='rg_settings[rg_username]' value='<?php echo $options['rg_username']; ?>'>
	<?php

}

function rg_text_field_2_render(  ) {

        $options = get_option( 'rg_settings' );
        ?>
        <input type='text' name='rg_settings[rg_password]' value='<?php echo $options['rg_password']; ?>'>
        <?php

}

function rg_settings_section_callback(  ) { 

	echo __( 'This section description', 'wordpress' );

}


function rg_options_page(  ) { 

	?>
	<form action='options.php' method='post'>

		<h2>RescueGroups.org Plugin v2</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}

?>
