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

//This is what they use with the search results...
//#petfocus_0=&resultSort_0=animalUpdatedDate&resultOrder_0=desc&page_0=1&searchString_0=lily&action_0=search&animalID=undefined

function rg_rescue( $atts ) {
    $search_json = '';

    $atts = array_change_key_case((array)$atts, CASE_LOWER);
    $atts = shortcode_atts(
        array(
            'show' => 'all', //all, random, single
            'species' => 'cat', //cats, dogs, all
            'status' => 'available', //available, adopted
            'sort' => 'animalName', //animalName, animalUpdatedDate
            'order' => 'desc', //desc, asc
            'display' => 'grid', //grid, list
            'fields' => '["animalID", "animalName", "animalSpecies", "animalBreed", "animalPictures"]',
            'animalid' => '0',
        ), $atts, 'rescue' );

    if(isset($_GET['id'])){
      $output = 'animalid: '.$_GET['id'];
      $output.= '<br/>'.$atts['fields'];
      return $output;
    }
    elseif(isset($_GET['search'])){
      $output = 'search results: '.$_GET['search'];
      $output.= '<br/>'.$atts['fields'];
      return $output;
    }
    elseif($atts['show'] == 'single'){
      $output = 'rescue: '.$atts['show'].' '.$atts['animalid'];
      $output.= '<br/>'.$atts['fields'];
      return $output;
    }
    elseif ($atts['show']=='random'){
      $output = 'rescue: '.$atts['show'].' '.$atts['species'].' '.$atts['status'].' '.$atts['sort'].' '.$atts['order'].' '.$atts['display'];
      $output.= '<br/>'.$atts['fields'];
      return $output;
    }
    else{
      $search_json = '{
        "token": "'.get_option(rg_token).'",
        "tokenHash": "'.get_option(rg_tokenhash).'",
        "objectType": "animals",
        "objectAction": "search",
        "search": {
          "resultStart": 0,
          "resultLimit": 100,
          "resultSort": "'.$atts['sort'].'",
          "filters": [{
            "fieldName": "animalSpecies",
            "operation": "equal",
            "criteria": "'.$atts['species'].'"
          },
          {
            "fieldName": "animalStatus",
            "operation": "equal",
            "criteria": "'.$atts['status'].'"
          }],
          "fields": '.$atts['fields'].'
        }
      }';
    }

    if(isset($_GET['id'])){
      $output.= 'Add display for single pet with more information and fields!';

    } else {
      $json_array = json_decode($search_json);
      $result_array = rg_curl_api($json_array);

      $output.= '<div class="row">';
      foreach($result_array['data'] as $k => $v){
          $output.= '<div class="rg-entry" style="float: left; text-align: center;">';
          $output.= '  <div class="rg-thumbnail">';
          $output.= '    <a href="'.$_SERVER['REQUEST_URI'].'/?id='.$v['animalID'].'">';
          $output.= '      <img src="'.$v['animalPictures'][0]['original']['url'].'" alt="" width="50%">';
          $output.= '    </a>';
          $output.= '  </div>';
          $output.= '  <div class="rg-info">';
          $output.= '    <div>'.$v['animalName'].'</div>';
          $output.= '    <div>'.$v['animalBreed'].'</div>';
          $output.= '  </div>';
          $output.= '  <div style="clear:both;"></div>';
          $output.= '</div>';
      }
      $output.= '</div><!-- End row -->';
    }

    return $output; 

}

function rg_rescue_search( $atts ) {
    $output = "<form method='get' action='".$_SERVER['REQUEST_URI']."'><input type='text' name='search' value='".$_GET['search']."'><input type='submit' value='Search'></form>";
    return $output;
}

add_shortcode( 'rescue', 'rg_rescue' );
add_shortcode( 'rescue-search', 'rg_rescue_search' );

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
		__( 'RescueGroups.org Portal Login Credentials', 'wordpress' ), 
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
        <input type='password' name='rg_settings[rg_password]'>
        <?php

}

function rg_settings_section_callback(  ) { 

	echo __( '<a href="https://rescuegroups.org/manage/login">https://rescuegroups.org/manage/login</a>', 'wordpress' );

}


function rg_curl_api($array_data){
        $url = "https://api.rescuegroups.org/http/";
        $data_string = json_encode($array_data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        $result_array = json_decode($result, True);
	return $result_array;
}


function rg_options_page(  ) { 

	if(get_option('rg_token') == null){
  		$options = get_option('rg_settings');
		$rg_account = $options['rg_account'];
		$rg_username = $options['rg_username'];
	        $rg_password = $options['rg_password'];

		$get_token_array = array('accountNumber' => $rg_account, 'username' => $rg_username, 'password' => $rg_password, 'action' => 'login');
		$result_array = rg_curl_api($get_token_array);

                if ($result_array['status'] == 'error'){
			echo '<div class="notice notice-error"><p>'.$result_array['message'].'</p></div>';
		} else {
			update_option('rg_token', $result_array['data']['token']);
		        update_option('rg_tokenhash', $result_array['data']['tokenHash']);
		}
	}

	?>
	<form action='options.php' method='post'>

		<h2>RescueGroups.org Plugin v2</h2>

        	Token: <?php echo get_option('rg_token'); ?><br/><br/>

		Use a non-admin, volunteer level account below to generate a secure token and tokenhash.

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
        <hr/>
	<a href='https://github.com/bderstine/RescueGroups.org-Plugin-v2/issues' target='_blank'>Click here</a> to report any issues or problems you have with the plugin!
	<?php

}

?>
