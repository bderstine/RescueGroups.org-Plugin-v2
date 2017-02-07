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

add_filter('the_posts', 'check_for_rg_rescue'); // the_posts gets triggered before wp_head
function check_for_rg_rescue($posts){
	if (empty($posts)) return $posts;
 
	$shortcode_found = false; // use this flag to see if styles and scripts need to be enqueued
	foreach ($posts as $post) {
		if (stripos($post->post_content, '[rescue]') !== false) {
			$shortcode_found = true; // bingo!
			break;
		}
	}
 
	return $posts;
}

function rg_rescue() {
	$output = "<!--
		Provided by RescueGroups.org completely free of cost,
		commitment, external links or advertisements
		http://www.rescuegroups.org
		-->
		<!-- End Pet Adoption Toolkit -->";

	return $output;
}

add_shortcode('rescue', 'rg_rescue');

// Adding the functions for the admin menu options
function rg_plugin_menu() {
	add_options_page('RescueGroups Settings', 'RescueGroups.org', 'manage_options', 'rg-unique-identifier', 'rg_options_page');
}

function rg_options_page() {
	include(WP_PLUGIN_DIR.'/RescueGroups.org-Plugin-v2/options.php');  
}

function register_rg_avail_key_settings() {
	register_setting('rg_options_group', 'rg_token'); 
	register_setting('rg_options_group', 'rg_tokenhash'); 
        register_setting('rg_options_group', 'rg_account');
        register_setting('rg_options_group', 'rg_username');
} 

add_action('admin_menu', 'rg_plugin_menu');
add_action('admin_init', 'register_rg_avail_key_settings');

?>
