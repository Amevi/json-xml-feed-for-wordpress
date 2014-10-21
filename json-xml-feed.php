<?php
/*
Plugin Name: JSON & XML Feed for Wordpress
Author: Samuel Guebo
Author URI: http://samuelguebo.com
Description: Wordpress plugin for generating JSON & XML feed for wordpress posts
Version: 1.0
*/

add_action('template_redirect', 'template_redirect');

//Redirect to a preferred template.
function template_redirect() {

	// Get settings
	$json_feed_link = wpsf_get_setting( 'json_xml_feed_settings','general','json_feed_link' );
	$json_feed_number = wpsf_get_setting( 'json_xml_feed_settings','general','json_feed_number' );

	$xml_feed_link = wpsf_get_setting( 'json_xml_feed_settings','general','xml_feed_link' );
	$xml_feed_number = wpsf_get_setting( 'json_xml_feed_settings','general','xml_feed_number' );

	if (empty($json_feed_link)) { $json_feed_link = "/json-feed";}	
	if ($json_feed_number<0|| $json_feed_number>100 ) { $json_feed_number = 10;}	
	if (empty($xml_feed_link)) { $xml_feed_link = "/xml-feed";	}
	if ($xml_feed_number<0|| $xml_feed_number>100 ) { $xml_feed_number = 10;}	

	$json_url =get_bloginfo('url').$json_feed_link;
	$rss_url =get_bloginfo('url').$xml_feed_link;
	$permalink = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];	

		if( $permalink===$json_url) {
			json_template($json_feed_number);
			
	   
		}else if ($permalink===$rss_url){
			rss_template($xml_feed_number);
		}
	
	
}
function rss_template($xml_feed_number) {
	$template_path = dirname( __FILE__ ) . '/templates/rss-template.php';
	if(file_exists($template_path)){
		include($template_path);
	exit;
		}
}
function json_template($json_feed_number) {
	
	$template_path = dirname( __FILE__ ) . '/templates/json-template.php';
	if(file_exists($template_path)){
		include($template_path);
	exit;
	}
}

// include WP Settings Framework from Gilbert Pellegrom
include 'framework/wpsf.php';
