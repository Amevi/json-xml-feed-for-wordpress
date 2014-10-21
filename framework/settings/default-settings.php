<?php
/**
 * WordPress Settings Framework
 *
 * @author Gilbert Pellegrom
 * @link https://github.com/gilbitron/WordPress-Settings-Framework
 * @license MIT
 */

/**
 * Define your settings
 */
add_filter( 'wpsf_register_settings', 'wpsf_example_settings' );
function wpsf_example_settings( $wpsf_settings ) {

    // General Settings section
    $wpsf_settings[] = array(
        'section_id' => 'general',
        'section_title' => 'Customize yours feeds as you wish by specifying custom links and number of posts to display',
        'section_description' => '',
        'section_order' => 5,
        'fields' => array(
            array(
                'id' => 'json_feed_link',
                'title' => 'Json Feed',
                'desc' => 'Link for JSON feed. For example "/json-feed" will return "'.get_site_url().'/json-feed"',
                'placeholder' => '/json-feed',
                'type' => 'text',
                'std' => '/json-feed'
            ),

            array(
                'id' => 'json_feed_number',
                'title' => 'How many posts?',
                'desc' => 'Number of posts to display in JSON feed',
                'type' => 'select',
                'std' => '10',
                'choices' => array(
                    '5' => '5',
                    '10' => '10',
                    '20' => '20',
                    '50' => '50',
                    '50' => '50',
                    '100' => '100'
                )
            ),
           array(
                'id' => 'xml_feed_link',
                'title' => 'Xml Feed',
                'desc' => 'Link for Xml feed. For example "/xml-feed" will return "'.get_site_url().'/xml-feed"',
                'placeholder' => '/xml-feed',
                'type' => 'text',
                'std' => '/xml-feed'
            ),

            array(
                'id' => 'xml_feed_number',
                'title' => 'How many posts?',
                'desc' => 'Number of posts to display in XML feed',
                'type' => 'select',
                'std' => '10',
                'choices' => array(
                    '5' => '5',
                    '10' => '10',
                    '20' => '20',
                    '50' => '50',
                    '50' => '50',
                    '100' => '100'
                )
            )
        )
    );

    return $wpsf_settings;
}
