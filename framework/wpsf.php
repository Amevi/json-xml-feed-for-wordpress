<?php
class WPSFTest {

    private $plugin_path;
    private $wpsf;

    function __construct()
    {
        $this->plugin_path = dirname( __FILE__ );
        add_action( 'admin_menu', array(&$this, 'jxf_admin_menu'), 99 );

        // Include and create a new WordPressSettingsFramework
        require_once( $this->plugin_path .'/wp-settings-framework.php' );
        $this->wpsf = new WordPressSettingsFramework( $this->plugin_path .'/settings/default-settings.php', 'json_xml_feed_settings' );
        // Add an optional settings validation filter (recommended)
        add_filter( $this->wpsf->jxf_get_option_group() .'_settings_validate', array(&$this, 'jxf_validate_settings') );
    }

    function jxf_admin_menu()
    {
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        $page_hook = add_menu_page( __( 'JSON & XML Feed', 'wp-settings-framework' ), __( 'JSON & XML', 'wp-settings-framework' ), 'update_core', 'json-xml-feed', array(&$this, 'jxf_settings_page'),plugin_dir_url( __FILE__ ).'/feed.png' );
        add_submenu_page( 'json-xml-feed', __( 'Settings', 'wp-settings-framework' ), __( 'Settings', 'wp-settings-framework' ), 'update_core', 'json-xml-feed', array(&$this, 'jxf_settings_page') );
    }

    function jxf_settings_page()
	{
	    // Your settings page
	    ?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2>JSON & XML Feed Settings</h2>
			<?php
			// Output your settings form
			$this->wpsf->settings();
			?>
		</div>
		<?php

		// Get settings
	}

	function jxf_validate_settings( $input )
	{
	    // Do your settings validation here
	    // Same as $sanitize_callback from http://codex.wordpress.org/Function_Reference/register_setting
    	return $input;
	}

}
new WPSFTest();

?>
